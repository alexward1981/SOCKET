<?php 

//tells the menu which module this is
$current_module = 12;

require_once('../../templates/standard/socket_header.php');

?>
<h1> SOCKET Access Logs </h1>
<p> The log shows all login/logout times for all users </p>
<?php
if ($_GET['message']) { echo $_GET['message']; }

//Start Pagination
$items_per_page = 50;
$result = mysql_query("SELECT COUNT(*) AS total_entries FROM socket_access_log") or die(mysql_error()); 
$row = mysql_fetch_row($result); $total_entries = $row[0];
if(isset($_GET['page_number'])) { $page_number = $_GET['page_number']; } else { $page_number = 1; } 
$total_pages = ceil($total_entries / $items_per_page);
$offset = ($page_number - 1) * $items_per_page;
//End pagination

$dblookup = "SELECT * FROM socket_access_log ORDER BY logID DESC LIMIT $offset, $items_per_page";
$data = mysql_query($dblookup) or die('<h3 style="color:red"> Retrieval Failed! </h3>' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
$searchrows = mysql_num_rows($data);

	if($searchrows != 0)
 
	{	echo '<table class="stripeMe" width="100%">';
		while($result = @mysql_fetch_array($data))
			{ 
		
echo '	<tr>';
echo '    <td>' . $result["username"]. '</td>';
echo '    <td>' . $result["last_logged_in"]. '</td>';
if ($result["last_logged_out"]) {
echo '    <td>' . $result["last_logged_out"]. '</td>';
} else {
echo '    <td> STILL LOGGED IN </td>';
}
echo '  </tr>';

		
    		};

		echo '</table>';
// Pagination Navigation
echo '<div class="pagination">';
echo '<a class="capper" href="access_log.php?page_number=1">&lt; First</a>';
for($i = 1; $i <= $total_pages; $i++) { if($i == $page_number) { // This is the current page. Don't make it a link. 
echo '<span>'.$i.'</span>'; } else { // This is not the current page. Make it a link. 
echo '<a class="link" href="access_log.php?page_number='.$i.'">'.$i.'</a>'; }}
echo '<a class="capper" href="access_log.php?page_number='.$total_pages.'">Last &gt;</a>';
echo '</div>';
	
	} 
require_once('../../templates/standard/socket_footer.php'); ?>