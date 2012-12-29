<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
    <head>
        <title><?php echo 'Yönetici Girişi' ?></title>
        <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
        <base href="<?php echo base_url() ?>" />
        <?php echo link_tag('css/admin.css'); ?>
        <script type="text/javascript" src="js/js.js"></script>
    </head>
    <body>
        <div class="page_margins" style="max-width:300px;min-width:300px;">
            <div class="page" style="background-color:#fff;">

                <?php echo isset($error)
                            ? $error : '' ?>
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
            </div>
        </div>
    </body>
</html>