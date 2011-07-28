<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Category_model $category_model
 *
 */
class Admin extends Admin_Controller
{

    /**
     * Yönetici kontrolünü yapar ve gerekli kütüphane ve modelleri yükler.
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Admin Controller Initialized');
        $this->load->model('category/category_model');
        $this->load->library(array('form_validation', 'pagination'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    /**
     * Controller index metodu. Doğrudan category listesine yönlendirir.
     * @return void
     */
    function index()
    {
        redirect('admin/category/category_list');
    }

    /**
     * category listesini gösterir.
     * @return void
     */
    function category_list()
    {
        $data['categories'] = $this->category_model->get_categories();
        $this->template->view('admin/category_list', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Yeni category ekler.
     * @return
     */
    function add_category()
    {
        $data = array();
        $this->form_validation->set_rules('category_title', 'Kategori Adı', 'trim|required');
        $this->form_validation->set_rules('category_description', 'Kategori Açıklaması', 'required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'category_slug' => $this->_get_slug($this->form_validation->set_value('category_title')),
                'category_title' => $this->form_validation->set_value('category_title'),
                'category_description' => $this->form_validation->set_value('category_description'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
            );
            $this->category_model->add_category($sql_data);
            flash_message('success', 'KAtegori başarıyla eklendi.');
            redirect('admin/category/category_list');
        }

        $this->template->view('admin/add_category', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Mevcut categoryyi düzenler.
     * @return
     */
    function update_category()
    {
        $category_id = $this->uri->rsegment(3);
        $data = $this->category_model->get_category($category_id);

        if (sizeof($data) < 1)
        {
            flash_message('error', 'Böyle bir Kategori bulunmuyor.');
            redirect('admin/category/category_list');
        }
        $this->form_validation->set_rules('category_title', 'Kategori Adı', 'trim|required');
        $this->form_validation->set_rules('category_description', 'Kategori Açıklaması', 'required');
        if ($this->form_validation->run())
        {
            $slug = ($data['category_title'] != $this->input->post('category_title')) ? $this->_get_slug($this->form_validation->set_value('category_title')) : $data['category_slug'];
            $sql_data = array(
                'category_id' => $category_id,
                'category_slug' => $slug,
                'category_title' => $this->form_validation->set_value('category_title'),
                'category_description' => $this->form_validation->set_value('category_description'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
            );
            $this->category_model->update_category($sql_data);
            if ($data['slug'] != $slug)
            {
                $this->load->library('redirect');
                $this->redirect->set('category/' . $data['slug'], 'category/' . $slug);
            }
            flash_message('success', 'Kategori güncellendi.');
            redirect('admin/category/category_list');
        }

        $this->template->view('admin/edit_category', $data);
        $this->template->load('admin_layout');
    }

    /**
     * category silmeye yarar.
     * @return void
     */
    function delete_category()
    {
        $category_id = $this->uri->rsegment(3);
        $category = $this->category_model->get_category($category_id);

        if (sizeof($category) < 1)
            flash_message('error', 'Üzgünüm, böyle bir Kategori yok.');
        else if ($this->category_model->delete_category($category_id))
            flash_message('success', 'Kategori silindi');
        else
            flash_message('error', 'Kategori <b>silinemedi.</b>');
        redirect('admin/category/category_list');
    }

    /**
     * categoryleri sıralar.
     * @return void
     */
    function sort_category()
    {
        $data['categories'] = $this->category_model->get_categories();
        $this->template->view('admin/sort_category', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Sıralanan categoryleri kaydeder.
     * @return void
     */
    function sort_category_save()
    {
        $sort = explode('-', $this->uri->rsegment(3));

        foreach ($sort as $position => $category_id)
        {
            $this->category_model->sort_category($category_id, $position);
        }
        //@todo ajax message
        $this->output->enable_profiler(FALSE);
        $data['message'] = '<span class="success" style="display:block">Sıralama kaydedildi.</span>';
        $this->load->view('system/message', $data);
    }

    /**
     * Verilen başlığa uygun olarak slug oluşturur ve geri döndürür.
     * Eğer veritabanında mevcut ise sonuna - ve rakam ekler.
     *
     * @param string $title
     * @return string
     */
    private function _get_slug($category_title)
    {
        $first = url_title($category_title);
        $i = 1;
        $slug_control = 1;

        while ($slug_control == 1)
        {
            $slug = ($i < 2) ? $first : $first . '-' . $i;
            $slug_control = $this->category_model->is_slug_available($slug);
            ++$i;
        }
        return $slug;
    }

}

/* End of file admin.php */