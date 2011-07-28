<?php echo $site_name; ?> sitesine hoşgeldiniz!

Bize katıldığınız için teşekkürler. Kayıt bilgilerinizi aşağıdaki listeledik.
E-posta adresinizi doğrulamak için lütfen aşağıdaki bağlantıyı takip edin:

<?php echo site_url('/user/activate/'.$user_id.'/'.$new_email_key); ?>


Lütfen e-posta adresinizi <?php echo $activation_period; ?> saat içerisinde doğrulayın, aksi takdirde kaydınız geçersiz olacak ve tekrar kayıt olmanız gerekecek.
<?php if (strlen($username) > 0) { ?>

Kullanıcı Adınız: <?php echo $username; ?>
<?php } ?>

E-posta Adresiniz: <?php echo $email; ?>
<?php if (isset($password)) { ?>

Şifreniz: <?php echo $password; ?>
<?php } ?>



<?php echo $site_name; ?> Ekibi
