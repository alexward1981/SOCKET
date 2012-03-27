<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); 

	$titlelookup = "SELECT articleID, articleTitle FROM module_stream WHERE articleID=" . $_GET['ID'];
  	$title_fetch = mysql_query($titlelookup) or die('Failed to return data: ' . mysql_error());
  	while($option = mysql_fetch_array($title_fetch)) { $articleID = $option[articleID]; $articleTitle = $option['articleTitle']; } 
?>

<h1>Reader Comments</h1>
<h2>Viewing comments for:</h2> <p><strong><?php echo urldecode($articleTitle);?></strong></p>
<?php
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM module_stream_comments WHERE commentID =" . $_GET['delete'];
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}
?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(commentID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_stream_comments.php?ID=<?php echo $articleID ?>&delete=' + commentID;
   }
}
</script>
<?php

if(isset($_GET['approve']))
{
   $dbapprove = "UPDATE module_stream_comments SET modRequired = 0 WHERE commentID =" . $_GET['approve'];
   mysql_query($dbapprove) or die('<h3 class="error"> Approval Failed! </h3>' . mysql_error());

}
?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function approveContent(commentID)
{
   if (confirm("Are you sure you want to approve this Content?'"))
   {
      window.location.href = 'admin_stream_comments.php?ID=<?php echo $articleID ?>&approve=' + commentID;
   }
}
</script>
<?php

if(isset($_GET['decline']))
{
   $dbblacklist = "UPDATE module_stream_comments SET modRequired = 2 WHERE commentID =" . $_GET['decline'];
   mysql_query($dbblacklist) or die('<h3 class="error"> Decline Failed! </h3>' . mysql_error());
}
?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function declineContent(commentID)
{
   if (confirm("Are you sure you want to add this comment to the blacklist?"))
   {
      window.location.href = 'admin_stream_comments.php?ID=<?php echo $articleID ?>&decline=' + commentID;
   }
}
</script>
<?php
echo '<h3> Approved Comments </h3>';
echo '<table class="stripeMe" width="100%">';
	echo '<tr>';
// Selects the title and description fields from the contents table
$dblookup = "SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE modRequired =0 AND articleID=" . $_GET['ID'];

$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($commentID, $articleID, $db_userID, $commentDetail, $timeStamp, $modRequired) = mysql_fetch_array($data, MYSQL_NUM))
{
/*HTML starts here */
?>
<tr>
<?php 
	//begin Fetch user script
	$ulookup = "SELECT usr_username FROM core_users WHERE userID =" . $db_userID;
	if ($globalAuthor) {
	$user_fetch = mysql_query($ulookup, $globalconn) or die('<h3 style="color:red"> Global Retrieval Failed! </h3>' . mysql_error());
	} else {
	$user_fetch = mysql_query($ulookup) or die('Failed to return data: ' . mysql_error());
	}
  	while($option = mysql_fetch_array($user_fetch)) { $postedby = $option['usr_username']; } 
	//end Fetch user script
?>
	<td> <strong>Comment By: </strong> <?php echo $postedby ?> <strong>at:</strong> <?php echo $timeStamp ?></td>
        <td width="16"><a href="javascript:declineContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_no.png" width="16" height="16" alt="Decline Comment" title="Decline Comment" /></a></td>
    <td width="16"><a href="javascript:deleteContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_delete.png" width="16" height="16" alt="Delete Comment" title="Delete Comment" /></a></td>
    
  </tr>
  <tr>
    <td colspan=" 3"><?php echo $commentDetail ?></td>
</tr>
<?PHP
}} else { echo '<td> No comments have been posted so far </td>';}
echo '</tr></table>';

// Returns the moderation queue if it exists
$bllookup = "SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE modRequired =1 AND articleID=" . $_GET['ID'];
$bldata = mysql_query($bllookup) or die('Failed to return data: ' . mysql_error());
$blsearchrows = mysql_num_rows($bldata);

	if($blsearchrows != 0)
 
	{
		echo '<h3>Flagged Comments</h3>';
		echo '<table class="stripeErr" width="100%">';
	echo '<tr>';
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($commentID, $articleID, $db_userID, $commentDetail, $timeStamp, $modRequired) = mysql_fetch_array($bldata, MYSQL_NUM))
{
?>
<tr>
<?php 
	//begin Fetch user script
	$ulookup = "SELECT usr_username FROM core_users WHERE userID =" . $db_userID;
	if ($globalAuthor) {
	$user_fetch = mysql_query($ulookup, $globalconn) or die('<h3 style="color:red"> Global Retrieval Failed! </h3>' . mysql_error());
	} else {
	$user_fetch = mysql_query($ulookup) or die('Failed to return data: ' . mysql_error());
	}
  	while($option = mysql_fetch_array($user_fetch)) { $postedby = $option['usr_username']; } 
	//end Fetch user script
?>
	<td> <strong>Comment By: </strong> <?php echo $postedby ?> <strong>at:</strong> <?php echo $timeStamp ?></td>
    <td width="16"><a href="javascript:approveContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_yes.png" width="16" height="16" alt="Approve Comment" title="Approve Comment" /></a></td>
            <td width="16"><a href="javascript:declineContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_no.png" width="16" height="16" alt="Decline Comment" title="Decline Comment" /></a></td>
  <td width="16"><a href="javascript:deleteContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_delete.png" width="16" height="16" alt="Delete Comment" title="Delete Comment" /></a></td>
    
  </tr>
  <tr>
    <td colspan="4"><?php echo $commentDetail ?></td>
</tr>	
<?php
}}
echo '</tr></table>';


// Returns any blacklisted comments if they exist.
$bllookup = "SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE modRequired =2 AND articleID=" . $_GET['ID'];
$bldata = mysql_query($bllookup) or die('Failed to return data: ' . mysql_error());
$blsearchrows = mysql_num_rows($bldata);

	if($blsearchrows != 0)
 
	{
		echo '<h3>Blacklisted Comments</h3>';
		echo '<table class="stripeDead" width="100%">';
	echo '<tr>';
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($commentID, $articleID, $db_userID, $commentDetail, $timeStamp, $modRequired) = mysql_fetch_array($bldata, MYSQL_NUM))
{
?>
<tr>
<?php 
	//begin Fetch user script
	$ulookup = "SELECT usr_username FROM core_users WHERE userID =" . $db_userID;
	if ($globalAuthor) {
	$user_fetch = mysql_query($ulookup, $globalconn) or die('<h3 style="color:red"> Global Retrieval Failed! </h3>' . mysql_error());
	} else {
	$user_fetch = mysql_query($ulookup) or die('Failed to return data: ' . mysql_error());
	}
  	while($option = mysql_fetch_array($user_fetch)) { $postedby = $option['usr_username']; } 
	//end Fetch user script
?>
	<td> <strong>Comment By: </strong> <?php echo $postedby ?> <strong>at:</strong> <?php echo $timeStamp ?></td>
    <td width="16"><a href="javascript:approveContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_yes.png" width="16" height="16" alt="Approve Comment" title="Approve Comment" /></a></td>
  <td width="16"><a href="javascript:deleteContent('<?php echo $commentID ?>');"><img src="<?php echo SITEROOT ?>/socket/assets/images/buttons/comments/comment_delete.png" width="16" height="16" alt="Delete Comment" title="Delete Comment" /></a></td>
    
  </tr>
  <tr>
    <td colspan=" 3"><?php echo $commentDetail ?></td>
</tr>	
<?php
}}
echo '</tr></table>';

require_once('../../templates/standard/socket_footer.php'); ?>
