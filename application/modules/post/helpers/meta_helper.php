<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

function get_title($post)
{
    if(!$post['meta_title'])
    {
        return $post['title'];
    }
    return $post['meta_title'];
}

function get_description($post)
{
    if(!$post['meta_description'])
    {
        return $post['title'].' '.word_limiter(strip_tags($post['content']), 10,'').' '.tr_date('d F Y', $post['created_at']->sec);
    }
    return $post['meta_description'];
}

function get_keyword($post)
{
    if(!$post['meta_keyword'])
    {
        return word_limiter($post['title'], 2);
    }
    return $post['meta_keyword'];
}
