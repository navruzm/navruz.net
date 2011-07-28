Merhaba <?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>,

Şifrenizi mi unuttunuz? Önemli değil.
Yeni şifre oluşturmak için aşağıdaki bağlantıyı takip etmeniz yeterli:

<?php echo site_url('/user/reset_password/'.$user_id.'/'.$new_pass_key); ?>


<?php echo $site_name; ?> sitesindeki şifrenizi unuttuğunuzu bildirdiğiniz için bu mesajı aldınız. Eğer böyle bir istekte BULUNMADIYSANIZ lütfen bu e-postayı dikkate almayınız. Böylece e-posta adresiniz ve şifreniz değişmemiş olur.

Teşekkürler,
<?php echo $site_name; ?> Ekibi