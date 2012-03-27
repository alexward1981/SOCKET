<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); ?>
                 <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p>   <h1> Add Blog Article </h1>
          <p>From here you can add new blog articles to your website</p>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
$articleAuthor = $_SESSION['userID'];
if ($g == 1) { $GlobalAuthor = 1; } else { $GlobalAuthor = 0; }
			$articleID 	 		= $_POST['articleID'];
			$articleTitleDirty	= str_replace("...","",$_POST['articleTitle']);
			$articleTitle 		= htmlentities(addslashes($articleTitleDirty));
			$permaLinkDirty		= str_replace(" ","-",$_POST['articleTitle']);
			$permaLinkDashed	= strtolower(preg_replace('/[^A-Za-z0-9-]/','',$permaLinkDirty));
			$permaLink 			= str_replace("---","-",$permaLinkDashed);
			$articleBody 		= addslashes($_POST['articleBody']);
			$articleSummary	 	= addslashes($_POST['articleSummary']);
			$articleImageAlt	= addslashes($_POST['articleImageAlt']);
			$articleCat	 		= $_POST['articleCat'];
			$articleImagePos	 		= $_POST['articleImagePos'];
			if ($_POST['submit'] == 'Publish') {$articlePosted = 1;}
			if (!empty($_FILES['articleImage'])) 
			{
			$filetype = $_FILES['articleImage']['type'];
			if($filetype == 'image/jpeg' || $filetype == 'image/jpg' || $filetype == 'image/gif' || $filetype == 'image/png') 
				{
			$articleImage = '/articleimages/' . date(dmy) .'_'. $_FILES['articleImage']['name'];
			$imageLocation = SERVERROOT .'/articleimages/';
			$imageTempName = $_FILES['articleImage']['tmp_name'];
			$imageName = date(dmy) .'_'. $_FILES['articleImage']['name'];
			move_uploaded_file($imageTempName, "$imageLocation/$imageName");
				}
			$articleImageAlt = $_POST['articleImageAlt'];	
			}
$dbinsert = "INSERT INTO module_blog (articleID, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, GlobalAuthor, articleCat, datePosted, articlePosted) VALUES ('$articleID', '$articleTitle', '$permaLink', '$articleBody', '$articleSummary', '$articleImage', '$articleImagePos', '$articleImageAlt', '$articleAuthor', '$GlobalAuthor', '$articleCat', now(), '$articlePosted')";
$posted = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted) {
	$message = '<div class="success"><strong>Success!</strong> <p>Your article has been added</p></div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo SOCKETROOT ?>/modules/blog/admin_blog.php?message=' + <?php echo $message; ?>;
</script> <?php
}
?>
          <form enctype="multipart/form-data" id="socket_form" action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="post">
            <input name="articleAuthor" type="hidden" id="articleAuthor" value="<?php echo $_SESSION['userID']; ?>">
                        <div class="inputcontainer">
              <label class="tab" for="articleTitle">Article Title</label>
                <input class="fullwidth biggun" name="articleTitle" type="text" id="articleTitle" size="70" value="">
                </div>
                <div class="inputcontainer">
             <label class="tab" for="articleCat">Select Category <select class="intab" name="articleCat">
		  <?php 
          $dblookup = "SELECT categoryID, categoryName FROM module_blog_categories";
          $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
          while($option = mysql_fetch_array($data)) {
          echo '<option value="' . $option['categoryID'].'">' .$option['categoryName'].'</option>';
          }
          ?>
   </select></label>
            </div>
            <div class="inputcontainer">
              <label class="tab" for="articleBody">Article Body</label>
                <textarea class="fullwidth mceAdvanced" name="articleBody" id="articleBody" rows="40"></textarea>
            </div>
            <div class="inputcontainer">
              <label class="tab" for="articleSummary">Article Summary (250 chars max)</label>
                <textarea class="fullwidth mceSimple" name="articleSummary" id="articleSummary" rows="5"></textarea>
            </div>
              <p>
                <div class="inputcontainer">
    <label class="tab">Add an image for this article (recommended)<br />
      <input name="articleImage" type="file" id="articleImage" size="50" value="articleImage">
    </label>
    </div>
  </p>
  <div class="inputcontainer">
              <label class="tab" for="articleImageAlt">Image Description</label>
                <input class="fullwidth" name="articleImageAlt" type="text" size="50" value=""><br /><br />

                             <label class="tab" for="articleImagePos">Select Thumbnail Position <select class="intab" name="articleImagePos">
                               <option value="C">Default (Centered)</option>
                               <option value="TR">Top Right</option>
                               <option value="TL">Top Left</option>
                               <option value="BR">Bottom Right</option>
                               <option value="BL">Bottom Left</option>
                               <option value="R">Centred Right</option>
                               <option value="L">Centred Left</option>
                               <option value="T">Centred Top</option>
                               <option value="B">Centred Bottom</option>
                             </select></label>
            </div>
           <input name="submit" type="submit" value="Publish">
           <input name="submit" type="submit" value="Save">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>