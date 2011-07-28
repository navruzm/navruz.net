<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @property Category_model $category_model
 *
 */
class Tag extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Etiket Controller Initialized');
        $this->load->library('pagination');
        $this->load->model(array('post/post_model', 'category/category_model'));
        $this->load->helper('post/post');
    }
    
    function index()
    {
        $tag_slug = $this->uri->rsegment(3);
        $tag = $this->tags->get_tag_by_slug($tag_slug);
        if(!$tag)
        {
            $this->_redirect($tag_slug);
            show_404(uri_string(),FALSE);
        }
        $config = array(
            'base_url' => 'tag/' . $tag_slug,
            'total_rows' => $this->db->where('tag_id', $tag['id'])->count_all_results('tags_object'),
            'uri_segment' => 4,
            'base_url_del' => TRUE,
            'delimiter' => '-page-',
        );
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $posts = $this->tags->get_objects_with_tag_id(
                $tag['id'],
                $this->pagination->get_offset(),
                get_option('per_page')
        );
        $data['tag'] = $tag;
        $data['posts'] = array();
        foreach($posts as $id)
        {
            $data['posts'][] = array_merge($this->post_model->get_post_by_id($id), array(
                    'categories' => $this->post_model->get_post_categories($id),
                    'tags' => $this->tags->get_tags_on_object($id)
                ))
            ;
        }
        
        $this->template->add_keyword($tag['raw_tag']);
        $this->template->set_description($tag['raw_tag'].' '.$data['posts'][0]['title'], TRUE);
        $this->template->set_title($tag['raw_tag'].' Etiketli Yazılar',TRUE);
        
        $this->template->view('tag', $data);
        $this->template->load();
    }
    
    private function _redirect($old_slug, $uri = NULL)
    {
        $uri = ($uri == NULL) ? $this->uri->uri_string() : $uri;
        $this->load->library('redirect');
        if($new_slug = $this->redirect->get($old_slug))
        {
            $uri = str_replace($old_slug, $new_slug, $uri);
            if((int) $this->category_model->is_slug_available($new_slug) < 1)
            {
                $this->_redirect($new_slug, $uri);
            }
            else
            {
                redirect($uri, 'location', 301);
            }
        }
        else
        {
            return FALSE;
        }
    }
}

/* End of file etiket.php */