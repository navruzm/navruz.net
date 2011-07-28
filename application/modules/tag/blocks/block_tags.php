<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['tags'] = array(
    'name' => 'Etiketler',
    'is_public' => 1,
    'module' => 'tag',
);

function block_tags()
{
    $ci = & get_instance();
    $ci->load->helper('tag/tag');
    return '<div class="tag-cloud">' . get_tag_cloud_html(20, 1, 9, 'tag', 'tag/') . '</div>';
}
