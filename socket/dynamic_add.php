<?php require_once('templates/standard/socket_header.php'); ?>
          <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p><h1> Add Dynamic content </h1>
          <p>From here you can add content to existing pages.</p>
          <br />
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
$pageID = $_POST['pageID'];
$userID = $_POST['userID'];
$dynamicID = $_POST['dynamicID'];
$pageName = $_POST['pageName'];
$articleTitle = $_POST['articleTitle'];
$articleBody = $_POST['articleBody'];

if (empty($pageName) || empty($articleTitle) || empty($articleBody)) {

$message = '<strong><p class="error">All fields MUST be filled</p></strong>';
} else {
	
	$article_corrected = addslashes($articleBody);

$dbinsert = "INSERT INTO core_dynamic (pageID, userID, dynamicID,  pageName, articleTitle, articleBody) VALUES ('$pageID','$userID', '$dynamicID', '$pageName','$articleTitle', '$article_corrected')";
$posted = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted) {
$message = '<strong><p class="success">Dynamic page added</p></strong>';
}

}

$dblookup = "SELECT pageID, pageName FROM core_content";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());


 ?>
<?php echo $message; ?>
<p class="error"> WARNING: This will add content to existing pages. Please make sure you check your page after completion.</p>
<form enctype="multipart/form-data" action="dynamic_add.php" method="post">
          <input name="userID" type="hidden" id="userID" size="50" value="<?php echo $_SESSION['userID'] ?>">
 <p> <label>Which page do you want to add content to? (Note: New Content appears at the bottom of the page)

<select name="pageID" id="pageID">
 <?php while($option = mysql_fetch_array($data)) {
  echo '<option value="' . $option['pageID']. '">' .$option['pageName'].'</option>';
  } ?>
</select>
  </label></p>
 <p>
          
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

  <input name="submit" type="submit" value="Submit">
</form>
          <?php require_once('templates/standard/socket_footer.php'); ?>