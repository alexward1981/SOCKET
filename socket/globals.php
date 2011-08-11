<?php

/*********************************************************/
/* SOCKET Global configuration file				  	     */
/*********************************************************/
/* Warning: Editing this file will have a serious        */
/* effect on how this site will function, it is          */
/* highly recommended that only invasion employees with  */
/* appropriate experience make changes to this file      */
/*********************************************************/

// SOCKET INFORMATION

$socket_version = 2.0;
$socket_version_name = 'Galactica';

// Make a connection to SOCKET Prime - Do not edit this docuement or remove this line.
require_once($_SERVER['DOCUMENT_ROOT'].'/socket/gconnect.php');

// A few global variables.
$socketroot = $siteroot .'/socket';
$serverroot = substr_replace($_SERVER['DOCUMENT_ROOT'] ,"",-1);

// If you have a development site located on another domain (or subdomain) enter it below.
$devSiteURL = 'www.digitalfusiondev.me';
if ($_SERVER['SERVER_NAME'] == $devSiteURL) { // Site is on development server
	$devMode = 1;
	$mainURL = 'http://'.$_SERVER['SERVER_NAME'];
	$hostname_conn = "localhost";
	$database_conn = "web132-socket";
	$username_conn = "web132-socket";
	$password_conn = "jkkd02kod9polma-dsgf_9i23";
} else { // Site is on live server
	$devMode = 0;
	//If your site uses multiple live domains, specify the primary one here, if not then leave it blank
	$mainURL = 'http://www.digitalfusionmag.com';
	$hostname_conn = "localhost";
	$database_conn = "web126-socket";
	$username_conn = "web126-socket";
	$password_conn = "jkkd02kod9polma-dsgf_9i23";
}

// Do not edit.
if ($mainURL) {
	$siteroot = $mainURL;
} else {
	$siteroot = 'http://'.$_SERVER['SERVER_NAME'];
}

//CONNECTION STRING - DO NOT MODIFY //
$conn = mysql_connect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_conn) or die('I cannot select the database because:'.mysql_error());

// Brings in the settings from the config database
$configLookup= "SELECT server_hash, siteadmin, latLang, site_status FROM core_config";
$configData = mysql_query($configLookup) or die('Failed to return data: ' . mysql_error());
list($server_hash, $socketadmin, $latLang, $site_status_code) = mysql_fetch_array($configData, MYSQL_NUM);

$site_status = $site_status_code;
$real_site_status = $site_status_code; //Logged in users always get a live site status, this displays the real site status even when logged in

// Brings in the remote data from the global database
if($setSocket) {
if (!$noConnection) {
$globalLookup= "SELECT customerID, site_hash, subscription_tier, sub_due_date FROM core_customers WHERE site_hash='$server_hash'";
$globalData = mysql_query($globalLookup, $globalconn) or die('Failed to return data: ' . mysql_error());
list($customerID, $site_hash, $subscription_tier, $sub_due_date) = mysql_fetch_array($globalData, MYSQL_NUM);


// Resolves tier ID into friendly name
$tierLookup= "SELECT tierID, tier_name, subscription_price FROM core_tier_levels WHERE tierID =" . $subscription_tier;
$tierData = mysql_query($tierLookup, $globalconn) or die('Failed to return data: ' . mysql_error());
list($tierID, $tier_name, $subscription_price) = mysql_fetch_array($tierData, MYSQL_NUM);

$sub_package = $tier_name;
} else { $socketErr = 'SOCKET error: Please try again later. Apologies for any inconvenience'; }
}
// Brings in the settings from the company database
$contactLookup= "SELECT * FROM core_contacts WHERE siteID =1";
$contactData = mysql_query($contactLookup, $conn) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($db_siteid, $db_site_name, $db_catchline, $db_site_description, $db_site_manager, $db_address1, $db_address2, $db_town, $db_county, $db_postcode, $db_country, $db_tel, $db_mob, $db_email, $db_twitter, $db_twitter_pw, $db_skype, $db_messenger, $db_response) = mysql_fetch_array($contactData, MYSQL_NUM))
{
$sc_sitename = $db_site_name;
$sc_meta_title = $db_catchline;
$sc_meta_desc = $db_site_description;
$sc_sitemanager = $db_site_manager;
$sc_address_line1 = $db_address1;
$sc_address_line2 = $db_address2;
$sc_town_name = $db_town;
$sc_county = $db_county;
$sc_postcode = $db_postcode;
$sc_country = $db_country;
$sc_telephone = $db_tel;
$sc_mobile = $db_mob;
$sc_email = $db_email;
$sc_twitter = $db_twitter;
$sc_twitter_pw = $db_twitter_pw;
$sc_skypeID = $db_skype;
$sc_messenger = $db_messenger;

switch ($db_messenger) {
	case strpos($db_messenger, "yahoo"): 
		$sc_messenger_type = "Yahoo Messenger";
	break;
	case strpos($db_messenger, "hotmail") || strpos($db_messenger, "msn"): 
		$sc_messenger_type = "MSN Messenger";
	break;
	case strpos($db_messenger, "google") || strpos($db_messenger, "gmail"): 
		$sc_messenger_type = "Google Talk";
	break;
	case strpos($db_messenger, "aol") || strpos($db_messenger, "america") : 
		$sc_messenger_type = "AOL Messenger";
	break;
	default: 
		$sc_messenger_type = "IM";
	break;
}


switch ($db_response) {
	case 1: 
		$response_result = "the same day";
	break;
	case 2: 
		$response_result = "24 hours";
	break;
	case 3: 
		$response_result = "48 hours";
	break;
	case 4: 
		$response_result = "one week";
	break;
	case 5: 
		$response_result = "the month";
	break;
}
$response_time = $response_result;

}

