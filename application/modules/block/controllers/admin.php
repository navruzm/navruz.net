<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property Block_model $block_model
 *
 */
class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Block_admin Controller Initialized');
        $this->load->model('block/block_model');
        $this->load->model('navigation/navigation_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    function index()
    {
        redirect('admin/block/blocks');
    }

    function blocks()
    {
        $data['blocks'] = $this->block->get_all_blocks();
        $this->template->view('admin/list', $data);
        $this->template->load('admin_layout');
    }

    function show()
    {
        $module = $this->uri->rsegment(3);
        $data['blocks'] = $this->block_model->get_all($module);
        $this->template->view('admin/list', $data);
        $this->template->load('admin_layout');
    }

    function add()
    {
        $data['files'] = array('' => 'Seçiniz');
        foreach ($this->block->block_files() as $name => $file)
        {
            $data['files'][$name] = $this->m_config[$file['module']]['name'] . '->' . $file['name'] . ' (' . $name . '.php)';
        }

        $data['menus'] = array('' => 'Seçiniz');
        foreach ($this->navigation_model->get_groups() as $group)
        {
            $data['menus'][$group['tag']] = $group['title'] . ' (' . $group['tag'] . ' )';
        }
        $content_type = $this->input->post('type');
        $this->form_validation->set_rules('title', 'Başlık', 'trim|required');
        $this->form_validation->set_rules('module[]', 'Modül', 'required');
        $this->form_validation->set_rules('type', 'Tip', 'required');
        $this->form_validation->set_rules('location', 'Eklenecek Bölüm', 'required');
        $this->form_validation->set_rules('content-' . $content_type, 'İçerik', 'required');
        if ($this->form_validation->run())
        {
            $module = ($content_type == 'file') ? $this->block->config[$this->input->post('content-' . $content_type)]['module'] : 'all';
            $sql_data = array(
                'title' => $this->form_validation->set_value('title'),
                'location' => $this->form_validation->set_value('location'),
                'content' => $this->form_validation->set_value('content-' . $content_type),
                'type' => $this->input->post('type'),
                'module' => $module,
                'active' => $this->input->post('active'),
                'access_level' => $this->input->post('access_level'),
            );

            $this->block_model->add($sql_data, $this->input->post('module'));
            flash_message('success', 'Blok veritabanına eklendi.');
            redirect('admin/block/blocks');
        }
        $this->template->view('admin/add', $data);
        $this->template->load('admin_layout');
    }

    function update()
    {
        $id = $this->uri->rsegment(3);
        $data = $this->block_model->get($id);

        if (sizeof($data) < 1)
        {
            flash_message('error', 'Böyle bir blok bulunmuyor.');
            redirect('admin/block/blocks');
        }

        $data['files'] = array('' => 'Seçiniz');
        foreach ($this->block->block_files() as $name => $file)
        {
            $data['files'][$name] = $this->m_config[$file['module']]['name'] . '->' . $file['name'] . ' (' . $name . '.php)';
        }
        $data['menus'] = array('' => 'Seçiniz');
        foreach ($this->navigation_model->get_groups() as $group)
        {
            $data['menus'][$group['tag']] = $group['title'] . ' (' . $group['tag'] . ' )';
        }
        $content_type = $this->input->post('type');
        $this->form_validation->set_rules('title', 'Başlık', 'trim|required');
        $this->form_validation->set_rules('module[]', 'Modül', 'required');
        $this->form_validation->set_rules('type', 'Tip', 'required');
        $this->form_validation->set_rules('location', 'Eklenecek Bölüm', 'required');
        $this->form_validation->set_rules('content-' . $content_type, 'İçerik', 'required');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'id' => $id,
                'title' => $this->form_validation->set_value('title'),
                'location' => $this->form_validation->set_value('location'),
                'content' => $this->form_validation->set_value('content-' . $content_type),
                'type' => $this->input->post('type'),
                'active' => $this->input->post('active'),
                'access_level' => $this->input->post('access_level'),
            );
            $this->block_model->update($sql_data, $this->input->post('module'));
            flash_message('success', 'Blok güncellendi.');
            redirect('admin/block/blocks');
        }

        $this->template->view('admin/edit', $data);
        $this->template->load('admin_layout');
    }

    function delete()
    {
        $id = $this->uri->rsegment(3);
        $block = $this->block_model->get($id);

        if (sizeof($block) < 1)
            flash_message('error', 'Böyle bir blok bulunmuyor.');
        else if ($this->block_model->delete($id))
            flash_message('success', 'Blok silindi');
        else
            flash_message('error', 'Blok <b>silinemedi.</b>');

        redirect('admin/block/blocks');
    }

    function sort()
    {
        $module = $this->uri->rsegment(3);
        $data['modules'] = $this->block->get_all_blocks_by_module();
        $this->template->view('admin/sort', $data);
        $this->template->load('admin_layout');
    }

    function sort_save()
    {
        $sort = explode('-', $this->uri->rsegment(3));

        foreach ($sort as $position => $id)
        {
            $this->block_model->sort($id, $position);
        }
        $this->output->enable_profiler(FALSE);
        //@todo ajax message
        $data['message'] = '<span class="success" style="display:block">Sıralama kaydedildi.</span>';
        $this->load->view('system/message', $data);
    }

    function active()
    {
        $id = $this->uri->rsegment(3);
        $block = $this->block_model->get($id);
        $action = ($block['active'] == 0) ? 'activate' : 'unactivate';
        if (sizeof($block) < 1)
            $message = 'Üzgünüm, böyle bir blok yok.';
        else if ($this->block_model->active($id, $action))
            $message = 'İşlem gerçekleştirildi.';
        else
            $message = 'İşlem <b>gerçekleştirilemedi.</b>';

        $this->template->redir($message, 'admin/block/blocks');
        return;
    }

}

/* End of file block_admin.php */