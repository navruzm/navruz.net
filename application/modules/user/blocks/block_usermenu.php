<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['usermenu'] = array(
    'name' => 'Üye Menüsü',
    'is_public' => 1,
    'module' => 'user',
);

function block_usermenu()
{
    $ci = & get_instance();
    $profile = $ci->users->get_user_profile(get_user_id());
    ob_start();
    echo '<div class="clearfix">';
    echo user_image(get_username());
    echo $profile['name'] . br();
    echo anchor('user/change_profile', 'Profili Düzenle').br();
    echo anchor('user/logout', 'Çıkış Yap');
    echo '</div>';
    if (!file_exists(config_item('avatar_upload_path') . get_username() . '.jpg'))
    {
        echo '<div class="tips">Henüz profil resminiz yok. '.anchor('user/avatar','<strong>Yüklemek için tıklayın.</strong>').'</div>';
    }
    if ($profile['first_name'] == '' OR $profile['last_name'] == '' OR $profile['birthday'] == '' OR $profile['gender'] == '')
    {
        echo '<div class="tips">Profil bilgileriniz eksik. '.anchor('user/change_profile','<strong>Güncellemek için tıklayın.</strong>').'</div>';
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
