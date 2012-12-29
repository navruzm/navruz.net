<?php
return array(
    'module' => 'post',
    'name' => 'Yazılar',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'admin' => array(
        'post' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Yazılar',
            'menu_image' => 'post.png',
            'menu_items' => array(
                'index' => 'Yazılar',
                'add' => 'Yazı Ekle',
            )
        ),
    )
);