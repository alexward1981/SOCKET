<?php

/*********************************************************/
/* SOCKET Global configuration file				  	     */
/*********************************************************/
/* Warning: Editing this file will have a serious        */
/* effect on how this site will function, it is          */
/* highly recommended that only invasion employees with  */
/* appropriate experience make changes to this file      */
/*********************************************************/

//Change error reporting method

error_reporting(E_ALL ^ E_NOTICE); // all except notices;

// SOCKET INFORMATION
$socket_version = 2.5;
$socket_version_name = 'Galactica';

//SOCKET settings
define('SITEROOT', 'http://'.$_SERVER['SERVER_NAME']); 
define('SOCKETROOT', SITEROOT .'/socket'); 
define('SERVERROOT', $_SERVER['DOCUMENT_ROOT']);
//database connection details
define('SQL_HOST', 'localhost');
define('SQL_DB', 'web126-socket');
define('SQL_UN', 'web126-socket');
define('SQL_PW', 'jkkd02kod9polma-dsgf_9i23');
//Facebook
define('FB_APP_ID', '109444512520512');
define('FB_APP_SECRET', '07ba5ac4c6830469ae35152d53eed1dc');


//CONNECTION STRING - DO NOT MODIFY //
$conn = mysql_connect(SQL_HOST, SQL_UN, SQL_PW) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db(SQL_DB) or die('I cannot select the database because:'.mysql_error());

// Brings in the settings from the config database
$configLookup= "SELECT server_hash, siteadmin, latLang, site_status FROM core_config";
$configData = mysql_query($configLookup) or die('Failed to return data: ' . mysql_error());
list($server_hash, $socketadmin, $latLang, $site_status_code) = mysql_fetch_array($configData, MYSQL_NUM);

$site_status = $site_status_code;
$real_site_status = $site_status_code; //Logged in users always get a live site status, this displays the real site status even when logged in

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
require_once (SERVERROOT . '/assets/scripts/facebook/facebook.php');
$facebook = new Facebook(array(
    'appId'  => FB_APP_ID,
    'secret' => FB_APP_SECRET,
));
// Get User ID
$user = $facebook->getUser();

/*********************************************************/
/*                       MODULES                         */
/*********************************************************/

// IMPORTANT: Do not include any modules that have not been installed. This will cause the site to break.

// Media Module
require_once(SERVERROOT.'/socket/modules/media/module_config.php');
// Blog Module
require_once(SERVERROOT.'/socket/modules/blog/module_config.php');

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