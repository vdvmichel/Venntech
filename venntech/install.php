<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!isset($CI)) {
    exit ("CI is not set");
}

$db_prefix = db_prefix();
$charset = $CI->db->char_set;


// if development drop all tables, change this to false when going to production!!!
if (false) {
    //Junction tables drop first because of FK
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_samengestelde_product_items");

    // drop order according to FK
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimate_pdf_layout");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimates_extra_meerwerken");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimates_extra_items");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimates_extra");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimate_template_items");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimate_template_element");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_estimate_template");

    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_items_extra");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_samengestelde_product");

    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_project_template_tasks");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_project_template");

    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport_algemeen");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport_elektriciteit");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport_info_dak");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport_info_pv");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport_image");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_opleverdocument_algemeen");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_opleverdocument_fotos");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_opleverdocument_installatie");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_opleverdocument_verbruiksmaterialen");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_traditioneel_berekening");

    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_inspectie_rapport");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_opleverdocumenten");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_plaatsing_datum");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_plaatsing");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_instellingen_taak");
    $CI->db->query("DROP TABLE IF EXISTS {$db_prefix}pc_korting_type");

    // default settings..
    delete_option('venntech_margin_of_profit');
    delete_option('assign_task_inspectie_rapport_staffid');
    delete_option('venntech_bebat_batterij');
    delete_option('venntech_unit_prijs_per_panel');
    delete_option('venntech_vollasturen');
    delete_option('venntech_premie_tot_31_maart');
    delete_option('venntech_premie_4kw');
    delete_option('venntech_premie_4_6kw');
    delete_option('venntech_premie_6kw');
    delete_option('venntech_aftopping_premie');
    delete_option('venntech_voor_tarief_zonder_batterij');
    delete_option('venntech_voor_tarief_met_batterij');
    delete_option('venntech_na_tarief_zonder_batterij');
    delete_option('venntech_na_tarief_met_batterij');
    delete_option('venntech_prijs_1kwh');
    delete_option('voor_zonder_percentage_zelf',);
    delete_option('voor_zonder_prijs_zelf_verbruik');
    delete_option('voor_zonder_percentage_injectie');
    delete_option('voor_zonder_prijs_injectie');
    delete_option('voor_met_percentage_zelf');
    delete_option('voor_met_prijs_zelf_verbruik');
    delete_option('voor_met_percentage_injectie');
    delete_option('voor_met_prijs_injectie');
    delete_option('na_met_percentage_zelf');
    delete_option('na_met_prijs_zelf_verbruik');
    delete_option('na_met_percentage_injectie');
    delete_option('na_met_prijs_injectie');
    delete_option('na_zonder_percentage_zelf');
    delete_option('na_zonder_prijs_zelf_verbruik');
    delete_option('na_zonder_percentage_injectie');
    delete_option('na_zonder_prijs_injectie');
// groups
    delete_option('zonnepanelen_id');
    delete_option('structuur_id');
    delete_option('omvormers_id');
    delete_option('hybride_batterij_id');
    delete_option('retrofit_batterij_id');
    delete_option('retrofit_toebehoren_id');
    delete_option('$elektrisch_materiaal_id');
    delete_option('meerwerken_id');
    delete_option('verbruiksmateriaal_id');
    delete_option('diff_schakelaars_id');
    delete_option('zekeringen_id');
    delete_option('instalatie');
    delete_option('ac_id');
    delete_option('dc_id');
    delete_option('aarding_id');
    delete_option('buizen_toebehoren_id');
    delete_option('zekering_kast_id');
    delete_option('instalatie_id');
    delete_option('verbruiksmateriaal_extra_id');
    delete_option('venntech_geschatte_vermogen');
    delete_option('venntech_forfait_bedragen');
    delete_option('invoice_company_name');
    delete_option('invoice_company_address');
    delete_option('invoice_company_name');
    delete_option('invoice_company_postal_code');
    delete_option('invoice_company_city');
    delete_option('invoice_company_phonenumber');
    delete_option('company_vat');
}
// default settings..
add_option('venntech_forfait_bedragen', '635');
add_option('venntech_margin_of_profit', '25');
add_option('assign_task_inspectie_rapport_staffid', '1');
add_option('venntech_bebat_batterij', '2.39');
add_option('venntech_unit_prijs_per_panel', '2');
add_option('venntech_vollasturen', '1000');
add_option('venntech_premie_tot_31_maart', '300');
add_option('venntech_premie_4kw', '300');
add_option('venntech_premie_4_6kw', '150');
add_option('venntech_premie_6kw', '0');
add_option('venntech_aftopping_premie', '6');
add_option('venntech_voor_tarief_zonder_batterij', '117.48');
add_option('venntech_voor_tarief_met_batterij', '197.21');
add_option('venntech_na_tarief_zonder_batterij', '100.5');
add_option('venntech_na_tarief_met_batterij', '161.55');
add_option('venntech_prijs_1kwh', '0.22');
add_option('venntech_waarde_capaciteit', '45');
add_option('voor_zonder_percentage_zelf', '30');
add_option('voor_zonder_prijs_zelf_verbruik', '287');
add_option('voor_zonder_percentage_injectie', '70');
add_option('voor_zonder_prijs_injectie', '45');
add_option('voor_met_percentage_zelf', '63');
add_option('voor_met_prijs_zelf_verbruik', '287');
add_option('voor_met_percentage_injectie', '37');
add_option('voor_met_prijs_injectie', '45');
add_option('na_met_percentage_zelf', '63');
add_option('na_met_prijs_zelf_verbruik', '230');
add_option('na_met_percentage_injectie', '37');
add_option('na_met_prijs_injectie', '45');
add_option('na_zonder_percentage_zelf', '30');
add_option('na_zonder_prijs_zelf_verbruik', '230');
add_option('na_zonder_percentage_injectie', '70');
add_option('na_zonder_prijs_injectie', '45');
add_option('venntech_hespul_waarde', '85');
add_option('invoice_company_name', 'Venntech Bv');
add_option('invoice_company_address', 'Pijkstraat 32');
add_option('invoice_company_postal_code', '8970');
add_option('invoice_company_city', 'Waregem');
add_option('invoice_company_phonenumber', '0496/63.80.90');
add_option('company_vat', 'BE 0711.902.202');


// kilo_watt_piek (kWp) voor zonnepaneel
// kilo_watt_uur (kWh) voor batterij

$table = "{$db_prefix}pc_items_extra";
if (!$CI->db->table_exists($table)) {

     $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      item_id int(11) NOT NULL,
                      estimate_description text,
                      technical_description text,                      
                      kilo_watt_piek decimal(15, 2),
                      kilo_watt_uur decimal(15, 2),
                      gewicht decimal(15, 2),
                      active boolean default true,
                      image_path varchar(1023),
                      forfait boolean default false,
                      inkoopprijs decimal(15, 2),
                      aanbevolen_verkoopprijs decimal(15, 2),
                      transport_prijs decimal(15, 2) default '10.00'
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_item_id FOREIGN KEY (item_id) REFERENCES " . $db_prefix . "items(id) ON DELETE CASCADE;");

}

function getTaxesId($CI, $db_prefix, $tax_rate)
{
    $CI->db->where('taxrate', $tax_rate);
    $tax = $CI->db->get("{$db_prefix}taxes")->row();
    if ($tax) {
        return $tax->id;
    } else {
        $CI->db->query("insert into {$db_prefix}taxes (name, taxrate) values('BTW-" . $tax_rate . "', $tax_rate);");
        return $CI->db->insert_id();
    }
}

function getTagId($CI, $db_prefix, $name)
{
    $CI->db->where('name', $name);
    $tag = $CI->db->get("{$db_prefix}tags")->row();
    if ($tag) {
        return $tag->id;
    } else {
        $CI->db->query("insert into {$db_prefix}tags (name) values('" . $name . "')");
        return $CI->db->insert_id();
    }
}

function getItemsGroupId($CI, $db_prefix, $name)
{
    $CI->db->where('name', $name);
    $group = $CI->db->get("{$db_prefix}items_groups")->row();
    if ($group) {
        return $group->id;
    } else {
        $CI->db->query("insert into {$db_prefix}items_groups (name) values('" . $name . "');");
        return $CI->db->insert_id();
    }
}

function insertItems($CI, $db_prefix, $group_id, $description, $rate, $kWp = 0, $kWh = 0, $forfait = 0, $gewicht = 0, $inkoopprijs = 0, $aanbevolen_verkoopprijs = 0)
{
    $CI->db->where('description', $description);
    $item = $CI->db->get("{$db_prefix}items")->row();
    if (!$item) {
        $CI->db->query("insert into {$db_prefix}items (description, rate, group_id) values('" . $description . "'," . $rate . ", " . $group_id . ");");
        $item_id = $CI->db->insert_id();
        // insert also into pc_items_extra
        $CI->db->query("insert into {$db_prefix}pc_items_extra (item_id, kilo_watt_piek, kilo_watt_uur, forfait, gewicht, inkoopprijs, transport_prijs, aanbevolen_verkoopprijs) values(" . $item_id . ", " . $kWp . ", " . $kWh . ", " . $forfait . "," . $gewicht . "," . $inkoopprijs . "," . $transport_prijs . "," . $aanbevolen_verkoopprijs . ");");

        return $item_id;
    } else {
        $CI->db->where('item_id', $item->id);
        $item_extra = $CI->db->get("{$db_prefix}pc_items_extra")->row();
        if (!$item_extra) {
            $CI->db->query("insert into {$db_prefix}pc_items_extra (item_id, kilo_watt_piek, kilo_watt_uur, forfait, gewicht, inkoopprijs, transport_prijs, aanbevolen_verkoopprijs) values(" . $item->id . ", " . $kWp . ", " . $kWh . ", " . $forfait . "," . $gewicht . "," . $inkoopprijs . "," . $transport_prijs . "," . $aanbevolen_verkoopprijs . ");");
        }

        return $item->id;
    }
}

function insertCustomfields($CI, $db_prefix, $fieldto, $name, $slug, $type)
{
    $CI->db->where('slug', $slug);
    $customfields = $CI->db->get("{$db_prefix}customfields")->row();
    if ($customfields) {
        return $customfields->id;
    } else {
        $CI->db->query("insert into {$db_prefix}customfields (fieldto, name, slug, type) values('" . $fieldto . "', '" . $name . "', '" . $slug . "', '" . $type . "');");
        return $CI->db->insert_id();
    }
}

$btw21 = getTaxesId($CI, $db_prefix, 21);
$btw6 = getTaxesId($CI, $db_prefix, 6);
$btw0 = getTaxesId($CI, $db_prefix, 0);

insertCustomfields($CI, $db_prefix, 'estimate', 'Aantal Panelen', 'estimate_number_of_panels', 'number');
insertCustomfields($CI, $db_prefix, 'estimate', 'Sale agent phone number', 'estimate_sale_agent_phonenumber', 'input');
insertCustomfields($CI, $db_prefix, 'estimate', 'Zonnepaneel Merk', 'estimate_zonnepaneel_merk', 'input');
insertCustomfields($CI, $db_prefix, 'estimate', 'Zonnepanneel vermogen', 'estimate_zonnepanneel_vermogen', 'number');
insertCustomfields($CI, $db_prefix, 'estimate', 'Totaal vermogen', 'estimate_totaal_vermogen', 'number');
insertCustomfields($CI, $db_prefix, 'estimate', 'Naam Sales Verkoper', 'estimate_naam_sales_verkoper', 'input');

