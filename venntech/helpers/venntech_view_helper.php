<?php

defined('BASEPATH') or exit('No direct script access allowed');


function get_settings_taak_name($id)
{
    $CI = &get_instance();

    $CI->db->where('id', $id);
    $taak = $CI->db->get(db_prefix() . 'pc_instellingen_taak')->row();

    return $taak->name;
}

function create_default_plaatsing_datum($clientid, $taskid = '')
{
    $CI = &get_instance();

    $data['clientid'] = $clientid;
    $data['taskid'] = $taskid;
    $CI->db->insert(db_prefix() . "pc_plaatsing_datum", $data);

    return $CI->db->insert_id();
}

function create_default_oplever_document($clientid, $taskid = '')
{
    $CI = &get_instance();

    $data['clientid'] = $clientid;
    $data['taskid'] = $taskid;
    $CI->db->insert(db_prefix() . "pc_opleverdocumenten", $data);

    $insert_id = $CI->db->insert_id();
    $data_child['opleverdocument_id'] = $insert_id;
    $CI->db->insert(db_prefix() . "pc_opleverdocument_algemeen", $data_child);
    $CI->db->insert(db_prefix() . "pc_opleverdocument_installatie", $data_child);

    return $insert_id;
}