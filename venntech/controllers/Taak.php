<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Taak extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_comments_model');
        $this->load->model('taken_model');
        $this->load->model('tasks_model');
    }

    public function index()
    {
        if (!staff_can('view', FEATURE_TAKEN)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('tasks'));
        }
        $data['title'] = _l('tasks');
        $this->load->view('venntech/taak_view', $data);
    }

    public function edit($id = '')
    {
        if (!staff_can('view', FEATURE_TAKEN)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('tasks'));
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            // create or edit data with form data
            $task_comments = $data['task_comments'];
            $task_comments['staffid'] = get_staff_user_id();
            $task_comments['contact_id'] = 0; // TODO ??
            $task_comments['dateadded'] = date('Y-m-d H:i:s');

            if ($task_comments['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_TAKEN)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('task'));
                }
                $taak_id = $this->task_comments_model->add($task_comments);
                if ($taak_id) {
                    set_alert('success', _l('added_successfully', _l('task')));
                }
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_TAKEN)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('task'));
                }
                $success = $this->task_comments_model->edit($task_comments);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('task')));
                }
            }

            $task_id = $task_comments['taskid'];
            if (array_key_exists('complete', $data)) {
                $this->tasks_model->mark_as(5, $task_id);
            }

            redirect(admin_url('venntech/taken'));
        } else {
            // view add or edit with id
            $data = [];
            $data['members'] = get_members_option($this->staff_model->get());;
            if ($id == '') {
                //view create

                $item = new stdClass();
                $item->task_comments = new stdClass();
                $item->task_comments->id = '';
                $item->task_comments->content = '';

                $data['item'] = $item;
                $data['edit_type'] = 'create';
                $data['title'] = _l('add_new', _l('task_comment'));

                $this->load->view('venntech/taak_view', $data);
            } else {
                // view edit
                $task = $this->tasks_model->get($id);
                $task_comment = $this->task_comments_model->get_comment_by_task_id($id);
                $task_staffid = $this->taken_model->get_staffid_by_task_id($id);

                $item = new stdClass();
                if (isset($task_comment)) {
                    $item->task_comments = $task_comment;
                } else {
                    $item = new stdClass();
                    $item->task_comments = new stdClass();
                    $item->task_comments->id = '';
                    $item->task_comments->content = '';
                }

                $data['item'] = $item;
                $data['task'] = $task;
                $data['task_staffid'] = $task_staffid;
                $data['edit_type'] = 'edit';
                $data['title'] = _l('edit', $task->name);

                $this->load->view('venntech/taak_view', $data);
            }
        }
    }

}
