<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content-html",{toolbar : "POST"});');
add_jquery('
    show_hide($("#type").val());
    $("#type").change(function() {show_hide($(this).val());});
');
add_js('
    function show_hide(type){
    if(type=="html") {$("#type-html").show();$("#type-file").hide();$("#type-menu").hide();}
    if(type=="file") {$("#type-file").show();$("#type-html").hide();$("#type-menu").hide();}
    if(type=="menu") {$("#type-menu").show();$("#type-file").hide();$("#type-html").hide();}
    $("#type-menu").val("");$("#type-file").val("");$("#type-html").val("");
}', 'embed')
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<?php echo form_fieldset('Yeni Blok Ekle'); ?>
<div class="subcolumns">
    <div class="c33l">
        <div class="subcl type-text">
            <?php echo form_item('title', 'Başlık'); ?>
        </div>
    </div>
    <div class="c33l">
        <div class="subcl type-select">
            <?php echo form_label('Tip', 'type'); ?>
            <?php echo form_dropdown('type', array('html' => 'HTML', 'file' => 'Dosya', 'menu' => 'Menü'), set_value('type'), 'id="type"'); ?>
        </div>
    </div>

    <div class="c33l">
        <div class="subcl" style="margin:0.5em 0;padding:3px 0.5em;">
            Modüller
            <div style="overflow-y:scroll;border:1px solid #ccc; padding:5px 0;height:100px;">
                <ul style="list-style-type:none;margin:0">
                    <?php foreach ($this->block->get_frontend_modules() as $name => $module): ?>
                        <li style="padding:2px 0">
                        <?php
                        echo form_checkbox(array('name' => 'module[]', 'value' => $name,
                            'checked' => set_checkbox('module[]', $name),
                            'id' => 'module-' . $name)
                        ); ?>
                        <?php echo form_label($module, 'module-' . $name, array('style' => 'display:inline')); ?>
                    </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>

<div class="subcolumns">
    <div class="c33l">
        <div class="subcl type-select">
                <?php echo form_label('Kimler Görebilir?', 'target'); ?>
                <?php echo form_dropdown('access_level', array('0' => 'Herkes', '1' => 'Üye Olmayanlar','2' => 'Üyeler','3'=>'Yöneticiler'), set_value('access_level'), 'id="access_level"'); ?>
            </div>
        </div>
    <div class="c33l">
            <div class="subcl type-select">
    <?php echo form_label('Eklenecek Bölüm', 'location'); ?>
    <?php echo form_dropdown('location', array('left' => 'Sol', 'right' => 'Sağ', 'center_top' => 'Orta Üst', 'center_bottom' => 'Orta Alt'), set_value('location'), 'id="location"'); ?>
                    </div>
    </div>
    <div class="c33l">
        <div class="subcl type-select">
            <?php echo form_label('Aktif/Pasif?', 'active'); ?>
            <?php echo form_dropdown('active', array('1' => 'Aktif', '0' => 'Pasif'), set_value('active',1), 'id="active"'); ?>
        </div>
    </div>
</div>


                    
                    <div id="type-html" class="type">
    <?php echo form_item('content-html', 'Blok içeriği', 'textarea', array('rows' => 50)); ?>
                    </div>


                    <div id="type-file" class="type">
                        <div class="type-select">
        <?php echo form_label('Dosyalar', 'content-file'); ?>
        <?php echo form_dropdown('content-file', $files, set_value('content-file'), 'id="content-file"'); ?>
        <?php echo form_error('content-file'); ?>
                    </div>
                </div>

                <div id="type-menu" class="type">
                    <div class="type-select">
        <?php echo form_label('Menüler', 'content-menu'); ?>
        <?php echo form_dropdown('content-menu', $menus, set_value('content-menu'), 'id="content-menu"'); ?>
        <?php echo form_error('content-menu'); ?>
                    </div>
                </div>
<?php echo form_fieldset_close(); ?>

                        <div class="type-button">
                            <button type="reset" class="awesome">Sıfırla</button>
                            <button type="submit" class="awesome">Gönder</button>
                        </div>
<?php echo form_close(); ?>