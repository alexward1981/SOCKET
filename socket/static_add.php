<?php require_once('templates/standard/socket_header.php'); ?>
                    <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p><h1> Add New Physical Pages</h1>
          <p>From here you can add new pages to your website. Note: This will not create a navigation option, just the page itself, for more information contact us and we'll help you out.</p>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
$userID = $_POST['userID'];
$pageName = $_POST['pageName'];
$articleTitle = $_POST['articleTitle'];
$articleBody = $_POST['articleBody'];
$meta_key = $_POST['meta_key'];
$meta_desc = $_POST['meta_desc'];

if (empty($pageName) || empty($articleTitle) || empty($meta_key) || empty($meta_desc)) {

$message = '<strong><p class="error">All fields MUST be filled</p></strong>';
} else {
	
	$article_corrected = addslashes($articleBody);
	$desc_corrected = addslashes($meta_desc);	

$dbinsert = "INSERT INTO core_content (userID, pageName, articleTitle, articleBody, meta_key, meta_desc) VALUES ('$userID','$pageName','$articleTitle', '$article_corrected','$meta_key','$desc_corrected')";
$posted = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted) {
$message = '<strong><p class="success">Dynamic page added</p></strong>';
}

}

$dblookup = "SELECT pageID, userID, pageName, articleTitle, articleBody, meta_key, meta_desc description FROM core_content";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
		
 ?>
<?php echo $message; ?>
<p class="error"> WARNING: This will add content to existing pages. Please make sure you check your page after completion.</p>
<form enctype="multipart/form-data" action="static_add.php" method="post">
          <input name="userID" type="hidden" id="userID" size="50" value="<?php echo $_SESSION['userID'] ?>">
 <p> <label>Short Headline
  <input name="pageName" type="text" id="pageName" size="50" value="">
  </label></p>
 <p> <label>Headline
  <input name="articleTitle" type="text" id="articleTitle" size="50" value="">
  </label></p>
    <p>
    <label>Article Body
    <textarea class="mceAdvanced" name="articleBody" id="articleBody" cols="75" rows="35"></textarea>
    </label>
  </p>
  <p>
    <label>Keywords (use at least 5, seperated by commas)
    <textarea class="mceSimple" name="meta_key" id="meta_key" cols="75" rows="2"><?php echo $default_keywords ?></textarea>
    </label>
  </p>
  <p>
    <label>Description
    <textarea class="mceSimple" name="meta_desc" id="meta_desc" cols="75" rows="3"></textarea>
    </label>
  </p>
  <input name="submit" type="submit" value="Submit">
</form>
          <?php require_once('templates/standard/socket_footer.php'); ?>