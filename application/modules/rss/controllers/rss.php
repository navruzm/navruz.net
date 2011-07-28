<?php

class Rss extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('xml');
    }

    function index()
    {
        $data['encoding'] = 'utf-8';
        $data['feed_name'] = get_option('site_name');
        $data['feed_url'] = site_url('rss');
        $data['page_description'] = get_option('site_description');
        $data['page_language'] = 'tr-TR';
        $data['creator_email'] = get_option('site_email');
        $this->output->set_header('Content-Type: application/rss+xml; Charset=utf-8');
        $this->load->model('post/post_model');
        $data['posts'] = $this->post_model->get_posts(10, 0);
        $this->load->view('rss', $data);
    }
}

    /* End of file rss.php */