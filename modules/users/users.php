<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table
?>
<?php
if ($_GET['authors']) {
	$meta_title = 'Authors';
	$special_crumb = '<a href="'. $siteroot . '/modules/users/users.php">'.users.'</a>';
} else {
	$meta_title = 'Users';
}
	$meta_keywords = "$meta_key";
	$meta_description = $sc_sitename . '\'s list of users';
	// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php');
if ($_GET['authors']) {
echo '<h1>'. $sc_sitename .' Authors</h1>';
} else {
echo '<h1>All '. $sc_sitename .' Users</h1>';	
}
// Selects the user and extracts the variables
if ($_GET['authors']) {
$db2lookup = "SELECT userID, usr_firstname, usr_surname, usr_username, usr_email, usr_email_private, usr_city, usr_country, usr_twitter, usr_facebook, usr_linkedin, usr_access_lvl, usr_avatar FROM core_users WHERE usr_access_lvl <=4 ORDER BY usr_access_lvl";
} else {
$db2lookup = "SELECT userID, usr_firstname, usr_surname, usr_username, usr_email, usr_email_private, usr_city, usr_country, usr_twitter, usr_facebook, usr_linkedin, usr_access_lvl, usr_avatar FROM core_users ORDER BY usr_access_lvl";
}
$data2 = mysql_query($db2lookup) or die('Failed to return data: ' . mysql_error());
while(list($dbl_userID, $dbl_usr_firstname, $dbl_usr_surname, $dbl_usr_username,  $dbl_usr_email, $dbl_usr_email_private, $dbl_usr_city, $dbl_usr_country, $dbl_usr_twitter, $dbl_usr_facebook, $dbl_usr_linkedin, $dbl_usr_access_lvl, $dbl_usr_avatar) = mysql_fetch_array($data2, MYSQL_NUM)) {

// Outputs users real name if available and the username if not.
if ($dbl_usr_firstname && $dbl_usr_surname) { $usr_realname = $dbl_usr_firstname .' '. $dbl_usr_surname; } else { $usr_realname = $dbl_usr_username; }
/*HTML starts here */
if ($_GET['authors']) { $avatarSize = 'w=90&amp;h=90'; } else { $avatarSize = 'w=50&amp;h=50'; }
if($dbl_usr_avatar) {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$dbl_usr_avatar.'&amp;'.$avatarSize.'&amp;zc=c" title="'.$usr_realname.'" alt="'.$usr_realname.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$serverroot.'/socket/modules/users/avatars/no_avatar.jpg&amp;'.$avatarSize.'&amp;zc=c" title="User does not have a profile picture" alt="User does not have a profile picture" />';	
}
switch ($dbl_usr_access_lvl) {
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
$userPosts = mysql_query("SELECT articleID, articleTitle, articleSummary, articleCat FROM module_blog WHERE articleAuthor =" .$dbl_userID);
$postCount = mysql_num_rows($userPosts);
//Retrieves all comments created by the user
$userComments = mysql_query("SELECT articleID, commentDetail FROM module_blog_comments WHERE modRequired = 0 AND userID =" .$dbl_userID);
$commentCount = mysql_num_rows($userComments);

// Gets all available public contact details for user and displays them on the site
if ($_GET['authors']) {
$contact_buttons = '<div class="contact_buttons">';
//Email
if ($dbl_usr_email && $dbl_usr_email_private !=1) { $contact_buttons .= '<a href="mailto:'.$dbl_usr_email.'"> <img src="'.$siteroot.'/modules/users/elements/btn_email.png" alt="Email '.$usr_realname.'" title="Email '. $usr_realname.'" /></a>'; }
//Twitter
if ($dbl_usr_twitter) { $contact_buttons .= '<a href="http://www.twitter.com/'.$dbl_usr_twitter.'"> <img src="'.$siteroot.'/modules/users/elements/btn_twitter.png" alt="'.$usr_realname.'\'s Twitter page" title="'. $usr_realname.'\'s Twitter page" /></a>'; }
//Facebook
if ($dbl_usr_facebook) { $contact_buttons .= '<a href="http://www.facebook.com/'.$dbl_usr_facebook.'"> <img src="'.$siteroot.'/modules/users/elements/btn_facebook.png" alt="'.$usr_realname.'\'s Facebook Profile" title="'. $usr_realname.'\'s Facebook Profile" /></a>'; }
//LinkedIn
if ($dbl_usr_linkedin) { $contact_buttons .= '<a href="http://www.linkedin.com/in/'.$dbl_usr_linkedin.'"> <img src="'.$siteroot.'/modules/users/elements/btn_linkedin.png" alt="'.$usr_realname.'\'s LinkedIn Profile" title="'. $usr_realname.'\'s LinkedIn Profile" /></a>'; }
//RSS
if ($postCount) { $contact_buttons .= '<a href="'.$siteroot.'/rss-'.$dbl_usr_username.'.php"> <img src="'.$siteroot.'/modules/users/elements/btn_rss.png" alt="Add '.$usr_realname.'\'s posts to your RSS feed" title="Add '.$usr_realname.'\'s posts to your RSS feed" /></a>'; }



//Close the contact buttons div
$contact_buttons .= '</div>';
}

if (!$_GET['authors']) { echo '<div class="userWrapper">'; }
?>


<div class="profileHeader">
<div class="top_right_box"><strong>Posts: </strong><?php echo $postCount; ?><br /><strong>Comments: </strong><?php echo $commentCount; ?><br />
<img class="margintop5px" src="<?php echo $siteroot .'/elements/stars_'.$al_pic.'.png'; ?>" alt="<?php echo $usr_is; ?>" title="<?php echo $usr_is; ?>" /></div><a title="View <?php echo $usr_realname . '\'s profile'?>" href="<?php echo $siteroot .'/users/'.$dbl_usr_username ?>"><?php echo $userAvatar; ?></a>
<h2><a href="<?php echo $siteroot .'/users/'.$dbl_usr_username ?>"><?php echo $usr_realname ?></a></h2>
<span><?php echo '<strong>Username: </strong>' . $dbl_usr_username; ?></span><br />
<?php 
if ($dbl_usr_city || $dbl_usr_country) {
echo '<span><strong>Location: </strong>';
if ($dbl_usr_city) { 
echo $dbl_usr_city;
}
if ($dbl_usr_city && $dbl_usr_country) { echo ', '; }
echo $dbl_usr_country; 
}
?></span>
<div class="articleInfo"><?php echo $contact_buttons; ?></div>
</div>


<?php
if (!$_GET['authors']) { echo '</div>'; }
}
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>