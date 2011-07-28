<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Keep all flashdata
 *
 * @author Mustafa Navruz
 * @return void
 */
function keep_all_flashdata()
{
    $ci = & get_instance();
    foreach ($ci->session->all_userdata() as $key => $value)
    {
        if (strpos($key, 'flash:old:') !== FALSE)
        {
            $key = str_replace('flash:old:', '', $key);
            $ci->session->keep_flashdata($key);
        }
    }
}

