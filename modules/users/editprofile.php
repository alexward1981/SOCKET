<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table
?>
<?php
	$meta_title ='Register a new ' . $sc_sitename .' account';
	$meta_keywords = "$meta_key";
	$meta_description = $sc_sitename . '\'s list of users';
	$special_crumb = '<a href="'. $siteroot . '/modules/users/users.php">'.users.'</a>';
	$mceInit = 1;	//Activates TinyMCE
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php'); ?>
<h1> Edit your Account Settings</h1>
<?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
if (!empty($_FILES['usr_avatar'])) 
			{
			$filetype = $_FILES['usr_avatar']['type'];
			if($filetype == 'image/jpeg' || $filetype == 'image/jpg' || $filetype == 'image/gif' || $filetype == 'image/png') 
				{
			$db_usr_avatar= $serverroot .'/socket/modules/users/avatars/' . date(U) . $_FILES['usr_avatar']['name'];
			move_uploaded_file($_FILES['usr_avatar']['tmp_name'], $db_usr_avatar);
				}
			}
$db_usr_firstname = addslashes(htmlentities($_POST['usr_firstname']));	
$db_usr_surname = addslashes(htmlentities($_POST['usr_surname']));	
$db_usr_username = addslashes(htmlentities($_POST['usr_username']));	
$db_usr_password = sha1(md5($_POST['usr_password']));
$db_usr_email = addslashes(htmlentities($_POST['usr_email']));	
$usr_email_private = $_POST['usr_email_private'];	
$db_usr_city = addslashes(htmlentities($_POST['usr_city']));	
$db_usr_country = addslashes(htmlentities($_POST['usr_country']));	
$db_usr_twitter = addslashes(htmlentities($_POST['usr_twitter']));	
$db_usr_facebook = addslashes(htmlentities($_POST['usr_facebook']));		
$db_usr_linkedin = addslashes(htmlentities($_POST['usr_linkedin']));	
$db_usr_biography = addslashes($_POST['usr_biography']);	
//Creates a random activation key
$activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
//Validates the form data
$mandatory[] = "usr_firstname";
$mandatory[] = "usr_surname";
if ($_POST['passwordUpdate']) {
$mandatory[] = "usr_password";
$mandatory[] = "usr_password_check";
}
$mandatory[] = "usr_email";
$mandatory[] = "recaptcha_response_field";
foreach($mandatory as $item) if(!isset($_POST[$item]) || empty($_POST[$item])) { 
$errors[$item] = TRUE;
$emptyMessage = 'This field cannot be empty';
}
if ($_POST['passwordUpdate']) {
if ($_POST['usr_password'] != $_POST['usr_password_check']) {
$errors['password_mismatch'] = TRUE;
$passwordMismatch = 'Passwords do not match';
}
}
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['usr_email'])) {
  $errors['invalid_email'] = TRUE;
$invalidEmail = 'That does not appear to be a valid email address';
}
$un_tocheck = $_POST['usr_username'];
	$sql="SELECT * FROM core_users WHERE usr_username='$un_tocheck'";
	$result=mysql_query($sql);
	$userExists=mysql_num_rows($result);
if ($userExists != 0) {
$errors['username_taken'] = TRUE;
$usernameTaken = 'This username has already been taken';
}

//Performs the Captcha
 require_once($serverroot . '/Scripts/recaptchalib.php');
 $privatekey = "6Ld5pgsAAAAAAO19vVx1p-Nl8Cwc-G81k3bxi5hc";
 $resp = recaptcha_check_answer ($privatekey,
                               $_SERVER["REMOTE_ADDR"],
                               $_POST["recaptcha_challenge_field"],
                               $_POST["recaptcha_response_field"]);

 if (!$resp->is_valid) {
   // What happens when the CAPTCHA was entered incorrectly
		$errors['captcha_failed'] = TRUE;
		$captchaFailed = 'You did not enter the details correctly';
 } 
if(count($errors) != 0) { 

$message= '<div class="failure"><strong>Failure</strong><p>Errors have been detected please review the form</p></div>';

} else {
	


//Everything is hunky dory, submit the form.	
// Gets the post data and puts it in variables.	

$dbinsert = "UPDATE core_users SET ";
$dbinsert .= "usr_firstname = '$db_usr_firstname', ";
$dbinsert .= "usr_surname = '$db_usr_surname', ";
if ($_POST['passwordUpdate']) {
$dbinsert .= "usr_password = '$db_usr_password', ";
}
$dbinsert .= "usr_email = '$db_usr_email', ";
$dbinsert .= "usr_email_private = '$usr_email_private', ";
$dbinsert .= "usr_city = '$db_usr_city', ";
$dbinsert .= "usr_country = '$db_usr_country', ";
$dbinsert .= "usr_twitter = '$db_usr_twitter', ";
$dbinsert .= "usr_facebook = '$db_usr_facebook', ";
$dbinsert .= "usr_linkedin = '$db_usr_linkedin', ";
$dbinsert .= "usr_biography = '$db_usr_biography'";
if ($_POST['avatarUpdate']) {
$dbinsert .= ", usr_avatar = '$db_usr_avatar'";
}
$dbinsert .= " WHERE userID = ". $_SESSION['userID'];
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Edit Failed!</strong><p>' . mysql_error() . '</p></div>');
}

if ($posted) { ?>
<div class="success"><strong>Success!</strong> <p>Your account has been updated</p></div>
<?php require_once('' . $serverroot . '/style/standard/footer.php');
exit();
}

	}	
	
 ?>
