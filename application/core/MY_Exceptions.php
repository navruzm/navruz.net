<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{

    function __construct()
    {
        parent::__construct();
    }

    function show_404($page='', $log_error=TRUE)
    {

        $ci = & get_instance();

        if ($log_error)
        {
            log_message('error', '404 Page Not Found --> ' . $page);
        }
        set_status_header(404);

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        ob_start();
        include(APPPATH . 'views/system/error' . EXT);
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
        exit;
    }

}

