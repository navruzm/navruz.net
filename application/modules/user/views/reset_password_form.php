<?php

$new_password = array(
    'name'	=> 'new_password',
    'id'	=> 'new_password',
    'maxlength'	=> $this->config->item('password_max_length'),
    'size'	=> 30,
);
$confirm_new_password = array(
    'name'	=> 'confirm_new_password',
    'id'	=> 'confirm_new_password',
    'maxlength'	=> $this->config->item('password_max_length'),
    'size' 	=> 30,
);
?>
<div class="success">
    Şifre sıfırlama işleminiz tamamlanmak üzere. Lütfen aşağıdaki formu doldurarak yeni şifrenizi kaydediniz.
</div>
<?php echo form_open($this->uri->uri_string(),array('class'=>'yform columnar')); ?>
<div class="type-text">
    <?php echo form_label('Yeni Şifre', $new_password['id']); ?>
    <?php echo form_password($new_password); ?>
    <?php echo form_error($new_password['name']); ?>
    <?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?>
</div>
<div class="type-text">
    <?php echo form_label('Yeni Şifreyi Doğrula', $confirm_new_password['id']); ?>
    <?php echo form_password($confirm_new_password); ?>
    <?php echo form_error($confirm_new_password['name']); ?>
    <?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?>
</div>
<div class="buttonbox">
    <?php echo form_submit('change', 'Şifreyi Değiştir','class="awesome"'); ?>
</div>
<?php echo form_close(); ?>