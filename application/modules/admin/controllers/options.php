<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Options extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index()
    {
        $data = array();
        $this->form_validation->set_rules('site_name', 'Site Adı', 'trim|required');
        $this->form_validation->set_rules('site_email', 'E-Posta', 'trim|required|valid_email');
        if ($this->form_validation->run())
        {
            unset($_POST['submit']);
            foreach ($_POST as $key => $value)
            {
                set_option($key, $value);
            }
            flash_message('success', 'Ayarlar güncellendi.');
            redirect($this->uri->uri_string());
        }
        $this->template->view('admin/admin/options', $data)->render();
    }

    function debug()
    {
        if (get_option('debug') == 1)
        {
            set_option('debug', 0);
            $message = 'Hata ayıklama modu kapatıldı.';
        }
        else
        {
            set_option('debug', 1);
            $message = 'Hata ayıklama modu açıldı.';
        }
        $this->template->redir($message, '');
    }

    public function clear_cache()
    {
        $this->load->helper('file');
        $data['directories'] = array(
            'image' => 'İmaj',
            '' => 'Genel',
            'min' => 'Minify'
        );
        $data['status'] = array();
        foreach ($data['directories'] as $directory => $message)
        {
            $data['status'][$message . ' (' . $directory . ')'] = delete_files(config_item('cache_path') . $directory);
        }
        $this->template->view('admin/admin/clear_cache', $data)->render();
    }

    public function database()
    {
        $this->template->view('admin/admin/database')->render();
    }

    public function database_export()
    {
        $this->load->library('mongo_export');
        $this->load->library('zip');
        $export_data = array();
        $collections = array();
        foreach ($this->mongo_db->db('listCollections') as $collection)
        {
            $collections[] = $collection->getName();
        }

        foreach ($collections as $collection)
        {
            foreach ($this->mongo_db->$collection->getIndexInfo() as $info)
            {
                $options = array();
                if (isset($info["unique"]))
                {
                    $options["unique"] = $info["unique"];
                }
                $export_data[] = "\n/** {$collection} indexes **/\ndb.getCollection(\""
                    . addslashes($collection) . "\").ensureIndex("
                    . $this->mongo_export->export($info["key"]) . ","
                    . $this->mongo_export->export($options) . ");\n";
            }
        }

        foreach ($collections as $collection)
        {
            $export_data[] = "\n/** " . $collection . " records **/";
            $export_data[] = "\ndb.getCollection(\"" . addslashes($collection) . "\").drop();\n";
            foreach ($this->mongo_db->$collection->find() as $one)
            {
                $export_data[] = "db.getCollection(\"" . addslashes($collection) . "\").insert(" . $this->mongo_export->export($one) . ");\n";
            }
        }
        $this->zip->add_data('backup.json', implode("", $export_data));
        foreach ($this->mongo_db->gridfs->find() as $file)
        {
            $this->zip->add_data('image/' . $file->file['filename'], $file->getBytes());
        }
        $this->zip->download('backup' . date('y_m_d_H_i_') . '.zip');
    }

    public function database_import()
    {
        if (!empty($_FILES["file"]["tmp_name"]))
        {
            $this->load->library('unzip');
            $this->load->helper('file');
            $this->load->helper('directory');
            $this->unzip->extract($_FILES["file"]["tmp_name"], config_item('cache_path') . 'tmp/');
            $body = file_get_contents(config_item('cache_path') . 'tmp/backup.json');
            $result = $this->mongo_db->db('execute', array('function (){ ' . $body . ' }'));
            if ($result['ok'] == 0)
            {
                $body = str_replace('ISODate', 'new Date', $body);
                $result = $this->mongo_db->db('execute', array('function (){ ' . $body . ' }'));
            }
            if ($result['ok'] == 1)
            {
                foreach (directory_map(config_item('cache_path') . 'tmp/image') as $file)
                {
                    $image = $this->mongo_db->gridfs->findOne($file);
                    $this->mongo_db->gridfs->delete($image->file['_id']);
                    $this->mongo_db->gridfs->storeFile(config_item('cache_path') . 'tmp/image/' . $file, array('filename' => $image->file['filename'], 'type' => $image->file['type']));
                }
                delete_files(config_item('cache_path') . 'tmp/', TRUE);
                flash_message('success', 'Veritabanı yüklendi.');
            }
            else
            {
                flash_message('error', 'Veritabanı yüklenemedi.');
            }
        }
        else
        {
            flash_message('error', 'Veritabanı dosyası yüklenemedi.');
        }
        redirect('admin/options/database');
    }
}

/* End of file options.php */