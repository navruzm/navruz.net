<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_option($option)
{
    return get_instance()->option->item($option);
}

function set_option($name, $value)
{
    return get_instance()->option->set_item($name, $value);
}
