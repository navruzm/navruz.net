<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter URL Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/helpers/url_helper.html
 */
// ------------------------------------------------------------------------

/**
 * Anchor Link
 *
 * Creates an anchor based on the local URL. (Even with #)
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
if (!function_exists('anchor'))
{

    function anchor($uri = '', $title = '', $attributes = '')
    {

        $title = (string) $title;
        $id = '';
        if (strpos($uri, '#'))
        {
            $uri_part = explode('#', $uri);
            $uri = $uri_part[0];
            $id = '#' . $uri_part[1];
        }
        if ($uri == '/')
        {
            $site_url = base_url();
        }
        elseif (!is_array($uri))
        {
            $site_url = (!preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
        }
        else
        {
            $site_url = site_url($uri);
        }

        if (!isset($attributes['title']))
        {
            $attributes['title'] = strip_tags($title);
        }
        
        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes != '')
        {
            $attributes = _parse_attributes($attributes);
        }

        return '<a href="' . $site_url . $id . '"' . $attributes . '>' . $title . '</a>';
    }

}

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access    public
 * @param    string    the string
 * @param    string    the separator: dash, or underscore
 * @return    string
 */
function url_title($str, $separator = 'dash', $lowercase = TRUE)
{
    if ($separator == 'dash')
    {
        $search = '_';
        $replace = '-';
    }
    else
    {
        $search = '-';
        $replace = '_';
    }

    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '\.' => $replace,
        '[^a-z0-9\-_]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );

    $search_tr = array('ı', 'İ', 'Ğ', 'ğ', 'Ü', 'ü', 'Ş', 'ş', 'Ö', 'ö', 'Ç', 'ç');
    $replace_tr = array('i', 'I', 'G', 'g', 'U', 'u', 'S', 's', 'O', 'o', 'C', 'c');
    $str = str_replace($search_tr, $replace_tr, $str);

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
        $str = strtolower($str);
    }

    return trim(stripslashes($str));
}

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_bitly_url($url)
{
    $bitly_data = @file_get_contents("http://api.bit.ly/shorten?version=2.0.1&longUrl=" . $url . "&login=" . get_option('bitly_login') . "&apiKey=" . get_option('bitly_apikey'));
    if ($bitly_data === FALSE)
        return FALSE;

    $bitly_content = json_decode($bitly_data, TRUE);
    if ($bitly_content["errorCode"] == 0)
    {
        return $bitly_content["results"][$url]["shortUrl"];
    }
    return FALSE;
}

/* End of file MY_url_helper.php */
/* Location: ./app/helpers/MY_url_helper.php */