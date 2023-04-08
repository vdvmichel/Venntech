<?php

defined('BASEPATH') or exit('No direct script access allowed');


function render_yes_no_option_venntech($name, $label, $option_value, $tooltip = '', $disabled = false)
{

    $tooltip_html = $tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ' : '';
    $disabled_html = $disabled ? 'disabled' : '';

    $yes_no_html = '<div class="form-group">';
    $yes_no_html .= '<label for="' . $name . '" class="control-label clearfix"> ' . $tooltip_html . $label . '</label>';
    $yes_no_html .= '<div class="radio radio-primary radio-inline">';
    $yes_no_html .= '<input type="radio" id="y_opt_1_' . $name . '" name="' . $name . '" value="1" ' . ($option_value == 1 ? "checked" : "") . ' ' . $disabled_html . '/>';
    $yes_no_html .= '<label for="y_opt_1_' . $name . '">' . _l("settings_yes") . '</label>';
    $yes_no_html .= '</div>';
    $yes_no_html .= '<div class="radio radio-primary radio-inline">';
    $yes_no_html .= '<input type="radio" id="y_opt_2_' . $name . '" name="' . $name . '" value="0" ' . ($option_value == 0 ? "checked" : "") . ' ' . $disabled_html . '/>';
    $yes_no_html .= '<label for="y_opt_2_' . $name . '">' . _l("settings_no") . '</label>';
    $yes_no_html .= '</div>';
    $yes_no_html .= '</div>';

    echo $yes_no_html;
}

function get_type_aansluiting_options()
{
    return [['id' => 1, 'name' => 'mono 230'],
        ['id' => 2, 'name' => '3x400+N'],
        ['id' => 3, 'name' => '3X230']];
}

function get_hellend_options()
{
    return [['id' => 1, 'name' => 'Pannen'],
        ['id' => 2, 'name' => 'Leien'],
        ['id' => 3, 'name' => 'Tegelpannen'],
        ['id' => 4, 'name' => 'Steeldeck']];
}

function get_materiaal_dakgoot_options()
{
    return [['id' => 1, 'name' => 'PVC'],
        ['id' => 2, 'name' => 'Zink'],
        ['id' => 3, 'name' => 'Alu'],
        ['id' => 4, 'name' => 'Bakgoot'],
        ['id' => 5, 'name' => 'Geen']];
}

function get_dakvoorvoer_options()
{
    return [['id' => 1, 'name' => 'Aanwezig'],
        ['id' => 2, 'name' => 'Te voorzien']];
}

function get_woning_options()
{
    return [['id' => 1, 'name' => 'Nieuwbouw'],
        ['id' => 2, 'name' => 'Gerenoveerd'],
        ['id' => 3, 'name' => 'Bestaande']];
}

function get_schaduw_options()
{
    return [['id' => 1, 'name' => 'Geen'],
        ['id' => 2, 'name' => 'Licht'],
        ['id' => 3, 'name' => 'Middelmatig'],
        ['id' => 4, 'name' => 'Veel']];
}

function get_orientatie_options()
{
    return [['id' => 1, 'name' => 'Oost'],
        ['id' => 2, 'name' => 'ZO'],
        ['id' => 3, 'name' => 'Zuid'],
        ['id' => 4, 'name' => 'ZW'],
        ['id' => 5, 'name' => 'West']];
}

function get_kabeltraject_options()
{
    return [['id' => 1, 'name' => 'Gevel'],
        ['id' => 2, 'name' => 'Dakdoorvoer'],
        ['id' => 3, 'name' => 'Koker'],
        ['id' => 4, 'name' => 'Andere']];
}

function get_plat_dak_options()
{
    return [['id' => 1, 'name' => 'EPDM'],
        ['id' => 2, 'name' => 'Roofing'],
        ['id' => 3, 'name' => 'PVC'],
        ['id' => 4, 'name' => 'Andere']];
}

