<?php

defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('item_deleted', 'delete_pc_items_extra');
hooks()->add_action('item_created', 'create_pc_items_extra');
hooks()->add_action('lead_converted_to_customer', 'create_inspectie_rapport_task');
hooks()->add_action('estimate_accepted', 'create_offerte_project');
hooks()->add_action('task_status_changed', 'create_next_task');
hooks()->add_action('after_client_added', 'create_inspectie_rapport_for_client');
hooks()->add_action('contact_created', 'create_inspectie_rapport_for_contact');

function delete_pc_items_extra($itemid)
{
    $CI = &get_instance();

    $CI->db->where('item_id', $itemid);
    $items_extra = $CI->db->get(db_prefix() . 'pc_items_extra')->row();

    if ($items_extra) {
        $CI->db->where('item_id', $itemid);
        $CI->db->delete(db_prefix() . 'pc_items_extra');

        if ($CI->db->affected_rows() > 0) {
            log_activity(db_prefix() . 'pc_items_extra' . ' deleted[ID:' . $items_extra->id . ', Staff id ' . get_staff_user_id() . ']');
        }
    }
}

function create_pc_items_extra($itemid)
{
    $CI = &get_instance();

    $CI->db->where('item_id', $itemid);
    $items_extra = $CI->db->get(db_prefix() . 'pc_items_extra')->row();

    if (!$items_extra) {
        $CI->db->query("insert into " . db_prefix() . "pc_items_extra (item_id) values(" . $itemid . ");");

        $CI->db->where('item_id', $itemid);
        $items_extra = $CI->db->get(db_prefix() . 'pc_items_extra')->row();

        if ($CI->db->affected_rows() > 0) {
            log_activity(db_prefix() . 'pc_items_extra' . ' created[ID:' . $items_extra->id . ', Staff id ' . get_staff_user_id() . ']');
        }
    }
}

function create_inspectie_rapport_task($hook_object)
{
    $customer_id = $hook_object['customer_id'];
    create_inspectie_task($customer_id);
}

function create_inspectie_rapport_for_client($customer_id)
{
    create_inspectie_task($customer_id);
}

function create_inspectie_rapport_for_contact($contact_id)
{
    $CI = &get_instance();
    $CI->db->where('id', $contact_id);
    $contact = $CI->db->get(db_prefix() . 'contacts')->row();
    create_inspectie_task($contact->userid);
}

/**
 * @param $customer_id
 * @return void
 */
function create_inspectie_task($customer_id): void
{
    $CI = &get_instance();

    $CI->db->where('clientid', $customer_id);
    $inspectie_task = $CI->db->get(db_prefix() . 'pc_inspectie_rapport')->row();
    if (isset($inspectie_task)) {
        return;
    }

    if (get_company_name($customer_id) == '') {
        // we do not have a name yet!
        return;
    }

    $CI->db->where('userid', $customer_id);
    $client = $CI->db->get(db_prefix() . 'clients')->row();
    $tagid = get_tag_id('inspectie_rapport');

    $task = array(
        'name' => 'Inspectie Rapport - ' . get_company_name($customer_id),
        'dateadded' => date('Y-m-d HH:mm:ss'),
        'startdate' => date('Y-m-d'),
        'priority' => 2,
        'status' => 1,
        'repeat_every' => 0
    );

    $CI->db->insert(db_prefix() . "tasks", $task);
    $task_id = $CI->db->insert_id();

    $taggable = array(
        'rel_id' => $task_id,
        'rel_type' => 'task',
        'tag_id' => $tagid,
        'tag_order' => 1,
    );
    $CI->db->insert(db_prefix() . "taggables", $taggable);

    assignTask($task_id, 'assign_task_inspectie_rapport_staffid');

    // create a default inspactie rapport
    $rapport = array(
        'clientid' => $customer_id,
        'taskid' => $task_id
    );

    $CI->db->insert(db_prefix() . "pc_inspectie_rapport", $rapport);
    $rapport_id = $CI->db->insert_id();
    $rapport_child = array(
        'inspectie_rapport_id' => $rapport_id
    );
    $CI->db->insert(db_prefix() . "pc_inspectie_rapport_algemeen", $rapport_child);
    $CI->db->insert(db_prefix() . "pc_inspectie_rapport_elektriciteit", $rapport_child);
    $CI->db->insert(db_prefix() . "pc_inspectie_rapport_info_dak", $rapport_child);
    $CI->db->insert(db_prefix() . "pc_inspectie_rapport_info_pv", $rapport_child);
}

