<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plaatsing_datums extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('plaatsing_datum_model');
        $this->load->model('utilities_model');
        $this->load->model('tasks_model');
        $this->load->model('taken_model');
    }

    public function index()
    {
        if (!staff_can('view', FEATURE_PLAATSING_DATUM)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('venntech-plaatsing-datum'));
        }

        $data['title'] = _l('venntech-plaatsing-datum');
        $this->load->view('venntech/plaatsing_datums_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_PLAATSING_DATUM)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/plaatsing_datums_table'));
    }

    public function edit($id = '')
    {

        if (!staff_can('view', FEATURE_PLAATSING_DATUM)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('venntech-plaatsing-datum'));
        }

        if ($this->input->post()) {
            $data = $this->input->post();


            // create or edit data with form data
            $plaatsing_datum_input = $data['plaatsing_datum'];
            if ($plaatsing_datum_input['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_PLAATSING_DATUM)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('plaatsing_datum'));
                }

                $plaatsing_datum_input['clientid'] = $data['clientid'];
                $this->plaatsing_datum_model->add($plaatsing_datum_input);

                redirect(admin_url('venntech/plaatsing_datums'));
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_PLAATSING_DATUM)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('plaatsing_datum'));
                }
                $plaatsing_datum_input['clientid'] = $data['clientid'];
                $data['current_staffid'] = $data['current_staffid'];
                $this->plaatsing_datum_model->edit($plaatsing_datum_input);

                $task_id = $plaatsing_datum_input['taskid'];
                if (array_key_exists('complete', $data)) {
                    $this->tasks_model->mark_as(5, $task_id);
                }
                if ($task_id == '') {
                    redirect(admin_url('venntech/plaatsing_datums'));
                } else {
                    redirect(admin_url('venntech/taken'));
                }

            }
        } else {
            $data = [];
            $data['members'] = get_members_option($this->staff_model->get());;

            if ($id == '') {
                //view create
                $item = new stdClass();
                $item->plaatsing_datum = new stdClass();
                $item->plaatsing_datum->id = '';
                $item->plaatsing_datum->name = '';
                $item->plaatsing_datum->clientid = '';
                $item->plaatsing_datum->staffid = '';
                $item->plaatsing_datum->datum = '';
                $item->plaatsing_datum->taskid = '';
                $item->current_staffid = '';

                $data['item'] = $item;
                $data['edit_type'] = 'create';
                $data['title'] = _l('add_new', _l('plaatsingdatum'));
            } else {
                // view edit
                $plaatsing_datum = $this->plaatsing_datum_model->get($id);
                $task_staffid = $this->taken_model->get_staffid_by_task_id($plaatsing_datum->taskid);

                $item = new stdClass();
                $item->plaatsing_datum = $plaatsing_datum;

                $task = null;
                if (isset($plaatsing_datum->taskid)) {
                    $task = $this->tasks_model->get($plaatsing_datum->taskid);
                }

                $data['item'] = $item;
                $data['edit_type'] = 'edit';
                $data['task'] = $task;
                $data['task_staffid'] = $task_staffid;
                $data['title'] = _l('edit', _l('plaatsingdatum'));

            }

            // adds needed javascript and css files
            add_calendar_assets();

            $this->load->view('venntech/plaatsing_datum_view', $data);
        }

    }


    public function get_calendar_data()
    {

        if ($this->input->get() && $this->input->is_ajax_request()) {
            $data = $this->input->get();
            $start = $data['start'];
            $end = $data['end'];
            $calendar_staffid = $data['calendar_staffid'];
            $calendar_data = [];
            if ($calendar_staffid != '') {


                $this->db->select(db_prefix() . 'tasks.name as title,' . db_prefix() . 'tasks.id,'
                    . tasks_rel_name_select_query() . ' as rel_name,rel_id,status,milestone,CASE WHEN duedate IS NULL THEN startdate ELSE duedate END as date', false);
                $this->db->from(db_prefix() . 'tasks');
                $this->db->join(db_prefix() . 'task_assigned', db_prefix() . 'tasks.id = ' . db_prefix() . 'task_assigned.taskid');
                $this->db->where('status !=', 5);
                $this->db->where('staffid =', $calendar_staffid);

                $this->db->where("CASE WHEN duedate IS NULL THEN (startdate BETWEEN '$start' AND '$end') ELSE (duedate BETWEEN '$start' AND '$end') END", null, false);

                $tasks = $this->db->get()->result_array();


                foreach ($tasks as $task) {
                    $task['date'] = $task['date'];
                    $name = mb_substr($task['title'], 0, 60) . '...';
                    $task['_tooltip'] = _l('calendar_task') . ' - ' . $name;
                    $task['title'] = $name;
                    $status = get_task_status_by_id($task['status']);
                    $task['color'] = $status['color'];
                    $task['url'] = '';
                    $task['className'] = $task['milestone'] ? ['milestone-' . $task['milestone']] : '';

                    array_push($calendar_data, $task);
                }
            }
            echo json_encode($calendar_data);
            die();
        }
    }


    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_PLAATSING_DATUM)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('tasks'));
        }


        // delete rapport with id
        $this->taken_model->delete($id);


        redirect(admin_url('venntech/taken'));
    }
}
