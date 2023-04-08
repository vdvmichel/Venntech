<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Task_comments_model extends App_Model
{
    private string $table = "task_comments";
    private string $table_name = "Task Comments";

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

    public function add($data)
    {
        $this->db->insert(db_prefix() . $this->table, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity($this->table_name . ' Added[ID:' . $insert_id . ', Staff id ' . get_staff_user_id() . ']');

            return $insert_id;
        }
        return false;
    }

    public function edit($data)
    {
        $this->db->where('id', $data['id']);
        $res = $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', ' . $data['name'] . ', Staff id ' . get_staff_user_id() . ']');
        }

        return $res;
    }

    public function get_comment_by_task_id($taskid)
    {
        $this->db->where('taskid', $taskid);
        return $this->db->get(db_prefix() . $this->table)->row();
    }

}
