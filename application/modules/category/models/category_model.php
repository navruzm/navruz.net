<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package Category_model
 *
 * @property CI_DB_active_record $db
 *
 */
class Category_model extends CI_Model {

    const TABLE = 'categories';
    const TABLE_RELATIONSHIP = 'post_relationship';
    const TABLE_POSTS = 'posts';

    /**
     * Yapıcı fonksiyon.
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Cat_model Model Initialized');
    }

    /**
     * Tüm kategorileri döndürür.
     * @return array
     */
    public function get_categories()
    {
        $this->db->order_by('weight');
        $query = $this->db->get(self::TABLE);
        return $query->result_array();
    }

    /**
     * Tüm kategorileri içerisindeki yazı sayısı ile döndürür.
     * @return array
     */
    public function get_categories_with_post_size()
    {
        $this->db->select('c.category_title, c.category_slug');
        $this->db->select('COUNT(r.id) as posts');
        $this->db->from(self::TABLE . ' as c');
        $this->db->join(self::TABLE_RELATIONSHIP . ' as r', 'c.category_id = r.category_id', 'left');
        $this->db->where_not_in('c.category_id', 1);
        $this->db->group_by('r.category_id');
        $this->db->order_by('c.weight');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * İlgili kategoriyi döndürür.
     * @param  $category_id int
     * @return array
     */
    public function get_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     * get_category metodu ile aynı işleve sahiptir.
     * @param  $category_slug string
     * @return array
     */
    public function get_category_by_slug($category_slug)
    {
        $this->db->where('category_slug', $category_slug);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     * İlgili kategorinin yazılarını döndürür.
     * @param  $category_id int
     * @param  $per_page int
     * @param  $offset int
     * @return array
     */
    public function get_category_posts($category_id, $per_page, $offset)
    {
        $this->db->select('*');
        $this->db->from(self::TABLE_RELATIONSHIP . ' as r');
        $this->db->join(self::TABLE_POSTS . ' as g', 'r.post_id = g.id', 'left');
        $this->db->where('category_id', $category_id);
        $this->db->order_by('g.id', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Kategori ekler.
     * @param  $sql_data array
     * @return boolean
     */
    public function add_category($sql_data)
    {
        $weight = $this->_get_max_weight();
        $sql_data['weight'] = ++$weight['weight'];
        $this->db->insert(self::TABLE, $sql_data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Mevcut bir kategoriyi günceller.
     * @param  $sql_data array
     * @return boolean
     */
    public function update_category($sql_data)
    {
        $this->db->where('category_id', $sql_data['category_id']);
        $this->db->update(self::TABLE, $sql_data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Kategori silmeye yarar.
     * @param  $category_id int
     * @return boolean
     */
    public function delete_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        $this->db->delete(self::TABLE);
        if ($this->db->affected_rows() > 0)
        {
            $this->_delete_relationship($category_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Kategori-Yazı ilişkilerini siler.
     * @access private
     * @param  $category_id int
     * @return void
     */
    private function _delete_relationship($category_id)
    {
        $this->db->where('category_id', $category_id);
        $this->db->delete(self::TABLE_RELATIONSHIP);
        return;
    }

    /**
     * Kategori sıralamasını kaydeder. 
     * @param  $category_id int
     * @param  $weight int
     * @return void
     */
    public function sort_category($category_id, $weight)
    {
        $this->db->where('category_id', $category_id);
        $this->db->update(self::TABLE, array('weight' => $weight));
    }

    /**
     * Kategori url'sinin daha önce eklenip eklenmediğini kontrol eden metod.
     * @param  $slug string
     * @return int
     */
    public function is_slug_available($slug)
    {
        $this->db->where('category_slug', $slug);
        $query = $this->db->get(self::TABLE);
        return $query->num_rows();
    }

    /**
     * Kategori eklenirken sıra numarasını hesaplamak için kullanılan metod.
     * @access private
     * @return int
     */
    private function _get_max_weight()
    {
        $this->db->select_max('weight');
        $query = $this->db->get(self::TABLE);
        return ($this->db->affected_rows() > 0) ? $query->row_array() : 0;
    }

}

/* End of file category_model.php */