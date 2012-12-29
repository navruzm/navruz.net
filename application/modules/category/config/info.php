<?php
return array(
    'module' => 'category',
    'name' => 'Kategoriler',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'admin' => array(
        'category' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Kategoriler',
            'menu_image' => 'category.png',
            'menu_items' => array(
                'index' => 'Kategoriler',
                'add' => 'Kategori Ekle',
                )
        ),
    )
);
