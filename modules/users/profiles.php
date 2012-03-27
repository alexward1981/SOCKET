<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table
?>
<?php
// Selects the user and extracts the variables
$dblookup = "SELECT * FROM core_users WHERE userID='" . $_GET['uid']."' OR usr_username = '".$_GET['username']."'";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
$dataArray = mysql_fetch_array($data, MYSQL_BOTH);
extract($dataArray, EXTR_PREFIX_ALL, "dbu");
	$meta_title = $dbu_usr_username;
	$meta_keywords = "$meta_key";
	$meta_description = $sitename . 'Profile for' . $dbu_usr_username;
//If the previous level is not the home page, specify it here for it to display in the breadcrumbs	
$special_crumb = '<a href="'. SITEROOT . '/modules/users/users.php">'.users.'</a>';
// imports header information
require_once('' . SERVERROOT . '/assets/style/standard/head.php');
require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
require_once('' . SERVERROOT . '/assets/style/standard/header.php');
// Outputs users real name if available and the username if not.
if ($dbu_usr_firstname && $dbu_usr_surname) { $usr_realname = $dbu_usr_firstname .' '. $dbu_usr_surname; } else { $usr_realname = $dbu_usr_username; }
/*HTML starts here */
if ($dbu_usr_avatar) {
$userAvatar = '<img class="userAvatar" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.$dbu_usr_avatar.'&amp;w=90&amp;h=90&amp;zc=c" title="'.$usr_realname.'" alt="'.$usr_realname.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="userAvatar" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.SERVERROOT.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=90&amp;h=90&amp;zc=c" title="User does not have a profile picture"/>';	
}
switch ($dbu_usr_access_lvl) {
	case 0: 
	case 1: 
	case 2: 
		$usr_is = 'Site Manager';
		$al_pic = '5';
		break;
	case 3: 
		$usr_is = 'Site Moderator';
		$al_pic = '4';
		break;
	case 4: 
		$usr_is = 'Contributor';
		$al_pic = '3';
		break;
	case 5: 
		$usr_is = 'Regular Commenter';
		$al_pic = '2';
		break;
	case 6: 
		$usr_is = 'Standard User';
		$al_pic = '1';
		break;
}

//Retrieves all posts created by the user
$articleLimit = 5;
$userPosts = mysql_query("SELECT articleID, articleTitle, permaLink, articleSummary, articleCat FROM module_blog WHERE articleAuthor =" . $dbu_userID." AND  articlePosted =1 ORDER BY articleID DESC LIMIT ".$articleLimit);
$userPostCount = mysql_query("SELECT articleID, articleTitle, permaLink, articleSummary, articleCat FROM module_blog WHERE articleAuthor =" . $dbu_userID." AND  articlePosted =1");
$postCount = mysql_num_rows($userPostCount);
//Retrieves all comments created by the user
$commentLimit = 10;
$userComments = mysql_query("SELECT articleID, commentDetail FROM module_blog_comments WHERE modRequired = 0 AND userID =" . $dbu_userID." ORDER BY commentID DESC LIMIT ".$commentLimit);
$userCommentCount = mysql_query("SELECT articleID, commentDetail FROM module_blog_comments WHERE modRequired = 0 AND userID =" . $dbu_userID);
$commentCount = mysql_num_rows($userCommentCount);

// Gets all available public contact details for user and displays them on the site
$contact_buttons = '<div class="contact_buttons">';
//Email
if ($dbu_usr_email && $dbu_usr_email_private != 1) { $contact_buttons .= '<a href="mailto:'.$dbu_usr_email.'"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_email.png" alt="Email '.$usr_realname.'" title="Email '. $usr_realname.'" /></a>'; }
//Twitter
if ($dbu_usr_twitter) { $contact_buttons .= '<a href="http://www.twitter.com/'.$dbu_usr_twitter.'"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_twitter.png" alt="'.$usr_realname.'\'s Twitter page" title="'. $usr_realname.'\'s Twitter page" /></a>'; }
//Facebook
if ($dbu_usr_facebook) { $contact_buttons .= '<a href="http://www.facebook.com/'.$dbu_usr_facebook.'"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_facebook.png" alt="'.$usr_realname.'\'s Facebook Profile" title="'. $usr_realname.'\'s Facebook Profile" /></a>'; }
//LinkedIn
if ($dbu_usr_linkedin) { $contact_buttons .= '<a href="http://www.linkedin.com/in/'.$dbu_usr_linkedin.'"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_linkedin.png" alt="'.$usr_realname.'\'s LinkedIn Profile" title="'. $usr_realname.'\'s LinkedIn Profile" /></a>'; }
//RSS
if ($postCount) { $contact_buttons .= '<a href="'.SITEROOT.'/rss-'.$dbu_usr_username.'.php"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_rss.png" alt="Add '.$usr_realname.'\'s posts to your RSS feed" title="Add '.$usr_realname.'\'s posts to your RSS feed" /></a>'; }
// Settings
if ($dbu_userID == $_SESSION['userID']) { $contact_buttons .= '<a href="'.SITEROOT.'/modules/users/editprofile.php"> <img src="'.SITEROOT.'/modules/users/assets/images/btn_editprofile.png" alt="Modify your account settings" title="Modify your account settings" /></a>'; }


