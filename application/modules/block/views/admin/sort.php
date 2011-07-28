<?php

$locations = array(
    'left' => 'Sol',
    'right' => 'Sağ',
    'center_top' => 'Orta Üst',
    'center_bottom' => 'Orta Alt',
);
add_js('ui');
add_css('
    .sort li {border:1px solid #888888;margin: 0 0 3px; padding:3px 5px; background-color:#F5F3F3;  cursor:move; list-style-type:decimal; list-style-position:inside}
    .sort .action {float:right}
', 'embed');

foreach ($modules as $name => $blocks):
    if ($blocks['block_count'] > 0):
?>
<?php echo heading($this->m_config[$name]['name'], 3); ?><br/>
<?php foreach ($locations as $location_name => $location) : ?>
<?php add_jquery('
$(function() {
        $("#sort-' . $name .$location_name. '").sortable({
            opacity: 0.6,cursor: "move",update: function() {
                var order = $(this).sortable("serialize");var t = order.replace(/\[/g,"")
                .replace(/\]/g,"").replace(/[sort]/g,"").replace(/&/g,"-").replace(/=/g,"");
                $("#response").load("admin/block/sort_save/"+t);
            }});});'); ?>

<?php if (count($blocks[$location_name])): ?>
<?php echo $location; ?>
                <ol id="sort-<?php echo $name.$location_name; ?>" class="sort">
<?php foreach ($blocks[$location_name] as $block) : ?>
                        <li id="sort_<?php echo $block['id']; ?>">
    <?php echo $block['title']; ?>
                        <span class="action">
        <?php echo anchor('admin/block/update/' . $block['id'], 'Düzenle', 'class="edit"'); ?>
<?php echo anchor('admin/block/delete/' . $block['id'], 'Sil', 'class="delete" ' . js_confirm()); ?>
                    </span>
                </li>
<?php endforeach; ?>
        </ol>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
<div id="response"></div>