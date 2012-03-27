<?php session_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // If the user is already logged in, bypass the form.
$prevPage = $_GET['r'];
if (isset($_SESSION['userID'])) {
	redirect_to("{SITEROOT}$prevPage");
}
if (isset($_GET['loggedout']) && $_GET['loggedout'] == 1) {
		$message = '<div class="success"><strong>Logout Successful</strong><p> You have sucessfully logged out </p></div>'; 
	}
//Facebook Connect
if($fb_user) {
	//Query your database to see if the $fb_user exists
    $query="select * from core_users where fbcID = $fb_user";
    $result=mysql_query($query);
	//if you get a row back then log the user into your application by setting a cookie 
	//or by setting a session variable then send off to the logged in user page:        
	if($result && @mysql_num_rows($result)==1) {
		$fbFoundYou = mysql_fetch_array($result);
    	$_SESSION['userID']= $fbFoundYou['userID'];
		$_SESSION['usr_username'] = $fbFoundYou['usr_username'];
		$_SESSION['usr_access_lvl'] = $fbFoundYou['usr_access_lvl'];
		$_SESSION['usr_email'] = $fbFoundYou['usr_email'];	
		$_SESSION['usr_location'] = $fbFoundYou['usr_country'];	
		$_SESSION['usr_firstname'] = $fbFoundYou['usr_firstname'];
		$_SESSION['usr_surname'] = $fbFoundYou['usr_surname'];
		$_SESSION['usr_avatar'] = $fbFoundYou['usr_avatar'];	
		$_SESSION['fblogged'] = 1;
		$loginPage =1;
		$logDate = date('l jS \of F Y h:i:s A');
		// Once the user has been found proceed to log the user as online and then grant the user access to SOCKET
		$logthis = "INSERT INTO socket_access_log (username, last_logged_in) VALUES ('". $_SESSION['usr_username'] ."','$logDate')";
		$logged = mysql_query($logthis) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());
		$usr_check_in = "UPDATE core_users SET usr_logged_in = 1 WHERE userID =" . $_SESSION['userID'];
		mysql_query($usr_check_in) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
    	//After setting session values, forward them off to the Logged in User page.
	redirect_to("{SITEROOT}$prevPage");
	} else {
		//user is logged into FB and has authorized yoursite.com for connect. But
		//we don't have their fb_user id stored on yoursite.com database yet.		
		//so send them to the Connect Account page so they can either link up with an 
		//existing account or create a new one.
	redirect_to("{SITEROOT}/modules/users/fbconnect.php");
	}
}


// Login Script
if (!empty($_POST['submit'])) { 
	$post_username = $_POST['usr_username'];
	$post_password = $_POST['usr_password'];
	$post_username = stripslashes($post_username);
	$post_password = stripslashes($post_password);
	$post_username = mysql_real_escape_string($post_username);
	$post_password = mysql_real_escape_string($post_password);
	$secure_password = sha1(md5($post_password));
	//Validates the input
	$mandatory[] = "usr_username";
	$mandatory[] = "usr_password";
	foreach($mandatory as $item) 
		if(!isset($_POST[$item]) || empty($_POST[$item])) { 
			$errors[$item] = TRUE;
			$emptyMessage = 'This field cannot be empty';
		}
	$un_tocheck = $_POST['usr_username'];
	$sql="SELECT * FROM core_users WHERE usr_username='$un_tocheck'";
	$result=mysql_query($sql);
	$userExists=mysql_num_rows($result);
		if ($userExists == 0) {
			$errors['no_user'] = TRUE;
			$noUser = 'This username does not exist';
		}
	$pw_tocheck = sha1(md5($_POST['usr_password']));
	$sql2="SELECT * FROM core_users WHERE usr_password='$pw_tocheck'";
	$result2=mysql_query($sql2);
	$passExists=mysql_num_rows($result2);
		if ($passExists == 0) {
			$errors['no_pass'] = TRUE;
			$noPass = 'This password does not match the username';
		}
		if(count($errors) != 0) { 
			$message= '<div class="failure"><strong>Failure</strong><p>Login failed, errors detected</p></div>';
			$loggedin = 0;
		} else {
			$sql="SELECT * FROM core_users WHERE usr_username='$post_username' AND usr_password='$secure_password'";
			$result=mysql_query($sql);
			$count=mysql_num_rows($result);
	
				// If only one result is returned as a match, it must be the right one, so continue.
				if($count==1){
					$loggedin = 1;	// user is local user
					if ($result) { $found_user = mysql_fetch_array($result); // user is local
						if ($found_user['usr_active'] == 1) {
							$_SESSION['global'] = 0;
							$_SESSION['userID'] = $found_user['userID'];
							$_SESSION['usr_username'] = $found_user['usr_username'];
							$_SESSION['usr_access_lvl'] = $found_user['usr_access_lvl'];
							$_SESSION['usr_email'] = $found_user['usr_email'];		
							$_SESSION['usr_firstname'] = $found_user['usr_firstname'];
							$_SESSION['usr_surname'] = $found_user['usr_surname'];
							$_SESSION['usr_avatar'] = $found_user['usr_avatar'];	
							$logDate = date('l jS \of F Y h:i:s A');
							// Once the user has been found proceed to log the user as online and then grant the user access to SOCKET
							$logthis = "INSERT INTO socket_access_log (username, last_logged_in) VALUES ('". $_SESSION['usr_username'] ."','$logDate')";
							$logged = mysql_query($logthis) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());
							$usr_check_in = "UPDATE core_users SET usr_logged_in = 1 WHERE userID =" . $_SESSION['userID'];
							mysql_query($usr_check_in) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
							redirect_to("{SITEROOT}$prevPage");
							} else {
							$loggedin = 0;
							$message = '<div class="failure"><strong>User not active</strong><p>Your account has not been activated </p></div>';
							}
					}
			
				} 
				
		}
}
 // If the user has not logged in, the login box will be displayed instead of the page 

