<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permissions {

    private $ci;

    public function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->model('permissions_model');
    }

    public function get($user_id)
    {
        
    }
    
}

/* End of file Permissions.php */