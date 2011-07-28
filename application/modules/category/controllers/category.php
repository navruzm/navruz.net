<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Category_model $category_model
 *
 */
class Category extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        log_message('debug', 'category Controller Initialized');
        $this->load->library('pagination');
        $this->load->model(array('post/post_model', 'category/category_model'));
        $this->load->helper('post/post');
    }

    function index()
    {
        $cat_slug = $this->uri->rsegment(3);
        $category = $this->category_model->get_category_by_slug($cat_slug);
        if (!isset($category['category_slug']))
        {
            _redirect('category/' . $cat_slug, 'category_model', 'is_slug_available', NULL, 'category/');
            show_404(uri_string(), FALSE);
        }
        $config = array(
            'base_url' => 'category/' . $cat_slug,
            'base_url_del' => TRUE,
            'total_rows' => $this->db->where('category_id', $category['category_id'])->count_all_results('post_relationship'),
            'uri_segment' => 4,
            'delimiter' => '-page-',
        );
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $posts = $this->category_model->get_category_posts(
                        $category['category_id'], get_option('per_page'), $this->pagination->get_offset()
        );

        $data['posts'] = array();
        foreach ($posts as $post_item)
        {
            $data['posts'][] = array_merge($post_item, array(
                'categories' => $this->post_model->get_post_categories($post_item['id']),
                    ));
        }
        $data['category'] = $category;

        $this->template
                ->add_breadcrumb('Kategoriler', 'category')
                ->add_breadcrumb($category['category_title'])
                ->add_keyword($category['category_title'])
                ->set_description($category['category_title'].' '.$data['posts'][0]['title'], TRUE)
                ->set_title($category['category_title'].' Yazıları',TRUE)
                ->view('kategori_view', $data)
                ->load();
    }

}

/* End of file category.php */