if ($loggedin == 0) {

	$meta_title = $sc_sitename .' Users';
	$meta_keywords = "$meta_key";
	$meta_description = $sc_sitename . '\'s list of users';
	$regOpen = 0;
	$loginPage =1;
// imports header information
require_once('' . SERVERROOT . '/assets/style/standard/head.php');
require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
require_once('' . SERVERROOT . '/assets/style/standard/header.php'); ?>

<h1> Welcome to <?php echo $sc_sitename ?></h1>
<h2> Login Directly:</h2>
<?php 
if ($_GET['message']){ $message = $_GET['message']; }
echo $message;
?>
<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="post" enctype="multipart/form-data" name="dologin" id="login_form">
  <?php if($errors['usr_username']) {
    echo '<label class="error" for="usr_username">Username</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['no_user']) {
	echo '<label class="error" for="usr_username">Username</label><span class="red bold">  - '.$noUser.'</span> '; 
	 } else {
	 echo '<label class="normal" for="usr_username">Username</label>';
	} ?>
  <input class="box" name="usr_username" type="text"/>
  <?php if($errors['usr_password']) {
    echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['no_pass']) {
	echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$noPass.'</span> '; 
	 } else {
	 echo '<label class="normal" for="usr_password">Password</label>';
	} ?>
  <input class="box" name="usr_password" type="password"/>
  <input class="button" name="submit" type="submit" value="Login" /><div id="lostpw">
  <p><span class="bold">Note:</span> Due to security reasons we cannot automatically recover lost passwords, if you have forgotten your login details please contact your <a class="boo" href="mailto:<?php echo $socketadmin; ?>?subject=Lost username and password for <?php echo $sc_sitename; ?> SOCKET Administrator">site administrator</a>.</p>
</div>
</form>

  <h2> Log in using Facebook</h2>
  <p><strong> If you already have a Facebook Account you can log into our site using Facebook connect</strong></p><br />

  <fb:login-button v="2" size="large" onlogin="window.location.reload(true);">Login using Facebook Connect</fb:login-button>
<h2> Don't have an account? Register Now</h2>
<p> <a class="regButton" href="<?php echo SITEROOT ?>/register.php">Click here to register with us</a> or click the button below to register instantly using your Facebook account </p><br />

  <fb:login-button v="2" size="large" onlogin="window.location.reload(true);">Register instantly with Facebook</fb:login-button>

<div id="vandc">
  <p>Powered by <span class="boo">SOCKET</span> v<?php echo number_format($socket_version, 2, '.', ''); ?> © 2007-<?php echo date(Y);?> <a href="http://www.alexward.me.uk">Alexander Ward.</a></p>
</div>
<?php
}
//Main content ends here
require_once('' . SERVERROOT . '/assets/style/standard/footer.php');
?>
