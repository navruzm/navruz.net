<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('typography', 'post', 'smiley', 'text', 'date'));
    }

    public function index()
    {
        $this->load->library('user_agent');
        $this->load->helper('meta');
        $slug = $this->uri->uri_string();
        $data = $this->mongo_db->post->findOne(array('slug' => $slug, 'status' => 'publish'));
        if (!isset($data['slug']))
        {
            $this->redirect($slug, NULL, NULL, 'post/index/');
            $this->show_404();
            return;
        }

        if (!$this->agent->is_robot())
        {
            $this->mongo_db->post->update(array(
                '_id' => new MongoId($data['_id'])), array(
                '$set' => array('counter' => ++$data['counter'])));
        }
        $related = $this->mongo_db->post->find(array('_id' => array('$ne' => $data['_id']), 'tags' => array('$in' => $data['tags'])))->limit(3);
        $data['related'] = iterator_to_array($related);
        $this->template->set_keyword(get_keyword($data))
            ->set_description(get_description($data))
            ->set_title(get_title($data))
            ->view('index', $data)
            ->render();
    }

    function tag($slug = NULL)
    {
        $page = $this->input->get('page') === FALSE ? 1 : $this->input->get('page');
        $cursor = $this->mongo_db->post
            ->find(array('tags.slug' => $slug, 'status' => 'publish'))
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page'))
            ->skip((get_option('per_page') * ($page - 1)));
        $data['posts'] = iterator_to_array($cursor);
        $data['total_page'] = ceil($cursor->count() / get_option('per_page'));

        if (count($data['posts']) == 0)
        {
            show_404(uri_string(), FALSE);
        }
        $first = reset($data['posts']);
        foreach ($first['tags'] as $_tag)
        {
            if ($_tag['slug'] == $slug)
            {
                $tag = $_tag['tag'];
            }
        }
        $data['title'] = '<em>' . $tag . '</em> Etiketli Yazılar';
        $this->template
            ->set_description($tag . ' ' . $first['title'], TRUE)
            ->set_title($tag . ' Etiketli Yazılar', TRUE)
            ->set_keyword($tag)
            ->view('list', $data)
            ->render();
    }

    public function archive()
    {
        $posts = $this->mongo_db->post->find(array('status' => 'publish'))->sort(array('created_at' => -1));

        $data['posts'] = array();
        foreach ($posts as $post)
        {
            $data['posts'][tr_date('F Y', $post['created_at']->sec)][] = $post;
        }

        $this->template->set_keyword('Yazı Arşivi')
            ->set_description('Yazı Arşivi', TRUE)
            ->set_title('Yazı Arşivi')
            ->add_breadcrumb('Yazı')
            ->view('archive', $data)
            ->render();
    }

    public function sync()
    {
        $data = $this->mongo_db->post->findOne(array('_id' => new MongoID($this->input->get('id'))));
        if (!count($data))
        {
            return;
        }

        require_once APPPATH . 'libraries/disqusapi/disqusapi.php';
        $disqus = new DisqusAPI(get_option('disqus_api_key'));
        $comments = $disqus->threads->listPosts(array(
            'thread' => 'ident:' . $this->input->get('ident'),
            'forum' => get_option('disqus'),
            'order' => 'asc'));

        $data['comments'] = array();
        foreach ($comments as $comment)
        {
            $data['comments'][] = array(
                'id' => $comment->id,
                'author_name' => $comment->author->name,
                'author_url' => isset($comment->author->url) ? : $comment->author->url,
                'message' => $comment->message,
                'created_at' => new MongoDate(strtotime($comment->createdAt)),
            );
        }
        $this->mongo_db->post->save($data);
        return 'OK';
    }

    public function show_404()
    {
        set_status_header(404);
        $data['posts'] = $this->mongo_db->post
            ->find(array('status' => 'publish', 'content' => array('$regex' => new MongoRegex('/' . str_replace('-', ' ', $this->uri->uri_string()) . '/i'))))
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page'));
        $data['posts'] = iterator_to_array($data['posts']);
        $this->template
            ->set_title('Sayfa Bulunamadı - 404')
            ->view('post/show_404', $data)
            ->render();
    }

}

/* End of file post.php */