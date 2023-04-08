<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Opleverdocument_model extends App_Model
{
    private string $table = "pc_opleverdocumenten";
    private string $table_name = "Opleverdocumenten";
    private string $task_assigned = "task_assigned";
    private string $tasks = "tasks";

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
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', Staff id ' . get_staff_user_id() . ']');
        }

        return $res;
    }

    public function edit_image_path($image_path, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, [
            'image_path' => $image_path,
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $id . ', image_path:' . $image_path . ', Staff id ' . get_staff_user_id() . ']');
        }
        return $res;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[ID:' . $id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }

}
