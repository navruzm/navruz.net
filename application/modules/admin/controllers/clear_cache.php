<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Clear_cache extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'Clear_cache Controller Initialized');
    }

    public function index()
    {
        $this->load->helper('file');
        $data['directories'] = array(
            'captcha'=>'Captcha',
            'db'=>'Veritabanı',
            'site'=>'Site',
            'min'=>'Minify (CSS ve JS Sıkıştırma)'
        );
        $data['status'] = array();
        foreach ($data['directories'] as $directory=>$message)
        {
            $data['status'][$message.' ('.$directory.')'] = delete_files('cache/'.$directory);
        }

        $data['status']['Avatar temp/'] = delete_files(config_item('temp_upload_path'));


        $this->template->view('admin/admin/clear_cache',$data);
        $this->template->load('admin_layout');
    }
}

/* End of file dashboard.php */