// insert default values for items_groups if not exists
$zonnepanelen_id = getItemsGroupId($CI, $db_prefix, "Zonnepanelen");
$omvormers_id = getItemsGroupId($CI, $db_prefix, "Omvormers");
$hybride_batterij_id = getItemsGroupId($CI, $db_prefix, "Hybride Batterij");
$retrofit_batterij_id = getItemsGroupId($CI, $db_prefix, "Retrofit Batterij");
$retrofit_toebehoren_id = getItemsGroupId($CI, $db_prefix, "Retrofit toebehoren");
$elektrisch_materiaal_id = getItemsGroupId($CI, $db_prefix, "Elektrisch materiaal");

$verbruiksmateriaal_id = getItemsGroupId($CI, $db_prefix, "Verbruiksmateriaal");
$plaatsing_id = getItemsGroupId($CI, $db_prefix, "Plaatsing");
$structuur_id = getItemsGroupId($CI, $db_prefix, "Structuur");

$meerwerken_id = getItemsGroupId($CI, $db_prefix, "Meerwerken/producten");

add_option('plaatsing_id', $plaatsing_id);
add_option('zonnepanelen_id', $zonnepanelen_id);
add_option('structuur_id', $structuur_id);
add_option('omvormers_id', $omvormers_id);
add_option('hybride_batterij_id', $hybride_batterij_id);
add_option('retrofit_batterij_id', $retrofit_batterij_id);
add_option('retrofit_toebehoren_id', $retrofit_toebehoren_id);
add_option('elektrisch_materiaal_id', $elektrisch_materiaal_id);
add_option('meerwerken_id', $meerwerken_id);
add_option('verbruiksmateriaal_id', $verbruiksmateriaal_id);

$default_winst = 1.25;

// insert default values for items if not exists

insertItems($CI, $db_prefix, $zonnepanelen_id, 'Longi 450 HC Silver', 161.2204 * $default_winst, 0.45,0,0,0);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'trina 395 Vertex S HC Black Frame', 145.7929 * $default_winst, 0.395);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'JA Solar 370 HC Fb', 137.5649 * $default_winst, 0.37);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'hyundai 400 full black', 151.4194 * $default_winst, 0.4);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Longi 365 HC FB', 140.1422 * $default_winst, 0.365);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Longi 360 HC FB', 138.9443 * $default_winst, 0.36);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'JA Solar 365 HC Fb', 141.8362 * $default_winst, 0.365);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'JA Solar 370 HC Fb', 144.7523 * $default_winst, 0.37);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'jinko 395 Tiger Full Black', 156.1505 * $default_winst, 0.395);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Canadian solar 360 FB', 142.3202 * $default_winst, 0.360);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'JA solar 395 HC FB', 156.8765 * $default_winst, 0.395);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Eurener 340 HC Black SB', 135.52 * $default_winst, 0.340);
$traditioneel_zonnepaneel = insertItems($CI, $db_prefix, $zonnepanelen_id, 'hyundai 410 full black', 163.5194 * $default_winst, 0.410);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Belinus M7 Glas/Glas Full Black Half Cut', 149.7375 * $default_winst, 0.375);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Eurener 415 HC Black BB', 174.24 * $default_winst, 0.415);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Eurener 375 MWT (1m77)', 160.93 * $default_winst, 0.375);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'REC 365 HC FB', 168.2868 * $default_winst, 0.365);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Futurasun 360 Full Black Half Cut', 168.19 * $default_winst, 0.360);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'REC 400 HC FB', 244.4321 * $default_winst, 0.400);
insertItems($CI, $db_prefix, $zonnepanelen_id, 'Luxor 330 Glas/Glas Full Black', 121 * $default_winst, 0.330);

// omvormers
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 1,5', 655.62 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 2,0', 699.28 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 2,5', 733.32 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 3,0', 786.6 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 3,6', 1055.17 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 4,0', 1266.37 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA 5,0', 1423.96 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA Tripower 6,0', 417.72 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA Tripower 8,0', 492.72 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'SMA Tripower 10,0', 580.23 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 2KTL-L1', 602.1 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 3KTL-L1', 646.9 * $default_winst);
$traditioneel_omvormer = insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 3,68KTL-L1', 653.15 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 4KTL-L1', 775.02 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 4,6KTL-L1', 864.61 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 5KTL-L1', 1026.07 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 5KTL-M1', 1153.16 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 6KTL-M1', 946.05 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 8KTL-M1', 975.8 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Huawei 10KTL-M1', 1015 * $default_winst);
insertItems($CI, $db_prefix, $omvormers_id, 'Enphase iq 7+', 180 * $default_winst);

// hybride batterij
insertItems($CI, $db_prefix, $hybride_batterij_id, 'luna 10kw + bms + 1F meter', 2368.66 * $default_winst, 0, 10,0,100);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Luna 10kw + bms + 3F meter', 3977.62 * $default_winst, 0, 10,0,100);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Luna 15kw + bms + 1F meter', 2368.66 * $default_winst, 0, 15,0,150);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Luna 15kw + bms + 3F meter', 5550.59 * $default_winst, 0, 15,0,150);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Luna 5kw + bms + 1F meter', 2368.66 * $default_winst, 0, 5,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'luna 5kw + bms + 3F meter', 2404.65 * $default_winst, 0, 5,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, '2 x Pylontech 2,4', 1293.44 * $default_winst, 0, 4.8,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, '2x Pylontech 3,5', 1316.4 * $default_winst, 0, 7,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, '3x pylontech 2,4', 1890.16 * $default_winst, 0, 7.2,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, '3x pylontech 3,5', 1924.6 * $default_winst, 0, 10.50,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'FOX 5,15 + bms + 3F meter ', 1480.6 * $default_winst, 0, 5.15,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Pylontech 2,4', 696.72 * $default_winst, 0, 2.4,0,50);
insertItems($CI, $db_prefix, $hybride_batterij_id, 'Pylontech 3,5', 708.2 * $default_winst, 0, 3.5,0,50);