/**
 * @param $task_id
 * @return array
 */
function assignTask($task_id, $tag_name_staffid)
{
    $CI = &get_instance();
    $staffid = get_option($tag_name_staffid);
    if (isset($staffid)) {
        $staffid = is_numeric($staffid) ? $staffid : 1;
    } else {
        $staffid = 1;
    }
    assignTaskToStaffid($task_id, $staffid);

}

/**
 * @param $staffid
 * @param $task_id
 * @param $CI
 * @return void
 */
function assignTaskToStaffid($task_id, $staffid): void
{
    $CI = &get_instance();
    $task_assigned = array(
        'staffid' => $staffid,
        'taskid' => $task_id,
        'assigned_from' => 1,
        'is_assigned_from_contact' => 0
    );
    $CI->db->insert(db_prefix() . "task_assigned", $task_assigned);
}

function create_offerte_project($estimate_id)
{
    $CI = &get_instance();
    $CI->db->where('id', $estimate_id);
    $estimate = $CI->db->get(db_prefix() . 'estimates')->row();
    $customerid = $estimate->clientid;
    $addedfromid = $estimate->addedfrom;

    $CI->db->where('userid', $customerid);
    $client = $CI->db->get(db_prefix() . 'clients')->row();

    $CI->db->where('estimates_id', $estimate_id);
    $estimate_extra = $CI->db->get(db_prefix() . 'pc_estimates_extra')->row();


    $CI->db->where('id', $estimate_extra->estimate_template_id);
    $estimate_template = $CI->db->get(db_prefix() . 'pc_estimate_template')->row();

    $CI->db->where('id', $estimate_template->project_template_id);
    $project_template = $CI->db->get(db_prefix() . 'pc_project_template')->row();

    $CI->db->where('project_template_id', $estimate_template->project_template_id);
    $CI->db->where('task_order', 0);
    $project_template_task = $CI->db->get(db_prefix() . 'pc_project_template_tasks')->row();

    $CI->db->where('id', $project_template_task->instellingen_taak_id);
    $instellingen_taak = $CI->db->get(db_prefix() . 'pc_instellingen_taak')->row();

    $project = [];
    $project['name'] = $project_template->name . ' - ' . get_company_name($client->userid);
    $project['status'] = '1';
    $project['clientid'] = $customerid;
    $project['billing_type'] = '1';
    $project['start_date'] = date('Y-m-d HH:mm:ss');
    $project['project_created'] = date('Y-m-d HH:mm:ss');
    $project['progress_from_tasks'] = '1';
    $project['progress'] = '100';
    $project['project_rate_per_hour'] = '0.00';
    $project['addedfrom'] = $addedfromid;
    $project['contact_notification'] = '1';
    $project['notify_contacts'] = 'a:0:{}';
    $project['project_cost'] = $estimate->total;


    $CI->db->insert(db_prefix() . "projects", $project);
    $project_id = $CI->db->insert_id();

    insert_project_settings($project_id, 'available_features', 'a:16:{s:16:"project_overview";i:1;s:13:"project_tasks";i:1;s:18:"project_timesheets";i:1;s:18:"project_milestones";i:1;s:13:"project_files";i:1;s:19:"project_discussions";i:1;s:13:"project_gantt";i:1;s:15:"project_tickets";i:1;s:17:"project_contracts";i:1;s:16:"project_invoices";i:1;s:17:"project_estimates";i:1;s:16:"project_expenses";i:1;s:20:"project_credit_notes";i:1;s:21:"project_subscriptions";i:1;s:13:"project_notes";i:1;s:16:"project_activity";i:1;}');
    insert_project_settings($project_id, 'view_tasks', '1');
    insert_project_settings($project_id, 'create_tasks', '1');
    insert_project_settings($project_id, 'edit_tasks', '1');
    insert_project_settings($project_id, 'comment_on_tasks', '1');
    insert_project_settings($project_id, 'view_task_comments', '1');
    insert_project_settings($project_id, 'view_task_attachments', '1');
    insert_project_settings($project_id, 'view_task_checklist_items', '1');
    insert_project_settings($project_id, 'upload_on_tasks', '1');
    insert_project_settings($project_id, 'view_task_total_logged_time', '1');
    insert_project_settings($project_id, 'view_finance_overview', '1');
    insert_project_settings($project_id, 'upload_files', '1');
    insert_project_settings($project_id, 'open_discussions', '0');
    insert_project_settings($project_id, 'view_milestones', '1');
    insert_project_settings($project_id, 'view_gantt', '0');
    insert_project_settings($project_id, 'view_timesheets', '1');
    insert_project_settings($project_id, 'view_activity_log', '1');
    insert_project_settings($project_id, 'view_team_members', '1');
    insert_project_settings($project_id, 'hide_tasks_on_main_tasks_table', '0');

    $project_members['project_id'] = $project_id;
    $project_members['staff_id'] = '1';


    $CI->db->insert(db_prefix() . "project_members", $project_members);

    $CI->db->where('id', $estimate_id);
    $CI->db->update(db_prefix() . "estimates", ['project_id' => $project_id]);

    $CI->db->where('id', $estimate_id);
    $estimates = $CI->db->get(db_prefix() . "estimates")->row();
    $invoice_id = $estimates->invoiceid;
    if (isset($invoice_id)) {
        $CI->db->where('id', $invoice_id);
        $CI->db->update(db_prefix() . "invoices", ['project_id' => $project_id]);
    }

    createProjectTask($project_id, $instellingen_taak, $client);
}

