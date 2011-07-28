<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Dashboard extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Dashboard Controller Initialized');
        $this->load->model('post/post_model');
    }

    function index()
    {
        $data['post'] = $this->post_model->get_posts(5, 0);
        $data['stats'] = array(
            'post' => $this->db->count_all('posts'),
            'category' => $this->db->count_all('categories'),
            'tags' => $this->db->count_all('tags'),
        );
        $this->template->view('admin/admin/dashboard', $data);
        $this->template->load('admin_layout');
    }

}

/* End of file dashboard.php */