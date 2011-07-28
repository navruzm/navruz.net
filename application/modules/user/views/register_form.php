<?php

$this->load->helper('place/place');

if ($use_username)
{
    $username = array(
        'name' => 'username',
        'id' => 'username',
        'value' => set_value('username'),
        'maxlength' => $this->config->item('username_max_length'),
        'size' => 30,
    );
}
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email'),
    'maxlength' => 80,
    'size' => 30,
);
$password = array(
    'name' => 'password',
    'id' => 'password',
    'value' => set_value('password'),
    'maxlength' => $this->config->item('password_max_length'),
    'size' => 30,
);
$confirm_password = array(
    'name' => 'confirm_password',
    'id' => 'confirm_password',
    'value' => set_value('confirm_password'),
    'maxlength' => $this->config->item('password_max_length'),
    'size' => 30,
);
$captcha = array(
    'name' => 'captcha',
    'id' => 'captcha',
    'maxlength' => 8,
);
?>
<div class="subcolumns">
    <div class="c50l">
        <div class="subcl">
            <div class="title">Doğrudan</div>
            <?php echo form_open(config_item('auth_uri_register'), array('class' => 'yform full')); ?>
            <?php if ($use_username) : ?>
                <div class="type-text">
                <?php echo form_label('Kullanıcı Adı', $username['id']); ?>
                <?php echo form_input($username); ?>
                <?php echo form_error($username['name']); ?>
                <?php echo isset($errors[$username['name']]) ? $errors[$username['name']] : ''; ?>
            </div>
            <?php endif; ?>
                <div class="type-text">
                <?php echo form_item('first_name', 'Adınız'); ?>
            </div>
            <div class="type-text">
                <?php echo form_item('last_name', 'Soyadınız'); ?>
            </div>
            <div class="type-text">
                <?php echo form_label('E-Posta Adresi', $email['id']); ?>
                <?php echo form_input($email); ?>
                <?php echo form_error($email['name']); ?>
                <?php echo isset($errors[$email['name']]) ? $errors[$email['name']] : ''; ?>
            </div>
            <div class="type-text">
                <?php echo form_label('Şifre', $password['id']); ?>
                <?php echo form_password($password); ?>
                <?php echo form_error($password['name']); ?>
            </div>
            <div class="type-text">
                <?php echo form_label('Şifreyi Doğrula', $confirm_password['id']); ?>
                <?php echo form_password($confirm_password); ?>
                <?php echo form_error($confirm_password['name']); ?>
            </div>
            <?php if ($captcha_registration): ?>
                    <div class="type-text">
                        <span style="margin-left:25%;">
                    <?php echo ($captcha_html); ?>
                </span>
            </div>
            <div class="type-text">
                <?php echo form_label('Doğrulama Kodu', $captcha['id']); ?>
                <?php echo form_input($captcha); ?>
                <?php echo form_error($captcha['name']); ?>
                </div>
            <?php endif; ?>
                    <div class="subcl type-select">
                <?php echo form_label('Kasaba ve Köyünüz', 'place_id'); ?>
                <?php echo form_dropdown('place_id', place_select(array('0' => 'Diğer')), set_value('place_id'), 'id="place_id"'); ?>
                <?php echo form_error('place_id'); ?>
                </div>    
                <div class="buttonbox">
                <?php echo form_submit('register', 'Kayıt Ol', 'class="awesome"'); ?>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <div class="c50l">
        <div class="subcl">
            <div class="title">veya Facebook ile kayıt olun</div>

            <p class="tips">
                Dilerseniz Facebook hesabınızla sitemize üye olabilirsiniz.
            </p>
            <a id="facebook-big" class="center" href="user/facebook/redirect">Facebook ile Giriş Yap</a>

        </div>
    </div>
</div>
<div class="warning">Sitemizle üye olmakla beraber <strong><?php echo anchor('user/terms','Üyelik Sözleşmemizi'); ?></strong> kabul etmiş bulunacaksınız.</div>