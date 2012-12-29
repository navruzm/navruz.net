<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function categories($categories)
{
    if (count($categories) < 1)
    {
        return 'Kategori Yok';
    }
    $category_array = array();
    foreach (get_instance()->mongo_db->category->find(array('_id'=>array('$in'=>$categories))) as $category)
    {
        $category_array[] = anchor('category/' . $category['slug'], $category['title']);
    }
    return implode(', ', $category_array);
}

function tags($tags)
{
    if (count($tags) < 1)
    {
        return 'Etiket Yok';
    }
    $tag_array = array();
    foreach ($tags as $tag)
    {
        $tag_array[] = anchor('tag/' . $tag['slug'], $tag['tag']);
    }
    return implode(', ', $tag_array);
}

function post_image($image, $date, $title = '')
{
    $title = str_replace(array('"', '\''), '', $title);
    $image = ($image != '') ?  'post/image/160/120/' . $image : 'assets/img/default-post.png';
    $img_properties = array(
        'src' => $image,
        'alt' => $title,
        'width' => '160',
        'height' => '120',
        'class' => 'float_left post-image'
    );
    return img($img_properties);
}


/* End of file post_helper.php */