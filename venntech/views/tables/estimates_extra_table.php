<?php

defined('BASEPATH') or exit('No direct script access allowed');


$table = db_prefix() . 'pc_estimates_extra';
$estimates_template = db_prefix() . 'pc_estimate_template';
$tax = db_prefix() . 'taxes';
$clients = db_prefix() . 'clients';
$estimates = db_prefix() . 'estimates';
$projects = db_prefix() . 'projects';
$contacts = db_prefix() . 'contacts';
$aColumns = [
    $table .'.id as id',
    $estimates . '.total_tax as total_tax',
    $estimates . '.subtotal as subtotal',
    //$estimates . '.total as total',
    get_sql_select_client_company(),
    $projects . '.name as project_name',
    $estimates . '.id as estimates_id',
    $table . '.staffid as sale_agent',
    $estimates_template . '.name as template_name',
    $estimates . '.date as datum',
    $tax . '.taxrate as tax_id',
    $table . '.number_of_panels as number_of_panels',
    $table . '.commission_amount as commission_amount', // Added commission_amount
    db_prefix() . 'estimates.status'
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'pc_estimates_extra';
$join = [
    'JOIN ' . $estimates . ' ON ' . $table . '.estimates_id=' . $estimates . '.id'
    ,'LEFT JOIN ' . $projects . ' ON ' . $estimates . '.project_id=' . $projects . '.id'
    ,'JOIN ' . $clients . ' ON ( ' . $estimates . '.clientid=' . $clients . '.userid AND '. $table. '.estimates_id = '. $estimates.'.id)'
    ,'LEFT JOIN ' . $contacts . ' ON ' . $clients . '.userid=' . $contacts . '.userid'
    ,'JOIN ' . $estimates_template . ' ON ' . $table . '.estimate_template_id=' . $estimates_template . '.id'
    , 'JOIN ' . $tax . ' ON ' . $table . '.tax_id=' . $tax . '.id'
    ,'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'estimates.currency'
];
$where = [];
if (!is_admin()) {
    $where[] = 'AND staffid = ' . get_staff_user_id();
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,  [
    db_prefix() . 'currencies.name as currency_name',
    db_prefix() . 'estimates.clientid',
    'deleted_customer_name',
    'hash',
]);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    //$row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';


    $row_option = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' .format_estimate_number( $aRow['estimates_id']) . '</a>';
    $row_option .= '<div class="row-options" style="opacity:0.6;">' . '<span >
        <a href="' . site_url('estimate/' . $aRow['estimates_id'] . '/' . $aRow['hash']) . '" target="_blank">' . _l('view') . '</a>
        <a> | </a>
        <a href="' . site_url('admin/estimates/pdf/' . $aRow['estimates_id']) . '" target="_blank">' . _l('download') . '</a>
        </span>' . '</div>';'<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' .format_estimate_number( $aRow['estimates_id']) . '</a>';

    $row[] = $row_option;
    $row[] = app_format_money($aRow['subtotal'], $aRow['currency_name']);
    //$row[] = app_format_money($aRow['total_tax'], $aRow['currency_name']);

    if (empty($aRow['deleted_customer_name'])) {
        $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    } else {
        $row[] = $aRow['deleted_customer_name'];
    }
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . $aRow['project_name'] . '</a>';
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . get_staff_full_name($aRow['sale_agent']) . '</a>';
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . $aRow['template_name'] . '</a>';
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . $aRow['datum'] . '</a>';
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . 'BTW %'  . $aRow['tax_id'] . '</a>';
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' . $aRow['number_of_panels'] . '</a>';
    $row[] = app_format_money($aRow['commission_amount'], $aRow['currency_name']); // Added commission_amount output
    $row[] = '<a href="/admin/venntech/offertes/edit/' . $aRow['id'] . '">' .format_estimate_status( $aRow[db_prefix() . 'estimates.status']). '</a>';


    // add to next index the data, no need to specify index the item will be added to the end.
    //$actions = icon_btn('venntech/offertes/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    //$row[] = $actions;
    $output['aaData'][] = $row;
}
