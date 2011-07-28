<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sol menüdeki categoryleri listeler.
 * @author OŞA 15.07.2010 14:37
 * @param array $attr
 * @return string
 */
function get_category_ul($attr = array())
{
    $ci =& get_instance();
    $ci->load->model('category/category_model');
    
    $categories = $ci->category_model->get_categories_with_post_size();
    $category_array = array();
    foreach($categories as $category)
    {
        $category_array[] = anchor('category/'. $category['category_slug'], $category['category_title'], array('title' => $category['category_title'])).' ('.$category['posts'].')';
    }
    return ul($category_array, $attr);
}