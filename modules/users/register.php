<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table
?>
<?php
	$meta_title ='Register a new ' . $sc_sitename .' account';
	$meta_keywords = "$meta_key";
	$meta_description = $sc_sitename . '\'s list of users';
	$special_crumb = '<a href="'. $siteroot . '/modules/users/users.php">'.users.'</a>';
	$regOpen = 1; // If set to '0' no new users will be able to sign up
	$mceInit = 1;	//Activates TinyMCE
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php'); ?>

<?php if (!$regOpen) {
echo '<h1> Registrations Closed</h1>';
echo '<p>At present we are not accepting new registrations, please check back soon</p>';
require_once('' . $serverroot . '/style/standard/footer.php');
exit();
} 
?>
<h1> Register for Digital Fusion</h1>
<p>In order to post comments on this site you need to be registered</p>

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
$db_usr_linkedin = addslashes($_POST['usr_linkedin']);	
$db_usr_biography = addslashes($_POST['usr_biography']);	
//Creates a random activation key
$activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
//Validates the form data
$mandatory[] = "usr_firstname";
$mandatory[] = "usr_surname";
$mandatory[] = "usr_username";
$mandatory[] = "usr_password";
$mandatory[] = "usr_password_check";
$mandatory[] = "usr_email";
$mandatory[] = "recaptcha_response_field";
foreach($mandatory as $item) if(!isset($_POST[$item]) || empty($_POST[$item])) { 
$errors[$item] = TRUE;
$emptyMessage = 'This field cannot be empty';
}
if ($_POST['usr_password'] != $_POST['usr_password_check']) {
$errors['password_mismatch'] = TRUE;
$passwordMismatch = 'Passwords do not match';
}
if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['usr_email'])) {
  $errors['invalid_email'] = TRUE;
$invalidEmail = 'That does not appear to be a valid email address';
}
if(!eregi("^[A-Za-z0-9_]{5,50}$" , $_POST['usr_username'])) {
  $errors['invalid_username'] = TRUE;
$invalidUsername = 'Must be at least 5 characters and cannot contain any spaces';
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

$dbinsert = "INSERT INTO core_users (usr_firstname, usr_surname, usr_username, usr_password, usr_email, usr_email_private, usr_city, usr_country,  usr_twitter, usr_facebook, usr_linkedin, usr_biography, usr_access_lvl, usr_avatar, usr_hash) VALUES ('$db_usr_firstname', '$db_usr_surname', '$db_usr_username', '$db_usr_password', '$db_usr_email', '$usr_email_private', '$db_usr_city', '$db_usr_country', '$db_usr_twitter', '$db_usr_facebook', '$db_usr_linkedin', '$db_usr_biography', 6, '$db_usr_avatar', '$activationKey')";
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Failure!</strong> Your new user has not been added' . mysql_error() . '</div>');
}

if ($posted) {
	// If the user is posted send the activation email
	$em_to      = $db_usr_email;
	$em_subject = " Activate your " .$sc_sitename. " Account";
	$em_message = "Welcome to ".$sc_sitename." \n \n You, or someone using your email address, has completed registration at ".$siteroot.". You can complete registration by clicking the following link: \n ".$siteroot."/modules/users/activate.php?".$activationKey." \n \n If this is an error, please ignore this email. \n \n Regards, \n The " .$sc_sitename. " team \n \n Note: Please do not reply to this email. We will not receive it";

$em_headers = 'From: noreply@'.str_replace('http://www.', '', $siteroot). "\r\n" .

    'Reply-To: noreply@'.str_replace('http://www.', '', $siteroot). "\r\n" .

    'X-Mailer: PHP/' . phpversion();

mail($em_to, $em_subject, $em_message, $em_headers);
?>
<div class="success"><strong>Success!</strong> <p>You are now registered</p></div>
<p> You will soon receive an activation email, please follow the instructions to activate your account. If you do not receive the email don't forget to check your spam folder. If it is still not there then please <a href="<?php echo $siteroot ?>/contactus.php">let us know </a> and we'll sort you out</p>

<?php require_once('' . $serverroot . '/style/standard/footer.php');
exit();
}

	}	
	
 ?>
