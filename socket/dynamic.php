<?php require_once('templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM core_dynamic WHERE dynamicID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(dynamicID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'dynamic.php?delete=' + dynamicID;
   }
}
</script>
<h1> Page Browswer </h1>
<p>From here you can view, edit and delete all dynamic content on your website </p>
<br />

<?php

$dblookup = "SELECT pageID, userID, dynamicID, articleTitle, pageName FROM core_dynamic ORDER BY pageID";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
	echo '<tr class="headerrow" align="center" >';
   	echo '<td><strong>Pages</strong></td>';
    echo '<td colspan="3"><strong>Administer</strong></td>';
  	echo '</tr>';
	
		while($result = @mysql_fetch_array($data))
	
		{ 
		
echo '	<tr>';
echo '    <td>' . $result["pageName"]. '</td>';
echo '    <td width="28"  align="center">';
echo '    <a href="dynamic_view.php?ID='. $result["dynamicID"].'"><img src="' . $siteroot . '/socket/elements/buttons/button_open.png" width="28" height="28" alt="Open Page" /></a></td>';
echo '    <td width="28" align="center">';
echo '      <a href="dynamic_edit.php?ID='. $result["dynamicID"].'"><img src="' . $siteroot . '/socket/elements/buttons/button_edit.png" width="28" height="28" alt="Edit Page" /></a></td>';
// Don't delete physical page content
if ($_SESSION['usr_access_lvl'] < 3)
	{
	echo '<td width="28" align="center">';
	echo '<a href="javascript:deleteContent(\''. $result["dynamicID"].'\');"><img src="' . $siteroot . '/socket/elements/buttons/button_delete.png" width="28" height="28" alt="Delete Page" /></a></td>';
} else { 
		echo '<td width="28" class="deadcol" align="center">';
		echo '<img src="' . $siteroot . '/socket/elements/buttons/off_button_delete.png" width="28" height="28" alt="Cannot Delete Page" /></td>'; }
echo '  </tr>';
		
    		};
	
		echo '</table>';
	
	} 
	 
	 	else 
	 
	 	{ 
	 // If the search found no information whatsoever then the following message is displayed to the user.
	 	echo "<h1>Search Results for: " . $_GET['search'] . "</h1><br />";
		echo "No titles found"; 
 
		} 

require_once('templates/standard/socket_footer.php'); ?>
