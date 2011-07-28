<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Post_Model $post_model
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
        $this->load->model('post/post_model');
        $this->load->helper('sitemap');
    }

    /**
     * Yazıları listeler.
     *
     * @return void
     */
    public function index()
    {
        $this->load->library('pagination');
        $data['pagination'] = $this->pagination->init('admin/post/index', $this->db->count_all('posts'), 3, get_option('per_page_admin'));
        $data['post'] = $this->post_model->get_posts(
                        get_option('per_page_admin'),
                        $this->pagination->get_offset()
        );

        $this->template->view('admin/list_post', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Yeni yazı ekler.
     *
     * @return void
     */
    public function add_post()
    {
        $this->load->library(array('image_lib', 'upload', 'form_validation', 'tags'));
        $this->load->model('category/category_model');
        $data = array();
        $data['categories'] = $this->category_model->get_categories();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Yazı Başlığı', 'trim|required');
        $this->form_validation->set_rules('content', 'Yazı İçeriği', 'required');
        $this->form_validation->set_rules('summary', 'Yazı Özeti', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('category_id[]', 'Yazı categorysi', 'trim|required');
        if ($this->form_validation->run())
        {
            $slug = ($this->form_validation->set_value('slug') != '') ? $this->form_validation->set_value('slug') : $this->_get_slug($this->form_validation->set_value('title'));
            $categories = $this->input->post('category_id');
            $created_on = time();
            $sql_data = array(
                'author' => get_user_id(),
                'slug' => $slug,
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'summary' => $this->form_validation->set_value('summary'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
                'comments_enabled' => $this->input->post('comments_enabled'),
                'created_on' => $created_on,
            );

            $id = $this->post_model->add_post($sql_data, $categories);

            if ($id > 0)
            {
                flash_message('success', 'Yazı başarıyla eklendi.');
            }
            else
            {
                flash_message('error', 'Yazı eklemede hata meydana geldi.');
            }

            if ($id > 0)
            {
                //Trackback işlemleri
                $trackbacks = explode(',', $this->input->post('trackbacks'));
                if (count($trackbacks) > 0)
                {
                    $pinged = array();
                    foreach ($trackbacks as $trackback)
                    {
                        if (strpos($trackback, 'http://') === FALSE)
                            continue;
                        $tb_data = array(
                            'ping_url' => trim($trackback),
                            'url' => site_url($sql_data['slug']),
                            'title' => $sql_data['title'],
                            'excerpt' => $sql_data['content'],
                            'blog_name' => get_option('site_name'),
                            'charset' => 'utf-8'
                        );
                        $ping = $this->_trackback($tb_data);
                        if ($ping === TRUE)
                        {
                            $pinged[] = trim($trackback);
                        }
                        else
                        {
                            $data['message'] .= $ping;
                        }
                    }
                }
                $sql_data = array();
                if (count($pinged) > 0)
                {
                    $sql_data['pinged'] = implode(',', $pinged);
                }

                if (get_option('bitly_login') && get_option('bitly_apikey'))
                {
                    $sql_data['short_url'] = get_bitly_url(site_url($slug));
                }

                $image = $this->_upload_image($slug, $created_on);
                if ($image)
                {
                    $sql_data['image'] = $image;
                }
                if (count($sql_data) > 0)
                {
                    $this->post_model->update_post($id, $sql_data);
                }
                $this->tags->tag_object($id, $this->input->post('tags'), get_user_id());
            }
            generate_sitemap();
            redirect('admin/post/index');
        }

        $this->template->view('admin/add_post', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Mevcut yazıyı düzenler.
     *
     * @return void
     */
    public function edit_post()
    {
        $id = $this->uri->rsegment(3);
        $data = $this->post_model->get_post_by_id($id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir yazı bulunmuyor');
        }

        $this->load->library(array('image_lib', 'upload', 'form_validation', 'tags'));
        $this->load->model('category/category_model');

        $data['categories'] = $this->category_model->get_categories();
        $data['post_categories'] = $this->post_model->get_post_categories($id);
        $data['tags'] = $this->tags->get_tags_on_object($id);

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Yazı Başlığı', 'trim|required');
        $this->form_validation->set_rules('slug', 'Yazı URL\'si', 'trim|required');
        $this->form_validation->set_rules('content', 'Yazı İçeriği', 'required');
        $this->form_validation->set_rules('summary', 'Yazı Özeti', 'trim|max_length[255]');
        $this->form_validation->set_rules('category_id[]', 'Yazı categorysi', 'trim|required');
        if ($this->form_validation->run())
        {
            $data['message'] = '';
            $slug = ($data['slug'] != $this->input->post('slug')) ? $this->_get_slug($this->form_validation->set_value('slug')) : $data['slug'];
            $sql_data = array(
                'slug' => $slug,
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'summary' => $this->form_validation->set_value('summary'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keywords' => $this->input->post('meta_keywords'),
                'comments_enabled' => $this->input->post('comments_enabled'),
                'updated_on' => time(),
            );
            $image = $this->_upload_image($slug, $data['created_on']);
            if ($image)
            {
                $sql_data['image'] = $image;
                if ($slug != $data['slug'])
                {
                    @unlink(config_item('post_upload_path') . $data['image']);
                    @unlink(config_item('post_upload_path') . 'thumbs/' . $data['image']);
                }
            }
            //Trackback işlemleri
            $old_pinged = explode(',', $data['pinged']);
            $trackbacks = explode(',', $this->input->post('trackbacks'));
            if (sizeof($trackbacks) > 0)
            {
                $pinged = array();
                foreach ($trackbacks as $trackback)
                {
                    $trackback = trim($trackback);
                    if (in_array($trackback, $old_pinged) OR strpos($trackback, 'http://') === FALSE)
                        continue;
                    $tb_data = array(
                        'ping_url' => $trackback,
                        'url' => site_url($sql_data['slug']),
                        'title' => $sql_data['title'],
                        'excerpt' => $sql_data['content'],
                        'blog_name' => get_option('site_name'),
                        'charset' => 'utf-8'
                    );
                    $ping = $this->_trackback($tb_data);
                    if ($ping === TRUE)
                    {
                        $pinged[] = $trackback;
                    }
                    else
                    {
                        $data['message'] .= $ping;
                    }
                }
            }
            if (sizeof($pinged) > 0)
            {
                $sql_data['pinged'] = implode(',', array_merge($old_pinged, $pinged));
            }

            if ($this->post_model->update_post($id, $sql_data, $this->input->post('category_id')))
            {
                if ($data['slug'] != $slug)
                {
                    $this->load->library('redirect');
                    $this->redirect->set($data['slug'], $slug);
                }
                flash_message('success', 'Yazı başarıyla düzenlendi.');
                $this->tags->delete_all_object_tags($id);
                $this->tags->tag_object($id, $this->input->post('tags'), get_user_id());
                $data = array_merge($data, $this->post_model->get_post_by_id($id));
                $data['tags'] = $this->tags->get_tags_on_object($id);
                $data['post_categories'] = $this->post_model->get_post_categories($id);
            }
            else
            {
                flash_message('error', 'Yazı düzenlenirken hata meydana geldi.');
            }
            redirect('admin/post/index');
        }

        $this->template->view('admin/edit_post', $data);
        $this->template->load('admin_layout');
    }

    /**
     * İlgili yazıyı siler.
     *
     * @return void
     */
    public function delete_post()
    {
        $id = $this->uri->rsegment(3);
        $post = $this->post_model->get_post_by_id($id);

        if (sizeof($post) < 1)
        {
            flash_message('error', 'Böyle bir Yazı bulunmuyor.');
        }
        else if ($this->post_model->delete_post($id))
        {
            $this->load->library('redirect');
            $this->redirect->delete_new($post['slug']);
            flash_message('success', 'Yazı silindi.');
            if ($post['image'] != '')
            {
                if ((@unlink(config_item('post_upload_path') . $post['image'])) &&
                        (@unlink(config_item('post_upload_path') . 'thumbs/' . $post['image'])))
                    flash_message('success', 'Yazının resim dosyaları silindi.');
                //@todo important! Galeri ve resimleride silinecek.
            }
            $this->load->library('tags');
            $this->tags->delete_all_object_tags($id);
        }
        else
        {
            flash_message('error', 'Yazı <b>silinemedi.</b>');
        }
        redirect('admin/post/index');
    }

    public function sitemap()
    {
        $this->load->library('ping');

        generate_sitemap();
        $this->ping->send();
        flash_message('success', 'Site haritası güncellendi ve arama motorlarına ping atıldı.');
        redirect('admin/post/index');
    }

    public function get_slug()
    {
        $this->output->enable_profiler(FALSE);
        $this->output->set_output($this->_get_slug($this->input->post('title')));
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
            $slug_control = $this->post_model->is_slug_available($slug);
            ++$i;
        }
        return $slug;
    }

    /**
     * Geri izleme işlemini yapan fonksiyon.
     * @param array $tb_data
     * @return boolean|string
     */
    public function _trackback($tb_data)
    {
        $this->load->library('trackback');
        if (!$this->trackback->send($tb_data))
        {
            return $this->trackback->display_errors('<div class="error">', '</div>');
        }
        else
        {
            return TRUE;
        }
    }

    private function _upload_image($slug, $date)
    {
        if ($_FILES['image']['name'] != '')
        {
            if (file_exists(config_item('post_upload_path') . date('Y', $date)) === FALSE)
            {
                @mkdir(config_item('post_upload_path') . date('Y', $date));
            }
            if (file_exists(config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date)) === FALSE)
            {
                @mkdir(config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date));
                log_message('error', config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date));
            }
            /*if (file_exists(config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date) . '/thumbs') === FALSE)
            {
                @mkdir(config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date) . '/thumbs');
            }*/
            $dir = config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date) . '/';

            $config['upload_path'] = $dir;
            $config['allowed_types'] = 'jpg';
            $config['overwrite'] = TRUE;
            $config['file_name'] = $slug . '.jpg';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image'))
            {
                log_message('error', $this->upload->display_errors());
                return;
            }
            else
            {
                $upload_data = $this->upload->data();
                $config['source_image'] = $upload_data['full_path'];
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = '';
                $config['width'] = 160;
                $config['height'] = 120;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                // for thumb
                //@todo çözünürlüğe göre kırp
                /*$config['new_image'] = $dir . 'thumbs/' . $upload_data['file_name'];
                $config['width'] = 160;
                $config['height'] = 120;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();*/

                return $upload_data['file_name'];
            }
        }
        return;
    }

}