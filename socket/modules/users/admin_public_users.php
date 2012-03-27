<?php 

//tells the menu which module this is
$current_module = 9;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM core_users WHERE userID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(userID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_users.php?delete=' + userID;
   }
}
</script>

<h1> User Administrator </h1>
<p>From here you can view, edit and delete Users from your site </p>
<?php
if ($_GET['message']) { echo $_GET['message']; }

$dblookup = "SELECT * FROM core_users WHERE usr_access_lvl >= 5 ORDER BY userID DESC";
$data = mysql_query($dblookup, $conn) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{echo '<h2> All Public Users </h2>';	
	echo '<table class="stripeMe" width="100%">';
		while($result = @mysql_fetch_array($data))
	
		{ 
		
// Turns access levels into friendly names
      $accesslookup = "SELECT level_name, access_level FROM core_access_levels";
      $accessdata = mysql_query($accesslookup) or die('Failed to return data: ' . mysql_error());
      while($option = mysql_fetch_array($accessdata)) {
		if ($result['usr_access_lvl'] == $option['access_level']) { $user_type = $option['level_name'];}
	  }
		
echo '	<tr>';
echo '    <td class="buttons" width="32"  align="center">';
if ($result['usr_avatar']) {
echo '    <img src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.SERVERROOT.'/socket/modules/users/'.$result['usr_avatar'].'&amp;w=35&amp;h=35&amp;zc=c" alt="'.$result['usr_username'].'\'s profile picture"/></td>';
} else
{
	echo '    <img src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src=' . SERVERROOT . '/socket/modules/users/avatars/no_avatar.jpg&amp;w=35&amp;h=35&amp;zc=c" alt="'.$result['usr_username'].'\'s profile picture"/></td>';
}
echo '    <td> <strong>&nbsp;' . $result["usr_firstname"]. ' ' . $result["usr_surname"]. ' </strong>( '. $user_type . ' )</td>';
echo '    <td class="buttons" width="32"  align="center">';
echo '    <a href="'.SITEROOT.'/modules/users/profiles.php?uid='. $result["userID"].'"><img src="' . SOCKETROOT . '/assets/images/buttons/button_open.png" width="32" height="32" alt="View User" /></a></td>';
	if ($_SESSION['usr_access_lvl'] <= 1) {
echo '    <td class="buttons" width="32" align="center">';
echo '    <a href="admin_users_edit.php?ID='. $result["userID"].'"><img src="' . SOCKETROOT . '/assets/images/buttons/button_edit.png" width="32" height="32" alt="Edit User" /></a></td>';
} elseif ($_SESSION['usr_access_lvl'] <= 1) {
echo '    <td class="buttons" width="32" align="center">';
echo '    <a href="admin_users_edit.php?ID='. $result["userID"].'"><img src="' . SOCKETROOT . '/assets/images/buttons/button_edit.png" width="32" height="32" alt="Edit User" /></a></td>';
} else { 
		echo '<td width="28" class="deadcol" align="center">';
		echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/off_button_edit.png" width="32" height="32" alt="Cannot Delete Page" /></td>'; }
// Admin switch yet to add
	if ($_SESSION['usr_access_lvl'] <= 1 && $result["userID"] != $_SESSION['userID']) {
	echo '<td class="buttons" width="32" align="center">';
	echo '<a href="javascript:deleteContent(\''. $result["userID"].'\');"><img src="' . SOCKETROOT . '/assets/images/buttons/button_delete.png" width="32" height="32" alt="Delete User" /></a></td>';
} else { 
		echo '<td width="28" class="deadcol" align="center">';
		echo '<img src="' . SOCKETROOT . '/assets/images/buttons/off_button_delete.png" width="32" height="32" alt="Cannot Delete Page" /></td>'; }
echo '  </tr>';
		
    		};
	
		echo '</table>';
	
	};
require_once('../../templates/standard/socket_footer.php'); ?>