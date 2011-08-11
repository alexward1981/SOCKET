<?php 

//tells the menu which module this is
$current_module = 1000;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM core_modules WHERE moduleID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(moduleID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'modules.php?delete=' + moduleID;
   }
}
</script>

<h1> Module Browser </h1>
<?php
if ($_GET['message']) { echo $_GET['message']; }
$dblookup = "SELECT moduleID, module_name FROM core_modules ORDER BY moduleID";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
		while($result = @mysql_fetch_array($data))
			{ 
		
echo '	<tr>';
echo '    <td>' . $result["module_name"]. '</td>';
echo '    <td class="buttons" width="32" align="center">';
echo '      <a href="module_menus.php?ID='. $result["moduleID"].'"><img src="' . $siteroot . '/socket/elements/buttons/button_view_menus.png" width="32" height="32" alt="Edit Page" /></a></td>';

echo '    <td class="buttons" width="32" align="center">';
echo '      <a href="module_edit.php?ID='. $result["moduleID"].'"><img src="' . $siteroot . '/socket/elements/buttons/button_edit.png" width="32" height="32" alt="Edit Page" /></a></td>';
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
