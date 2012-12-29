<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Image extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mongo_db');
    }

    public function index($width, $height, $id)
    {

        $image = $this->mongo_db->gridfs->findOne($id);
        if (is_null($image))
        {
            show_404();
        }

        if (in_array($image->file['type'], array('image/jpeg', 'image/pjpeg')))
        {
            $ext = 'jpg';
        }
        elseif (in_array($image->file['type'], array('image/png', 'image/x-png')))
        {
            $ext = 'png';
        }
        else
        {
            show_error('This is not an image');
        }
        $file = config_item('cache_path') . 'image/' . md5($width . $height . $id) . '.' . $ext;
        if (!file_exists($file))
        {
            $this->load->library('image_lib');
            try
            {
                $image->write(config_item('cache_path') . 'image/' . md5($id));
            }
            catch (MongoGridFSException $e)
            {
                show_error("MongoDB connection failed: {$e->getMessage()}", 500);
            }

            $config['image_library'] = 'gd2';
            $config['source_image'] = config_item('cache_path') . 'image/' . md5($id);
            $config['maintain_ratio'] = TRUE;
            $config['new_image'] = $file;
            $size = getimagesize($config['source_image']);
            $ratio = $size[1] / $size[0];
            $config['width'] = $width;
            $config['height'] = $width * $ratio;
            if ($config['height'] < $height)
            {
                $config['width'] = $height / $ratio;
                $config['height'] = $height;
            }
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
            $config['source_image'] = $config['new_image'];
            $config['new_image'] = '';
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $width;
            $config['height'] = $height;
            $config['x_axis'] = 0;
            $config['y_axis'] = 0;
            $this->image_lib->initialize($config);
            $this->image_lib->crop();
            $this->image_lib->clear();
            unlink(config_item('cache_path') . 'image/' . md5($id));
        }
        echo $this->display($file,$image->file['type']);
        die;
    }

    function display($file, $type='image/jpeg')
    {
        $mod_time = filemtime($file);
        $headers = $this->get_request_headers();
        if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $mod_time))
        {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mod_time) . ' GMT', true, 304);
        }
        else
        {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mod_time) . ' GMT', true, 200);
            header('Content-type: ' . $type);
            header('Content-transfer-encoding: binary');
            header('Content-length: ' . filesize($file));
            readfile($file);
        }
    }

    function get_request_headers()
    {
        if (function_exists("apache_request_headers"))
        {
            $headers = apache_request_headers();
            if ($headers)
            {
                return $headers;
            }
        }
        $headers = array();
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
        {
            $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
        }
        return $headers;
    }

}

/* End of file image.php */