<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM module_stream_revisions WHERE revisionID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());
 }

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(revisionID)
{
   if (confirm("Are you sure you want to delete this revision? This is not reversable'"))
   {
      window.location.href = 'admin_stream_revisions.php?ID=<?php echo $_GET['ID'] ?>&delete=' + revisionID;
   }
}
</script>

<?php //if republish has been clicked republish the requested ID
if(isset($_GET['republish']))
{
	// Gets the current article data
	$republish = mysql_query("SELECT * FROM module_stream_revisions WHERE revisionID =".$_GET['republish']);
	$republishArticles = mysql_fetch_array($republish, MYSQL_BOTH);
	extract($republishArticles, EXTR_PREFIX_ALL, "rp");
    // Save current live article to the revisions table
		$saveArticle = mysql_query("SELECT * FROM module_stream WHERE articleID =" . $rp_articleID) or die ('Error Detected;'. mysql_error());
		$savedArticles = mysql_fetch_array($saveArticle, MYSQL_BOTH);
		extract($savedArticles, EXTR_PREFIX_ALL, "saved");
		$currentUser = $_SESSION['userID'];
		$dbinsert = mysql_query("INSERT INTO module_stream_revisions (articleID, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImageAlt, articleAuthor, articleCat, datePosted, articlePosted, revisionBy) VALUES ('$saved_articleID', '".addslashes($saved_articleTitle)."', '".addslashes($saved_permaLink)."', '".addslashes($saved_articleBody)."', '".addslashes($saved_articleSummary)."', '$saved_articleImage', '$saved_articleImageAlt', '$saved_articleAuthor', '$saved_articleCat', '$saved_datePosted', '$saved_articlePosted', '$currentUser')") or die ('insertion failed: ' . mysql_error());
// Replace existing live article with revision.
$dbupdate  = mysql_query("UPDATE module_stream SET articleTitle = '".addslashes($rp_articleTitle)."', permaLink = '".addslashes($rp_permaLink)."', articleBody = '".addslashes($rp_articleBody)."', articleSummary = '".addslashes($rp_articleSummary)."', articleCat = '$rp_articleCat' , articleImage = '$rp_articleImage' , articleImageAlt = '$rp_articleImageAlt' WHERE articleID = '$rp_articleID'") or die ('update failed: ' .mysql_error());
$message = 'Your revision has been republished';
}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function republishContent(revisionID)
{
   if (confirm("Are you sure you want to activate this revision?'"))
   {
      window.location.href = 'admin_stream_revisions.php?ID=<?php echo $_GET['ID'] ?>&republish=' + revisionID;
   }
}
</script>
<h1>Article Revisions </h1>
<p>From here you manage your article revisions, you may restore to live, edit or delete them.</p>
<?php
if ($_GET['message']) { $message = $_GET['message']; }
echo $message;
$dblookup = "SELECT * FROM module_stream_revisions WHERE articleID = ".$_GET['ID']." ORDER BY revisionID DESC";
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
echo '<td>&nbsp;' . html_entity_decode(stripslashes($result['articleTitle'])) . '</td>';

// VIEW ARTICLE IN LIVE SITE
echo '<td class="buttonCol">';
echo '<a href="'.SITEROOT.'/modules/stream/article.php?article=true&amp;revision='. $result['revisionID'].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_open.png"  title="View revision on live site (not visible to the public)" /></a></td>';

// EDIT ARTICLES
echo '<td class="buttonCol2">';
echo '<a href="javascript:republishContent(\''. $result['revisionID'].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_republish.png"  title="Make this revision active" /></a></td>';

// DELETE ARTICLES
if ($_SESSION['usr_access_lvl'] <= 2) // If user is a site admin then let them delete articles
	{
	echo '<td class="buttonCol2">';
	echo '<a href="javascript:deleteContent(\''. $result['revisionID'].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_delete.png"  title="Delete Revision" /></a></td>';
} else { // if they are not then display a greyed out button
		echo '<td class="deadcol" width="20" align="center">';
		echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/off_button_delete.png"  alt="Cannot Delete Page" /></td>'; }
echo '  </tr>';
};
	
		echo '</table>';
	
	} 
require_once('../../templates/standard/socket_footer.php'); ?>
