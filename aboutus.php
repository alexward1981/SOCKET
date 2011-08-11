<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); //Sets the static pageID 
$pageID = 2;
$dynamicID = $pageID;
// Selects the title and description fields from the contents table
$dblookup = "SELECT articleTitle,articleBody,meta_key,meta_desc FROM core_content WHERE(core_content.pageID = '$pageID')";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($article_title, $article_body, $meta_key, $meta_desc) = mysql_fetch_array($data, MYSQL_NUM))
{
$meta_title = "$article_title";
$meta_keywords = "$meta_key";
$meta_description = "$meta_desc";
$title = "$article_title";
$body = "$article_body";
};
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('style/standard/header.php');
//Main content starts here ?>
<?php if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 3) {echo '<span class="socket_action_button"> <a href="'.$siteroot.'/socket/pages_edit.php?ID='.$pageID.'"><img src="' . $siteroot . '/socket/elements/buttons/button_edit.png" width="15" height="15" alt="Edit Page" /></a></span>';}?>
<h1><?php echo stripslashes($title); ?></h1>
<div id="bodytext">
<?php echo stripslashes($body); ?>
  </div>
<?php 
//Main content ends here
require_once('style/standard/footer.php');
?>
