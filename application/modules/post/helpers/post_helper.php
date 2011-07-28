<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function categories($categories)
{
    if (count($categories) < 1)
        return 'Kategori Yok';
    $category_array = array();
    foreach ($categories as $category)
    {
        $category_array[] = anchor('category/' . $category['category_slug'], $category['category_title']);
    }
    return implode(', ', $category_array);
}

function post_image($image, $date, $title = '')
{
    $title = str_replace(array('"', '\''), '', $title);
    $image = ($image != '') ? config_item('post_upload_path') . date('Y', $date) . '/' . date('m', $date) . '/' . $image : 'assets/img/default-post.png';
    $img_properties = array(
        'src' => $image,
        'alt' => $title,
        'width' => '160',
        'height' => '120',
        'class' => 'float_left post-image'
    );
    return img($img_properties);
}

function image_path($date)
{
    return config_item('post_images_upload_path') . date('Y', $date) . '/' . date('m', $date) . '/';
}

function tags($tags)
{
    if(count($tags) < 1)
        return 'Etiket Yok';
    $tag_array = array();
    foreach($tags as $tag)
    {
        $tag_array[] = anchor('tag/' . $tag['tag'], $tag['raw_tag']);
    }
    return implode(', ', $tag_array);
}



function related($id)
{
    $ci = & get_instance();
    $objects = $ci->tags->similar_objects($id);
    if(count($objects) > 0)
    {
        $id_array = array();

        foreach($objects as $post)
        {
            if($post['object_id'] != $id)
                $id_array[] = $post['object_id'];
        }
        if(count($id_array) == 0)
            return 'Benzer Yazı Bulunamadı.';
        $posts = $ci->post_model->related($id_array);
        $post_list = array();

        foreach($posts as $post)
        {
            $post_list[] = anchor($post['slug'], $post['title'], array('title' => htmlspecialchars($post['title'])));
        }
        return ul($post_list);
    }
    return 'Benzer yazı bulunamadı!';
}
/* End of file post_helper.php */