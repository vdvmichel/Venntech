<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_templates extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_taak_model');
        $this->load->model('project_template_model');
        $this->load->model('project_template_tasks_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_PROJECT_TEMPLATES)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('project_templates'));
        }
        $data['title'] = _l('project_template');
        $this->load->view('venntech/project_templates_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_PROJECT_TEMPLATES)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/project_template_table'));
    }

    public function edit($id = '')
    {
        // forms do a POST call for save/update, else a GET call to fill the form
        if ($this->input->post()) {

            $data = $this->input->post();

            unset($data['all_tasks_name']);
            if ($data['id'] == "") {
                if (!staff_can('create', FEATURE_PROJECT_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('project_templates'));
                }
                $id = $this->project_template_model->add($data);
                if ($id) {
                    // initialize tasks
                    $instellingen_taken = $this->settings_taak_model->get();
                    foreach ($instellingen_taken as $key => $instellingen_taak) {

                        $task = [];
                        $task['project_template_id'] = $id;
                        $task['instellingen_taak_id'] = $instellingen_taak['id'];
                        $task['enabled'] = true;
                        $task['task_order'] = $key;
                        $this->project_template_tasks_model->add($task);
                    }
                    set_alert('success', _l('added_successfully', _l('project_template')));

                    redirect(admin_url('venntech/project_templates/edit/' . $id));
                }

                redirect(admin_url('venntech/project_templates'));
            } else {
                if (!staff_can('edit', FEATURE_PROJECT_TEMPLATES)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('project_templates'));
                }
                $success = $this->project_template_model->edit($data);
                if ($success) {
                    // tasks are edited in view no input params available..
                    set_alert('success', _l('updated_successfully', _l('project_template')));
                }

                redirect(admin_url('venntech/project_templates'));
            }

        } else {

            $instellingen_taken = $this->settings_taak_model->get();
            $data['instellingen_taken'] = $instellingen_taken;

            if ($id == "") {
                if (!staff_can('create', FEATURE_PROJECT_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('project_templates'));
                }
                $project_template = new stdClass();
                $project_template->id = "";
                $project_template->name = "";
                $project_template->description = "";
                $data['title'] = _l('add_new', _l('project_template'));
                $data['edit_type'] = 'create';
                $data['project_template'] = $project_template;

                $template_tasks = [];
                foreach ($instellingen_taken as $key => $instellingen_taak) {
                    $template_task = [];
                    $template_task['project_template_id'] = '';
                    $template_task['task_order'] = $key;
                    $template_task['instellingen_taak_id'] = $instellingen_taak['id'];
                    $template_task['enabled'] = true;

                    $template_tasks[] = $template_task;
                }
                $data['template_tasks'] = $template_tasks;

                $this->load->view('venntech/project_template_view', $data);

            } else {

                $template_tasks = $this->project_template_tasks_model->get_by_project_template_id($id);

                $project_template = $this->project_template_model->get($id);

                $data['title'] = _l('edit', _l('project_template'));
                $data['edit_type'] = 'edit';
                $data['project_template'] = $project_template;
                $data['template_tasks'] = $template_tasks;

                $this->load->view('venntech/project_template_view', $data);
            }
        }

    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_PROJECT_TEMPLATES)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('project_template'));
        }

        $this->project_template_tasks_model->delete_all_by_project_template_id($id);
        $success = $this->project_template_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('project_template')));
        }
        redirect(admin_url('venntech/project_templates'));

    }

    public function change_task_status($id, $status)
    {
        if (!staff_can('edit', FEATURE_PROJECT_TEMPLATES)) {
            ajax_access_denied();
        }
        $this->project_template_tasks_model->change_task_status($id, $status);

    }

    public function task_order_up($template_id, $id)
    {
        $task = $this->project_template_tasks_model->get($id);
        $old_order = $task->task_order;
        $new_order = $old_order - 1;

        $task2 = $this->project_template_tasks_model->get_by_order($new_order);

        if(isset($task2) && isset($task2->id)){
            $this->project_template_tasks_model->update_order($task->id, $new_order);
            $this->project_template_tasks_model->update_order($task2->id, $old_order);
        }

        set_alert('success', _l('updated_successfully', _l('project_template')));

        redirect(admin_url('venntech/project_templates/edit/' . $template_id));
    }

    public function task_order_down($template_id, $id)
    {
        $task = $this->project_template_tasks_model->get($id);
        $old_order = $task->task_order;
        $new_order = $old_order + 1;

        $task2 = $this->project_template_tasks_model->get_by_order($new_order);

        if(isset($task2) && isset($task2->id)){
            $this->project_template_tasks_model->update_order($task->id, $new_order);
            $this->project_template_tasks_model->update_order($task2->id, $old_order);
        }
        set_alert('success', _l('updated_successfully', _l('project_template')));

        redirect(admin_url('venntech/project_templates/edit/' . $template_id));
    }
}
