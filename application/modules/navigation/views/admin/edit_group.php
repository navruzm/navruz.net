<?php echo form_open($this->uri->uri_string(), array('class' => 'yform full')); ?>
<div class="subcolumns">
    <div class="c33l">
        <div class="subcl type-text">
            <?php echo form_item('title', 'Grup Adı', 'input', array('value' => set_value('title', $title))); ?>
        </div>
    </div>
    <div class="c33l">
        <div class="subcl type-text">
            <?php echo form_item('tag', 'Grup Etiketi', 'input', array('value' => set_value('tag', $tag))); ?>
        </div>
    </div>
</div>

<div class="type-button">
    <button type="reset" class="awesome">Sıfırla</button>
    <button type="submit" class="awesome">Gönder</button>
</div>
<?php echo form_close(); ?>
