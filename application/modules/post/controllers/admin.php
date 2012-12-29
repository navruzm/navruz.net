<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->config('post');
    }

    public function index()
    {
        $data = array('title' => '', 'status' => '');
        $search_data = array();
        if ($this->input->get('q'))
        {
            $data['title'] = $this->input->get('q');
            $search_data['title'] = array('$regex' => new MongoRegex('/' . $this->input->get('q') . '/i'));
        }
        if ($this->input->get('status'))
        {
            $data['status'] = $this->input->get('status');
            $search_data['status'] = $this->input->get('status');
        }
        $this->load->library('pagination');
        $page = ($this->input->get('page') > 0) ? $this->input->get('page') : 1;

        $cursor = $this->mongo_db->post
            ->find($search_data)
            ->sort(array('created_at' => -1))
            ->limit(get_option('per_page_admin'))
            ->skip((get_option('per_page_admin') * ($page - 1)));
        $total_documents = $cursor->count();
        $data['posts'] = $cursor;
        $config = array(
            'base_url' => 'admin/post/index?q=' . $data['title'] . '&amp;status=' . $data['status'],
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'total_rows' => $total_documents,
            'per_page' => get_option('per_page_admin')
        );
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->view('admin/index', $data)->render();
    }

    public function add($id = NULL)
    {

        $data = array();
        if ($id !== NULL)
        {
            $data = $this->mongo_db->post->findOne(array('_id' => new MongoID($id)));
            if (!count($data))
            {
                show_404();
            }
        }
        $this->load->library(array('image_lib', 'upload', 'form_validation'));
        $this->form_validation->set_rules('title', 'Yazı Başlığı', 'trim|required');
        $this->form_validation->set_rules('categories[]', 'Yazı Kategorisi', 'trim|required');
        $this->form_validation->set_rules('content', 'Yazı İçeriği', 'required');
        if ($this->form_validation->run())
        {

            $categories = array_map(function($value)
            {
                return new MongoID($value);
            }, set_value('categories'));
            $tags = array();
            foreach (explode(',', set_value('tags')) as $tag)
            {
                $tags[] = array('slug' => url_title($tag), 'tag' => $tag);
            }
            $sql_data = array(
                '_id' => $id !== NULL ? new MongoID($id) : new MongoID(),
                'slug' => url_title($this->form_validation->set_value('title')),
                'author' => $this->session->userdata('user_id'),
                'title' => $this->form_validation->set_value('title'),
                'content' => $this->form_validation->set_value('content'),
                'status' => $this->input->post('status'),
                'comments_enabled' => $this->input->post('comments_enabled'),
                'counter' => isset($data['counter']) ? $data['counter'] : 0,
                'categories' => $categories,
                'tags' => $tags,
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keyword' => $this->input->post('meta_keyword'),
                'created_at' => isset($data['created_at']) ? $data['created_at'] : new MongoDate(),
                'updated_at' => new MongoDate(),
            );
            $sql_data['disqus_identifier'] = 'post_' . (string)$sql_data['_id'];
            $image = $this->upload_image($sql_data['slug']);
            if ($image)
            {
                $sql_data['image'] = $image;
            }
            $sql_data = array_merge($data, $sql_data);
            $this->mongo_db->post->ensureIndex('slug');
            $this->mongo_db->post->save($sql_data);
            if (isset($data['slug']) && $data['slug'] != $sql_data['slug'])
            {
                $redirect = array('old_slug' => $data['slug'], 'new_slug' => $sql_data['slug'], 'module' => 'post');
                $this->mongo_db->redirect->save($redirect);
            }
            if ($id === NULL)
            {
                flash_message('success', 'Yazı başarıyla eklendi.');
            }
            else
            {
                flash_message('success', 'Yazı başarıyla düzenlendi.');
            }

            if (ENVIRONMENT == 'production')
            {
                //Update sitemap
                $this->load->library('xml_sitemap');
                $items = array(
                    array(
                        'slug' => '',
                        'created_on' => time() + 5,
                        'updated_on' => time() + 5,
                        'changefreq' => 'daily',
                        'priority' => '1',
                    )
                );
                $this->xml_sitemap->add($items);
                $posts = array();
                foreach ($this->mongo_db->post->find(array('status' => 'publish'))
                             ->sort(array('created_at' => -1)) as $post)
                {
                    $posts[] = array(
                        'title' => $post['title'],
                        'slug' => $post['slug'],
                        'created_on' => $post['created_at']->sec,
                        'updated_on' => $post['updated_at']->sec,
                        'comment' => count($post['comments']),
                    );
                }
                $this->xml_sitemap->add($posts);
                $this->xml_sitemap->generate();
            }
            redirect('admin/post/index');
        }
        $data['all_categories'] = $this->mongo_db->category->find();
        $this->template->view('admin/add', $data)->render();
    }

    public function edit($id)
    {
        $this->add($id);
    }

    function delete($id)
    {
        $post = $this->mongo_db->post->findOne(array('_id' => new MongoID($id)));

        if (count($post) < 1)
        {
            flash_message('error', 'Üzgünüm, böyle bir yazı yok.');
        }
        else if ($this->mongo_db->post->remove(array('_id' => new MongoID($id))))
        {
            flash_message('success', 'Yazı silindi');
            $image = $this->mongo_db->gridfs->findOne($post['image']);
            $this->mongo_db->gridfs->delete($image->file['_id']);
        }
        else
        {
            flash_message('error', 'Yazı <b>silinemedi.</b>');
        }
        redirect('admin/post/index');
    }

    public function get_tags()
    {
        $tags = array();
        foreach ($this->mongo_db->post->find(array('tags.tag' => new MongoRegex('/' . $this->input->get('term') . '/')), array('tags')) as $post)
        {
            foreach ($post['tags'] as $tag)
            {
                if (stripos($tag['tag'], $this->input->get('term')) !== FALSE)
                {
                    $tags[$tag['slug']] = array('id' => $tag['tag'], 'label' => $tag['tag'], 'value' => $tag['tag']);
                }
            }
        }
        sort($tags);
        $this->output->set_output(json_encode($tags));
    }

    private function upload_image($slug)
    {
        if (!isset($_FILES['image']))
        {
            return FALSE;
        }
        $allowed = array('jpg', 'jpeg', 'png');
        $file_type = strtolower(trim(stripslashes(preg_replace("/^(.+?);.*$/", "\\1", $_FILES['image']['type'])), '"'));
        $file_ext = '.' . end(explode('.', $_FILES['image']['name']));
        $file_name = $slug . $file_ext;
        if (!in_array(trim($file_ext, '.'), $allowed))
        {
            return FALSE;
        }
        $image = $this->mongo_db->gridfs->findOne($file_name);
        $this->mongo_db->gridfs->delete($image->file['_id']);
        $this->mongo_db->gridfs->storeUpload('image', array('filename' => $file_name, 'type' => $_FILES['image']['type']));
        return $file_name;
    }

}
/* End of file admin.php */