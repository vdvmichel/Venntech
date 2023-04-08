<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_instellingen_taak';
$staff = db_prefix() . 'staff';

$aColumns = [
    $table. '.id as id',
    $table. '.task_order as task_order',
    $table . '.name as name',
    $table . '.tag_name as tag_name',
    'CONCAT('.$staff.'.firstname, \' \', '.$staff.'.lastname) as assignees'];

$sIndexColumn = 'id';
$sTable = $table;
$join = ['JOIN ' . $staff . ' ON ' . $table . '.staffid=' . $staff . '.staffid'];
$where = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,
    [
        $table . '.tag_id as tag_id',
        $staff.'.staffid assignees_ids'
    ]
);

$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $key => $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/settings_taak/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/settings_taak/edit/' . $aRow['id'] . '">' . $aRow['task_order'] . '</a>';
    $row[] = '<a href="/admin/venntech/settings_taak/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    $row[] = '<a href="/admin/venntech/settings_taak/edit/' . $aRow['id'] . '">' . $aRow['tag_name'] . '</a>';
    $row[] = format_members_by_ids_and_names($aRow['assignees_ids'], $aRow['assignees']);
    //$row[] = '<a href="/admin/venntech/settings_taak/edit/' . $aRow['id'] . '">' . $aRow['staff'] . '</a>';


    $actions = '';

    if($key != 0){
        $actions = $actions . icon_btn('venntech/settings_taak/task_order_up/' . $aRow['id'], 'angle-up', 'btn-info');
    }

    if($key != sizeof($rResult) - 1){
        $actions = $actions . icon_btn('venntech/settings_taak/task_order_down/' . $aRow['id'], 'angle-down', 'btn-info');
    }

    // add to next index the data, no need to specify index the item will be added to the end.
    if (staff_can('delete', FEATURE_TAKEN)) {
        $actions = $actions . icon_btn('venntech/settings_taak/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    }

    $row[] = $actions;

    $output['aaData'][] = $row;
}
