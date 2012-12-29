<?php

class User extends MY_Controller
{

    private $redirect = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->redirect = $this->session->flashdata('redirect');
        $this->session->keep_flashdata('redirect');
    }

    public function index()
    {
        $this->template->view('index')->render();
    }

    public function register()
    {
        $data = array();
        if ($this->auth->is_user())
        {
            redirect($this->redirect === FALSE ? 'user' : $this->redirect);
        }
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'lang:auth_password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'lang:auth_retype_password', 'trim|required|xss_clean|matches[password]');
        $this->form_validation->set_rules('email', 'lang:auth_email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('name', 'lang:auth_name', 'trim|required|xss_clean');
        if ($this->form_validation->run())
        {
            $data['name'] = set_value('name');
            if ($this->auth->create(set_value('email'), set_value('password'), $data))
            {
                redirect($this->redirect === FALSE ? 'user' : $this->redirect);
            }
            else
            {
                $data['error'] = $this->auth->get_error_messages();
            }
        }
        $this->template->view('register', $data)->render();
    }

    public function edit()
    {
        $data = array();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm_password', 'lang:auth_retype_password', 'trim|xss_clean|matches[password]');
        $this->form_validation->set_rules('email', 'lang:auth_email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('name', 'lang:auth_name', 'trim|required|xss_clean');
        if ($this->form_validation->run())
        {
            
        }
        $this->template->view('edit', $data)->render();
    }

    public function login()
    {
        $data = array();
        if ($this->auth->is_user())
        {
            redirect($this->redirect === FALSE ? 'user' : $this->redirect);
        }
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
            if ($this->auth->login(set_value('email'), set_value('password'), $this->input->post('remember')))
            {
                redirect($this->redirect === FALSE ? 'user' : $this->redirect);
            }
            else
            {
                $data['error'] = $this->auth->get_error_messages();
            }
        }

        $this->template->view('login', $data)->render();
    }

    public function forgot_password()
    {
        $data = array();
        if ($this->auth->is_user())
        {
            redirect($this->redirect === FALSE ? 'user' : $this->redirect);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'lang:auth_email', 'trim|required|xss_clean|valid_email');
        if ($this->form_validation->run())
        {
            if ($this->auth->forgot_password(set_value('email')))
            {
                redirect($this->redirect === FALSE ? 'user' : $this->redirect);
            }
            else
            {
                $data['error'] = $this->auth->get_error_messages();
            }
        }
        $this->template->view('forgot_password', $data)->render();
    }

    public function reset_password($id, $key)
    {
        $data = array();
        if ($this->auth->is_user())
        {
            redirect($this->redirect === FALSE ? 'user' : $this->redirect);
        }

        if ($this->auth->can_reset_password($id, $key))
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('password', 'lang:auth_password', 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('confirm_password', 'lang:auth_retype_password', 'trim|required|xss_clean|matches[password]');
            if ($this->form_validation->run())
            {
                if ($this->auth->reset_password(set_value('password'), $id, $key))
                {
                    redirect($this->redirect === FALSE ? 'user' : $this->redirect);
                }
                else
                {
                    $data['error'] = $this->auth->get_error_messages();
                }
            }
            $this->template->view('reset_password', $data)->render();
        }
    }

    public function logout()
    {
        $this->auth->logout();
        redirect($this->redirect === FALSE ? 'user' : $this->redirect);
    }

    public function activate($id, $key)
    {
        $data = array();
        if ($this->auth->is_user())
        {
            redirect($this->redirect === FALSE ? 'user' : $this->redirect);
        }
        $data['message'] = $this->auth->activate($id, $key);
        $this->template->view('activate', $data)->render();
    }

    public function _check_captcha($val)
    {
        if ($this->recaptcha->check_answer($this->input->ip_address(), $this->input->post('recaptcha_challenge_field'), $val))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('recaptcha_incorrect_response'));
            return FALSE;
        }
    }

}
/* End of file user.php */