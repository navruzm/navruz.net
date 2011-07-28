<?php $this->load->helper('display');?>
<table class="full" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Üye Adı </th>
            <th>E-Posta </th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php  foreach($users as $user) :?>
            <?php $user_group = get_user_group($user['user_group']);?>
        <tr>
            <td><?php echo $user['username'];?></td>
            <td><?php echo $user['email'];?></td>
            <td class="action">
                    <?php echo anchor('admin/permission/edit/'.$user['id'],'İzinleri Düzenle',array('class'=>'edit'));?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo $pagination;?>