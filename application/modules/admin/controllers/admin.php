<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Admin extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->language('auth');
    }

    function index()
    {
        if (is_admin ())
        {
            $redirect = ($this->session->flashdata('redir')) ? $this->session->flashdata('redir') : 'admin/dashboard';
            redirect($redirect);
        }
        else
        {
            $data['login_by_username'] = ($this->config->item('login_by_username') AND
                    $this->config->item('use_username'));
            $data['login_by_email'] = $this->config->item('login_by_email');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('login', 'Kullanıcı Adı', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', 'Beni Hatırla', 'integer');

            if ($this->config->item('login_count_attempts') && ($login = $this->input->post('login')))
            {
                $login = $this->security->xss_clean($login);
            }
            else
            {
                $login = '';
            }

            if ($this->auth->is_max_login_attempts_exceeded($login))
            {
                $this->form_validation->set_rules('captcha', 'Doğrulama Kodu', 'trim|xss_clean|required|callback__check_captcha');
            }
            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                if ($this->auth->login(
                                $this->form_validation->set_value('login'),
                                $this->form_validation->set_value('password'),
                                $this->form_validation->set_value('remember'),
                                $data['login_by_username'],
                                $data['login_by_email']))
                {
                    $redirect = ($this->session->flashdata('redir')) ? $this->session->flashdata('redir') : 'admin/dashboard';
                    $this->template->redir('Başarıyla giriş yaptınız.', $redirect);
                    //return;
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }

            if ($this->session->flashdata('redir'))
                $this->session->keep_flashdata('redir');

            $data['show_captcha'] = FALSE;
            if ($this->auth->is_max_login_attempts_exceeded($login))
            {
                $data['show_captcha'] = TRUE;
                $data['captcha_html'] = $this->_create_captcha();
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

    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return    string
     */
    function _create_captcha()
    {
        $this->load->library('captcha');
        $cap = $this->captcha->create_captcha();
        // Save captcha params in session
        $this->session->set_flashdata(array(
            'captcha_word' => $cap['word'],
            'captcha_time' => $cap['time'],
        ));
        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param    string
     * @return    bool
     */
    function _check_captcha($code)
    {
        $this->config->load('captcha', TRUE);
        $time = $this->session->flashdata('captcha_time');
        $word = $this->session->flashdata('captcha_word');

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float) $usec + (float) $sec);

        if ($now - $time > $this->config->item('expiration', 'captcha'))
        {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
            return FALSE;
        }
        elseif (($this->config->item('case_sensitive') AND
                $code != $word) OR
                strtolower($code) != strtolower($word))
        {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
            return FALSE;
        }
        return TRUE;
    }

}

/* End of file admin.php */
/* Location: ./application/controllers/admin/admin.php */