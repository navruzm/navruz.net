<?php
$item_size = array(
    5 => 5,
    10 => 10,
    15 => 15,
    20 => 20,
    50 => 50,
);
add_js_jquery('$(".tabs").tabs();','js/bootstrap-tabs.js');
?>
<?php echo validation_errors('<div class="error">', '</div>'); ?>
<?php echo form_open($this->uri->uri_string()); ?>
<ul class="tabs">
    <li class="active"><a href="#site">Site Ayarları</a></li>
    <li><a href="#seo">SEO Ayarları</a></li>
    <li><a href="#other">Diğer</a></li>
</ul>           
<div class="tab-content">
    <div id="site" class="tab-pane active">
        <?php echo form_item('site_name', 'Site Adı', 'input', array('value' => set_value('site_name', get_option('site_name')))); ?>
        <?php echo form_item('site_email', 'E-Posta Adresi', 'input', array('value' => set_value('site_email', get_option('site_email')))); ?>
        <div class="clearfix">
            <?php echo form_label('Sayfa başına öğe sayısı', 'per_page'); ?>
            <div class="input">
                <?php echo form_dropdown('per_page', $item_size, set_value('per_page', get_option('per_page')), 'id="per_page"'); ?>
            </div>
        </div>
        <div class="clearfix">
            <?php echo form_label('Sayfa başına öğe sayısı (Yönetim)', 'per_page_admin'); ?>
            <div class="input">
                <?php echo form_dropdown('per_page_admin', $item_size, set_value('per_page_admin', get_option('per_page_admin')), 'id="per_page_admin"'); ?>
            </div>
        </div>
        <div class="clearfix">
            <?php echo form_label('Hata ayıklama modu', 'debug'); ?>
            <div class="input">        
                <?php echo form_dropdown('debug', array(0 => 'Kapalı', 1 => 'Açık'), set_value('debug', get_option('debug')), 'id="debug"'); ?>
            </div>
        </div>
    </div>
    <div id="seo" class="tab-pane">
        <?php echo form_item('site_title', 'Anasayfa Title', 'input', array('value' => set_value('site_title', get_option('site_title')))); ?>
        <?php echo form_item('site_description', 'Anasayfa Description', 'textarea', array('rows' => 2, 'value' => set_value('site_description', get_option('site_description')))); ?>
        <?php echo form_item('site_keywords', 'Anasayfa Keywords', 'input', array('value' => set_value('site_keywords', get_option('site_keywords')))); ?>
        <?php echo form_item('google_verify', 'Google doğrulama kodu', 'input', array('value' => set_value('google_verify', get_option('google_verify')))); ?>
    </div>
    <div id="other" class="tab-pane">
        <?php echo form_item('feedburner_username', 'Feedburner Kullanıcı Adı', 'input', array('value' => set_value('feedburner_username', get_option('feedburner_username')))); ?>
        <?php echo form_item('analytics_id', 'Google Analytics ID (UA-XXXXX-X)', 'input', array('value' => set_value('analytics_id', get_option('analytics_id')))); ?>
        <?php echo form_item('disqus', 'Disqus Hesabı', 'input', array('value' => set_value('disqus', get_option('disqus')))); ?>
        <?php echo form_item('disqus_api_key', 'Disqus API Key', 'input', array('value' => set_value('disqus_api_key', get_option('disqus_api_key')))); ?>
    </div>
</div>    
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>
