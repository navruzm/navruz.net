<?php
$locations = array(
    'left' => 'Sol',
    'right' => 'Sağ',
    'center_top' => 'Orta Üst',
    'center_bottom' => 'Orta Alt',
);
?>
<?php $this->load->helper('display'); ?>
<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Bloklar</th>
            <th>Modüller</th>
            <th class="action">İşlem</th>
        </tr>
    </thead>
    <?php foreach ($locations as $name => $location) : ?>
        <tbody>
            <tr>
                <th colspan="3"><?php echo $location; ?> Bloklar</th>
            </tr>
        <?php if (count($blocks[$name])): ?>
        <?php foreach ($blocks[$name] as $block) : ?>
        <?php
                $modules = array();
                foreach ($block['modules'] as $module)
                    $modules[] = $this->m_config[$module]['name'];
        ?>
                <tr>
                    <td><?php echo $block['title']; ?> (<?php echo $block['type']; ?>)</td>
                    <td><?php echo implode(', ', $modules); ?></td>
                    <td class="action">
                <?php echo anchor('admin/block/active/' . $block['id'], ($block['active'] == 1) ? 'Pasifleştir' : 'Aktifleştir', 'class="edit"'); ?>
                <?php echo anchor('admin/block/update/' . $block['id'], 'Düzenle', 'class="edit"'); ?>
                <?php echo anchor('admin/block/delete/' . $block['id'], 'Sil', 'class="delete" ' . js_confirm()); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
                    <tr>
                        <td colspan="3">Henüz blok bulunmuyor.</td>
                    </tr>
        <?php endif; ?>
                </tbody>
    <?php endforeach; ?>
</table>