function get_tag_id($name)
{
    $CI = &get_instance();
    $CI->db->where('name', $name);
    $tag = $CI->db->get(db_prefix() . "tags")->row();
    if ($tag) {
        return $tag->id;
    } else {
        $CI->db->query("insert into " . db_prefix() . "tags (name) values('" . $name . "')");
        return $CI->db->insert_id();
    }
}

/**
 * @param $project_id
 * @param $project_template_task
 * @param $client
 * @param $CI
 * @param $tagid
 * @return void
 */
function createProjectTask($project_id, $instelling_taak, $client, $prev_task_id = ''): void
{
    $CI = &get_instance();

    $start_date = date('Y-m-d');
    $assignee_staffid = $instelling_taak->staffid;
    $visible_to_client = '0';

    if ($prev_task_id != '') {
        // do stuff with previous values..
        // like venntech-plaatsing-datum task which decides the assignee and start_date of next task venntech-plaatsing
        if ($instelling_taak->tag_name == 'venntech-plaatsing') {
            // get plaatsing_datum
            $CI->db->where('taskid', $prev_task_id);
            $plaatsing_datum = $CI->db->get(db_prefix() . "pc_plaatsing_datum")->row();
            if (isset($plaatsing_datum)) {
                $start_date = $plaatsing_datum->datum;
                $assignee_staffid = $plaatsing_datum->staffid;
                $visible_to_client = '1';
            }
        }
    }


    $task = [];
    $task ['rel_id'] = $project_id;
    $task ['rel_type'] = 'project';
    $task ['name'] = $instelling_taak->name . ' - ' . get_company_name($client->userid);
    $task ['dateadded'] = date('Y-m-d H:i:s');

    $task ['startdate'] = $start_date;
    $task ['priority'] = '2';
    $task ['status'] = '1';
    $task ['repeat_every'] = '0';
    $task ['addedfrom'] = '1';
    $task ['is_added_from_contact'] = '1';
    $task ['recurring'] = '0';
    $task ['cycles'] = '0';
    $task ['total_cycles'] = '0';
    $task ['billable'] = '0';
    $task ['billed'] = '0';
    $task ['invoice_id'] = '0';
    $task ['hourly_rate'] = '0.00';
    $task ['milestone'] = '0';
    $task ['kanban_order'] = '1';
    $task ['milestone_order'] = '0';
    $task ['visible_to_client'] = $visible_to_client;
    $task ['deadline_notified'] = '0';

    $CI->db->insert(db_prefix() . "tasks", $task);
    $task_id = $CI->db->insert_id();

    assignTaskToStaffid($task_id, $assignee_staffid);

    $taggable = array(
        'rel_id' => $task_id,
        'rel_type' => 'task',
        'tag_id' => $instelling_taak->tag_id,
        'tag_order' => 1,
    );
    $CI->db->insert(db_prefix() . "taggables", $taggable);

    // create default forms if available
    if ($instelling_taak->tag_name == 'venntech-plaatsing-datum') {
        // create datum plaatsing form
        create_default_plaatsing_datum($client->userid, $task_id);
    } else if ($instelling_taak->tag_name == 'venntech-oplever-document') {
        // create oplever document
        create_default_oplever_document($client->userid, $task_id);
    }
}

