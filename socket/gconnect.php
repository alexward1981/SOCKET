<?php
//GLOBAL CONNECTION - Modifying this may cause SOCKET to stop functioning
$invasion_host = "79.170.40.225";
$global_db = "web225-socket";
$global_un = "web225-socket";
$global_pw = "uj3k45w4h6e36roui";
$globalconn = @mysql_connect($invasion_host, $global_un, $global_pw);
if ($globalconn==FALSE && $setSocket == 1) 
{
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="noindex, nofollow" name="robots"/>
<title>Socket | Website Administration Software</title>';
echo '<link href="http://'.$_SERVER['SERVER_NAME'].'/socket/templates/standard/socket.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="'.$socketroot.'/favicon.ico" />';
echo' </head><body id="errorpage"><div id="horizon"';
echo '<div class="error_box"><strong>Cannot load site</strong> <p>SOCKET is experiencing temporary connection difficulties, please try again later</p></div></div>'; 
require_once($_SERVER['DOCUMENT_ROOT'].'/socket/templates/standard/socket_footer.php'); 
exit();
} else if($globalconn==FALSE && $setSocket == NULL) 
{ $noConnection = 1; }
if (!$noConnection) {
mysql_select_db($global_db) or die('I cannot select the database because:'.mysql_error());
}
?>