<?php

function check_permission($module=NULL, $controller=NULL)
{
    $exclude = array('admin/admin', 'admin/dashboard');

    $ci = & get_instance();

    $module = ($module === NULL) ? $ci->router->fetch_module() : $module;
    $controller = ($controller === NULL) ? $ci->router->fetch_class() : $controller;
    $permissions = $ci->session->userdata('permissions');
    if ($permissions === FALSE || !is_array($permissions))
    {
        if (in_array($module . '/' . $controller, $exclude))
            return TRUE;
        return FALSE;
    }
    if ((isset($permissions[$module . '/' . $controller])
            AND $permissions[$module . '/' . $controller] == 1)
            || in_array($module . '/' . $controller, $exclude))
        return TRUE;
    return FALSE;
}

function get_module_has_admin_menu()
{
    $ci = & get_instance();
    $module_config = $ci->module_config->get_module_config();
    $items = array();
    foreach ($module_config as $module)
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

/* End of file permission_helper.php */