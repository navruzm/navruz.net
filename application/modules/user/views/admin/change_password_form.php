<?php

$old_password = array(
    'name'    => 'old_password',
    'id'    => 'old_password',
    'value' => set_value('old_password'),
    'size'     => 30,
);
$new_password = array(
    'name'    => 'new_password',
    'id'    => 'new_password',
    'maxlength'    => $this->config->item('password_max_length'),
    'size'    => 30,
);
$confirm_new_password = array(
    'name'    => 'confirm_new_password',
    'id'    => 'confirm_new_password',
    'maxlength'    => $this->config->item('password_max_length'),
    'size'     => 30,
);
?>
<?php echo form_open($this->uri->uri_string(),array('class'=>'yform columnar')); ?>
<?php echo form_fieldset('Şifrenizi Değiştirin'); ?>

<div class="type-text">
<?php echo form_label('Eski Şifre', $old_password['id']); ?>
    <?php echo form_password($old_password); ?>
    <?php echo form_error($old_password['name']); ?>
    <?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?>
</div>
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
<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>