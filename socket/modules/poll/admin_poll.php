<?php 

//tells the menu which module this is
$current_module = 14;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM module_poll WHERE pollID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());
}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(pollID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_poll.php?delete=' + pollID;
   }
}
</script>

<?php 
//poll Activator
if(isset($_GET['activate']))
{
// First initialise all active polls
   $dbdeactivate = "UPDATE module_poll SET pollStatus = '0' WHERE pollStatus = '1'";
   mysql_query($dbdeactivate) or die('<h3 class="error"> Deactivation Failed! </h3>' . mysql_error());
   if ($dbdeactivate) { 
	   $dbactivate = "UPDATE module_poll SET pollStatus = '1' WHERE pollID = '{$_GET['activate']}'";
	   mysql_query($dbactivate) or die('<h3 class="error"> Activation Failed! </h3>' . mysql_error());
   }
}

?>
<!-- javascript confirm activation of poll -->
<script language="JavaScript">
function activatePoll(pollID)
{
   if (confirm("Warning: This will replace the current active Poll. Continue?'"))
   {
      window.location.href = 'admin_poll.php?activate=' + pollID;
   }
}
</script>


<h1>Poll Browser </h1>
<p>From here you manage all of the polls on your site.</p>
<?php
if ($_GET['message']) { $message = $_GET['message']; }
echo $message;
$dblookup = "SELECT * FROM module_poll ORDER BY pollID DESC";
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
$userlookup = "SELECT usr_firstname, usr_surname, usr_avatar FROM core_users WHERE core_users.userID =" . $result['userID'];
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
if ($db_usr_avatar) {
	echo '<img src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$db_usr_avatar.'&amp;w=25&amp;h=25&amp;zc=c" title="Created by '.$db_usr_firstname.' '.$db_usr_surname.'" alt="'.$db_usr_username.'"/></td>';

} else
{
	echo '<img src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src=' . $socketroot . '/modules/users/avatars/no_avatar.jpg&amp;w=20&amp;h=20&amp;zc=c" title="Created by '.$db_usr_firstname.' '.$db_usr_surname.'" alt="'.$db_usr_username.'"/></td>';
}
if ($result['pollStatus'] == 1) { $pollstatus = '<span style="color:red"> [Active]</span>'; } else { $pollstatus = ''; }
echo '<td>&nbsp;' . html_entity_decode(stripslashes($result['question'])) . $pollstatus .'</td>';
// ACTIVATE POLL
if ($_SESSION['usr_access_lvl'] <= 2) // If user is a site admin then let them activate polls
	{
		if ($result['pollStatus'] != 1) {
	echo '<td class="buttonCol2">';
	echo '<a href="javascript:activatePoll(\''. $result['pollID'].'\');"><img src="' . $siteroot . '/socket/elements/buttons/button_republish.png"  title="Activate Poll" /></a></td>';
	} else { // if the poll is already active. don't let them do it again
		echo '<td class="deadCol" width="20" align="center">';
		echo '<img src="' . $siteroot . '/socket/elements/buttons/off_button_republish.png"  title="Poll already active" /></td>'; }
} else { // if they are not then display a greyed out button
		echo '<td class="deadCol" width="20" align="center">';
		echo '<img src="' . $siteroot . '/socket/elements/buttons/off_button_republish.png"  title="Insufficient permissions" /></td>'; }
// EDIT POLL
echo '<td class="buttonCol2">';
echo '<a href="admin_poll_edit.php?ID='. $result['pollID'].'"><img src="' . $siteroot . '/socket/elements/buttons/button_edit.png"  title="Edit Poll" /></a></td>';

// DELETE POLL
if ($_SESSION['usr_access_lvl'] <= 2) // If user is a site admin then let them delete polls
	{
	echo '<td class="buttonCol2">';
	echo '<a href="javascript:deleteContent(\''. $result['pollID'].'\');"><img src="' . $siteroot . '/socket/elements/buttons/button_delete.png"  title="Delete Poll" /></a></td>';
} else { // if they are not then display a greyed out button
		echo '<td class="deadCol" width="20" align="center">';
		echo '<img src="' . $siteroot . '/socket/elements/buttons/off_button_delete.png"  alt="Cannot Delete Poll" /></td>'; }


echo '  </tr>';
};
	
		echo '</table>';
	
	}
	 
/*	 	else 
	 
	 	{ 
	 // If the search found no information whatsoever then the following message is displayed to the user.
	 	echo "<h1>Search Results for: " . $_GET['search'] . "</h1><br />";
		echo "No POLLS found"; 
 
		} */

require_once('../../templates/standard/socket_footer.php'); ?>
