<?php echo isset($error) ? $error : '' ?>
<?php echo form_open($this->uri->uri_string()); ?>
<div>
    <?php echo form_label(lang('auth_email'), 'email'); ?>
    <?php echo form_input('email', set_value('email'), 'id="email"'); ?>
    <?php echo form_error('email'); ?>
</div>
<div>
    <?php echo form_label(lang('auth_password'), 'password'); ?>
    <?php echo form_password('password', '', 'id="password"'); ?>
    <?php echo form_error('password'); ?>
</div>
<?php if (isset($recaptcha)): ?>
    <div>
        <?php echo $recaptcha; ?>
        <?php echo form_error('recaptcha_response_field'); ?>
    </div>
<?php endif; ?>
<div>
    <?php echo form_checkbox('remember', 1, TRUE, 'id="remember"'); ?>
    <?php echo form_label(lang('auth_remember_me'), 'remember'); ?>
</div>
<?php echo form_submit('submit', lang('auth_send')); ?>
<?php echo form_close(); ?>
<?php echo anchor('user/forgot_password', 'Forgot Password'); ?>