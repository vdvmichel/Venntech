<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimates_extra_items_model extends App_Model
{
    private string $table = "pc_estimates_extra_items";
    private string $table_name = "Estimate Extra Items";

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
        $items = $this->db->get(db_prefix() . $this->table)->result_array();

        return $items;
    }

    public function get_by_estimates_extra_relation($estimates_extra_id,  $rel_id, $rel_type)
    {
        $query = "select * from " . db_prefix() . $this->table
            . " where estimates_extra_id = " . $estimates_extra_id
            . " and rel_id=" . $rel_id
            . " and rel_type='" . $rel_type . "'";
        return $this->db->query($query)->result_array();
    }

    public function find_by_estimate_template_element_id($estimate_template_element_id)
    {
        $this->db->where('estimate_template_element_id', $estimate_template_element_id);
        return $this->db->get(db_prefix() . $this->table)->result_array();
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
    public function edit($data)
    {
        $this->db->where('id', $data['id']);
        $res = $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', Staff id ' . get_staff_user_id() . ']');
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

    /**
     * @param $id
     * @return returns true if successful otherwise false
     */
    public function delete_by_extra_id($estimates_extra_id)
    {
        $this->db->where('estimates_extra_id', $estimates_extra_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[estimates_extra_id:' . $estimates_extra_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }

}
