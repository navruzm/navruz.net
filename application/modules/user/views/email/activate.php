<html>
<body>
	<h1>Activate account for <?php echo $email;?></h1>
	<p>Please click this link to <?php echo anchor('user/activate/'. $_id .'/'. $activation['key'], 'Activate Your Account');?>.</p>
</body>
</html>