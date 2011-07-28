<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function get_navigation($tag, $class=NULL)
{
    if (is_admin ())
    {
        $levels = array(0, 2, 3);
    }
    elseif (is_user ())
    {
        $levels = array(0, 2);
    }
    else
    {
        $levels = array(0, 1);
    }
    $ci = & get_instance();
    $ci->load->model('navigation/navigation_model');
    $group = $ci->navigation_model->get_group_by_tag($tag);
    $links = $ci->navigation_model->get_links($group['id'], $levels);
    $link_array = array();
    foreach ($links as $link)
    {
        $attr = '';
        $_link = explode('/', $link['link']);
        if ($ci->module == trim($_link[0], '/')||  strstr($_link[0], $ci->module))
            $attr = ' class="current"';
        $link_array[] = '<li' . $attr . '>' . anchor($link['link'], $link['title']) . '</li>';
    }
    $style = ($class === NULL) ? '' : ' class="' . $class . '"';
    return '<ul' . $style . '>' . implode("\n\r", $link_array) . '</ul>';
}