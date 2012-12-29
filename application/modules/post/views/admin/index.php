<?php
add_js_jquery("$('select').chosen(); ", 'assets/js/chosen.jquery.min.js');
add_css_minify('assets/css/chosen.css');
?>
<table class="condensed-table zebra-striped">
    <thead>
    <tr>
        <th>
            <?php echo form_open($this->uri->uri_string(), 'method="get"'); ?>
            <?php echo form_dropdown('status', array('' => 'Hepsi', 'publish' => 'Yayında', 'draft' => 'Taslak'), set_value('status', isset($status) ? $status : ''), 'id="status"'); ?>
            <?php echo form_input('q', set_value('q', isset($title) ? $title : ''), 'class="span4" placeholder="Ara"'); ?>
            <input type="submit" value="Ara" class="btn primary">
            <?php echo form_close(); ?>
        </th>
        <th>Durum</th>
        <th>Tarih</th>
        <th class="action">İşlem</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($posts)): ?>
        <?php foreach ($posts as $post) : ?>
        <tr>
            <td><?php echo anchor($post['slug'], $post['title'], 'target="_blank"'); ?></td>
            <td>
                <?php if ($post['status'] == 'publish'): ?>
                <span class="label success">Yayında</span>
                <?php elseif ($post['status'] == 'draft'): ?>
                <span class="label warning">Taslak</span>
                <?php endif; ?>
            </td>
            <td><?php echo date('d-m-Y H:i', $post['created_at']->sec); ?></td>
            <td class="action">
                <?php echo anchor('admin/post/edit/' . $post['_id'], 'Düzenle', 'class="btn"'); ?>
                <?php echo anchor('admin/post/delete/' . $post['_id'], 'Sil', 'class="btn danger"'); ?>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else: ?>
    <tr>
        <td colspan="3">Henüz Yazı bulunmuyor.</td>
    </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php echo $pagination; ?>