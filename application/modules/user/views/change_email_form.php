<?php

$this->load->view('control_panel');
$password = array(
    'name' => 'password',
    'id' => 'password',
    'size' => 30,
);
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email'),
    'maxlength' => 80,
    'size' => 30,
);
?>
<?php echo form_open(config_item('auth_uri_change_email'), array('class' => 'yform columnar')); ?>
<div class="type-text">
<?php echo form_label('Şifre', $password['id']); ?>
    <?php echo form_password($password); ?>
    <?php echo form_error($password['name']); ?>
    <?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : ''; ?>
</div>
<div class="type-text">
<?php echo form_label('Yeni eposta adresi', $email['id']); ?>
    <?php echo form_input($email); ?>
    <?php echo form_error($email['name']); ?>
    <?php echo isset($errors[$email['name']]) ? $errors[$email['name']] : ''; ?>
</div>
<div class="buttonbox">
<?php echo form_submit('change', 'Doğrulama mailini gönder', 'class="awesome"'); ?>
</div>
<?php echo form_close(); ?>