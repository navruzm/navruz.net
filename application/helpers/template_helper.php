<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function add_css_inline($css)
{
    get_instance()->asset->add_css_inline($css);
}

function add_css_minify($css)
{
    get_instance()->asset->add_css_minify($css);
}

function add_css_link($css)
{
    get_instance()->asset->add_css_link($css);
}

function add_js_inline($js, $position='after')
{
    get_instance()->asset->add_js_inline($js, $position);
}

function add_js_minify($js)
{
    get_instance()->asset->add_js_minify($js);
}

function add_js_link($js, $type='file')
{
    get_instance()->asset->add_js_link($js, $type);
}

function add_js_jquery($js, $file=NULL)
{
    get_instance()->asset->add_js_jquery($js, $file);
}

/**
 * Meta kısmına eklenmek üzere anahtar kelime ekler.
 * @param string|array $keyword
 * @return void
 */
function set_keyword($keyword)
{
    get_instance()->template->set_keyword($keyword);
}

/**
 * Sayfa başlığını ekler.
 * @param string $title
 * @return void
 */
function set_title($title)
{
    get_instance()->template->set_title($title);
}

/**
 * Sayfa açıklamasını ekler.
 * @param string $description
 * @return void
 */
function set_description($description)
{
    get_instance()->template->set_description($description);
}

function add_more($more)
{
    get_instance()->template->add_more($more);
}

function add_breadcrumb($title, $url = NULL)
{
    return get_instance()->template->add_breadcrumb($title, $url = NULL);
}

function get_breadcrumb()
{
    return get_instance()->template->get_breadcrumb();
}

function flash_message($type, $message)
{
    $ci = get_instance();
    $_messages = $ci->session->userdata($ci->session->flashdata_key . ':new:messages');
    if (!is_array($_messages))
    {
        $_messages = array();
    }
    $_messages[] = array('type' => $type, 'message' => $message);
    $ci->session->set_flashdata('messages', $_messages);
}

function no_index()
{
    $CI = get_instance();
    $CI->output->set_header("HTTP/1.1 200 OK");
    $CI->output->set_header("Pragma: no-cache");
    $CI->template->add_meta(array(
        'name' => 'robots',
        'content' => 'noindex,noarchive'));
}

function get_themes()
{
    get_instance()->load->helper('directory');
    $themes = array();
    foreach (directory_map('themes') as $theme => $dummy)
    {
        if (is_array($dummy) && $theme != 'admin')
        {
            $themes[$theme] = $theme;
        }
    }
    return $themes;
}

function analytics_code()
{
    $analytic_id = get_option('analytics_id');
    if (!$analytic_id)
    {
        return;
    }
    $code = "
   var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$analytic_id}']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();";
    return add_js_inline($code, 'before');
}