<?php

$password = array(
    'name'	=> 'password',
    'id'	=> 'password',
    'size'	=> 30,
);
?>
<?php echo form_open(config_item('auth_uri_delete'),array('class'=>'yform columnar')); ?>
<div class="type-text">
    <?php echo form_label('Şifre', $password['id']); ?>
    <?php echo form_password($password); ?>
    <?php echo form_error($password['name']); ?>
    <?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>
</div>
<div class="buttonbox">
    <?php echo form_submit('cancel', 'Üyeliğimi Sil','class="awesome"'); ?>
</div>
<?php echo form_close(); ?>