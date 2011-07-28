<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Başlık</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if($pages !== FALSE): ?>
            <?php foreach($pages as $page) :?>
        <tr>
            <td><?php echo anchor('page/'.$page['slug'],cut_string($page['title'],50,'...',' '),'target="_blank"');?></td>
            <td>
                        <?php echo ($page['created_on'] < time()) ? '<span class="yesil">Yayında</span>' : '<span class="kirmizi">Beklemede</span>';?>
            </td>
            <td><?php echo date('d-m-Y H:i',$page['created_on']);?></td>
            <td class="action">
                        <?php echo anchor('admin/page/edit_page/'.$page['id'],'Düzenle','class="edit"');?>
                        <?php echo anchor('admin/page/delete_page/'.$page['id'],'Sil','class="delete" '.js_confirm());?>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="5">Henüz sayfa bulunmuyor.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination;?>