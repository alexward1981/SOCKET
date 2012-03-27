<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); ?>
                    <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p><h1>Article Editor</h1>
          <p>From here you can edit your stream articles.</p>
          <?php
if($_GET['ID'])
	{
	// Pulls the data from the database
	$dblookup = "SELECT * FROM module_stream WHERE articleID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	$dataarray = mysql_fetch_array($data, MYSQL_BOTH);
	extract($dataarray, EXTR_PREFIX_ALL, "bl");
	}
	elseif (!empty($_POST['submit'])) // If the updates have been submitted
	{
		// Save previous article to the revisions table
		$saveArticle = mysql_query("SELECT * FROM module_stream WHERE articleID =" . $_POST['articleID']) or die ('No articles found');
		$savedArticles = mysql_fetch_array($saveArticle, MYSQL_BOTH);
		extract($savedArticles, EXTR_PREFIX_ALL, "saved");
		$currentUser = $_SESSION['userID'];
		$dbinsert = mysql_query("INSERT INTO module_stream_revisions (articleID, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImageAlt, articleAuthor, articleCat, datePosted, articlePosted, revisionBy) VALUES ('$saved_articleID', '".addslashes($saved_articleTitle)."', '".addslashes($saved_permaLink)."', '".addslashes($saved_articleBody)."', '".addslashes($saved_articleSummary)."', '$saved_articleImage', '".addslashes($saved_articleImageAlt)."', '$saved_articleAuthor', '$saved_articleCat', '$saved_datePosted', '$saved_articlePosted', '$currentUser')") or die ('insertion failed: ' . mysql_error());
		// Now insert the updates into the live database
			$articleID 	 		= $_POST['articleID'];
			$articleTitleDirty	= str_replace("...","",$_POST['articleTitle']);
			$articleTitle 		= htmlentities(addslashes($articleTitleDirty));
			$permaLinkDirty		= str_replace(" ","-",$_POST['articleTitle']);
			$permaLinkDashed	= strtolower(preg_replace('/[^A-Za-z0-9-]/','',$permaLinkDirty));
			$permaLink 			= str_replace("---","-",$permaLinkDashed);
			$articleBody 		= addslashes($_POST['articleBody']);
			$articleSummary	 	= addslashes($_POST['articleSummary']);
			$articleImageAlt	= addslashes($_POST['articleImageAlt']);
			$articleImagePos	= $_POST['articleImagePos'];
			$articleCat	 		= $_POST['articleCat'];
			if ($_POST['submit'] == 'Publish Article' || $saved_articlePosted ==1) {$articlePosted = 1;}
			$db_filetype = $_FILES['articleImage']['type'];
			if($db_filetype == 'image/jpeg' || $db_filetype == 'image/jpg' ||  $db_filetype == 'image/gif' || $db_filetype == 'image/png') 
				{
			$articleImage = '/articleimages/'.date(dmy).'_'.$_FILES['articleImage']['name'];
			$imageLocation = SERVERROOT .'/articleimages/';
			$imageTempName = $_FILES['articleImage']['tmp_name'];
			$imageName = date(dmy) .'_'. $_FILES['articleImage']['name'];
			move_uploaded_file($imageTempName, "$imageLocation/$imageName");
				}
			// Updates the database
			$dbupdate  = "UPDATE module_stream ";
	        $dbupdate .= "SET articleTitle = '$articleTitle', permaLink = '$permaLink', articleBody = '$articleBody', articleSummary = '$articleSummary', articleCat = '$articleCat', articlePosted = '$articlePosted'";
			if ($articlePosted == 1) {
				$dbupdate .= ", datePosted = now() ";
			} 
	if($_POST['imageUpdate']) {
			$dbupdate .= ", articleImage = '".mysql_real_escape_string($articleImage)."' ";
			$dbupdate .= ", articleImageAlt = '$articleImageAlt' ";
			$dbupdate .= ", articleImagePos = '$articleImagePos' ";
	}
			$dbupdate .= " WHERE articleID = '$articleID'";
			$posted = mysql_query($dbupdate) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
	if ($posted) 
	{
	$message = '<div class="success"><p><strong>Success!</strong> Your article has been modifed</p></div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo SOCKETROOT ?>/modules/stream/admin_stream.php?message=' + <?php echo $message; ?>;
</script>
		<?php }}
		?>
          <h2> <?php echo "Curently Editing: " . html_entity_decode(stripslashes($bl_articleTitle));?></h2>
          <?php 
		  	$alookup = "SELECT usr_firstname, usr_surname FROM core_users WHERE userID =" . $bl_articleAuthor;
			$adata = mysql_query($alookup) or die('<h3 style="color:red"> User retrieval Failed! </h3>' . mysql_error());				
			/* sorts the data into variables and puts them in an array ready to be called when needed */
			while($result = @mysql_fetch_array($adata)) 
			{ ?>
		
             <p><strong> Originally posted by: <?php echo $result['usr_firstname'] .' '. $result['usr_surname'] ?></p>
             	<?php }?>
			<form enctype="multipart/form-data" action="<?php echo $_SERVER['../Copy of stream/SCRIPT_NAME']; ?>" method="post">
            <input name="articleID" type="hidden" id="articleID" value="<?php echo $bl_articleID; ?>">
            <p>
              <label>Full Title<br />
                <input name="articleTitle" type="text" id="articleTitle" size="70" value="<?php echo html_entity_decode(stripslashes($bl_articleTitle)) ?>">
              </label>
            </p>
            <p>
              <label>Body Text<br />
                <textarea class="mceAdvanced" name="articleBody" id="articleBody" cols="75" rows="35"><?php echo stripslashes($bl_articleBody) ?></textarea>
              </label>
            </p>
            <p>
              <label>Article Summary<br />
                <textarea class="mceSimple" name="articleSummary" type="text" id="articleSummary" cols="75" rows="3"><?php echo stripslashes($bl_articleSummary) ?></textarea>
              </label>
            </p>
            <label>Update Image? 
    <input onclick="if (this.checked) {document.getElementById('articleImageBox').style.display='inline'; } else { document.getElementById('articleImageBox').style.display='none'; } return true;" name="imageUpdate" type="checkbox" value="1" />
  </label>
  </p>
    <div id="articleImageBox" style="display:none">
  <p>
    <label for="articleImage" style="display:none"> Edit Article Image</label>
    <input name="articleImage" type="file" id="articleImage" size="50" value="articleImage">
  </p>
    <div class="inputcontainer">
              <label class="tab" for="articleImageAlt">Image Description</label>
                <input class="fullwidth" name="articleImageAlt" type="text" size="50" value="<?php echo html_entity_decode($bl_articleImageAlt) ?>" /> <br /><br />
                             <label class="tab" for="articleImagePos">Select Thumbnail Position <select class="intab" name="articleImagePos">
                               <option <?php if ($bl_articleImagePos == 'C') { echo 'selected="selected"'; }?> value="C">Default (Centered)</option>
                               <option <?php if ($bl_articleImagePos == 'TR') { echo 'selected="selected"'; }?> value="TR">Top Right</option>
                               <option <?php if ($bl_articleImagePos == 'TL') { echo 'selected="selected"'; }?> value="TL">Top Left</option>
                               <option <?php if ($bl_articleImagePos == 'BR') { echo 'selected="selected"'; }?> value="BR">Bottom Right</option>
                               <option <?php if ($bl_articleImagePos == 'BL') { echo 'selected="selected"'; }?> value="BL">Bottom Left</option>
                               <option <?php if ($bl_articleImagePos == 'R') { echo 'selected="selected"'; }?> value="R">Centred Right</option>
                               <option <?php if ($bl_articleImagePos == 'L') { echo 'selected="selected"'; }?> value="L">Centred Left</option>
                               <option <?php if ($bl_articleImagePos == 'T') { echo 'selected="selected"'; }?> value="T">Centred Top</option>
                               <option <?php if ($bl_articleImagePos == 'B') { echo 'selected="selected"'; }?> value="B">Centred Bottom</option>
                             </select></label>
            </div>
            </div>
           <div class="inputcontainer">
           <label class="tab" for="articleCat">Select Category <select class="intab" name="articleCat">
		  <?php 
          $catlookup = "SELECT categoryID, categoryName FROM module_stream_categories";
          $datac = mysql_query($catlookup) or die('Failed to return data: ' . mysql_error());
          while($option = mysql_fetch_array($datac)) {
          echo '<option value="' . $option['categoryID'];
		  if ($option['categoryID'] == $bl_articleCat) { echo 'selected="selected"'; }
		  echo '">' .$option['categoryName'].'</option>';
          }
          ?>
   </select></label>
            </div>
            <?php if ($bl_articlePosted != 1) { ?> 
            <input name="submit" type="submit" value="Publish Article">
            <?php } ?>
			<input name="submit" type="submit" value="Save Changes">
			
          </form>
          <?php require_once('../../templates/standard/socket_footer.php'); ?>