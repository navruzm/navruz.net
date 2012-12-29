<?php
return array(
    'module' => 'navigation',
    'name' => 'Menüler',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'admin' => array(
        'navigation' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Menüler',
            'menu_image' => 'menu.png',
            'menu_items' => array(
                'index' => 'Menüler',
                'add' => 'Menü Ekle',
            )
        ),
    )
);