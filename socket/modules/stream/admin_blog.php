<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM module_stream WHERE articleID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());
   $revisionerase = "DELETE FROM module_stream_revisions WHERE articleID = '{$_GET['delete']}'";
   mysql_query($revisionerase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());
}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(articleID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_stream.php?delete=' + articleID;
   }
}
</script>

<!-- javascript confirm tweet of article -->
<script language="JavaScript">
function tweetContent(permaLink)
{
   if (confirm("Are you sure you want to tweet this article?'"))
   {
      window.location.href = 'admin_stream.php?tweet=' + permaLink;
   }
}
</script>

<?php 

// Posts the article to twitter if required
if ($_GET['tweet']) {
	$getArticle = mysql_query("SELECT * FROM module_stream WHERE permaLink ='".$_GET['tweet']."'");
	$articleData = mysql_fetch_array($getArticle, MYSQL_BOTH);
	extract($articleData, EXTR_PREFIX_ALL, "tweet");
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_stream_categories WHERE categoryID =" . $tweet_articleCat;
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
	
// Takes the url of the article and converts it into a is.gd url
	$longURL = SITEROOT.'/stream/'.$dbcat_categoryName.'/'.$_GET['tweet'];
//	$shortURL =  get_isgd_url($longURL);
	$shortURL =  'http://dfmag.me/s/'.$dbcat_categoryID.'x'.$tweet_articleID;
	$status = $tweet_articleTitle.': '. $shortURL;
	$tweetUrl = 'http://www.twitter.com/statuses/update.xml';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "$tweetUrl");
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "status=$status");
	curl_setopt($curl, CURLOPT_USERPWD, "$sc_twitter:$sc_twitter_pw");
	
	$result = curl_exec($curl);
	$resultArray = curl_getinfo($curl);
	
	if ($resultArray['http_code'] == 200) {
	$message = '<div class="success"> <strong> Tweet Posted </strong> <p> Your tweet has been posted </p></div>';
	} else {
	$message =  '<div class="failure"> <strong> Tweet Failed </strong> <p> Could not post Tweet to Twitter right now. Try again later.</p></div>';
	}
	curl_close($curl);
}
?>

<h1>Article Browser </h1>
<p>From here you manage all of the articles on your site.</p>
<?php
if ($_GET['message']) { $message = $_GET['message']; }
echo $message;
$dblookup = "SELECT * FROM module_stream ORDER BY articleID DESC";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());
/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
	
		while($result = @mysql_fetch_array($data))
	
		{ 
		
echo '	<tr>';
echo '    <td class="firstCol" align="center">';
// checks the databases and returns the authors avatar if one exists.
$userlookup = "SELECT usr_firstname, usr_surname, usr_avatar FROM core_users WHERE core_users.userID =" . $result['articleAuthor'];
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
if ($db_usr_avatar) {
	echo '<img src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.$db_usr_avatar.'&amp;w=25&amp;h=25&amp;zc=c" title="Created by '.$db_usr_firstname.' '.$db_usr_surname.'" alt="'.$db_usr_username.'"/></td>';

} else
{
	echo '<img src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src=' . SOCKETROOT . '/modules/users/avatars/no_avatar.jpg&amp;w=20&amp;h=20&amp;zc=c" title="Created by '.$db_usr_firstname.' '.$db_usr_surname.'" alt="'.$db_usr_username.'"/></td>';
}
if ($result['articlePosted'] != 1) { $draft = '<span style="color:red"> [Draft]</span>'; } else { $draft = ''; }
echo '<td>&nbsp;' . html_entity_decode(stripslashes($result['articleTitle'])) . $draft .'</td>';

// VIEW ARTICLE IN LIVE SITE
echo '<td class="buttonCol">';
echo '<a href="'.SITEROOT.'/modules/stream/article.php?article='. $result['permaLink'].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_open.png"  title="View article on live site" /></a></td>';

// (RE)TWEET ARTICLE
if ($result['articlePosted'] == 1) {
echo '<td class="buttonCol2">';
echo '<a href="javascript:tweetContent(\''. $result['permaLink'].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_tweet.png"  title="Submit this article to Twitter" /></a></td>';
} else {
	echo '<td class="deadCol">';
echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/off_button_tweet.png"  title="You cannot tweet a draft article" /></td>';
}
// SHOW COMMENTS
echo '<td ';
$modlookup = "SELECT modRequired FROM module_stream_comments WHERE articleID = " . $result['articleID'];
$moddata = mysql_query($modlookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());
while(list($modRequired) = mysql_fetch_array($moddata, MYSQL_NUM))
{
if ($modRequired == 1) { echo 'class="moderationRequired"'; // If there are comments which require attention, highlight this box
} else { echo 'class="buttonCol"';}
}
$commentlookup = "SELECT commentID, modRequired FROM module_stream_comments WHERE articleID = " . $result['articleID'];
$commentdata = mysql_query($commentlookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());
$numcomments = mysql_num_rows($commentdata); // Checks how many comments each post has and displays the number in the row
if ($numcomments == 0) { echo 'class="deadCol"';}
echo '>';
if ($numcomments == 0) {$comnum = '0'; } else if ($numcomments >= 1 && $numcomments <= 20) { $comnum = $numcomments; } else { $comnum = '20plus'; }
if ($numcomments != 0) { echo '<a href="admin_stream_comments.php?ID='. $result['articleID'].'">'; }
echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/comments/comment_'.$comnum.'.png"  title="View '.$numcomments.' Comments" />';
if ($numcomments != 0) { echo '</a>';}
echo '</td>';

// EDIT ARTICLES
echo '<td class="buttonCol2">';
echo '<a href="admin_stream_edit.php?ID='. $result['articleID'].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_edit.png"  title="Edit Article" /></a></td>';

// SHOW REVISIONS
// First check to see if there are any revisions
$revisioncheck = mysql_query("SELECT revisionID FROM module_stream_revisions WHERE articleID =".$result['articleID']);
$revisioncount = mysql_num_rows($revisioncheck); // Checks how many revisions each post has
if ($revisioncount == 0) {
echo '<td class="deadCol">';
echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/button_view_menus.png"  title="This article has no revisions" /></td>';
} else {
echo '<td class="buttonCol">';	
echo '<a href="admin_stream_revisions.php?ID='. $result['articleID'].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_view_menus.png"  title="View Revisions" /></a></td>';
}
// DELETE ARTICLES
if ($_SESSION['usr_access_lvl'] <= 2) // If user is a site admin then let them delete articles
	{
	echo '<td class="buttonCol2">';
	echo '<a href="javascript:deleteContent(\''. $result['articleID'].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_delete.png"  title="Delete Article" /></a></td>';
} else { // if they are not then display a greyed out button
		echo '<td class="deadCol" width="20" align="center">';
		echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/off_button_delete.png"  alt="Cannot Delete Page" /></td>'; }
echo '  </tr>';
};
	
		echo '</table>';
	
	} 
	 
/*	 	else 
	 
	 	{ 
	 // If the search found no information whatsoever then the following message is displayed to the user.
	 	echo "<h1>Search Results for: " . $_GET['search'] . "</h1><br />";
		echo "No articles found"; 
 
		} */

require_once('../../templates/standard/socket_footer.php'); ?>
