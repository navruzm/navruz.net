Merhaba<?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>,

<?php echo $site_name; ?> üzerindeki e-posta adresini değiştirdiniz.
E-posta adresinizi doğrulamak için aşağıdaki bağlantıyı takip edin:

<?php echo site_url('/user/change_email/'.$user_id.'/'.$new_email_key); ?>


Yeni e-posta adresiniz: <?php echo $new_email; ?>


<?php echo $site_name; ?> üzerindeki e-posta adresini değiştirdiğiniz için bu mesajı aldınız. Bunun bir hata olduğunu düşünüyorsanız lütfen doğrulama bağlantısına TIKLAMAYINIZ ve bu postayı siliniz. Kısa bir süre sonra bu istek sistem tarafından otomatik olarak silinecektir.


Teşekkürler,
<?php echo $site_name; ?> Ekibi