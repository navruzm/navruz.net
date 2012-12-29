<?php
return array(
    'module' => 'admin',
    'name' => 'Yönetim',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'admin' => array(
        'dashboard' => array(
            'controller_name' => 'dashboard',
        ),
        'options' => array(
            'controller_name' => 'options',
            'menu_text' => 'Ayarlar',
            'menu_image' => 'options.png',
            'menu_items' => array(
                'index' => 'Ayarlar',
                'clear_cache' => 'Önbelleği Temizle',
                'database' => 'Veritabanı İşlemleri',
            )
        )
    )
);