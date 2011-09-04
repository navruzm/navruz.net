<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Post_Model $post_model
 * @property Tags $tags
 *
 */
class Post extends MY_Controller
{

    /**
     * Gerekli kütüphane ve modelleri yükler
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('post/post_model'));
        $this->load->helper(array('typography', 'post', 'smiley', 'text', 'meta'));
        $this->load->library('tags');
    }

    /**
     * Yazıları listeler.
     */
    public function index()
    {
        $this->load->library('user_agent');
        $slug = $this->uri->rsegment(3);
        $data = $this->post_model->get_post($slug);
        if (!isset($data['slug']))
        {
            _redirect($slug, 'post_model', 'is_slug_available');
            show_404(uri_string(), FALSE);
        }
        $data['categories'] = $this->post_model->get_post_categories($data['id']);

        if (!$this->agent->is_robot())
        {
            $this->post_model->increase_view_count($data['id']);
        }
        $this->load->driver('cache');
        $data['comments'] = $this->cache->file->get('comments_' . $data['id']);
        if ($data['comments'] === FALSE && get_option('disqus_api_key'))
        {
            try
            {
                require APPPATH . 'libraries/disqusapi/disqusapi.php';
                $disqus = new DisqusAPI(get_option('disqus_api_key'));
                $data['comments'] = $disqus->threads->listPosts(array('thread' => 'ident:post_' . $data['id'], 'forum' => get_option('disqus'), 'order' => 'asc'));
                $this->cache->file->save('comments_' . $data['id'], $data['comments'], 172800);
            }
            catch (Exception $e)
            {
                show_error('Disqus API : '.$e->getMessage());
            }
        }
        else
        {
            $data['comments'] = array();
        }


        $data['tags'] = $this->tags->get_tags_on_object($data['id']);
        $this->template->add_keyword(get_keywords($data));
        $this->template->set_description(get_description($data));
        $this->template->set_title(get_title($data));
        $this->template->add_breadcrumb('Yazı', 'post');
        $this->template->add_breadcrumb($data['title']);
        $this->template->view('post', $data);
        $this->template->load();
    }

    /**
     * Yazıları listeler.
     */
    public function archive()
    {

        $posts = $this->post_model->get_posts(NULL, NULL);

        $data['posts'] = array();
        foreach ($posts as $post_item)
        {
            $data['posts'][tr_date('F Y', $post_item['created_on'])][] = $post_item;
        }

        $this->template->add_keyword('Yazı Arşivi');
        $this->template->set_description('Yazı Arşivi', TRUE);
        $this->template->set_title('Yazı Arşivi');
        $this->template->add_breadcrumb('Yazı');
        $this->template->view('archive', $data);
        $this->template->load();
    }

}

/* End of file post.php */