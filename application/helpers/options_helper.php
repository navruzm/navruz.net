<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_option($option)
{
    $ci =& get_instance();
    //$ci->load->library('options');
    return $ci->options->item($option);
}

function set_option($name, $value, $insert = FALSE)
{
    $ci =& get_instance();
    return $ci->options->set_item($name, $value, $insert);
}

function install()
{
    set_option('site_name', 'Navruz.net', TRUE);
    set_option('site_keywords', 'kelime', TRUE);
    set_option('site_description', 'Açıklama', TRUE);
    set_option('site_email', 'mustafanavruz@gmail.com', TRUE);
    set_option('per_page', '5', TRUE);
    set_option('google_verify', '', TRUE);
    set_option('yahoo_verify', '', TRUE);
    set_option('bing_verify', '', TRUE);
    set_option('debug', '0', TRUE);
    //set_option('case_sensitive', '', TRUE);
}