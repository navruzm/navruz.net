<?php

/**
 *
 * @property Post_model $post_model
 *
 */
class Home extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->load->model('post/post_model');
        $this->load->library(array('pagination', 'tags'));
        $this->load->helper('post/post');

        $config = array(
            'base_url' => '',
            'base_url_del' => TRUE,
            'total_rows' => $this->db->count_all('posts'),
            'uri_segment' => 3,
            'delimiter' => 'page-',
        );
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $posts = $this->post_model->get_posts(
                        get_option('per_page'),
                        $this->pagination->get_offset()
        );
        $data['posts'] = array();
        foreach ($posts as $post)
        {
            $data['posts'][] = array_merge($post, array(
                        'categories' => $this->post_model->get_post_categories($post['id']),
                        'tags' => $this->tags->get_tags_on_object($post['id'])
                    ));
        }
        $this->template->add_keyword(get_option('site_keywords'));
        $this->template->set_description(get_option('site_description'));
        $this->template->set_title(get_option('site_name'));
        $this->template->view('home', $data);
        $this->template->load();
    }

	function error_404()
    {
        show_404('',FALSE);
    }
    
}

/* End of file home.php */
