<?php 
// Begin pagination
$blogCounter = mysql_query("SELECT COUNT(p.articleID) AS total_entries FROM module_blog AS p LEFT JOIN module_blog_categories AS c ON c.categoryID = p.articleCat WHERE p.articlePosted =1 AND c.isPrivate != 1") or die(mysql_error()); $row = mysql_fetch_row($blogCounter); $total_entries = $row[0];
if(isset($_GET['page_number'])) { $page_number = $_GET['page_number']; } else { $page_number = 1; } 
if ($_SESSION['blog_ppp']) { $posts_per_page = $_SESSION['blog_ppp']; } else if($mcblog_postsPerPage){ $posts_per_page = $mcblog_postsPerPage;} else {$posts_per_page = 10; }
$total_pages = ceil($total_entries / $posts_per_page);
$offset = ($page_number - 1) * $posts_per_page;
$dblookup = "SELECT p.articleID, p.articleTitle, p.articleCat, p.permaLink, p.articleSummary, p.articleAuthor, p.articleImage, p.articleImagePos, p.articleImageAlt, p.datePosted FROM module_blog AS p LEFT JOIN module_blog_categories AS c ON c.categoryID = p.articleCat WHERE p.articlePosted =1 AND c.isPrivate !=1 ORDER BY p.articleID DESC LIMIT $offset, $posts_per_page";	
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
/* sorts the data into variables and puts them in an array ready to be called when needed*/
$anum = mysql_num_rows($data);
while(list($articleID, $articleTitle, $articleCat, $permaLink, $articleSummary, $articleAuthor, $articleImage, $articleImagePos, $articleImageAlt, $datePosted) = mysql_fetch_array($data, MYSQL_NUM))
{
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryID =" . $articleCat;
	$catdetails = mysql_query($catlookup) or die ('fained to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
$cnum++;
// checks the databases and returns the authors avatar if one exists.
$userlookup = "SELECT usr_firstname, usr_surname, usr_username, usr_avatar FROM core_users WHERE core_users.userID =" . $articleAuthor;
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
$postTimestamp = convert_datetime($datePosted);
$cleanDayPosted = date( 'j', $postTimestamp);
$cleanMonthPosted = date( 'M', $postTimestamp);
$cleanYearPosted = date( 'y', $postTimestamp);

if ($db_usr_avatar){
$userAvatar = '<img class="previewuserAvatar" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.$db_usr_avatar.'&amp;w=90&amp;h=90&amp;zc=c" alt="'.$db_usr_firstname.' '.$db_usr_surname.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="userAvatar" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.SERVERROOT.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=90&amp;h=90&amp;zc=c" title="User does not have a profile picture"/>';	
}
if ($articleImage) {
$articleImagePos = 1;
$articleThumb = '<img class="tn" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.$articleImage.'&amp;w=90&amp;h=90&amp;zc='.$articleImagePos.'" title="'.urldecode($articleTitle).'" alt="'.articleImageAlt.'"/>';
} else {
$articleThumb = $userAvatar;
}
$authorLink = SITEROOT.'/users/'.$db_usr_username;
?>
	<section>
		<div class="articleInfo">
			<div class="dateComments">
				<span class="d"><?php echo $cleanDayPosted; ?></span>
				<span class="my"><?php echo $cleanMonthPosted.' '.$cleanYearPosted; ?></span>
				<div class="comments">
					<a href="">0</a>
				</div>
			</div>
			<?php echo $articleThumb ?>
		</div>
		<div class="articleSummary">
			<span class="category"><?php echo $dbcat_categoryName; ?></span>
			<h1><?php echo stripslashes(html_entity_decode($articleTitle)); ?></h1>
			<?php echo stripslashes($articleSummary) ?>
			<a class="rm" href="<?php echo SITEROOT.'/blog/'.strtolower($dbcat_categoryName).'/'.$permaLink ?>">Read more</a>
		</div>
	</section>
<?php

};
// Display Pagination
if ($total_pages != 1) { // Only display pagination if there is more than one page
echo '<div id="pagination"><div id="pagination_title">Pages:</div><ul id="pagination_box">';
for($i = 1; $i <= $total_pages; $i++) { if($i == $page_number) { // This is the current page. Don't make it a link. 
echo '<li class="pagination_null">'.$i.'</li>'; } else { // This is not the current page. Make it a link. 
echo '<li><a class="pagination" href="'.$_SERVER['SCRIPT_NAME'].'?page_number='.$i.'">'.$i.'</a></li> '; } }
}
echo '</ul></div>'; // end pagination
?>