// structuur
$traditioneel_structuur = insertItems($CI, $db_prefix, $structuur_id, 'Hellend dak pannen', 39.64 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'Plat dak Flatix', 52 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'hellend dak leien', 89.21 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'hellend dak tegelpannen', 89.21 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'Golfplaten', 36.88 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'Staande naad', 38.24 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'plat dak schans', 82.44 * $default_winst);
insertItems($CI, $db_prefix, $structuur_id, 'overzetdak', 42.03 * $default_winst);


// verbruiksmateriaal
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardingsklem', 1.65 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardingsmof', 5.11 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardingsonderbreker 1', 7.45 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardingsonderbreker 5', 6.96 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardingspen', 9.38 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Aardrail Groen', 4.93 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 10A', 8.23 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 16A', 4.60 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 20A', 4.60 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 25A', 8.78 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 32A', 9.42 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 40A', 9.94 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 50A', 45.79 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 63A', 54.23 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 2P 6A', 8.95 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 16A', 21.17 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 20A', 23.96 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 25A', 24.26 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 32A', 26.24 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 20A', 23.96 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 25A', 24.26 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 32A', 26.54 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 40A', 22.44 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 50A', 85.07 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 63A', 96.62 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Automaat 4P 6A', 20.56 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 2P 300ma/40a', 42.67 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 2P 300ma/63a', 47.57 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 2P 30ma/40a', 46.32 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 2P 30ma/63a', 55.40 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 4P 300ma/40a', 42.59 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 4P 300ma/63a', 79.86 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 4P 30ma/40a', 48.79 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Differentieel 4P 30ma/63a', 109.84 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Enphase Fasekoppelaar', 0.0);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Enphase Metered', 0.0);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Enphase Q relais', 0.0);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'GST Kwh Teller 65A mono', 112.64 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kabelgoot wit 40x16', 9.21 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kamgeleider L1+L2+L3+N', 71.83 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kast 12 Mod', 49.62 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kast 24 Mod', 77.00 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kast 4 Mod', 12.35 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Kast 8 Mod', 23.69 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'MC4 Fiche', 2.60 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'mc4 splitter  y connector', 5.52);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs extra omvormer(0,5h)', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs extra verplaatsing km', 1.16 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs herbekabelen', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs kabeltraject', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs omwille van terugkeer', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'meerprijs zekeringskast afremmen', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Niko Hydro Opbouw stopcontact enkel IN', 8.22 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Powerline ', 55.00 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Productiemeter', 00.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Klein Materiaal', 25.00 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'PVC Buis 20 + Klemmen', 1.22 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'PVC Buis 25 + Klemmen', 1.85 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'PVC Flex 20 ', 0.47 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'PVC Flex 25 ', 0.69 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'UTP Kabel ', 0.46 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'solarzwart 4mm² ', 1.25 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'solarzwart 6mm² ', 0.82 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Stopcontact ', 8.32 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'UTP Kabel 1m ', 1.19 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'UTP Kabel 1,5m ', 1.79 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'UTP Kabel 2m ', 2.38 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'UTP Kabel 3m ', 2.56 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Verdeeldoos ( FLEX-O-Box) + Wartels ', 4.74 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Verdeeldoos-Wartel ', 0.37 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 1,5 Blauw ', 0.30 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 1,5 Geel/Groen ', 0.30 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 1,5 Zwart', 0.30 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 10mm BLAUW/ZWART', 2.09 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 16mm Geel/Groen (aardingskabel) ', 2.15 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 2,5 Geel/Groen', 0.45 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 2,5 Zwart/blauw', 0.49 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 4mm Geel/Groen', 1.15 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 4mm Zwart/blauw', 0.62 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 6mm Aarding ', 0.88 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 6mm BLAUW/ZWART ', 1.28 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'VOB 6mm geel/groen ', 0.72 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Wachtbuis 5cm Rood ', 0.94 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Wifi Repeater', 0.00);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 3G1,5', 0.61 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 3G2,5', 0.85 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 3G4', 4.00 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 3G6', 4.47 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 5G1,5', 0.96 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 5G2,5', 1.57 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'XVB 5G4', 3.82 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'GST Kwh Teller 65A 3F', 178.42 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'TP-Link 5-Port (TL-SG1005D)', 19.46 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'TP-Link 8-Port (LS1008G)', 29.82 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'Edimax Slimstopcontact  (SP-2101W V3)', 49.68 * $default_winst);
insertItems($CI, $db_prefix, $verbruiksmateriaal_id, 'stenen balast ', 0.2 * $default_winst);

//plaatsing
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 5', 877.21 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 6', 897.41 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 7', 917.60 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 8', 937.80 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 9', 958.00 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 10', 978.20 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 11', 995.80 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 12', 1018.71 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 13', 1038.03 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 14', 1058.23 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 15', 1076.67 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 16', 1097.75 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 17', 1117.96 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 18', 1139.03 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 19', 1156.50 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 20', 1178.42 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 21', 1198.70 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 22', 1218.10 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 23', 1235.70 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 24', 1255.63 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 25', 1275.79 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 26', 1296.84 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 27', 1316.90 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 28', 1336.09 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 29', 1356.35 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 30', 1376.61 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 31', 1396.76 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 32', 1416.88 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 33', 1436.20 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 34', 1456.40 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 35', 1475.72 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 36', 1496.80 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 37', 1517.31 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 38', 1538.01 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 39', 1559.17 * $default_winst);
insertItems($CI, $db_prefix, $plaatsing_id, 'Aantal panelen 40', 1599.16 * $default_winst);

