<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('typography', 'post/post', 'smiley', 'text', 'date'));
    }

    public function index()
    {
        $page = $this->input->get('page') === FALSE ? 1 : $this->input->get('page');
        $data['posts'] = $this->mongo_db->post
            ->find(array('status' => 'publish'))
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page'))
            ->skip((get_option('per_page') * ($page - 1)));
        $data['total_page'] = ceil($data['posts']->count() / get_option('per_page'));
        $this->template
            ->set_title(get_option('site_title'))
            ->set_description(get_option('site_description'))
            ->set_keyword(get_option('site_keywords'))
            ->view('post/list', $data)->render();
    }

}

/* End of file home.php */
