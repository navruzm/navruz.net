<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Yeni şifre oluşturun - <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%"></td>
            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Yeni şifre oluşturun</h2>
                Şifrenizi mi unuttunuz? Önemli değil.<br />
                Yeni şifre oluşturmak için aşağıdaki bağlantıyı takip etmeniz yeterli:<br />
                <br />
        <big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/user/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">Yeni şifre oluştur</a></b></big><br />
        <br />
        Bağlantı çalışmıyormu? Aşağıdaki bağlantıyı tarayıcınızın adres satırına kopyalayarak deneyin:<br />
        <nobr><a href="<?php echo site_url('/user/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/user/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
        <br />
        <br />
        <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> sitesindeki şifrenizi unuttuğunuzu bildirdiğiniz için bu mesajı aldınız. Eğer böyle bir istekte BULUNMADIYSANIZ lütfen bu e-postayı dikkate almayınız. Böylece e-posta adresiniz ve şifreniz değişmemiş olur.<br />
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