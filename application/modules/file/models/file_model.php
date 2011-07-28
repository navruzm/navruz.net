<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model {
    
    CONST TABLE = 'files';

    /**
     * Modelin yapıcı fonksiyonu. Model sınıfının __construct metodunu çalıştırır.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int $per_page
     * @param int $offset
     * @return array
     */
    public function get_files($per_page, $offset)
    {
        $this->db->order_by('file_id', 'desc');
        $query = $this->db->get(self::TABLE, $per_page, $offset);
        return $query->result_array();
    }

    /**
     * @param int $file_id
     * @return boolean|array
     */
    public function get_file($file_id)
    {
        $this->db->where('file_id', $file_id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     *
     * @param string $file_name
     * @return array
     */
    public function get_file_by_name($file_name)
    {
        $this->db->where('file_name', $file_name);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     
     * @param array $sql_data
     * @param array $cat_data
     * @return int
     */
    public function add_file($sql_data)
    {
        $this->db->insert(self::TABLE, $sql_data);
        return $this->db->insert_id();
    }

    /**
     *
     * @param int $file_id
     * @param array $sql_data
     * @param array $cat_data
     * @return
     */
    public function update_file($file_id, $sql_data)
    {
        $this->db->where('file_id', $file_id);
        $this->db->update(self::TABLE, $sql_data);
        return ($this->db->affected_rows() > 0);
    }

    /**
     *
     * @param int $file_id
     * @return boolean
     */
    public function delete_file($file_id)
    {
        $this->db->where('file_id', $file_id);
        $this->db->delete(self::TABLE);
        return ($this->db->affected_rows() > 0);
    }

    /**
     *
     * @param <type> $file_id
     */
    public function increase_count($file_id)
    {
        $this->db->where('file_id', $file_id);
        $this->db->set('file_download_count', 'file_download_count + 1', FALSE);
        $this->db->update(self::TABLE);
    }
}

//End of file file_model.php