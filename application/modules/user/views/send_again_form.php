<?php

$email = array(
    'name'	=> 'email',
    'id'	=> 'email',
    'value'	=> set_value('email'),
    'maxlength'	=> 80,
    'size'	=> 30,
);
?>
<?php echo form_open(config_item('auth_uri_send_again'),array('class'=>'yform columnar')); ?>
<div class="type-text">
    <?php echo form_label('E-Posta adresi', $email['id']); ?>
    <?php echo form_input($email); ?>
    <?php echo form_error($email['name']); ?>
    <?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?>
</div>
<div class="buttonbox">
    <?php echo form_submit('send', 'Gönder','class="awesome"'); ?>
</div>
<?php echo form_close(); ?>