// Pulls in the information required for SEO from the database
$seoLookup= "SELECT default_keywords FROM core_seo";
$seoData = mysql_query($seoLookup) or die('Failed to return data: ' . mysql_error());
list($default_keywords) = mysql_fetch_array($seoData, MYSQL_NUM);

//Performs the functions required for Facebook Connect
if ($devMode != 1) {
include_once $_SERVER['DOCUMENT_ROOT'] . 'Scripts/facebook-platform/footprints/config.php';
} else {
include_once $_SERVER['DOCUMENT_ROOT'] . 'Scripts/facebook-platform/footprints/config-dev.php';
}
include_once $_SERVER['DOCUMENT_ROOT'] . 'Scripts/facebook-platform/php/facebook.php';
global $api_key,$secret;
$fb=new Facebook($api_key,$secret);
$fb_user=$fb->get_loggedin_user();

/*********************************************************/
/*                       MODULES                         */
/*********************************************************/

// IMPORTANT: Do not include any modules that have not been installed. This will cause the site to break.

// Poll Module
require_once($serverroot.'/socket/modules/poll/module_config.php');
// Media Module
require_once($serverroot.'/socket/modules/media/module_config.php');
// Blog Module
require_once($serverroot.'/socket/modules/blog/module_config.php');
// Google adsense Module
require_once($serverroot.'/socket/modules/adsense/module_config.php');

/*********************************************************/
/*                      FUNCTIONS                        */
/*********************************************************/

// Page redirection
function redirect_to($url) {
	header("Location: {$url}");
	exit;
}

// Adds ordinals to the end of dates
function addOrdinal($num=0){
	return $num.(((strlen($num)>1)&&(substr($num,-2,1)=='1'))?'th':date("S",mktime(0,0,0,0,substr($num,-1),0)));
	}	
	
//Converts url into is.gd url
//gets the data from a URL  
function get_isgd_url($url)  
{  
	//get content
	$ch = curl_init();  
	$timeout = 5;  
	curl_setopt($ch,CURLOPT_URL,'http://is.gd/api.php?longurl='.$url);  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	$content = curl_exec($ch);  
	curl_close($ch);
	
	//return the data
	return $content;  
}


//Function to turn a mysql datetime (YYYY-MM-DD HH:MM:SS) into a unix timestamp

function convert_datetime($str) {

list($date, $time) = explode(' ', $str);
list($year, $month, $day) = explode('-', $date);
list($hour, $minute, $second) = explode(':', $time);
$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
//return the data
return $timestamp;
}

// Turns access level number into friendly name
function friendly_accesslvl($lvl) {
	$lvlLookup= "SELECT level_name FROM core_access_levels WHERE access_level =" . $lvl;
	$lvlData = mysql_query($lvlLookup) or die('Failed to return data: ' . mysql_error());
	list($level_name) = mysql_fetch_array($lvlData, MYSQL_NUM);
//return the data
return $level_name;
}

// Check if number is odd or even
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
?>