<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Plot\LinePlot;
use Amenadiel\JpGraph\Themes\UniversalTheme;

class Terugverdientijd
{

    /**
     * @return array
     */
    function berekenen($zonnepaneel_id, $aantal, $omvormer_id, $plaatsing_id, $structuur_id, $hespul_waarde, $forfait_bedragen, $batterij_id = '', $is_na_2021_april = true)
    {
        $CI = &get_instance();
        $CI->load->model('invoice_items_model');
        $CI->load->model('venntech/product_model');

        if (isset($batterij_id) && $batterij_id != '') {
            $item_batterij = $CI->invoice_items_model->get($batterij_id);
            $prijs_batterij = $item_batterij->rate * 1;
            $zelfconsumptie = get_option('voor_met_percentage_zelf');;
        } else {
            $prijs_batterij = 0;
            $zelfconsumptie = get_option('voor_zonder_percentage_zelf');;
        }
        $item_zonnepaneel = $CI->invoice_items_model->get($zonnepaneel_id);
        $item_extra_zonnepaneel = $CI->product_model->get_by_item_id($zonnepaneel_id);
        $totaal_vermogen_kwh = $item_extra_zonnepaneel->kilo_watt_piek * $aantal;

        $prijs = ($item_zonnepaneel->rate * 1) * $aantal;
        $item_omvormer = $CI->invoice_items_model->get($omvormer_id);
        $kostprijs_hybride_omvormer = $item_omvormer->rate * 1;
        $item_plaatsing = $CI->invoice_items_model->get($plaatsing_id);
        $plaatsing = $item_plaatsing->rate;
        $item_structuur = $CI->invoice_items_model->get($structuur_id);
        $structuur = ($item_structuur->rate) * $aantal;
        $totaal_vermogen_kwh = $totaal_vermogen_kwh * 1;
        $hespul_waarde = $hespul_waarde * 1;
        $forfait_bedragen = $forfait_bedragen * 1;
        $premie_tot_31_maart = get_option('venntech_premie_tot_31_maart');
        $premie_4kw = get_option('venntech_premie_4kw');
        $premie_4_6kw = get_option('venntech_premie_4_6kw');
        $premie_6kw = get_option('venntech_premie_6kw');
        $aftopping_premie = get_option('venntech_aftopping_premie');
        $waarde_capaciteit = get_option('venntech_waarde_capaciteit');
        if (isset($item_batterij)) {
            $na_cap_tarief_pv_inst = get_option('venntech_na_tarief_met_batterij');
            $voor_cap_tarief_pv_inst = get_option('venntech_voor_tarief_met_batterij');
        } else {
            $na_cap_tarief_pv_inst = get_option('venntech_na_tarief_zonder_batterij');
            $voor_cap_tarief_pv_inst = get_option('venntech_voor_tarief_zonder_batterij');
        }
        $piek_zonder = 5;
        $piek_batt = 4;
        $vollasturen = get_option('venntech_vollasturen');
        $zonnepaneel_investering = $prijs + $plaatsing + $structuur + $kostprijs_hybride_omvormer + $forfait_bedragen + $prijs_batterij;
        // kostprijs_hybride_omvormer heeft geen waarde in excel??
        $calculate_1 = 40 / 100 * ($zonnepaneel_investering - (50 / 100 * $kostprijs_hybride_omvormer));
        $calculate_2 = (4 * $premie_4kw) + (2 * $premie_4_6kw) + (($aftopping_premie - 6) * $premie_6kw);
        $calculate_3 = 0;
        $calculate_4 = 0;
        if ($is_na_2021_april) {
            if ($totaal_vermogen_kwh > 6) {
                $calculate_3 = ((4 * $premie_4kw) + (2 * $premie_4_6kw) + (($totaal_vermogen_kwh - 6) * $premie_6kw));
            } else if ($totaal_vermogen_kwh > 4) {
                $calculate_3 = (4 * $premie_4kw) + (($totaal_vermogen_kwh - 4) * $premie_4_6kw);
            } else {
                $calculate_3 = $totaal_vermogen_kwh * $premie_4kw;
            }
            $result = min($calculate_1, $calculate_2, $calculate_3);
        } else {
            $calculate_investering = 35 / 100 * ($zonnepaneel_investering - (50 / 100 * $kostprijs_hybride_omvormer));
            $calculate_4 = min($calculate_investering, $totaal_vermogen_kwh * $premie_tot_31_maart, 1500);
            $result = $calculate_4;
        }
        $eerste = $voor_cap_tarief_pv_inst * $totaal_vermogen_kwh * $vollasturen / 1000 + $result;
        $jaarlijks = $waarde_capaciteit + $na_cap_tarief_pv_inst * $totaal_vermogen_kwh * $vollasturen / 1000;
        $opbrengst = $this->opbrengst($zonnepaneel_investering, $eerste, $jaarlijks);
        $irr = $this->rendement($zonnepaneel_investering, $eerste, $jaarlijks);
        $verdientijd = $this->verdientijd($zonnepaneel_investering, $result, $voor_cap_tarief_pv_inst, $totaal_vermogen_kwh, $vollasturen, $waarde_capaciteit, $piek_zonder, $piek_batt,);
        $kwh = $this->bereken_kwh($hespul_waarde, $totaal_vermogen_kwh);
        $besparing = $this->co2_besparing($aantal, $totaal_vermogen_kwh);

        $verdientijd_zonder_batterij = $verdientijd * 1.35;
        $geschattekost = $zonnepaneel_investering - $result;

        return [
            'success' => true,
            'na_cap_tarief_pv_inst' => $na_cap_tarief_pv_inst,
            'voor_cap_tarief_pv_inst' => $voor_cap_tarief_pv_inst,
            'geschattekost' => $geschattekost,
            'piek_zonder' => $piek_zonder,
            'piek_batt' => $piek_batt,
            'vollasturen' => $vollasturen,
            'totaal_vermogen_kwh' => $totaal_vermogen_kwh,
            'waarde_capaciteit' => $waarde_capaciteit,
            'verdientijd_zonder_batterij' => $verdientijd_zonder_batterij,
            'zonnepaneel_investering' => $zonnepaneel_investering,
            'plaatsing' => $plaatsing,
            'prijs' => $prijs,
            'prijs_batterij' => $prijs_batterij,
            'structuur' => $structuur,
            'forfait_bedragen' => $forfait_bedragen,
            'hespul_waarde' => $hespul_waarde,
            'kostprijs_hybride_omvormer' => $kostprijs_hybride_omvormer,
            'result' => $result,
            'eerste' => round($eerste, 2),
            'jaarlijks' => round($jaarlijks, 2),
            'calculate_1' => $calculate_1,
            'calculate_2' => $calculate_2,
            'calculate_3' => $calculate_3,
            'calculate_4' => $calculate_4,
            'irr' => $irr,
            'verdientijd' => $verdientijd,
            'kwh' => $kwh,
            'besparing' => $besparing,
            'opbrengst' => $opbrengst,
            'zelfconsumptie' => $zelfconsumptie,
        ];

    }

