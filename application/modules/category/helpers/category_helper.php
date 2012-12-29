<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_category_ul($attr = array())
{
    $category_array = array();
    foreach (get_instance()->mongo_db->category->find()->sort(array('order' => 1)) as $category)
    {
        $category_array[] = anchor('category/' . $category['slug'], $category['title']);
    }
    return ul($category_array, $attr);
}