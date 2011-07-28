<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->load->language('user/auth');
        $this->load->config('auth');
    }

    public function index()
    {
        if (!$this->auth->is_logged_in())
            redirect(config_item('auth_uri_login'));
        $this->template->set_title('Üye');
        $this->template->view('control_panel', '');
        $this->template->load();
    }

    public function terms()
    {
        $this->template->set_title('Üyelik Sözleşmesi');
        $this->template->add_keyword('Üyelik Sözleşmesi');
        $this->template->set_description('Üyelik Sözleşmesi',TRUE);
        $this->template->view('terms', '');
        $this->template->load();
    }

    public function view()
    {
        /**
         * @todo forum mesajlarına link
         * @todo cevap verdiği son konular
         * @todo açtığı son konular
         * @todo yorum sayısı
         */
        $username = $this->uri->rsegment(3);
        $user = $this->users->get_user_by_username($username, 1);

        if (!$user)
        {
            show_404(uri_string());
        }
        $this->load->model('forum/forum_model');
        $data['user'] = $user;
        $data['user_profile'] = $this->users->get_user_profile($user->id);
        $data['user_group'] = $this->users->get_group($user->user_group);
        $data['forum_topics'] = $this->forum_model->get_last_topics_by_user($user->id);
        $data['forum_posts'] = $this->forum_model->get_last_posts_by_user($user->id);
        $this->template->set_title($data['user_profile']['name'].' - '.$username,TRUE);
        $this->template->set_description('Üye - ' . $data['user_profile']['name'].' - '.$username,TRUE);
        $this->template->add_breadcrumb('Kullanıcılar', 'user');
        $this->template->add_breadcrumb($data['user_profile']['name'].' - '.$username);
        $this->template->view('user', $data);
        $this->template->load();
    }

    function avatar()
    {
        $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
        $this->template->add_breadcrumb('Profil Resmi');
        $this->template->view('avatar')->load();
    }

    function avatar_upload()
    {
        $username = $this->input->post('uname');
        $file_info = getimagesize($_FILES['image']['tmp_name']);
        if (!empty($file_info))
        {
            $_FILES['image']['type'] = $file_info['mime'];
        }
        $this->load->library('upload');
        $this->load->library('image_lib');
        $config['upload_path'] = config_item('temp_upload_path');
        $config['file_name'] = $username . '_' . time() . '.jpg';
        $config['overwrite'] = TRUE;
        $config['allowed_types'] = 'jpg';
        $this->upload->initialize($config);
        if ($this->upload->do_upload('image'))
        {
            $upload_data = $this->upload->data();
            $config['source_image'] = $upload_data['full_path'];
            $config['maintain_ratio'] = TRUE;
            $config['new_image'] = '';
            $config['width'] = 400;
            $config['height'] = 400;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
            $output = array('success' => 'Dosya Yüklendi.', 'image' => $upload_data['file_name']);
        }
        else
        {
            $output = array('error' => $this->upload->display_errors('', ''));
        }
        $data['message'] = json_encode($output);
        $this->load->view('system/message', $data);
    }

    function avatar_crop()
    {
        //if(!is_ajax()) exit('No direct script access allowed');
        $this->output->enable_profiler(FALSE);
        $this->form_validation->set_rules('filename', 'Dosya', 'trim|required');
        $this->form_validation->set_rules('width', 'Genişlik', 'trim|required');
        $this->form_validation->set_rules('height', 'Yükseklik', 'trim|required');
        $this->form_validation->set_rules('x_axis', 'X', 'trim|required');
        $this->form_validation->set_rules('y_axis', 'Y', 'trim|required');
        if ($this->form_validation->run())
        {
            $this->load->library('image_lib');
            $config['source_image'] = config_item('temp_upload_path') . set_value('filename');
            $config['maintain_ratio'] = FALSE;
            $config['new_image'] = config_item('avatar_upload_path') . get_username() . '.jpg';
            $config['width'] = set_value('width', 50);
            $config['height'] = set_value('height', 50);
            $config['x_axis'] = set_value('x_axis', 0);
            $config['y_axis'] = set_value('y_axis', 0);
            $this->image_lib->initialize($config);
            $this->image_lib->crop();
            $this->image_lib->clear();

            if (($config['width'] > 50) || ($config['height'] > 50))
            {
                $config['source_image'] = $config['new_image'];
                $config['new_image'] = '';
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 50;
                $config['height'] = 50;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            }

            $data['message'] = 'Kaydedildi.';
            $this->load->view('system/message', $data);
            return;
        }
        $data['message'] = 'Hata oluştu.';
        $this->load->view('system/message', $data);
    }

    /**
     * Change user profile
     *
     * @return void
     */
    public function change_profile()
    {
        if (!$this->auth->is_logged_in())
        {
            // not logged in or not activated
            redirect(config_item('auth_uri_login'));
        }
        else
        {
            $this->form_validation->set_rules('first_name', 'Adınız', 'trim|required|alpha|xss_clean|strtolower|ucwords');
            $this->form_validation->set_rules('last_name', 'Soyadınız', 'trim|required|alpha|xss_clean|strtolower|ucwords');
            $this->form_validation->set_rules('bio', 'Hakkınızda', 'trim|required|xss_clean|mx_lenght[255]');
            $this->form_validation->set_rules('gender', 'Cinsiyet', 'trim|required|xss_clean');
            $this->form_validation->set_rules('birthday', 'Doğum Tarihiniz', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                $darray = explode('-', $this->form_validation->set_value('birthday'));
                $date = mktime(0, 0, 0, $darray[1], $darray[0], $darray[2]);
                $sql_data = array(
                    'first_name' => $this->form_validation->set_value('first_name'),
                    'last_name' => $this->form_validation->set_value('last_name'),
                    'bio' => $this->form_validation->set_value('bio'),
                    'gender' => $this->form_validation->set_value('gender'),
                    'birthday' => $date,
                    'job' => $this->input->post('job'),
                    'location' => $this->input->post('location'),
                );

                // validation ok
                if ($this->auth->change_profile($sql_data))
                {
                    // success
                    $this->_show_message('Profil bilgileriniz başarıyla değiştirildi.');
                    return;
                }
                else
                {
                    // fail
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $data['profile'] = $this->users->get_user_profile(get_user_id());
            $this->template->set_title('Profil Bilgilerini Değiştirin');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Profil Bilgilerini Değiştirin');
            $this->template->view('change_profile', $data);
            $this->template->load();
        }
    }

    /**
     * Login user on the site
     *
     * @return void
     */
    public function login()
    {
        if ($this->auth->is_logged_in())
        {
            // logged in
            redirect('');
        }
        elseif ($this->auth->is_logged_in(FALSE))
        {
            // logged in, not activated
            redirect(config_item('auth_uri_send_again'));
        }
        else
        {
            $data['login_by_username'] = ($this->config->item('login_by_username') AND
                    $this->config->item('use_username'));
            $data['login_by_email'] = $this->config->item('login_by_email');

            $this->form_validation->set_rules('login', 'Kullanıcı Adı', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', 'Beni Hatırla', 'integer');

            // Get login for counting attempts to login
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
                {// success
                    $redirect = ($this->session->flashdata('redir')) ? $this->session->flashdata('redir') : '';
                    $this->template->redir('Başarıyla giriş yaptınız.', $redirect);
                    return;
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    if (isset($errors['banned']))
                    {// banned user
                        $this->_show_message($this->lang->line('auth_message_banned') . ' ' . $errors['banned']);
                        return;
                    }
                    elseif (isset($errors['not_activated']))
                    {// not activated user
                        redirect(config_item('auth_uri_send_again'));
                    }
                    else
                    {// fail
                        foreach ($errors as $k => $v)
                            $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                    }
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
            $this->template->set_title('Üye Girişi');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Giriş');
            $this->template->view('login_form', $data);
            $this->template->load();
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
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
        $this->template->redir($message, 'user');
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    public function register()
    {
        if ($this->auth->is_logged_in())
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
            $this->form_validation->set_rules('first_name', 'Adınız', 'trim|required|xss_clean|strtolower|ucfirst');
            $this->form_validation->set_rules('last_name', 'Soyadınız', 'trim|required|xss_clean|strtolower|ucfirst');
            $this->form_validation->set_rules('place_id', 'Kasaba veya Köyünüz', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', 'Şifreyi Doğrula', 'trim|required|xss_clean|matches[password]');
            $captcha_registration = $this->config->item('captcha_registration');
            if ($captcha_registration)
            {
                $this->form_validation->set_rules('captcha', 'Doğrulama Kodu', 'trim|xss_clean|required|callback__check_captcha');
            }
            $data['errors'] = array();

            $email_activation = $this->config->item('email_activation');

            if ($this->form_validation->run())
            {
                // validation ok
                $profile = array(
                    'first_name' => $this->form_validation->set_value('first_name'),
                    'last_name' => $this->form_validation->set_value('last_name'),
                    'place_id' => $this->form_validation->set_value('place_id'),
                );
                if (!is_null($data = $this->auth->create_user(
                                        $use_username ? $this->form_validation->set_value('username') : '',
                                        $this->form_validation->set_value('email'),
                                        $this->form_validation->set_value('password'),
                                        $email_activation,
                                        $profile)))
                {
                    // success
                    $data['site_name'] = get_option('site_name');
                    $data['site_email'] = get_option('site_email');

                    if ($email_activation)
                    {
                        // send "activate" email
                        $data['activation_period'] = $this->config->item('email_activation_expire') / 3600;

                        $this->_send_email('activate', $data['email'], $data);

                        unset($data['password']);
                        // Clear password (just for any case)

                        $this->_show_message($this->lang->line('auth_message_registration_completed_1'));
                        return;
                    }
                    else
                    {
                        if ($this->config->item('email_account_details'))
                        {
                            // send "welcome" email

                            $this->_send_email('welcome', $data['email'], $data);
                        }
                        unset($data['password']);
                        // Clear password (just for any case)
                        $this->_show_message($this->lang->line('auth_message_registration_completed_2') . ' ' . anchor(site_url(config_item('auth_uri_login')), 'Giriş'));
                        return;
                    }
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            if ($captcha_registration)
            {
                $data['captcha_html'] = $this->_create_captcha();
            }
            $data['use_username'] = $use_username;
            $data['captcha_registration'] = $captcha_registration;
            $this->template->set_title('Üye Kaydı');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Kayıt Ol');
            $this->template->view('register_form', $data);
            $this->template->load();
        }
    }

    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    public function send_again()
    {
        if (!$this->auth->is_logged_in(FALSE))
        {
            // not logged in or activated
            redirect(config_item('auth_uri_login'));
        }
        else
        {
            $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                // validation ok
                if (!is_null($data = $this->auth->change_email(
                                        $this->form_validation->set_value('email'))))
                {
                    // success
                    $data['site_name'] = get_option('site_name');
                    $data['activation_period'] = $this->config->item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);
                    $this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
                    return;
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $this->template->set_title('Üyelik Aktivasyonu');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Aktivasyon');
            $this->template->view('send_again_form', $data);
            $this->template->load();
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function activate()
    {
        $user_id = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);

        // Activate user
        if ($this->auth->activate_user($user_id, $new_email_key))
        {
            // success
            $this->auth->logout();
            $this->_show_message($this->lang->line('auth_message_activation_completed') . ' ' . anchor(site_url(config_item('auth_uri_login')), 'Giriş'), TRUE);
        }
        else
        {
            // fail
            $this->_show_message($this->lang->line('auth_message_activation_failed'), TRUE);
        }
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    public function forgot_password()
    {
        if ($this->auth->is_logged_in())
        {
            // logged in
            redirect('');
        }
        elseif ($this->auth->is_logged_in(FALSE))
        {
            // logged in, not activated
            redirect(config_item('auth_uri_send_again'));
        }
        else
        {
            $this->form_validation->set_rules('login', 'Kullanıcı adı veya e-posta', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                // validation ok
                if (!is_null($data = $this->auth->forgot_password(
                                        $this->form_validation->set_value('login'))))
                {
                    $data['site_name'] = get_option('site_name');
                    $data['site_email'] = get_option('site_email');

                    // Send email with password activation link
                    $this->_send_email('forgot_password', $data['email'], $data);

                    $this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    return;
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $this->template->set_title('Şifremi Unuttum');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Şifremi Unuttum');
            $this->template->view('forgot_password_form', $data);
            $this->template->load();
        }
    }

    /**
     * Replace user password (forgotten) with a new one (set by user).
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function reset_password()
    {
        $user_id = $this->uri->segment(3);
        $new_pass_key = $this->uri->segment(4);

        $this->form_validation->set_rules('new_password', 'Yeni Şifre', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
        $this->form_validation->set_rules('confirm_new_password', 'Yeni Şifreyi Doğrula', 'trim|required|xss_clean|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run())
        {
            // validation ok
            if (!is_null($data = $this->auth->reset_password(
                                    $user_id, $new_pass_key,
                                    $this->form_validation->set_value('new_password'))))
            {
                // success
                $data['site_name'] = get_option('site_name');
                // Send email with new password
                $this->_send_email('reset_password', $data['email'], $data);

                $this->_show_message($this->lang->line('auth_message_new_password_activated') . ' ' . anchor(site_url(config_item('auth_uri_login')), 'Giriş'));
                return;
            }
            else
            {
                // fail
                $this->_show_message($this->lang->line('auth_message_new_password_failed'));
                return;
            }
        }
        else
        {
            if (!$this->auth->can_reset_password($user_id, $new_pass_key))
            {
                $this->_show_message($this->lang->line('auth_message_new_password_failed'));
                return;
            }
        }
        $this->template->set_title('Şifre Sıfırlama');
        $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
        $this->template->add_breadcrumb('Şifre Sıfırlama');
        $this->template->view('reset_password_form', $data);
        $this->template->load();
    }

    /**
     * Change user password
     *
     * @return void
     */
    public function change_password()
    {
        if (!$this->auth->is_logged_in())
        {
            // not logged in or not activated
            redirect(config_item('auth_uri_login'));
        }
        else
        {
            $this->form_validation->set_rules('old_password', 'Eski Şifre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('new_password', 'Yeni Şifre', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
            $this->form_validation->set_rules('confirm_new_password', 'Yeni şifreyi doğrula', 'trim|required|xss_clean|matches[new_password]');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                // validation ok
                if ($this->auth->change_password(
                                $this->form_validation->set_value('old_password'),
                                $this->form_validation->set_value('new_password')))
                {
                    // success
                    $this->_show_message($this->lang->line('auth_message_password_changed'));
                    return;
                }
                else
                {
                    // fail
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $this->template->set_title('Şifre Değiştirme');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Şifre Değiştirme');
            $this->template->view('change_password_form', $data);
            $this->template->load();
        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    public function change_email()
    {
        if (!$this->auth->is_logged_in())
        {
            // not logged in or not activated
            redirect(config_item('auth_uri_login'));
        }
        else
        {
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                // validation ok
                if (!is_null($data = $this->auth->set_new_email(
                                        $this->form_validation->set_value('email'),
                                        $this->form_validation->set_value('password'))))
                {
                    // success
                    $data['site_name'] = get_option('site_name');

                    // Send email with new email address and its activation link
                    $this->_send_email('change_email', $data['new_email'], $data);

                    $this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));
                    return;
                }
                else
                {
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $this->template->set_title('E-posta Değiştirme');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('E-posta Değişikliği');
            $this->template->view('change_email_form', $data);
            $this->template->load();
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    public function reset_email()
    {
        $user_id = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);

        // Reset email
        if ($this->auth->activate_new_email($user_id, $new_email_key))
        {
            // success
            $this->auth->logout();
            $this->_show_message($this->lang->line('auth_message_new_email_activated') . ' ' . anchor(site_url(config_item('auth_uri_login')), 'Giriş'));
        }
        else
        {
            // fail
            $this->_show_message($this->lang->line('auth_message_new_email_failed'));
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    public function delete()
    {
        if (!$this->auth->is_logged_in())
        {
            // not logged in or not activated
            redirect(config_item('auth_uri_login'));
        }
        else
        {
            $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run())
            {
                // validation ok
                if ($this->auth->delete_user(
                                $this->form_validation->set_value('password')))
                {
                    // success
                    $this->_show_message($this->lang->line('auth_message_unregistered'));
                    return;
                }
                else
                {
                    // fail
                    $errors = $this->auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = message_box($this->lang->line($v), 'error');
                }
            }
            $this->template->set_title('Üyelikten Çık');
            $this->template->add_breadcrumb('Kullanıcı İşlemleri', 'user');
            $this->template->add_breadcrumb('Üyelikten Çık');
            $this->template->view('unregister_form', $data);
            $this->template->load();
        }
    }

    /**
     * Show info message
     *
     * @param    string
     * @return    void
     */
    private function _show_message($message)
    {

        $this->template->view('general_message', array('message' => $message));
        $this->template->load();
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param    string
     * @param    string
     * @param    array
     * @return    void
     */
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

    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return    string
     */
    private function _create_captcha()
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
    public function _check_captcha($code)
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

    function _check_avatar($str)
    {
        if (isset($_FILES[$str]['name']) && $_FILES[$str]['name'] == '')
        {
            $this->form_validation->set_message('_check_avatar', 'Yüklenecek avatarı seçmediniz.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */