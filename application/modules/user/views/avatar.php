<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$this->load->view('control_panel');

add_js('assets/uploadify/swfobject.js', 'import');
add_js('assets/uploadify/uploadify.js', 'import');
add_js('assets/uploadify/json.js', 'import');
add_css('assets/uploadify/uploadify.css', 'link');
add_css('jcrop');
add_js('js');
add_js('jcrop');
add_jquery("
    $('#uploadify').uploadify({
    'uploader'       : '" . base_url() . "assets/uploadify/uploadify.swf',
    'script'         : '" . base_url() . "user/avatar_upload',
    'cancelImg'      : 'assets/uploadify/cancel.png',
    'folder'         : 'uploads',
    'queueID'        : 'fileQueue',
    'auto'           : true,
    'multi'          : false,
    'buttonText'          : 'DOSYA SECIN',
    'scriptAccess'  : 'always',
    'fileDesc'      : 'JPG Dosyası (.jpg)',
    'fileExt'       : '*.jpg;',
    'fileDataName'  : 'image',
    'scriptData'    : {
        'uname':'" . get_username() . "'
    },
    'onComplete'    : function(e,q,f,r,d) {
        var response = $.evalJSON(r);
        if(response.error) {
            show_status(response.error);
        }
        if(response.success) {
            show_status(response.success);
            $('#cropbox').attr('src','" . config_item('temp_upload_path') . "'+response.image);
            $('#upload-area').fadeOut(1000);
            $('#crop-area').fadeIn(1000);
            $('#cropbox').Jcrop({
                aspectRatio: 1/1,
                minSize: [ 50, 50 ],
                maxSize: [ 400, 400 ],
                setSelect: [ 0, 0, 200, 200 ],
                onSelect: function (c){
                    $('#x_axis').val(c.x);
                    $('#y_axis').val(c.y);
                    $('#width').val(c.w);
                    $('#height').val(c.h);
                    $('#filename').val(response.image);
                }
            });
        }
    }
});

$('form').submit(function(){
    if ($(this).children('input[name=width]').val() != ''){
        $.ajax({
            type: 'POST',
            url: 'user/avatar_crop',
            data: $(this).serialize(),
            success: function(response) {
                show_status(response);
            }
        });
    } else {
        alert('Lütfen kırpılacak alanı seçin.');
    }
    return false;
});");

add_css('#crop-area {display:none;}', 'embed');
?>
<?php if (isset($message))
    echo $message; ?>

<div class="subcolumns" id="upload-area">
    <div class="info clearfix">
<?php echo user_image(get_username(), array('class' => 'float_right')); ?>
        Profil resminizi değiştirmek için DOSYA SEÇİN butonuna tıklayarak yüklemek istediğiniz dosyayı seçin.
        Unutmayın, yalnızca .jpg uzantılı dosyalar yükleyebilirsiniz.
    </div>
    <div class="c50l">
        <div class="subcl">
<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform')); ?>
            <input type="file" name="uploadify" id="uploadify" />
<?php echo form_close(); ?>
        </div>
    </div>
    <div class="c50l">
        <div class="subcr">
            <div id="fileQueue"></div>
        </div>
    </div>
</div>
<div id="response"></div>
<div class="subcolumns" id="crop-area">
    <div class="warning">
        Profil resminiz değiştirilmek üzere. Lütfen aşağıdan yüklediğiniz resmin profil resmi olarak kullanmak istediğiniz
        alanını seçerek kırp butonuna basınız.
    </div>
<?php echo form_open('', array('class' => 'sf', 'id' => 'crop')); ?>
    <img src="" id="cropbox" alt="" />
    <br />
    <input type="hidden" name="filename" id="filename" />
    <input type="hidden" name="width" id="width" />
    <input type="hidden" name="height" id="height" />
    <input type="hidden" name="x_axis" id="x_axis" />
    <input type="hidden" name="y_axis" id="y_axis" />
    <input type="submit" value="Kırp" class="awesome"/>
<?php echo form_close(); ?>
</div>