<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Grup</th>
            <th>Etiket</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($groups)): ?>
            <?php foreach($groups as $group) :?>
        <tr>
            <td><?php echo $group['title'];?></td>
            <td><?php echo $group['tag'];?></td>

            <td class="action">
                        <?php echo anchor('admin/navigation/links/'.$group['id'],'Grubu Göster','class="edit"');?>
                        <?php echo anchor('admin/navigation/edit_group/'.$group['id'],'Düzenle','class="edit"');?>
                        <?php echo anchor('admin/navigation/delete_group/'.$group['id'],'Sil','class="delete" '.js_confirm());?>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="5">Henüz grup bulunmuyor.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>