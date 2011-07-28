<?php $this->load->helper('display');?>
<table class="full" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>Kategori</th>
            <th class="action">İşlem </th>
        </tr>
    </thead>
    <tbody>
        <?php if($categories!==FALSE): ?>
            <?php foreach($categories as $category) :?>
        <tr>
            <td><?php echo $category['category_title'];?></td>
            <td class="action">
                        <?php echo anchor('admin/category/update_category/'.$category['category_id'],'Düzenle', 'class="edit"');?>
                        <?php echo anchor('admin/category/delete_category/'.$category['category_id'],'Sil','class="delete" '.js_confirm());?>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="2">Henüz kategori bulunmuyor.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
