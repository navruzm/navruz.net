<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('form_open'))
{
    function form_open($action = '', $attributes = '', $hidden = array())
    {
        $CI =& get_instance();

        $name = $CI->controller . '-' . $CI->method;

        if ($attributes == '')
        {
            $attributes = 'method="post"';
        }

        if (is_string($attributes))
        {
            if (strpos($attributes, 'name=') === FALSE)
            {
                $attributes .= ' name="' . $name . '"';
            }
        }
        else
        {
            if (!isset($attributes['name']))
            {
                $attributes['name'] = $name;
            }
        }

        // If an action is not a full URL then turn it into one
        if ($action && strpos($action, '://') === FALSE)
        {
            $action = $CI->config->site_url($action);
        }

        // If no action is provided then set to the current url
        $action OR $action = $CI->config->site_url($CI->uri->uri_string());

        $form = '<form action="'.$action.'"';

        $form .= _attributes_to_string($attributes, TRUE);

        $form .= '>';

        // Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
        if ($CI->config->item('csrf_protection') === TRUE AND ! (strpos($action, $CI->config->site_url()) === FALSE OR strpos($form, 'method="get"')))
        {
            $hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
        }

        if (is_array($hidden) AND count($hidden) > 0)
        {
            $form .= sprintf("<div style=\"display:none\">%s</div>", form_hidden($hidden));
        }

        return $form;
        $CI =& get_instance();


        return form_open($action, $attributes, $hidden);
    }
}

/**
 *
 * @param string $id
 * @param string $label
 * @param string $type
 * @param array $attributes
 * @return string
 */
function form_item($id, $label, $type = 'input', $attributes = array())
{
    $attributes = array_merge(array(
        'name' => $id,
        'id' => str_replace(array('[', ']'), '', $id),
        'value' => set_value($id),
        'class' => 'xxlarge',
    ), $attributes);
    $error = form_error($attributes['id'], '<span class="help-inline">', '</span>');
    $class = ($error == '') ? '' : ' error';
    $retval = '<div class="clearfix' . $class . '">';
    $retval .= form_label($label, $attributes['id']);
    $retval .= '<div class="input">';
    $retval .= call_user_func('form_' . $type, $attributes);
    $retval .= $error;
    $retval .= '</div>';
    $retval .= '</div>';

    return $retval;
}

function set_value($field = '', $default = '')
{
    if (!isset($_POST[$field]))
    {
        return $default;
    }

    if (FALSE === ($OBJ = & _get_validation_object()))
    {
        return form_prep($_POST[$field], $field);
    }

    return form_prep($OBJ->set_value($field, $_POST[$field]), $field);
}