function get_plaats_omvormer_options()
{
    return [['id' => 1, 'name' => 'Zolder'],
        ['id' => 2, 'name' => 'Kelder'],
        ['id' => 3, 'name' => 'Garage'],
        ['id' => 4, 'name' => 'Berging']];
}

function get_monitoring_options()
{
    return [['id' => 1, 'name' => 'Wifi'],
        ['id' => 2, 'name' => 'Lan'],
        ['id' => 3, 'name' => 'Powerline'],
        ['id' => 4, 'name' => 'Geen']];
}

function get_hindernissen_options()
{
    return [['id' => 1, 'name' => 'Veranda'],
        ['id' => 2, 'name' => 'Vijver'],
        ['id' => 4, 'name' => 'Bomen']];
}

function get_differentieel_300ma_aanwezig_options()
{
    return [['id' => 1, 'name' => 'Ja'],
        ['id' => 2, 'name' => 'Nee'],
        ['id' => 4, 'name' => 'Geen Type A']];
}

function get_differentieel_30ma_aanwezig_options()
{
    return [['id' => 1, 'name' => 'Ja'],
        ['id' => 2, 'name' => 'Nee'],
        ['id' => 3, 'name' => 'Geen Type A']];
}


function get_installatie_type2_options()
{
    return [['id' => 1, 'name' => 'Envoy S'],
        ['id' => 2, 'name' => 'Envoy M'],
        ['id' => 3, 'name' => 'Omvormer']];
}

function get_micro_optimizers_options()
{
    return [['id' => 1, 'name' => 'Optimizers/micro omvormers'],
        ['id' => 2, 'name' => 'Traditioneel']];
}

function get_code_installatie_gegevens_options()
{
    return [['id' => 1, 'name' => 'Andere'],
        ['id' => 2, 'name' => 'ADV-SG'],
        ['id' => 3, 'name' => 'HOO'],
        ['id' => 4, 'name' => 'VENN'],
        ['id' => 5, 'name' => 'TEKNICA'],
        ['id' => 6, 'name' => 'AE'],
        ['id' => 7, 'name' => 'ENE'],
        ['id' => 8, 'name' => 'BOG'],
        ['id' => 9, 'name' => 'VAB'],
        ['id' => 10, 'name' => 'HAE'],
        ['id' => 11, 'name' => 'GAR'],
        ['id' => 12, 'name' => 'ADV']];
}

function get_dak_type_options()
{
    return [['id' => 1, 'name' => 'Andere'],
        ['id' => 2, 'name' => 'Tegelpannen'],
        ['id' => 3, 'name' => 'Plat dak FLATFIX'],
        ['id' => 4, 'name' => 'Platdak SCHANS'],
        ['id' => 5, 'name' => 'Leien'],
        ['id' => 6, 'name' => 'Pannen,...']];
}

function get_default_tax_id($taxes)
{
    foreach ($taxes as $tax) {
        if ($tax['taxrate'] == 21) {
            return $tax['id'];
        }
    }

    return null;
}

function get_members_option($members)
{
    $result = [];
    foreach ($members as $member) {
        $result[] = ['id' => $member['staffid'], 'name' => $member['firstname'] . ' ' . $member['lastname']];
    }
    return $result;
}
function get_extra_gegevens_options()
{
    return [['id' => 1, 'name' => 'Andere'],
        ['id' => 2, 'name' => 'Geen onderdak aanwezig'],
        ['id' => 3, 'name' => 'SCHANS constructie'],
        ['id' => 4, 'name' => 'Meer dan 7 meter en/of hoogtewerker'],
        ['id' => 5, 'name' => 'Meer dan 7 meter en/of hoogtewerker'],
        ['id' => 6, 'name' => 'Extra omvormer'],
        ['id' => 7, 'name' => 'Extra zekeringkast afremmen'],
        ['id' => 8, 'name' => 'STOPCONTACT'],
        ['id' => 9, 'name' => 'INTERVENTIE']];
}