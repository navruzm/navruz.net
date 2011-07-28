<html>
    <head>
    <title>Uploader</title>
</head>
<body>
    <?php
    $funcNum = $this->input->get('CKEditorFuncNum');
    $CKEditor = $this->input->get('CKEditor');

    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$upload', '$error');</script>";
    ?>