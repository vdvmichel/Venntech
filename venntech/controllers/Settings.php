<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_SETTINGS)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('settings'));
        }

        if ($this->input->post()) {
            // save values..
            if (!staff_can('edit', FEATURE_SETTINGS)) {
                access_denied(_l('edit') . ' VENNTECH ' . _l('settings'));
            }

            $data = $this->input->post();

            update_option('assign_task_inspectie_rapport_staffid', $data['inspectie_rapport_staffid']);
            update_option('venntech_margin_of_profit', $data['margin_of_profit']);

            redirect(admin_url('venntech/settings'));
        } else {
            $data = [];

            $data['inspectie_rapport_staffid'] = get_option('assign_task_inspectie_rapport_staffid');
            $data['margin_of_profit'] = get_option('venntech_margin_of_profit');
            $data['members'] = get_members_option($this->staff_model->get());;

            $this->load->view('venntech/settings_view', $data);
        }
    }

}
