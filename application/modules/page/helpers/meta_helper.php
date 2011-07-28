<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

function get_title($page)
{
    if(!$page['meta_title'])
    {
        return $page['title'];
    }
    return $page['meta_title'];
}

function get_description($page)
{
    if(!$page['meta_description'])
    {
        return $page['title'];
    }
    return $page['meta_description'];
}

function get_keywords($page)
{
    $ci = & get_instance();
    $ci->load->helper('text');
    if(!$page['meta_keywords'])
    {
        return word_limiter($page['title'], 2);
    }
    return $page['meta_keywords'];
}
