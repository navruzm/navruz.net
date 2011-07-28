<?php

add_css('style');
add_js('js');
add_more('<!--[if lte IE 7]><link href="css/ie.css" rel="stylesheet" type="text/css" /><![endif]-->');
add_more(link_tag('http://feeds.feedburner.com/' . get_option('feedburner_username'), 'alternate', 'application/rss+xml', 'RSS Feed'));
analytics_code();
$this->load->helper('navigation/navigation');
?>
<!-- skip link navigation -->
<ul id="skiplinks">
    <li><a class="skip" href="<?php echo current_url() ?>#nav">Menüye git (Enter'a basın).</a></li>
    <li><a class="skip" href="<?php echo current_url() ?>#col3">İçeriğe git (Enter'a basın).</a></li>
</ul>
<div class="page_margins">
    <div class="page">
        <div id="header">
            <h2><?php echo anchor('', get_option('site_name'), 'title="' . get_option('site_name') . '"'); ?></h2>
            <span><?php echo get_option('site_description') ?></span>
        </div>
        <!-- begin: main navigation #nav -->
        <div id="navigation">

        </div>
        <div id="nav">

            <div class="hlist">
                <?php echo get_navigation('{HEAD_MENU}'); ?>
            </div>
        </div>

        <!-- end: main navigation -->
        <!-- begin: main content area #main -->
        <div id="main" class="<?php echo css_class_name(); ?>">

            <!-- begin: #col1 - first float column -->
            <div id="col1">
                <div id="col1_content" class="clearfix">
                    <?php echo get_block('left'); ?>
                </div>
            </div>
            <div id="col2">
                <div id="col2_content" class="clearfix">
                    <?php echo get_block('right'); ?>
                </div>
            </div>
            <!-- end: #col1 -->
            <!-- begin: #col3 static column -->
            <div id="col3">
                <div id="col3_content" class="clearfix">
                    <?php echo get_block('center_top'); ?>
                    <?php echo $content; ?>
                    <?php echo get_block('center_bottom'); ?>
                </div>
                <div id="ie_clearing">&nbsp;</div>
                <!-- End: IE Column Clearing -->
            </div>
        </div>
        <!-- end: #main -->
        <!-- begin: #footer -->
        <div id="footer">
            Sayfa {elapsed_time} saniyede oluşturuldu.<br/>
            &copy; 2011 navruz.net<br/>
            Powered by <a href="http://www.yaml.de/" title="YAML">YAML</a>, <a href="http://www.codeigniter.com/" title="Codeigniter">Codeigniter</a>, <a
                href="http://www.famfamfam.com/" title="Famfamfam">Silk Icons</a>
        </div>
        <!-- end: #footer -->
    </div>
</div>