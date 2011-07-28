<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
                'id' => $id,
                'value' => set_value($id),
                    ), $attributes);
    $retval = form_label($label, $attributes['id']);
    $retval .= call_user_func('form_' . $type, $attributes);
    $retval .= form_error($attributes['id']);

    return $retval;
}
