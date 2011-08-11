<?php require_once('templates/standard/socket_header.php'); ?>
                   <p class="float_right button"> <a href="<?php echo $siteroot?>/socket/index.php">Discard</a></p> <h1> Page Browswer </h1>
          <p>From here you can view and edit pages on your website, if you have created any dynamic pages you can also delete them from here as well if required. </p>
          <br />
          <?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$dblookup = "SELECT pageID, dynamicID, pageName, articleTitle, articleBody FROM core_dynamic WHERE dynamicID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	list($pageID, $dynamicID, $pageName, $articleTitle, $articleBody) = mysql_fetch_array($data, MYSQL_NUM);
	}
	elseif (!empty($_POST['submit']))
	{
		if (empty($_POST['articleTitle']) || empty($_POST['articleBody']) || empty($_POST['pageName'])) 
		{
			$message = '<strong><p class="error">All fields MUST be filled</p></strong>';
			$dynamicID 	 	= $_POST['dynamicID'];			
			$pageID 	 	= $_POST['pageID'];
			$articleTitle   = $_POST['articleTitle'];
			$articleBody 	= $_POST['articleBody'];
			$pageName	 	= $_POST['pageName'];
		} 
		else 
		{
			$dynamicID 	 	= $_POST['dynamicID'];			
			$pageID 	 	= $_POST['pageID'];
			$articleTitle   = $_POST['articleTitle'];
			$articleBody 	= $_POST['articleBody'];
			$pageName	 	= $_POST['pageName'];
			//Sanitises the content
			$article_corrected = addslashes($articleBody);
			
			// Updates the database
			$dbupdate = "UPDATE core_dynamic ".
	         "SET articleTitle = '$articleTitle', pageID = '$pageID', articleBody = '$article_corrected', pageName = '$pageName'".
			 "WHERE dynamicID = '$dynamicID'";
			mysql_query($dbupdate) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
	
			// Return a message if successful.
			$message = '<strong><p class="success">Page updated</p></strong>';
		}

	}
	?>
          <h3> <?php echo "Curently Editing: " . $pageName ;?></h3> 
          <h3>
          <?php echo $message; ?>
          <form enctype="multipart/form-data" action="dynamic_edit.php" method="post">
                      <input name="dynamicID" type="hidden" id="dynamicID" size="3" value="<?php echo $dynamicID ?>">
            <p>
              <label>If you want to move the content to another page, please select it here<br />
              <select name="pageID" id="pageID">
 <?php
 	$dblookup2 = "SELECT pageID, pageName FROM core_content";
	$data2 = mysql_query($dblookup2) or die('Failed to return data: ' . mysql_error());
	while($option = mysql_fetch_array($data2)) {
  echo '<option value="' . $option['pageID']. '">' .$option['pageName'].'</option>';
  } ?></select>
              </label>
            </p>
            <p>
              <label>Short Headline<br />
                <input name="pageName" type="text" id="pageName" size="50" value="<?php echo $pageName ?>">
              </label>
            </p>
            <p>
              <label>Full Headline<br />
                <input name="articleTitle" type="text" id="articleTitle" size="70" value="<?php echo $articleTitle ?>">
              </label>
            </p>
            <p>
              <label>Body Text<br />
                <textarea class="mceAdvanced" name="articleBody" id="articleBody" cols="75" rows="35"><?php echo $articleBody ?></textarea>
              </label>
            </p>

            <input name="submit" type="submit" value="Submit">
          </form>
          <?php require_once('templates/standard/socket_footer.php'); ?>