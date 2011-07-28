<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>İletişim Formu'ndan Mesaj Var!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
    <table width="80%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="5%"></td>
            <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">
                    İletişim Formu üzerinden yeni mesaj aldınız!</h2>
                <b>Gönderen : </b> <?php echo $name;?><br>
                <b>E-Posta Adresi : </b> <?php echo $email;?><br>
                <b>Tarih : </b> <?php echo date('d-M-Y', time());?><br>
                <b>Mesaj : </b>
                <?php echo $message;?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>