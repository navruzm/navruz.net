<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Css kodu ekler.
 * @param  string $css
 * @param string $type
 * @return void
 */
function add_css($css, $type = 'compress')
{
    $ci = & get_instance();
    $ci->template->add_css($css, $type);
}

/**
 * Javascript kodu ekler.
 * @param string $js
 * @param string $type
 * @return void
 */
function add_js($js, $type = 'compress')
{
    $ci = & get_instance();
    $ci->template->add_js($js, $type);
}

/**
 * Jquery kütühanesi kullanılarak yazılan kodları ekler.
 * @param string $js
 * @return void
 */
function add_jquery($js)
{
    $ci = & get_instance();
    $ci->template->add_jquery($js);
}

/**
 * Meta kısmına eklenmek üzere anahtar kelime ekler.
 * @param string|array $keyword
 * @return void
 */
function add_keyword($keyword)
{
    $ci = & get_instance();
    $ci->template->add_keyword($keyword);
}

/**
 * Sayfa başlığını ekler.
 * @param string $title
 * @return void
 */
function set_title($title)
{
    $ci = & get_instance();
    $ci->template->set_title($title);
}

/**
 * Sayfa açıklamasını ekler.
 * @param string $description
 * @return void
 */
function set_description($description)
{
    $ci = & get_instance();
    $ci->template->set_description($description);
}

/**
 * Ekstra head kodu ekler.
 * @param  $more
 * @return void
 */
function add_more($more)
{
    $ci = & get_instance();
    $ci->template->add_more($more);
}

/**
 * Template::minify_css();
 * @param  $code
 * @return void
 */
function minify_css($code)
{
    $ci = & get_instance();
    return $ci->template->minify_css($code);
}

/**
 * Template::minify_js();
 * @param  $code
 * @return void
 */
function minify_js($code)
{
    $ci = & get_instance();
    return $ci->template->minify_js($code);
}

function add_breadcrumb($title, $url = NULL)
{
    $ci = & get_instance();
    return $ci->template->add_breadcrumb($title, $url = NULL);
}

function get_breadcrumb()
{
    $ci = & get_instance();
    return $ci->template->get_breadcrumb();
}

function flash_message($type, $message)
{
    $ci = & get_instance();
    $_messages = $ci->session->userdata($ci->session->flashdata_key . ':new:messages');
    if (!is_array($_messages))
    {
        $_messages = array();
    }
    $_messages[] = array('type' => $type, 'message' => $message);
    $ci->session->set_flashdata('messages', $_messages);
}

