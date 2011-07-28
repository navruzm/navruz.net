<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedburner
{

    private $ci;

    function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('cache', array('cache_path' => 'cache', 'cache_expiration' => 86400));
    }

    public function get_circulation()
    {

        $feed_data = $this->ci->cache->get('data', 'feedburner');
        if ($feed_data !== FALSE)
        {
            return $feed_data;
        }
        else
        {
            $feed_data = $this->get_data();
            $this->ci->cache->write('data', 'feedburner', $feed_data, 86400);
            return $feed_data;
        }
    }

    function get_data()
    {
        $url = 'http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=' . get_option('feedburner_username');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($response);
        return (isset($xml->feed->entry['circulation'])) ? (int)$xml->feed->entry['circulation'] : 0;
    }

}