<?php

defined('BASEPATH') or exit('No direct script access allowed');

$baseCurrency = get_base_currency();

$table = db_prefix() . 'pc_items_extra';
$items = db_prefix() . 'items';
$items_groups = db_prefix() . 'items_groups';

$aColumns = [
    $table . '.id as id',
    $items_groups . '.name as group_name',
    $items . '.description as description',
    $table . '.kilo_watt_piek as kilo_watt_piek',
    $table . '.kilo_watt_uur as kilo_watt_uur',
    $table . '.technical_description as technical_description',
    'forfait',
    $items. '.rate as rate',
    'image_path',
    'active'];
$sIndexColumn = 'id';
$sTable = $table;
$join = ['JOIN ' . $items . ' ON ' . $table . '.item_id=' . $items . '.id', 'JOIN ' . $items_groups . ' ON ' . $items . '.group_id=' . $items_groups . '.id'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['group_name'] . '</a>';
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['kilo_watt_piek'] . '</a>';
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['kilo_watt_uur'] . '</a>';
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . $aRow['technical_description'] . '</a>';
    if ($aRow['forfait'] == 0){
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . _l('settings_no') .'</a>';
    }else{
        $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' .  _l('settings_yes') .'</a>';
    }
    $row[] = '<a href="/admin/venntech/producten/edit/' . $aRow['id'] . '">' . app_format_money($aRow['rate'], $baseCurrency) . '</a>';

    if ($aRow['image_path'] == "") {
        $row[] = '';
    } else {
        $image_full_path = base_url('modules/' . VENNTECH_MODULE_NAME . '/uploads/product/' . $aRow['image_path']);
        $row[] = '<a type="button" class="btn btn-info" href="' . $image_full_path . '" data-lightbox="gallery">' . _l('view') . '</button>';
    }

    if (staff_can('edit', FEATURE_PRODUCTEN)) {
        $toggleActive = '<div class="onoffswitch">
        <input type="checkbox" data-switch-url="' . admin_url() . 'venntech/producten/change_product_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['active'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="' . $aRow['id'] . '"></label>
        </div>';
        $row[] = $toggleActive;
    } else {
        $row[] = $aRow['actief'] == 1 ? '<i class="fa fa-check"></i>' : '';
    }

    // add to next index the data, no need to specify index the item will be added to the end.
    if (staff_can('delete', FEATURE_PRODUCTEN)) {
        $row[] = icon_btn('venntech/producten/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
