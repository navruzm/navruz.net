<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redirect {

    CONST TABLE = 'redirect';

    private $ci;

    private $db;

    private $module;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper('redirect');
        $this->db = $this->ci->db;
        $this->module = $this->ci->router->fetch_module();
    }

    /**
     * @param  string $old_slug
     * @param  string $new_slug
     * @return void
     */
    public function set($old_slug, $new_slug)
    {
        $this->db->set('old_slug', $old_slug);
        $this->db->set('new_slug', $new_slug);
        $this->db->set('module', $this->module);
        $this->db->insert(self::TABLE);
        $this->delete($new_slug);
    }

    /**
     * @param  string $slug
     * @return boolean| string
     */
    public function get($slug)
    {
        $this->db->select('new_slug');
        $this->db->where('old_slug', $slug);
        $this->db->where('module', $this->module);
        $query = $this->db->get(self::TABLE);
        if($query->num_rows() > 0)
        {
            $result = $query->row_array();
            return $result['new_slug'];
        }
        return FALSE;
    }
    /**
     * @param  string $slug
     * @return void
     */
    public function delete($slug)
    {
        $this->db->where('old_slug', $slug);
        $this->db->where('module', $this->module);
        $this->db->delete(self::TABLE);
    }

    /**
     * @param  string $slug
     * @return void
     */
    public function delete_new($slug)
    {
        $this->db->where('new_slug', $slug);
        $this->db->where('module', $this->module);
        $this->db->delete(self::TABLE);
    }
}

/* End of file Redirect.php */