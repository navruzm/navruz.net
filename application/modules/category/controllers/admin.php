<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'pagination'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    function index()
    {
        $data['categories'] = $this->mongo_db->category->find()->sort(array('order'=>1));
        $this->template->view('admin/index', $data)->render();
    }

    function add($id=NULL)
    {
        $data = array();
        if ($id !== NULL)
        {
            $data = $this->mongo_db->category->findOne(array('_id' => new MongoID($id)));
            if (!count($data))
            {
                show_404();
            }
        }
        $this->form_validation->set_rules('title', 'Kategori Adı', 'trim|required');
        $this->form_validation->set_rules('description', 'Kategori Açıklaması', 'required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'slug' => $this->_get_slug($this->form_validation->set_value('title')),
                'title' => $this->form_validation->set_value('title'),
                'description' => $this->form_validation->set_value('description'),
                'meta_title' => $this->input->post('meta_title'),
                'meta_description' => $this->input->post('meta_description'),
                'meta_keyword' => $this->input->post('meta_keyword'),
            );
            if ($id !== NULL)
            {
                $sql_data['_id'] = new MongoID($id);
            }
            $this->mongo_db->category->save($sql_data);
            if (isset($data['slug']) && $data['slug'] != $sql_data['slug'])
            {
                
            }
            flash_message('success', 'Kategori başarıyla eklendi/düzenlendi.');
            redirect('admin/category/index');
        }
        $this->template->view('admin/add', $data)->render();
    }

    function edit($id)
    {
        $this->add($id);
    }

    function delete($id)
    {
        $category = $this->mongo_db->category->findOne(array('_id' => new MongoID($id)));

        if (count($category) < 1)
        {
            flash_message('error', 'Üzgünüm, böyle bir Kategori yok.');
        }
        else if ($this->mongo_db->category->remove(array('_id' => new MongoID($id))))
        {
            flash_message('success', 'Kategori silindi');
        }
        else
        {
            flash_message('error', 'Kategori <b>silinemedi.</b>');
        }
        redirect('admin/category/index');
    }

    function sort()
    {
        foreach ($this->input->get('sort') as $position => $category_id)
        {
            $this->mongo_db->category->update(array('_id' => new MongoID($category_id)), array('$set' => array('order' => $position)));
        }
        //@todo ajax message
        $this->output->enable_profiler(FALSE);
        $message = '<span class="success" style="display:block">Sıralama kaydedildi.</span>';
        $this->output->set_output($message);
    }

    private function _get_slug($title)
    {
        $first = url_title($title);
        $i = 1;
        $slug_control = 1;

        while ($slug_control == 1)
        {
            $slug = ($i < 2) ? $first : $first . '-' . $i;
            $slug_control = $this->mongo_db->category->find(array('slug' => $slug))->count();
            ++$i;
        }
        return $slug;
    }

}

/* End of file admin.php */