<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr">
    <head>
        <title>File Manager</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <style type="text/css">
            body {
                background: #EEE;
                padding: 15px;
                margin: 0;
                font-family: Lucida Grande, Verdana, Sans-serif;
                font-size: 14px;
                color: #4F5155;
            }
            #container {
                width:530px;
            }
            #filetree {
                width: 300px;
                height: 400px;
                border-top: solid 1px #BBB;
                border-left: solid 1px #BBB;
                border-bottom: solid 1px #FFF;
                border-right: solid 1px #FFF;
                background: #FFF;
                overflow: scroll;
                float:left;
            }
            #preview {
                margin-left:20px;
                width:200px;
                float:left;
                height:150px;
            }
            #add {
                display:none;
            }
        </style>
        <base href="<?php echo base_url();?>" />
        <link href="assets/css/jquery.filetree.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="assets/css/button.css" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="js/jquery-filetree.js"></script>
        <script type="text/javascript">
            /* <![CDATA[ */
            $(document).ready( function() {
                $('#filetree').fileTree({ root: 'media/upload/', script: 'extra.php/admin/manager/files' }, function(file) {
                    $('#preview').html('<img src="'+ file+'" alt="" />');
                    $('#add').css('display','inline');
                });

                $('#add').click(function(){
                    window.opener.CKEDITOR.tools.callFunction(<?php echo $this->input->get('CKEditorFuncNum');?>, $('#preview img').attr('src'));
                    window.close();
                });
            });
            /* ]]> */
        </script>
    </head>
    <body>
        <div id="container">
            <div id="filetree"></div>
            <div id="preview">
                Önizleme için dosya seçiniz.
            </div>
            <button id="add" class="awesome">Seç</button>
            <div style="clear:both"></div>
        </div>
    </body>
</html>
