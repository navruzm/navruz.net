<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_admin_menu_array()
{
    $ci = & get_instance();
    $module_config = $ci->module_config->get_module_config();
    $menu_items = array();
    foreach ($module_config as $module)
    {
        if (array_key_exists('admin', $module) && count($module['admin']))
        {
            foreach ($module['admin'] as $name => $cf)
            {
                if (isset($cf['menu_text'])
                        AND check_permission($module['module'], $cf['controller_name'])
                )
                    $menu_items[] = array(
                        'module_name' => $name,
                        'controller_name' => $cf['controller_name'],
                        'menu_text' => $cf['menu_text'],
                        'menu_image' => $cf['menu_image'],
                        'menu_items' => $cf['menu_items'],
                    );
            }
        }
    }
    return $menu_items;
}

