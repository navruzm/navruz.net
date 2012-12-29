<?php

function check_permission($module=NULL, $controller=NULL)
{

    $exclude = array('admin/admin', 'admin/dashboard');
    $ci = get_instance();
    if (!$ci->auth->is_user())
    {
        return FALSE;
    }
    $module = ($module === NULL) ? $ci->module : $module;
    $controller = ($controller === NULL) ? $ci->controller : $controller;
    $permissions = isset($ci->userdata['permissions']) ? $ci->userdata['permissions'] : FALSE;
    if ($permissions === FALSE)
    {
        if (in_array($module . '/' . $controller, $exclude))
        {
            return TRUE;
        }
        return NULL;
    }
    if (isset($permissions[':all:']) || isset($permissions[$module . '/' . $controller]) || in_array($module . '/' . $controller, $exclude))
    {
        return TRUE;
    }
    return NULL;
}

function get_module_has_admin_menu()
{
    $items = array();
    foreach (get_instance()->module_config->get() as $module)
    {
        if (isset($module['admin']))
        {
            foreach ($module['admin'] as $cname => $admin)
            {
                if (isset($admin['menu_text']))
                {
                    $module_name = ($module['name'] == $admin['menu_text']) ? $module['name'] : $module['name'] . '/' . $admin['menu_text'];
                    $items[] = array(
                        'module' => $module['module'],
                        'module_name' => $module_name,
                        'admin_controller_name' => $admin['controller_name'],
                    );
                }
            }
        }
    }
    return $items;
}