<?php

class Manager extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('directory');
        $this->load->library('session');
    }

    function index()
    {
        $this->load->helper('directory');
        $this->load->helper('url');
        $this->load->view('admin/admin/manager');
    }

    function files()
    {
        $this->load->view('admin/admin/manager_files');
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */