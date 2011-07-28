<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post_model extends CI_Model
{
    /**
     * Yazıların tutulduğu veritabanı tablosunun ismi
     */
    CONST TABLE = 'posts';

    /**
     * Yazıların kategori ilişkilerinin tutulduğu veritabanı tablosunun ismi
     */
    CONST TABLE_RELATIONSHIP = 'post_relationship';

    /**
     * Yazıların kategorilerinin tablo ismi
     */
    CONST TABLE_CATEGORIES = 'categories';

    CONST TABLE_USERS = 'users';

    CONST TABLE_USER_PROFILE = 'user_profiles';

    private $ci;

    /**
     * Modelin yapıcı fonksiyonu. Model sınıfının __construct metodunu çalıştırır.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ci = & get_instance();
    }

    /**
     * Yazıları sayfa sayfa veritabanından alır.
     *
     * @param int $per_page
     * @param int $offset
     * @return array
     */
    public function get_posts($per_page, $offset)
    {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get(self::TABLE, $per_page, $offset);
        return $query->result_array();
    }

    /**
     * get_post_by_slug fonksiyonun takma adı.
     * @param  string $slug
     * @return array
     */
    public function get_post($slug)
    {
        return $this->get_post_by_slug($slug);
    }

    /**
     * slug değişkeniyle belirli bir yazıyı getirir. Yazı kayıtlı değilse NULL döndürür.
     *
     * @param string $slug
     * @return boolean|array
     */
    public function get_post_by_slug($slug)
    {
        $this->db->select('p.*,i.bio as author_bio,u.username as author_username');
        $this->db->select('CONCAT_WS(\' \', first_name, last_name) AS author_name', FALSE);
        $this->db->from(self::TABLE . ' as p');
        $this->db->join(self::TABLE_USERS . ' as u', 'p.author = u.id', 'left');
        $this->db->join(self::TABLE_USER_PROFILE . ' as i', 'p.author = i.user_id', 'left');
        $this->db->where('p.slug', $slug);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * get_post fonksiyonu ile yazıyı id numarasına göre çekmesi dışında aynı işleve sahiptir.
     *
     * @param int $id
     * @return boolean|array
     */
    public function get_post_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     * Veritabanına yeni yazı ekler. 2. parametre ile gelen yazı kategorilerini ekler.
     * Eklenen yazının id'sini döndürür.
     *
     * @param array $sql_data
     * @param array $cat_data
     * @return int
     */
    public function add_post($sql_data, $cat_data)
    {
        $this->db->insert(self::TABLE, $sql_data);
        $id = $this->db->insert_id();
        if ($id > 0)
        {
            if (sizeof($cat_data) > 0)
            {
                foreach ($cat_data as $category_id)
                {
                    $this->add_relationship($id, $category_id);
                }
            }
            return $id;
        }
        return FALSE;
    }

    /**
     * Yazıyı günceller.
     *
     * @param int $id
     * @param array $sql_data
     * @param array $cat_data
     * @return
     */
    public function update_post($id, $sql_data, $cat_data = FALSE)
    {
        $this->db->where('id', $id);
        $this->db->update(self::TABLE, $sql_data);
        if ($cat_data === FALSE)
            return;
        $this->delete_relationship($id);
        if (sizeof($cat_data) > 0)
        {
            foreach ($cat_data as $category_id)
            {
                $this->add_relationship($id, $category_id);
            }
        }
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * id ile gelen id numaralı yazıyı ve kategori ilişkilerini siler.
     * Sonuca göre TRUE veya FALSE döndürür.
     *
     * @param int $id
     * @return boolean
     */
    public function delete_post($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(self::TABLE);
        if ($this->db->affected_rows() > 0)
        {
            $this->delete_relationship($id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Yazıların kategori ilişkilerini ilgili tabloya ekler.
     * Sonuca göre TRUE veya FALSE döndürür.
     *
     * @param int $id
     * @param int $category_id
     * @return boolean
     */
    private function add_relationship($id, $category_id)
    {
        $this->db->set('post_id', $id);
        $this->db->set('category_id', $category_id);
        $this->db->insert(self::TABLE_RELATIONSHIP);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Yazıların kategori ilişkilerini siler.
     *
     * @param int $id
     * @return void
     */
    private function delete_relationship($id)
    {
        $this->db->where('post_id', $id);
        $this->db->delete(self::TABLE_RELATIONSHIP);
    }

    /**
     * Verilen slug'ın veritabanında olup olmadığını kontrol eder.
     * @param string $slug
     * @return int
     */
    public function is_slug_available($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get(self::TABLE);
        return $query->num_rows();
    }

    /**
     * Yazının kategorilerini verir.
     * @param int $id
     * @return array
     */
    function get_post_categories($id)
    {
        $this->db->select('*');
        $this->db->from(self::TABLE_RELATIONSHIP . ' as r');
        $this->db->join(self::TABLE_CATEGORIES . ' as c', 'r.category_id = c.category_id', 'left');
        $this->db->where('post_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function related($id_arr)
    {
        $query = ('SELECT slug, title FROM ' . $this->db->dbprefix(self::TABLE) . '
        WHERE id IN (' . implode(',', $id_arr) . ')
        ORDER BY FIELD(id,' . implode(',', $id_arr) . ')');
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function get_post_for_sitemap()
    {
        $this->db->select('p.created_on,p.updated_on,p.slug');
        $this->db->from(self::TABLE . ' as p');
        $this->db->order_by('p.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function increase_view_count($id)
    {
        $this->db->where('id', $id);
        $this->db->set('counter', 'counter + 1', FALSE);
        $this->db->update(self::TABLE);
    }

    public function search($per_page, $offset, $keyword)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('content', $keyword);
        $this->db->or_like('summary', $keyword);
        $this->db->limit($per_page, $offset);
        $this->db->order_by('created_on', 'desc');
        $query = $this->db->get(self::TABLE);
        return $query->result_array();
    }

    public function search_count($keyword)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('content', $keyword);
        $this->db->or_like('summary', $keyword);
        return $this->db->count_all_results(self::TABLE);
    }

}