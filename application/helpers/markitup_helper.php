<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function bbcode_js()
{
    add_jquery('$(\'#content\').markItUp(mySettings);');
    add_css('assets/markitup/skins/simple/style.css','link');
    add_css('assets/markitup/sets/bbcode/style.css','link');
    add_js('assets/markitup/jquery.markitup.js','link');
    add_js('assets/markitup/sets/bbcode/set.js','link');
}

function markdown_js()
{
    add_jquery('$(\'#content\').markItUp(mySettings);');
    add_css('assets/markitup/skins/simple/style.css','link');
    add_css('assets/markitup/sets/markdown/style.css','link');
    add_js('assets/markitup/jquery.markitup.js','link');
    add_js('assets/markitup/sets/markdown/set.js','link');
}
