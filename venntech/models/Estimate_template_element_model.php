<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_template_element_model extends App_Model
{
    private string $table = "pc_estimate_template_element";
    private string $table_name = "Estimate Template Element";

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

    public function get_options()
    {
        $items_array = $this->db->get(db_prefix() . $this->table)->result_array();

        $select_options = array_map(function ($items) {
            $_option['id'] = $items['id'];
            $_option['name'] = $items['name'];

            return $_option;
        }, $items_array);

        return $select_options;
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
            log_activity($this->table_name . ' Added[ID:' . $insert_id . ', ' . $data['name'] . ', Staff id ' . get_staff_user_id() . ']');

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
        $res = $this->db->update(db_prefix() . $this->table, [
            'name' => $data['name']
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', ' . $data['name'] . ', Staff id ' . get_staff_user_id() . ']');
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

    public function delete_by_template_id($template_id)
    {
        $this->db->where('estimate_template_id', $template_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[estimate_template_id:' . $template_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }

}
