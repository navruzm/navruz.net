<?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'yform full')); ?>
<?php echo form_fieldset('Yeni Kategori Ekle'); ?>

<div class="type-text">
<?php echo form_item('category_title', 'Kategori Adı');?>
</div>
<div class="type-text">
<?php echo form_item('category_description', 'Kategori Açıklaması', 'textarea', array('rows'=>5));?>
</div>
<?php echo form_fieldset_close();?>
<?php echo form_fieldset('Meta Bilgileri'); ?>

<div class="type-text">
<?php echo form_item('meta_title', 'Meta title');?>
        </div>
<div class="subcolumns">
    <div class="c50l">
        <div class="subcl type-text">
        <?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows'=>4));?>
                </div>
    </div>
    <div class="c50r">
        <div class="subcl type-text">
        <?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows'=>4));?>
                </div>
    </div>
</div>
<?php echo form_fieldset_close();?>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>