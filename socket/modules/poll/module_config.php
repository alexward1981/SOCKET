<?php 
// Sets a cookie for use in the poll mod
	$getPoll = mysql_query("SELECT pollID FROM module_poll WHERE pollStatus = 1") or die ('could not get poll:' . mysql_error());
	$pollNumber = mysql_fetch_array($getPoll, MYSQL_BOTH);
	extract($pollNumber, EXTR_PREFIX_ALL, "pn");
	if ($ipresult != 0) {
	setcookie("pollCast", $pn_pollID, time()+31556926, '/');  /* expire in 1 year */
	$fauxCookie = 1;
}
?>