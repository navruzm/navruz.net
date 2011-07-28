<?php echo $site_name; ?> sitesine hoşgeldiniz,

Bize katıldığınız için teşekkürler. Kayıt bilgilerinizi aşağıda listeledik. Lütfen bunları güvenle saklayınız.
Giriş yapmak için aşağıdaki bağlantıyı takip edin:

<?php echo site_url('/user/login/'); ?>

<?php if (strlen($username) > 0) { ?>

Kullanıcı adınız: <?php echo $username; ?>
<?php } ?>

E-posta adresiniz: <?php echo $email; ?>

Şifreniz: <?php echo $password; ?>




<?php echo $site_name; ?> Ekibi