<?php

$login = array(
    'name'	=> 'login',
    'id'	=> 'login',
    'value' => set_value('login'),
    'maxlength'	=> 80,
    'size'	=> 30,
);
if ($this->config->item('use_username')) {
    $login_label = 'Kullanıcı adı veya e-posta';
} else {
    $login_label = 'E-Posta';
}
?>
<div class="info">
    Lütfen şifre sıfırlama mesajının e-posta adresinize gönderilebilmesi için sitemize kaydolurken kullandığınız e-posta adresinizi
    veya kullanıcı adınızı giriniz.
</div>
<?php echo form_open(config_item('auth_uri_forgot_password'),array('class'=>'yform columnar')); ?>
<div class="type-text">
    <?php echo form_label($login_label, $login['id']); ?>
    <?php echo form_input($login); ?>
    <?php echo form_error($login['name']); ?>
    <?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
</div>
<div class="buttonbox">
    <?php echo form_submit('reset', 'Gönder','class="awesome"'); ?>
</div>
<?php echo form_close(); ?>