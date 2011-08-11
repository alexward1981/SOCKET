<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php');

if ($_GET['article']) {
	if ($_GET['revision']) { // If viewing a revision from SOCKET
$dblookup = "SELECT articleID, datePosted, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, articleCat FROM module_stream_revisions WHERE(revisionID ='".$_GET['revision']."') LIMIT 1";		
	} else {
$dblookup = "SELECT articleID, datePosted, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, articleCat FROM module_stream WHERE(articleID ='".$_GET['article']."' OR permaLink ='".$_GET['article']."') LIMIT 1";
	}
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());	
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($articleID, $datePosted, $articleTitle, $permaLink, $articleBody, $articleSummary, $articleImage, $articleImagePos, $articleImageAlt, $articleAuthor, $articleCat) = mysql_fetch_array($data, MYSQL_BOTH))
{
$meta_title = html_entity_decode(stripslashes("$articleTitle"));
$meta_keywords = $default_keywords ;
$meta_description = "$articleSummary";
$module_ID = 2;
$mceInit = 1;	//Activates TinyMCE
// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_stream_categories WHERE categoryID =" . $articleCat;
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
if ($_GET['short'] == 1) { redirect_to($mainURL."/stream/".strtolower($dbcat_categoryName)."/".$permaLink); }
$special_crumb = '<a href="'. $siteroot . '/stream/'.$dbcat_categoryName.'">'.$dbcat_categoryName.'</a>';
// Insert the header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
// Create a hidden thumbnail of the article image for Social sharing sites
if (!$articleImagePos) { $articleImagePos = 'c'; }
//echo '<meta name="title" content="'.$articleTitle.'" />';
echo '<link rel="image_src" href="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?w=100&h=100&zc='.$articleImagePos.'&src='.$articleImage.'" />';
require_once('' . $serverroot . '/style/standard/header.php');
$postTimestamp = convert_datetime($datePosted);
$cleanDatePosted = date( 'l, j M Y', $postTimestamp);

$getComments = mysql_query("SELECT commentID FROM module_stream_comments WHERE articleID =" . $articleID);
$commentCount = mysql_num_rows($getComments);

/****************************/
/*    Page starts here 		*/
/****************************/
//echo '<img class="hidden" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$siteroot.'/Scripts/phpThumb/phpThumb.php?w=100&h=100&zc='.$articleImagePos.'&src='.$articleImage.'" />';

// checks the databases and returns the authors avatar if one exists.
$userlookup = "SELECT usr_firstname, usr_surname, usr_username, usr_avatar FROM core_users WHERE userID =" . $articleAuthor;
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$db_usr_avatar.'&amp;w=90&amp;h=90&amp;zc=c" alt="'.$db_usr_firstname.' '.$db_usr_surname.'\'s profile picture" />';
$authorLink = $siteroot.'/users/'.$db_usr_username;
echo '<div class="articleHeader"><a href="'.$authorLink.'">' . $userAvatar . '</a><h1>' . stripslashes(html_entity_decode($articleTitle)) . '</h1>';
echo '<div class="articleInfo">By <a href="'.$authorLink.'">'.$db_usr_firstname.' '.$db_usr_surname.'</a> | <a href="'. $siteroot . '/'.strtolower($dbcat_categoryName).'">'.$dbcat_categoryName.'</a> | '.$cleanDatePosted.'</div>';
echo '</div>';
?>
<div class="articleTabs noPrint">
<div id="shareBox">
<?php if ($addThisEnabled == TRUE) { // Begin Add this code ?>
<a class="addthis_button float_right" href="http://www.addthis.com/bookmark.php?v=250&amp;username=<?php echo $addThisUN ?>"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=digitalfusionmag"></script>
<?php } //end if add this ?>
<a class="DiggThisButton DiggCompact"></a>
</div>
<ul> 
<li class="selected"> Article </li> 
<?php
echo '<li><a href="'.$siteroot.'/stream/comments/'.strtolower($dbcat_categoryName).'/'.$permaLink.'"> Comments ('.$commentCount.') </a></li>';
?>
<?php if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 2) { ?>
<li><a href="<?php echo $siteroot ?>/socket/modules/stream/admin_stream_edit.php?ID=<?php echo $articleID; ?>"> Edit </a></li>
<?php } ?>
</ul>
</div>
<?php
if (!$articleImageAlt) { $articleImageAlt = urldecode($articleTitle); }
echo '<div class="articleDetail">';
if ($articleImage) {
echo '<img class="firstImage" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$articleImage.'&amp;w=621&amp;h=159" alt="'.$articleImageAlt.'" title="'.urldecode($articleTitle).'" />';
}
echo '<div class="highlight">' . stripslashes($articleSummary) .'</div>';
echo stripslashes($articleBody);

// Add poll module
require_once($serverroot.'/modules/poll/widget_article_results.php');

?>

<span class="shortLink">Like this? Share it with your friends: <input readonly="readonly" value="<?php echo 'http://dfmag.me/s/'.$dbcat_categoryID.'x'.$articleID; ?>"/> <?php if ($addThisEnabled == TRUE) { // Begin Add this code ?>
<div class="addthis_toolbox addthis_default_style noPrint">
<a class="addthis_button_twitter"></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_digg"></a>
<a class="addthis_button_print"></a>
<span class="addthis_separator">|</span>
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=digitalfusionmag" class="addthis_button_expanded">More</a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=digitalfusionmag"></script></span>
<?php if ($devMode != 1) { // Add a google adSense banner
require_once($serverroot.'/modules/adsense/widget_banner.php');

}
} // End add this code ?>

<?php
$commentForm = '
<form class="noPrint" enctype="multipart/form-data" id="commentForm" action="/stream/comments/'.strtolower($_GET['cat']).'/'.$_GET['article'].'" method="post">
    <label for="user_comment">Post a comment</label>
	'.$message.'
	<input name="articleID" type="hidden" value="'.$articleID.'" />
	<input name="commenterID" type="hidden" value="'.$_SESSION['userID'].'" />
     <textarea name="user_comment" id="user_comment" cols="75" rows="7">'.$_POST['user_comment'].'</textarea>
		<input class="submit" name="submit" type="submit" value="Post Comment" />
</form>';
echo '</div>';
if ($_SESSION['userID']) { echo $commentForm; }
	};
} else {
	// Insert the header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php');
echo '<div> <h1>Thank you for visiting '.$sc_sitename.'. </h1>  <p> It appears you have accessed this page through invalid means, <a href="'.$siteroot.'">please click here to return to the home page</p></a></div>';
}
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>