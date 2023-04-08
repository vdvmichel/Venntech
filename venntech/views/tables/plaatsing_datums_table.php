<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_plaatsing_datum';
$clients = db_prefix() . 'clients';
$tasks = db_prefix() . 'tasks';
$task_assigned = db_prefix() . 'task_assigned';


$aColumns = [
    $table . '.id as id',
    $tasks . '.name as name',
    $table . '.datum as datum',
    get_sql_select_client_company()];

$sIndexColumn ='id';
$sTable = $table;
$join = [
    'JOIN ' . $clients . ' ON ' . $table . '.clientid=' . $clients . '.userid',
    'LEFT JOIN ' . $tasks . ' ON ' . $table . '.taskid=' . $tasks . '.id'
];
$where = [];

if (!is_admin()) {
    $where[] = 'AND '.$task_assigned.'.staffid = ' . get_staff_user_id();
    $join[] = 'LEFT JOIN ' . $task_assigned . ' ON ' . $tasks . '.id=' . $task_assigned . '.taskid';
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,  [
    $table . '.clientid as clientid',
    $tasks . '.id as task_id',
    '(SELECT staffid FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . db_prefix() . 'tasks.id AND staffid=' . get_staff_user_id() . ') as is_assigned'
]);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/plaatsing_datums/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';

    if ($aRow['task_id'] != '') {
        $row_option = '<a href="/admin/venntech/plaatsing_datums/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
        $row_option .= '<div class="row-options" style="opacity:0.6;">' . '<span >
        <a href="#" class="text-dark  tasks-table-start-timer" onclick="timer_action(this,' . $aRow['task_id'] . '); return false;">' . _l('task_start_timer') . '</a>
        </span>' . '</div>';
    } else {
        $row_option = '<a href="/admin/venntech/plaatsing_datums/edit/' . $aRow['id'] . '">' . _l('plaatsing_datum') . ' - ' . $aRow['company'] . '</a>';
    }
    $row[] = $row_option;
    $row[] = '<a href="/admin/venntech/plaatsing_datums/edit/' . $aRow['datum'] . '">' . $aRow['datum'] . '</a>';
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';


    // add to next index the data, no need to specify index the item will be added to the end.
    /*if (staff_can('delete', FEATURE_OPLEVERDOCUMENT)) {
        $row[] = icon_btn('venntech/plaatsing_datum/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    } else {
        $row[] = '';
    }*/
    $output['aaData'][] = $row;
}
