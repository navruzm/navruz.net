<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


function generate_sitemap()
{
    $ci = & get_instance();

    $ci->load->library(array('xml_sitemap'));
    $ci->load->model(array(
        'post/post_model',
    ));

    $items = array(
        array(
            'slug' => '',
            'created_on' => time()+5,
            'updated_on' => time()+5,
            'changefreq' => 'daily',
            'priority' => '1',
        )
    );
    $ci->xml_sitemap->add($items);
    //news
    $posts = $ci->post_model->get_post_for_sitemap();
    $ci->xml_sitemap->add($posts);

    return $ci->xml_sitemap->generate();
}

