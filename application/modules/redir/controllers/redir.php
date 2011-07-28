<?php

class Redir extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('redir_model');
    }

    function index()
    {
        $module = $this->uri->rsegment(3);
        $id = $this->uri->rsegment(4);
        $page = $this->uri->rsegment(5);
        $result = $this->redir_model->get($module, $id);
        //echo $this->db->last_query();
        //var_dump($result);die;
        if (!isset($result['new_slug']))
        {
            show_404($this->uri->uri_string());
        }
        if ($page > 1)
        {
            $page = '-s' . $page;
        }
        else
        {
            $page = '';
        }
        redirect($result['new_slug'] . $page);
    }

}

/* End of file rss.php */