<table class="full">
    <thead>
        <tr>
            <th>Başlık</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if ($post !== FALSE): ?>
            <?php foreach ($post as $post_item) : ?>
                <tr>
                    <td><?php echo anchor($post_item['slug'], cut_string($post_item['title'], 50, '...', ' '), 'target="_blank"'); ?></td>
                    <td>
                        <?php echo ($post_item['created_on'] < time()) ? '<span class="yesil">Yayında</span>' : '<span class="kirmizi">Beklemede</span>'; ?>
                    </td>
                    <td><?php echo date('d-m-Y H:i', $post_item['created_on']); ?></td>
                    <td class="action">
                        <?php echo anchor('admin/post/edit_post/' . $post_item['id'], 'Düzenle', 'class="edit"'); ?>
                        <?php echo anchor('admin/post/delete_post/' . $post_item['id'], 'Sil', 'class="delete" ' . js_confirm()); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Henüz Yazı bulunmuyor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination; ?>