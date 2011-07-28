<?php


function _redirect($old_slug, $model, $function, $uri = NULL, $prefix='/')
{
    $ci =& get_instance();
    $uri = ($uri == NULL) ? $ci->uri->uri_string() : $uri;
    $ci->load->library('redirect');
    if ($new_slug = $ci->redirect->get($old_slug))
    {
        $uri = str_replace($old_slug, $new_slug, $uri);
        $new_slug = str_replace($prefix, '', $new_slug);
        if ((int) $ci->$model->$function($new_slug) < 1)
        {
            _redirect($new_slug, $model, $function,$uri,$prefix);
        }
        else
        {
            redirect($uri, 'location', 301);
        }
    }
    else
    {
        return FALSE;
    }
}
