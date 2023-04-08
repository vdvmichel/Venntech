<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Opleverdocument_fotos_model extends App_Model
{
    private string $table = "pc_opleverdocument_fotos";
    private string $table_name = "Opleverdocument fotos";

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

    public function get_by_document_id($document_id)
    {
        $this->db->where('opleverdocument_id', $document_id);
        return $this->db->get(db_prefix() . $this->table)->row();
    }
    public function find_by_document_id($document_id)
    {
        $this->db->where('opleverdocument_id', $document_id);
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
    public function delete_by_parentid_filename($opleverdocument_id, $filename)
    {
        $this->db->where('opleverdocument_id', $opleverdocument_id);
        $this->db->where('filename', $filename);
        $this->db->delete(db_prefix() . $this->table);


        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[ID:' . $opleverdocument_id . $filename . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }
}
