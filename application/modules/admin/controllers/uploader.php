<?php

class Uploader extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }
    
    function index()
    {
        if(($_FILES['upload']['name'] == ''))
        {
            return;
        }
        $this->load->library(array('upload', 'image_lib'));
        $data['error'] = FALSE;
        $data['upload'] = '';

        if((bool) ini_get('safe_mode') !== TRUE)
        {
            log_message('error', 'yok');
            if(file_exists('media/upload/' . date('Y')) === FALSE)
            {
                @mkdir('media/upload/' . date('Y'));
            }
            if(file_exists('media/upload/' . date('Y') . '/' . date('m')) === FALSE)
            {
                @mkdir('media/upload/' . date('Y') . '/' . date('m'));
            }
        }
        else
        {
            return;
        }
        $config['upload_path'] = 'media/upload/' . date('Y') . '/' . date('m') . '/';
        $config['allowed_types'] = 'jpg|png';
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('upload'))
        {
            $data['error'] = $this->upload->display_errors('', '');
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $config['source_image'] = $data['upload_data']['full_path'];
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 200;
            $config['height'] = 150;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
            $data['upload'] = $config['upload_path'] . $data['upload_data']['file_name'];
        }

        $this->load->view('admin/admin/uploader', $data);
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */