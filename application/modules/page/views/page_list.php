<h1>Sayfalar</h1>
<?php foreach ($pages as $page): ?>
        <div class="floatbox">
            <h2><?php echo anchor('page/' . $page['slug'], $page['title']); ?></h2>
            <p class="post">
        <?php echo cut_string(strip_tags($page['content']), 200); ?>
    </p>
</div>
<div class="post-info floatbox">
    <span class="date"><?php echo tr_date('d F Y', $page['created_on']); ?></span>

    <span class="more-link"><?php echo anchor('page/' . $page['slug'], 'Devamı'); ?></span>
</div>
<?php endforeach; ?>

<?php echo $pagination; ?>