$installatie_batterij_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Installatie batterij', 200 * $default_winst);


// aantal vlakken -> aantal = aantal - 1!!!
$aantal_vlakken_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Aantal vlakken', 150, 0, 0, 0);
add_option('aantal_vlakken_id', $aantal_vlakken_id);

$materiaal_elek_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Materiaal Elek', 250, 0, 0, 1);
$keuring_en_aanmelding_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Keuring en aanmelding', 135, 0, 0, 1);
$aankoop_lead_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Aankoop lead', 100, 0, 0, 1);
$transport_kosten_id = insertItems($CI, $db_prefix, $meerwerken_id, 'Transport kosten', 150, 0, 0, 1);


$table = "{$db_prefix}pc_samengestelde_product";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      naam varchar(255) NOT NULL,
                      omschrijving varchar(1023),
                      actief boolean default true
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");


    $CI->db->query("insert into " . $table . " (naam, omschrijving) values('Forfait kosten','Forfait kosten');");

}

$table = "{$db_prefix}pc_samengestelde_product_items";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      samengestelde_product_id int(11) NOT NULL,
                      item_id int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (samengestelde_product_id, item_id);");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_samengestelde_product FOREIGN KEY (samengestelde_product_id) REFERENCES {$db_prefix}pc_samengestelde_product(id);");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_items FOREIGN KEY (item_id) REFERENCES {$db_prefix}items(id);");

    $CI->db->query("insert into " . $table . " (samengestelde_product_id, item_id) values(1, $materiaal_elek_id);");
    $CI->db->query("insert into " . $table . " (samengestelde_product_id, item_id) values(1, $keuring_en_aanmelding_id);");
    $CI->db->query("insert into " . $table . " (samengestelde_product_id, item_id) values(1, $aankoop_lead_id);");
    $CI->db->query("insert into " . $table . " (samengestelde_product_id, item_id) values(1, $transport_kosten_id);");
}

$table = "{$db_prefix}pc_project_template";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      name varchar(255) NOT NULL,
                      description varchar(1023)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

    $CI->db->query("insert into " . $table . " (name) values('Project PV');");
    $CI->db->query("insert into " . $table . " (name) values('Project PV+Batterij');");
    $CI->db->query("insert into " . $table . " (name) values('Project Retrofit');");
    $CI->db->query("insert into " . $table . " (name) values('Project Hybride');");

}

$table = "{$db_prefix}pc_instellingen_taak";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      task_order int(11) NOT NULL,
                      name varchar(255) NOT NULL,
                      tag_name varchar(255) NOT NULL,
                      tag_id int(11) NOT NULL,
                      staffid int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

    $order = 0;
    foreach (TASK_TAGS as $tag_name => $name) {
        $tag_id = getTagId($CI, $db_prefix, $tag_name);
        $CI->db->query("insert into " . $table . " (task_order, name, tag_name, tag_id, staffid) values(" . $order . " , '" . $name . "', '" . $tag_name . "', " . $tag_id . ", 1);");
        $order++;
    }

}

function createProjectTemplateTasks($project_template_id, $CI, $db_prefix, $table)
{
    $order = 0;
    foreach (TASK_TAGS as $value) {
        $CI->db->query("insert into " . $table . " (project_template_id, task_order, instellingen_taak_id) values(" . $project_template_id . ", " . $order . " , " . ($order + 1) . ");");
        $order++;
    }
}

$table = "{$db_prefix}pc_project_template_tasks";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      project_template_id int(11) NOT NULL,
                      task_order int(11) NOT NULL,
                      instellingen_taak_id int(11) NOT NULL,
                      enabled boolean default true
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_project_template FOREIGN KEY (project_template_id) REFERENCES {$db_prefix}pc_project_template(id);");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_instellingen_taak FOREIGN KEY (instellingen_taak_id) REFERENCES {$db_prefix}pc_instellingen_taak(id);");


    createProjectTemplateTasks(1, $CI, $db_prefix, $table);
    createProjectTemplateTasks(2, $CI, $db_prefix, $table);
    createProjectTemplateTasks(3, $CI, $db_prefix, $table);
    createProjectTemplateTasks(4, $CI, $db_prefix, $table);
}


$table = "{$db_prefix}pc_estimate_template";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      project_template_id int(11) NOT NULL,
                      name varchar(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_project_template FOREIGN KEY (project_template_id) REFERENCES {$db_prefix}pc_project_template(id);");

    $CI->db->query("insert into " . $table . " (project_template_id, name) values(1, 'Traditioneel PV');");
    $CI->db->query("insert into " . $table . " (project_template_id, name) values(2, 'Traditioneel PV+Hybride Batterij');");
}

$table = "{$db_prefix}pc_estimate_template_element";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimate_template_id int(11) NOT NULL,
                      name varchar(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template FOREIGN KEY (estimate_template_id) REFERENCES {$db_prefix}pc_estimate_template(id);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, name) values(1, 'Installatie van zonnepanelen');");
    $CI->db->query("insert into " . $table . " (estimate_template_id, name) values(2, 'Installatie van zonnepanelen');");
    $CI->db->query("insert into " . $table . " (estimate_template_id, name) values(2, 'Hybride Batterij');");

}

