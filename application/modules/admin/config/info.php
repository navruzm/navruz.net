<?php
return array(
    'module' => 'admin',
    'name' => 'Yönetim',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'dashboard' => array(
            'controller_name' => 'dashboard',
        ),
        'options_admin' => array(
            'controller_name' => 'options_admin',
            'menu_text' => 'Ayarlar',
            'menu_image' => 'options.png',
            'menu_items' => array(
                'index' => 'Ayarlar'
            ),
        ),
        'database' => array(
            'controller_name' => 'database',
            'menu_text' => 'Veritabanı',
            'menu_image' => 'database.png',
            'menu_items' => array(
                'index' => 'Yedekle',
                'optimize' => 'Veritabanını İyileştir'
            ),
        ),
        'clear_cache' => array(
            'controller_name' => 'clear_cache',
            'menu_text' => 'Önbelleği Temizle',
            'menu_image' => 'clear.png',
            'menu_items' => array(
                '' => 'Önbelleği Temizle'
            ),
        ),
    )
);