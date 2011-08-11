<?php 

//tells the menu which module this is
$current_module = 10;

require_once('../../templates/standard/socket_header.php');

//if delete has been clicked delete the requested ID
if(isset($_GET['delete']))
{
   $dberase = "DELETE FROM core_media WHERE fileID = '{$_GET['delete']}'";
   mysql_query($dberase) or die('<h3 class="error"> Deletion Failed! </h3>' . mysql_error());

}

?>
<!-- javascript confirm deletion of article -->
<script language="JavaScript">
function deleteContent(fileID)
{
   if (confirm("Are you sure you want to delete this Content?'"))
   {
      window.location.href = 'admin_media.php?delete=' + fileID;
   }
}
</script>
<h1> Media Browser </h1>
<p>From here you can view, edit and delete files from your site </p>

<p> <strong> Why not upgrade to Media Bolt Premium? </strong> Its only an additional &pound;10 a month and it allows users to store documents (Text, audio, Pictures and video) on their website (for file sizes upto 100mb each) These files will then be accessible to the public and usable from the website, includes a file-browser to allow users to browse for files to add to the site instead of having to insert via a URL.  <a href="http://www.invasionmedia.co.uk/boltcatalogue">Bolt Catalogue</a></p>

<?php

$dblookup = "SELECT fileID, userID, fileTitle, fileDescription, fileURL FROM core_media ORDER BY fileID";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
	echo '<tr class="headerrow" align="center" >';
   	echo '<td><strong>Files</strong></td>';
    echo '<td colspan="2"><strong>Administer</strong></td>';
  	echo '</tr>';
	
		while($result = @mysql_fetch_array($data))
	
		{ 
		
echo '	<tr>';
echo '    <td>' . $result["fileTitle"]. '</td>';
echo '    <td class="buttons" width="32"  align="center">';
echo '    <a href="admin_media_view.php?ID='. $result["fileID"].'"><img src="' . $siteroot . '/socket/elements/buttons/button_open.png" width="32" height="32" alt="Open Page" /></a></td>';
// Admin switch yet to add
//if ($result["dynamicID"] > $static_pages)
	//{
	echo '<td class="buttons" width="32" align="center">';
	echo '<a href="javascript:deleteContent(\''. $result["fileID"].'\');"><img src="' . $siteroot . '/socket/elements/buttons/button_delete.png" width="32" height="32" alt="Delete Page" /></a></td>';
/*} else { 
		echo '<td width="28" class="deadcol" align="center">';
		echo '<img src="' . $siteroot . '/socket/elements/buttons/off_button_delete.png" width="32" height="32" alt="Cannot Delete Page" /></td>'; }*/
echo '  </tr>';
		
    		};
	
		echo '</table>';
	
	} 
	 
/*	 	else 
	 
	 	{ 
	 // If the search found no information whatsoever then the following message is displayed to the user.
	 	echo "<h1>Search Results for: " . $_GET['search'] . "</h1><br />";
		echo "No files found"; 
 
		} */

require_once('../../templates/standard/socket_footer.php'); ?>
