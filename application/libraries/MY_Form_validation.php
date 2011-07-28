<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Valid Date 
     *
     */
    function valid_datetime($str)
    {
        $pattern = '/^([0-3][0-9])-([0-1][0-9])-([0-9]{2,4}) ([0-2][0-9]):([0-5][0-9])?$/';
        return (preg_match($pattern, $str) === 1) ? TRUE : FALSE;
    }

    /**
     * Valid Url
     *
     */
    function valid_url($str)
    {
        return (filter_var($str, FILTER_VALIDATE_URL)) ? TRUE : FALSE;
    }

}

