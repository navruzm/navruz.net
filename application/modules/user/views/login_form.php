<?php

$login = array(
    'name' => 'login',
    'id' => 'login',
    'value' => set_value('login'),
    'maxlength' => 80,
    'size' => 30,
);
if ($login_by_username AND $login_by_email)
{
    $login_label = 'Kullanıcı adı veya e-posta';
}
else if ($login_by_username)
{
    $login_label = 'Kullanıcı Adı';
}
else
{
    $login_label = 'E-Posta';
}
$password = array(
    'name' => 'password',
    'id' => 'password',
    'size' => 30,
);
$remember = array(
    'name' => 'remember',
    'id' => 'remember',
    'value' => 1,
    'checked' => set_value('remember'),
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
            <div class="title">Hesabınla Giriş Yap</div>
            <?php echo form_open(config_item('auth_uri_login'), array('class' => 'yform full')); ?>
            <div class="type-text">
                <?php echo form_label($login_label, $login['id']); ?>
                <?php echo form_input($login); ?>
                <?php echo form_error($login['name']); ?>
                <?php echo isset($errors[$login['name']]) ? $errors[$login['name']] : ''; ?>
            </div>
            <div class="type-text">
                <?php echo form_label('Şifre', $password['id']); ?>
                <?php echo form_password($password); ?>
                <?php echo form_error($password['name']); ?>
                <?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : ''; ?>
            </div>

            <?php
                if ($show_captcha)
                {
            ?>
                    <div class="type-text">
                        <span style="margin-left:25%;">
<?php echo ($captcha_html); ?></span>
            </div>

            <div class="type-text">
                <?php echo form_label('Doğrulama Kodu', $captcha['id']); ?>
                <?php echo form_input($captcha); ?>
<?php echo form_error($captcha['name']); ?>
                </div>
<?php } ?>

                <div class="type-check">
                <?php echo form_checkbox($remember); ?>
<?php echo form_label('Beni Hatırla', $remember['id']); ?>

            </div>
            <div class="buttonbox">
<?php echo form_submit('submit', 'Giriş', 'class="awesome"'); ?>
            </div>
            <ul>
                <li><?php echo anchor('/user/forgot_password/', 'Şifremi Unuttum'); ?></li>
                <li><?php if ($this->config->item('allow_registration'))
                    echo anchor('/user/register/', 'Kayıt Ol'); ?></li>
            </ul>
<?php echo form_close(); ?>
        </div>
    </div>
    <div class="c50l">
        <div class="subcl">
            <div class="title">Facebook Hesabınla Giriş Yap</div>

            <p class="tips">
                Dilerseniz Facebook hesabınız ile de giriş yapabilirsiniz. Facebook ile ilk defa giriş yapacaksanız
                bir defaya mahsus kayıt işlemi yapmanız gerekiyor.
            </p>
            <a id="facebook-big" class="center" href="user/facebook/redirect">Facebook ile Giriş Yap</a>
        </div>
    </div>
</div>