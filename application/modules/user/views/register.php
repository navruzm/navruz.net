<?php echo isset($error) ? $error : '' ?>
<?php echo validation_errors(); ?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
    <?php echo form_label(lang('auth_email'), 'email'); ?>
    <?php echo form_input('email', set_value('email'), 'id="email"'); ?>
</div>
<div>
    <?php echo form_label(lang('auth_password'), 'password'); ?>
    <?php echo form_password('password', '', 'id="password"'); ?>
</div>
<div>
    <?php echo form_label(lang('auth_retype_password'), 'confirm_password'); ?>
    <?php echo form_password('confirm_password', '', 'id="confirm_password"'); ?>
</div>
<div>
    <?php echo form_label(lang('auth_name'), 'name'); ?>
    <?php echo form_input('name', set_value('auth_name'), 'id="name"'); ?>
</div>
<?php echo form_submit('submit', lang('auth_send')); ?>
<?php echo form_close(); ?>