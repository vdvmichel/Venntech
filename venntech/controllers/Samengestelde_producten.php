<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Samengestelde_producten extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('samengestelde_product_model');
        $this->load->model('samengestelde_product_items_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('producten'));
        }
        $data['title'] = _l('samengestelde_product');
        $this->load->view('venntech/samengestelde_producten_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/samengestelde_product_table'));
    }

    public function edit($id = '')
    {
        // forms do a POST call for save/update, else a GET call to fill the form
        if ($this->input->post()) {

            $data = $this->input->post();
            if ($data['id'] == "") {
                if (!staff_can('create', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('producten'));
                }
                $id = $this->samengestelde_product_model->add($data);
                if ($id) {
                    $all_items_id = $data['all_items_id'];
                    foreach ($all_items_id as $item_id) {
                        $this->samengestelde_product_items_model->add($id, $item_id);
                    }
                    set_alert('success', _l('added_successfully', _l('samengestelde_product')));
                }
            } else {
                if (!staff_can('edit', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('producten'));
                }
                $success = $this->samengestelde_product_model->edit($data);
                if ($success) {
                    handle_venntech_product_upload($data['id']);

                    $this->samengestelde_product_items_model->deleteAll($data['id']);
                    $all_items_id = $data['all_items_id'];
                    foreach ($all_items_id as $item_id) {
                        $this->samengestelde_product_items_model->add($data['id'], $item_id);
                    }
                    set_alert('success', _l('added_successfully', _l('samengestelde_product')));
                }
            }
            redirect(admin_url('venntech/samengestelde_producten'));
        } else {
            $this->load->model('product_model');
            $data['producten'] = $this->product_model->get_active_items_combobox();

            if ($id == "") {
                if (!staff_can('create', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('producten'));
                }
                $item = new stdClass();
                $item->id = "";
                $item->product_id = "";
                $item->naam = "";
                $item->omschrijving = "";
                $item->actief = "";
                $data['title'] = _l('add_new', _l('samengestelde_product'));
                $data['item'] = $item;
                $data['all_items_id'] = [0, 0];
                $this->load->view('venntech/samengestelde_product_view', $data);

            } else {

                $item = $this->samengestelde_product_model->get($id);
                $all_items_id = [];
                $samengestelde_product_items = $this->samengestelde_product_items_model->get($id);

                foreach ($samengestelde_product_items as $samengestelde_product_item) {
                    array_unshift( $all_items_id, array_get_by_index(1, $samengestelde_product_item));
                }

                $data['title'] = _l('edit', _l('samengestelde_product'));
                $data['item'] = $item;
                $data['all_items_id'] = $all_items_id;
                $this->load->view('venntech/samengestelde_product_view', $data);
            }
        }

    }

    public function change_samengestelde_product_status($id, $status)
    {
        if (!staff_can('edit', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
            ajax_access_denied();
        }

        $this->samengestelde_product_model->change_samengestelde_product_status($id, $status);
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('samengestelde_product'));
        }

        $this->samengestelde_product_items_model->deleteAll($id);

        $item = $this->samengestelde_product_model->get($id);
        $success = $this->samengestelde_product_model->delete($id);

        if ($success) {
            set_alert('success', _l('deleted', _l('samengestelde_product')));
        }
        redirect(admin_url('venntech/samengestelde_producten'));

    }
}
