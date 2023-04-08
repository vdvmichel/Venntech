<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings_taak extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('settings_taak_model');
        $this->load->model('project_template_tasks_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_SETTINGS)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('settings'));
        }

        $this->load->view('venntech/settings_taken_view', []);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_SETTINGS)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/settings_taken_table'));
    }

    public function edit($id = '')
    {

        if (!staff_can('view', FEATURE_SETTINGS)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('tasks'));
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            // create or edit data with form data
            if ($data['settings_taak']['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_SETTINGS)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('tasks'));
                }

                $settings_taak = $data['settings_taak'];
                $tag_name = $settings_taak['tag_name'];
                $tag_id = get_tag_id($tag_name);
                $settings_taak['tag_id'] = $tag_id;
                // create
                $settings_taak_id = $this->settings_taak_model->add($settings_taak);

                $settings_taak['id'] = $settings_taak_id;
                $settings_taak['task_order'] = $this->settings_taak_model->get_max_order() + 1;
                $success = $this->settings_taak_model->edit($settings_taak);

                if($success){
                    set_alert('success', _l('added_successfully', _l('tasks')));
                }
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_INSPECTIE_RAPPORT)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('tasks'));
                }
                $success = $this->settings_taak_model->edit($data['settings_taak']);
                if($success){
                    set_alert('success', _l('updated_successfully', _l('tasks')));
                }
            }

            redirect(admin_url('venntech/settings_taak'));
        } else {
            // view add or edit with id
            $data['members'] = get_members_option($this->staff_model->get());;

            if ($id == '') {
                //view create

                $item = new stdClass();
                $item->settings_taak = new stdClass();
                $item->settings_taak->id = '';
                $item->settings_taak->order = '';
                $item->settings_taak->name = '';
                $item->settings_taak->tag_name = '';
                $item->settings_taak->tag_id = '';
                $item->settings_taak->staffid = '';
                $item->settings_taak->view_url = '';

                $data['item'] = $item;
                $data['edit_type'] = 'create';
                $data['title'] = _l('add_new', _l('tasks'));

                $this->load->view('venntech/settings_taak_view', $data);
            } else {
                // view edit
                $settings_taak = $this->settings_taak_model->get($id);

                $item = new stdClass();
                $item->settings_taak = $settings_taak;

                $data['item'] = $item;
                $data['edit_type'] = 'edit';
                $data['title'] = _l('edit', _l('tasks'));
                $this->load->view('venntech/settings_taak_view', $data);
            }
        }

    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_SETTINGS)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('tasks'));
        }

        $settings_taak = $this->settings_taak_model->get($id);

        $this->project_template_tasks_model->delete_by_settings_taak_id($id);
        $success = $this->settings_taak_model->delete($id);

        if ($success) {
            $sql = "update ".db_prefix()."pc_instellingen_taak set task_order = task_order - 1 where task_order > ".$settings_taak->task_order;
            $this->db->query($sql);

            set_alert('success', _l('deleted', _l('task')));
        }

        redirect(admin_url('venntech/settings_taak'));

    }

    public function task_order_up($id)
    {
        $task = $this->settings_taak_model->get($id);
        $old_order = $task->task_order;
        $new_order = $old_order - 1;

        $task2 = $this->settings_taak_model->get_by_order($new_order);

        if(isset($task2) && isset($task2->id)){
            $this->settings_taak_model->update_order($task->id, $new_order);
            $this->settings_taak_model->update_order($task2->id, $old_order);
        }
        redirect(admin_url('venntech/settings_taak'));
    }

    public function task_order_down($id)
    {
        $task = $this->settings_taak_model->get($id);
        $old_order = $task->task_order;
        $new_order = $old_order + 1;

        $task2 = $this->settings_taak_model->get_by_order($new_order);

        if(isset($task2) && isset($task2->id)){
            $this->settings_taak_model->update_order($task->id, $new_order);
            $this->settings_taak_model->update_order($task2->id, $old_order);
        }
        redirect(admin_url('venntech/settings_taak'));
    }
}
