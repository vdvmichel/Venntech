<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimates_extra_model extends App_Model
{
    private string $table = "pc_estimates_extra";
    private string $table_name = "Estimate Extra";

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $item = $this->db->get(db_prefix() . $this->table)->row();
            return $item;
        }
        $this->db->order_by('id', 'desc');
        $items = $this->db->get(db_prefix() . $this->table)->result_array();

        return $items;
    }

    public function get_by_template_id($id)
    {
        $this->db->where('estimate_template_id', $id);
        $this->db->order_by('id', 'desc');
        $item = $this->db->get(db_prefix() . $this->table)->row();
        return $item;
    }

    /**
     * @param $data
     * @return returns the id if successful otherwise false
     */
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

    /**
     * @param $data
     * @return returns true if successful otherwise false
     */
    public function edit($data, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $id . ', Staff id ' . get_staff_user_id() . ']');
        }

        return $res;
    }

    /**
     * @param $id
     * @return returns true if successful otherwise false
     */
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
