<?php

defined('BASEPATH') or exit('No direct script access allowed');

const VENNTECH_MODULE_NAME = 'venntech';
const FEATURE_PRODUCTEN = 'venntech-producten';
const FEATURE_SAMENGESTELDE_PRODUCTEN = 'venntech-samengestelde-producten';
const FEATURE_PROJECT_TEMPLATES = 'venntech-project-templates';
const FEATURE_ESTIMATE_TEMPLATES = 'venntech-estimate-templates';
const FEATURE_ESTIMATE = 'venntech-estimate';
const FEATURE_INSPECTIE_RAPPORT = 'venntech-inspectie-rapport';
const FEATURE_OPLEVERDOCUMENT = 'venntech-opleverdocument';
const FEATURE_PLAATSING_DATUM = 'venntech-plaatsing-datums';
const FEATURE_TAKEN = 'venntech-taak';
const FEATURE_BEREKENING = 'venntech-berekening';
const FEATURE_SETTINGS = 'venntech-settings';


// TASKS
const TASK_TAGS = [
    'venntech-benodigdemat-form' => 'Benodigdemat Form',
    'venntech-plaatsing-datum' => 'Plaatsing Datum',
    'venntech-plaatsing' => 'Plaatsing',
    'venntech-inspectie-document' => 'Inspectie Document',
    'venntech-oplever-document' => 'Oplever Document',
    'venntech-monitoring' => 'Monitoring',
    'venntech-schema' => 'Schema',
    'venntech-keuring' => 'Keuring',
    'venntech-aanmelding-netbeheer' => 'Aanmelding Netbeheer'
];

// WINST, zie default winst in install.php die staat momenteel default op 25%
// Om terug te keren naar aankoop_prijs = prijs - (prijs * WINST_VERWIJDER_PERCENTAGE)
// tijdens aanmaken van de offerte zal er gevraagd worden naar winst percentage,
// als verschillend dan 25% dan eerst terug keren naar aankoopprijs en nadien de nieuwe winst percentage toepassen.
// LETOP! niet alle producten hebben winst percentage
// deze hebben winst percentage: Zonnepanelen, omvormers, batterijen, structuur, installatie
const WINST_PERCENTAGE = 0.25;
const WINST_VERWIJDER_PERCENTAGE = 0.20;

// Define upload folder location
define('VENNTECH_MODULE_UPLOAD_FOLDER', module_dir_path(VENNTECH_MODULE_NAME, 'uploads/'));

/**
 * Register activation module hook
 * runs when module is initialized
 */
register_activation_hook(VENNTECH_MODULE_NAME, 'venntech_module_activation_hook');
register_language_files(VENNTECH_MODULE_NAME);

$CI = &get_instance();
// Load module helper file
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_tools');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_view');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_hooks');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_images');
$CI->load->helper(VENNTECH_MODULE_NAME . '/select_options');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_estimatepdf');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_calculator');
$CI->load->helper(VENNTECH_MODULE_NAME . '/venntech_terugverdientijd');

hooks()->add_action('admin_init', 'venntech_menu');
hooks()->add_action('admin_init', 'venntech_permissions');

