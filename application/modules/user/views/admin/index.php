<table class="condensed-table zebra-striped">
    <thead>
        <tr>
            <th>İsim</th>
            <th>E-Posta</th>
            <th>Tarih</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($users)): ?>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo date('d-m-Y H:i', $user['created_at']->sec); ?></td>
                    <td class="action">
                        <?php echo anchor('admin/user/edit/' . $user['_id'], 'Düzenle', 'class="btn"'); ?>
                        <?php echo anchor('admin/user/delete/' . $user['_id'], 'Sil', 'class="btn danger"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Henüz üye bulunmuyor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination; ?>