<?php echo form_open($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>Yeni Bağlantı Ekle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
                <?php echo form_item('title', 'Bağlantı Adı'); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
                <?php echo form_item('link', 'Bağlantı'); ?>
            </div>
        </div>
    </div>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-select">
                <?php echo form_label('Kimler Görebilir?', 'target'); ?>
                <?php echo form_dropdown('access_level', array('0' => 'Herkes', '1' => 'Üye Olmayanlar','2' => 'Üyeler','3'=>'Yöneticiler'), set_value('access_level'), 'id="access_level"'); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-select">
                <?php echo form_label('Açılma Şekli', 'target'); ?>
                <?php echo form_dropdown('target', array('' => 'Aynı Sayfada', 'blank' => 'Yeni Sayfada'), set_value('target'), 'id="target"'); ?>
            </div>
        </div>
    </div>
</fieldset>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>
