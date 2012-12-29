<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('typography', 'smiley', 'text', 'date'));
    }

    public function index($slug)
    {
        $this->load->library('user_agent');
        $this->load->helper('meta');
        $data = $this->mongo_db->page->findOne(array('slug' => $slug));
        if (!isset($data['slug']))
        {
            $this->redirect($slug, NULL, NULL, 'page/index/');
            show_404(uri_string(), FALSE);
        }

        if (!$this->agent->is_robot())
        {
            $this->mongo_db->page->update(array(
                '_id' => new MongoId($data['_id'])), array(
                '$set' => array('counter' => ++$data['counter'])));
        }
        $this->template->set_keyword(get_keyword($data))
            ->set_description(get_description($data))
            ->set_title(get_title($data))
            ->view('index', $data)
            ->render();
    }

}

/* End of file page.php */