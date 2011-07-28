<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content");');
add_jquery("$('input[name=\"title\"]').keyup(function(){
    $.post('admin/post/get_slug', {
        title : $(this).val()
    }, function(slug){
        $('input[name=\"slug\"]').val( slug );
    });
});");
if(is_array($this->input->post('tags')))
{
    add_jquery('$("#tags").addTag("'.implode(',', $this->input->post('tags')).'");');
}
add_js('tagger');
?>
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>Yeni Yazı Ekle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
<?php echo form_item('title', 'Yazı başlığı'); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
<?php echo form_item('slug', 'Yazı url adresi (Otomatik oluşturmak için boş bırakınız.)'); ?>
            </div>
        </div>
    </div>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl" style="margin:0.5em 0;padding:3px 0.5em;">
                Kategori
                <div style="overflow-y:scroll;border:1px solid #ccc; padding:5px 0;height:100px;">
<?php if (sizeof($categories) > 0): ?>
                    <ul style="list-style-type:none;margin:0">
<?php foreach ($categories as $category): ?>
                        <li style="padding:2px 0">
<?php
        echo form_checkbox(array('name' => 'category_id[]', 'value' => $category['category_id'],
            'checked' => set_checkbox('category_id[]', $category['category_id']),
            'id' => 'category-' . $category['category_id'])
        ); ?>
                            <?php echo form_label($category['category_title'], 'category-' . $category['category_id'], array('style' => 'display:inline')); ?>
                        </li>
<?php endforeach; ?>
                    </ul>
<?php else: ?>
                            Kategori eklenmemiş.
<?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <div class="c50r">
                        <div class="subcl type-text">
<?php echo form_item('image', 'Yazı fotoğrafı', 'upload'); ?>
                        </div>
                    </div>
                </div>
                <div class="subcolumns">
                    <div class="c50l">
                        <div class="subcl type-text">
<?php echo form_item('summary', 'Yazı özeti', 'textarea', array('rows' => 5)); ?>
                            </div>
                        </div>
<div class="c50r">
                <div class="subcl type-text">
                <?php echo form_label('Etiketler', 'tags'); ?>
                <?php echo form_input(array('name'=>'tags[]','id'=>'tags','class'=>'tagger')); ?>
                </div>
            </div>
                    </div>
                    <div class="type-text">
<?php echo form_item('content', 'Yazı içeriği', 'textarea', array('rows' => 50)); ?>
                    </div>
                    <div class="type-text">
<?php echo form_item('trackbacks', 'Geri izlemeler (Virgülle ayırın.)'); ?>
                            </div>
                            <div class="type-select">
<?php echo form_label('Yorumlar', 'comments_enabled'); ?>
        <?php echo form_dropdown('comments_enabled', array(0 => 'Hayır', 1 => 'Evet'), set_value('comments_enabled', 1), 'id="comments_enabled"'); ?>
                            </div>                        
                        </fieldset>
                        <fieldset>
                            <legend>Meta bilgileri</legend>
                            <div class="type-text">
<?php echo form_item('meta_title', 'Meta title'); ?>
                            </div>
                            <div class="subcolumns">
                                <div class="c50l">
                                    <div class="subcl type-text">
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4)); ?>
                                    </div>
                                </div>
                                <div class="c50r">
                                    <div class="subcl type-text">
<?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 4)); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="type-button">
                    <button type="reset" class="awesome">Sıfırla</button>
                    <button type="submit" class="awesome">Gönder</button>
                </div>
<?php echo form_close(); ?>
