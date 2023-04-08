<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_template_elements extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('estimate_template_model');
        $this->load->model('estimate_template_element_model');
        $this->load->model('estimate_template_items_model');
        $this->load->model('estimates_extra_items_model');
        $this->load->model('project_template_model');
        $this->load->model('product_model');
        $this->load->model('samengestelde_product_model');
        $this->load->model('invoice_items_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_PROJECT_TEMPLATES)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('estimate_templates'));
        }
        $data['title'] = _l('project_template');
        $this->load->view('venntech/estimate_templates_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_PROJECT_TEMPLATES)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/estimate_template_table'));
    }

    public function edit($estimate_template_id, $id = '')
    {
        // forms do a POST call for save/update, else a GET call to fill the form
        if ($this->input->post()) {

            $data = $this->input->post();
            $all_items = [];
            if (array_key_exists('all_items', $data)) {
                $all_items = $data['all_items'];
            }
            $all_samengestelde = [];
            if (array_key_exists('all_samengestelde', $data)) {
                $all_samengestelde = $data['all_samengestelde'];
            }
            $all_groups = [];
            if (array_key_exists('all_groups', $data)) {
                $all_groups = $data['all_groups'];
            }

            unset($data['estimate_template']);
            unset($data['all_items']);
            unset($data['all_samengestelde']);
            unset($data['all_groups']);

            if ($data['id'] == "") {
                if (!staff_can('create', FEATURE_PROJECT_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('estimate_templates'));
                }
                $element_id = $this->estimate_template_element_model->add($data);
                if ($element_id) {
                    foreach ($all_items as $item_input) {
                        $item = [];
                        $item['estimate_template_id'] = $estimate_template_id;
                        $item['estimate_template_element_id'] = $element_id;
                        $item['rel_id'] = $item_input['id'];
                        $item['rel_type'] = 'items';
                        $item['multiply'] = $item_input['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }
                    foreach ($all_samengestelde as $samengesteld_input) {
                        $item = [];
                        $item['estimate_template_id'] = $estimate_template_id;
                        $item['estimate_template_element_id'] = $element_id;
                        $item['rel_id'] = $samengesteld_input['id'];
                        $item['rel_type'] = 'samengestelde_product';
                        $item['multiply'] = $samengesteld_input['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }
                    foreach ($all_groups as $all_groups) {
                        $item = [];
                        $item['estimate_template_id'] = $estimate_template_id;
                        $item['estimate_template_element_id'] = $element_id;
                        $item['rel_id'] = $all_groups['id'];
                        $item['rel_type'] = 'groups';
                        $item['multiply'] = $all_groups['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }
                    set_alert('success', _l('added_successfully', _l('estimate_template')));
                }
            } else {
                if (!staff_can('edit', FEATURE_PROJECT_TEMPLATES)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('estimate_templates'));
                }
                $success = $this->estimate_template_element_model->edit($data);
                if ($success) {

                    $this->estimate_template_items_model->delete_by_element_id($estimate_template_id, $data['id']);

                    foreach ($all_items as $item_input) {
                        $item = [];
                        $item['estimate_template_id'] = $data['estimate_template_id'];
                        $item['estimate_template_element_id'] = $data['id'];
                        $item['rel_id'] = $item_input['id'];
                        $item['rel_type'] = 'items';
                        $item['multiply'] = $item_input['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }
                    foreach ($all_samengestelde as $samengesteld_input) {
                        $item = [];
                        $item['estimate_template_id'] = $data['estimate_template_id'];
                        $item['estimate_template_element_id'] = $data['id'];
                        $item['rel_id'] = $samengesteld_input['id'];
                        $item['rel_type'] = 'samengestelde_product';
                        $item['multiply'] = $samengesteld_input['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }
                    foreach ($all_groups as $group_input) {
                        $item = [];
                        $item['estimate_template_id'] = $data['estimate_template_id'];
                        $item['estimate_template_element_id'] = $data['id'];
                        $item['rel_id'] = $group_input['id'];
                        $item['rel_type'] = 'groups';
                        $item['multiply'] = $group_input['multiply'];
                        $this->estimate_template_items_model->add($item);
                    }

                    set_alert('success', _l('added_successfully', _l('estimate_template')));
                }
            }
            redirect(admin_url('venntech/estimate_templates/edit/' . $estimate_template_id));
        } else {

            $project_templates = $this->project_template_model->get_options();
            $items_options = $this->product_model->get_active_items_combobox();
            $samengestelde_options = $this->samengestelde_product_model->get_active_samengestelde_producten_combobox();
            $groups_arr = $this->invoice_items_model->get_groups();
            $groups_options = array_map(function ($group) {
                $_group['id'] = $group['id'];
                $_group['name'] = $group['name'];

                return $_group;
            }, $groups_arr);

            $data['project_templates'] = $project_templates;
            $data['items_options'] = $items_options;
            $data['samengestelde_options'] = $samengestelde_options;
            $data['groups_options'] = $groups_options;

            if ($id == "") {
                if (!staff_can('create', FEATURE_ESTIMATE_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('estimate_templates'));
                }
                $estimate_template = $this->estimate_template_model->get($estimate_template_id);

                $data['title'] = _l('add_new', _l('estimate_template_element'));
                $data['estimate_template'] = $estimate_template;

                $estimate_template_element = new stdClass();
                $estimate_template_element->id = "";
                $estimate_template_element->estimate_template_id = $estimate_template_id;
                $estimate_template_element->name = "";
                $data['estimate_template_element'] = $estimate_template_element;


                $data['all_items'] = [];
                $data['all_samengestelde'] = [];
                $data['all_groups'] = [];
                $data['disabled'] = false;

                $this->load->view('venntech/estimate_template_element_view', $data);

            } else {
                if (!staff_can('edit', FEATURE_ESTIMATE_TEMPLATES)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('estimate_templates'));
                }

                $items = $this->estimate_template_items_model->get_by_estimate_template($estimate_template_id, $id, 'items');
                $samengestelde_producten = $this->estimate_template_items_model->get_by_estimate_template($estimate_template_id, $id, 'samengestelde_product');
                $groups = $this->estimate_template_items_model->get_by_estimate_template($estimate_template_id, $id, 'groups');

                $all_items = [];
                foreach ($items as $key => $item) {
                    $all_items[$key]['id'] = $item['rel_id'];
                    $all_items[$key]['multiply'] = $item['multiply'];
                }

                $all_samengestelde = [];
                foreach ($samengestelde_producten as $key => $samengestelde_product) {
                    $all_samengestelde[$key]['id'] = $samengestelde_product['rel_id'];
                    $all_samengestelde[$key]['multiply'] = $samengestelde_product['multiply'];
                }

                $all_groups = [];
                foreach ($groups as $key => $group) {
                    $all_groups[$key]['id'] = $group['rel_id'];
                    $all_groups[$key]['multiply'] = $group['multiply'];
                }

                $estimate_template = $this->estimate_template_model->get($estimate_template_id);
                $estimate_template_element = $this->estimate_template_element_model->get($id);

                $data['title'] = _l('edit', _l('estimate_template_element'));
                $data['estimate_template'] = $estimate_template;
                $data['estimate_template_element'] = $estimate_template_element;

                $data['all_items'] = $all_items;
                $data['all_samengestelde'] = $all_samengestelde;
                $data['all_groups'] = $all_groups;

                // disable edit and delete of currently used items..
                $result = $this->estimates_extra_items_model->find_by_estimate_template_element_id($id);
                $data['disabled'] = sizeof($result) > 0;

                $this->load->view('venntech/estimate_template_element_view', $data);
            }
        }

    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('estimate_template'));
        }

        $estimate_template_element = $this->estimate_template_element_model->get($id);
        $estimate_template_id = $estimate_template_element->estimate_template_id;

        $result = $this->estimates_extra_items_model->find_by_estimate_template_element_id($id);
        if (sizeof($result) > 0) {
            set_alert('danger', _l('is_referenced', _l('estimate_template_element')));
        } else {
            $this->estimate_template_items_model->delete_by_element_id($estimate_template_id, $id);
            $success = $this->estimate_template_element_model->delete($id);

            if ($success) {
                set_alert('success', _l('deleted', _l('estimate_template_element')));
            }
        }
        redirect(admin_url('venntech/estimate_templates/edit/' . $estimate_template_id));

    }
}
