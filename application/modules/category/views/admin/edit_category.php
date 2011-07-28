<?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'yform full')); ?>
<?php echo form_fieldset('Kategori Düzenle'); ?>

<div class="type-text">
<?php echo form_item('category_title', 'Kategori Adı','input',array('value' => set_value('category_title', $category_title)));?>
</div>
<div class="type-text">
<?php echo form_item('category_description', 'Kategori Açıklaması', 'textarea', array('rows'=>5, 'value' => set_value('category_description', $category_description)));?>
</div>
<?php echo form_fieldset_close();?>

<?php echo form_fieldset('Meta Bilgileri'); ?>

<div class="type-text">
<?php echo form_item('meta_title', 'Meta title','input',array('value' => set_value('meta_title', $meta_title)));?>
        </div>
<div class="subcolumns">
    <div class="c50l">
        <div class="subcl type-text">
        <?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', $meta_description)));?>
                </div>
    </div>
    <div class="c50r">
        <div class="subcl type-text">
        <?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keywords', $meta_keywords)));?>
                </div>
    </div>
</div>
<?php echo form_fieldset_close();?>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Kaydet</button>
</div>
<?php echo form_close(); ?>