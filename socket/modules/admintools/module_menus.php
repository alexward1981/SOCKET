<?php 

//tells the menu which module this is
$current_module = 12;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM core_modules_menus WHERE menuID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
 
function deleteContent(menuID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'module_menus.php?ID=' + <?php echo $_GET['ID']; ?> + '&delete=' + menuID;
   }
}
</script>
<?php
if(isset($_GET['ID']))
	{
$modlookup = "SELECT folder_name FROM core_modules WHERE moduleID=" . $_GET['ID'];
$moddata = mysql_query($modlookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());
while($module = @mysql_fetch_array($moddata))
			{
echo '<h1> ' . $module["folder_name"]. ' Menu Browser </h1>';
			}

$dblookup = "SELECT menuID, menu_item_name FROM core_modules_menus WHERE moduleID=" . $_GET['ID'] ." ORDER BY menuID";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
		while($result = @mysql_fetch_array($data))
			{ 
		
echo '	<tr>';
echo '    <td>' . $result["menu_item_name"]. '</td>';
echo '    <td class="buttons" width="32" align="center">';
echo '      <a href="module_menu_edit.php?ID='. $result["menuID"].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_edit.png" width="32" height="32" alt="Edit Page" /></a></td>';
	echo '<td class="buttons" width="32" align="center">';
	echo '<a href="javascript:deleteContent(\''. $result["menuID"].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_delete.png" width="32" height="32" alt="Delete Page" /></a></td>';
echo '  </tr>';
		
    		};
	
		echo '</table>';
	
	} 
	} else { echo '<h1>No results found </h1>'; }

require_once('../../templates/standard/socket_footer.php'); ?>