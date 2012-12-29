<h1>İletişim</h1>
Görüş ve önerilerinizi bizimle paylaşmanız sitemizin büyümesine ve aradığınız bilgi ve özelliklerin
sitemizde yer almasına katkı sağlayacaktır.
<hr />
<?php echo form_open(uri_string(), array('class' => 'yform full')); ?>
<?php echo form_item('name', 'Adınız'); ?>
<?php echo form_item('email', 'E-Posta Adresiniz'); ?>
<?php echo form_item('message', 'Mesajınız', 'textarea', array('rows' => 10)); ?>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>