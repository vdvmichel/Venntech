<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_samengestelde_product';

$aColumns = [
    'id',
    'naam',
    'omschrijving',
    'actief'];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'pc_samengestelde_product';
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/samengestelde_producten/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/samengestelde_producten/edit/' . $aRow['id'] . '">' . $aRow['naam'] . '</a>';
    $row[] = '<a href="/admin/venntech/samengestelde_producten/edit/' . $aRow['id'] . '">' . $aRow['omschrijving'] . '</a>';

    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox" data-switch-url="' . admin_url() . 'venntech/samengestelde_producten/change_samengestelde_product_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['actief'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['id'] . '"></label>
    </div>';
    $row[] = $toggleActive;

    // add to next index the data, no need to specify index the item will be added to the end.
    $row[] = icon_btn('venntech/samengestelde_producten/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
