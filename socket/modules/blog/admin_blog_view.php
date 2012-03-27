<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); ?>
<h1>Article Viewer</h1>
<?php
// Selects the title and description fields from the contents table
$dblookup = "SELECT blogID, userID,  blogTitle, blogBody FROM module_blog WHERE blogID=" . $_GET['ID'];

$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($blogID, $userID, $blogTitle, $blogBody) = mysql_fetch_array($data, MYSQL_NUM))
{


/*HTML starts here */
?>
<div class="notebox"><h3> <?php echo "Viewing: " . $blogTitle; ?>&nbsp;(<a href="../blog/admin_blog_edit.php?ID=<?php echo $blogID; ?>">Edit</a>)</h3>
<p> <strong>Article added by: </strong>
<?php 
	//begin Fetch user script
	$ulookup = "SELECT full_name FROM core_users WHERE userID = $userID";
  	$user_fetch = mysql_query($ulookup) or die('Failed to return data: ' . mysql_error());
  	while($option = mysql_fetch_array($user_fetch)) { echo $option['full_name']; } 
	//end Fetch user script
?></p>
<p><strong><?php echo 'Current Article ID = ' . $blogID; ?></strong></p></div>
<p><strong>Body Text</strong></p>
<p><?php echo preg_replace("/<img[^>]+\>/i", "<img src=\"" .SITEROOT."/socket/assets/images/image_placeholder.png\" class=\"image_left\" />", $blogBody) ?></p>

<?PHP
};

require_once('../../templates/standard/socket_footer.php'); ?>
