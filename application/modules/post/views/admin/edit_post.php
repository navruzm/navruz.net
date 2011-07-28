<?php

add_js('assets/ckeditor/ckeditor.js', 'import');
add_jquery('CKEDITOR.replace("content",{toolbar : "POST"});');

$current_tags = array();
foreach ($tags as $tag)
{
    $current_tags[] = $tag['raw_tag'];
}

$tags = (is_array($this->input->post('tags'))) ? $this->input->post('tags') : $current_tags;
if (is_array($tags) && sizeof($tags) > 0)
{
    add_jquery('$("#tags").addTag("' . implode(',', $tags) . '");');
}
add_js('tagger');

//Kategori işlemleri
$post_cat_array = array();
foreach ($post_categories as $post_category)
{
    $post_cat_array[] = $post_category['category_id'];
}
?>

<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<fieldset>
    <legend>yazı düzenle</legend>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl type-text">
                <?php echo form_item('title', 'Yazı başlığı', 'input', array('value' => set_value('title', $title))); ?>
            </div>
        </div>
        <div class="c50r">
            <div class="subcl type-text">
                <?php echo form_item('slug', 'Yazı url adresi', 'input', array('value' => set_value('slug', $slug))); ?>
            </div>
        </div>
    </div>
    <div class="subcolumns">
        <div class="c50l">
            <div class="subcl">
                Kategori
                <div style="overflow-y:scroll; border:1px solid #ccc; padding:5px 0;height:100px;">
                    <?php if (sizeof($categories) > 0): ?>
                        <ul style="list-style-type:none;margin:0">
                        <?php foreach ($categories as $category): ?>
                            <li style="padding:2px 0">
                            <?php
                            echo form_checkbox(array('name' => 'category_id[]', 'value' => $category['category_id'],
                                'checked' => set_checkbox('category_id[]', $category['category_id'], in_array($category['category_id'], $post_cat_array)),
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
                <?php echo form_item('summary', 'Yazı özeti', 'textarea', array('rows' => 5, 'value' => set_value('summary', $summary))); ?>
                            </div>
                        </div>
                        <div class="c50r">
                            <div class="subcl type-text">
                <?php echo form_label('Etiketler', 'tags'); ?>
                <?php echo form_input(array('name' => 'tags[]', 'id' => 'tags', 'class' => 'tagger')); ?>
                            </div>
                        </div>                
                    </div>
                    <div class="type-text">
        <?php echo form_item('content', 'Yazı içeriği', 'textarea', array('rows' => 50, 'value' => set_value('content', $content))); ?>
                            </div>
                            <div class="type-text">
        <?php echo form_item('trackbacks', 'Geri izlemeler (Virgülle ayırın.)'); ?>
                            </div>
    <?php if ($pinged): ?>
                                    <div class="type-text">
                                        <strong>Bu url'ler zaten pinglendi:</strong> <?php echo $pinged; ?>
                                    </div>
    <?php endif; ?>
                                    <div class="type-select">
        <?php echo form_label('Yorumlar', 'comments_enabled'); ?>
        <?php echo form_dropdown('comments_enabled', array(0 => 'Hayır', 1 => 'Evet'), set_value('comments_enabled', $comments_enabled), 'id="comments_enabled"'); ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Meta bilgileri</legend>
                                <div class="type-text">
        <?php echo form_item('meta_title', 'Meta title', 'input', array('value' => set_value('meta_title', $meta_title))); ?>
                                </div>
                                <div class="subcolumns">
                                    <div class="c50l">
                                        <div class="subcl type-text">
                <?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', $meta_description))); ?>
                                </div>
                            </div>
                            <div class="c50r">
                                <div class="subcl type-text">
                <?php echo form_item('meta_keywords', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keywords', $meta_keywords))); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="type-button">
                        <button type="reset" class="awesome">Sıfırla</button>
                        <button type="submit" class="awesome">Gönder</button>
                    </div>
<?php echo form_close(); ?>
