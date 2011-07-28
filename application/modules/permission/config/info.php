<?php
/**
 * Module config
 */
return array(
    'module' => 'permission',
    'name' => 'İzinler',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'permission' => array(
            'controller_name' => 'admin',
            'menu_text' => 'İzinler',
            'menu_image' => 'permission.png',
            'menu_items' => array(
                'index' => 'İzinleri Düzenle',
            )
        ),
    )
);