<?php echo $message; 
$findUser = mysql_query("SELECT * FROM core_users WHERE userID =".$_SESSION['userID']);
$userArray = mysql_fetch_array($findUser, MYSQL_BOTH);
extract($userArray, EXTR_PREFIX_ALL, "db");
?>
<form enctype="multipart/form-data" id="registerForm" action="<?php $_SERVER['SCRIPT_NAME']?>" method="post">
  <p>
  <?php if($errors['usr_firstname']) {
    echo '<label class="error" for="usr_firstname">First Name</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else {
	echo '<label class="normal" for="usr_firstname">First Name</label>';
	} ?>
      <input name="usr_firstname" type="text" size="50" class="biginput" value="<?php echo stripslashes(html_entity_decode($db_usr_firstname)) ?>" />
    
  </p>
  <p>
  <?php if($errors['usr_surname']) {
    echo '<label class="error" for="usr_surname">Surname</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else {
	echo '<label class="normal" for="usr_surname">Surname</label>';
	} ?>
      <input name="usr_surname" type="text" size="50" class="biginput" value="<?php echo stripslashes(html_entity_decode($db_usr_surname)) ?>"/>
    
  </p>
  <p>
  <label class="normal" for="usr_username">Username (Cannot be changed)</label>
      <input name="usr_username" type="text" disabled="disabled" size="50" class="biginput" value="<?php echo stripslashes(html_entity_decode($db_usr_username))?>"/>
    
  </p>
  <p> <label class="normal">Update Password? 
    <input onclick="if (this.checked) {document.getElementById('usr_password_box').style.display='inline'; } else { document.getElementById('usr_password_box').style.display='none'; } return true;" name="passwordUpdate" type="checkbox" value="1" />
  </label></p>
  <div id="usr_password_box" style="display:none">
     <?php if($errors['usr_password']) {
    echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['password_mismatch']){
	echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$passwordMismatch.'</span> ';	 
	 } else {
	echo '<label class="normal" for="usr_password">Password</label>';
	} ?>
      <input name="usr_password" type="password" class="biginput" size="50" value=""/> 
  <br />
     <p>
    <?php if($errors['usr_password_check']) {
    echo '<label class="error" for="usr_password_check">Repeat Password</label><span class="red bold">  - '.$emptyMessage.'</span> ';
		 } else {
	echo '<label class="normal" for="usr_password_check">Repeat Password</label>';
	} ?>
      <input name="usr_password_check" type="password" class="biginput" size="50" value=""/> 
  </p>
  </div>
  <p>
     <?php if($errors['usr_email']) {
    echo '<label class="error" for="usr_email">Email Address</label><span class="red bold">  - '.$emptyMessage.'</span> ';
		 } else if($errors['invalid_email']) {
	echo '<label class="error" for="usr_email">Email Address</label><span class="red bold">  - '.$invalidEmail.'</span> ';
	} else {
	echo '<label class="normal" for="usr_email">Email Address</label>';
	} ?>
    <input name="usr_email" type="text" size="50" class="biginput"  value="<?php echo stripslashes(html_entity_decode($db_usr_email)) ?>" />
	<label class="normal" for="usr_email_private">Keep email address private
  <input name="usr_email_private" type="checkbox" <?php if($db_usr_email_private ==1) { echo 'checked="checked"'; } ?> value="1" /></label>
    </p>
  <h2>The following fields are optional but recommended </h2>
  <p>
    <label class="<?php if($errors['usr_city']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_city">City</label>
      <input name="usr_city" type="text" class="biginput" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_city)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_country']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_country">Country</label>
      <input name="usr_country" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_country)) ?>"/>
    
  </p>
  <p>
    <label class="<?php if($errors['usr_twitter']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_twitter">Twitter Profile Name</label>
      <input name="usr_twitter" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_twitter)) ?>"/>
    
  </p>
  <p>
    <label class="<?php if($errors['usr_facebook']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_facebook">Facebook Profile Name</label>
      <input name="usr_facebook" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_facebook)) ?>"/>
    
  </p>
  <p>
    <label class="<?php if($errors['usr_linkedin']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_linkedin">Linkedin Profile Name</label>
      <input name="usr_linkedin" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_linkedin)) ?>"/>
    
  </p>
  <p>
    <label class="<?php if($errors['usr_biography']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_biography">Biography</label>
      <textarea name="usr_biography" class="biginput" cols="75" rows="7"><?php echo stripslashes(html_entity_decode($db_usr_biography)) ?></textarea>
    
  </p><label class="normal">Update Profile Picture? 
    <input onclick="if (this.checked) {document.getElementById('usr_avatar_box').style.display='inline'; } else { document.getElementById('usr_avatar_box').style.display='none'; } return true;" name="avatarUpdate" type="checkbox" value="1" />
  </label><br />
<br />
  <div id="usr_avatar_box" style="display:none">
<label class="<?php if($errors['usr_avatar']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_avatar">Profile Image</label>
  <p class="bigFileBox">
    <input name="usr_avatar" type="file"  class="bigFile"  size="70" value="usr_avatar"/>
  </p></div>
  <div>
  <?php if($errors['recaptcha_response_field']) {
    echo '<label class="error" for="recaptcha_challenge_field">Are you human? </label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['captcha_failed']){
	echo '<label class="error" for="recaptcha_challenge_field">Are you human? </label><span class="red bold">  - '.$captchaFailed.'</span> ';	 
	 } else {
	echo '<label class="normal" for="recaptcha_challenge_field">Are you human? </label>';
	} 
 require_once($serverroot . '/Scripts/recaptchalib.php');
 $publickey = "6Ld5pgsAAAAAAAwm1Owe8LN-ZYqcdZJXfXXx8Ve8"; // you got this from the signup page
 echo recaptcha_get_html($publickey);
  ?>
</div>
<input class="regSubmit" name="submit" type="submit" value="Update"/>
</form>

<?php
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>