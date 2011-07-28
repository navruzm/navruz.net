<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Yeni şifreniz - <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%"></td>
            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Yeni şifreniz - <?php echo $site_name; ?></h2>
                Şifrenizi değiştirdiniz.<br />
                Lütfen bunu kayıtlarınıza alın ve unutmayın..<br />
                <br />
                <?php if (strlen($username) > 0) { ?>Kullanıcı adınız: <?php echo $username; ?><br /><?php } ?>
                E-posta adresiniz: <?php echo $email; ?><br />
                Yeni şifreniz: <?php echo $new_password; ?><br />
                <br />
                <br />
                Teşekkürler,<br />
                <?php echo $site_name; ?> Ekibi
            </td>
        </tr>
    </table>
</div>
</body>
</html>