<?php

add_css('tools');
$cont = $this->uri->rsegment(2);
?>
<ul class="tabs-link">
    <li><?php echo anchor('user/change_profile', 'Bilgilerini Değiştir', ($cont == 'change_profile') ? 'class="current"' : ''); ?></li>
    <li><?php echo anchor('user/avatar', 'Profil Resmi', ($cont == 'avatar') ? 'class="current"' : ''); ?></li>
    <li><?php echo anchor('user/change_password', 'Şifre Değiştir', ($cont == 'change_password') ? 'class="current"' : ''); ?></li>
    <li><?php echo anchor('user/change_email', 'E-Posta Değiştir', ($cont == 'change_email') ? 'class="current"' : ''); ?></li>
</ul>