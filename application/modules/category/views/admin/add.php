<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<?php echo form_item('title', 'Kategori Adı', 'input', array('value' => set_value('title', isset($title)
                        ? $title : ''))); ?>
<?php echo form_item('description', 'Kategori Açıklaması', 'textarea', array('rows' => 5, 'value' => set_value('description', isset($description)
                        ? $description : ''))); ?>

<?php echo form_item('meta_title', 'Meta title', 'input', array('value' => set_value('meta_title', isset($meta_title)
                        ? $meta_title : ''))); ?>
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', isset($meta_description)
                        ? $meta_description : ''))); ?>
<?php echo form_item('meta_keyword', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keyword', isset($meta_keyword)
                        ? $meta_keyword : ''))); ?>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>