<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table = db_prefix() . 'pc_estimate_template';
$project_template = db_prefix() . 'pc_project_template';

$aColumns = [
    $table . '.id as id',
    $table . '.name as name',
    $project_template.'.name as project'];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'pc_estimate_template';
$join = ['JOIN ' . $project_template . ' ON ' . $table . '.project_template_id = ' . $project_template . '.id'];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/estimate_templates/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/estimate_templates/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';
    $row[] = '<a href="/admin/venntech/estimate_templates/edit/' . $aRow['id'] . '">' . $aRow['project'] . '</a>';
    $row[] = '<a href="/admin/venntech/estimate_pdf_layouts/edit/' . $aRow['id'] . '">PDF Layout</a>';

    // add to next index the data, no need to specify index the item will be added to the end.
    $actions = icon_btn('venntech/estimate_templates/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[] = $actions;
    $output['aaData'][] = $row;
}
