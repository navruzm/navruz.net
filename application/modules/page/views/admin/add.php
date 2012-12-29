<?php
add_js_jquery('CKEDITOR.replace("content");
CKEDITOR.instances["content"].on("instanceReady", function(){
    this.document.on("keyup", function(){CKEDITOR.instances.content.updateElement();});
    this.document.on("paste", function(){CKEDITOR.instances.content.updateElement();});
});
', 'assets/ckeditor/ckeditor.js');
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<?php echo form_item('title', 'Sayfa başlığı', 'input', array('value' => set_value('title', isset($title) ? $title : ''))); ?>
<?php echo form_item('content', 'Sayfa içeriği', 'textarea', array('rows' => 50, 'value' => set_value('content', isset($content) ? $content : ''))); ?>
<?php echo form_item('meta_title', 'Meta title', 'input', array('value' => set_value('meta_title', isset($meta_title) ? $meta_title : ''))); ?>
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', isset($meta_description) ? $meta_description : ''))); ?>
<?php echo form_item('meta_keyword', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keyword', isset($meta_keyword) ? $meta_keyword : ''))); ?>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>
