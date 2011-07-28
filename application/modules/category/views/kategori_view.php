<?php foreach ($posts as $post): ?>
    <div class="floatbox">
    <?php echo post_image($post['image'], $post['created_on'], $post['title']); ?>
    <h2><?php echo anchor($post['slug'], $post['title']); ?></h2>
    <p class="post">
        <?php echo ($post['summary'] != '') ? $post['summary'] : cut_string(strip_tags($post['content']), 400); ?>
    </p>
</div>
<div class="post-info quick">
    <div class="floatbox">
        <span class="date"><?php echo tr_date('d F Y', $post['created_on']); ?></span>
        <span class="category">
            <?php echo categories($post['categories']); ?>
        </span>
        <?php /* @todo eklenecek ?><span class="comments"><?php echo ($post['comments'] > 0) ? $post['comments'] . ' Yorum' : 'Yorum Yok'; ?></span>*/?>
        <span class="more-link"><?php echo anchor($post['slug'], 'Devamı'); ?></span>
    </div>
</div>
<?php endforeach; ?>

<?php echo $pagination; ?>