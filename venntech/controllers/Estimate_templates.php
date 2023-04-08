<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_templates extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_template_model');
        $this->load->model('estimate_template_model');
        $this->load->model('estimate_template_element_model');
        $this->load->model('estimate_template_items_model');
        $this->load->model('estimates_extra_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('estimate_templates'));
        }
        $data['title'] = _l('estimate_templates');
        $this->load->view('venntech/estimate_templates_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_ESTIMATE_TEMPLATES)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/estimate_template_table'));
    }

    public function table_elements($estimate_template_id)
    {
        if (!staff_can('view', FEATURE_ESTIMATE_TEMPLATES)) {
            ajax_access_denied();
        }

        $aColumns = [
            'id',
            'name'];

        $sIndexColumn = 'id';
        $sTable = db_prefix() . 'pc_estimate_template_element';
        $join = [];
        $where = ['AND estimate_template_id=' . $estimate_template_id];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
        $output = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {

            // init empty array
            $row = [];

            $row[] = '<a href="/admin/venntech/estimate_template_elements/edit/' . $estimate_template_id . '/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
            $row[] = '<a href="/admin/venntech/estimate_template_elements/edit/' . $estimate_template_id . '/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';

            // add to next index the data, no need to specify index the item will be added to the end.
            $actions = icon_btn('venntech/estimate_template_elements/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
            $row[] = $actions;
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
        die();

    }

    public function edit($id = '')
    {
        if (!staff_can('edit', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('edit') . ' VENNTECH ' . _l('estimate_templates'));
        }

        if ($this->input->post()) {

            $data = $this->input->post();
            if ($data['id'] == "") {
                if (!staff_can('create', FEATURE_ESTIMATE_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('estimate_templates'));
                }
                $id = $this->estimate_template_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('estimate_template')));
                }
                redirect(admin_url('venntech/estimate_templates/edit/' . $id));
            } else {
                if (!staff_can('edit', FEATURE_ESTIMATE_TEMPLATES)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('estimate_templates'));
                }
                $success = $this->estimate_template_model->edit($data);
                if ($success) {
                    set_alert('success', _l('added_successfully', _l('estimate_template')));
                }
                redirect(admin_url('venntech/estimate_templates/edit/' . $data['id']));
            }

        } else {
            $data = [];
            $project_templates = $this->project_template_model->get_options();
            $data['project_templates'] = $project_templates;

            if ($id == "") {
                // create
                $data['title'] = _l('add_new', _l('estimate_template'));

                $estimate_template = new stdClass();
                $estimate_template->id = "";
                $estimate_template->project_template_id = "";
                $estimate_template->name = "";
                $data['estimate_template'] = $estimate_template;
            } else {
                // edit
                $estimate_template = $this->estimate_template_model->get($id);

                $data['title'] = _l('edit', _l('estimate_template'));
                $data['estimate_template'] = $estimate_template;
            }

            $this->load->view('venntech/estimate_template_view', $data);
        }
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('edit') . ' VENNTECH ' . _l('estimate_templates'));
        }

        // if this template is al used in an estimate you can not delete it..
        $estimates_extra = $this->estimates_extra_model->get_by_template_id($id);
        if (isset($estimates_extra)) {
            set_alert('danger', _l('is_referenced', _l('estimate_template')));
        } else {

            $this->estimate_template_items_model->delete_by_template_id($id);
            $this->estimate_template_element_model->delete_by_template_id($id);
            $success = $this->estimate_template_model->delete($id);

            if ($success) {
                set_alert('success', _l('deleted', _l('estimate_template')));
            }
        }
        redirect(admin_url('venntech/estimate_templates'));
    }
}
