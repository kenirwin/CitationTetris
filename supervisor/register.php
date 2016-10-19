<html>
<head>
<title>Register as a Supervisor</title>
<?php include('../global_settings.php'); ?>
<?php include('../captcha.php'); ?>
<?php include('supervisor_scripts.php'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
<style>
@import url("../style.css");
</style>
<script type="text/javascript">
   $(document).ready(function() {
       $('#reg-form').validate({
	 rules: {
	   name: {
	     required: true
		 },
	       email: {
	     required: true,
		 email: true
		 },
	       confirm_email: {
	     required: true,
		 email: true,
		 equalTo: '#email'
	     },
	       inst_name: {
	     required: true
	     },

	   }
	 });
     });
</script>
</head>
<body>
<h1>Sort Tetris - Register as a Supervisor</h1>

<?php
   if (isset($_REQUEST['submit_button']) && (isset($_REQUEST['g-recaptcha-response']))) {
     ConfirmHuman($captcha_secret_key);
     SubmitSupervisorRequest($require_supervisor_confirmation);
     
     print '<hr />'.PHP_EOL;
     PrintRegForm();
   }
elseif (TestMysql()) {
  if ($allow_supervisor_registration) {
    PrintRegForm();
  }
  else { 
    print 'Registration is not enabled for this site. The administrator will need to set <b>$allow_supervisor_registration = true</b> in <b>global_settings.php</b> to allow this function.';
  }
}

else {
  print 'Unable to connect to MySQL.';
}
?>
<?

?>
<?php include("../license.php"); ?>
</body>
</html>
