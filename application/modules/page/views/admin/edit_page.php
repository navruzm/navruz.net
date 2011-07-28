<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content",{toolbar : "POST"});');
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>sayfa düzenle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
<?php echo form_item('title', 'Sayfa başlığı', 'input', array('value' => set_value('title', $title))); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
<?php echo form_item('slug', 'Sayfa url adresi', 'input', array('value' => set_value('slug', $slug))); ?>
            </div>
        </div>
    </div>

    <div class="type-text">
<?php echo form_item('content', 'Sayfa içeriği', 'textarea', array('rows' => 50, 'value' => set_value('content', $content))); ?>
    </div>
    <div class="type-select">
<?php echo form_label('Yorumlar', 'comments_enabled'); ?>
        <?php echo form_dropdown('comments_enabled', array(0 => 'Hayır', 1 => 'Evet'), set_value('comments_enabled', $comments_enabled), 'id="comments_enabled"'); ?>
    </div>
</fieldset>
<fieldset>
    <legend>Meta bilgileri</legend>
    <div class="type-text">
<?php echo form_item('meta_title', 'Meta title', 'input', array('value' => set_value('meta_title', $meta_title))); ?>
    </div>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', $meta_description))); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
<?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keywords', $meta_keywords))); ?>
            </div>
        </div>
    </div>
</fieldset>
<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>
