<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        log_message('debug', 'category Controller Initialized');
        $this->load->library('pagination');
        $this->load->helper('post/post');
        $this->load->helper('category');
        $this->load->helper('text');
    }

    function index($slug = NULL)
    {
        $data = $this->mongo_db->category->findOne(array('slug' => $slug));
        if (!count($data))
        {
            $this->redirect($slug, NULL, NULL, 'category/');
            show_404(uri_string(), FALSE);
        }
        $page = ($this->input->get('page') > 0) ? $this->input->get('page') : 1;
        $cursor = $this->mongo_db->post
            ->find(array('categories' => $data['_id'], 'status' => 'publish'))
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page'))
            ->skip((get_option('per_page') * ($page - 1)));
        $data['posts'] = iterator_to_array($cursor);
        $data['total_page'] = ceil($cursor->count() / get_option('per_page'));
        $first = reset($data['posts']);
        $this->template
            ->set_description($data['title'] . ' ' . $first['title'], TRUE)
            ->set_title($data['title'] . ' Yazıları', TRUE)
            ->set_keyword($data['title'])
            ->view('post/list', $data)
            ->render();
    }

}

/* End of file category.php */