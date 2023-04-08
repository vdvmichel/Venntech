<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends App_Model
{
    private string $table = "pc_items_extra";
    private string $table_name = "Items Extra";
    private string $items = "items";

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

    public function get_by_item_id($itemid)
    {
        $this->db->where('item_id', $itemid);
        return $this->db->get(db_prefix() . $this->table)->row();
    }

    public function get_active_items_combobox()
    {
        $query="SELECT it.id as id, it.description as description, ip.name as group_name from " . db_prefix()."items it "
            . "join " . db_prefix() ."pc_items_extra pie on it.id = pie.item_id "
            . "join " . db_prefix() ."items_groups ip on it.group_id = ip.id "
            . "where pie.active=1";
        $items_array = $this->db->query($query)->result_array();

        $select_options = array_map(function ($items) {
            $_option['id'] = $items['id'];
            $_option['name'] = $items['group_name'] . ' - ' .$items['description'];

            return $_option;
        }, $items_array);

        return $select_options;
    }

    public function get_active_items_combobox_by_groupid($groupid)
    {
        $query="SELECT it.id as id, it.description as description, ip.name as group_name from " . db_prefix()."items it "
            . "join " . db_prefix() ."pc_items_extra pie on it.id = pie.item_id "
            . "join " . db_prefix() ."items_groups ip on it.group_id = ip.id "
            . "where pie.active=1 and ip.id =". $groupid;
        $items_array = $this->db->query($query)->result_array();

        $select_options = array_map(function ($items) {
            $_option['id'] = $items['id'];
            $_option['name'] = $items['description'];
            $_option['group_name'] = $items['group_name'];

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

    public function change_product_status($id, $status)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, [
            'active' => $status,
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
        $original = $this->get($id);
        // delete items_extra
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . $this->table);

        $this->db->where('id', $original->item_id);
        $this->db->delete(db_prefix() . $this->items);

        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[ID:' . $id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }
}
