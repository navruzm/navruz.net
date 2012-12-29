<html>
    <body>
        <h1>Reset Password for <?php echo $email; ?></h1>
        <p>Please click this link to <?php echo anchor('user/reset_password/' . $_id. '/' . $key, 'Reset Your Password'); ?>.</p>
    </body>
</html>