<table class="condensed-table zebra-striped">
    <thead>
        <tr>
            <th>Başlık</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($pages)): ?>
            <?php foreach ($pages as $page) : ?>
                <tr>
                    <td><?php echo anchor($page['slug'], $page['title'], 'target="_blank"'); ?></td>
                    <td><?php  echo date('d-m-Y H:i', $page['created_at']->sec); ?></td>
                    <td class="action">
                        <?php echo anchor('admin/page/edit/' . $page['_id'], 'Düzenle', 'class="btn"'); ?>
                        <?php echo anchor('admin/page/delete/' . $page['_id'], 'Sil', 'class="btn danger"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Henüz Sayfa bulunmuyor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination; ?>