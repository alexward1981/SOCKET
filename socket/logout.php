<?php session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php');
		$logDate = date('l jS \of F Y h:i:s A');
		$logout_username = $_SESSION['usr_username'];
		// Set the user as offline and log them out of socket
		$logthis = "UPDATE socket_access_log SET last_logged_out = '$logDate' WHERE username = '$logout_username'";
		$logged = mysql_query($logthis) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());
		$usr_check_in = "UPDATE core_users SET usr_logged_in = 0 WHERE userID =" . $_SESSION['userID'];
if ($g == 1) {
		mysql_query($usr_check_in, $globalconn) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
} else {
		mysql_query($usr_check_in) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
}
$_SESSION = array();
if(isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
}
// Destroy Session
session_unset();
session_destroy();
mysql_close($conn);
mysql_close($globalconn);
?>
<script language="JavaScript">
      window.location.href = '<?php echo SOCKETROOT ?>/login.php?loggedout=1';
</script>
