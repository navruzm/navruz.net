<?php echo isset($error) ? $error : '' ?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
    <?php echo form_label(lang('auth_new_password'), 'password'); ?>
    <?php echo form_password('password', '', 'id="password"'); ?>
    <?php echo form_error('password'); ?>
</div>
<div>
    <?php echo form_label(lang('auth_retype_password'), 'confirm_password'); ?>
    <?php echo form_password('confirm_password', '', 'id="confirm_password"'); ?>
    <?php echo form_error('confirm_password'); ?>
</div>
<?php echo form_submit('submit', lang('auth_send')); ?>
<?php echo form_close(); ?>