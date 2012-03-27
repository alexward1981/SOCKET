<?php 
//Gets the latest poll from the database
	$getPoll = mysql_query("SELECT * FROM module_poll WHERE pollStatus = 1 LIMIT 1") or die ('Failed to return poll: '.mysql_error());
	$pollArray = mysql_fetch_array($getPoll, MYSQL_BOTH);
	extract($pollArray, EXTR_PREFIX_ALL, "dbp");
	// Checks the ip addresses in the database against the users ip address
	$ipUpdate = ip2long($_SERVER['REMOTE_ADDR']); 
	$getIPArray = mysql_query("SELECT ipAddress FROM module_poll_ip WHERE pollID = $dbp_pollID AND ipAddress = $ipUpdate") or die ('Could not retrieve IP Addresses: '.mysql_error());
	$ipresult = mysql_num_rows($getIPArray);
	if (($_COOKIE['pollCast'] == $dbp_pollID) || ($ipresult != 0)){ $userVoted = 1; } 
	if ($userVoted !=1) {
	if ($_POST['vote']) {
	if ($_POST['answers']) {
	switch ($_POST['answers']) {
		case 1: 
		$incrementPoll = "UPDATE module_poll SET a1result = a1result+1 WHERE pollID = $dbp_pollID";
		break;
		case 2: 
		$incrementPoll = "UPDATE module_poll SET a2result = a2result+1 WHERE pollID = $dbp_pollID";
		break;
		case 3: 
		$incrementPoll = "UPDATE module_poll SET a3result = a3result+1 WHERE pollID = $dbp_pollID";
		break;
		case 4: 
		$incrementPoll = "UPDATE module_poll SET a4result = a4result+1 WHERE pollID = $dbp_pollID";
		break;
		case 5: 
		$incrementPoll = "UPDATE module_poll SET a5result = a5result+1 WHERE pollID = $dbp_pollID";
		break;
		}
$updatePoll = mysql_query($incrementPoll) or die ('Could not update poll results'.(mysql_error()));	
if ($ipresult == 0) { 
$logIP = mysql_query("INSERT INTO module_poll_ip (ipAddress, pollID) VALUES ('$ipUpdate', '$dbp_pollID')") or die ('IP Log failed:' . mysql_error());
$userVoted = 1; 
}
} else {
	$pollMessage = 'You did not choose an answer';
}
}
	}
