<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_template_tasks_model extends App_Model
{
    private string $table = "pc_project_template_tasks";
    private string $table_name = "Project Template Tasks";

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

    public function get_by_order($order)
    {
        $this->db->where('task_order', $order);
        $item = $this->db->get(db_prefix() . $this->table)->row();
        return $item;
    }

    public function get_by_project_template_id($project_template_id)
    {
        $query = "select * from " . db_prefix() . $this->table . " where project_template_id = " . $project_template_id . " order by task_order ASC";
        return $this->db->query($query)->result_array();
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

    function getTagId($name)
    {
        $this->db->where('name', $name);
        $tag = $this->db->get(db_prefix() . "tags")->row();
        if ($tag) {
            return $tag->id;
        } else {
            $this->db->query("insert into ".db_prefix()."tags (name) values('" . $name . "')");
            return $this->db->insert_id();
        }
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

    function change_task_status($id, $status)
    {
        $this->db->where('id', $id);
        $res = $this->db->update(db_prefix() . $this->table, [
            'enabled' => $status
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' updated[ID:' . $id . ', status: ' . $status. ', Staff id ' . get_staff_user_id() . ']');
        }
    }


    public function delete_all_by_project_template_id($project_template_id)
    {
        $this->db->where('project_template_id', $project_template_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[project_template_id:' . $project_template_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }

    public function delete_by_settings_taak_id($settings_taak_id)
    {
        $this->db->where('instellingen_taak_id', $settings_taak_id);
        $this->db->delete(db_prefix() . $this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->table_name . ' deleted[settings_taak_id:' . $settings_taak_id . ', Staff id ' . get_staff_user_id() . ']');

            return true;
        }

        return false;
    }
}
