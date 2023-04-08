<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Samengestelde_product_items_model extends App_Model
{
    private string $table = "pc_samengestelde_product_items";
    private string $table_name = "Samengestelde Product Items";

    public function __construct()
    {
        parent::__construct();
    }


    public function get($id = false)
    {
        $this->db->where('samengestelde_product_id', $id);
        return $this->db->get(db_prefix() . $this->table)->result_array();
    }

    /**
     * @param $id
     * @return returns true if successful otherwise false
     */
    public function add($samengestelde_product_id, $product_id)
    {
        $data['samengestelde_product_id']= $samengestelde_product_id;
        $data['item_id']= $product_id;
        $this->db->insert(db_prefix() . $this->table, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity($this->table_name . ' Added[ID:' . $insert_id . ', ' . $data['naam'] . ', Staff id ' . get_staff_user_id() . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * @param $id
     * @return returns true if successful otherwise false
     */
    public function deleteAll($samengestelde_product_id)
    {
        $this->db->where('samengestelde_product_id', $samengestelde_product_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[ID:' . $samengestelde_product_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }
}
