<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/socket/globals.php';

$user_details=$fb->api_client->users_getInfo($fb_user, array('last_name', 'first_name', 'email', 'pic', 'about_me', 'profile_url'));  
$usr_firstName = $user_details[0]['first_name']; 
$usr_lastName = $user_details[0]['last_name'];
// Check for existing username and automatically append things on to the end until a free name is found.
$UNToCheck = $usr_firstName.$usr_lastName; 
$UNCheck = mysql_query("SELECT COUNT(*) AS usercount FROM core_users WHERE usr_username = ".mysql_real_escape_string($UNToCheck));
if ($usercount != 0) { // If username exists
$UNToCheck = $usr_firstName.$usr_lastName.'2'; //Add a '2' on the end
mysql_query("SELECT COUNT(*) AS usercount2 FROM core_users WHERE usr_username = ".mysql_real_escape_string($UNToCheck));
}
if ($usercount2 != 0) { // If username exists
$UNToCheck = $usr_firstName.$usr_lastName.date(Y); // Add the 4 digit current year on the end
mysql_query("SELECT COUNT(*) AS usercount3 FROM core_users WHERE usr_username = ".mysql_real_escape_string($UNToCheck));
}
if ($usercount3 != 0) { // If username exists
$UNToCheck = $usr_firstName.$usr_lastName.date(U); //add the UNIX time code on the end (always will be unique)
mysql_query("SELECT COUNT(*) AS usercount4 FROM core_users WHERE usr_username = ".mysql_real_escape_string($UNToCheck));
}
$usr_username = strtolower($UNToCheck);
$usr_email = $user_details[0]['email'];
$usr_avatar = $user_details[0]['pic'];
if ($user_details[0]['about_me']) {
$usr_bio = '<p>' . mysql_real_escape_string($user_details[0]['about_me']) . '</p>';
} else {
$usr_bio = '';
}
$usr_facebook = str_replace('http://www.facebook.com/', '', $user_details[0]['profile_url']);

$query="INSERT INTO core_users (fbcID, usr_firstname, usr_surname, usr_username, usr_email, usr_email_private, usr_active, usr_avatar, usr_biography, usr_facebook, usr_access_lvl) values ('$fb_user', '$usr_firstName', '$usr_lastName', '$usr_username', '$usr_email', '1', '1', '$usr_avatar', '$usr_bio', '$usr_facebook', '6')";
mysql_query($query) or die ('Failed because: '.mysql_error());
if ($query) {
	$message = '<div class="success"><strong>Facebook Sync Complete</strong><p>Your facebook account has been linked to $sc_sitename Please login. </p></div>';
	}
redirect_to("{SITEROOT}/modules/users/login.php?message=$message");
?>