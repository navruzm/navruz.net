<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Page_Model $page_model
 *
 */
class Page extends MY_Controller
{

    /**
     * Gerekli kütüphane ve modelleri yükler
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('page/page_model'));
        $this->load->helper(array('typography', 'smiley', 'meta'));
        $this->load->library('pagination');
    }

    /**
     * Sayfaları listeler.
     */
    public function index()
    {
        $slug = $this->uri->rsegment(3);
        $this->load->library('user_agent');
        $data = $this->page_model->get_page($slug);
        if (!isset($data['slug']))
        {
            _redirect('page/' . $slug, 'page_model', 'is_slug_available', NULL, 'page/');
            show_404(uri_string());
        }
        $pages = explode('<!--pagebreak-->', $data['content']);
        $config = array(
            'base_url' => 'page/' . $data['slug'],
            'base_url_del' => TRUE,
            'total_rows' => count($pages),
            'uri_segment' => 4,
            'delimiter' => '-s',
            'per_page' => 1,
        );
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['content'] = $pages[$this->pagination->cur_page - 1];

        if (!$this->agent->is_robot())
        {
            $this->page_model->increase_view_count($data['id']);
        }
        $this->template->add_keyword(get_keywords($data));
        $this->template->set_description(get_description($data), TRUE);
        $this->template->set_title(get_title($data));
        $this->template->add_breadcrumb('Sayfa', 'pages');
        $this->template->add_breadcrumb($data['title']);
        $this->template->view('page', $data);
        $this->template->load();
    }

    public function all_pages()
    {
        $config = array(
            'base_url' => 'pages',
            'base_url_del' => TRUE,
            'total_rows' => $this->db->count_all_results('pages'),
            'uri_segment' => 3,
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['pages'] = $this->page_model->get_pages(
                        get_option('per_page'),
                        $this->pagination->get_offset()
        );

        $this->template->add_keyword('Sayfalar');
        $this->template->set_description('Sayfalar', TRUE);
        $this->template->set_title('Sayfalar');
        $this->template->add_breadcrumb('Sayfa');
        $this->template->view('page_list', $data);
        $this->template->load();
    }

}