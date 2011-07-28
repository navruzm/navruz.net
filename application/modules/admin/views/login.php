<?php echo doctype('xhtml1-trans') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
    <head>
    <title><?php echo 'Yönetici Girişi' ?></title>
    <?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
    <base href="<?php echo base_url() ?>" />
    <?php echo link_tag('css/style.css'); ?>
    <script type="text/javascript" src="js/js.js"></script>
    <!--[if lte IE 7]>
    <link href="css/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
</head>
<body>
<div class="page_margins" style="max-width:300px;min-width:300px;">
    <div class="page" style="background-color:#fff;">

        <?php if (is_user ()): ?>
            <div class="error">
                Üzgünüz, sadece yöneticilerimiz bu alana girebilir.
            </div>
        <?php
            else:
                if ($this->config->item('login_by_username') AND $this->config->item('login_by_email'))
                {
                    $login_label = 'E-Posta veya Kullanıcı Adı';
                }
                else if ($this->config->item('login_by_username'))
                {
                    $login_label = 'Kullanıcı Adı';
                }
                else
                {
                    $login_label = 'E-Posta';
                }
                $remember = array(
                    'name' => 'remember',
                    'id' => 'remember',
                    'value' => 1,
                    'checked' => set_value('remember')
                );
        ?>
                <div class="warning">
                    Bu bölüme erişebilmek için giriş yapmalısınız.
                </div>

        <?php echo form_open($this->uri->uri_string(), array('class' => 'yform full')); ?>
        <?php echo form_hidden('ref', $this->uri->uri_string()); ?>
                <div class="type-text">
            <?php echo form_item('login', $login_label); ?>
            <?php echo isset($errors['login']) ? $errors['login'] : ''; ?>
            </div>
            <div class="type-text">
            <?php echo form_item('password', 'Parola', 'password'); ?>
            <?php echo isset($errors['password']) ? $errors['password'] : ''; ?>
            </div>

        <?php if ($show_captcha):?>

                    <div class="type-text" style="margin-left:25%;">
            <?php echo ($captcha_html); ?>
                </div>

                <div class="type-text">
            <?php echo form_item('captcha', 'Doğrulama kodu'); ?>
                </div>
        <?php endif; ?>

                    <div class="type-check">
            <?php echo form_checkbox($remember); ?>
            <?php echo form_label('Beni Hatırla', $remember['id']); ?>
                </div>
                <div class="buttonbox">
            <?php echo form_submit('submit', 'Giriş', 'class="awesome"'); ?>
                </div>
        <?php echo form_close(); ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>