<?php
add_css('style');
add_js('js');
add_css('

#links {
    height: 120px;
    position: relative;
    list-style-type: none;
    padding: 0;
    margin: 0;
}
#links li {
    position: absolute;
    width: 114px;
    height: 50px;
}
#links li a {
    display: block;
    height: 50px;
    padding-left: 54px;
    background-repeat: no-repeat;
    color: #666666;
    font-size: 10px;
    line-height: 12px;
}
#links li a strong {
    color: #000000;
}
#links li a span {
    display: block;
    padding-top: 12px;
}
#links li a:hover {
    color: #999999;
}
#links li a:hover strong {
    color: #666666;
}

#links li#link-chrome a {
    background-image: url(assets/img/browsers/chrome.jpg);
}
#links li#link-firefox {
    left: 115px;
}
#links li#link-firefox a {
    background-image: url(assets/img/browsers/firefox.jpg);
}
#links li#link-safari {
    left: 230px;
}
#links li#link-safari a {
    background-image: url(assets/img/browsers/safari.jpg);
}
#links li#link-ie {
    left: 340px;
}
#links li#link-ie a {
    background-image: url(assets/img/browsers/ie.jpg);
}
#links li#link-ie a span {
    padding-top: 6px;
}
#links li#link-opera {
    left:460px;
}
#links li#link-opera a {
    background-image: url(assets/img/browsers/opera.jpg);
}
#links li#link-opera a span {
    padding-top: 18px;
}', 'embed');
?>
<div class="page_margins" style="width:600px;padding: 100px 10px 10px 10px;">
    <div class="page" style="padding: 10px 20px;">
        <?php echo heading('Tarayıcınız Çok Eski !', 2); ?><br/>
        Kullandığınız Tarayıcı günümüz web teknolojisinin gerisinde kaldığı için sitemizdeki bazı uygulamaları
        görüntüleme yeteneğine sahip <strong>değildir.</strong><br />
        <strong>Lütfen aşağıdaki modern tarayıcılardan birini yükleyin:</strong>
        <hr/>
        <ul id="links">
            <li id="link-chrome"><a href="http://www.google.com/chrome" title="Download Chrome"><span>Google<br /><strong>Chrome</strong></span></a></li>
            <li id="link-firefox"><a href="http://www.mozilla.com" title="Download Firefox"><span>Mozilla<br /><strong>Firefox</strong></span></a></li>
            <li id="link-safari"><a href="http://www.apple.com/safari/" title="Download Safari"><span>Apple<br /><strong>Safari</strong></span></a></li>

            <li id="link-ie"><a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" title="Download Internet Explorer"><span>Microsoft<br /><strong>Internet<br />Explorer</strong></span></a></li>
            <li id="link-opera"><a href="http://www.opera.com" title="Download Opera"><span><strong>Opera</strong></span></a></li>
        </ul>

    </div>
</div>
<?php echo analytics_code(); ?>