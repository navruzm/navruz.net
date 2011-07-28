<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Page_Model $page_model
 *
 */
class Admin extends Admin_Controller
{

    /**
     * Yönetici kontrolünü yapar ve gerekli kütüphane ve modelleri yükler
     */
    public function __construct()
    {
        parent::__construct();
        access_control();
        $this->load->model('page/page_model');
    }

    /**
     * Sayfaları listeler.
     *
     * @return void
     */
    public function index()
    {
        $this->load->library('pagination');
        $data['pagination'] = $this->pagination->init('admin/page/index', $this->db->count_all('pages'), 3, get_option('per_page_admin'));
        $data['pages'] = $this->page_model->get_pages(
                        get_option('per_page_admin'),
                        $this->pagination->get_offset()
        );

        $this->template->view('admin/list_pages', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Yeni sayfa ekler.
     *
     * @return void
     */
    public function add_page()
    {
        $this->load->library(array('form_validation'));
        $data = array();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Sayfa Başlığı', 'trim|required');
        $this->form_validation->set_rules('content', 'Sayfa İçeriği', 'required');
        if ($this->form_validation->run())
        {
            $slug = $this->_get_slug($this->form_validation->set_value('title'));
            $sql_data = array(
                'author' => get_user_id(),
                'slug' => $slug,
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
                'comments_enabled' => $this->input->post('comments_enabled'),
                'created_on' => time(),
            );

            $page_id = $this->page_model->add_page($sql_data);

            if ($page_id > 0)
            {
                flash_message('success', 'Sayfa başarıyla eklendi.');
            }
            else
            {
                flash_message('error', 'Sayfa eklenirken bir hata meydana geldi');
            }

            if (get_option('bitly_login') && get_option('bitly_apikey'))
            {
                $sql_data = array('short_url' => get_bitly_url(site_url('s/' . $slug)));
                $this->page_model->update_page($page_id, $sql_data);
            }
            redirect('admin/page/index');
        }

        $this->template->view('admin/add_page', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Mevcut sayfayı düzenler.
     *
     * @return void
     */
    public function edit_page()
    {
        $page_id = $this->uri->rsegment(3);
        $data = $this->page_model->get_page_by_id($page_id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir sayfa bulunmuyor');
        }

        $this->load->library(array('form_validation'));


        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Sayfa Başlığı', 'trim|required');
        $this->form_validation->set_rules('slug', 'Sayfa URL\'si', 'trim|required');
        $this->form_validation->set_rules('content', 'Sayfa İçeriği', 'required');
        if ($this->form_validation->run())
        {
            $slug = ($data['slug'] != $this->input->post('slug')) ? $this->_get_slug($this->form_validation->set_value('slug')) : $data['slug'];
            $sql_data = array(
                'slug' => $slug,
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
                'comments_enabled' => $this->input->post('comments_enabled'),
                'updated_on' => time(),
            );


            if ($this->page_model->update_page($page_id, $sql_data))
            {
                if ($data['slug'] != $slug)
                {
                    $this->load->library('redirect');
                    $this->redirect->set('page/' . $data['slug'], 'page/' . $slug);
                }
                flash_message('success', 'Sayfa başarıyla düzenlendi.');
                $data = array_merge($data, $this->page_model->get_page_by_id($page_id));
            }
            else
            {
                flash_message('error', 'Sayfa düzenlenirken hata meydana geldi.');
            }
            redirect('admin/page/index');
        }

        $this->template->view('admin/edit_page', $data);
        $this->template->load('admin_layout');
    }

    /**
     * İlgili sayfayı siler.
     *
     * @return void
     */
    public function delete_page()
    {
        $page_id = $this->uri->rsegment(3);
        $page = $this->page_model->get_page_by_id($page_id);

        if (sizeof($page) < 1)
        {
            flash_message('error', 'Böyle bir sayfa bulunmuyor.');
        }
        else if ($this->page_model->delete_page($page_id))
        {
            flash_message('success', 'Sayfa silindi.');
        }
        else
        {
            flash_message('error', 'Sayfa <b>silinemedi.</b>');
        }
        redirect('admin/page/index');
    }

    /**
     * Verilen başlığa uygun olarak slug oluşturur ve geri döndürür.
     * Eğer veritabanında mevcut ise sonuna - ve rakam ekler.
     *
     * @param string $title
     * @return string
     */
    private function _get_slug($title)
    {
        $first = url_title($title);
        $i = 1;
        $slug_control = 1;

        while ($slug_control == 1)
        {
            $slug = ($i < 2) ? $first : $first . '-' . $i;
            $slug_control = $this->page_model->is_slug_available($slug);
            ++$i;
        }
        return $slug;
    }

}

/* End of file admin.php */