function insert_project_settings($project_id, $name, $value)
{
    $CI = &get_instance();
    $project_setting['project_id'] = $project_id;
    $project_setting['name'] = $name;
    $project_setting['value'] = $value;
    $CI->db->insert(db_prefix() . "project_settings", $project_setting);
}

function create_next_task($input_array)
{
    $status = $input_array['status'];
    $task_id = $input_array['task_id'];
    if ($status == 5) {


        $CI = &get_instance();
        $CI->db->where('id', $task_id);
        $task = $CI->db->get(db_prefix() . 'tasks')->row();

        if (isset($task->rel_id) && $task->rel_type == 'project') {

            $CI->db->where('id', $task->rel_id);
            $project = $CI->db->get(db_prefix() . 'projects')->row();

            $CI->db->where('project_id', $task->rel_id);
            $estimate = $CI->db->get(db_prefix() . 'estimates')->row();

            $CI->db->where('userid', $estimate->clientid);
            $client = $CI->db->get(db_prefix() . 'clients')->row();

            $CI->db->where('estimates_id', $estimate->id);
            $estimate_extra = $CI->db->get(db_prefix() . 'pc_estimates_extra')->row();

            if (isset($estimate_extra)) {
                $CI->db->where('id', $estimate_extra->estimate_template_id);
                $estimate_template = $CI->db->get(db_prefix() . 'pc_estimate_template')->row();

                $CI->db->where('id', $estimate_template->project_template_id);
                $project_template = $CI->db->get(db_prefix() . 'pc_project_template')->row();

                $CI->db->where('project_template_id', $project_template->id);
                $project_template_tasks = $CI->db->get(db_prefix() . 'pc_project_template_tasks')->result_array();

                $CI->db->where('rel_id', $task_id);
                $CI->db->where('rel_type', 'task');
                $taggables = $CI->db->get(db_prefix() . 'taggables')->result_array();

                // we have a list of PROJECT TEMPLATE tasks and taggables of PROJECT TASKS
                // do a foreach for taggables and try to find in $project_template_tasks

                $template_task_order = -1;
                foreach ($taggables as $taggable) {
                    $task_tag_id = $taggable['tag_id'];
                    foreach ($project_template_tasks as $project_template_task) {
                        $CI->db->where('id', $project_template_task['instellingen_taak_id']);
                        $instelling_taak = $CI->db->get(db_prefix() . 'pc_instellingen_taak')->row();
                        if ($task_tag_id == $instelling_taak->tag_id) {
                            // we have found the template get the order and create next task by incrementing order
                            $template_task_order = $project_template_task['task_order'];
                            break;
                        }
                    }
                }

                if ($template_task_order > -1) {
                    $next_template_task_order = $template_task_order + 1;
                    $next_template_task = null;
                    foreach ($project_template_tasks as $project_template_task) {
                        $template_task_order = $project_template_task['task_order'];
                        if ($template_task_order == $next_template_task_order) {
                            // we have found the template get the order and create next task by incrementing order
                            $next_template_task = $project_template_task;
                            break;
                        }
                    }

                    if ($next_template_task != null) {
                        // create task
                        $CI->db->where('id', $next_template_task['instellingen_taak_id']);
                        $instelling_taak = $CI->db->get(db_prefix() . 'pc_instellingen_taak')->row();
                        createProjectTask($project->id, $instelling_taak, $client, $task_id);

                    }
                }
            }


        }
    }
}