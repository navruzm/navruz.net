<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @property CI_DB_active_record $db
 * @property CI_Form_validation $form_validation
 * @property CI_Loader $load
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_URI $uri
 * @property Template $template
 * @property Redirect $redirect
 *
 * @property File_Model $file_model
 *
 */
class Admin extends Admin_Controller {

    private $file = FALSE;
    private $file_error = FALSE;

    function __construct()
    {
        parent::__construct();
        log_message('debug', 'Admin Controller Initialized');
        access_control();
        $this->load->library('admin_menu');
        $this->load->model('file_model');
    }

    public function index()
    {
        $this->load->library('pagination');
        $data['pagination'] = $this->pagination->init('admin/file/index', $this->db->count_all('files'), 3, get_option('per_page_admin'));
        $data['files'] = $this->file_model->get_files(
                        get_option('per_page_admin'),
                        $this->pagination->get_offset()
        );

        $this->template->view('admin/list_files', $data);
        $this->template->load('admin_layout');
    }

    /**
     * Yeni dosya ekler.
     *
     * @return void
     */
    public function add_file()
    {
        $this->_upload_file();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('file_title', 'Dosya Adı', 'trim|required');
        $this->form_validation->set_rules('file', 'Dosya', 'callback__check_file');
        if ($this->form_validation->run())
        {
            $sql_data = array(
                'file_title' => $this->form_validation->set_value('file_title'),
                'file_name' => $this->file['file_name'],
                'file_size' => $this->file['file_size'],
                'file_download_count' => 0,
                'file_date_add' => date('Y-m-d H:i:s'),
            );
            $data['message'] = message_box('Eklendi', 'success-box');
            $file_id = $this->file_model->add_file($sql_data);
            $this->template->view('system/message', $data);
            $this->template->load('admin_layout');
            return;
        }

        $this->template->view('admin/add_file');
        $this->template->load('admin_layout');
    }

    /**
     * Mevcut yazıyı düzenler.
     *
     * @return void
     */
    public function edit_file()
    {
        $file_id = $this->uri->rsegment(3);
        $data = $this->file_model->get_file($file_id);

        if ($data === NULL)
        {
            die('Üzgünüm, böyle bir dosya bulunmuyor');
        }
        $this->_upload_file();
        $this->load->library(array('upload', 'form_validation'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('file_title', 'Dosya Adı', 'trim|required');
        if ($this->form_validation->run())
        {
            $sql_data['file_title'] = $this->form_validation->set_value('file_title');

            if (is_array($this->file) && count($this->file))
            {
                $sql_data['file_name'] = $this->file['file_name'];
                $sql_data['file_size'] = $this->file['file_size'];
                @unlink('media/files/' . $data['file_name']);
            }

            $this->file_model->update_file($file_id, $sql_data);
            $data['message'] = message_box('Dosya düzenlendi', 'success-box');
            $this->template->view('system/message', $data);
            $this->template->load('admin_layout');
            return;
        }

        $this->template->view('admin/edit_file', $data);
        $this->template->load('admin_layout');
    }

    /**
     * İlgili yazıyı siler.
     *
     * @return void
     */
    public function delete_file()
    {
        $data = array();
        $file_id = $this->uri->rsegment(3);
        $file = $this->file_model->get_file($file_id);
        if (sizeof($file) < 1)
        {
            $data['message'] = message_box('Böyle bir dosya bulunmuyor.', 'error-box');
        }
        else if ($this->file_model->delete_file($file_id))
        {
            if ($file['file_name'] != '')
            {
                if (@unlink('media/files/' . $file['file_name']))
                    $data['message'] = message_box('Dosya silindi.', 'success-box');
            }
        }
        else
        {
            $data['message'] = message_box('Dosya <b>silinemedi.</b>', 'error-box');
        }
        $this->template->view('system/message', $data);
        $this->template->load('admin_layout');
    }

    function _check_file($str)
    {
        if ($_FILES['file']['name'] == '')
        {
            $this->form_validation->set_message('_check_file', 'Yüklenecek dosya seçmediniz.');
            return FALSE;
        }
        else
        {
            if ($this->file !== FALSE)
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('_check_file', $this->file_error);
                return FALSE;
            }
        }
    }

    private function _upload_file()
    {
        if (isset($_FILES['file']) AND $_FILES['file']['name'] != '')
        {
            $this->load->library('upload');
            $config['upload_path'] = 'media/files/';
            $config['allowed_types'] = 'zip|php|html|css|htm|txt';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file'))
            {
                $this->file_error = $this->upload->display_errors();
            }
            else
            {
                $this->file = $this->upload->data();
            }
        }
    }

}

/* End of file admin.php */