if ($userVoted != 1) { // Checks the users cookies to find out if they have already voted, if they have not yet voted, display the poll
?>
<div id="poll">
<?php if ($dbp_articleID) {
	// If there is a related article. Show a link to it.
	$getArticleLink = mysql_query("SELECT articleID, articleCat, permaLink FROM module_blog WHERE articleID =".$dbp_articleID);
	$getArticleLinkArray = mysql_fetch_array($getArticleLink, MYSQL_BOTH);
	extract($getArticleLinkArray, EXTR_PREFIX_ALL, "ala");
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryID =" . $ala_articleCat;
	$catdetails = mysql_query($catlookup) or die ('fained to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
	echo '<a class="articleLink" href="'.SITEROOT.'/blog/'.strtolower($dbcat_categoryName).'/'.$ala_permaLink.'"> View related article </a>';
	} ?>
<h1> User Poll </h1>
<div class="box">
<h2> <?php echo $dbp_question; ?> </h2>
<div class="detail">
<?php if ($dbp_detail) { echo $dbp_detail; } ?>
</div>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" method="post" id="pollForm">
    <div class="radio"><label class="block">
      <input type="radio" name="answers" value="1"/>
      <?php echo $dbp_answer1; ?></label>
    </div>
    <div class="radio">
    <label class="block">
      <input type="radio" name="answers" value="2"/>
      <?php echo $dbp_answer2; ?></label>
    </div>
<?php if ($dbp_answer3) { ?>
    <div class="radio">
    <label class="block">
      <input type="radio" name="answers" value="3"/>
      <?php echo $dbp_answer3; ?></label>
    </div>
<?php } ?>
<?php if ($dbp_answer4) { ?>
    <div class="radio">
    <label class="block">
      <input type="radio" name="answers" value="4"/>
      <?php echo $dbp_answer4; ?></label>
    </div>
<?php } ?><?php if ($dbp_answer5) { ?>
    <div class="radio">
    <label class="block">
      <input type="radio" name="answers" value="5"/>
      <?php echo $dbp_answer5; ?></label>
    </div>
<?php } ?>
<input class="voteButton" name="vote" type="submit" value="vote" />
<?php echo $pollMessage; ?>
</form>
</div>
</div>
<?php } else { 
// If a cookie for this poll already exists on the users machine, show them the results instead of the poll.
// work out how many votes the were in total
$allVotes = array($dbp_a1result, $dbp_a2result, $dbp_a3result, $dbp_a4result, $dbp_a5result);
$totalVotes = array_sum($allVotes);
// Now work out the percentage value of each question
if ($totalVotes == 0) { $result1 = 0; } else { $result1 = ceil($dbp_a1result / $totalVotes * 100);}
if ($totalVotes == 0) { $result2 = 0; } else { $result2 = ceil($dbp_a2result / $totalVotes * 100);}
if ($dbp_answer3) { if ($totalVotes == 0) { $result3 = 0; } else { $result3 = ceil($dbp_a3result / $totalVotes * 100); }}
if ($dbp_answer4) { if ($totalVotes == 0) { $result4 = 0; } else { $result4 = ceil($dbp_a4result / $totalVotes * 100); }}
if ($dbp_answer5) { if ($totalVotes == 0) { $result5 = 0; } else { $result5 = ceil($dbp_a5result / $totalVotes * 100); }}

?>
<div id="poll">
<?php if ($dbp_articleID) {
	// If there is a related article. Show a link to it.
	$getArticleLink = mysql_query("SELECT articleID, articleCat, permaLink FROM module_blog WHERE articleID =".$dbp_articleID);
	$getArticleLinkArray = mysql_fetch_array($getArticleLink, MYSQL_BOTH);
	extract($getArticleLinkArray, EXTR_PREFIX_ALL, "ala");
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryID =" . $ala_articleCat;
	$catdetails = mysql_query($catlookup) or die ('fained to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
	echo '<a class="articleLink" href="'.SITEROOT.'/blog/'.strtolower($dbcat_categoryName).'/'.$ala_permaLink.'"> View related article </a>';
	} ?>
<h1> Poll Results</h1>
<div class="box">
<h2><?php echo $dbp_question; ?></h2>

<ul id="resultContainer">
   <li class="result"> <?php echo $dbp_answer1 ?>
 	<li><div class="resultBar" style="width:<?php if ($result1 == 0) { echo '1px'; } else { echo $result1.'%'; } ?>"><?php echo '<span class="text">' . $result1 . '%</span>' ?></div></li>
  </li>
  
   <li class="result"> <?php echo $dbp_answer2 ?>
 	<li><div class="resultBar" style="width:<?php if ($result2 == 0) { echo '1px'; } else { echo $result2.'%'; } ?>"><?php echo '<span class="text">' . $result2 . '%</span>' ?></div></li>
  </li>
  
  <?php if ($dbp_answer3) { ?>
   <li class="result"> <?php echo $dbp_answer3 ?>
 	<li><div class="resultBar" style="width:<?php if ($result3 == 0) { echo '1px'; } else { echo $result3.'%'; } ?>"><?php echo '<span class="text">' . $result3 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  
  <?php if ($dbp_answer4) { ?>
   <li class="result"> <?php echo $dbp_answer4 ?>
 	<li><div class="resultBar" style="width:<?php if ($result4 == 0) { echo '1px'; } else { echo $result4.'%'; } ?>"><?php echo '<span class="text">' . $result4 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  
  <?php if ($dbp_answer5) { ?>
   <li class="result"> <?php echo $dbp_answer5 ?>
 	<li><div class="resultBar" style="width:<?php if ($result5 == 0) { echo '1px'; } else { echo $result5.'%'; } ?>"><?php echo '<span class="text">' . $result5 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  </ul>
  <p class="totalVotes"> <strong>Total votes:</strong> <?php echo $totalVotes ?> </p>
</div>

</div>
<?php }; ?>

