<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('pagination');
        $cursor = $this->mongo_db->page->find()->sort(array('created_at' => -1))->limit(get_option('per_page_admin'))->skip((get_option('per_page_admin') * ($this->uri->rsegment(3, 1) - 1)));
        $total_documents = $cursor->count();
        $data['pages'] = $cursor;
        $data['pagination'] = $this->pagination->init('admin/page/index', $total_documents, 3, get_option('per_page_admin'));
        $this->template->view('admin/index', $data)->render();
    }

    public function add($id = NULL)
    {

        $data = array();
        if ($id !== NULL)
        {
            $data = $this->mongo_db->page->findOne(array('_id' => new MongoID($id)));
            if (!count($data))
            {
                show_404();
            }
        }
        $this->load->library(array('form_validation'));

        $this->form_validation->set_rules('title', 'Sayfa Başlığı', 'trim|required');
        $this->form_validation->set_rules('content', 'Sayfa İçeriği', 'required');
        if ($this->form_validation->run())
        {

            $sql_data = array(
                'slug' => url_title($this->form_validation->set_value('title')),
                'author' => $this->session->userdata('user_id'),
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keyword' => $this->input->post('meta_keyword'),
                'created_at' => isset($data['created_at']) ? $data['created_at'] : new MongoDate(),
                'updated_at' => new MongoDate(),
            );
            if ($id !== NULL)
            {
                $sql_data['_id'] = new MongoID($id);
            }
            $sql_data = array_merge($data, $sql_data);
            $this->mongo_db->page->ensureIndex('slug');
            $this->mongo_db->page->save($sql_data);
            if (isset($data['slug']) && $data['slug'] != $sql_data['slug'])
            {
                $redirect = array('old_slug' => $data['slug'], 'new_slug' => $sql_data['slug'], 'module' => 'page');
                $this->mongo_db->redirect->save($redirect);
            }
            if ($id === NULL)
            {
                flash_message('success', 'Sayfa başarıyla eklendi.');
            }
            else
            {
                flash_message('success', 'Sayfa başarıyla düzenlendi.');
            }
            redirect('admin/page/index');
        }
        $this->template->view('admin/add', $data)->render();
    }

    public function edit($id)
    {
        $this->add($id);
    }

    function delete($id)
    {
        $page = $this->mongo_db->page->findOne(array('_id' => new MongoID($id)));

        if (count($page) < 1)
        {
            flash_message('error', 'Üzgünüm, böyle bir sayfa yok.');
        }
        else if ($this->mongo_db->page->remove(array('_id' => new MongoID($id))))
        {
            flash_message('success', 'Sayfa silindi');
        }
        else
        {
            flash_message('error', 'Sayfa <b>silinemedi.</b>');
        }
        redirect('admin/page/index');
    }

}
/* End of file admin.php */