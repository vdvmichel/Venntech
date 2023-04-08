<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_template_items_model extends App_Model
{
    private string $table = "pc_estimate_template_items";
    private string $table_name = "Estimate Template Items";

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
        return $this->db->get(db_prefix() . $this->table)->result_array();
    }

    public function get_by_estimate_template_id($estimate_template_id, $rel_type = '')
    {
        $query = "select * from " . db_prefix() . $this->table
            . " where estimate_template_id = " . $estimate_template_id;
            if ($rel_type != '') {
                $query = $query . " and rel_type='" . $rel_type . "'";
            }
        return $this->db->query($query)->result_array();
    }

    public function get_by_estimate_template($estimate_template_id, $estimate_template_element_id, $rel_type)
    {
        $query = "select * from " . db_prefix() . $this->table
            . " where estimate_template_id = " . $estimate_template_id
            . " and estimate_template_element_id= " . $estimate_template_element_id
            . " and rel_type='" . $rel_type . "'";
        return $this->db->query($query)->result_array();
    }

    /**
     * returns 1 row object
     */
    public function get_by_estimate_template_releation($estimate_template_id, $rel_id, $rel_type)
    {
        $query = "select * from " . db_prefix() . $this->table
            . " where estimate_template_id = " . $estimate_template_id
            . " and rel_id= " . $rel_id
            . " and rel_type='" . $rel_type . "'";
        return $this->db->query($query)->row();
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

    public function delete_by_element_id($estimate_template_id, $estimate_template_element_id)
    {
        $this->db->where('estimate_template_id', $estimate_template_id);
        $this->db->where('estimate_template_element_id', $estimate_template_element_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[estimate_template_element_id:' . $estimate_template_element_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }

    public function delete_by_template_id($estimate_template_id)
    {
        $this->db->where('estimate_template_id', $estimate_template_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[estimate_template_id:' . $estimate_template_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }
}
