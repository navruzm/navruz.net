<h1>İletişim</h1>
Görüş ve önerilerinizi bizimle paylaşmanız sitemizin büyümesine ve aradığınız bilgi ve özelliklerin
sitemizde yer almasına katkı sağlayacaktır.
<hr />
<?php echo form_open(uri_string(), array('class' => 'yform full')); ?>

<div class="subcl type-text">
    <?php echo form_item('name', 'Adınız'); ?>
</div>
<div class="subcl type-text">
    <?php echo form_item('email', 'E-Posta Adresiniz (yayınlanmayacak)'); ?>
</div>
<div class="subcl type-text">
    <?php echo form_item('message', 'Mesajınız', 'textarea', array('rows' => 15)); ?>
</div>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
