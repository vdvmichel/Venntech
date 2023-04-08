<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Type_kortingen extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_kortingen_model');

    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_SETTINGS)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('settings'));
        }

        $this->load->view('venntech/type_kortingen_view', []);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_SETTINGS)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/type_kortingen_table'));
    }

    public function edit($id = '')
    {

        if (!staff_can('view', FEATURE_SETTINGS)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('tasks'));
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            // create or edit data with form data
            if ($data['type_korting']['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_SETTINGS)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('type_korting'));
                }
                $type_korting_id = $this->type_kortingen_model->add($data['type_korting']);
                if($type_korting_id){
                    set_alert('success', _l('added_successfully', _l('type_korting')));
                }
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_SETTINGS)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('type_korting'));
                }
                $success = $this->type_kortingen_model->edit($data['type_korting']);
                if($success){
                    set_alert('success', _l('updated_successfully', _l('type_korting')));
                }
            }

            redirect(admin_url('venntech/type_kortingen'));
        } else {
            // view add or edit with id
            if ($id == '') {
                //view create
                $item = new stdClass();
                $item->type_korting = new stdClass();
                $item->type_korting->id = '';
                $item->type_korting->name = '';
                $item->type_korting->discount_percentage = '';
                $item->type_korting->is_voor_btw = '';

                $data['item'] = $item;
                $data['edit_type'] = 'create';
                $data['title'] = _l('add_new', _l('type_korting'));

                $this->load->view('venntech/type_korting_view', $data);
            } else {
                // view edit
                $type_korting = $this->type_kortingen_model->get($id);

                $item = new stdClass();
                $item->type_korting = $type_korting;

                $data['item'] = $item;
                $data['edit_type'] = 'edit';
                $data['title'] = _l('edit', _l('type_korting'));
                $this->load->view('venntech/type_korting_view', $data);
            }
        }
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_SETTINGS)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('tasks'));
        }
        $success = $this->type_kortingen_model->delete($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('task')));
        }
        redirect(admin_url('venntech/type_kortingen'));
    }

}
