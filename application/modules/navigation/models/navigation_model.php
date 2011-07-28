<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Navigation_model extends CI_Model
{
    CONST TABLE = 'navigations';


    CONST TABLE_GROUP = 'navigation_groups';

    /**
     * Modelin yapıcı fonksiyonu. Model sınıfının __construct metodunu çalıştırır.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function get_links($group, $level = NULL)
    {
        if ($level != NULL)
        {
            $this->db->where_in('access_level', $level);
        }
        $this->db->where('group', $group);
        $this->db->order_by('weight asc');
        $query = $this->db->get(self::TABLE);
        return $query->result_array();
    }

    public function get_links_by_tag($tag, $level = NULL)
    {
        //@todo eklenecek
    }

    public function get_link($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    public function add_link($sql_data)
    {
        $weight = $this->_get_link_max_weight($sql_data['group']);
        $sql_data['weight'] = ++$weight['weight'];
        $this->db->insert(self::TABLE, $sql_data);
        return $this->db->insert_id();
    }

    public function update_link($id, $sql_data)
    {
        $this->db->where('id', $id);
        $this->db->update(self::TABLE, $sql_data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function delete_link($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(self::TABLE);
        return $this->db->affected_rows() > 0;
    }

    public function delete_links($group_id)
    {
        $this->db->where('group', $group_id);
        $this->db->delete(self::TABLE);
        return $this->db->affected_rows() > 0;
    }

    private function _get_link_max_weight($group_id)
    {
        $this->db->select_max('weight');
        $this->db->where('group', $group_id);
        $query = $this->db->get(self::TABLE);
        return ($this->db->affected_rows() > 0) ? $query->row_array() : 0;
    }

    public function sort_link($id, $weight)
    {
        $this->db->where('id', $id);
        $this->db->update(self::TABLE, array('weight' => $weight));
    }

    /**
     * Authors
     */
    public function get_groups()
    {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get(self::TABLE_GROUP);
        return $query->result_array();
    }

    public function get_group($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(self::TABLE_GROUP);
        return $query->row_array();
    }

    public function get_group_by_tag($tag)
    {
        $this->db->where('tag', $tag);
        $query = $this->db->get(self::TABLE_GROUP);
        return $query->row_array();
    }

    public function add_group($sql_data)
    {
        $this->db->insert(self::TABLE_GROUP, $sql_data);
        return $this->db->insert_id();
    }

    public function update_group($id, $sql_data)
    {
        $this->db->where('id', $id);
        $this->db->update(self::TABLE_GROUP, $sql_data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(self::TABLE_GROUP);
        if ($this->db->affected_rows() > 0)
        {
            $this->delete_links($id);
            return TRUE;
        }
        return FALSE;
    }

}