<script type="text/javascript">
    var RecaptchaOptions = { 
        theme:"<?php echo $theme ?>",
        lang:"<?php echo $lang ?>"
    };
</script>
<script type="text/javascript" src="<?php echo $server ?>/challenge?k=<?php echo $key . $errorpart ?>"></script>
