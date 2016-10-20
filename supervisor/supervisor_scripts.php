<?
function SupervisorNav() {
  print '<div id="supervisor-nav">'.PHP_EOL.
    '<a class="button-small" href="../">Play</a></span>'.PHP_EOL. 
    '<a class="button-small" href="./">Login as Supervisor</a></span>'.PHP_EOL. 
    '<a class="button-small" href="register.php">Register as Supervisor</a></span>'.PHP_EOL. 
    '<a class="button-small" href="recover.php">Recover Password</a></span>'.PHP_EOL.
    '</div>'.PHP_EOL;
}

function TestMysql() {
  $path = $_SERVER['REQUEST_SCHEME'] .'://'.$_SERVER['HTTP_HOST']. preg_replace('/\/supervisor\/.*/','/',$_SERVER['REQUEST_URI']);
  $url = $path.'ajax.php?action=test-mysql';
  $response = json_decode(file_get_contents($url));
  return($response->success);
}

function PrintRegForm() {
  global $captcha_site_key, $using_captcha;
  print '
<div id="description">
   Once you have registered, you&apos;ll receive an email with a password to gain access to the perfomance history for players identified with your institution.
</div>

';
  SupervisorNav();
print'<form method="post" id="reg-form">
   <label for="name">Name</label><input type="text" name="name" /><br />
   <label for="email">Email</label><input type="text" name="email" id="email"/><br />
   <label for="confirm_email">Confirm Email</label><input type="text" name="confirm_email" /><br />
   <label for="inst_name">Institution Name</label><input type="text" name="inst_name" /><br />
   <input type="submit" name="submit_button" value="Register" />'.PHP_EOL;
if ($using_captcha === true) {
  print'<div class="g-recaptcha" data-sitekey="'.$captcha_site_key.'"></div>';
}
}

function SubmitSupervisorRequest($require_supervisor_confirmation) {
  $path = $_SERVER['REQUEST_SCHEME'] .'://'.$_SERVER['HTTP_HOST']. preg_replace('/\/supervisor\/.*/','/',$_SERVER['REQUEST_URI']);
  $url = $path.'ajax.php?action=register&inst_name='.urlencode($_REQUEST['inst_name']).'&email='.urlencode($_REQUEST['email']).'&contact_name='.urlencode($_REQUEST['name']);
  $response = json_decode(file_get_contents($url));
  if ($response->success == true) {
    print '<h2>Registration Successful.</h2> <div>An email has been sent with your login information.</div>';
    SupervisorNav();
  }
  else { 
    print ('<h2 class="warn">Registration Failed:</h2> ');
    if (preg_match('/Duplicate entry.*contact_email/',$response->error)) {
      $display_error = 'The email address "'.$_REQUEST['email'].'" is already registered. You may <a href="recover.php">recover the password</a> if lost.';
    }
    if (preg_match('/Duplicate entry.*institution_name/',$response->error)) {
      $display_error = 'The institition "'.$_REQUEST['inst_name'].'" is already registered under another email address. Please check to see if your institution is already registered, or register under a different name.';
    }
    else { $display_error = $response->error; }
    print '<ul><li class="warn" data-errorcode='.$response->error.'>'.$display_error.'</li></ul>'.PHP_EOL;
    print '<div>You can try registering again below.</div>'.PHP_EOL;
  }
}


function RecoverPassword() {
  $path = $_SERVER['REQUEST_SCHEME'] .'://'.$_SERVER['HTTP_HOST']. preg_replace('/\/supervisor\/.*/','/',$_SERVER['REQUEST_URI']);
  $url = $path.'ajax.php?action=recoverPassword&email='.urlencode($_REQUEST['email']);
  $response = json_decode(file_get_contents($url));
  if ($response->success == true) { 
    print '<h2>Success</h2><div>The password has been sent to your email</div>'.PHP_EOL;
    SupervisorNav();
  }
  else {
    print '<h2>Unable to Recover Password</h2>';
    print '<div>We were unable to recover the password.</div>';
    if (isset($response->error)) {
      print '<li class="warn">'.$response->error.'</li>'.PHP_EOL;
    }
    print '<hr />'.PHP_EOL;
    print '<h2>Try Password Recovery Again</h2>'.PHP_EOL;
    DisplayRecoveryForm();
  }
}

function DisplayRecoveryForm() {
  global $captcha_site_key, $using_captcha;
  global $using_captcha;
  SupervisorNav();
  print '<form method="post" id="recover-form">
   <label for="email">Email</label><input type="text" name="email" /><br />
   <input type="submit" name="submit_button" value="Recover Password" />'.PHP_EOL;
  if ($using_captcha) {
   print '<div class="g-recaptcha" data-sitekey="'.$captcha_site_key.'"></div>'.PHP_EOL;
  }
}

?>