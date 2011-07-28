<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['login'] = array(
    'name' => 'Üye Girişi',
    'is_public' => 1,
    'module' => 'user',
);

function block_login()
{
    $ci = & get_instance();
    ob_start();
    echo form_open('user/login', array('class' => 'yform full'));
    echo '<div class="type-text">';
    echo form_item('login', 'Kullanıcı Adı');
    echo '</div>';
    echo '<div class="type-text">';
    echo form_item('password', 'Şifre', 'password');
    echo '</div>';
    echo '<div class="type-check">';
    echo form_checkbox('remember', 1, TRUE, 'id="remember"');
    echo form_label('Beni Hatırla', 'remember');
    echo '</div>';
    echo '<div class="buttonbox">';
    echo form_submit('submit', 'Giriş', 'class="awesome"');
    echo '</div>';
    echo '<ul>';
    echo '<li>' . anchor('/user/forgot_password/', 'Şifremi Unuttum') . '</li>';
    echo '<li>';
    if ($ci->config->item('allow_registration'))
        echo anchor('/user/register/', 'Kayıt Ol') . '</li>';
    echo '</ul>';
    echo '<a id="facebook" class="center" href="user/facebook/redirect">Facebook ile Giriş Yap</a>';
    echo form_close();
    $html = ob_get_contents();
    ob_end_clean();
    //add_jquery('$("#facebook").colorbox({ iframe:true, innerWidth:600,innerHeight:400 });');
    //add_js('colorbox');
    //add_css('colorbox');
    return $html;
}
