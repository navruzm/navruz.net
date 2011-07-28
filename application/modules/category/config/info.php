<?php
/**
 * Module config
 */
return array(
    'module' => 'category',
    'name' => 'Kategoriler',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'category' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Kategoriler',
            'menu_image' => 'categories.png',
            'menu_items' => array(
                'category_list' => 'Kategoriler',
                'add_category' => 'Kategori Ekle',
                'sort_category' => 'Kategorileri Sırala')
        ),
    )
);