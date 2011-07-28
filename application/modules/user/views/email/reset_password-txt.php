Merhaba <?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>,

Şifrenizi değiştirdiniz.
Lütfen kayıtlarınıza alın ve unutmayın.
<?php if (strlen($username) > 0) { ?>

Kullanıcı adınız: <?php echo $username; ?>
<?php } ?>

E-posta adresiniz: <?php echo $email; ?>

Yeni şifreniz: <?php echo $new_password; ?>



Teşekkürler,
<?php echo $site_name; ?> Ekibi