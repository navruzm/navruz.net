<?php
return array(
    'module' => 'page',
    'name' => 'Sayfalar',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'admin' => array(
        'page' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Sayfalar',
            'menu_image' => 'page.png',
            'menu_items' => array(
                'index' => 'Sayfalar',
                'add' => 'Sayfa Ekle',
            )
        ),
    )
);