$table = "{$db_prefix}pc_estimate_template_items";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimate_template_id int(11) NOT NULL,
                      estimate_template_element_id int(11) NOT NULL,
                      rel_id int(11) NOT NULL,
                      rel_type varchar(255) NOT NULL,
                      multiply tinyint(1) default 0
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template FOREIGN KEY (estimate_template_id) REFERENCES {$db_prefix}pc_estimate_template(id);");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template_element FOREIGN KEY (estimate_template_element_id) REFERENCES {$db_prefix}pc_estimate_template_element(id);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, " . $zonnepanelen_id . ", 'groups', 1);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, " . $omvormers_id . ", 'groups', 0);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, " . $structuur_id . ", 'groups', 1);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, " . $plaatsing_id . ", 'groups', 0);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, " . $aantal_vlakken_id . ", 'items', 0);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(1, 1, 1, 'samengestelde_product', 0);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, " . $zonnepanelen_id . ", 'groups', 1);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, " . $omvormers_id . ", 'groups', 0);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, " . $structuur_id . ", 'groups', 1);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, " . $plaatsing_id . ", 'groups', 0);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, " . $aantal_vlakken_id . ", 'items', 0);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 2, 1, 'samengestelde_product', 0);");

    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 3, " . $hybride_batterij_id . ", 'groups', 0);");
    $CI->db->query("insert into " . $table . " (estimate_template_id, estimate_template_element_id, rel_id, rel_type, multiply) values(2, 3, " . $installatie_batterij_id . ", 'items', 0);");





}


$table = "{$db_prefix}pc_estimates_extra";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimates_id int(11) NOT NULL,
                      estimate_template_id int(11) NOT NULL,
                      tax_id int(11) NOT NULL,
                      margin_of_profit decimal(15, 2) default 25.00,
                      number_of_panels int NOT NULL,
                      kilogram_of_battery int default 0,
                      zonnepanneel_vermogen int NOT NULL,
                      totaal_vermogen int NOT NULL,
                      zonnepaneel_merk varchar(255) NOT NULL,
                      hespul_waarde decimal(15, 2) default 85.00,
                      korting_type_id int,
                      naam_sales_verkoper varchar(255) NOT NULL,
                      staffid int(11) NOT NULL                      
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template FOREIGN KEY (estimate_template_id) REFERENCES {$db_prefix}pc_estimate_template(id);");
}

// save groups, selected group value, samengestelde_product and items
$table = "{$db_prefix}pc_estimates_extra_items";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimates_extra_id int(11) NOT NULL,
                      estimate_template_element_id int(11) NOT NULL,
                      rel_id int(11) NOT NULL,
                      rel_type varchar(255) NOT NULL,
                      items_id int(11) NOT NULL,
                      description varchar(255) NOT NULL,
                      multiply tinyint(1) NOT NULL,
                      quantity int(11) NOT NULL,
                      forfait boolean NOT NULL,
                      rate decimal(15, 2) NOT NULL,
                      total_rate_quantity int(11) NOT NULL,
                      total_rate decimal(15, 2) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimates_extra FOREIGN KEY (estimates_extra_id) REFERENCES {$db_prefix}pc_estimates_extra(id);");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template_element FOREIGN KEY (estimate_template_element_id) REFERENCES {$db_prefix}pc_estimate_template_element(id);");
}

$table = "{$db_prefix}pc_estimates_extra_meerwerken";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimates_extra_id int(11) NOT NULL,
                      items_id int(11) NOT NULL,
                      description varchar(255) NOT NULL,
                      forfait boolean NOT NULL,
                      quantity int(11) NOT NULL,
                      rate decimal(15, 2) NOT NULL,
                      total_rate decimal(15, 2) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimates_extra FOREIGN KEY (estimates_extra_id) REFERENCES {$db_prefix}pc_estimates_extra(id);");
}

$table = "{$db_prefix}pc_inspectie_rapport";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      clientid int(11) NOT NULL,
                      taskid int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
}

