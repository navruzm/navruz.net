<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['online'] = array(
    'name' => 'Çevrimiçi Üyeler',
    'is_public' => 1,
    'module' => 'user',
);

function block_online()
{
    $ci = & get_instance();
    $total_user = $ci->db->where('activated', 1)->count_all_results('users');
    $result = $ci->db->where('last_activity >', now() - 300)
                    ->group_by('user_agent')
                    ->group_by('ip_address')
                    ->order_by('last_activity', 'desc')
                    ->get('sessions');
    $sessions = $result->result_array();
    $guest = 0;
    $users = array();
    foreach ($sessions as $session)
    {
        if ($session['user_data'] == '')
        {
            $guest++;
        }
        else
        {
            $data = unserialize($session['user_data']);
            if (isset($data['username']))
            {
                $users[] = $data;
            }
        }
    }
    ob_start();
    //echo '<div>';
    //echo 'Üye Sayısı : ' . $total_user . br();
    //echo 'Çevrimiçi Misafiler : ' . $guest . br();
    //echo 'Çevrimiçi Üyeler : ' . count($users);
    //echo '</div>';
    //echo '<div>';
    if (count($users)):
        //echo 'Çevrimiçi Üyeler: ';
        echo '<div class="online">';
        echo user_list($users);
        echo '</div>';
    endif;
    // echo '</div>';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

/*
  function block_online()
  {
  $ci = & get_instance();
  $total_user = $ci->db->where('activated',1)->count_all_results('users');
  $result = $ci->db->where('last_activity >', now() - 300)
  ->group_by('user_agent')
  ->order_by('last_activity', 'desc')
  ->get('sessions');
  $sessions = $result->result_array();
  $guest = 0;
  $users = array();
  foreach ($sessions as $session)
  {
  if ($session['user_data'] == '')
  {
  $guest++;
  }
  else
  {
  $data = unserialize($session['user_data']);
  if (isset($data['username']))
  {
  $user = user_anchor($data);
  }
  if (!in_array($user, $users))
  $users[] = $user;
  }
  }
  ob_start();
  echo '<div>';
  echo 'Üye Sayısı : ' . $total_user . br();
  echo 'Çevrimiçi Misafiler : ' . $guest . br();
  echo 'Çevrimiçi Üyeler : ' . count($users);
  echo '</div>';
  echo '<div>';
  if (count($users)):
  echo 'Çevrimiçi Üyeler: ';
  echo ul($users, array('id' => 'online-list'));
  endif;
  echo '</div>';
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
  }
 */