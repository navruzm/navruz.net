<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr">
    <head>
        <title>Sayfa Bulunamadı</title>
        <base href="<?php echo base_url(); ?>" />
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <style type="text/css">
            body {
                background-color:#fff;
                margin:	40px;
                font-family:"Lucida Grande",Verdana,'Trebuchet MS',Helvetica,Arial,sans-serif;
                font-size:14px;
                color:#000;
                text-align: center;
                line-height: 1.6em;
            }
            #wrapper {
                margin:0 auto;
                width: 500px;
            }
            #error{
                font-family:"Helvetica Neue","Lucida Sans Unicode",sans-serif;
                font-weight: bold;
                font-size: 3em;
                line-height: 1.3em;
                margin-bottom: 20px;
            }
            #error #message{
                font-size: 0.5em;
                line-height: 1.3em;
            }
            ul {

            }
            ul li {
                text-align: left;
                padding: 4px 4px 4px 40px;
                border: 1px solid #EFEFEF;
                margin-bottom: 5px;
                list-style: none;
                background-color: #f9f9f9;
            }
            #home {background: transparent url(assets/img/404/home.png) no-repeat left center;}
            #contact {background: transparent url(assets/img/404/contact.png) no-repeat left center;}
            #search {background: transparent url(assets/img/404/search.png) no-repeat left center;}
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="error">
                <div id="code">{404}</div>
                <div id="message">Sayfa Bulunamadı</div>
            </div>
            İstekte bulunduğunuz sayfa yada dosya bulunamamıştır.<br />
            Aradığınız sayfa yayından kaldırılmış veya adresi değişmiş olabilir.<br />
            <ul>
                <li id="home"><?php echo anchor('', 'Anasayfa'); ?>'ya dönüp istediğiniz sayfaya ulaşmayı deneyebibilirsiniz.</li>
                <li id="search"><?php echo anchor('search', 'Arama'); ?> sayfamıza giderek arama yapabilirsiniz.</li>
                <li id="contact">Eğer bir teknik bir hatadan dolayı bu sayfayı gördüğünüzü düşünüyorsanız
                    <?php echo anchor('contact', 'İletişim'); ?> bölümünden bizlere ulaşabilirsiniz.</li>
            </ul>


        </div>

    </body>
</html>