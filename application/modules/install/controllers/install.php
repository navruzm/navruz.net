<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Install extends MY_Controller
{

    public function index()
    {
        if ($this->mongo_db->user->find()->count() && !$this->input->get('ok'))
        {
            show_error('Kurulum zaten yapılmış!');
        }
        $this->load->library('form_validation');
        $this->load->library('user/auth');
        $data = array();
        $this->form_validation->set_rules('password', 'Şifre', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Şifre tekrarı', 'trim|required|xss_clean|matches[password]');
        $this->form_validation->set_rules('email', 'E-posta', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('name', 'İsim', 'trim|required|xss_clean');

        if ($this->form_validation->run())
        {
            $data['name'] = set_value('name');
            $data['permissions'] = set_value('permissions');

            if ($this->auth->create(set_value('email'), set_value('password'), $data, TRUE))
            {
                set_option('site_name', set_value('site_name'));
                set_option('site_email', set_value('site_email'));
                set_option('per_page', 10);
                set_option('per_page_admin', 20);
                set_option('debug', 0);
                $navigation = array(
                    'slug'=>'HEAD_MENU',
                    'title'=>'Üst Menü',
                    'items'=>array(
                        array(
                            'title'=>'Anasayfa',
                            'url'=>'/',
                            'access_level'=>'0',
                            'target'=>'',
                        ),
                        array(
                            'title'=>'İletişim',
                            'url'=>'/contact',
                            'access_level'=>'0',
                            'target'=>'',
                        )
                    ),
                );
                $this->mongo_db->navigation->insert($navigation);
                flash_message('success', 'Üye başarıyla eklendi.');
                redirect('install?ok=1');
            }
        }
        $this->load->view('index', $data);
    }

}