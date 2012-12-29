<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('typography', 'post/post', 'smiley', 'text', 'date'));
    }

    public function index()
    {
        $q = $this->input->get('q');
        $page = ($this->input->get('page') > 0) ? $this->input->get('page') : 1;
        $cursor = $this->mongo_db->post
            ->find(array('status' => 'publish', 'content' => array('$regex' => new MongoRegex('/' . $q . '/i'))))
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page'))
            ->skip((get_option('per_page') * ($page - 1)));
        $data['posts'] = $cursor;
        $data['total_page'] = ceil($cursor->count() / get_option('per_page'));
        $data['title'] = 'Arama Sonuçları : ' . $q;
        $this->template
            ->set_title('Arama Sonuçları : ' . $q)
            ->view('post/list', $data)
            ->render();
    }

}

/* End of file search.php */
