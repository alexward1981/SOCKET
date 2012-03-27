<?php 

//tells the menu which module this is
$current_module = 10;

require_once('../../templates/standard/socket_header.php'); ?>
<h1>Article Viewer</h1>
<?php
// Selects the title and description fields from the contents table
$dblookup = "SELECT fileID, userID, fileTitle, fileDescription, fileURL FROM core_media WHERE fileID=" . $_GET['ID'];

$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($fileID, $userID, $fileTitle, $fileDescription, $fileURL) = mysql_fetch_array($data, MYSQL_NUM))
{

/*HTML starts here */
?>
<h3> <?php echo "Viewing: " . $fileTitle; ?></h3>
<p> <strong>Uploaded by:</strong> 
<?php 
	//begin Fetch user script
	$ulookup = "SELECT full_name FROM core_users WHERE userID = $userID";
  	$user_fetch = mysql_query($ulookup) or die('Failed to return data: ' . mysql_error());
  	while($option = mysql_fetch_array($user_fetch)) { echo $option['full_name']; } 
	//end Fetch user script
?>

</p>
<div><img class="margin5px" src="<?php echo SITEROOT ?>/assets/scripts/timthumb/timthumb.php?src=<?php echo SERVERROOT ?>/socket/core-modules/media/<?php echo $fileURL ?>&amp;w=550" alt="<?php $fileTitle?>" />
<p><?php echo $fileDescription ?>
<p class="bold">
<label for="url"> Copy url (Note: If you are using firefox you will have to copy the text manually)</label>
<input class="inline" id="url" name="url" type="text" value="<?php echo SITEROOT .'/socket/core-modules/media/'. $fileURL ?>" size="60" /><input name="copy" onClick="ClipBoard();" type="button" value="Copy to Clipboard" class="inline" />
</p>
</div>

<?PHP
};

require_once('../../templates/standard/socket_footer.php'); ?>
