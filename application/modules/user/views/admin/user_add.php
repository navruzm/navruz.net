<?php

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

$user_status = array(
    '0' => 'Hiçbir bilgi göndermeden aktive et',
    '1' => 'Aktivasyon maili gönder',
    '2' => 'Aktive et ve hoşgeldin mesajı gönder',
);



$status_value = ($this->input->post('status')) ? $this->input->post('status') : 0;
?>
<?php echo form_open($this->uri->uri_string(), array('class' => 'yform columnar')); ?>
<?php echo form_fieldset('Üye Ekle'); ?>

<?php if ($use_username) : ?>

    <div class="type-text">
<?php echo form_label('Kullanıcı Adı', $username['id']); ?>
<?php echo form_input($username); ?>
    <?php echo form_error($username['name']); ?>
    <?php echo isset($errors[$username['name']]) ? $errors[$username['name']] : ''; ?>
</div>
    <?php endif; ?>

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
<div class="type-select">
<?php echo form_label('Üye grubu', 'user_group'); ?>
<?php echo form_dropdown('user_group', $groups, $default_group, 'id="user_group"'); ?>
    <?php echo form_error('user_group'); ?>
</div>
<div class="type-select">
<?php echo form_label('Durum', 'status'); ?>
<?php echo form_dropdown('status', $user_status, $status_value, 'id="status"'); ?>
    <?php echo form_error('status'); ?>
</div>
<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>