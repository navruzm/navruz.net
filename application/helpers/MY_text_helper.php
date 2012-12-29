<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function cut_string($string, $len, $add = '.', $cutter = '. ')
{
    if (strlen($string) > $len)
    {
        $string = substr($string, 0, $len + strlen($add));
        $last = (empty($cutter)) ? $len : strrpos($string, $cutter);
        $last = (empty($last)) ? $len : $last;
        $string = trim(substr($string, 0, $last)) . $add;
    }
    return $string;
}

/* End of file MY_text_helper.php */
/* Location: ./app/helpers/MY_text_helper.php */