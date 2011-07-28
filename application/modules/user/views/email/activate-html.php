<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title><?php echo $site_name; ?> sitesine hoşgeldiniz!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%"></td>
            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo $site_name; ?> sitesine hoşgeldiniz!</h2>
                Bize katıldığınız için teşekkürler. Kayıt bilgilerinizi aşağıdaki listeledik..<br />
                E-posta adresinizi doğrulamak için lütfen aşağıdaki bağlantıyı takip edin:<br />
                <br />
        <big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/user/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Kaydınızı tamamlayın...</a></b></big><br />
        <br />
        Bağlantı çalışmıyormu? Aşağıdaki bağlantıyı tarayıcınızın adres satırına kopyalayarak deneyin:<br />
        <nobr><a href="<?php echo site_url('/user/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/user/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
        <br />
        Lütfen e-posta adresinizi <?php echo $activation_period; ?> saat içerisinde doğrulayın, aksi takdirde kaydınız geçersiz olacak ve tekrar kayıt olmanız gerekecek.<br />
        <br />
        <br />
        <?php if (strlen($username) > 0) { ?>Kullanıcı adınız: <?php echo $username; ?><br /><?php } ?>
        E-Posta adresiniz: <?php echo $email; ?><br />
        <?php if (isset($password)) { ?>Şifreniz: <?php echo $password; ?><br /><?php } ?>
        <br />
        <br />
        İyi Eğlenceler!<br />
        <?php echo $site_name; ?> Ekibi
        </td>
        </tr>
    </table>
</div>
</body>
</html>