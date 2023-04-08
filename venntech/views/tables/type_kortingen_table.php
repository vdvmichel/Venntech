<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_korting_type';

$aColumns = [
    'id',
    'name',
    'discount_percentage',
    'is_voor_btw'
];

$sIndexColumn = 'id';
$sTable = $table;
$join = [];
$where = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,
    []
);

$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/type_kortingen/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/type_kortingen/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    $row[] = '<a href="/admin/venntech/type_kortingen/edit/' . $aRow['id'] . '">' . $aRow['discount_percentage'] . '%' . '</a>';
    $row[] = '<a href="/admin/venntech/type_kortingen/edit/' . $aRow['id'] . '">' . ($aRow['is_voor_btw'] == 1 ? _l('settings_yes') : _l('settings_no')) . '</a>';


    if (staff_can('delete', FEATURE_SETTINGS)) {
        $row[] = icon_btn('venntech/type_kortingen/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    } else {
        $row[] = '';
    }

    $output['aaData'][] = $row;
}
