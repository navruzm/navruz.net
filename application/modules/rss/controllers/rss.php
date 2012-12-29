<?php

class Rss extends MY_Controller
{

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
        $data['posts'] = $this->mongo_db->post->find()->sort(array('created_at' => -1))->limit(get_option('per_page'));
        $this->load->view('rss', $data);
    }
}

/* End of file rss.php */