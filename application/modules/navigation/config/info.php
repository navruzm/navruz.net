<?php
/**
 * Module config
 */
return array(
    'module' => 'navigation',
    'name' => 'Menüler',
    'version' => '0.1',
    'is_frontend' => 0,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'navigation' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Menü Yönetimi',
            'menu_image' => 'menu.png',
            'menu_items' => array(
                'groups' => 'Menü Grupları',
                'add_group' => 'Menü Grubu Ekle',
            )
        ),
    )
);