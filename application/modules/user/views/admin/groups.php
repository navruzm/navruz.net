<?php $this->load->helper('display');?>
<table class="full" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Grup Başlığı </th>
            <th>Grup Adı </th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php  foreach($groups as $group) :?>
        <tr>
            <td><?php echo $group['title'];?></td>
            <td><?php echo $group['name'];?></td>
            <td class="action">
                    <?php // echo anchor('admin/user/show/'.$group['id'],'Göster',array('class'=>'show'));?>
                    <?php echo anchor('admin/user/group_edit/'.$group['id'],'Düzenle',array('class'=>'edit'));?>
                    <?php echo anchor('admin/user/group_delete/'.$group['id'],'Sil','class="delete" '.js_confirm());?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>