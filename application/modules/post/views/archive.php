<?php foreach ($posts as $date => $data): ?>
        <div class="title"><?php echo $date; ?></div>
        <ul>
<?php foreach ($data as $post): ?>
            <li> <?php echo anchor($post['slug'],$post['title']); ?> (<?php echo tr_date('d F Y', $post['created_on']); ?>)</li>
<?php endforeach; ?>
            </ul>
<?php endforeach; ?>