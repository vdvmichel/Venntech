<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Taken_model extends App_Model
{
    private string $table = "tasks";
    private string $table_name = "Tasks";
    private string $inspectie = "pc_inspectie_rapport";
    private string $opleverdocument = "pc_opleverdocumenten";
    private string $plaatsing_datum = "pc_plaatsing_datum";
    private string $task_assigned = "task_assigned";


    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . $this->table)->row();
        }
        return $this->db->get(db_prefix() . $this->table)->result_array();
    }

    public function get_inspectie_by_task_id($taskid)
    {
        $this->db->where('taskid', $taskid);
        return $this->db->get(db_prefix() . $this->inspectie)->row();
    }

    public function get_opleverdocument_by_task_id($taskid)
    {
        $this->db->where('taskid', $taskid);
        return $this->db->get(db_prefix() . $this->opleverdocument)->row();
    }

    public function get_plaatsing_datum_by_task_id($taskid)
    {
        $this->db->where('taskid', $taskid);
        return $this->db->get(db_prefix() . $this->plaatsing_datum)->row();
    }

    public function get_staffid_by_task_id($task_id)
    {
        if ($task_id > 0) {
            $sql = 'SELECT ta.staffid as staffid FROM ' . db_prefix() . $this->task_assigned . ' ta JOIN ' . db_prefix() . $this->table . ' t on (t.id=ta.taskid) WHERE ta.taskid = ' . $task_id . ' limit 1';
            $result_array = $this->db->query($sql)->result_array();
            return $result_array[0]['staffid'];
        }
        return '';
    }
}
