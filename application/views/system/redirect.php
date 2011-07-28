<?php

$this->output->set_header("HTTP/1.1 200 OK");
$this->output->set_header("Pragma: no-cache");
$this->output->set_header("Refresh: $wait;url=" . $redirect);

$css = 'body {margin-top:200px;font-family:"Lucida Grande",Verdana;font-size:12px;color:#000;}
        #content {border:#999 1px solid;background:#fff;padding:15px;width:400px;margin:0 auto;line-height:18px;text-align:center;}
        h1 {font-weight:bold;font-size:14px;color:#990000;margin: 0 0 4px 0;}';
$this->template->add_css($css, 'embed');
$this->template->add_css('style');
$this->template->set_title($message);
$this->template->add_meta(array(
    'name' => 'refresh',
    'content' => $wait . ';URL=' . $redirect,
    'type' => 'equiv'));
$this->template->add_meta(array(
    'name' => 'robots',
    'content' => 'noindex,nofollow'));
?>
<div id="content">
    <h1><?php echo $message; ?></h1>
    Biraz bekleyin, hemen yönlendirileceksiniz...<br/>
    <a href="<?php echo $redirect; ?>">Beklemek istemiyorsanız buraya tıklayınız.</a>
</div>