<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * Permissions
 *
 * @package	Auth
 * @author	Navruz
 */
class Permissions_model extends CI_Model {

    const TABLE = 'user_permissions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($user_id)
    {
        $this->db->select('permissions');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get(self::TABLE);
        if ($query->num_rows() == 1)
        {
            $data = $query->row_array();
            return unserialize($data['permissions']);
        }
        return FALSE;
    }

    /**
     *
     * @param array $sql_data
     * @return int
     */
    public function add($user_id, $permissions='')
    {
        $this->db->set('user_id', $user_id);
        $this->db->set('permissions', $permissions);
        $this->db->insert(self::TABLE);
        return $this->db->insert_id();
    }

    /**
     *
     * @param int $user_id
     * @param array $permissions
     * @return
     */
    public function update($user_id, $permissions)
    {
        if ($this->get($user_id) === FALSE)
        {
            $this->add($user_id, $permissions);
            return TRUE;
        }
        else
        {
            $this->db->where('user_id', $user_id);
            $this->db->set('permissions', $permissions);
            $this->db->update(self::TABLE);
            return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
        }
    }

    /**
     *
     * @param int $user_id
     * @return boolean
     */
    public function delete($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete(self::TABLE);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

}

/* End of file Permissions_model.php */