<?php
add_js_jquery('CKEDITOR.replace("content");
CKEDITOR.instances["content"].on("instanceReady", function(){
    this.document.on("keyup", function(){CKEDITOR.instances.content.updateElement();});
    this.document.on("paste", function(){CKEDITOR.instances.content.updateElement();});
});
', 'assets/ckeditor/ckeditor.js');

add_js_jquery("$('#tags').tagsInput({
	autocomplete_url:'admin/post/get_tags','width':'530px','height':'60px'
});", 'assets/js/jquery.tagsinput.min.js');
add_js_jquery("$('select').chosen(); ", 'assets/js/chosen.jquery.min.js');
add_css_minify('assets/css/chosen.css');
add_js_link('assets/js/jquery.ui.js', 'ready');
add_css_minify('assets/css/jquery.tagsinput.css');
add_css_minify('assets/css/aristo.css');
$_categories = array();
if (isset($all_categories))
{
    foreach ($all_categories as $category)
    {
        $_categories[(string) $category['_id']] = $category['title'];
    }
}
$current_categories = array();
if (isset($categories))
{
    foreach ($categories as $category)
    {
        $current_categories[] = (string) $category;
    }
}
$current_tags = array();
if (isset($tags))
{
    foreach ($tags as $tag)
    {
        $current_tags[] = (string) $tag['tag'];
    }
}
?>
<?php echo form_open_multipart($this->uri->uri_string()); ?>
<?php echo form_item('title', 'Yazı başlığı', 'input', array('value' => set_value('title', isset($title) ? $title : ''))); ?>
<div class="clearfix">
    <label id="categories">Kategoriler</label>
    <div class="input">
        <?php echo form_dropdown('categories[]', $_categories, $current_categories,'id="categories" multiple style="width:538px;"');?>
    </div>
</div>
<?php echo form_item('image', 'Yazı fotoğrafı', 'upload'); ?>
<?php echo form_item('tags', 'Etiketler', 'input', array('value' => set_value('tags', count($current_tags) ? implode(',', $current_tags) : ''))); ?>
<?php echo form_item('content', 'Yazı içeriği', 'textarea', array('rows' => 50, 'value' => set_value('content', isset($content) ? $content : ''))); ?>
<div class="clearfix">
    <label for="status">Durum</label>
    <div class="input">
        <?php echo form_dropdown('status', array('publish' => 'Yayında', 'draft' => 'Taslak'), set_value('status', isset($status) ? $status : 'active'), 'id="status"'); ?>
    </div>
</div>
<div class="clearfix">
    <label for="comments_enabled">Yorumlar</label>
    <div class="input">
        <?php echo form_dropdown('comments_enabled', array('1' => 'Açık', '0' => 'Kapalı'), set_value('comments_enabled', isset($comments_enabled) ? $comments_enabled : 1), 'id="comments_enabled"'); ?>
    </div>
</div>
<?php echo form_item('meta_title', 'Meta title', 'input', array('value' => set_value('meta_title', isset($meta_title) ? $meta_title : ''))); ?>
<?php echo form_item('meta_description', 'Meta description', 'textarea', array('rows' => 4, 'value' => set_value('meta_description', isset($meta_description) ? $meta_description : ''))); ?>
<?php echo form_item('meta_keyword', 'Anahtar kelimeler', 'textarea', array('rows' => 4, 'value' => set_value('meta_keyword', isset($meta_keyword) ? $meta_keyword : ''))); ?>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>
