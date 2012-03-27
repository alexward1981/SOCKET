<?php require_once('templates/standard/socket_header.php'); ?>
                   <p class="float_right button"> <a href="<?php echo SITEROOT?>/socket/index.php">Discard</a></p> <h1> Page Browswer </h1>
          <p>From here you can view and edit pages on your website, if you have created any dynamic pages you can also delete them from here as well if required. </p>
          <br />
          <?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$dblookup = "SELECT pageID, pageName, articleTitle, articleBody, meta_key, meta_desc FROM core_content WHERE pageID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	list($pageID, $pageName, $articleTitle, $articleBody, $meta_key, $meta_desc) = mysql_fetch_array($data, MYSQL_NUM);
	}
	elseif (!empty($_POST['submit']))
	{
		if (empty($_POST['articleTitle']) || empty($_POST['articleBody']) || empty($_POST['pageName']) || empty($_POST['meta_key']) || empty($_POST['meta_desc'])) 
		{
			$message = '<strong><p class="error">All fields MUST be filled</p></strong>';
			
			$pageID 	 	= $_POST['pageID'];
			$articleTitle   = addslashes($_POST['articleTitle']);
			$articleBody 	= addslashes($_POST['articleBody']);
			$pageName	 	= addslashes($_POST['pageName']);
			$meta_key	 	= addslashes($_POST['meta_key']);
			$meta_desc	 	= addslashes($_POST['meta_desc']);
		} 
		else 
		{
			$pageID 	 	= $_POST['pageID'];
			$articleTitle   = addslashes($_POST['articleTitle']);
			$articleBody 	= addslashes($_POST['articleBody']);
			$pageName	 	= addslashes($_POST['pageName']);
			$meta_key	 	= addslashes($_POST['meta_key']);
			$meta_desc	 	= addslashes($_POST['meta_desc']);
						
			// Updates the database
			$dbupdate = "UPDATE core_content ".
	         "SET articleTitle = '$articleTitle', pageID = '$pageID', articleBody = '$articleBody', pageName = '$pageName', meta_key = '$meta_key', meta_desc = '$meta_desc'".
			 "WHERE pageID = '$pageID'";
			mysql_query($dbupdate) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
	
			// Return a message if successful.
			$message = '<strong><p class="success">Page updated</p></strong>';
		}

	}
	?>
          <h3> <?php echo "Curently Editing: " . $pageName ;?></h3>
          <h3>
          <?php echo $message; ?>
          <p class="error"> WARNING: This will modify core pages on the website, please ensure you have the appropriate permissions to perform this task, errors can cause the site to fail.</p>
          <form enctype="multipart/form-data" action="pages_edit.php" method="post">
            <input name="pageID" type="hidden" id="pageID" size="3" value="<?php echo $pageID ?>">
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
            <p>
              <label>Keywords (At least 5 - separeted by commas)
                <textarea class="mceSimple" name="meta_key" type="text" id="meta_key" cols="75" rows="2"><?php echo $meta_key ?></textarea>
              </label>
            </p>
            <p>
              <label>Article Summary<br />
                <textarea class="mceSimple" name="meta_desc" type="text" id="meta_desc" cols="75" rows="3"><?php echo $meta_desc ?></textarea>
              </label>
            </p>
            <input name="submit" type="submit" value="Submit">
          </form>
          <?php require_once('templates/standard/socket_footer.php'); ?>