$table = "{$db_prefix}pc_inspectie_rapport_algemeen";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      inspectie_rapport_id int(11) NOT NULL,
                      ean_nr varchar(255) ,
                      dag_verbruik int(11) ,
                      dal_verbruik int(11) ,
                      dag_injectie int(11) ,
                      dal_injectie int(11) ,
                      gemiddelde_nacht_verbruik int(11) 
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_inspectie_rapport FOREIGN KEY (inspectie_rapport_id) REFERENCES {$db_prefix}pc_inspectie_rapport(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_inspectie_rapport_elektriciteit";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      inspectie_rapport_id int(11) NOT NULL,
                      aardingsonderbreker_aanwezig int(11) NOT NULL,
                      digitale_meter_aanwezig int(11) NOT NULL,
                      type_aansluiting int(11),
                      ampere varchar(255) ,
                      differentieel_300ma_aanwezig int(11),
                      ampere_300 varchar(255) ,
                      differentieel_30ma_aanwezig int(11),
                      ampere_30 varchar(255) ,
                      extra_zekeringkast_nodig int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_inspectie_rapport FOREIGN KEY (inspectie_rapport_id) REFERENCES {$db_prefix}pc_inspectie_rapport(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_inspectie_rapport_info_dak";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      inspectie_rapport_id int(11) NOT NULL,
                      hellend int(11) ,
                      hellend_andere varchar(255) ,
                      onderdak varchar(255),
                      gemetste_nok_en_gevelpannen int(11) ,
                      sarking_dak int(11),
                      hoogte_dakgoot varchar(255) ,
                      materiaal_dakgoot int(11),
                      plat_dak int(11),
                      dakdoorvoer int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_inspectie_rapport FOREIGN KEY (inspectie_rapport_id) REFERENCES {$db_prefix}pc_inspectie_rapport(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_inspectie_rapport_info_pv";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      inspectie_rapport_id int(11) NOT NULL,
                      woning int(11),
                      schaduw int(11),
                      orientatie int(11),
                      hellingsgraad int(11),
                      lengte_ac varchar(255) ,
                      lengte_dc varchar(255) ,
                      kabeltraject int(11),
                      type_paneel varchar(255) ,
                      aantal_paneel varchar(255) ,
                      type_omvormers varchar(255) ,
                      aantal_omvormers varchar(255),
                      plaats_omvormer int(11),
                      plaats_omvormer_andere varchar(255) ,
                      batterij int(11) ,
                      smart_meter int(11) ,
                      monitoring int(11),
                      hindernissen int(11),
                      hindernissen_andere varchar(255) NOT NULL,
                      opmerking_veiligheid varchar(1023) NOT NULL,
                      specifieke_afspraken_en_opmerkingen varchar(1023) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_inspectie_rapport FOREIGN KEY (inspectie_rapport_id) REFERENCES {$db_prefix}pc_inspectie_rapport(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_inspectie_rapport_image";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      inspectie_rapport_id int(11) NOT NULL,
                      filename varchar(255)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_inspectie_rapport FOREIGN KEY (inspectie_rapport_id) REFERENCES {$db_prefix}pc_inspectie_rapport(id) ON DELETE CASCADE;");

}


$table = "{$db_prefix}pc_opleverdocumenten";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      clientid int(11) NOT NULL,
                      datum varchar (255) NOT NULL,
                      verval_datum varchar (255) NOT NULL,
                      taskid int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

}

$table = "{$db_prefix}pc_opleverdocument_algemeen";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      opleverdocument_id int(11),
                      name varchar(255) NOT NULL,
                      client varchar(255) NOT NULL,
                      medewerkers varchar (255),
                      aarding_goed_en_gecontroleerd int(11),
                      totaal_prijs int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}pc_opleverdocumenten FOREIGN KEY (opleverdocument_id) REFERENCES {$db_prefix}pc_opleverdocumenten(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_opleverdocument_installatie";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      opleverdocument_id int(11),
                      aantal_panelen int(11) NOT NULL,
                      aantal_velden int(11) NOT NULL,
                      aantal_q_relais int(11) NOT NULL,
                      installatie_type int(11) NOT NULL,
                      micro_optimizers int(11) NOT NULL,
                      code int(11) NOT NULL,
                      code_andere varchar(255),
                      type_dak int(11) NOT NULL,
                      type_dak_andere varchar(255),
                      aantal_haken_leien_tegelpannen int(11) NOT NULL,
                      extra_man_andere varchar(255),
                      afgewerkt int(11) NOT NULL,
                      gecontroleerd int(11) NOT NULL,               
                      extra_materiaal varchar(1023),
                      extra_werkuren varchar(1023),
                      opmerkingen varchar(1023),
                      extra_gegevens int (11),
                      extra_gegevens_andere varchar(255)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}pc_opleverdocumenten FOREIGN KEY (opleverdocument_id) REFERENCES {$db_prefix}pc_opleverdocumenten(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_opleverdocument_verbruiksmaterialen";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      opleverdocument_id int(11),
                      item_id varchar(255),
                      aantal int (11)
                      
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}pc_opleverdocumenten FOREIGN KEY (opleverdocument_id) REFERENCES {$db_prefix}pc_opleverdocumenten(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_opleverdocument_fotos";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      opleverdocument_id int(11),
                      filename varchar(255)
                    
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}pc_opleverdocumenten FOREIGN KEY (opleverdocument_id) REFERENCES {$db_prefix}pc_opleverdocumenten(id) ON DELETE CASCADE;");

}

$table = "{$db_prefix}pc_plaatsing_datum";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      clientid int(11) NOT NULL,
                      staffid int(11) NOT NULL,
                      datum date,
                      taskid int(11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

}

