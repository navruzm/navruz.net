<?php
/**
 * Module config
 */
return array(
    'module' => 'user',
    'name' => 'Üyeler',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'comments_enabled' => 0,
    'admin' => array(
        'user' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Üyeler',
            'menu_image' => 'user.png',
            'menu_items' => array(
                'add' => 'Üye Ekle',
                'list_user' => 'Üyeler',
                'list_admin' => 'Yöneticiler',
                'groups' => 'Gruplar',
                'group_add' => 'Grup Ekle',
                'change_pass' => 'Şifrenizi Değiştirin'
            )
        ),
    )
);