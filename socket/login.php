<?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // If the user is already logged in, bypass the form.
if (isset($_SESSION['userID'])) {
	redirect_to("{SOCKETROOT}/index.php");
}

// Login Script
if (!empty($_POST['submit']))
	{ 
	$post_username = $_POST['usr_username'];
	$post_password = $_POST['usr_password'];
	$post_username = stripslashes($post_username);
	$post_password = stripslashes($post_password);
	$post_username = mysql_real_escape_string($post_username);
	$post_password = mysql_real_escape_string($post_password);
	$secure_password = sha1(md5($post_password));
	
	$sql="SELECT * FROM core_users WHERE usr_username='$post_username' AND usr_password='$secure_password'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);
	
	// If only one result is returned as a match, it must be the right one, so continue.
	if($count==1){
		$loggedin = 1;	// user is local user
		if ($result) { $found_user = mysql_fetch_array($result); // user is local
		$_SESSION['global'] = 0;
		$_SESSION['userID'] = $found_user['userID'];
		$_SESSION['usr_username'] = $found_user['usr_username'];
		$_SESSION['usr_access_lvl'] = $found_user['usr_access_lvl'];
		$_SESSION['usr_email'] = $found_user['usr_email'];		
		$_SESSION['usr_firstname'] = $found_user['usr_firstname'];
		$_SESSION['usr_surname'] = $found_user['usr_surname'];
		$_SESSION['usr_avatar'] = $found_user['usr_avatar'];		
		}
		$logDate = date('l jS \of F Y h:i:s A');
		// Once the user has been found proceed to log the user as online and then grant the user access to SOCKET
		$logthis = "INSERT INTO socket_access_log (username, last_logged_in) VALUES ('". $_SESSION['usr_username'] ."','$logDate')";
		$logged = mysql_query($logthis) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());
		$usr_check_in = "UPDATE core_users SET usr_logged_in = 1 WHERE userID =" . $_SESSION['userID'];
		mysql_query($usr_check_in) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
		redirect_to("{SITEROOT}/socket/index.php");
} else {
		$message = '<p class="error"> Login Failed </p>';
		$loggedin = 0;
	}
	} else { 		if (isset($_GET['loggedout']) && $_GET['loggedout'] == 1) {
			$message = '<p class="success">Logout Successful</p>'; }
}
?>

<?php // If the user has not logged in, the login box will be displayed instead of the page 

if ($loggedin == 0) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Socket | Website Administration Software</title>
<link href="<?php echo SOCKETROOT?>/templates/standard/login.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="horizon">
<div id="login_box">
<h1> Welcome to the <span class="boo">SOCKET</span> Administrator</h1>
<h2 class="boo"> Please Log in:</h2>
<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="post" enctype="multipart/form-data" name="dologin" id="login_form">
<label class="un" for="usr_username">Username</label>
      <input id="usr_username" name="usr_username" type="text"/>
<label class="pw" for="usr_password">Password</label>
      <input id="usr_password" name="usr_password" type="password"/>
      <input id="submit" name="submit" type="submit" value="Login" />

</form>
<div id="status">
<?php 
if (isset($message)) { 
echo $message;
}?>
</div>
<div id="lostpw"><p><span class="boo">Note:</span> Due to security reasons we cannot automatically recover lost passwords, if you have forgotten your login details please contact your <a class="boo" href="mailto:<?php echo $socketadmin; ?>?subject=Lost username and password for <?php echo $sc_sitename; ?> SOCKET Administrator">site administrator</a>.</p></div>
<div id="vandc"><p><span class="boo">SOCKET</span> Administrator v<?php echo number_format($socket_version, 2, '.', ''); ?> © 2007-<?php echo date(Y);?> Alexander Ward.</p> 
</div>
<div class="returntosite">
<a href="<?php echo SITEROOT ?>/index.php">Return to <?php echo $sc_sitename; ?> 
<img src="<?php echo SITEROOT ?>/iphone-icon.png" width="20" height="20" alt="return to <?php echo $sc_sitename; ?>" title="return to <?php echo $sc_sitename; ?>"/>
</a>
</div>
</div>
</body>
</html>
<?php } ?>