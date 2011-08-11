<?php 

//tells the menu which module this is
$current_module = 9;

require_once('../../templates/standard/socket_header.php'); ?>
<h1>Article Viewer</h1>
<!-- This line and the one below it should be removed when the page is completed -->
<div class="failure"><strong>Incomplete</strong><p>This page is not release ready</p></div>
<?php
// Selects the title and description fields from the contents table
$dblookup = "SELECT * FROM core_users WHERE userID=" . $_GET['ID'];
if ($_GET['global'] == 1) {
$data = mysql_query($dblookup, $globalconn) or die('Failed to return data: ' . mysql_error());
} else {
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
}
$dataArray = mysql_fetch_array($data, MYSQL_BOTH);
extract($dataArray);
/*HTML starts here */
?>
<h3> <?php echo "Viewing: " . $full_name; ?>&nbsp;(<a href="admin_users_edit.php?ID=<?php echo $userID; ?>">Edit</a>)</h3>

<p><strong><?php echo 'Current User ID = ' . $userID; ?></strong></p>
<p><?php echo '<strong>Username: </strong>' . $usr_username; ?></p>
<p><?php echo '<strong>Email Address: </strong>' . $usr_email; ?></p>
<p><?php echo '<strong>Access Level: </strong>' . $useris; ?></p>


<?PHP require_once('../../templates/standard/socket_footer.php'); ?>
