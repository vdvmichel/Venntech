<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inputparameters extends AdminController
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

        if ($this->input->post()) {
            // save values..
            if (!staff_can('edit', FEATURE_SETTINGS)) {
                access_denied(_l('edit') . ' VENNTECH ' . _l('settings'));
            }

            $data = $this->input->post();
            update_option('venntech_bebat_batterij', $data['venntech_bebat_batterij']);
            update_option('venntech_unit_prijs_per_panel', $data['venntech_unit_prijs_per_panel']);
            update_option('venntech_vollasturen', $data['venntech_vollasturen']);
            update_option('venntech_premie_tot_31_maart', $data['venntech_premie_tot_31_maart']);
            update_option('venntech_premie_4kw', $data['venntech_premie_4kw']);
            update_option('venntech_premie_4_6kw', $data['venntech_premie_4_6kw']);
            update_option('venntech_premie_6kw', $data['venntech_premie_6kw']);
            update_option('venntech_aftopping_premie', $data['venntech_aftopping_premie']);
            update_option('venntech_voor_tarief_zonder_batterij', $data['venntech_voor_tarief_zonder_batterij']);
            update_option('venntech_voor_tarief_met_batterij', $data['venntech_voor_tarief_met_batterij']);
            update_option('venntech_na_tarief_zonder_batterij', $data['venntech_na_tarief_zonder_batterij']);
            update_option('venntech_na_tarief_met_batterij', $data['venntech_na_tarief_met_batterij']);
            update_option('venntech_aftopping_premie', $data['venntech_aftopping_premie']);
            update_option('venntech_prijs_1kwh', $data['venntech_prijs_1kwh']);
            update_option('voor_zonder_percentage_zelf', $data['voor_zonder_percentage_zelf']);
            update_option('voor_zonder_prijs_zelf_verbruik', $data['voor_zonder_prijs_zelf_verbruik']);
            update_option('voor_zonder_percentage_injectie', $data['voor_zonder_percentage_injectie']);
            update_option('voor_zonder_prijs_injectie', $data['voor_zonder_prijs_injectie']);
            update_option('voor_met_percentage_zelf', $data['voor_met_percentage_zelf']);
            update_option('voor_met_prijs_zelf_verbruik', $data['voor_met_prijs_zelf_verbruik']);
            update_option('voor_met_percentage_injectie', $data['voor_met_percentage_injectie']);
            update_option('voor_met_prijs_injectie', $data['voor_met_prijs_injectie']);
            update_option('na_met_percentage_zelf', $data['na_met_percentage_zelf']);
            update_option('na_met_prijs_zelf_verbruik', $data['na_met_prijs_zelf_verbruik']);
            update_option('na_met_percentage_injectie', $data['na_met_percentage_injectie']);
            update_option('na_met_prijs_injectie', $data['na_met_prijs_injectie']);
            update_option('na_zonder_percentage_zelf', $data['na_zonder_percentage_zelf']);
            update_option('na_zonder_prijs_zelf_verbruik', $data['na_zonder_prijs_zelf_verbruik']);
            update_option('na_zonder_percentage_injectie', $data['na_zonder_percentage_injectie']);
            update_option('na_zonder_prijs_injectie', $data['na_zonder_prijs_injectie']);
            update_option('venntech_hespul_waarde', $data['venntech_hespul_waarde']);


            redirect(admin_url('venntech/inputparameters',));
        } else {
            $data = [];
            $data['venntech_bebat_batterij'] = get_option('venntech_bebat_batterij');
            $data['venntech_unit_prijs_per_panel'] = get_option('venntech_unit_prijs_per_panel');
            $data['venntech_vollasturen'] = get_option('venntech_vollasturen');
            $data['venntech_premie_tot_31_maart'] = get_option('venntech_premie_tot_31_maart');
            $data['venntech_premie_4kw'] = get_option('venntech_premie_4kw');
            $data['venntech_premie_4_6kw'] = get_option('venntech_premie_4_6kw');
            $data['venntech_premie_6kw'] = get_option('venntech_premie_6kw');
            $data['venntech_aftopping_premie'] = get_option('venntech_aftopping_premie');
            $data['venntech_voor_tarief_zonder_batterij'] = get_option('venntech_voor_tarief_zonder_batterij');
            $data['venntech_voor_tarief_met_batterij'] = get_option('venntech_voor_tarief_met_batterij');
            $data['venntech_na_tarief_zonder_batterij'] = get_option('venntech_na_tarief_zonder_batterij');
            $data['venntech_na_tarief_met_batterij'] = get_option('venntech_na_tarief_met_batterij');
            $data['venntech_prijs_1kwh'] = get_option('venntech_prijs_1kwh');
            $data['voor_zonder_percentage_zelf'] = get_option('voor_zonder_percentage_zelf');
            $data['voor_zonder_prijs_zelf_verbruik'] = get_option('voor_zonder_prijs_zelf_verbruik');
            $data['voor_zonder_percentage_injectie'] = get_option('voor_zonder_percentage_injectie');
            $data['voor_zonder_prijs_injectie'] = get_option('voor_zonder_prijs_injectie');
            $data['voor_met_percentage_zelf'] = get_option('voor_met_percentage_zelf');
            $data['voor_met_prijs_zelf_verbruik'] = get_option('voor_met_prijs_zelf_verbruik');
            $data['voor_met_percentage_injectie'] = get_option('voor_met_percentage_injectie');
            $data['voor_met_prijs_injectie'] = get_option('voor_met_prijs_injectie');
            $data['na_met_percentage_zelf'] = get_option('na_met_percentage_zelf');
            $data['na_met_prijs_zelf_verbruik'] = get_option('na_met_prijs_zelf_verbruik');
            $data['na_met_percentage_injectie'] = get_option('na_met_percentage_injectie');
            $data['na_met_prijs_injectie'] = get_option('na_met_prijs_injectie');
            $data['na_zonder_percentage_zelf'] = get_option('na_zonder_percentage_zelf');
            $data['na_zonder_prijs_zelf_verbruik'] = get_option('na_zonder_prijs_zelf_verbruik');
            $data['na_zonder_percentage_injectie'] = get_option('na_zonder_percentage_injectie');
            $data['na_zonder_prijs_injectie'] = get_option('na_zonder_prijs_injectie');
            $data['venntech_hespul_waarde'] = get_option('venntech_hespul_waarde');


            $this->load->view('venntech/inputparameters_view', $data);
        }
    }


    public function voor_met_batterij()
    {
        $data = $this->input->post();

        $euro = ($voor_met_percentage_zelf / 100) * $voor_met_prijs_zelf_verbruik;
        $injectie = ($voor_met_percentage_injectie / 100) * $voor_met_prijs_injectie;
        $voor_met_result = $euro + $injectie;
        return $voor_met_result;
    }

    public function voor_zonder_batterij()
    {
        $euro = ($voor_zonder_percentage_zelf / 100) * $voor_zonder_prijs_zelf_verbruik;
        $injectie = ($voor_zonder_percentage_injectie / 100) * $voor_zonder_prijs_injectie;
        $voor_zonder_result = $euro + $injectie;
        return $voor_zonder_result;
    }

    public function na_met_batterij()
    {
        $euro = ($na_met_percentage_zelf / 100) * $na_met_prijs_zelf_verbruik;
        $injectie = ($na_met_percentage_injectie / 100) * $na_met_prijs_injectie;
        $na_met_result = $euro + $injectie;
        return $na_met_result;
    }

    public function na_zonder_batterij()
    {
        $euro = ($na_zonder_percentage_zelf / 100) * $na_zonder_prijs_zelf_verbruik;
        $injectie = ($na_zonder_percentage_injectie / 100) * $na_zonder_prijs_injectie;
        $na_zonder_result = $euro + $injectie;
        return $na_zonder_result;
    }
}
