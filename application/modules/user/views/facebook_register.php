<?php

$this->load->helper('place/place');
if ($use_username)
{
    $username = array(
        'name' => 'username',
        'id' => 'username',
        'value' => set_value('username'),
        'maxlength' => $this->config->item('username_max_length'),
        'size' => 30,
    );
}
$password = array(
    'name' => 'password',
    'id' => 'password',
    'value' => set_value('password'),
    'maxlength' => $this->config->item('password_max_length'),
    'size' => 30,
);
$confirm_password = array(
    'name' => 'confirm_password',
    'id' => 'confirm_password',
    'value' => set_value('confirm_password'),
    'maxlength' => $this->config->item('password_max_length'),
    'size' => 30,
);
?>

<div class="title">Kayıt Ol</div>
<div class="warning">
    Sitemize kayıt yapmamışsınız. Sitemize Facebook hesabınızla erişmek için bir defaya mahsus kayıt olmanız gerekiyor.
    Kayıt olsuktan sonra ister Facebook hesabınız üzerinden,ister sitemiz üzerinden aldığınız şifrenizle giriş yapabilirsiniz.
</div>
<?php echo form_open(config_item('auth_uri_facebook_register'), array('class' => 'yform full')); ?>
<?php if ($use_username) : ?>
    <div class="type-text">
<?php echo form_label('Kullanıcı Adı *', $username['id']); ?>
    <?php echo form_input($username); ?>
    <?php echo form_error($username['name']); ?>
    <?php echo isset($errors[$username['name']]) ? $errors[$username['name']] : ''; ?>
</div>
<?php endif; ?>
<div class="type-text">
<?php echo form_label('Şifre', $password['id']); ?>
    <?php echo form_password($password); ?>
    <?php echo form_error($password['name']); ?>
</div>
<div class="type-text">
<?php echo form_label('Şifreyi Doğrula', $confirm_password['id']); ?>
    <?php echo form_password($confirm_password); ?>
    <?php echo form_error($confirm_password['name']); ?>
</div>
<div class="subcl type-select">
<?php echo form_label('Kasaba ve Köyünüz *', 'place_id'); ?>
    <?php echo form_dropdown('place_id', place_select(array('0'=>'Diğer')), set_value('place_id'), 'id="place_id"'); ?>
    <?php echo form_error('place_id'); ?>
</div>
"*" ile belirtilen alanlar zorunludur.
<div class="buttonbox">
<?php echo form_submit('register', 'Kayıt Ol', 'class="awesome"'); ?>
</div>
<?php echo form_close(); ?>
