<?php

defined('BASEPATH') or exit('No direct script access allowed');

$parameters = $this->input->get();
//echo "parameters: $parameters";

$table = db_prefix() . 'pc_estimate_template_element';

$aColumns = [
    'id',
    'name'];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'pc_estimate_template_element';
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    // init empty array
    $row = [];

    $row[] = '<a href="/admin/venntech/estimate_template_elements/edit/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
    $row[] = '<a href="/admin/venntech/estimate_template_elements/edit/' . $aRow['id'] . '">' . $aRow['name'] . '</a>';

    // add to next index the data, no need to specify index the item will be added to the end.
    $actions = icon_btn('venntech/estimate_templates/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[] = $actions;
    $output['aaData'][] = $row;
}
