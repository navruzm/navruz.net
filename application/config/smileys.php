<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| SMILEYS
| -------------------------------------------------------------------
| This file contains an array of smileys for use with the emoticon helper.
| Individual images can be used to replace multiple simileys.  For example:
| :-) and :) use the same image replacement.
|
| Please see user guide for more info:
| http://codeigniter.com/user_guide/helpers/smiley_helper.html
|
*/

$smileys = array(

//	smiley			image name						width	height	alt

    ':-)' => array('icon_biggrin.png', '16', '16', 'grin'),
    ':D' => array('icon_biggrin.png', '16', '16', 'grin'),
    ':cry:' => array('icon_cry.png', '16', '16', 'cry'),
    ':lol:' => array('icon_lol.png', '16', '16', 'LOL'),
    ':)' => array('icon_smile.png', '16', '16', 'smile'),
    ':-(' => array('icon_sad.png', '16', '16', 'sad'),
    ':(' => array('icon_sad.png', '16', '16', 'sad'),
    ';-)' => array('icon_wink.png', '16', '16', 'wink'),
    ';)' => array('icon_wink.png', '16', '16', 'wink'),
    ':roll:' => array('icon_rolleyes.png', '16', '16', 'rolleyes'),
    ':-S' => array('icon_confused.png', '16', '16', 'confused'),
    ':-P' => array('icon_razz.png', '16', '16', 'tongue laugh'),
    ':blank:' => array('icon_neutral.png', '16', '16', 'blank stare'),
    ':red:' => array('icon_redface.png', '16', '16', 'red face'),
    '>:(' => array('icon_mad.png', '16', '16', 'mad'),
    ':mad:' => array('icon_mad.png', '16', '16', 'mad'),
    ':zip:' => array('icon_idea.png', '16', '16', 'zipper'),
    ':coolsmile:' => array('icon_cool.png', '16', '16', 'cool smile'),
    ':coolgrin:' => array('icon_mrgreen.png', '16', '16', 'cool grin'),
    ':exclaim:' => array('icon_exclaim.png', '16', '16', 'excaim'),
    ':question:' => array('icon_question.png', '16', '16', 'question') // no comma after last item

		);

/* End of file smileys.php */
/* Location: ./application/config/smileys.php */