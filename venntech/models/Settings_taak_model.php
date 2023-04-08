<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings_taak_model extends App_Model
{
    private string $table = "pc_instellingen_taak";
    private string $table_name = "Instellingen Taak";

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

    public function get_max_order()
    {
        $this->db->select_max('task_order');
        return $this->db->get(db_prefix() . $this->table)->row()->task_order;
    }

    public function get_by_order($order)
    {
        $this->db->where('task_order', $order);
        $item = $this->db->get(db_prefix() . $this->table)->row();
        return $item;
    }

    public function get_options()
    {
        $items_array = $this->db->get(db_prefix() . $this->table)->result_array();

        $select_options = array_map(function ($items) {
            $_option['id'] = $items['id'];
            $_option['firstname'] = $items['firstname'];

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
        $res = $this->db->update(db_prefix() . $this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', ' . $data['name'] . ', Staff id ' . get_staff_user_id() . ']');
        }

        return $res;
    }

    public function update_order($id, $order)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, [
            'task_order' => $order
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $id . ', order:' . $order . ', Staff id ' . get_staff_user_id() . ']');
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
