<?php


$this->load->view('control_panel');
$old_password = array(
    'name'	=> 'old_password',
    'id'	=> 'old_password',
    'value' => set_value('old_password'),
    'size' 	=> 30,
);
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
<?php echo form_open(config_item('auth_uri_change_password'),array('class'=>'yform columnar')); ?>
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
<div class="buttonbox">
    <?php echo form_submit('change', 'Şifreyi Değiştir','class="awesome"'); ?>
</div>
<?php echo form_close(); ?>