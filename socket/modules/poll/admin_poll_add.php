<?php 

//tells the menu which module this is
$current_module = 14;

require_once('../../templates/standard/socket_header.php'); ?>
                 <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p>   <h1> Add New Poll </h1>
          <p>From here you can add new Polls to your website</p>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
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

if (empty($_POST['pollQuestion']) || empty($_POST['pollAnswer1']) || empty($_POST['pollAnswer2'])) { 
$message = '<div class="failure"><strong>Error!</strong> <p>Only fields marked \'optional\' may be empty</p></div>';
} else {
			
$dbinsert = "INSERT INTO module_poll (userID, articleID, pollStatus, question, detail, answer1, answer2, answer3, answer4, answer5) VALUES ('$pollCreator', '$relatedArticle', '$pollStatus', '$pollQuestion', '$pollDetail', '$pollAnswer1', '$pollAnswer2', '$pollAnswer3', '$pollAnswer4', '$pollAnswer5')";
$posted = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted) {
	$message = '<div class="success"><strong>Success!</strong> <p>Your Poll has been added</p></div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo SOCKETROOT ?>/modules/poll/admin_poll.php?message=' + <?php echo $message; ?>;
</script> <?php
}
}
echo $message;
?>
          <form enctype="multipart/form-data" id="socket_form" action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="post">
            <input name="pollStatus" type="hidden" id="pollStatus" value="1">
              <div class="inputcontainer">
              <label class="tab" for="pollQuestion">Poll Question</label>
                  <input class="fullwidth biggun" name="pollQuestion" type="text" id="pollQuestion" size="70" value="<?php echo $_POST['pollQuestion']; ?>">
            </div>
            <div class="inputcontainer">
              <label class="tab" for="pollDetail">Poll Details (optional)</label>
               <textarea class="fullwidth mceSimple" name="pollDetail" rows="4"><?php echo $_POST['pollDetail']; ?></textarea>
            </div>
 
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer1">Answer 1</label>
                <input class="fullwidth biggun" name="pollAnswer1" type="text" id="pollAnswer1" size="70" value="<?php echo $_POST['pollAnswer1']; ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer2">Answer 2</label>
                <input class="fullwidth biggun" name="pollAnswer2" type="text" id="pollAnswer2" size="70" value="<?php echo $_POST['pollAnswer2']; ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer3">Answer 3 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer3" type="text" id="pollAnswer3" size="70" value="<?php echo $_POST['pollAnswer3']; ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="pollAnswer4">Answer 4 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer4" type="text" id="pollAnswer4" size="70" value="<?php echo $_POST['pollAnswer4']; ?>">
                </div>
                
         <div class="inputcontainer">
              <label class="tab" for="pollAnswer5">Answer 5 (optional)</label>
                <input class="fullwidth biggun" name="pollAnswer5" type="text" id="pollAnswer5" size="70" value="<?php echo $_POST['pollAnswer5']; ?>">
                </div>
 			<div class="inputcontainer">
              <label class="tab" for="relatedArticle">Link to article
              <select class="intab" name="relatedArticles">
 <?php 
          $dblookup = "SELECT articleID, articleTitle FROM module_blog ORDER BY articleID DESC LIMIT 10";
          $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
          while($option = mysql_fetch_array($data)) {
          echo '<option value="' . $option['articleID'].'">' .stripslashes(html_entity_decode($option['articleTitle'])).'</option>';
          }
          ?>
              </select></label>
            </div>
 			
                
           <input name="submit" type="submit" valu="Publish">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>