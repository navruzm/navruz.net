<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('user/auth');
        $this->load->helper(array('form', 'url'));
    }

    function index()
    {
        $data = array();
        if ($this->auth->is_user())
        {
            $redirect = ($this->session->flashdata('redir')) ? $this->session->flashdata('redir')
                        : 'admin/dashboard';
            redirect($redirect);
        }
        else
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'lang:auth_password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'lang:auth_email', 'trim|required|xss_clean|valid_email');
            if ($this->auth->is_max_login_attempts_exceeded())
            {
                $this->load->library('recaptcha');
                $this->form_validation->set_rules('recaptcha_response_field', 'lang:auth_captcha', 'trim|required|xss_clean|callback__check_captcha');
                $data['recaptcha'] = $this->recaptcha->get_html();
            }
            if ($this->form_validation->run())
            {
                if ($this->auth->login($this->form_validation->set_value('email'), $this->form_validation->set_value('password'), $this->input->post('remember')))
                {
                    redirect('user');
                }
                else
                {
                    $data['error'] = $this->auth->get_error_messages();
                }
            }
            $this->load->view('login', $data);
        }
    }

    function logout()
    {
        if ($this->auth->is_logged_in())
        {
            $this->auth->logout();
            $message = $this->lang->line('auth_message_logged_out');
        }
        else
        {
            $message = $this->lang->line('auth_message_already_logged_out');
        }
        $this->template->redir($message, 'admin');
    }

}

/* End of file admin.php */
