<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Dosya Adı</th>
            <th>Boyut</th>
            <th>İndirilme</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if($files !== FALSE): ?>
            <?php foreach($files as $file) :?>
        <tr>
            <td><?php echo anchor(site_url().'file/' . $file['file_name'], $file['file_name']);?> (<?php echo $file['file_title'];?>)</td>
            <td><?php echo $file['file_size'];?> Kb.</td>
            <td><?php echo $file['file_download_count'];?> Kez</td>
            <td><?php echo $file['file_date_add'];?></td>
            <td class="action">
                        <?php echo anchor('admin/file/edit_file/' . $file['file_id'], 'Düzenle', 'class="edit"');?>
                        <?php echo anchor('admin/file/delete_file/' . $file['file_id'], 'Sil', 'class="delete" ' . js_confirm());?>
            </td>
        </tr>
                <?php endforeach; ?>
            <?php else: ?>
        <tr>
            <td colspan="5">Henüz dosya bulunmuyor.</td>
        </tr>
            <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination;?>