<?php echo form_open($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>Yeni Grup Ekle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
                <?php echo form_item('title', 'Grup İsmi'); ?>
            </div>
        </div>
        <div class="c50l">
            <div class="subcl type-text">
                <?php echo form_item('tag', 'Grup Etiketi'); ?>
            </div>
        </div>
    </div>

</fieldset>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>
