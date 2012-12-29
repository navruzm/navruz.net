<table class="condensed-table zebra-striped">
    <thead>
        <tr>
            <th>Kategori</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($navigations)): ?>
            <?php foreach ($navigations as $navigation) : ?>
                <tr id="sort_<?php echo $navigation['_id'];?>">
                    <td><?php echo $navigation['title']; ?></td>
                    <td class="action">
                        <?php echo anchor('admin/navigation/edit/' . $navigation['_id'], 'Düzenle', 'class="btn"'); ?>
                        <?php echo anchor('admin/navigation/delete/' . $navigation['_id'], 'Sil', 'class="btn danger"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Henüz menü bulunmuyor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>