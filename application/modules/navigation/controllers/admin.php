<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property navigation_model $navigation_model
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
        $this->load->model('link/navigation_model');
        $this->load->helper('sitemap');
    }

    public function index()
    {
        $this->links();
    }

    public function links()
    {
        $id = $this->uri->rsegment(3);
        $data = $this->navigation_model->get_group($id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir grup bulunmuyor');
        }
        $data['links'] = $this->navigation_model->get_links($id);

        $this->template->view('admin/list', $data);
        $this->template->load('admin_layout');
    }

    public function add_link()
    {
        $id = $this->uri->rsegment(3);
        $data = $this->navigation_model->get_group($id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir grup bulunmuyor');
        }
        $this->load->library(array('form_validation'));

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Bağlantı Adı', 'trim|required');
        $this->form_validation->set_rules('link', 'Bağlantı', 'required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'title' => $this->form_validation->set_value('title'),
                'link' => $this->form_validation->set_value('link'),
                'access_level' => $this->input->post('access_level'),
                'target' => $this->input->post('target'),
                'group' => $id,
            );

            $link_id = $this->navigation_model->add_link($sql_data);

            if ($link_id > 0)
            {
                $message = 'Bağlantı başarıyla veritabanına eklendi.';
            }
            else
            {
                $message = 'Bağlantı eklemede hata meydana geldi.';
            }

            $this->template->redir($message, 'admin/navigation/links/' . $id);
        }

        $this->template->view('admin/add', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Mevcut yazıyı düzenler.
     *
     * @return void
     */
    public function edit_link()
    {
        $link_id = $this->uri->rsegment(3);
        $data = $this->navigation_model->get_link($link_id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir bağlantı bulunmuyor');
        }

        $this->load->library(array('form_validation'));

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Bağlantı Adı', 'trim|required');
        $this->form_validation->set_rules('link', 'Bağlantı', 'required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'title' => $this->form_validation->set_value('title'),
                'link' => $this->form_validation->set_value('link'),
                'access_level' => $this->input->post('access_level'),
                'target' => $this->input->post('target'),
            );

            if ($this->navigation_model->update_link($link_id, $sql_data))
            {
                $message = 'Bağlantı başarıyla düzenlendi.';
                $data = array_merge($data, $this->navigation_model->get_link($link_id));
            }
            else
            {
                $message = 'Bağlantı düzenlenirken hata meydana geldi.';
            }
            $this->template->redir($message, 'admin/navigation/links/' . $data['group']);
        }

        $this->template->view('admin/edit', $data);
        $this->template->load('admin_layout');
    }

    /**
     * İlgili yazıyı siler.
     *
     * @return void
     */
    public function delete_link()
    {
        $id = $this->uri->rsegment(3);
        $link = $this->navigation_model->get_link($id);

        if (sizeof($link) < 1)
        {
            $message = 'Böyle bir bağlantı bulunmuyor.';
        }
        else if ($this->navigation_model->delete_link($id))
        {
            $message = 'Bağlantı silindi.';
        }
        else
        {
            $message = 'Bağlantı <b>silinemedi.</b>';
        }
        $this->template->redir($message, 'admin/navigation/links/' . $link['group']);
    }

    function sort_links_save()
    {
        $sort = explode('-', $this->uri->rsegment(3));

        foreach ($sort as $position => $link_id)
        {
            $this->navigation_model->sort_link($link_id, $position);
        }
        //@todo ajax message
        $this->output->enable_profiler(FALSE);
        $data['message'] = '<span class="success" style="display:block">Sıralama kaydedildi.</span>';
        $this->load->view('system/message', $data);
    }

    /*
     * Groups
     */

    public function groups()
    {
        $data['groups'] = $this->navigation_model->get_groups();

        $this->template->view('admin/list_group', $data);
        $this->template->load('admin_layout');
    }

    public function add_group()
    {

        $this->load->library(array('form_validation'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Grup Adı', 'trim|required');
        $this->form_validation->set_rules('tag', 'Grup Etiketi', 'trim|required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'title' => $this->form_validation->set_value('title'),
                'tag' => $this->form_validation->set_value('tag'),
            );

            $author_id = $this->navigation_model->add_group($sql_data);

            if ($author_id > 0)
            {
                flash_message('success', 'Grup başarıyla veritabanına eklendi.');
            }
            else
            {
                flash_message('error', 'Grup eklemede hata meydana geldi.');
            }
            redirect('admin/navigation/groups');
        }

        $this->template->view('admin/add_group');
        $this->template->load('admin_layout');
    }

    public function edit_group()
    {
        $id = $this->uri->rsegment(3);
        $data = $this->navigation_model->get_group($id);
        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir grup bulunmuyor');
        }

        $this->load->library(array('form_validation'));

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Grup Adı', 'trim|required');
        $this->form_validation->set_rules('tag', 'Grup Etiketi', 'trim|required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'title' => $this->form_validation->set_value('title'),
                'tag' => $this->form_validation->set_value('tag'),
            );

            if ($this->navigation_model->update_group($id, $sql_data))
            {
                flash_message('success', 'Grup başarıyla düzenlendi.');
            }
            else
            {
                flash_message('error', 'Grup düzenlenirken hata meydana geldi.');
            }
            redirect('admin/navigation/groups');
        }

        $this->template->view('admin/edit_group', $data);
        $this->template->load('admin_layout');
    }

    public function delete_group()
    {
        $id = $this->uri->rsegment(3);
        $group = $this->navigation_model->get_group($id);

        if (sizeof($group) < 1)
        {
            flash_message('error', 'Böyle bir grup bulunmuyor.');
        }
        else if ($this->navigation_model->delete_group($id))
        {
            flash_message('success', 'Grup silindi.');
        }
        else
        {
            flash_message('error', 'Grup <b>silinemedi.</b>');
        }
        redirect('admin/navigation/groups');
    }

}