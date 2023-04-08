<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'tasks';
$tag = db_prefix() . 'tags';
$taggable = db_prefix() . 'taggables';
$task_assigned = db_prefix() . 'task_assigned';

$aColumns = [
    $table . '.id as id',
    'name',
    get_sql_select_task_asignees_full_names() . ' as assignees',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'tasks.id and rel_type="task" ORDER by tag_order ASC) as tags',
    'startdate'];

$sIndexColumn = 'id';
$sTable = $table;

$join = [];

$where = ['AND status != 5'];

if (!is_admin()) {
    $join = ['LEFT JOIN ' . $task_assigned . ' ON ' . $table . '.id=' . $task_assigned . '.taskid'];

    $where[] = 'AND staffid = ' . get_staff_user_id();
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,
    ['(SELECT staffid FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . db_prefix() . 'tasks.id AND staffid=' . get_staff_user_id() . ') as is_assigned',
        get_sql_select_task_assignees_ids() . ' as assignees_ids',
        db_prefix() . 'tasks.id as task_id',]);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/taken/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';

    $row_option = '<a href="/admin/venntech/taken/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';

    $row_option = '<a href="/admin/venntech/taken/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    $row_option .= '<div class="row-options" style="opacity:0.6;">'
        . '<span ><a href="#" class="text-dark  tasks-table-start-timer" onclick="timer_action(this,' . $aRow['task_id'] . '); return false;">' . _l('task_start_timer') . '</a></span>'
        . '</div>';

    $row[] = $row_option;
    $row[] = format_members_by_ids_and_names($aRow['assignees_ids'], $aRow['assignees']);
    $row[] = '<a href="/admin/venntech/taken/edit/' . $aRow['id'] . '">' . $aRow['tags'] . '</a>';
    $row[] = '<a href="/admin/venntech/taken/edit/' . $aRow['id'] . '">' . $aRow['startdate'] . '</a>';


    // add to next index the data, no need to specify index the item will be added to the end.
    if (staff_can('delete', FEATURE_TAKEN)) {
        $row[] = icon_btn('venntech/taken/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    } else {
        $row[] = '';
    }
    $output['aaData'][] = $row;
}
