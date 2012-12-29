<?php
add_js_jquery('
$(function() {
        $("#sort tbody").sortable({
            opacity: 0.6,
            cursor: "move",
            helper: fixHelper
        });
        var fixHelper = function(e, ui) {
        ui.children().each(function() {$(this).width($(this).width());});
        return ui;
    };
    });
', 'assets/js/jquery.ui.js');
$access_level = array('0' => 'Herkes', '1' => 'Üye Olmayanlar', '2' => 'Üyeler', '3' => 'Yöneticiler');
$target = array('' => 'Aynı Sayfada', 'blank' => 'Yeni Sayfada');
?>
<?php echo form_open_multipart($this->uri->uri_string()); ?>
<?php echo form_item('title', 'Menü Adı', 'input', array('value' => set_value('title', isset($title) ? $title : ''))); ?>
<?php echo form_item('slug', 'Menü Etiketi', 'input', array('value' => set_value('slug', isset($slug) ? $slug : ''))); ?>
<table class="zebra-striped" id="sort">
    <thead>
        <tr>
            <th>#</th>
            <th>Başlık</th>
            <th>Bağlantı</th>
            <th>Kimler Görebilir?</th>
            <th>Açılma Şekli</th>
            <th><a class="btn add" onclick="add_row();">Ekle</a></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; ?>
        <?php foreach (set_value('items', isset($items) ? $items : array()) as $item) : ?>
            <tr>
                <td class="sort">x</td>
                <td><?php echo form_input('items[' . $i . '][title]', $item['title'], 'class="span4"'); ?></td>
                <td><?php echo form_input('items[' . $i . '][url]', $item['url'], 'class="span4"'); ?></td>
                <td><?php echo form_dropdown('items[' . $i . '][access_level]', $access_level, $item['access_level'], 'class="span3"'); ?></td>
                <td><?php echo form_dropdown('items[' . $i++ . '][target]', $target, $item['target'], 'class="span3"'); ?></td>
                <td><a class="btn delete danger" onclick="delete_row(this);">Sil</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td class="sort">x</td>
            <td><?php echo form_input('items[' . $i . '][title]', '', 'class="span4"'); ?></td>
            <td><?php echo form_input('items[' . $i . '][url]', '', 'class="span4"'); ?></td>
            <td><?php echo form_dropdown('items[' . $i . '][access_level]', $access_level, '', 'class="span3"'); ?></td>
            <td><?php echo form_dropdown('items[' . $i++ . '][target]', $target, '', 'class="span3"'); ?></td>
            <td><a class="btn delete danger" onclick="delete_row(this);">Sil</a></td>
        </tr>
    </tbody>
</table>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    function delete_row(row) {
        $(row).closest('tr').remove();
    }
    var i = <?php echo $i; ?>;
    function add_row() {
        row = '<tr><td class="sort">x</td><td><input type="text" name="items['+i+'][title]" value="" class="span4" /></td>\
            <td><input type="text" name="items['+i+'][url]" value="" class="span4" /></td>\
            <td><select name="items['+i+'][access_level]" class="span3">\
                        <option value="0">Herkes</option>\
                        <option value="1">Üye Olmayanlar</option>\
                        <option value="2">Üyeler</option>\
                        <option value="3">Yöneticiler</option>\
                        </select></td>\
            <td><select name="items['+i+'][target]" class="span3">\
                        <option value="" selected="selected">Aynı Sayfada</option>\
                        <option value="blank">Yeni Sayfada</option>\
                        </select></td>\
            <td><a class="btn delete danger" onclick="delete_row(this);">Sil</a></td>\
                        </tr>'; 
                    $('#sort tbody').append(row);
                    i++;
                }
</script>