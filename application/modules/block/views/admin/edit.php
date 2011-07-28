<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content-html",{toolbar : "POST"});');

$content_file = '';
$content_html = '';
$content_menu = '';
if ($type == 'html')
{
    $content_html = $content;
}
elseif ($type == 'file')
{
    $content_file = $content;
}
elseif ($type == 'menu')
{
    $content_menu = $content;
}
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<?php echo form_fieldset('Blok Düzenle'); ?>
<?php echo form_hidden('type', $type); ?>
<div class="subcolumns">
    <div class="c50l">
        <div class="subcl type-text">
<?php echo form_item('title', 'Başlık', 'input', array('value' => set_value('title', $title))); ?>
        </div>
    </div>
    <div class="c50l">
        <div class="subcl" style="margin:0.5em 0;padding:3px 0.5em;">
            Modüller
            <div style="overflow-y:scroll;border:1px solid #ccc; padding:5px 0;height:100px;">
                <ul style="list-style-type:none;margin:0">
<?php foreach ($this->block->get_frontend_modules() as $name => $module_name): ?>
                    <li style="padding:2px 0">
<?php
    echo form_checkbox(array('name' => 'module[]', 'value' => $name,
        'checked' => set_checkbox('module[]', $name, in_array($name, $modules)),
        'id' => 'module-' . $name)
    ); ?>
                        <?php echo form_label($module_name, 'module-' . $name, array('style' => 'display:inline')); ?>
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
            <?php echo form_dropdown('access_level', array('0' => 'Herkes', '1' => 'Üye Olmayanlar', '2' => 'Üyeler', '3' => 'Yöneticiler'), set_value('access_level', $access_level), 'id="access_level"'); ?>
                    </div>
                </div>
                <div class="c33l">
                    <div class="subcl type-select">
<?php echo form_label('Eklenecek Bölüm', 'location'); ?>
            <?php echo form_dropdown('location', array('left' => 'Sol', 'right' => 'Sağ', 'center_top' => 'Orta Üst', 'center_bottom' => 'Orta Alt'), set_value('location', $location), 'id="location"'); ?>
                    </div>
                </div>
                <div class="c33l">
                    <div class="subcl type-select">
<?php echo form_label('Aktif/Pasif?', 'active'); ?>
            <?php echo form_dropdown('active', array('1' => 'Aktif', '0' => 'Pasif'), set_value('active', $active), 'id="active"'); ?>
                    </div>
                </div>
            </div>
<?php if ($type == 'html'): ?>
                <div id="type-html" class="type">
<?php echo form_item('content-html', 'Blok içeriği', 'textarea', array('rows' => 50, 'value' => $content_html)); ?>
                            </div>

<?php elseif ($type == 'file'): ?>
                            <div id="type-file" class="type">
                                <div class="type-select">
<?php //@todo disabled  ?>
        <?php echo form_hidden('content-file', $content_file) ?>
        <?php // echo form_label('Dosyalar', 'content-file'); ?>
        <?php // echo form_dropdown('content-file', $files, set_value('content-file', $content_file), 'id="content-file" '); ?>
        <?php // echo form_error('content-file'); ?>
                            </div>
                        </div>
<?php elseif ($type == 'menu'): ?>
                            <div id="type-menu" class="type">
                                <div class="type-select">
<?php echo form_label('Menüler', 'content-menu'); ?>
        <?php echo form_dropdown('content-menu', $menus, set_value('content-menu', $content_menu), 'id="content-menu"'); ?>
        <?php echo form_error('content-menu'); ?>
                                </div>
                            </div>
<?php endif; ?>
<?php echo form_fieldset_close(); ?>

                                    <div class="type-button">
                                        <button type="reset" class="awesome">Sıfırla</button>
                                        <button type="submit" class="awesome">Gönder</button>
                                    </div>
<?php echo form_close(); ?>