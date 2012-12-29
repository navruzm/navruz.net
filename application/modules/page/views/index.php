<?php
$content = parse_smileys($content, site_url() . 'assets/img/smilies/');
?>
<h1><?php echo $title; ?></h1>
<div class="content">
    <?php echo $content; ?>
</div>
<div class="page-share">
    <div>
        <g:plusone size="tall"></g:plusone>
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="navruzm" data-lang="tr">Tweet</a>
        <?php add_js_link('//apis.google.com/js/plusone.js') ?>
        <?php add_js_link('//platform.twitter.com/widgets.js'); ?>
    </div>
</div>