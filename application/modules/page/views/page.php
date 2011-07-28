<?php
$content = parse_smileys($content, site_url() . 'assets/img/smilies/');
?>
<h1><?php echo $title; ?></h1>

<div>
    <?php echo $content; ?>
</div>
<div class="page-info floatbox">
    <span class="date">
        <?php echo tr_date('d F Y', $created_on); ?>
    </span>
</div>
<?php echo $pagination; ?>
<?php if ($comments_enabled == 1 && get_option('disqus')!=''): ?>
    <div class="title">Yorumlar</div>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = '<?php echo get_option('disqus');?>';
        var disqus_identifier = 'page_<?php echo $id; ?>';
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