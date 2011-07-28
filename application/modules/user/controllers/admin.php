<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Admin_Model $Admin_model
 *
 * @todo _send_email
 */
class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        access_control();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->lang->load('auth');
        $this->load->config('auth');
    }

    public function index()
    {
        redirect('/admin/user/list_user');
    }

    public function list_user()
    {
        $this->load->library('pagination');
        $data['pagination'] = $this->pagination->init('admin/user/list_user', $this->db->count_all('users'), 3, get_option('per_page_admin'));
        $data['users'] = $this->users->get_users(
                        get_option('per_page_admin'),
                        $this->pagination->get_offset()
        );
        $this->template->view('admin/user_list', $data);
        $this->template->load('admin_layout');
    }

    public function show()
    {
        $user_id = $this->uri->segment(4);
        $user = $this->users->get_user_by_id($user_id);

        if ($user === FALSE)
            redirect('admin/user');

        $data['user'] = $user;
        $data['user_info'] = $this->users->get_user_profile($user_id);
        $data['user_group'] = $this->users->get_group($user->user_group);
        $this->template->view('admin/user_view', $data);
        $this->template->load('admin_layout');
    }

    public function delete()
    {
        $user_id = $this->uri->segment(4);
        $user = $this->users->get_user_by_id($user_id);
        if ($user->user_group == $this->config->item('admin_group'))
        {
            $message = 'Yönetici Hesabını Silemezsiniz.';
        }
        else if ($this->users->delete_user($user_id))
        {
            $message = 'Üye silindi';
        }
        else
        {
            $message = 'Üye silinemedi.';
        }
        $this->template->redir($message, 'admin/user/list_user');
    }

    public function list_admin()
    {
        $this->load->library('pagination');
        $total = $this->db->where('user_group', $this->config->item('admin_group'))->count_all_results('users');
        $data['pagination'] = $this->pagination->init('admin/user/list_admin', $total, 3, get_option('per_page_admin'));
        $data['users'] = $this->users->get_admins(get_option('per_page_admin'), $this->pagination->get_offset());

        $this->template->view('admin/user_list', $data);
        $this->template->load('admin_layout');
    }

    public function add()
    {
        $use_username = $this->config->item('use_username');
        if ($use_username)
        {
            $this->form_validation->set_message('alpha_dash', 'Kullanıcı Adı alanına sadece alfa-nümerik karakterler, altçizgi ve kesikli çizgi girilmelidir. Türkçe (ö,ç,ş gibi) ve özel (?,!,= gibi) karakterler kullanamazsınız.');
            $this->form_validation->set_rules('username', 'Kullanıcı Adı', 'trim|required|xss_clean|min_length[' . $this->config->item('username_min_length') . ']|max_length[' . $this->config->item('username_max_length') . ']|alpha_dash');
        }
        $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
        $this->form_validation->set_rules('confirm_password', 'Şifreyi Doğrula', 'trim|required|xss_clean|matches[password]');
        $data['errors'] = array();

        $status = $this->input->post('status');
        $email_activation = ($status == 1) ? TRUE : FALSE;
        $welcome_message = ($status == 2) ? TRUE : FALSE;
        $data['default_group'] = $this->config->item('default_user_group');
        $data['groups'] = array();
        foreach ($this->users->get_groups() as $group)
        {
            $data['groups'][$group['id']] = $group['title'];
        }
        if ($this->form_validation->run())
        {
            if (!is_null($data = $this->auth->create_user(
                                    $use_username ? $this->form_validation->set_value('username') : '',
                                    $this->form_validation->set_value('email'),
                                    $this->form_validation->set_value('password'),
                                    $email_activation,
                                    array(),
                                    $this->input->post('user_group'))))
            {
                $data['site_name'] = get_option('site_name');
                $data['site_email'] = get_option('site_email');

                if ($email_activation)
                {
                    $data['activation_period'] = $this->config->item('email_activation_expire') / 3600;
                    $this->_send_email('activate', $data['email'], $data);
                }
                elseif ($welcome_message && $this->config->item('email_account_details'))
                {
                    $this->_send_email('welcome', $data['email'], $data);
                }
                unset($data['password']);
                $this->template->redir('Üye eklendi.', 'admin/user/list_user');
                return;
            }
            else
            {
                $errors = $this->auth->get_error_message();
                foreach ($errors as $k => $v)
                    $data['errors'][$k] = message_box($this->lang->line($v), 'error');
            }
        }
        $data['use_username'] = $use_username;
        $this->template->view('admin/user_add', $data);
        $this->template->load('admin_layout');
    }

    public function edit()
    {
        $user_id = $this->uri->segment(4);
        $user = $this->users->get_user_by_id($user_id);
        if ($user === FALSE)
            redirect('admin/user');

        $this->form_validation->set_rules('email', 'E-Posta', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('confirm_password', 'Şifreyi Doğrula', 'trim|xss_clean|matches[password]');
        if ($this->form_validation->run())
        {

            if ($this->auth->update_user(
                            $user_id,
                            $this->form_validation->set_value('email'),
                            $this->input->post('password'),
                            $this->input->post('user_group')))
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

        $data['groups'] = array();
        foreach ($this->users->get_groups() as $group)
        {
            $data['groups'][$group['id']] = $group['title'];
        }
        $data['user_info'] = $this->users->get_user_profile($user_id);
        $data['user'] = $user;

        $this->template->view('admin/user_edit', $data);
        $this->template->load('admin_layout');
    }

    public function change_pass()
    {
        $this->form_validation->set_rules('old_password', 'Eski Şifre', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_password', 'Yeni Şifre', 'trim|required|xss_clean|min_length[' . $this->config->item('password_min_length') . ']|max_length[' . $this->config->item('password_max_length') . ']|alpha_dash');
        $this->form_validation->set_rules('confirm_new_password', 'Yeni şifreyi doğrula', 'trim|required|xss_clean|matches[new_password]');
        $data['errors'] = array();

        if ($this->form_validation->run())
        {
            if ($this->auth->change_password(
                            $this->form_validation->set_value('old_password'),
                            $this->form_validation->set_value('new_password')))
            {
                $this->template->redir($this->lang->line('auth_message_password_changed'), $this->uri->uri_string());
                return;
            }
            else
            {
                $errors = $this->auth->get_error_message();
                foreach ($errors as $k => $v)
                    $data['errors'][$k] = message_box($this->lang->line($v), 'error');
            }
        }
        $this->template->view('admin/change_password_form', $data);
        $this->template->load('admin_layout');
    }

    public function groups()
    {
        $data['groups'] = $this->users->get_groups();
        $this->template->view('admin/groups', $data);
        $this->template->load('admin_layout');
    }

    public function group_add()
    {
        $data = array();
        $this->form_validation->set_rules('name', 'Grup Adı', 'trim|required');
        $this->form_validation->set_rules('title', 'Grup Adı', 'trim|required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'name' => $this->form_validation->set_value('name'),
                'title' => $this->form_validation->set_value('title'),
                'description' => $this->input->post('description'),
            );
            $this->users->add_group($sql_data);
            flash_message('success', 'Grup veritabanına eklendi.');
            redirect('admin/user/groups');
        }

        $this->template->view('admin/group_add', $data);
        $this->template->load('admin_layout');
    }

    public function group_edit()
    {
        $group_id = $this->uri->rsegment(3);
        $data['group'] = $this->users->get_group($group_id);

        if (sizeof($data) < 1)
        {
            flash_message('error', 'Böyle bir grup bulunmuyor.');
            redirect('admin/user/groups');
        }
        $this->form_validation->set_rules('name', 'Grup Adı', 'trim|required');
        $this->form_validation->set_rules('title', 'Grup Adı', 'trim|required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'name' => $this->form_validation->set_value('name'),
                'title' => $this->form_validation->set_value('title'),
                'description' => $this->input->post('description'),
            );
            $this->users->update_group($group_id, $sql_data);
            flash_message('success', 'Grup güncellendi.');
            redirect('admin/user/groups');
        }

        $this->template->view('admin/group_edit', $data);
        $this->template->load('admin_layout');
    }

    public function group_delete()
    {
        $group_id = $this->uri->rsegment(3);
        $group = $this->users->get_group($group_id);

        if (sizeof($group) < 1)
            flash_message('error', 'Üzgünüm, böyle bir grup yok.');
        elseif ($group_id == $this->config->item('admin_group'))
            flash_message('error', 'Yönetim grubunu silemezsiniz.');
        elseif ($this->users->count_group_users($group_id) > 0)
            flash_message('error', 'Bu gruba kayıtlı üyeler var.');
        elseif ($this->users->delete_group($group_id))
            flash_message('success', 'Grup silindi.');
        else
            flash_message('error', 'Grup <b>silinemedi.</b>');

        redirect('admin/user/groups');
    }

}

/* End of file Admin.php */