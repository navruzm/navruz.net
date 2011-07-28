<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function parse_smileys($str = '', $image_url = '', $smileys = NULL)
{
    if ($image_url == '')
    {
        return $str;
    }

    if (!is_array($smileys) AND (FALSE === ($smileys = _get_smiley_array())))
    {
        return $str;
    }
    // Add a trailing slash to the file path if needed
    $image_url = preg_replace("/(.+?)\/*$/", "\\1/", $image_url);
    foreach ($smileys as $key => $val)
    {
        $str = str_replace($key, "<img src=\"" . $image_url . $smileys[$key][0] . "\" width=\"" . $smileys[$key][1] . "\" height=\"" . $smileys[$key][2] . "\" alt=\"" . $smileys[$key][3] . "\" class=\"smiley\"/>", $str);
    }

    return $str;
}
