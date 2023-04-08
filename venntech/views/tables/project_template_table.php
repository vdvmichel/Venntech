<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_project_template';

$aColumns = [
    'id',
    'name',
    'description'];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'pc_project_template';
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/project_templates/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/project_templates/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    $row[] = '<a href="/admin/venntech/project_templates/edit/' . $aRow['id'] . '">' . $aRow['description'] . '</a>';

    // add to next index the data, no need to specify index the item will be added to the end.
    $row[] = icon_btn('venntech/project_templates/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
