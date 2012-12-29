<?php echo isset($error) ? $error : '' ?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
    <?php echo form_label(lang('auth_email'), 'email'); ?>
    <?php echo form_input('email', set_value('email'), 'id="email"'); ?>
    <?php echo form_error('email'); ?>
</div>
<?php echo form_submit('submit', lang('auth_send')); ?>
<?php echo form_close(); ?>