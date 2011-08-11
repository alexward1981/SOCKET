<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // If the user is already logged in, bypass the form.
if (isset($_SESSION['userID'])) {
	// Don't let active users see this page.
	redirect_to("{$siteroot}/modules/users/index.php");
}
if (!isset($_SERVER['QUERY_STRING'])) {
	// Don't let users get to this page without a key
	redirect_to("{$siteroot}/modules/users/index.php");
}
// This script will verify an inactive user
$queryString = $_SERVER['QUERY_STRING'];
$query = "SELECT * FROM core_users";
$result = mysql_query($query) or die(mysql_error());
  while($row = mysql_fetch_array($result)){
    if ($queryString == $row['usr_hash']){
       $sql="UPDATE core_users SET usr_hash = '', usr_active='1' WHERE userID =" . $row['userID'];
       $message = 'Your account is now activated. <a href="'.$siteroot.'/modules/users/login.php">Please click here to log in</a>';
		   if (!mysql_query($sql))
		   {
			die('Error: ' . mysql_error());
		   }
	 }
   }
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php'); 
echo $message;
require_once('' . $serverroot . '/style/standard/footer.php'); 
exit();
  ?>
