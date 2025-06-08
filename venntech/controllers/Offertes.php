<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Offertes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('estimates_extra_items_model');
        $this->load->model('estimates_extra_meerwerken_model');
        $this->load->model('estimates_extra_model');
        $this->load->model('estimate_template_model');
        $this->load->model('estimate_template_element_model');
        $this->load->model('estimate_template_items_model');
        $this->load->model('taxes_model');
        $this->load->model('estimates_model');
        $this->load->model('currencies_model');
        $this->load->model('clients_model');
        $this->load->model('invoice_items_model');
        $this->load->model('samengestelde_product_items_model');
        $this->load->model('samengestelde_product_model');
        $this->load->model('product_model');
        $this->load->model('type_kortingen_model');
        // Ensure custom fields model is loaded if not already part of AdminController or App_Model
        // For Perfex, custom fields are often handled by a general model or directly via DB.
        // Let's assume direct DB access for custom fields for now, as is common in Perfex.
    }

    // Private helper function to get staff commission percentage
    private function _get_staff_commission_percentage_value($staff_id) {
        if (!$staff_id) {
            return 0.00;
        }

        $this->db->select('id');
        $this->db->where('fieldto', 'staff');
        $this->db->where('slug', 'commissie_percentage');
        $custom_field = $this->db->get(db_prefix() . 'customfields')->row();

        if ($custom_field) {
            $this->db->select('value');
            $this->db->where('relid', $staff_id);
            $this->db->where('fieldid', $custom_field->id);
            $custom_field_value = $this->db->get(db_prefix() . 'customfieldsvalues')->row();

            if ($custom_field_value && is_numeric($custom_field_value->value)) {
                return (float) $custom_field_value->value;
            }
        }
        return 0.00;
    }

    /* List all available groepen */
    public function index()
    {

        if (!staff_can('create', FEATURE_ESTIMATE)) {
            access_denied(_l('add_new', _l('estimate')));
        }
        $data['title'] = _l('estimate');
        $this->load->view('venntech/estimates_extras_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_ESTIMATE)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/estimates_extra_table'));
    }

    public function edit($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            $client = $this->clients_model->get($data['clientid']);
            $estimate_template_id = $data['estimate_template_id'];
            $template_items_item_arr = [];
            if (array_key_exists('estimate_items', $data)) {
                $template_items_item_arr = $data['estimate_items'];
            }

            $all_verbruiksmaterialen = isset($data['all_verbruiksmaterialen']) && !empty($data['all_verbruiksmaterialen']) ? $data['all_verbruiksmaterialen'] : array();

            // zonnepanelen_merk = item description
            // zonnepanelen_vermogen = item_extra kWp * 1000
            // totaal_vermogen = zonnepanelen_vermogen * aantal
            $zonnepanelen_group_id = get_option('zonnepanelen_id');
            $zonnepaneel_template_item = $this->estimate_template_items_model->get_by_estimate_template_releation($estimate_template_id, $zonnepanelen_group_id, 'groups');
            $selected_zonnepaneel_item_id = $template_items_item_arr[$zonnepaneel_template_item->id]['items_id'][0];
            $zonnepaneel_invoice_item = $this->invoice_items_model->get($selected_zonnepaneel_item_id);
            $zonnepaneel_product = $this->product_model->get_by_item_id($selected_zonnepaneel_item_id);

            $zonnepanelen_merk = $zonnepaneel_invoice_item->description;
            $zonnepanelen_vermogen = $zonnepaneel_product->kilo_watt_piek * 1000;
            $totaal_vermogen = $zonnepanelen_vermogen * $data['number_of_panels'];


            if ($data['id'] == "") {
                // create
                // we need to create first an estimate because we need its id..
                $estimate = [];
                $estimate['clientid'] = $data['clientid'];
                $estimate['number'] = get_option("next_estimate_number");
                $estimate['date'] = _d(date('Y-m-d'));
                $estimate['expirydate'] = _d(date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                $estimate['currency'] = $this->currencies_model->get_base_currency()->id;

                $estimate['billing_street'] = $client->billing_street;
                $estimate['billing_city'] = $client->billing_city;
                $estimate['billing_state'] = $client->billing_state;
                $estimate['billing_zip'] = $client->billing_zip;
                $estimate['billing_country'] = $client->billing_country;

                $estimates_id = $this->estimates_model->add($estimate);

                if ($estimates_id) {

                    $estimate_extras = [];
                    $estimate_extras['estimates_id'] = $estimates_id;
                    $estimate_extras['estimate_template_id'] = $estimate_template_id;
                    $estimate_extras['tax_id'] = $data['tax_id'];
                    $estimate_extras['korting_type_id'] = $data['korting_type_id'];
                    $estimate_extras['margin_of_profit'] = get_option('venntech_margin_of_profit');
                    $estimate_extras['number_of_panels'] = $data['number_of_panels'];
                    $estimate_extras['hespul_waarde'] = $data['hespul_waarde'];
                    $estimate_extras['zonnepaneel_merk'] = $zonnepanelen_merk;
                    $estimate_extras['zonnepanneel_vermogen'] = $zonnepanelen_vermogen;
                    $estimate_extras['totaal_vermogen'] = $totaal_vermogen;
                    $estimate_extras['naam_sales_verkoper'] = get_staff_full_name($data['staffid']);
                    $estimate_extras['staffid'] = $data['staffid'];

                    $staff_phone = $this->db->where('staffid' , $data['staffid'])->get(db_prefix().'staff')->row('phonenumber');
                    $estimate_extras['sale_agent_phonenumber'] = $staff_phone;

                    // Determine commission percentage
                    $commission_percentage = 0.00;
                    if (isset($data['commission_percentage']) && is_numeric($data['commission_percentage']) && $data['commission_percentage'] !== '') {
                        // Use value from form input if it's valid and provided
                        $commission_percentage = (float) $data['commission_percentage'];
                    } else {
                        // Otherwise, fetch the default for the staff member
                        $commission_percentage = $this->_get_staff_commission_percentage_value($data['staffid']);
                    }
                    $estimate_extras['commission_percentage'] = $commission_percentage;
                    // Commission amount will be calculated and updated after subtotal is known

                    $estimates_extra_id = $this->estimates_extra_model->add($estimate_extras);

                    if ($estimates_extra_id) {
                        // This function also updates the main estimate's subtotal and total
                        $updated_estimate_totals = $this->update_estimate_itemables($estimate_extras, $estimates_extra_id, $estimate_template_id, $template_items_item_arr, $all_verbruiksmaterialen);

                        $commission_amount = 0.00;
                        if ($commission_percentage > 0 && isset($updated_estimate_totals['subtotal'])) {
                            $commission_amount = round(($updated_estimate_totals['subtotal'] * $commission_percentage) / 100, 2);
                        }

                        // Add commission_amount to $estimate_extras for custom field update and pc_estimates_extra update
                        $estimate_extras['commission_amount'] = $commission_amount;

                        // Update pc_estimates_extra with commission_amount AND the original commission_percentage
                        $this->estimates_extra_model->edit([
                            'commission_amount' => $commission_amount,
                            'commission_percentage' => $commission_percentage // Ensure this is also passed if edit only updates specified fields
                        ], $estimates_extra_id);

                        $this->update_customfieldsvalues($estimate_extras); // Now $estimate_extras contains commission_amount
                        set_alert('success', _l('added_successfully', _l('estimates')));
                    } else {
                        // Error adding to estimates_extra_model
                        set_alert('danger', _l('problem_creating', _l('estimates_extra')));
                    }

                } else {
                    set_alert('error', _l('problem_creating', _l('estimate'))); // Error from $this->estimates_model->add()
                }
            } else {

                $estimates_extra_id = $data['id'];
                $estimates_extra = $this->estimates_extra_model->get($estimates_extra_id);

                // create
                $client = $this->clients_model->get($data['clientid']);
                $estimate_template_id = $data['estimate_template_id'];


                // we need to update estimate..
                $estimates_id = $estimates_extra->estimates_id;
                $original_estimate = $this->estimates_model->get($estimates_id);
                $estimate = [];
                $estimate['clientid'] = $data['clientid'];
                $estimate['number'] = $original_estimate->number;
                $estimate['date'] = _d(date('Y-m-d'));
                $estimate['expirydate'] = _d(date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                $estimate['currency'] = $this->currencies_model->get_base_currency()->id;

                $estimate['billing_street'] = $client->billing_street;
                $estimate['billing_city'] = $client->billing_city;
                $estimate['billing_state'] = $client->billing_state;
                $estimate['billing_zip'] = $client->billing_zip;
                $estimate['billing_country'] = $client->billing_country;

                $estimate['shipping_street'] = $client->shipping_street;

                // remove all existing items by items_id array
                $estimate['removed_items'] = $this->get_original_estimates_item_ids($estimates_id);

                $this->estimates_model->update($estimate, $estimates_id);

                $estimate_extras = [];
                $estimate_extras['estimates_id'] = $estimates_id;
                $estimate_extras['estimate_template_id'] = $estimate_template_id;
                $estimate_extras['tax_id'] = $data['tax_id'];
                $estimate_extras['korting_type_id'] = $data['korting_type_id'];
                $estimate_extras['margin_of_profit'] = get_option('venntech_margin_of_profit');
                $estimate_extras['number_of_panels'] = $data['number_of_panels'];
                $estimate_extras['hespul_waarde'] = $data['hespul_waarde'];
                $estimate_extras['zonnepaneel_merk'] = $zonnepanelen_merk;
                $estimate_extras['zonnepanneel_vermogen'] = $zonnepanelen_vermogen;;
                $estimate_extras['totaal_vermogen'] = $totaal_vermogen;
                $estimate_extras['naam_sales_verkoper'] = get_staff_full_name($data['staffid']);
                $estimate_extras['staffid'] = $data['staffid'];

                $staff_phone = $this->db->where('staffid' , $data['staffid'])->get(db_prefix().'staff')->row('phonenumber');
                    $estimate_extras['sale_agent_phonenumber'] = $staff_phone;

                // Determine commission percentage
                $commission_percentage = 0.00;
                if (isset($data['commission_percentage']) && is_numeric($data['commission_percentage']) && $data['commission_percentage'] !== '') {
                    // Use value from form input if it's valid and provided
                    $commission_percentage = (float) $data['commission_percentage'];
                } else {
                    // Otherwise, fetch the default for the staff member
                    $commission_percentage = $this->_get_staff_commission_percentage_value($data['staffid']);
                }
                $estimate_extras['commission_percentage'] = $commission_percentage;
                // Commission amount will be calculated and updated after subtotal is known

                $success_edit_extra = $this->estimates_extra_model->edit($estimate_extras, $estimates_extra_id);

                if ($success_edit_extra) {
                    // This function also updates the main estimate's subtotal and total
                    $updated_estimate_totals = $this->update_estimate_itemables($estimate_extras, $estimates_extra_id, $estimate_template_id, $template_items_item_arr, $all_verbruiksmaterialen);

                    $commission_amount = 0.00;
                    if ($commission_percentage > 0 && isset($updated_estimate_totals['subtotal'])) {
                        $commission_amount = round(($updated_estimate_totals['subtotal'] * $commission_percentage) / 100, 2);
                    }

                    // Add commission_amount to $estimate_extras for custom field update and pc_estimates_extra update
                    $estimate_extras['commission_amount'] = $commission_amount;

                    // Update pc_estimates_extra with commission_amount AND the original commission_percentage
                    $this->estimates_extra_model->edit([
                        'commission_amount' => $commission_amount,
                        'commission_percentage' => $commission_percentage // Ensure this is also passed if edit only updates specified fields
                    ], $estimates_extra_id);

                    $this->update_customfieldsvalues($estimate_extras); // Now $estimate_extras contains commission_amount
                    set_alert('success', _l('updated_successfully', _l('estimates')));
                } else {
                     set_alert('danger', _l('problem_updating', _l('estimates_extra')));
                }
            }

            if (isset($estimates_id) && array_key_exists('save_and_send', $data)) {
                $this->estimates_model->send_estimate_to_client($estimates_id, '', true, '', true);
            }

            redirect(admin_url('venntech/offertes'));
        } else {
            $data = [];
            $data['members'] = get_members_option($this->staff_model->get());;
            $data['estimate_templates_options'] = $this->estimate_template_model->get_options();
            $data['taxes'] = $this->taxes_model->get();
            $data['korting_types'] = $this->type_kortingen_model->get();
            $data['verbruiksmaterialen'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('verbruiksmateriaal_id'));

            if ($id == "") {
                $item = new stdClass();
                $item->id = "";
                $item->clientid = "";
                $item->estimates_id = "";
                $item->estimate_template_id = "";
                $item->tax_id = get_default_tax_id($data['taxes']);
                $item->korting_type_id = 1;
                $item->number_of_panels = 10;
                $item->hespul_waarde = get_option('venntech_hespul_waarde');
                $item->staffid = get_staff_user_id();

                $data['item'] = $item;
                $data['all_verbruiksmaterialen'] = [];
                $data['title'] = _l('add_new', _l('estimate'));
            } else {

                $item = $this->estimates_extra_model->get($id);

                $estimates = $this->estimates_model->get($item->estimates_id);
                $clientid = $estimates->clientid;

                $data['item'] = $item;
                $data['clientid'] = $clientid;
                $data['all_verbruiksmaterialen'] = $this->estimates_extra_meerwerken_model->get_by_estimate_extra_id($id);
                $data['title'] = _l('edit', _l('estimate'));
            }

            // final step fill in tblitemable and tblitem_tax

            $this->load->view('venntech/estimates_extra_view', $data);
        }

    }

    // call offerte template and return a html for selecting groups
    // list of samengestelde producten en producten...
    public function estimate_template_items_html()
    {
        // get groups of template
        $data = $this->input->post();
        $template_id = $data['template_id'];
        $estimates_extra_id = $data['estimates_extra_id'];
        $estimate_template_items = $this->estimate_template_items_model->get_by_estimate_template_id($template_id);

        $estimate_template_element_ids = [];
        foreach ($estimate_template_items as $template_item) {
            $estimate_template_element_ids[] = $template_item['estimate_template_element_id'];
        }
        $estimate_template_element_ids = array_unique($estimate_template_element_ids, SORT_NUMERIC);

        // get invoice items of group to render in select
        //$grouped_items = $this->invoice_items_model->get_grouped();
        $grouped_items = $this->get_grouped();
        // foreach($grouped_items as $gp_key => $gp_val){
        //     $is_active = $this->db->where('item_id' , $gp_val['id'])->get(db_prefix().'pc_items_extra')->row('active');
        //     if(!$is_active){
        //         unset($grouped_items[$gp_key]);
        //     }
        // }

        $html = '';

        foreach ($estimate_template_element_ids as $template_element_id) {
            $template_element = $this->estimate_template_element_model->get($template_element_id);

            $html .= '<div class="col-md-12"><h4>' . $template_element->name . '</h4></div>';
            foreach ($estimate_template_items as $template_item) {
                $template_items_id = $template_item['id'];

                if ($template_item['estimate_template_element_id'] == $template_element_id) {
                    if ($template_item['rel_type'] == 'groups') {

                        $items_id = '';
                        $qty = 1;

                        $estimate_extra_item = $this->getEstimateExtraItem($estimates_extra_id, $template_item);
                        if (isset($estimate_extra_item)) {
                            $items_id = $estimate_extra_item['items_id'];
                            $qty = $estimate_extra_item['quantity'];
                        }

                        if (array_key_exists($template_item['rel_id'], $grouped_items)) {
                            $group_items = $grouped_items[$template_item['rel_id']];
                            $group_items_options = array_map(function ($arr_item) {
                                $_item['id'] = $arr_item['id'];
                                $_item['name'] = $arr_item['description'];
                                $_item['group_name'] = $arr_item['group_name'];

                                return $_item;
                            }, $group_items);
                        } else {
                            // get group and its items
                            $group_id = $template_item['rel_id'];
                            $group_items_options = $this->product_model->get_active_items_combobox_by_groupid($group_id);
                        }

                        if (sizeof($group_items_options) > 0) {
                            $html .= '<div class="col-xs-8">';
                            $html .= render_select('estimate_items[' . $template_items_id . '][items_id][]', $group_items_options, array('id', 'name'), $group_items_options[0]['group_name'], $items_id, ['required' => true]);
                            $html .= '</div>';
                            $html .= '<div class="col-xs-4">';

                            if ($template_item['multiply']) {
                                $tooltip_html = '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l('multiply_with_number_of_panels') . '"></i>';
                                $html .= '<label class="control-label clearfix"> ' . $tooltip_html . _l('multiply') . '</label>';
                                $html .= '<i class="fa fa-check-circle text-light-green mtop10"></i>';
                                // set default to 0, since qty is equal to aantal panelen..
                                $html .= form_hidden('estimate_items[' . $template_items_id . '][qty][]', 0);
                            } else {
                                $html .= render_input('estimate_items[' . $template_items_id . '][qty][]', _l('quantity_as_qty'), $qty, 'number', ['required' => true]);
                            }
                            $html .= '</div>';
                        }
                    } else if ($template_item['rel_type'] == 'samengestelde_product') {
                        $samengestelde_product = $this->samengestelde_product_model->get($template_item['rel_id']);
                        $samengestelde_product_items = $this->samengestelde_product_items_model->get($template_item['rel_id']);

                        foreach ($samengestelde_product_items as $samengestelde_product_item) {

                            $items_id = $samengestelde_product_item['item_id'];
                            $item = $this->invoice_items_model->get($items_id);
                            $qty = 1;

                            $estimate_extra_item = $this->getEstimateExtraItem($estimates_extra_id, $template_item, $items_id);
                            if (isset($estimate_extra_item)) {
                                $qty = $estimate_extra_item['quantity'];
                            }

                            $html .= '<div class="col-xs-8"><div class="form-group">';
                            $html .= '<label class="control-label">' . _l('invoice_table_item_heading') . '</label>';
                            $html .= '<div>' . $item->description . '</div>';
                            $html .= form_hidden('estimate_items[' . $template_items_id . '][items_id][]', $items_id);
                            $html .= '</div></div>';
                            $html .= '<div class="col-xs-4">';

                            if ($template_item['multiply']) {
                                $tooltip_html = '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l('multiply_with_number_of_panels') . '"></i>';
                                $html .= '<label class="control-label clearfix"> ' . $tooltip_html . _l('multiply') . '</label>';
                                $html .= '<i class="fa fa-check-circle text-light-green mtop10"></i>';
                                // set default to 0, since qty is equal to aantal panelen..
                                $html .= form_hidden('estimate_items[' . $template_items_id . '][qty][]', 0);
                            } else {
                                $html .= render_input('estimate_items[' . $template_items_id . '][qty][]', _l('quantity_as_qty'), $qty, 'number', ['required' => true]);
                            }

                            $html .= '</div>';
                        }

                    } else if ($template_item['rel_type'] == 'items') {
                        // items
                        $item_id = $template_item['rel_id'];
                        $item = $this->invoice_items_model->get($item_id);

                        $qty = 1;
                        $estimate_extra_item = $this->getEstimateExtraItem($estimates_extra_id, $template_item, $items_id);
                        if (isset($estimate_extra_item)) {
                            $qty = $estimate_extra_item['quantity'];
                        }

                        $html .= '<div class="col-xs-8"><div class="form-group">';
                        $html .= '<label class="control-label">' . _l('invoice_table_item_heading') . '</label>';
                        $html .= '<div>' . $item->description . '</div>';
                        $html .= form_hidden('estimate_items[' . $template_items_id . '][items_id][]', $item_id);
                        $html .= '</div></div>';
                        $html .= '<div class="col-xs-4">';

                        if ($template_item['multiply']) {
                            $tooltip_html = '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l('multiply_with_number_of_panels') . '"></i>';
                            $html .= '<label class="control-label clearfix"> ' . $tooltip_html . _l('multiply') . '</label>';
                            $html .= '<i class="fa fa-check-circle text-light-green mtop10"></i>';
                            // set default to 0, since qty is equal to aantal panelen..
                            $html .= form_hidden('estimate_items[' . $template_items_id . '][qty][]', 0);
                        } else {
                            $html .= render_input('estimate_items[' . $template_items_id . '][qty][]', _l('quantity_as_qty'), $qty, 'number', ['required' => true]);
                        }


                        $html .= '</div>';
                    }
                }
            }

            $html .= '</div>';
        }
        $html .= '';

        echo json_encode([
            'success' => true,
            'groupsHTML' => $html
        ]);
    }

    public function get_original_estimates_item_ids($estimates_id)
    {
        $this->db->where('rel_id', $estimates_id);
        $this->db->where('rel_type', 'estimate');
        $items = $this->db->get(db_prefix() . "itemable")->result_array();
        $all_ids = [];
        foreach ($items as $item) {
            $all_ids[] = $item['id'];
        }
        return $all_ids;
    }

    public function update_customfieldsvalues($estimate_extras)
    {
        $custom_field_slugs = [
            'number_of_panels' => 'estimate_number_of_panels',
            'zonnepaneel_merk' => 'estimate_zonnepaneel_merk',
            'zonnepanneel_vermogen' => 'estimate_zonnepanneel_vermogen',
            'totaal_vermogen' => 'estimate_totaal_vermogen',
            'sale_agent_phonenumber' => 'estimate_sale_agent_phonenumber',
            'naam_sales_verkoper' => 'estimate_naam_sales_verkoper',
            'commission_percentage' => 'estimate_commission_percentage',
            'commission_amount' => 'estimate_commission_amount',
        ];

        // delete all related custom fields
        $this->db->where('relid', $estimate_extras['estimates_id']);
        $this->db->where('fieldto', 'estimate');
        $this->db->delete(db_prefix() . "customfieldsvalues");

        foreach ($custom_field_slugs as $key => $slug) {

            $this->db->where('slug', $slug);
            $custom_field = $this->db->get(db_prefix() . "customfields")->row();

            if ($custom_field && isset($estimate_extras[$key])) { // Ensure the key exists in $estimate_extras
                $custom['relid'] = $estimate_extras['estimates_id'];
                $custom['fieldid'] = $custom_field->id;
                $custom['fieldto'] = 'estimate';
                $custom['value'] = $estimate_extras[$key];
                $this->db->insert(db_prefix() . "customfieldsvalues", $custom);
                $this->db->insert_id();
            }
        }
    }

    public function update_estimate_itemables($estimate_extras, $estimates_extra_id, $estimate_template_id, $template_items_item_arr, $all_verbruiksmaterialen)
    {
        $this->estimates_extra_items_model->delete_by_extra_id($estimates_extra_id);
        $this->estimates_extra_meerwerken_model->delete_by_extra_id($estimates_extra_id);

        // aantal vlakken = aantal vlakken - 1
        $aantal_vlakken_item_id = get_option('aantal_vlakken_id');

        $all_items = [];
        $all_element_ids = [];

        $battery_ids = [];
        $battery_ids[] = get_option("hybride_batterij_id");
        $battery_ids[] = get_option("retrofit_batterij_id");

        $estimates_id = $estimate_extras['estimates_id'];
        $number_of_panels = $estimate_extras['number_of_panels'];
        $kilogram_of_battery = 0;
        $margin_of_profit = $estimate_extras['margin_of_profit'];
        $tax = $this->taxes_model->get($estimate_extras['tax_id']);
        $korting = $this->type_kortingen_model->get($estimate_extras['korting_type_id']);

        $discount_type = $korting->is_voor_btw ? 'before_tax' : 'after_tax';
        $discount_percent = $korting->discount_percentage;

        foreach ($template_items_item_arr as $key => $items_arr) {
            $template_items = $this->estimate_template_items_model->get($key);
            // $key = items_id or qty
            $item_id_arr = $items_arr['items_id'];
            $item_qty_arr = $items_arr['qty'];

            foreach ($item_id_arr as $key => $items_id) {

                $product = $this->product_model->get_by_item_id($items_id);
                $item = $this->invoice_items_model->get($items_id);
                // sum battery kilograms
                if(in_array($item->group_id, $battery_ids)){
                    $kilogram_of_battery += $product->gewicht;
                }

                $qty = $item_qty_arr[$key];
                $total_rate_qty = $qty;
                $rate = $item->rate;

                if ($items_id == $aantal_vlakken_item_id) {
                    $total_rate_qty = $qty - 1;
                } else if ($template_items->multiply) {
                    $qty = $number_of_panels;
                    $total_rate_qty = $number_of_panels;
                }

                if ($margin_of_profit != 25 && !$product->forfait) {
                    $rate = $rate * 0.8 * (1 + $margin_of_profit / 100);
                }

                $extra_item = [];
                $extra_item['estimates_extra_id'] = $estimates_extra_id;
                $extra_item['estimate_template_element_id'] = $template_items->estimate_template_element_id;
                $extra_item['rel_id'] = $template_items->rel_id;
                $extra_item['rel_type'] = $template_items->rel_type;
                $extra_item['items_id'] = $items_id;
                $extra_item['description'] = $item->description;
                $extra_item['multiply'] = $template_items->multiply;
                $extra_item['quantity'] = $qty;
                $extra_item['forfait'] = $product->forfait;
                $extra_item['rate'] = $rate;
                $extra_item['total_rate_quantity'] = $total_rate_qty;
                $extra_item['total_rate'] = $rate * $total_rate_qty;

                $all_items[] = $extra_item;
                $all_element_ids[] = $template_items->estimate_template_element_id;
                $this->estimates_extra_items_model->add($extra_item);
            }
        }

        // now time to save itemable and item_tax!
        $CI = &get_instance();
        $subtotal = 0;
        $totaltax = 0;
        $total = 0;

        // do a for loop in all_items group by estimate_template_element_id
        $unique_element_ids = array_unique($all_element_ids);
        $item_order = 1;
        foreach ($unique_element_ids as $element_id) {
            $estimate_template_element = $this->estimate_template_element_model->get($element_id);

            $itemable = [];
            $itemable['rel_id'] = $estimates_id;
            $itemable['rel_type'] = 'estimate';
            $itemable['description'] = $estimate_template_element->name;

            $itemable['qty'] = 1;
            $element_rate = 0;
            $long_description = '';

            foreach ($all_items as $extra_item) {
                if ($extra_item['estimate_template_element_id'] == $element_id) {
                    $qty = $extra_item['total_rate_quantity'];
                    if ($qty != 0) {
                        $qty_str = $qty == 1 ? '' : ($qty . ' x ');
                        $long_description = $long_description . $qty_str . $extra_item['description'] . '<br />' . "\r\n";
                        $element_rate = $element_rate + $extra_item['total_rate'];
                    }
                }
            }

            $itemable['long_description'] = $long_description;
            $itemable['rate'] = $element_rate;
            $itemable['unit'] = '';
            $itemable['item_order'] = $item_order++;
            $CI->db->insert(db_prefix() . "itemable", $itemable);
            $itemable_id = $CI->db->insert_id();

            $item_total = $itemable['qty'] * $itemable['rate'];
            $subtotal += $item_total;

            $item_tax = [];
            $item_tax['itemid'] = $itemable_id;
            $item_tax['rel_id'] = $estimates_id;
            $item_tax['rel_type'] = 'estimate';
            $item_tax['taxrate'] = $tax->taxrate;
            $item_tax['taxname'] = $tax->name;
            $CI->db->insert(db_prefix() . "item_tax", $item_tax);
        }

        // Meerwerken/producten element (offerte onderdeel)
        if (sizeof($all_verbruiksmaterialen) > 0) {

            $long_description = '';
            $element_rate = 0;
            foreach ($all_verbruiksmaterialen as $key => $items_arr) {

                $items_id = $items_arr['items_id'];
                $qty = $items_arr['qty'];

                $product = $this->product_model->get_by_item_id($items_id);
                $item = $this->invoice_items_model->get($items_id);
                $rate = $item->rate;

                if ($margin_of_profit != 25 && !$product->forfait) {
                    $rate = $rate * 0.8 * (1 + $margin_of_profit / 100);
                }
                
                $extra_meerwerken = [];
                $extra_meerwerken['estimates_extra_id'] = $estimates_extra_id;
                $extra_meerwerken['items_id'] = $items_id;
                $extra_meerwerken['description'] = $item->description;
                $extra_meerwerken['quantity'] = $qty;
                $extra_meerwerken['forfait'] = $product->forfait;
                $extra_meerwerken['rate'] = $rate;
                $extra_meerwerken['total_rate'] = $rate * $qty;
                //$extra_meerwerken['total_rate'] = $rate * $total_rate_qty;
                $this->estimates_extra_meerwerken_model->add($extra_meerwerken);

                // $qty = $extra_item['total_rate_quantity'];
                // if ($qty != 0) {
                //     $qty_str = $qty == 1 ? '' : ($qty . ' x ');
                //     $long_description = $long_description . $qty_str . $extra_meerwerken['description'] . '<br />' . "\r\n";
                //     $element_rate = $element_rate + $extra_meerwerken['total_rate'];
                // }



                $itemable = [];
                $itemable['rel_id'] = $estimates_id;
                $itemable['rel_type'] = 'estimate';
                $itemable['description'] = 'Meerwerken/producten';
                $itemable['qty'] = $qty;
                $itemable['long_description'] = $qty_str . $extra_meerwerken['description'];
                $itemable['rate'] = $rate;
                $itemable['unit'] = '';
                $itemable['item_order'] = $item_order++;
                $CI->db->insert(db_prefix() . "itemable", $itemable);
                $itemable_id = $CI->db->insert_id();

                $item_total = $itemable['qty'] * $itemable['rate'];
                $subtotal += $item_total;

                $item_tax = [];
                $item_tax['itemid'] = $itemable_id;
                $item_tax['rel_id'] = $estimates_id;
                $item_tax['rel_type'] = 'estimate';
                $item_tax['taxrate'] = $tax->taxrate;
                $item_tax['taxname'] = $tax->name;
                $CI->db->insert(db_prefix() . "item_tax", $item_tax);
            }

            
        }

        // milieubijdrage voor zonnepaneel en batterij
        $element_rate = $number_of_panels * get_option("venntech_unit_prijs_per_panel");
        $long_description = "Milieubijdrage Zonnepanelen<br/>";
        if($kilogram_of_battery > 0){
            $long_description .= "Milieubijdrage Batterij<br/>";
            $element_rate += $kilogram_of_battery * get_option("venntech_bebat_batterij");
        }

        $itemable = [];
        $itemable['rel_id'] = $estimates_id;
        $itemable['rel_type'] = 'estimate';
        $itemable['description'] = 'Milieubijdrage';
        $itemable['qty'] = 1;
        $itemable['long_description'] = $long_description;
        $itemable['rate'] = $element_rate;
        $itemable['unit'] = '';
        $itemable['item_order'] = $item_order++;
        $CI->db->insert(db_prefix() . "itemable", $itemable);
        $itemable_id = $CI->db->insert_id();

        $item_total = $itemable['qty'] * $itemable['rate'];
        $subtotal += $item_total;

        $item_tax = [];
        $item_tax['itemid'] = $itemable_id;
        $item_tax['rel_id'] = $estimates_id;
        $item_tax['rel_type'] = 'estimate';
        $item_tax['taxrate'] = $tax->taxrate;
        $item_tax['taxname'] = $tax->name;
        $CI->db->insert(db_prefix() . "item_tax", $item_tax);

        if ($korting->is_voor_btw) {
            $discount_total = $subtotal * $discount_percent / 100;
            $subtotal = $subtotal - $discount_total;
            $total_tax = $tax->taxrate / 100 * $subtotal;
            $total = $subtotal + $total_tax;
        } else {
            $total_tax = $tax->taxrate / 100 * $subtotal;
            $total = $total_tax + $subtotal;
            $discount_total = $total * $discount_percent / 100;
            $total = $total - $discount_total;
        }

        // update subtotal, total_tax and total..
        $CI->db->where('id', $estimates_id);
        $CI->db->update(db_prefix() . "estimates", [
            'subtotal' => $subtotal,
            'total_tax' => $total_tax,
            'total' => $total,
            'discount_percent' => $discount_percent,
            'discount_total' => $discount_total,
            'discount_type' => $discount_type
        ]);

        // Return totals for commission calculation
        return ['subtotal' => $subtotal, 'total' => $total, 'total_tax' => $total_tax];
    }

    /**
     * @param $estimates_extra_id
     * @param $template_item
     * @return mixed|string
     */
    public
    function getEstimateExtraItem($estimates_extra_id, $template_item, $item_id = '')
    {
        if ($estimates_extra_id != '') {
            $estimate_extra_items = $this->estimates_extra_items_model->get_by_estimates_extra_relation($estimates_extra_id, $template_item['rel_id'], $template_item['rel_type']);
            foreach ($estimate_extra_items as $estimate_extra_item) {
                if ($template_item['rel_type'] == 'groups' || $template_item['rel_type'] == 'items') {
                    // we have only 1 data for group en items
                    return $estimate_extra_item;
                } else if ($item_id == $estimate_extra_item['items_id']) {
                    return $estimate_extra_item;
                }
            }
        }

        return null;
    }


    public function get_grouped()
    {
        $items = [];
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            $this->db->where(db_prefix().'pc_items_extra.active',1);
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->join(db_prefix() . 'pc_items_extra', '' . db_prefix() . 'pc_items_extra.item_id = ' . db_prefix() . 'items.id');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }

    public function get_staff_commission_percentage($staff_id = '')
    {
        if (!$staff_id) {
            $staff_id = $this->input->get('staff_id');
        }

        if (!$staff_id) {
            echo json_encode(['success' => false, 'message' => 'Staff ID not provided.']);
            return;
        }

        $commission_percentage = $this->_get_staff_commission_percentage_value($staff_id);

        if ($commission_percentage !== null) {
            echo json_encode(['success' => true, 'commission_percentage' => $commission_percentage]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Commission percentage not found for staff.']);
        }
    }
}