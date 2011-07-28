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
            <span class="read">
                <?php echo $post['counter']; ?> Okunma
            </span>
            <?php /*
              <span class="tags">
              <?php echo tags($post['tags']);?>
              </span>
             */ ?>
            <span class="comments"><?php echo anchor($post['slug'] . '#disqus_thread', 'Yorumlar', 'title="Yorumlar" data-disqus-identifier="post_' . $post['id'] . '"'); ?></span>
            <span class="more-link"><?php echo anchor($post['slug'], 'Devamı'); ?></span>
        </div>    
    </div>
<?php endforeach; ?>

<?php echo $pagination; ?>
<?php if (get_option('disqus') != ''): ?>
    <script type="text/javascript">
        var disqus_shortname = '<?php echo get_option('disqus'); ?>';
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>
<?php endif; ?>