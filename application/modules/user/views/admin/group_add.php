<?php

$title = array(
    'name' => 'title',
    'id' => 'title',
    'value' => set_value('title'),
    'maxlength' => 100,
    'size' => 30,
);

$name = array(
    'name' => 'name',
    'id' => 'name',
    'value' => set_value('name'),
    'maxlength' => 100,
    'size' => 30,
);
$description = array(
    'name' => 'description',
    'id' => 'description',
    'value' => set_value('description'),
    'maxlength' => 255,
    'cols' => 3,
    'rows' => 3,
);
?>
<?php echo form_open($this->uri->uri_string(), array('class' => 'yform columnar')); ?>
<?php echo form_fieldset('Grup Ekle'); ?>

<div class="type-text">
<?php echo form_label('Grup Başlığı', $title['id']); ?>
<?php echo form_input($title); ?>
    <?php echo form_error($title['name']); ?>
</div>
<div class="type-text">
<?php echo form_label('Grup Adı', $name['id']); ?>
<?php echo form_input($name); ?>
    <?php echo form_error($name['name']); ?>
</div>
<div class="type-text">
<?php echo form_label('Grup Açıklaması', $description['id']); ?>
<?php echo form_textarea($description); ?>
    <?php echo form_error($description['name']); ?>
</div>
<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>