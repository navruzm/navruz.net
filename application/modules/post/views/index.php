<?php
$content = parse_smileys($content, site_url() . 'assets/img/smilies/');
if (strpos($content, '<code class="') !== FALSE)
{
    add_css_minify('assets/css/github.css');
    add_js_jquery('
  hljs.tabReplace = "    ";
  hljs.initHighlightingOnLoad();', 'assets/js/highlight.pack.js', 'link');
}
?>
<h1><?php echo $title; ?></h1>

<div class="content">
    <?php if (isset($image)): ?>
    <?php echo post_image($image, $created_at->sec, $title); ?>
    <?php endif; ?>
    <?php echo $content; ?>
    <?php $last_update = ($updated_at->sec != '') ? $updated_at->sec : $created_at->sec; ?>
    <?php if ($last_update < now() - 5184000): ?>
    <div class="alert-message block-message warning">
        Bu yazı en son <?php echo tr_date('d F Y', $last_update); ?> tarihinde düzenlenmiştir ve güncelliğini yitirmiş
        olabilir.
    </div>
    <?php endif; ?>
</div>

<div class="post post-info">
    <span class="date">
        <?php echo tr_date('d F Y', $created_at->sec); ?>
    </span>
    <span class="category">
        <?php echo categories($categories); ?>
    </span>
    <span class="tags">
        <?php echo tags($tags); ?>
    </span>
    <span class="read">
        <?php echo $counter; ?> kez okundu.
    </span>
</div>

<div class="row post-share">
    <div class="span5">
        <?php echo anchor('http://feeds.feedburner.com/' . get_option('feedburner_username'), 'Yazılarımızı RSS ile takip edebilirsiniz.'); ?>
        <div>
            <g:plusone size="tall"></g:plusone>
            <a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="navruzm"
               data-lang="tr">Tweet</a>
            <?php add_js_link('//apis.google.com/js/plusone.js') ?>
            <?php add_js_link('//platform.twitter.com/widgets.js'); ?>
        </div>
    </div>
    <div class="span6">
        <?php if (count($related)): ?>
        <div class="title">Benzer Yazılar</div>
        <ul>
            <?php foreach ($related as $_related) : ?>
            <li><?php echo anchor($_related['slug'], $_related['title']); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<hr/>
<?php if ($comments_enabled == 1 && get_option('disqus') != ''): ?>
<div id="disqus_thread">
    <?php if (isset($comments) && count($comments)): ?>
    <div id="dsq-content">
        <ul id="dsq-comments">
            <?php foreach ($comments as $comment) : ?>
            <li>
                <cite>
                    <?php if (isset($comment['author_url'])) : ?>
                    <?php echo anchor($comment['author_url'], $comment['author_name'], 'target="_blank" rel="nofollow"'); ?>
                    <?php else : ?>
                    <span><?php echo $comment['author_name']; ?></span>
                    <?php endif; ?>
                </cite>

                <div><?php echo $comment['message']; ?></div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<?php add_js_inline("
function disqus_config() {
    this.callbacks.onNewComment = [function(comment) {
        $.get('".site_url('post/sync?id='.$_id.'&ident='.$disqus_identifier)."');
    }];
}
var disqus_shortname = '" . get_option('disqus') . "';
var disqus_identifier = '" . $disqus_identifier . "';
var disqus_url = '" . site_url($slug) . "';", 'before'); ?>
<?php add_js_link('//' . get_option('disqus') . '.disqus.com/embed.js'); ?>
<a href="http://disqus.com" class="dsq-brlink" rel="nofollow">blog comments powered by <span
    class="logo-disqus">Disqus</span></a>
<?php endif; ?>