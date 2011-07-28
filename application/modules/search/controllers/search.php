<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends MY_Controller
{

    private $keyword = '';

    /**
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'pagination'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->input->post('is_search') == 1)
        {
            $this->keyword = $this->input->post('keyword',TRUE);
            $this->session->set_flashdata(array('keyword' => $this->keyword));
        }
        elseif ($this->session->flashdata('keyword'))
        {
            $this->keyword = $this->session->flashdata('keyword');
            $this->session->keep_flashdata('keyword');
        }
        no_index();
    }

    /**
     * @return void
     */
    function index()
    {
        $this->post();
    }

    function post()
    {
        $this->load->model('post/post_model');
        $this->load->helper('post/post');
        $data['pagination'] = $this->pagination->init('search/' . $this->type, $this->post_model->search_count($this->keyword), 3);
        $posts = $this->post_model->search(get_option('per_page'), $this->pagination->get_offset(), $this->keyword);
        $data['posts'] = array();
        foreach ($posts as $post)
        {
            $data['news'][] = array_merge($post, array(
                        'categories' => $this->post_model->get_post_categories($post['id']),
                    ));
        }
        $data['keyword'] = $this->keyword;
        $this->template->add_breadcrumb('Arama', 'search');
        $this->template->add_breadcrumb('Haber Ara');
        $this->template->view('list_post', $data);
        $this->template->load();
    }

}

/* End of file admin.php */