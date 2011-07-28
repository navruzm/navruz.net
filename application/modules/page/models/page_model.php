<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page_model extends CI_Model
{
    /**
     * Sayfaların tutulduğu veritabanı tablosunun ismi
     */
    CONST TABLE = 'pages';

    /**
     * Modelin yapıcı fonksiyonu. Model sınıfının __construct metodunu çalıştırır.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sayfaları sayfa sayfa veritabanından alır.
     *
     * @param int $per_page
     * @param int $offset
     * @return array
     */
    public function get_pages($per_page, $offset)
    {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get(self::TABLE, $per_page, $offset);
        return $query->result_array();
    }

    /**
     * get_page_by_slug fonksiyonun takma adı.
     * @param  string $page_slug
     * @return array
     */
    public function get_page($page_slug)
    {
        return $this->get_page_by_slug($page_slug);
    }

    /**
     * slug değişkeniyle belirli bir sayfayı getirir. Sayfa kayıtlı değilse NULL döndürür.
     *
     * @param string $page_slug
     * @return boolean|array
     */
    public function get_page_by_slug($page_slug)
    {
        $this->db->where('slug', $page_slug);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     * get_page fonksiyonu ile sayfayı id numarasına göre çekmesi dışında aynı işleve sahiptir.
     *
     * @param int $page_id
     * @return boolean|array
     */
    public function get_page_by_id($page_id)
    {
        $this->db->where('id', $page_id);
        $query = $this->db->get(self::TABLE);
        return $query->row_array();
    }

    /**
     * Veritabanına yeni sayfa ekler. 2. parametre ile gelen sayfa kategorilerini ekler.
     * Eklenen sayfanın id'sini döndürür.
     *
     * @param array $sql_data
     * @return int
     */
    public function add_page($sql_data)
    {
        $this->db->insert(self::TABLE, $sql_data);
        return $this->db->insert_id();
    }

    /**
     * Sayfayı günceller.
     *
     * @param int $page_id
     * @param array $sql_data
     * @return
     */
    public function update_page($page_id, $sql_data)
    {
        $this->db->where('id', $page_id);
        $this->db->update(self::TABLE, $sql_data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * page_id ile gelen id numaralı sayfayı ve kategori ilişkilerini siler.
     * Sonuca göre TRUE veya FALSE döndürür.
     *
     * @param int $page_id
     * @return boolean
     */
    public function delete_page($page_id)
    {
        $this->db->where('id', $page_id);
        $this->db->delete(self::TABLE);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
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

    function get_pages_for_sitemap()
    {
        $this->db->select('p.created_on,p.updated_on,p.slug');
        $this->db->from(self::TABLE . ' as p');
        $this->db->order_by('p.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function search($per_page, $offset, $keyword)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('content', $keyword);
        $this->db->order_by('created_on', 'desc');
        $query = $this->db->get(self::TABLE, $per_page, $offset);
        return $query->result_array();
    }

    public function search_count($keyword)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('content', $keyword);
        return $this->db->count_all_results(self::TABLE);
    }
    public function increase_view_count($id)
    {
        $this->db->where('id', $id);
        $this->db->set('counter', 'counter + 1', FALSE);
        $this->db->update(self::TABLE);
    }
}