<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Yeni e-posta adresiniz - <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%"></td>
            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Yeni e-posta adresiniz - <?php echo $site_name; ?></h2>
                <?php echo $site_name; ?> üzerindeki e-posta adresini değiştirdiniz. <br />
                E-posta adresinizi doğrulamak için aşağıdaki bağlantıyı takip edin:<br />
                <br />
        <big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/user/change_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Confirm your new email</a></b></big><br />
        <br />
        Bağlantı çalışmıyormu? Aşağıdaki bağlantıyı tarayıcınızın adres satırına kopyalayarak deneyin:<br />
        <nobr><a href="<?php echo site_url('/user/change_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/user/change_email/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
        <br />
        <br />
        E-posta adresiniz: <?php echo $new_email; ?><br />
        <br />
        <br />
        <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> üzerindeki e-posta adresini değiştirdiğiniz için bu mesajı aldınız. Bunun bir hata olduğunu düşünüyorsanız lütfen doğrulama bağlantısına TIKLAMAYINIZ ve bu postayı siliniz. Kısa bir süre sonra bu istek sistem tarafından otomatik olarak silinecektir.<br />
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