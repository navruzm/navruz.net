<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content",{toolbar : "POST"});');
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>Yeni Sayfa Ekle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
<?php echo form_item('title', 'Sayfa başlığı'); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
<?php echo form_item('slug', 'Sayfa url adresi (Otomatik oluşturmak için boş bırakınız.)'); ?>
            </div>
        </div>
    </div>
    <div class="type-text">
<?php echo form_item('content', 'Sayfa içeriği', 'textarea', array('rows' => 50)); ?>
    </div>
    <div class="type-select">
<?php echo form_label('Yorumlar', 'comments_enabled'); ?>
        <?php echo form_dropdown('comments_enabled', array(0 => 'Hayır', 1 => 'Evet'), set_value('comments_enabled', 1), 'id="comments_enabled"'); ?>
    </div> 
</fieldset>
<fieldset>
    <legend>Meta bilgileri</legend>
    <div class="type-text">
<?php echo form_item('meta_title', 'Meta title'); ?>
    </div>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4)); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
<?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 4)); ?>
            </div>
        </div>
    </div>
</fieldset>
<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>
