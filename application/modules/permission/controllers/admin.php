<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Users $users
 *
 */
class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->library('pagination');
        $total = $this->db->where('user_group', $this->config->item('admin_group'))->count_all_results('users');
        $data['pagination'] = $this->pagination->init('admin/user/list_admin', $total, 3, get_option('per_page_admin'));
        $data['users'] = $this->users->get_admins(get_option('per_page_admin'), $this->pagination->get_offset());

        $this->template->view('admin/user_list', $data);
        $this->template->load('admin_layout');
    }

    public function edit()
    {
        $user_id = $this->uri->segment(4);
        $user = $this->users->get_user_by_id($user_id);
        if ($user === FALSE)
            redirect('admin/user');

        $this->form_validation->set_rules('module', 'Module', 'required');
        if ($this->form_validation->run())
        {
            /*if ($user_id == 1)
            {
                $this->template->redir('Ana yönetici izinleri değiştirilemez.', $this->uri->uri_string());
                return;
            }*/
            if ($this->permissions_model->update($user_id, serialize($this->input->post('module'))))
            {
                $message = 'Değişiklik başarıyla gerçekleştirildi.';
            }
            else
            {
                $message = 'Değişiklik yapılamadı.';
            }
            $this->template->redir($message, $this->uri->uri_string());
            return;
        }

        $data['user'] = $user;

        $this->template->view('admin/permission', $data);
        $this->template->load('admin_layout');
    }

}
