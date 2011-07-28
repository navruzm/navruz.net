<?php
/*
 * $Date: 2009-12-03 14:24:28 +0200 (Per, 03 Ara 2009) $
 * $Id: category_view.php 126 2009-12-03 12:24:28Z Mustafa $
 * $Rev: 126 $
 */
?>
<?php foreach ($post as $post_item): ?>
    <div class="floatbox">
    <?php echo post_image($post_item['image'], $post_item['created_on'], $post_item['title']); ?>
    <h2><?php echo anchor($post_item['slug'], $post_item['title']); ?></h2>
    <p class="post">
        <?php echo ($post_item['summary'] != '') ? $post_item['summary'] : cut_string(strip_tags($post_item['content']), 400); ?>
    </p>
</div>
<div class="post-info">
    <div class="floatbox">
        <span class="date"><?php echo tr_date('d F Y', $post_item['created_on']); ?></span>
        <span class="category">
            <?php echo categories($post_item['categories']); ?>
        </span>
        <span class="comments"><?php echo ($post_item['comments'] > 0) ? $post_item['comments'] . ' Yorum' : 'Yorum Yok'; ?></span>
        <span class="more-link"><?php echo anchor($post_item['slug'], 'Devamı'); ?></span>
    </div>
</div>
<?php endforeach; ?>

<?php echo $pagination; ?>