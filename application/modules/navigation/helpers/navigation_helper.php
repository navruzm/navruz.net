<?php

function get_navigation($tag, $class=NULL)
{
    $ci = get_instance();
    if ($ci->auth->is_admin())
    {
        $levels = array(0, 2, 3);
    }
    elseif ($ci->auth->is_user())
    {
        $levels = array(0, 2);
    }
    else
    {
        $levels = array(0, 1);
    }
    $group = $ci->mongo_db->navigation->findOne(array('slug' => $tag));
    if (!isset($group['items']))
    {
        return 'Menü Bulunamadı.';
    }
    $link_array = array();
    foreach ($group['items'] as $link)
    {
        if (!in_array($link['access_level'], $levels))
        {
            continue;
        }
        $attr = '';
        $_link = explode('/', $link['url']);
        if ($ci->module == trim($_link[0], '/') || strstr($_link[0], $ci->module))
        {
            $attr = ' class="current"';
        }
        $link_array[] = '<li' . $attr . '>' . anchor($link['url'], $link['title']) . '</li>';
    }
    $style = ($class === NULL) ? '' : ' class="' . $class . '"';
    return '<ul' . $style . '>' . implode("\n\r", $link_array) . '</ul>';
}