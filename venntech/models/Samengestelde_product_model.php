<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Samengestelde_product_model extends App_Model
{
    private string $table = "pc_samengestelde_product";
    private string $table_name = "Samengestelde Product";

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

    public function get_active_samengestelde_producten_combobox()
    {
        $query = "SELECT sp.* from " . db_prefix() . $this->table . " sp where sp.actief=1";
        $items_array = $this->db->query($query)->result_array();

        $select_options = array_map(function ($items) {
            $_option['id'] = $items['id'];
            $_option['name'] = $items['naam'];

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
        $this->db->insert(db_prefix() . $this->table, [
            'naam' => $data['naam'],
            'omschrijving' => $data['omschrijving'],
        ]);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity($this->table_name . ' Added[ID:' . $insert_id . ', ' . $data['naam'] . ', Staff id ' . get_staff_user_id() . ']');

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
            'naam' => $data['naam'],
            'omschrijving' => $data['omschrijving'],
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $data['id'] . ', ' . $data['naam'] . ', Staff id ' . get_staff_user_id() . ']');
        }

        return $res;
    }

    public function change_samengestelde_product_status($id, $status)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, [
            'actief' => $status,
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $id['id'] . ', status:' . $status . ', Staff id ' . get_staff_user_id() . ']');
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
