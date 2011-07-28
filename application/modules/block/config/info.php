<?php
return array(
    'module' => 'block',
    'name' => 'Bloklar',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'block' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Bloklar',
            'menu_image' => 'block.png',
            'menu_items' => array(
                'index' => 'Bloklar',
                'add' => 'Ekle',
                'sort' => 'Sırala',
            ),
        ),
    )
);