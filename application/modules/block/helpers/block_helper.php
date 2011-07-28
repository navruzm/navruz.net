<?php

function get_block($location)
{
    $ci = & get_instance();
    return $ci->block->get_blocks_by_module($location);
}

function css_class_name()
{
    $ci = & get_instance();
    return $ci->block->css_class_name();
}
