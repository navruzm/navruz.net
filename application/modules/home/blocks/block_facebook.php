<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['facebook'] = array(
    'name' => 'Facebook',
    'is_public' => 1,
    'module' => 'home',
);

function block_facebook()
{
    add_jquery('$("#facebook-like").html(\'<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fapps%2Fapplication.php%3Fid%3D177616999479&amp;width=190&amp;colorscheme=light&amp;connections=6&amp;stream=false&amp;header=true&amp;height=280" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:190px; height:280px;" allowTransparency="true"></iframe>\');');
    ob_start();
    echo '<div id="facebook-like"></div>';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
