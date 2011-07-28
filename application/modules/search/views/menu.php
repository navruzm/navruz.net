<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="tabs">
    <?php echo form_open('search/post', array('class' => 'yform full')); ?>
    <?php echo form_hidden('is_search', 1); ?>
    <div class="type-text">
        <?php echo form_item('keyword', 'Arama Terimi', 'input', array('value' => $keyword)); ?>
    </div>
    <div class="type-button">
        <button type="reset" class="awesome">Sıfırla</button>
        <button type="submit" class="awesome">Gönder</button>
    </div>
    <?php echo form_close(); ?>
</div>