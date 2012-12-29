<?php
return array(
    'module' => 'user',
    'name' => 'Üyeler',
    'version' => '0.1',
    'is_frontend' => 1,
    'is_backend' => 1,
    'admin' => array(
        'user' => array(
            'controller_name' => 'admin',
            'menu_text' => 'Üyeler',
            'menu_image' => 'user.png',
            'menu_items' => array(
                'index' => 'Üyeler',
                'add' => 'Üye Ekle',
            )
        ),
    )
);