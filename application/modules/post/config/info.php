<?php
/**
 * Module config
 */
return array(
    'module' => 'post',
    'name' => 'Yazılar',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'comments_enabled' => 1,
    'admin' => array(
        'post' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Yazılar',
            'menu_image' => 'posts.png',
            'menu_items' => array(
                'index' => 'Yazılar',
                'add_post' => 'Yazı Ekle',
                'sitemap' => 'Sitemap Güncelle',
            )
        ),
    )
);