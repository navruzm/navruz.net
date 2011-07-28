<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ping {

    private $services = array(
        'Google' => 'http://www.google.com/webmasters/tools/ping?sitemap=%s',
        'Yahoo' => 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=%s',
        'Bing' => 'http://www.bing.com/webmaster/ping.aspx?siteMap=%s'
    );

    public function send()
    {
        $response = array();
        $sitemap_url = urlencode(base_url() . 'sitemap.xml');
        foreach ($this->services as $service => $url)
        {
            $response[$service] = $this->send_ping(sprintf($url, $sitemap_url));
        }
        return $response;
    }

    private function send_ping($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_REFERER, base_url());
        curl_setopt($ch, CURLOPT_USERAGENT, 'Codeigniter');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        if (!curl_errno($ch))
        {
            $header_info = curl_getinfo($ch);
            if ((int) $header_info['http_code'] == 200)
            {
                $return = TRUE;
            }
        }
        curl_close($ch);
        return FALSE;
    }

}

/* End of file Ping.php */