<?php echo $message; ?>
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
      <input name="usr_surname" type="text" size="50" class="biginput" value="<?php echo stripslashes(html_entity_decode($db_usr_surname)) ?>" />
    
  </p>
  <p>
  <?php if($errors['usr_username']) {
    echo '<label class="error" for="usr_username">Desired Username</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['invalid_username']) {
	echo '<label class="error" for="usr_username">Desired Username</label><span class="red bold">  - '.$invalidUsername.'</span> '; 
	 } else if($errors['username_taken']) {
	echo '<label class="error" for="usr_username">Desired Username</label><span class="red bold">  - '.$usernameTaken.'</span> '; 
	 } else {
	 
	echo '<label class="normal" for="usr_username">Desired Username</label>';
	} ?>
      <input name="usr_username" type="text" size="50" class="biginput" value="<?php echo stripslashes(html_entity_decode($db_usr_username))?>" />
    
  </p>
  <p>
     <?php if($errors['usr_password']) {
    echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['password_mismatch']){
	echo '<label class="error" for="usr_password">Password</label><span class="red bold">  - '.$passwordMismatch.'</span> ';	 
	 } else {
	echo '<label class="normal" for="usr_password">Password</label>';
	} ?>
      <input name="usr_password" type="password" class="biginput" size="50" value="" /> 
  </p>
     <p>
    <?php if($errors['usr_password_check']) {
    echo '<label class="error" for="usr_password_check">Repeat Password</label><span class="red bold">  - '.$emptyMessage.'</span> ';
		 } else {
	echo '<label class="normal" for="usr_password_check">Repeat Password</label>';
	} ?>
      <input name="usr_password_check" type="password" class="biginput" size="50" value="" /> 
  </p>
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
  	  <input name="usr_email_private" type="checkbox" checked="checked" value="1" /></label>
  </p>
  <h2>The following fields are optional but recommended </h2>
  <p>
    <label class="<?php if($errors['usr_city']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_city">City</label>
      <input name="usr_city" type="text" class="biginput" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_city)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_country']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_country">Country</label>
      <input name="usr_country" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_country)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_twitter']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_twitter">Twitter Profile Name</label>
      <input name="usr_twitter" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_twitter)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_facebook']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_facebook">Facebook Profile Name</label>
      <input name="usr_facebook" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_facebook)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_linkedin']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_linkedin">Linkedin Profile Name</label>
      <input name="usr_linkedin" class="biginput" type="text" size="50" value="<?php echo stripslashes(html_entity_decode($db_usr_linkedin)) ?>" />
    
  </p>
  <p>
    <label class="<?php if($errors['usr_biography']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_biography">Biography</label>
      <textarea name="usr_biography" class="biginput" cols="75" rows="5"><?php echo stripslashes(html_entity_decode($db_usr_biography)) ?></textarea>
    
  </p>
<label class="<?php if($errors['usr_avatar']) { echo 'error'; } else { echo 'normal';} ?>"  for="usr_avatar">Profile Image</label>
  <p class="bigFileBox">
    <input name="usr_avatar" type="file"  class="bigFile"  size="70" value="usr_avatar" />
  </p>
  <div>
  <?php if($errors['recaptcha_response_field']) {
    echo '<label class="error" for="recaptcha_response_field">Are you human? </label><span class="red bold">  - '.$emptyMessage.'</span> ';
	 } else if($errors['captcha_failed']){
	echo '<label class="error" for="recaptcha_response_field">Are you human? </label><span class="red bold">  - '.$captchaFailed.'</span> ';	 
	 } else {
	echo '<label class="normal" for="recaptcha_response_field">Are you human? </label>';
	} 
 require_once($serverroot . '/Scripts/recaptchalib.php');
 $publickey = "6Ld5pgsAAAAAAAwm1Owe8LN-ZYqcdZJXfXXx8Ve8"; // you got this from the signup page
 echo recaptcha_get_html($publickey);
  ?>
</div>
<input class="regSubmit" name="submit" type="submit" value="Sign up" />
</form>

<?php
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>