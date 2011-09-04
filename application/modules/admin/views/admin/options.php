<?php
$item_size = array(
    5 => 5,
    10 => 10,
    15 => 15,
    20 => 20,
    50 => 50,
);
add_css('ui');
add_js('ui');
add_jquery('$("#tabs").tabs();');
?>
<?php echo validation_errors('<div class="error">', '</div>'); ?>
<div id="tabs" class="subcolumns">
    <ul style="/*display: inline-block;*/">
        <li><a href="#site">Site Ayarları</a></li>
        <li><a href="#seo">SEO Ayarları</a></li>
        <li><a href="#maintanence">Bakım Modu</a></li>
        <li><a href="#other">Diğer</a></li>
    </ul>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'yform full nobg')); ?>

    <div id="site">
        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('site_name', 'Site Adı', 'input', array('value' => set_value('site_name', get_option('site_name')))); ?>
                </div>
            </div>
            <div class="c50r">
                <div class="subcl type-text">
                    <?php echo form_item('site_email', 'E-Posta Adresi', 'input', array('value' => set_value('site_email', get_option('site_email')))); ?>
                </div>
            </div>
        </div>
        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('site_description', 'Site Açıklaması', 'textarea', array('rows' => 2, 'value' => set_value('site_description', get_option('site_description')))); ?>
                </div>
            </div>
            <div class="c50r">
                <div class="subcl type-text">
                    <?php echo form_item('site_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 2, 'value' => set_value('site_keywords', get_option('site_keywords')))); ?>
                </div>
            </div>
        </div>
        <div class="subcolumns">
            <div class="c33l">
                <div class="subcl type-select">
                    <?php echo form_label('Sayfa başına öğe sayısı', 'per_page'); ?>
                    <?php echo form_dropdown('per_page', $item_size, set_value('per_page', get_option('per_page')), 'id="per_page"'); ?>
                </div>
            </div>
            <div class="c33l">
                <div class="subcl type-select">
                    <?php echo form_label('Sayfa başına öğe sayısı (Yönetim)', 'per_page_admin'); ?>
                    <?php echo form_dropdown('per_page_admin', $item_size, set_value('per_page_admin', get_option('per_page_admin')), 'id="per_page_admin"'); ?>
                </div>
            </div>
            <div class="c33r">
                <div class="subcl type-select">
                    <?php echo form_label('Hata ayıklama modu', 'debug'); ?>
                    <?php echo form_dropdown('debug', array(0 => 'Kapalı', 1 => 'Açık'), set_value('debug', get_option('debug')), 'id="debug"'); ?>
                </div>
            </div>
        </div>

    </div>
    <div id="seo">

        <div class="type-text">
            <?php echo form_item('google_verify', 'Google doğrulama kodu', 'input', array('value' => set_value('google_verify', get_option('google_verify')))); ?>
        </div>
        <div class="type-text">
            <?php echo form_item('yahoo_verify', 'Yahoo doğrulama kodu', 'input', array('value' => set_value('yahoo_verify', get_option('yahoo_verify')))); ?>
        </div>
        <div class="type-text">
            <?php echo form_item('bing_verify', 'Bing doğrulama kodu', 'input', array('value' => set_value('bing_verify', get_option('bing_verify')))); ?>
        </div>

    </div>
    <div id="maintanence">


        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-select">
                    <?php echo form_label('Bakım modu', 'maintenance'); ?>
                    <?php echo form_dropdown('maintenance', array(0 => 'Kapalı', 1 => 'Açık'), set_value('maintenance', get_option('maintenance')), 'id="maintenance"'); ?>
                </div>
            </div>
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('maintenance-end', 'Bitiş Zamanı (gg-aa-yyyy ss:dd)', 'input', array('value' => set_value('maintenance-end', date('d-m-Y H:i', get_option('maintenance-end'))))); ?>

                </div>
            </div>
        </div>
        <div class="type-text">
            <?php echo form_item('maintenance-message', 'Bakım Mesajı', 'textarea', array('rows' => 2, 'value' => set_value('maintenance-message', get_option('maintenance-message')))); ?>
        </div>
    </div>
    <div id="other">

        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('bitly_login', 'bit.ly Kullanıcı Adı', 'input', array('value' => set_value('bitly_login', get_option('bitly_login')))); ?>
                </div>
            </div>
            <div class="c50r">
                <div class="subcl type-text">
                    <?php echo form_item('bitly_apikey', 'bit.ly Api Key', 'input', array('value' => set_value('bitly_apikey', get_option('bitly_apikey')))); ?>
                </div>
            </div>
        </div>
        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('feedburner_username', 'Feedburner Kullanıcı Adı', 'input', array('value' => set_value('feedburner_username', get_option('feedburner_username')))); ?>
                </div>
            </div>
            <div class="c50r">
                <div class="subcl type-text">
                    <?php echo form_item('analytics_id', 'Google Analytics ID (UA-XXXXX-X)', 'input', array('value' => set_value('analytics_id', get_option('analytics_id')))); ?>
                </div>
            </div>
        </div>
        <div class="subcolumns">
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('disqus', 'Disqus Hesabı', 'input', array('value' => set_value('disqus', get_option('disqus')))); ?>
                </div>
            </div>
            <div class="c50l">
                <div class="subcl type-text">
                    <?php echo form_item('disqus_api_key', 'Disqus API Key', 'input', array('value' => set_value('disqus_api_key', get_option('disqus_api_key')))); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="type-button">
        <button type="reset" class="awesome">Sıfırla</button>
        <button type="submit" class="awesome">Gönder</button>
    </div>
    <?php echo form_close(); ?>
</div>