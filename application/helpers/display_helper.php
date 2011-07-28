<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Generate HTML code for JS confirmation boxes displaying a provided message.
 *
 * @access public
 * @param string
 * @return string
 */
function js_confirm($msg = NULL)
{
    $message = ($msg == NULL) ? 'Bu işlemi yapmak istediğinize eminmisiniz?' : $msg;
    return 'onclick="return confirm(\'' . $message . '\');"';
}

/**
 * Generate HTML code for displaying message.
 *
 * @param string $message
 * @param string $boxtype
 * @return string
 */
function message_box($message, $boxtype = 'error')
{
    $str = '<div class="%s">%s</div>';
    return sprintf($str, $boxtype, $message);
}

function analytics_code()
{
    $analytic_id = get_option('analytics_id');
    if (!$analytic_id)
        return;
    $code = "
   var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$analytic_id}']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();";
    return add_js($code, 'embed');
}

/**
 * pragmaMx
 * @param string $string
 * @param int $len
 * @param string $add
 * @param string $cutter
 * @return string
 */
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

/**
 *
 * @param int $uri_segment
 * @return string
 */
/* function page_num($uri_segment = 4)
  {
  $ci = & get_instance();
  $page_num = $ci->uri->rsegment($uri_segment);
  if ($page_num > 1)
  {
  return ' - Sayfa ' . $page_num;
  }
  } */

function no_index()
{
    $CI = & get_instance();
    $CI->output->set_header("HTTP/1.1 200 OK");
    $CI->output->set_header("Pragma: no-cache");
    $CI->template->add_meta(array(
        'name' => 'robots',
        'content' => 'noindex,nofollow'));
}


/* End of file display_helper.php */
/* Location: ./application/helpers/display_helper.php */