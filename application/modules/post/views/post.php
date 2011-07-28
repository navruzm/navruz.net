<?php
$this->template->jquery();
$content = parse_smileys($content, site_url() . 'assets/img/smilies/');
if (strpos($content, '<code class="') !== FALSE)
{
    add_css('github');
    add_js('
  hljs.tabReplace = "    ";
  hljs.initHighlightingOnLoad();', 'embed');
    add_js('highlight');
}
?>
<h1><?php echo $title; ?></h1>
<div class="floatbox">
    <?php echo post_image($image, $created_on, $title); ?>

    <p class="post">
        <?php echo $summary; ?>
    </p>
</div>
<hr/>
<div class="content">
    <?php echo $content; ?>
    <?php $last_update = ($updated_on != '')
                ? $updated_on : $created_on; ?>
    <?php if ($last_update < now() - 5184000): ?>
        <div class="warning-box">
            Bu yazı en son <?php echo tr_date('d F Y', $last_update); ?> tarihinde düzenlenmiştir ve güncelliğini yitirmiş olabilir.
        </div>
    <?php endif; ?>
</div>
<div class="post-info">
    <span class="date">
        <?php echo tr_date('d F Y', $created_on); ?>
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

<div>
    <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="navruzm" data-lang="tr">Tweet</a>
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <g:plusone size="medium"></g:plusone>
    <?php add_js('http://apis.google.com/js/plusone.js','link') ?>
    <span id="fb-root"></span>
    <script src="http://connect.facebook.net/tr_TR/all.js#appId=201644383183217&amp;xfbml=1"></script>
    <fb:like href="" send="true" layout="button_count" width="450" show_faces="true" font="trebuchet ms"></fb:like>
</div>
<div id="rss">
    <?php echo anchor('http://feeds.feedburner.com/' . get_option('feedburner_username'),'Yazılarımızı RSS ile takip edebilirsiniz.'); ?>
</div>
<div class="title">Yazar Hakkında</div>
<div class="floatbox">
    <?php echo user_image($author_username); ?>  <strong><?php echo $author_name; ?></strong> <?php echo $author_bio; ?>
</div>
<div class="title">Benzer Yazılar</div>
<?php echo related($id); ?>
<?php if ($comments_enabled == 1 && get_option('disqus') != ''): ?>
    <div class="title">Yorumlar</div>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = '<?php echo get_option('disqus'); ?>';
        var disqus_identifier = 'post_<?php echo $id; ?>';
        var disqus_url = '<?php echo site_url($slug); ?>';
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
<?php endif; ?>