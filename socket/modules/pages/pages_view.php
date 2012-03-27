<?php 

//tells the menu which module this is
$current_module = 11;

require_once('../../templates/standard/socket_header.php');
?>
<h1> Page Viewer</h1>
<br />
<?php
// Selects the title and description fields from the contents table
$dblookup = "SELECT pageID, articleTitle, articleBody FROM core_content WHERE pageID=" . $_GET['ID'];

$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($pageID, $title, $content) = mysql_fetch_array($data, MYSQL_NUM))
{

/*HTML starts here */
?>
<h3> <?php echo "Viewing: " . $title; ?>&nbsp;(<a href="pages_edit.php?ID=<?php echo $pageID; ?>">Edit</a>)</h3>
<p><strong><?php echo 'Current Page ID = ' . $pageID; ?></strong></p>
<p><strong>Body Text</strong></p>
<p><?php echo preg_replace("/<img[^>]+\>/i", "<img src=\"" .SITEROOT."/socket/assets/images/image_placeholder.png\" class=\"image_left\" />", $content) ?></p>

<?PHP
};

require_once('../../templates/standard/socket_footer.php'); ?>
