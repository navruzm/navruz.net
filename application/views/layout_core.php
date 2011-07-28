<?php
echo doctype('xhtml1-trans');
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
    <head>
    <title><?php echo $title ?></title>
<?php echo meta($meta); ?>
    <base href="<?php echo base_url() ?>"/>
<?php echo $css . "\n"; ?>
    <?php echo $js . "\n"; ?>
    <?php echo $more . "\n"; ?>
</head>
<body>
<?php echo $body; ?>
</body>
</html>