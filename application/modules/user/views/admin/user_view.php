<?php
//@todo düzenlenecek...
$user_group = ($user->user_group == $this->config->item('admin_group'))?'Yönetici':'Üye';
?>
<table class="full" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2">Üye Bilgileri</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Üye Adı</td>
            <td><?php echo $user->username;?></td>
        </tr>
        <tr>
            <td>E-Posta Adresi</td>
            <td><?php echo $user->email;?></td>
        </tr>
        <tr>
            <td>Üye Grubu</td>
            <td><?php echo $user_group;?></td>
        </tr>
    </tbody>
</table>

