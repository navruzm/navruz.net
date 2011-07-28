<?php
add_css('mobile');
add_js('js');
add_more(link_tag('http://feeds.feedburner.com/' . get_option('feedburner_username'), 'alternate', 'application/rss+xml', 'RSS Feed'));
analytics_code();
$this->load->helper('navigation/navigation');
$this->template->add_meta(array(
    'name' => 'viewport',
    'content' => 'width=320;')
);
?>
<div class="page_margins">
    <div class="page">
        <div id="header">
            <h2><?php echo anchor('', get_option('site_name'), 'title="' . get_option('site_name') . '"'); ?></h2>
            <span><?php echo get_option('site_description') ?></span>
        </div>
        <!-- begin: main navigation #nav -->
        <div id="nav">

            <div class="hlist">
<?php echo get_navigation('{HEAD_MENU}'); ?>
            </div>
        </div>

        <!-- end: main navigation -->
        <!-- begin: main content area #main -->
        <div id="main">

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
            &copy; 2011 navruz.net (Mobile)<br/>
            Powered by <a href="http://www.yaml.de/">YAML</a>, <a href="http://www.codeigniter.com/">Codeigniter</a>, <a
                href="http://www.famfamfam.com/">Silk Icons</a>
        </div>
        <!-- end: #footer -->
    </div>
</div>