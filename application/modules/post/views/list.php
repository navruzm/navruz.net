<?php if (isset($title) && !$this->input->is_ajax_request()): ?>
<h1><?php echo $title; ?></h1>
<?php endif; ?>
<?php foreach ($posts as $post): ?>
<div class="post-summary">
    <h2><?php echo anchor($post['slug'], $post['title']); ?></h2>
    <div class="post-info">
            <span class="date">
                <?php echo tr_date('d F Y', $post['created_at']->sec); ?>
            </span>
            <span class="comments">
                <?php echo count($post['comments']); ?> Yorum
            </span>
            <span class="read">
                <?php echo $post['counter']; ?> kez okundu.
            </span>
    </div>
    <?php echo word_limiter(strip_tags($post['content']), 50); ?>
</div>
<?php endforeach; ?>
<?php if (!isset($post['counter'])): ?>
<div class="alert-message block-message warning">
    Kayıt bulunamadı.
</div>
<?php endif; ?>
<?php if (!$this->input->is_ajax_request() && isset($total_page) && !$this->input->get('page')): ?>
<input type="hidden" name="max-page" value="<?php echo $total_page;?>" id="max-page">
<input type="hidden" name="page-number" value="1" id="page-number">
<div id="loading-bar" class="alert-message block-message warning"></div>
<?php
    $q = $this->input->get('q') == FALSE ? '' : 'q=' . $this->input->get('q') . '&';
    $this->asset->add_js_jquery('
    var loading = false;
    $(window).scroll(function() {
        if ((($(window).scrollTop() + $(window).height()) + 250) >= $(document).height()) {
            if (loading == false) {
                loading = true;
                if(parseInt($("#page-number").val())>=parseInt($("#max-page").val()))
                {
                    return;
                }
                $("#loading-bar").css("display", "block");
                $("#page-number").val(parseInt($("#page-number").val()) + 1);
                $.get("' . $this->uri->uri_string() . '?' . $q . 'page=" + $("#page-number").val(), function(loaded) {
                        $("#page-number").before(loaded);
                        $("#loading-bar").css("display", "none");
                        loading = false;
                });
            }
        }
    });');?>
<?php endif; ?>