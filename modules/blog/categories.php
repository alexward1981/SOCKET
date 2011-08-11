<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table

if ($_GET['cat']) {
	$catlookup = "SELECT * FROM module_blog_categories WHERE (categoryID = '".$_GET['cat']."' OR categoryName = '".$_GET['cat']."')";
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
$module_ID = 2;
	$meta_title = "$dbcat_categoryName";
	$meta_keywords = "$meta_key";
	$meta_description = "$dbcat_categoryDesc";
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php');
// Category specific RSS feed
echo '<a href="'.$siteroot.'/'.strtolower($dbcat_categoryName).'-rss.php" title="'.$dbcat_categoryName.' RSS Feed alt="RSS Logo""><img class="float_right margintop10px" src="'.$siteroot.'/elements/rss_16.png" /></a>';
echo '<h1>'.$dbcat_categoryName.' Articles</h1>';
$blogCounter = mysql_query("SELECT COUNT(*) AS total_entries FROM module_blog WHERE(articleCat =". $dbcat_categoryID ." AND articlePosted =1)") or die(mysql_error()); $row = mysql_fetch_row($blogCounter); $total_entries = $row[0];
if(isset($_GET['page_number'])) { $page_number = $_GET['page_number']; } else { $page_number = 1; } 
if ($_SESSION['blog_ppp']) { $posts_per_page = $_SESSION['blog_ppp']; } else if($mcblog_postsPerPage){ $posts_per_page = $mcblog_postsPerPage;} else {$posts_per_page = 10; }
$total_pages = ceil($total_entries / $posts_per_page);
$offset = ($page_number - 1) * $posts_per_page;
$dblookup = "SELECT articleID, articleTitle, permaLink, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, datePosted FROM module_blog WHERE(articleCat =". $dbcat_categoryID ." AND articlePosted =1) ORDER BY articleID DESC LIMIT $offset, $posts_per_page";
} else {
	require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php');
$dblookup = "SELECT articleID, articleTitle, articleSummary,  articleImage, articleImagePos, articleImageAlt, articleAuthor, datePosted FROM module_blog WHERE articlePosted =1";	
}
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($articleID, $articleTitle, $permaLink, $articleSummary, $articleImage, $articleImagePos, $articleImageAlt, $articleAuthor, $datePosted) = mysql_fetch_array($data, MYSQL_NUM))
{
// checks the databases and returns the authors avatar if one exists.
$userlookup = "SELECT usr_firstname, usr_surname, usr_username, usr_avatar FROM core_users WHERE core_users.userID =" . $articleAuthor;
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
$postTimestamp = convert_datetime($datePosted);
$cleanDatePosted = date( 'l, j M Y', $postTimestamp);

if ($db_usr_avatar){
$userAvatar = '<img class="previewuserAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$db_usr_avatar.'&amp;w=90&amp;h=90&amp;zc=c" alt="'.$db_usr_firstname.' '.$db_usr_surname.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="previewuserAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$serverroot.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=90&amp;h=90&amp;zc=c" title="User does not have a profile picture"/>';	
}

if ($articleImage) {
if (!$articleImagePos) { $articleImagePos = 'C'; }
$articleThumb = '<img class="previewuserAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$articleImage.'&amp;w=90&amp;h=90&amp;zc='.$articleImagePos.'" title="'.urldecode($articleTitle).'" alt="'.articleImageAlt.'"/>';
} else {
$articleThumb = $userAvatar;
}
$authorLink = $siteroot.'/users/'.$db_usr_username;
echo '<div class="fpSeperator">';
echo '<div class="previewHeader"><a href="'.$siteroot.'/blog/'.strtolower($dbcat_categoryName).'/'.$permaLink.'">' . $articleThumb . '</a><h2><a href="'.$siteroot.'/blog/'.strtolower($dbcat_categoryName).'/'.$permaLink.'">' . html_entity_decode(stripslashes($articleTitle)) . '</a></h2></div>';
echo '<div class="previewSummary">' . stripslashes($articleSummary).'</div>';
echo '<p>By <a href="'.$authorLink.'">'.$db_usr_firstname.' '.$db_usr_surname.'</a> | '.$cleanDatePosted.'</p>';
echo '</div>';
};
// Display Pagination
if ($total_pages != 1) { // Only display pagination if there is more than one page
echo '<div id="pagination_title">Pages:</div><ul id="pagination_box">';
for($i = 1; $i <= $total_pages; $i++) { if($i == $page_number) { // This is the current page. Don't make it a link. 
echo '<li class="pagination_null">'.$i.'</li>'; } else { // This is not the current page. Make it a link. 
echo '<li><a class="pagination" href="'.$_SERVER['SCRIPT_NAME'].'?page_number='.$i.'">'.$i.'</a></li> '; } }
}
echo '</ul>'; // end pagination
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>