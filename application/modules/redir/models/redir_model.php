<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Redir_model extends CI_Model {

    const TABLE = '_oldsite';

    /**
     * Yapıcı fonksiyon.
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Cat_model Model Initialized');
    }

    function get($module,$id)
    {
        $this->db->select('new_slug')
                ->where('module',$module)
                ->where('item_id',$id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }
}