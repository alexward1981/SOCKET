<?php 

//tells the menu which module this is
$current_module = 14;

require_once('../../templates/standard/socket_header.php'); ?>
                 <p class="float_right button"> <a href="<?php echo $siteroot?>/socket/index.php">Discard</a></p>   <h1>Edit Poll </h1>
          <p>From here you can modify your existing polls</p>
          <?php
// checks to see if the form has already been submitted
if($_GET['ID'])
	{
	// Pulls the data from the database
	$dblookup = "SELECT * FROM module_poll WHERE pollID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	$dataarray = mysql_fetch_array($data, MYSQL_BOTH);
	extract($dataarray, EXTR_PREFIX_ALL, "pl");
	} elseif (!empty($_POST['submit'])) {
			$pollID = $_POST['pollID'];
			$pollCreator = $_SESSION['userID'];
			$pollStatus = 0;
			$relatedArticle 	= $_POST['relatedArticle'];
			$pollQuestion		= str_replace("...","",$_POST['pollQuestion']);
			$pollDetail			= str_replace("...","",$_POST['pollDetail']);
			$pollAnswer1 		= htmlentities(addslashes(urldecode($_POST['pollAnswer1'])));
			$pollAnswer2 		= htmlentities(addslashes(urldecode($_POST['pollAnswer2'])));
			$pollAnswer3 		= htmlentities(addslashes(urldecode($_POST['pollAnswer3'])));
			$pollAnswer4 		= htmlentities(addslashes(urldecode($_POST['pollAnswer4'])));
			$pollAnswer5 		= htmlentities(addslashes(urldecode($_POST['pollAnswer5'])));			
			
$dbupdate = "UPDATE module_poll SET userID = '$pollCreator', articleID = '$relatedArticle', pollStatus = '$pollStatus', question = '$pollQuestion', detail = '$pollDetail', answer1 = '$pollAnswer1', answer2 = '$pollAnswer2', answer3 = '$pollAnswer3', answer4 = '$pollAnswer4', answer5 = '$pollAnswer5' WHERE pollID = " . $pollID;
$posted = mysql_query($dbupdate) or die($message = '<h3 style="color:red"> Update Failed! </h3>' . mysql_error());;
}

if ($posted) {
	$message = '<div class="success"><strong>Success!</strong> <p>Your Poll has been modified</p></div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo $socketroot ?>/modules/poll/admin_poll.php?message=' + <?php echo $message; ?>;
</script> <?php
}
?>
          <form enctype="multipart/form-data" id="socket_form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
            <input name="pollID" type="hidden" id="pollID" value="<?php echo $pl_pollID; ?>">
            <input name="pollStatus" type="hidden" id="pollStatus" value="1">
              <div class="inputcontainer">
              <label class="tab" for="pollQuestion">Poll Question</label>
                  <input class="fullwidth biggun" name="pollQuestion" type="text" id="pollQuestion" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollQuestion']; } else { echo $pl_question; } ?>">
            </div>
            <div class="inputcontainer">
              <label class="tab" for="pollDetail">Poll Details (optional)</label>
               <textarea class="fullwidth mceSimple" name="pollDetail" rows="4"><?php if ($_POST['submit']) { echo $_POST['pollDetail']; } else { echo $pl_detail; } ?></textarea>
            </div>
 
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer1">Answer 1</label>
                <input class="fullwidth biggun" name="pollAnswer1" type="text" id="pollAnswer1" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollAnswer1']; } else { echo $pl_answer1; } ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer2">Answer 2</label>
                <input class="fullwidth biggun" name="pollAnswer2" type="text" id="pollAnswer2" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollAnswer2']; } else { echo $pl_answer2; } ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer3">Answer 3 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer3" type="text" id="pollAnswer3" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollAnswer3']; } else { echo $pl_answer3; } ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer4">Answer 4 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer4" type="text" id="pollAnswer4" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollAnswer4']; } else { echo $pl_answer4; } ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer5">Answer 5 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer5" type="text" id="pollAnswer5" size="70" value="<?php if ($_POST['submit']) { echo $_POST['pollAnswer5']; } else { echo $pl_answer5; } ?>">
 			<div class="inputcontainer">
              <label class="tab" for="relatedArticle">Link to article
              <select class="intab" name="relatedArticle">
 <?php 
          $dblookup = "SELECT articleID, articleTitle FROM module_blog ORDER BY articleID DESC LIMIT 10";
          $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
		  echo '<option value="0"> No article Link </option>';
          while($option = mysql_fetch_array($data)) {
			  if ($option['articleID'] == $pl_articleID) { $optionSelected = 'selected="selected"'; }

          echo '<option '.$optionSelected.' value="' . $option['articleID'].'">' .stripslashes(html_entity_decode($option['articleTitle'])).'</option>';
          }
          ?>
              </select></label>
            </div>

                </div>
                
           <input name="submit" type="submit" value="submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>