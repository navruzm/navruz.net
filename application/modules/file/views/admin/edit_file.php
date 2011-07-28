<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>Dosya Düzenle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
                <?php echo form_item('file_title', 'Dosya Adı', 'input', array('value' => set_value('file_title', $file_title)));?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
                <?php echo form_item('file', 'Dosya (zip|php|html|css|htm|txt)', 'upload');?>
                <?php echo form_hidden('file_name','1');?>
            </div>
        </div>
    </div>
</fieldset>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Kaydet</button>
</div>
<?php echo form_close();?>
