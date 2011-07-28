<?php
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email',$user->email),
    'maxlength' => 80,
    'size' => 30,
);
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
<?php echo form_open($this->uri->uri_string(),array('class'=>'yform columnar')); ?>
<?php echo form_fieldset('Üye Bilgilerini Değiştirin'); ?>
<div class="type-text">
<?php echo form_label('E-Posta Adresi', $email['id']); ?>
<?php echo form_input($email); ?>
    <?php echo form_error($email['name']); ?>
    <?php echo isset($errors[$email['name']]) ? $errors[$email['name']] : ''; ?>
</div>
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
<div class="type-select">
<?php echo form_label('Üye grubu', 'user_group'); ?>
<?php echo form_dropdown('user_group', $groups, $user->user_group, 'id="user_group"'); ?>
    <?php echo form_error('user_group'); ?>
</div>
<div class="buttonbox">
<?php echo form_submit('change', 'Değiştir','class="awesome"'); ?>
</div>
<?php echo form_fieldset_close();?>
<?php echo form_close(); ?>