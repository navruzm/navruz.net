<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'pagination'));
    }

    function index()
    {
        $data['navigations'] = $this->mongo_db->navigation->find()->sort(array('order' => 1));
        $this->template->view('admin/index', $data)->render();
    }

    function add($id=NULL)
    {
        $data = array();
        if ($id !== NULL)
        {
            $data = $this->mongo_db->navigation->findOne(array('_id' => new MongoID($id)));
            if (!count($data))
            {
                show_404();
            }
        }
        $this->form_validation->set_rules('title', 'Menü Adı', 'trim|required');
        $this->form_validation->set_rules('slug', 'Menü Etiketi', 'required');
        if ($this->form_validation->run())
        {
            $items = array();
            foreach (set_value('items') as $item)
            {
                if ($item['title'] != '')
                {
                    $items[] = $item;
                }
            }
            $sql_data = array(
                'slug' => set_value('slug'),
                'title' => set_value('title'),
                'items' => $items,
            );
            if ($id !== NULL)
            {
                $sql_data['_id'] = new MongoID($id);
            }
            $this->mongo_db->navigation->save($sql_data);
            flash_message('success', 'Menü başarıyla eklendi/düzenlendi.');
            redirect('admin/navigation/index');
        }
        $this->template->view('admin/add', $data)->render();
    }

    function edit($id)
    {
        $this->add($id);
    }

    function delete($id)
    {
        $navigation = $this->mongo_db->navigation->findOne(array('_id' => new MongoID($id)));

        if (count($navigation) < 1)
        {
            flash_message('error', 'Üzgünüm, böyle bir menü yok.');
        }
        else if ($this->mongo_db->navigation->remove(array('_id' => new MongoID($id))))
        {
            flash_message('success', 'Menü silindi');
        }
        else
        {
            flash_message('error', 'Menü <b>silinemedi.</b>');
        }
        redirect('admin/navigation/index');
    }

}

/* End of file admin.php */