<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM module_blog_categories WHERE categoryID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(categoryID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_blog_cat.php?delete=' + blogID;
   }
}
</script>
<h1>Category Browser </h1>
<p>From here you can view, edit and delete categories from your site </p>

<?php
$dblookup = "SELECT * FROM module_blog_categories ORDER BY categoryID";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());
/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
	echo '<tr class="headerrow" align="center" >';
   	echo '<td><strong>Categories</strong></td>';
    echo '<td colspan="3"><strong>Administer</strong></td>';
  	echo '</tr>';
	
		while($result = @mysql_fetch_array($data))
	
		{ 
		
echo '	<tr>';
echo '    <td>&nbsp;&nbsp;' , $result['categoryName'];
if ($result['isPrivate'] == 1) { echo ' <span style="color: blue;">[ Private ]</span>'; }
echo'	  </td>';
echo '    <td width="28" align="center">';
echo '      <a href="admin_blog_cat_edit.php?ID='. $result['categoryID'].'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_edit.png" width="32" height="32" alt="Edit Page" /></a></td>';
// Admin switch yet to add
//if ($result["dynamicID"] > $static_pages)
	//{
	echo '<td width="28" align="center">';
	echo '<a href="javascript:deleteContent(\''. $result['categoryID'].'\');"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_delete.png" width="32" height="32" alt="Delete Page" /></a></td>';
/*} else { 
		echo '<td width="28" class="deadcol" align="center">';
		echo '<img src="' . SITEROOT . '/socket/assets/images/buttons/off_button_delete.png" width="32" height="32" alt="Cannot Delete Category" /></td>'; }*/
echo '  </tr>';
		
    		};
	
		echo '</table>';
	
	} 
	 
/*	 	else 
	 
	 	{ 
	 // If the search found no information whatsoever then the following message is displayed to the user.
	 	echo "<h1>Search Results for: " . $_GET['search'] . "</h1><br />";
		echo "No categories found"; 
 
		} */

require_once('../../templates/standard/socket_footer.php'); ?>
