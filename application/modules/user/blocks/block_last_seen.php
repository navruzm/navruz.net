<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['last_seen'] = array(
    'name' => 'Son Görülenler',
    'is_public' => 1,
    'module' => 'user',
);

function block_last_seen()
{
    $ci = & get_instance();
    $l_users = $ci->users->get_last_seened_users();
    $users = array();
    foreach ($l_users as $user)
    {
        $users[] = anchor('user/'.$user['username'],'<strong>'.$user['name'].'</strong>')
                .'<br/> ('.timespan_basic(human_to_unix($user['last_online']),'',1).' önce)';
    }
    ob_start();
    echo ul($users, array('id' => 'last-seen-list'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
