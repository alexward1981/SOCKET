<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/socket/globals.php';
if(!$fb_user) {
	redirect_to("{$siteroot}/modules/users/login.php");
}
if ($_POST['submit']) {
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
			}// end if errors 
			else //if no errors
			{ 
				$u_user = $_POST['usr_username'];
				$u_passw = sha1(md5($_POST['usr_password']));			
				$connected = false;
				if($u_user && $u_passw && $fb_user) { // We have all the info we need		
					$query="SELECT userID FROM core_users WHERE usr_username='" . $u_user . "' AND usr_password ='" . $u_passw ."'";
					$result = mysql_query($query);
					if(!$result) {
						$_SESSION['fb_connect_error']="Invalid login";
						echo 'Its all gone wrong somehow<br />';
						echo "username was $u_user<br />";
						echo "Password was $u_passw<br />";						
						echo "FBUser was $fb_user<br />";						
					} else {
						//Got a row, Ok now link up the accounts
						$dataArray = mysql_fetch_array($result, MYSQL_BOTH);
						extract($dataArray, EXTR_PREFIX_ALL, "u");
						$query="UPDATE core_users SET fbcID = $fb_user WHERE userID = $u_userID";
						mysql_query($query);
						$connected=true;
					}
					if($connected) {
						unset($_SESSION['fb_connect_error']);							
						//OK we linked up their account now log them in. 
						$_SESSION['userID']= $u_userID;    	
						redirect_to("{$siteroot}/index.php");
					}
				}
			} // end if no errors
} // end if submit
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php'); ?>
<h1> Next step... </h1>
<p>If you already have an account with <?php echo $sc_sitename; ?> you can link to it using the form below. </p> 
<p>Not registered with us yet? Click the button below to automatically generate and account using your facebook details<br />
 <a class="fbcButton" href="<?php echo $sireroot ?>/modules/users/newFbAccount.php">Create new account using Facebook Connect</a> </p>

<h1> Link Accounts </h1>
<p> Link to an existing account by entering your username/password below. This will let us connect our Facebook Identity to your 
  identity on our site, so that you don't loose any data you've stored here.</p>
<p>You will only have to do this one time.</p>
<?php 
if($_SESSION['fb_connect_error']!=null) {
        echo '<h2 class="error_medium">' . $_SESSION['fb_connect_error'] . "</h2><br/>";
} 

?>
<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="post" enctype="multipart/form-data"  id="login_form">
<input type="hidden" name="fb_user" value="<?php echo($fb_user) ?>">
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
  <input class="button" name="submit" type="submit" value="Login" />
</form>
<?php require_once('' . $serverroot . '/style/standard/footer.php'); ?>
