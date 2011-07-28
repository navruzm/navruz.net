<?php


add_js('ui');
add_jquery('
$(function() {
        $("#sort-link").sortable({
            opacity: 0.6,
            cursor: "move",
            update: function() {
                var order = $(this).sortable("serialize");
                var t = order.replace(/\[/g,"")
                .replace(/\]/g,"")
                .replace(/[sort]/g,"")
                .replace(/&/g,"-")
                .replace(/=/g,"");
                $("#response").load("admin/navigation/sort_links_save/"+t);
            }
        });
    });
');
add_css('
    #sort-link li {border:1px solid #888888;margin: 0 0 3px; padding:3px 5px; background-color:#F5F3F3;  cursor:move; list-style-type:decimal; list-style-position:inside}
    #sort-link .action {float:right}
','embed')
?>
<?php if($links!==FALSE): ?>

<ol id="sort-link">
    <?php foreach($links as $link) :?>
    <li id="sort_<?php echo $link['id'];?>">
    <?php echo $link['title'];?> (<?php echo $link['link'];?>)
    <span class="action">
        <?php echo anchor('admin/navigation/edit_link/'.$link['id'],'Düzenle','class="edit"');?>
                        <?php echo anchor('admin/navigation/delete_link/'.$link['id'],'Sil','class="delete" '.js_confirm());?>
    </span>
    </li>
    <?php endforeach; ?>
</ol>
<div id="response"></div>
    <?php else:?>
<div class="warning">Henüz link eklenmemiş.</div>
<?php endif;?>
<div class="center">
    <?php echo anchor('admin/navigation/add_link/'.$this->uri->rsegment(3), 'Yeni Bağlantı Ekle', 'class="red awesome"'); ?>
</div>