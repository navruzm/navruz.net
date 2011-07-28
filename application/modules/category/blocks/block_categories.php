<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['categories'] = array(
    'name' => 'Kategoriler',
    'is_public' => 0,
    'module' => 'category',
);

function block_categories()
{
    $ci = & get_instance();
    $ci->load->helper('category/category');
    return get_category_ul(array('class'=>'categories'));
}
