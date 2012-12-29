<?php 
add_js_jquery('
$(function() {
        $("#sort-category tbody").sortable({
            opacity: 0.6,
            cursor: "move",
            helper: fixHelper,
            update: function() {
                var order = $(this).sortable("serialize");
                console.log(order);
                $.get("admin/category/sort", $(this).sortable("serialize") );
            }
        });
        var fixHelper = function(e, ui) {
        ui.children().each(function() {$(this).width($(this).width());});
        return ui;
    };
    });
','assets/js/jquery.ui.js');
?>
<table class="condensed-table zebra-striped" id="sort-category">
    <thead>
        <tr>
            <th>#</th>
            <th>Kategori</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($categories)): ?>
            <?php foreach ($categories as $category) : ?>
                <tr id="sort_<?php echo $category['_id'];?>">
                    <td class="sort">x</td>
                    <td><?php echo $category['title']; ?></td>
                    <td class="action">
                        <?php echo anchor('admin/category/edit/' . $category['_id'], 'Düzenle', 'class="btn"'); ?>
                        <?php echo anchor('admin/category/delete/' . $category['_id'], 'Sil', 'class="btn danger"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Henüz kategori bulunmuyor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>