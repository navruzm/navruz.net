<?php

add_js('ui');
add_jquery('
$(function() {
        $("#sort-category").sortable({
            opacity: 0.6,
            cursor: "move",
            update: function() {
                var order = $(this).sortable("serialize");
                var t = order.replace(/\[/g,"")
                .replace(/\]/g,"")
                .replace(/[sort]/g,"")
                .replace(/&/g,"-")
                .replace(/=/g,"");
                $("#response").load("admin/category/sort_category_save/"+t);
            }
        });
    });
');
add_css('#sort-category li {margin: 0 0 3px; padding:8px; background-color:#5f5f5f; color:#f5f5f5; cursor:move; list-style-type:decimal; list-style-position:inside}','embed')
?>
<?php if($categories!==FALSE): ?>

<ol id="sort-category">
    <?php foreach($categories as $category) :?>
    <li id="sort_<?php echo $category['category_id'];?>"><?php echo $category['category_title'];?></li>
    <?php endforeach; ?>
</ol>
<div id="response"></div>
    <?php else:?>
<div class="warning">Henüz kategori eklenmemiş.</div>
<?php endif;?>



