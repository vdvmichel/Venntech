<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Berekening_simulatie extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');

    }

    public function index()
    {
        if (!staff_can('view', FEATURE_BEREKENING)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('berekening'));
        }
        $item = new stdClass();
        $item->id = "";
        $item->zonnepaneel_id = "";
        $item->omvormer_id = "";
        $data['item'] = $item;
        $data['zonnepanelen'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('zonnepanelen_id'));
        $data['omvormers'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('omvormers_id'));
        $data['plaatsing'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('plaatsing_id'));
        $data['structuur'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('structuur_id'));
        $data['batterij'] = $this->product_model->get_active_items_combobox_by_groupid(get_option('hybride_batterij_id'));
        $data['hespul_waarde'] = get_option('venntech_hespul_waarde');
        $data['forfait_bedragen'] = get_option('venntech_forfait_bedragen');
        $data['prijs'] = '0';
        $data['result'] = '0';
        $data['eerste'] = '0';
        $data['jaarlijks'] = '0';
        $data['title'] = _l('venntech-terugverdienst_simulatie');
        $this->load->view('venntech/berekening_simulatie_view', $data);
    }


    function berekening()
    {
        $data = $this->input->post();
        $aantal = $data['aantal'];
        $batterij_id = $data['batterij_id'];
        $zonnepaneel_id = $data['zonnepaneel_id'];
        $omvormer_id = $data['omvormer_id'];
        $plaatsing_id = $data['plaatsing_id'];
        $structuur_id = $data['structuur_id'];
        //$totaal_vermogen_kwh = $data['totaal_vermogen_kwh'] * 1;
        $hespul_waarde = $data['hespul_waarde'] * 1;
        $forfait_bedragen = $data['forfait_bedragen'] * 1;
        $is_na_2021_april = $data['is_voor_2021_april'] == 1 ? true : false;

        $terugverdientijd = new Terugverdientijd();
        $result_arr = $terugverdientijd->berekenen($zonnepaneel_id, $aantal, $omvormer_id, $plaatsing_id, $structuur_id, $hespul_waarde, $forfait_bedragen, $batterij_id, $is_na_2021_april);

        echo json_encode($result_arr);
    }


}
