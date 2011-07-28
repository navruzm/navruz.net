<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Facebook extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'session'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->load->language('user/auth');
        $this->load->config('auth');
        keep_all_flashdata();
    }

    function index()
    {
        
    }

    function redirect()
    {
        $this->template->redir('Giriş yapmak için Facebook sitesine yönlendiriliyorsunuz.', site_url() . 'extra.php/connect/facebook/connect');
    }

    function connected()
    {
        if ($this->auth->is_logged_in())
            redirect('user');
        $connected_user = $this->session->userdata('user');
        if (isset($connected_user['email']))
        {
            if ($this->users->is_email_exists($connected_user['email']))
            {
                if ($this->auth->login_facebook($connected_user['email']))
                {
                    $redirect = ($this->session->flashdata('redir')) ? $this->session->flashdata('redir') : '';
                    $this->template->redir('Başarıyla giriş yaptınız.', $redirect);
                }
                else
                {
                    $message = message_box('(1)İşlem yapılırken bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyiniz.', 'error');
                    $this->template->view('system/message', array('message' => $message))
                            ->load();
                }
            }
            else
            {
                $this->template->redir('Kayıt işlemi için yönlendiriliyorsunuz.', 'user/facebook/register');
            }
        }
        else
        {
            $message = message_box('(2)İşlem yapılırken bir hata ile karşılaşıldı. Lütfen daha sonra tekrar deneyiniz.', 'error');
            $this->template->view('system/message', array('message' => $message))
                    ->load();
        }
    }

    function register()
    {
        $connected_user = $this->session->userdata('user');
        if (!isset($connected_user['email']))
        {
            $this->template->redir('Bir hata ile karşılaşıldı', 'user');
        }
        elseif ($this->users->is_email_exists($connected_user['email']))
        {

            $this->template->redir('Bu e-posta adresi zaten kayıtlı. Lütfen giriş yapın.', config_item('auth_uri_login'));
        }
        elseif ($this->auth->is_logged_in())
        {
            // logged in
            $this->template->redir($this->lang->line('auth_message_already_logged_in'), 'user');
        }
        elseif ($this->auth->is_logged_in(FALSE))
        {
            // logged in, not activated
            redirect(config_item('auth_uri_send_again'));
        }
        elseif (!$this->config->item('allow_registration'))
        {
            // registration is off
            $this->_show_message($this->lang->line('auth_message_registration_disabled'));
            return;
        }
        else
        {
            $use_username = $this->config->item('use_username');
            if ($use_username)
            {
                $this->form_validation->set_message('alpha_dash', 'Kullanıcı Adı alanına sadece alfa-nümerik karakterler, altçizgi ve kesikli çizgi girilmelidir. Türkçe (ö,ç,ş gibi) ve özel (?,!,= gibi) karakterler kullanamazsınız.');
                $this->form_validation->set_rules('username', 'Kullanıcı Adı', 'trim|required|xss_clean|min_length[' . $this->config->item('username_min_length') . ']|max_length[' . $this->config->item('username_max_length') . ']|alpha_dash|strtolower');
            }
            $this->form_validation->set_rules('confirm_password', 'Şifreyi Doğrula', 'trim|xss_clean|matches[password]');
            $this->form_validation->set_rules('place_id', 'Kasaba ve Köyünüz', 'required|trim|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                $password = $this->input->post('password', TRUE);
                if ($password == '')
                {
                    $password = random_string('alnum', 10);
                }
                $profile_data = array();
                $profile_data['place_id'] = $this->form_validation->set_value('place_id');
                $profile_data['first_name'] = ucfirst($connected_user['first_name']);
                $profile_data['last_name'] = ucfirst($connected_user['last_name']);
                $profile_data['facebook_id'] = $connected_user['facebook_id'];
                $birthday = explode('/', $connected_user['birthday']);
                $profile_data['birthday'] = mktime(0, 0, 0, $birthday[1], $birthday[0], $birthday[2]);

                if (isset($connected_user['location']->name))
                {
                    $profile_data['location'] = $connected_user['location']->name;
                }

                if ($connected_user['gender'] == 'erkek' OR $connected_user['gender'] == 'male')
                {
                    $profile_data['gender'] = 'm';
                }
                elseif ($connected_user['gender'] == 'bayan' OR $connected_user['gender'] == 'female')
                {
                    $profile_data['gender'] = 'f';
                }

                if (!is_null($data = $this->auth->create_user(
                                        $use_username ? $this->form_validation->set_value('username') : '',
                                        $connected_user['email'],
                                        $password,
                                        FALSE,
                                        $profile_data)))
                {
                    // success
                    $this->_save_picture($connected_user['picture'], $this->form_validation->set_value('username'));
                    $data['site_name'] = get_option('site_name');
                    $data['site_email'] = get_option('site_email');


                    if ($this->config->item('email_account_details'))
                    {
                        // send "welcome" email
                        $this->_send_email('welcome', $data['email'], $data);
                    }
                    unset($data['password']);
                    // Clear password (just for any case)

                    redirect(site_url() . 'extra.php/facebook/connect');
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }

            $data['use_username'] = $use_username;
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Kayıt Ol');
            $this->template->set_title('Sitemize Facebook ile Kayıt Olun');
            $this->template->view('facebook_register', $data);
            $this->template->load();
        }
    }

    private function _save_picture($picture, $username)
    {
        if (strpos($picture, 'UlIqmHJn-SK.gif') OR $username == '')
            return;
        $this->load->helper('file');
        $img = file_get_contents($picture);
        write_file(config_item('avatar_upload_path') . $username . '.jpg', $img);
    }

    private function _send_email($type, $email, $data)
    {
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->from(get_option('site_email'), get_option('site_name'));
        //$this->email->reply_to(get_setting('site_email'), get_setting('site_name'));
        $this->email->to($email);
        $this->email->subject(sprintf($this->lang->line('auth_subject_' . $type), $data['site_name']));
        $this->email->message($this->load->view('email/' . $type . '-html', $data, TRUE));
        $this->email->set_alt_message($this->load->view('email/' . $type . '-txt', $data, TRUE));
        $this->email->send();
    }

}