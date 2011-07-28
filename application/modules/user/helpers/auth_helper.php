<?php

/**
 * Auth Helper
 *
 * @package		Auth
 * @author		Mustafa Navruz
 * @copyright		Copyright (c) 2010
 * @link                http://www.navruz.net
 * @since		Version 1.1
 */

/**
 * Check if user admin
 * @return boolean
 */
function is_admin()
{
    $CI = & get_instance();
    return $CI->auth->is_admin();
}

/**
 * User is logged in?
 * @return boolean
 */
function is_user()
{
    $CI = & get_instance();
    return $CI->auth->is_logged_in();
}

/**
 * Get current user id
 * @return int
 */
function get_user_id()
{
    $CI = & get_instance();
    return $CI->auth->get_user_id();
}

/**
 * Get current username
 * @return string
 */
function get_username()
{
    $CI = & get_instance();
    return $CI->auth->get_username();
}

/**
 * Get current user e-mail
 * @return string
 */
function get_user_email()
{
    $CI = & get_instance();
    return $CI->auth->get_user_email();
}

/**
 * User access control.
 */
function access_control()
{
    $ci = & get_instance();
    $ci->load->helper('permission/permission');
    $exclude = array('admin/admin', 'admin/dashboard');
    $module = $ci->router->fetch_module();
    $controller = $ci->router->fetch_class();

    if (in_array($module . '/' . $controller, $exclude))
    {
        return;
    }
    elseif (!$ci->auth->is_admin())
    {
        $ci->session->set_flashdata('redir', $ci->uri->uri_string());
        redirect('admin');
    }
    else
    {
        /* if (!is_array($CI->session->userdata('permissions')))
          {
          redirect('admin/dashboard');
          } */
        if (!check_permission())
        {
            $ci->template->redir('Bu sayfayı görme yetkiniz yok.', 'admin/dashboard');
            return;
        }
    }
}

/**
 * Get user group
 * @param int $group_id
 * @return array
 */
function get_user_group($group_id)
{
    $CI = & get_instance();
    return $CI->users->get_group($group_id);
}

function user_anchor($user)
{
    //@todo grupları bir kere başta çek...
    $group = get_user_group($user['user_group']);
    return anchor('user/' . $user['username'], $user['username'], 'class="' . $group['color'] . '"');
}

function user_image($username, $attr = array())
{
    $default = site_url() . 'assets/img/gravatar.jpg';
    if (file_exists(config_item('avatar_upload_path') . $username . '.jpg'))
    {
        $img_url = 'usr-img/' . $username . '.jpg';
    }
    else
    {
        $img_url = $default;
    }
    $image_properties = array(
        'src' => $img_url,
        'alt' => $username,
        'width' => '50',
        'height' => '50',
        'class' => 'float_left'
    );
    if (!is_array($attr))
        $attr = (array) $attr;
    $image_properties = array_merge($image_properties, $attr);

    return img($image_properties);
}

function user_list($users)
{
    add_js('js');
    add_js('tipsy');
    add_css('tipsy');
    add_jquery('$(".userr").tipsy({fade: true,gravity: "s"});');
    $_users = array();
    foreach ($users as $user)
    {
        $user['name'] = ($user['name'] == '') ? $user['username'] : $user['name'];
        $box = anchor('user/' . $user['username'], user_image($user['username'], array('class' => '')), 'class="userr" title="' . $user['name'] . '"');
        $_users[] = '<div class="user">' . $box . '</div>';
    }
    return '<div class="clearfix">'
    . implode("\n", $_users)
    . '</div>';
}

function last_users($limit)
{
    $ci = & get_instance();
    $users = $ci->users->get_last_users($limit);
    return user_list($users);
}

/* End of file auth_helper.php */
/* Location: ./application/modules/user/helpers/auth_helper.php */