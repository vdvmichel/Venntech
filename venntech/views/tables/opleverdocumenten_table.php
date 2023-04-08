<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_opleverdocumenten';
$clients = db_prefix() . 'clients';
$tasks = db_prefix() . 'tasks';
$task_assigned = db_prefix() . 'task_assigned';
$tasks = db_prefix() . 'tasks';
$verbruiksmaterialen = db_prefix() . 'pc_opleverdocument_verbruiksmaterialen';
$items = db_prefix() . 'items';

$aColumns = [
    $table . '.id as id',
    $tasks . '.name as name',
    get_sql_select_client_company(),
    $table . '.datum as datum',
    $table . '.verval_datum as verval_datum',];

$sIndexColumn = 'id';
$sTable = $table;
$join = [
    'JOIN ' . $clients . ' ON ' . $table . '.clientid=' . $clients . '.userid',
    'LEFT JOIN ' . $tasks . ' ON ' . $table . '.taskid=' . $tasks . '.id'
];
$where = [];
if (!is_admin()) {
    $where[] = 'AND ' . $task_assigned . '.staffid = ' . get_staff_user_id();
    $join[] = 'LEFT JOIN ' . $task_assigned . ' ON ' . $tasks . '.id=' . $task_assigned . '.taskid';
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'pc_opleverdocumenten.clientid',
    db_prefix() . 'tasks.id as task_id',
]);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $query = "select sum(total) as totaal_prijs
from (select vm.aantal * it.rate as total
      from  " . $table . " od
               inner join " . $verbruiksmaterialen . " vm on (od.id = vm.opleverdocument_id)
               inner join " . $items . " it on (it.id = vm.item_id)
      where od.id = " . $aRow['id'] . ") as rates
";
    $CI = &get_instance();
    $result = $CI->db->query($query)->row();

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';

    $row_option = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    if ($aRow['task_id'] != '') {
        $row_option = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . _l('opleverdocument') . ' - ' . $aRow['company'] . '</a>';
        $row_option .= '<div class="row-options" style="opacity:0.6;">' . '<span >
        <a href="#" class="text-dark  tasks-table-start-timer" onclick="timer_action(this,' . $aRow['task_id'] . '); return false;">' . _l('task_start_timer') . '</a>
        </span>' . '<span >
        <i href="#" class="text-dark  " > | </i>
        </span>' . '<span >
        <a href="#" class="text-dark  " >' . _l('verbruiksmaterialen') . '</a>
        </span>' . '</div>';
    } else {
        $row_option = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . _l('opleverdocument') . ' - ' . $aRow['company'] . '</a>';
        $row_option .= '<div class="row-options" style="opacity:0.6;">'  . '<span >
        <a href="/admin/venntech/opleverdocumenten_verbruiksmaterialen/view_table/' . $aRow['id'] . '" class="text-dark  " >' . _l('verbruiksmaterialen') . '</a>
        </span>' . '</div>';
    }
    $row[] = $row_option;
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    $row[] = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . $aRow['datum'] . '</a>';
    $row[] = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . $aRow['verval_datum'] . '</a>';
    $row[] = '<a href="/admin/venntech/opleverdocumenten/edit/' . $aRow['id'] . '">' . $result->totaal_prijs . '</a>';

    // add to next index the data, no need to specify index the item will be added to the end.
    if (staff_can('delete', FEATURE_OPLEVERDOCUMENT)) {
        $row[] = icon_btn('venntech/opleverdocumenten/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
