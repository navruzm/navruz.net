<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function tr_date($datestr, $date, $short = FALSE)
{
    $replace = array(
        'January' => 'Ocak',
        'February' => 'Şubat',
        'March' => 'Mart',
        'April' => 'Nisan',
        'May' => 'Mayıs',
        'June' => 'Haziran',
        'July' => 'Temmuz',
        'August' => 'Ağustos',
        'September' => 'Eylül',
        'October' => 'Ekim',
        'November' => 'Kasım',
        'December' => 'Aralık',
        'Monday' => 'Pazartesi',
        'Tuesday' => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday' => 'Perşembe',
        'Friday' => 'Cuma',
        'Saturday' => 'Cumartesi',
        'Sunday' => 'Pazar',
    );
    $short_replace = array(
        'January' => 'Oca',
        'February' => 'Şub',
        'March' => 'Mar',
        'April' => 'Nis',
        'May' => 'May',
        'June' => 'Haz',
        'July' => 'Tem',
        'August' => 'Ağu',
        'September' => 'Eyl',
        'October' => 'Eki',
        'November' => 'Kas',
        'December' => 'Ara',
        'Monday' => 'Pts',
        'Tuesday' => 'Sal',
        'Wednesday' => 'Çar',
        'Thursday' => 'Per',
        'Friday' => 'Cum',
        'Saturday' => 'Cts',
        'Sunday' => 'Paz',
    );
    $use_it = ($short === FALSE) ? $replace : $short_replace;
    return strtr(date($datestr, $date), $use_it);
}

if (!function_exists('timespan_basic'))
{

    function timespan_basic($seconds = 1, $time = '', $deep = NULL)
    {
        $CI = & get_instance();
        $CI->lang->load('date');
        $current_deep = 0;
        if (!is_numeric($seconds))
        {
            $seconds = 1;
        }

        if (!is_numeric($time))
        {
            $time = time();
        }

        if ($time <= $seconds)
        {
            $seconds = 1;
        }
        else
        {
            $seconds = $time - $seconds;
        }

        $str = '';
        $years = floor($seconds / 31536000);

        if ($years > 0)
        {
            $str .= $years . ' ' . $CI->lang->line((($years > 1) ? 'date_years' : 'date_year')) . ', ';
            if (++$current_deep == $deep)
                return substr(trim($str), 0, -1);
        }

        $seconds -= $years * 31536000;
        $months = floor($seconds / 2628000);

        if ($years > 0 OR $months > 0)
        {
            if ($months > 0)
            {
                $str .= $months . ' ' . $CI->lang->line((($months > 1) ? 'date_months' : 'date_month')) . ', ';
                if (++$current_deep == $deep)
                    return substr(trim($str), 0, -1);
            }

            $seconds -= $months * 2628000;
        }

        $weeks = floor($seconds / 604800);

        if ($years > 0 OR $months > 0 OR $weeks > 0)
        {
            if ($weeks > 0)
            {
                $str .= $weeks . ' ' . $CI->lang->line((($weeks > 1) ? 'date_weeks' : 'date_week')) . ', ';
                if (++$current_deep == $deep)
                    return substr(trim($str), 0, -1);
            }

            $seconds -= $weeks * 604800;
        }

        $days = floor($seconds / 86400);

        if ($months > 0 OR $weeks > 0 OR $days > 0)
        {
            if ($days > 0)
            {
                $str .= $days . ' ' . $CI->lang->line((($days > 1) ? 'date_days' : 'date_day')) . ', ';
                if (++$current_deep == $deep)
                    return substr(trim($str), 0, -1);
            }

            $seconds -= $days * 86400;
        }

        $hours = floor($seconds / 3600);

        if ($days > 0 OR $hours > 0)
        {
            if ($hours > 0)
            {
                $str .= $hours . ' ' . $CI->lang->line((($hours > 1) ? 'date_hours' : 'date_hour')) . ', ';
                if (++$current_deep == $deep)
                    return substr(trim($str), 0, -1);
            }

            $seconds -= $hours * 3600;
        }

        $minutes = floor($seconds / 60);

        if ($days > 0 OR $hours > 0 OR $minutes > 0)
        {
            if ($minutes > 0)
            {
                $str .= $minutes . ' ' . $CI->lang->line((($minutes > 1) ? 'date_minutes' : 'date_minute')) . ', ';
                if (++$current_deep == $deep)
                    return substr(trim($str), 0, -1);
            }

            $seconds -= $minutes * 60;
        }

        if ($str == '')
        {
            $str .= $seconds . ' ' . $CI->lang->line((($seconds > 1) ? 'date_seconds' : 'date_second')) . ', ';
        }

        return substr(trim($str), 0, -1);
    }

}
/* End of file MY_date_helper.php */