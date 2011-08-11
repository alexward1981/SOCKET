<?php 
// This widget checks the polls database for links to the current article.
$checkLinks = mysql_query("SELECT * FROM module_poll WHERE pollStatus = 0 AND articleID =".$articleID);
$checkLinksArray = mysql_fetch_array($checkLinks, MYSQL_BOTH);
if ($checkLinksArray) { extract($checkLinksArray, EXTR_PREFIX_ALL, "link"); }
if ($link_pollID) { //if this article is linked to a poll, display the results here.
// work out how many votes the were in total
$allVotes = array($link_a1result, $link_a2result, $link_a3result, $link_a4result, $link_a5result);
$totalVotes = array_sum($allVotes);
if ($totalVotes != 0) {
// Now work out the percentage value of each question
if ($totalVotes == 0) { $result1 = 0; } else { $result1 = ceil($link_a1result / $totalVotes * 100);}
if ($totalVotes == 0) { $result2 = 0; } else { $result2 = ceil($link_a2result / $totalVotes * 100);}
if ($link_answer3) { if ($totalVotes == 0) { $result3 = 0; } else { $result3 = ceil($link_a3result / $totalVotes * 100); }}
if ($link_answer4) { if ($totalVotes == 0) { $result4 = 0; } else { $result4 = ceil($link_a4result / $totalVotes * 100); }}
if ($link_answer5) { if ($totalVotes == 0) { $result5 = 0; } else { $result5 = ceil($link_a5result / $totalVotes * 100); }}
?>
<div id="articleResults">
<h1> Poll</h1>
<div class="box">
<h2><?php echo $link_question; ?></h2>
<?php echo $link_detail ?>
<ul id="resultContainer">
   <li class="result"> <?php echo $link_answer1 ?>
 	<li><div class="resultBar" style="width:<?php if ($result1 == 0) { echo '1px'; } else { echo $result1.'%'; } ?>"><?php echo '<span class="text">' . $result1 . '%</span>' ?></div></li>
  </li>
  
   <li class="result"> <?php echo $link_answer2 ?>
 	<li><div class="resultBar" style="width:<?php if ($result2 == 0) { echo '1px'; } else { echo $result2.'%'; } ?>"><?php echo '<span class="text">' . $result2 . '%</span>' ?></div></li>
  </li>
  
  <?php if ($link_answer3) { ?>
   <li class="result"> <?php echo $link_answer3 ?>
 	<li><div class="resultBar" style="width:<?php if ($result3 == 0) { echo '1px'; } else { echo $result3.'%'; } ?>"><?php echo '<span class="text">' . $result3 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  
  <?php if ($link_answer4) { ?>
   <li class="result"> <?php echo $link_answer4 ?>
 	<li><div class="resultBar" style="width:<?php if ($result4 == 0) { echo '1px'; } else { echo $result4.'%'; } ?>"><?php echo '<span class="text">' . $result4 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  
  <?php if ($link_answer5) { ?>
   <li class="result"> <?php echo $link_answer5 ?>
 	<li><div class="resultBar" style="width:<?php if ($result5 == 0) { echo '1px'; } else { echo $result5.'%'; } ?>"><?php echo '<span class="text">' . $result5 . '%</span>' ?></div></li>
  </li>
  <?php } ?>
  </ul>
  <p class="totalVotes"> <strong>Total votes:</strong> <?php echo $totalVotes ?> </p>
</div></div>

<?php 
} // if the article has receive no votes, don't display the poll results;
} //end if article is linked?>