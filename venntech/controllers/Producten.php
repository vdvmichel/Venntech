<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Producten extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_items_model');
        $this->load->model('product_model');
    }

    /* List all available groepen */
    public function index()
    {
        if(!staff_can('view', FEATURE_PRODUCTEN)) {
            access_denied(_l('view').' VENNTECH '. _l('producten'));
        }

        $data['title'] = _l('producten');
        $this->load->view('venntech/producten_view', $data);
    }

    public function table()
    {
        if(!staff_can('view', FEATURE_PRODUCTEN)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/product_table'));
    }

    public function edit($id = '')
    {

        // forms do a POST call for save/update, else a GET call to fill the form
        if ($this->input->post()) {

            $data = $this->input->post();
            $items = $data['items'];
            $items_extra = $data['items_extra'];

            if ($data['id'] == "") {
                if(!staff_can('create', FEATURE_PRODUCTEN)) {
                    access_denied(_l('create').' VENNTECH '. _l('producten'));
                }
                // this creates an items_extra with hook, see venntech.php
                $item_id = $this->invoice_items_model->add($items);
                // so we have to update
                if ($item_id) {
                    $original_items_extra = $this->product_model->get_by_item_id($item_id);
                    $items_extra['id'] = $original_items_extra->id;
                    $this->product_model->edit($items_extra);
                    $filename = handle_venntech_product_upload($items_extra['id']);
                    if($filename){
                        $this->product_model->edit_image_path($filename, $items_extra['id']);
                    }
                    // returns a message to be shown on top right corner
                    set_alert('success', _l('added_successfully', _l('product')));
                }
            } else {
                if(!staff_can('edit', FEATURE_PRODUCTEN)) {
                    access_denied(_l('edit').' VENNTECH '. _l('producten'));
                }
                $items['itemid'] = $data['itemid'];
                $this->invoice_items_model->edit($items);

                $items_extra['id'] = $data['id'];
                $success = $this->product_model->edit($items_extra);

                if ($success) {
                    $filename = handle_venntech_product_upload($data['id']);
                    if($filename){
                        $this->product_model->edit_image_path($filename, $data['id']);
                    }
                    // returns a message to be shown on top right corner
                    set_alert('success', _l('updated_successfully', _l('product')));
                }
            }
            redirect(admin_url('venntech/producten'));
        } else {


            $groups = $this->invoice_items_model->get_groups();
            $groups = array_map(function ($group) {
                $_group['id'] = $group['id'];
                $_group['name'] = $group['name'];

                return $_group;
            }, $groups);

            if ($id == "") {

                if(!staff_can('create', FEATURE_PRODUCTEN)) {
                    access_denied(_l('create').' VENNTECH '. _l('producten'));
                }

                $items_extra = new stdClass();
                $items_extra->id = "";
                $items_extra->item_id = "";
                $items_extra->estimate_description = "";
                $items_extra->technical_description = "";
                $items_extra->active = "";
                $items_extra->image_path = "";
                $items_extra->forfait = "";
                $items_extra->kilo_watt_piek = "";
                $items_extra->kilo_watt_uur = "";
                $items_extra->gewicht = "";
                $items_extra->inkoopprijs = "";
                $items_extra->aanbevolen_verkoopprijs = "";
                $items_extra->transport_prijs = "";

                $items = new stdClass();
                $items->itemid = "";
                $items->description = "";
                $items->long_description = "";
                $items->rate = "";
                $items->tax = "";
                $items->tax2 = "";
                $items->unit = "";
                $items->group_id = "";

                $data['title'] = _l('add_new', _l('product'));

                $data['items'] = $items;
                $data['items_extra'] = $items_extra;

                $data['groups'] = $groups;

                $data['edit_type'] = 'create';
                $this->load->view('venntech/product_view', $data);

            } else {

                $items_extra = $this->product_model->get($id);
                $items = $this->invoice_items_model->get($items_extra->item_id);

                $data['title'] = _l('edit', _l('product'));

                // form data
                $data['items'] = $items;
                $data['items_extra'] = $items_extra;

                // selectbox for groups
                $data['groups'] = $groups;

                $data['edit_type'] = 'edit';
                $this->load->view('venntech/product_view', $data);
            }
        }

    }


    public function change_product_status($id, $status)
    {
        if(!staff_can('edit', FEATURE_PRODUCTEN)) {
            ajax_access_denied();
        }
        $this->product_model->change_product_status($id, $status);
    }

    public function delete($id = '')
    {
        if(!staff_can('delete', FEATURE_PRODUCTEN)) {
            access_denied(_l('delete').' VENNTECH '. _l('producten'));
        }

        $items_extra = $this->product_model->get($id);
        $image_path = $items_extra->image_path;
        // this will also delete items_extra with a hook, see venntech.php
        $success = $this->invoice_items_model->delete($items_extra->item_id);

        if ($success) {
            // returns a message to be shown on top right corner
            handle_venntech_product_delete($image_path);
            set_alert('success', _l('deleted', _l('product')));
        }
        redirect(admin_url('venntech/producten'));

    }
}
