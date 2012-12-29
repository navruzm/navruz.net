<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('admin/admin_menu');
        $this->template->set_theme('admin');
        $perm = check_permission();
        if ($perm === FALSE)
        {
            $this->session->set_flashdata('redirect', $this->uri->uri_string());
            redirect('user/login');
        }
        elseif ($perm === NULL)
        {
            show_error('Yetkiniz yok.');
        }
    }

}