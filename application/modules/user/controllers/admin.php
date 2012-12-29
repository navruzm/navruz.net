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
        $cursor = $this->mongo_db->user->find()->sort(array('created_at' => -1))->limit(get_option('per_page_admin'))->skip((get_option('per_page_admin') * ($this->uri->rsegment(3, 1) - 1)));
        $total_documents = $cursor->count();
        $data['users'] = $cursor;
        $data['pagination'] = $this->pagination->init('admin/user/index', $total_documents, 3, get_option('per_page_admin'));
        $this->template->view('admin/index', $data)->render();
    }

    public function add($id=NULL)
    {
        $this->load->library('form_validation');
        $data = array();
        if ($id !== NULL)
        {
            $data = $this->mongo_db->user->findOne(array('_id' => new MongoID($id)));
            if (!count($data))
            {
                show_404();
            }
            $this->form_validation->set_rules('confirm_password', 'Şifre tekrarı', 'trim|xss_clean|matches[password]');
        }
        else
        {
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Şifre tekrarı', 'trim|required|xss_clean|matches[password]');
        }
        $this->form_validation->set_rules('email', 'E-posta', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('name', 'İsim', 'trim|required|xss_clean');

        if ($this->form_validation->run())
        {
            if ($id === NULL)
            {
                $data['name'] = set_value('name');
                $data['permissions'] = set_value('permissions');

                if ($this->auth->create(
                                set_value('email'), set_value('password'), $data))
                {
                    flash_message('success', 'Üye başarıyla eklendi.');
                }
                else
                {
                    flash_message('error', $this->auth->get_error_messages());
                }
            }
            else
            {
                $data = array(
                    'name' => set_value('name'),
                    'email' => set_value('email'),
                    'password' => set_value('password'),
                    'permissions' => set_value('permissions'),
                );
                $this->auth->update($id, $data);
                flash_message('success', 'Üye başarıyla düzenlendi.');
            }
            redirect('admin/user/index');
        }
        $this->template->view('admin/add', $data)->render();
    }

    function edit($id)
    {
        $this->add($id);
    }

    public function delete()
    {
        
    }

}
/* End of file admin.php */