function venntech_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function venntech_menu()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('venntech-menu-id', [
        'name' => 'VENNTECH', // The name if the item
        'collapse' => true, // Indicates that this item will have submitems
        'position' => 10, // The menu position
        'icon' => 'fa fa-bolt', // Font awesome icon
    ]);


    if (staff_can('view', FEATURE_ESTIMATE)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'estimate-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('estimates'), // The name if the item
            'href' => admin_url('venntech/offertes'), // URL of the item
            'position' => 2, // The menu position
        ]);
    }

    if (staff_can('view', FEATURE_ESTIMATE_TEMPLATES)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'estimate-templates-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('estimate_templates'), // The name if the item
            'href' => admin_url('venntech/estimate_templates'), // URL of the item
            'position' => 3, // The menu position
        ]);
    }

    if (staff_can('view', FEATURE_PROJECT_TEMPLATES)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'project-templates-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('project_templates'), // The name if the item
            'href' => admin_url('venntech/project_templates'), // URL of the item
            'position' => 4, // The menu position
        ]);
    }

    if (staff_can('view', FEATURE_SAMENGESTELDE_PRODUCTEN)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'samengestelde-producten-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('samengestelde_producten'), // The name if the item
            'href' => admin_url('venntech/samengestelde_producten'), // URL of the item
            'position' => 5, // The menu position
        ]);
    }

    if (staff_can('view', FEATURE_PRODUCTEN)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'producten-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('producten'), // The name if the item
            'href' => admin_url('venntech/producten'), // URL of the item
            'position' => 6, // The menu position
        ]);
    }
    if (staff_can('view', FEATURE_TAKEN)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'taken-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('tasks'), // The name if the item
            'href' => admin_url('venntech/taken'), // URL of the item
            'position' => 7, // The menu position
        ]);
    }
    if (staff_can('view', FEATURE_INSPECTIE_RAPPORT)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'inspectie_rapport-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('inspectie_rapport'), // The name if the item
            'href' => admin_url('venntech/inspectie_rapporten'), // URL of the item
            'position' => 8, // The menu position
        ]);
    }
    if (staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'opleverdocument-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('opleverdocument '), // The name if the item
            'href' => admin_url('venntech/opleverdocumenten'), // URL of the item
            'position' => 9, // The menu position
        ]);
    }
    if (staff_can('view', FEATURE_PLAATSING_DATUM)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'plaatsing_datum-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('venntech-plaatsing-datum'), // The name if the item
            'href' => admin_url('venntech/plaatsing_datums'), // URL of the item
            'position' => 10, // The menu position
        ]);
    }
    if (staff_can('view', FEATURE_PLAATSING_DATUM)) {
        $CI->app_menu->add_sidebar_children_item('venntech-menu-id', [
            'slug' => 'berekening_simulatie-menu-id', // Required ID/slug UNIQUE for the child menu
            'name' => _l('venntech-terugverdienst_simulatie'), // The name if the item
            'href' => admin_url('venntech/berekening_simulatie'), // URL of the item
            'position' => 11, // The menu position
        ]);
    }

    if (staff_can('view', FEATURE_SETTINGS)) {
        $CI->app_menu->add_setup_menu_item('venntech-settings', [
            'collapse' => true,
            'name' => 'VENNTECH',
            'position' => 70,
        ]);

        $CI->app_menu->add_setup_children_item('venntech-settings', [
            'slug' => 'venntech-instellingen',
            'name' => _l('settings'),
            'href' => admin_url('venntech/settings'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('venntech-settings', [
            'slug' => 'venntech-taak-instellingen',
            'name' => _l('project_tasks'),
            'href' => admin_url('venntech/settings_taak'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('venntech-settings', [
            'slug' => 'venntech-type-kortingen',
            'name' => _l('type_kortingen'),
            'href' => admin_url('venntech/type_kortingen'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('venntech-settings', [
            'slug' => 'venntech-inputparameters',
            'name' => _l('inputparameters'),
            'href' => admin_url('venntech/inputparameters'),
            'position' => 5,
        ]);
    }
}

function venntech_permissions()
{
    $config = [];

    // 'view_own'   => _l('permission_view_own'),
    $config['capabilities'] = [
        'view' => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities(FEATURE_PRODUCTEN, $config, 'VENNTECH ' . _l('producten'));
    register_staff_capabilities(FEATURE_SAMENGESTELDE_PRODUCTEN, $config, 'VENNTECH ' . _l('samengestelde_product'));
    register_staff_capabilities(FEATURE_PROJECT_TEMPLATES, $config, 'VENNTECH ' . _l('project_template'));
    register_staff_capabilities(FEATURE_ESTIMATE_TEMPLATES, $config, 'VENNTECH ' . _l('estimate_template'));
    register_staff_capabilities(FEATURE_ESTIMATE, $config, 'VENNTECH ' . _l('estimates'));
    register_staff_capabilities(FEATURE_TAKEN, $config, 'VENNTECH ' . _l('tasks'));
    register_staff_capabilities(FEATURE_INSPECTIE_RAPPORT, $config, 'VENNTECH ' . _l('inspectie_rapport'));
    register_staff_capabilities(FEATURE_OPLEVERDOCUMENT, $config, 'VENNTECH ' . _l('opleverdocument'));
    register_staff_capabilities(FEATURE_PLAATSING_DATUM, $config, 'VENNTECH ' . _l('plaatsing_datums'));
    register_staff_capabilities(FEATURE_BEREKENING, $config, 'VENNTECH ' . _l('venntech-terugverdienst_simulatie'));
    register_staff_capabilities(FEATURE_SETTINGS, $config, 'VENNTECH ' . _l('settings'));

}

// Inject upload folder location for products module
hooks()->add_filter('get_upload_path_by_type', 'venntech_upload_folder', 10, 2);
function venntech_upload_folder($path, $type)
{
    if ('venntech' == $type) {
        return VENNTECH_MODULE_UPLOAD_FOLDER;
    }

    return $path;
}



/*
Module Name: VENNTECH
Description: VENNTECH Perfex CRM Module
Version: 1.0.0
Requires at least: 1.0.0
*/