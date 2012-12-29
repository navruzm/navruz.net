<?php
$this->template->add_meta(array(
    'name' => 'description',
    'content' => $this->template->get_description())
);

$this->template->add_meta(array(
    'name' => 'keywords',
    'content' => $this->template->get_keywords())
);

if (get_option('google_verify') != '')
{
    $this->template->add_meta(array(
        'name' => 'google-site-verification',
        'content' => get_option('google_verify'))
    );
}

$this->load->helper('category/category');
$this->template->add_more(link_tag('http://feeds.feedburner.com/' . get_option('feedburner_username'), 'alternate', 'application/rss+xml', 'RSS Feed'));
add_css_minify('styles.css');
add_js_jquery('','themes/default/js/default.js');
if(ENVIRONMENT=='production')
{
    analytics_code();
}
