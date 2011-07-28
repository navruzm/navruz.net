<?php $this->load->helper('display');?>
<table class="full" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Üye Adı </th>
            <th>E-Posta </th>
            <th>Grup </th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php  foreach($users as $user) :?>
            <?php $user_group = get_user_group($user['user_group']);?>
        <tr>
            <td><?php echo $user['username'];?></td>
            <td><?php echo $user['email'];?></td>
            <td><?php echo $user_group['title'];?></td>
            <td class="action">
                    <?php echo anchor('admin/user/show/'.$user['id'],'Göster',array('class'=>'show'));?>
                    <?php echo anchor('admin/user/edit/'.$user['id'],'Düzenle',array('class'=>'edit'));?>
                    <?php echo anchor('admin/user/delete/'.$user['id'],'Sil','class="delete" '.js_confirm());?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $pagination;?>