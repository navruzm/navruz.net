<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->block->config['feedburner'] = array(
    'name' => 'Rss',
    'is_public' => 1,
    'module' => 'home',
);

function block_feedburner()
{
    $ci = & get_instance();
    $ci->load->library('feedburner');
    $count = $ci->feedburner->get_circulation();

    $html = 'Sitemizi <b>' . $count . '</b> kişi takip ediyor.
                Sizde takip etmek isterseniz <b>' . anchor('http://feeds.feedburner.com/' . get_option('feedburner_username'), 'RSS') . '</b> adresimizi ekleyebilirsiniz.';
    return $html;
}