    /**
     * de array dat berekenen calculeert is result_arr as input parameter..
     */
    function getGraph($result_arr)
    {

        $start_bedrag = $result_arr['zonnepaneel_investering'] * -1;
        $eerste_jaar = $result_arr['eerste'];
        $jaarlijks = $result_arr['jaarlijks'];

        $labels = [];
        $data = [];

        for ($i = 0; $i < 25; $i++) {
            $labels[$i] = '' . $i;
            if ($i === 0) {
                // default start bedrag
            } else if ($i === 1) {
                $start_bedrag += $eerste_jaar;
            } else {
                $start_bedrag += $jaarlijks;
            }
            $data[$i] = $start_bedrag;
        }

        $datay1 = $data;

        $graph = new Graph(800,600);
        $graph->SetScale("textlin");

        $theme_class= new UniversalTheme();
        $graph->SetTheme($theme_class);
        $graph->SetMargin(40,20,33,58);

        $graph->title->Set('Terugverdientijd');
        $graph->SetBox(false);

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);
        $graph->yaxis->title->Set('Winst (€)');

        $graph->xaxis->SetTickLabels($labels);
        $graph->xaxis->title->Set('Tijd (jaren)');

        $graph->ygrid->SetFill(false);

        $p1 = new LinePlot($datay1);
        $graph->Add($p1);

        $p1->SetColor("#55bbdd");
        $p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
        $p1->mark->SetColor('#55bbdd');
        $p1->mark->SetFillColor('#55bbdd');
        $p1->SetCenter();


        $graph->legend->SetFrameWeight(1);
        $graph->legend->SetColor('#4E4E4E','#00A78A');
        $graph->legend->SetMarkAbsSize(8);

        return $graph;
    }

    function rendement($zonnepaneel_investering, $eerste, $jaarlijks)
    {
        $cashflow = $this->get_cashflow_arr($zonnepaneel_investering, $eerste, $jaarlijks);
        return round(IRRCalculator::calculateFromCashFlow($cashflow), 3) * 100;
    }

    function opbrengst($zonnepaneel_investering, $eerste, $jaarlijks)
    {
        return ($jaarlijks * 25) + ($zonnepaneel_investering - $eerste);
    }

    function verdientijd($zonnepaneel_investering, $result, $voor_cap_tarief_pv_inst, $totaal_vermogen_kwh, $vollasturen, $waarde_capaciteit, $piek_zonder, $piek_batt)
    {
        return ($zonnepaneel_investering - $result) / ($voor_cap_tarief_pv_inst * $totaal_vermogen_kwh * $vollasturen / 1000 + ($piek_zonder - $piek_batt) * $waarde_capaciteit);
    }

    public function get_cashflow_arr($zonnepaneel_investering, $eerste, $jaarlijks): array
    {
        $cashflow = [];
        for ($i = 0; $i < 25; $i++) {

            if ($i === 0) {
                $cashflow[$i] = $zonnepaneel_investering * -1;
            } else if ($i === 1) {
                $cashflow[$i] = $eerste;
            } else {
                $cashflow[$i] = $jaarlijks;
            }

        }
        return $cashflow;
    }

    public function bereken_kwh($hespul_waarde, $totaal_vermogen_kwh)
    {
        return ($hespul_waarde / 100) * ($totaal_vermogen_kwh * 1000) * 25;
    }

    public function co2_besparing($aantal, $totaal_vermogen_kwh)
    {
        return 0.1970 * ($totaal_vermogen_kwh * 1000 / $aantal) * 25 * $aantal;
    }

}