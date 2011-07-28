<?php
/**
 * Module config
 */
return array(
    'module' => 'page',
    'name' => 'Sayfalar',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'page' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Sayfalar',
            'menu_image' => 'pages.png',
            'menu_items' => array(
                'index' => 'Sayfalar',
                'add_page' => 'Sayfa Ekle',
            )
        ),
    )
);