$table = "{$db_prefix}pc_estimate_pdf_layout";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      estimate_template_id int(11) NOT NULL,
                      pre_page_1 mediumtext,
                      pre_page_2 mediumtext,
                      pre_page_3 mediumtext,
                      pre_page_4 mediumtext,
                      pre_page_5 mediumtext,
                      post_page_1 mediumtext,
                      post_page_2 mediumtext,
                      post_page_3 mediumtext,
                      post_page_4 mediumtext,
                      post_page_5 mediumtext
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");
    $CI->db->query("ALTER TABLE " . $table . " ADD CONSTRAINT fk_{$table}_pc_estimate_template FOREIGN KEY (estimate_template_id) REFERENCES {$db_prefix}pc_estimate_template(id);");

    $pre_page_1 = htmlspecialchars('<div style="text-align: left;"><table border="0" style="width: 100%;"><tbody><tr><td style="width: 33%;"></td><td style="width: 34%;"></td><td style="width: 33%;">{contact_firstname}<br />{contact_lastname}<br />{client_address}<br />{client_zip} {client_city}<br /><br /><br /><br />Waregem, {estimate_date}</td></tr></tbody></table></div><br /><p>Geachte heer/mevrouw {contact_lastname} {contact_firstname},</p><p>Naar aanleiding van uw aanvraag hebben wij het genoegen u onze aanbieding te doen voor het leveren en monteren van een zonnepanelen installatie aan {client_address} in {client_zip} {client_city}.</p><p>Vermogen en rendement</p><p>De installatie bestaat uit {estimate_number_of_panels} zonnepanelen van het merk {estimate_zonnepaneel_merk}, van elk {estimate_zonnepanneel_vermogen} Wp, met een totaalvermogen van <strong>{estimate_totaal_vermogen}</strong> Wattpiek.</p><h1><span style="font-size: 12pt;">Werkzaamheden:</span></h1><ul><li>Levering en montage van de zonnepanelen.</li><li>Levering en montage van de omvormer(s).</li><li>De bekabeling naar de meterkast.</li><li>Het plaatsen van een nieuwe zekering in de meterkast.</li><li>Het installeren van de monitoring app</li><li>De keuring van de installatie door een onafhankelijk keuringsbureau</li><li>De melding bij de netbeheerder</li></ul><p>&nbsp;</p><p>Mocht u een afspraak willen maken of vragen hebben, bel ons dan gerust op 09/123123.</p><p>We kijken ernaar uit om samen met u werk te maken van een groene &eacute;n zonnige toekomst Alvast bedankt voor uw vertrouwen,</p><p>Met zonnige groeten, <br />{estimate_naam_sales_verkoper}</p>');
    $pre_page_2 = htmlspecialchars('<p><strong><span style="color: #3366ff;">Welkom bij Venntech</span><br /><br /></strong></p> <p>Voor niets gaat de zon op, en daar wilt u maximaal van profiteren. Gelijk hebt u! Duurzaam investeren in zonne- panelen, zonne- en warmtepompboilers, laadpalen voor elektrische wagens en huisbatterijen levert u meer op dan uw geld op een spaarboekje zetten. Uw energiefactuur daalt terwijl uw autonomie stijgt.<br /><br /></p> <h1><span style="font-size: 12pt;">De oplossing voor de toekomst?<br /><strong><span style="font-size: 12pt;">Dat is toch zonneklaar&hellip; Die vindt u bij Venntech!<br /><br /></span><br /><br /><img src="https://perfex.palitconsulting.be/media/public/venntech_afbeelding1.jpg" width="731" height="142" alt="" style="display: block; margin-left: auto; margin-right: auto;" /><br /><br /></strong></span></h1> <p>Bij Venntech geloven we in energieoplossingen die klaar zijn voor de toekomst. We kiezen bewust voor kwaliteit en professionaliteit, van bij de offertevraag tot productaankoop, installatie en dienst na verkoop. Met twaalf jaar ervaring en duizenden tevreden klanten verspreid over heel Vlaanderen behoren we tot de meest ervaren spelers op de markt.</p> <p>Ons ervaren team van adviseurs, planners, installateurs en ingenieurs staat voor u klaar</p>');
    $post_page_1 = htmlspecialchars('<h3 style="color: #1e88e5;">Meerwaardepakketten:</h3><p>U kan uw zonnepanelensysteem opwaarderen met &eacute;&eacute;n van de volgende opties</p><h3 style="color: #1e88e5;">Optie Huisbatterij:</h3><p>Een huisbatterij is een oplaadbare batterij die u toelaat om de overdag geproduceerde energie op te slaan. De Vlaamse regering geeft een premie voor huisbatterijen tot 3200 euro. Meer info op <a>https://www.vlaanderen.be/premie-voor-thuisbatterij-voor-zelf-opgewekte-energie</a></p><h3 style="color: #1e88e5;">All-in prijs:</h3><p>Venntech voorziet de volledige elektrische installatie inclusief de vervanging van de verliesstroomschakelaars indien nodig. De aarding van de elektrische installatie dient te beschikken over een aardingswaarde &lt; 30 Ohm.</p>');

    $CI->db->query("insert into " . $table . " (estimate_template_id, pre_page_1, pre_page_2, post_page_1) values(1, '" . $pre_page_1 . "', '" . $pre_page_2 . "', '" . $post_page_1 . "');");
    $CI->db->query("insert into " . $table . " (estimate_template_id, pre_page_1, pre_page_2, post_page_1) values(2, '" . $pre_page_1 . "', '" . $pre_page_2 . "', '" . $post_page_1 . "');");
}

$table = "{$db_prefix}pc_korting_type";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      name varchar(255) NOT NULL,
                      discount_percentage int(11),
                      is_voor_btw boolean default false
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('GEEN', 0);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('5% korting', 5);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('10% korting', 10);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('15% korting', 15);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('20% korting', 20);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('25% korting', 25);");
    $CI->db->query("insert into " . $table . " (name, discount_percentage) values('30% korting', 30);");

}

$table = "{$db_prefix}pc_traditioneel_berekening";
if (!$CI->db->table_exists($table)) {

    $CI->db->query("CREATE TABLE " . $table . " (
                      id int(11) NOT NULL,
                      vollasturen int(11),
                      prieme_tot_31maart int(11),
                      batterijprieme4kwh int (11),
                      batterijprieme4_6kwh int (11),
                      batterijprieme6kwh int (11),
                      aftropping_batterijprieme int (11),
                      waarde_capaciteit int (11),
                      piek_zonder_batterij int (11),
                      piek_met_batterij int (11),
                      bonus decimal(15, 2),
                      eenheidprijs_elektriciteit_voor_22 decimal (15, 2),
                      eenheidprijs_elektriciteit_na_22 decimal (15, 2),
                      terugleververgoeding int (11)
                    ) ENGINE=InnoDB DEFAULT CHARSET=" . $charset . ";");

    $CI->db->query("ALTER TABLE " . $table . " ADD PRIMARY KEY (id);");
    $CI->db->query("ALTER TABLE " . $table . " MODIFY id int(11) NOT NULL AUTO_INCREMENT;");

    $CI->db->query("insert into " . $table . " (vollasturen, prieme_tot_31maart, batterijprieme4kwh, batterijprieme4_6kwh, batterijprieme6kwh, aftropping_batterijprieme, waarde_capaciteit, piek_zonder_batterij, piek_met_batterij, bonus, eenheidprijs_elektriciteit_voor_22, eenheidprijs_elektriciteit_na_22) values(1000, 300, 300, 300, 0, 6, 45, 5, 4, 0.3, 286.6, 230);");

}

if (!$CI->db->field_exists('sale_agent_phonenumber', 'pc_estimates_extra')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'pc_estimates_extra`  ADD COLUMN `sale_agent_phonenumber` VARCHAR(255) NULL DEFAULT NULL');
}