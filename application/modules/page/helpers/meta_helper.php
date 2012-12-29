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
        return $page['title'].' '.word_limiter(strip_tags($page['content']), 10,'').' '.tr_date('d F Y', $page['created_at']->sec);
    }
    return $page['meta_description'];
}

function get_keyword($page)
{
    if(!$page['meta_keyword'])
    {
        return word_limiter($page['title'], 2);
    }
    return $page['meta_keyword'];
}
