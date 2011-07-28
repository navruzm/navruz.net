<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Options_admin extends Admin_Controller {

    function __construct() {
        parent::__construct();
        log_message('debug', 'Options_admin Controller Initialized');
        $this->load->library('form_validation');
    }

    function index() {
        $data = array();
        $this->form_validation->set_rules('site_name', 'Site Adı', 'trim|required');
        $this->form_validation->set_rules('site_email', 'E-Posta', 'trim|required|valid_email');
        $this->form_validation->set_rules('maintenance-end', 'Bitiş zamanı', 'required|valid_datetime');
        $this->form_validation->set_message('valid_datetime', 'Tarih-Saat formatını (gg-aa-yyyy ss:dd) yanlış girdiniz ');
        if ($this->form_validation->run()) {
            unset($_POST['submit']);
            $_POST['maintenance-end'] = strtotime($_POST['maintenance-end']);
            foreach ($_POST as $key => $value) {
                set_option($key, $value, TRUE);
            }
            $this->template->redir('Ayarlar güncellendi', $this->uri->uri_string());
        }
        $this->template->view('admin/admin/options', $data);
        $this->template->load('admin_layout');
    }

    function debug() {
        if (get_option('debug') == 1) {
            set_option('debug', 0);
            $message = 'Hata ayıklama modu kapatıldı.';
        } else {
            set_option('debug', 1);
            $message = 'Hata ayıklama modu açıldı.';
        }
        $this->template->redir($message, '');
    }

}

/* End of file dashboard.php */