//Close the contact buttons div
$contact_buttons .= '</div>';
?>


<div class="profileHeader">
<div class="top_right_box"><strong>Posts: </strong><?php echo $postCount; ?><br /><strong>Comments: </strong><?php echo $commentCount; ?><br />
<img class="margintop5px" src="<?php echo SITEROOT .'/assets/images/stars_'.$al_pic.'.png'; ?>" alt="<?php echo $usr_is; ?>" title="<?php echo $usr_is; ?>" /></div><?php echo $userAvatar; ?><h1><?php echo $usr_realname ?></h1>
<p><?php echo '<strong>Username: </strong>' . $dbu_usr_username; ?><br />
<?php 
if ($dbu_usr_city || $dbu_usr_country) {
echo '<strong>Location: </strong>';
if ($dbu_usr_city) { 
echo $dbu_usr_city;
}
if ($dbu_usr_city && $dbu_usr_country) { echo ', '; }
echo $dbu_usr_country; 
}
?></p>
<div class="articleInfo"><?php echo $contact_buttons; ?></div>
</div>
<?php if ($dbu_usr_biography) { ?>
<div class="articleDetail">
<?php echo $dbu_usr_biography ?>
</div>
<?php } if ($postCount) { ?>
<div class="articleSubHeader">
<h2> Last <?php echo $articleLimit; ?> posts by <?php echo $usr_realname; ?> </h2>
<?php while(list($articleID, $articleTitle, $permaLink, $articleSummary, $articleCat) = mysql_fetch_array($userPosts, MYSQL_NUM)) {
	$catlookup = "SELECT * FROM module_blog_categories WHERE (categoryID = '".$articleCat."' OR categoryName = '".$articleCat."')";
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
echo '<div class="articleCompact">';
echo '<span class="embiggen_box"><a class="embiggen" href="'.SITEROOT.'/blog/'.strtolower($dbcat_categoryName).'/'.$permaLink.'">' . urldecode($articleTitle) .'</a></span>';
$numwords = 80; 
preg_match("/([\S]+\s*){0,$numwords}/", $articleSummary, $regs); 
$aricleShort = trim($regs[0]); 
echo '<span>'.strip_tags($aricleShort).'...</span>';
echo '  ( <a href="'.SITEROOT.'/blog/'.strtolower($dbcat_categoryName).'/'.$permaLink.'">';
echo 'View full article';
echo '</a> )</div>';
}?>
</div><!-- Close articleHeader -->
<?php } ?>
<div class="articleSubHeader">
<h2> Last <?php echo $commentLimit; ?> comments by <?php echo $usr_realname; ?> </h2>
<?php
while(list($articleCommentID, $commentDetail) = mysql_fetch_array($userComments, MYSQL_NUM)) {
$getTitle = mysql_query("SELECT articleTitle, permaLink, articleCat FROM module_blog WHERE articleID = ".$articleCommentID." LIMIT 20");
$getTitleArray = mysql_fetch_array($getTitle, MYSQL_BOTH);
extract($getTitleArray, EXTR_PREFIX_ALL, "p");
	$catlookup = "SELECT * FROM module_blog_categories WHERE (categoryID = '".$p_articleCat."' OR categoryName = '".$p_articleCat."')";
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "pcat");
echo '<div class="articleCompact">';
echo '<span class="embiggen_box"><strong>On article:</strong> <a class="embiggen" href="'.SITEROOT.'/blog/'.strtolower($pcat_categoryName).'/'.$p_permaLink.'">' . urldecode($p_articleTitle) .'</a></span>';
$numwords = 40; 
preg_match("/([\S]+\s*){0,$numwords}/", $commentDetail, $regs); 
$commentShort = trim($regs[0]); 
echo '<span>'.strip_tags($commentShort).'...</span>';
echo '  ( <a href="'.SITEROOT.'/blog/comments/'.strtolower($pcat_categoryName).'/'.$p_permaLink.'">';
echo 'View full comment';
echo '</a> )</div>';
} 
if (!$commentCount) { echo '<div class="articleCompact"><span>User has not made any comments</span></div>'; }
?>
</div><!-- Close articleSubHeader -->

<?php
//Main content ends here
require_once('' . SERVERROOT . '/assets/style/standard/footer.php');
?>