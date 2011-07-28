<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

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
class File extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        log_message('debug', 'File Controller Initialized');
        $this->load->model('file_model');
        $this->load->helper(array('download', 'file'));
        $this->load->library('user_agent');
    }
    
    function index()
    {
        $file_name = $this->uri->rsegment(3);
        $file = $this->file_model->get_file_by_name($file_name);
       // show_error($this->db->last_query());
        if($file_name === FALSE || !array_key_exists('file_id', $file))
        {
            show_404('file/' . $file_name);
        }
        $data = read_file('media/files/' . $file['file_name']);
        if($data !== FALSE)
        {
            if(!$this->agent->is_robot())
            {
                $this->file_model->increase_count($file['file_id']);
            }
            
            force_download($file_name, $data);
        }
        else
        {
            show_404('media/file/' . $file_name);
        }
    }
}

/* End of file file.php */