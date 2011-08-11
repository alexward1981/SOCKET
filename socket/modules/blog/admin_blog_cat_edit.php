<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); ?>
                    <p class="float_right button"> <a href="<?php echo $siteroot?>/socket/index.php">Discard</a></p><h1>Category Editor</h1>
          <p>From here you can edit your categories.</p>
          <?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$dblookup = "SELECT * FROM module_blog_categories WHERE categoryID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	$dataarray = mysql_fetch_array($data, MYSQL_BOTH);
	extract($dataarray);
	}
	if (!empty($_POST['submit']))
	{
			$categoryID 	= $_POST['categoryID'];
			$categoryName 	= $_POST['categoryName'];
			$categoryDesc   = $_POST['categoryDesc'];
			$isPrivate   	= $_POST['isPrivate'];
			
			// Updates the database
			$dbupdate = "UPDATE module_blog_categories ".
	         "SET categoryName = '$categoryName', categoryDesc = '$categoryDesc', isPrivate = '$isPrivate'".
			 "WHERE categoryID = '$categoryID'";
			mysql_query($dbupdate) or die('<h3 class="error"> Update Failed! </h3>' . mysql_error());
	
		}

	?>
          <h3> <?php echo "Curently Editing: " . $categoryName;?></h3>
        
          <form enctype="multipart/form-data" id="socket_form" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
            <input name="categoryID" type="hidden" id="categoryID" size="3" value="<?php echo $categoryID ?>">
            <p>
              <label>Category Name<br />
                <input name="categoryName" class="required" type="text" id="categoryName" size="70" value="<?php echo $categoryName ?>">
              </label>
            </p>
            <p>
              <label>Category Description (optional)<br />
                <textarea name="categoryDesc" id="categoryDesc" cols="75" rows="5"><?php echo $categoryDesc ?></textarea>
              </label>
            </p>
           <p><label> Select category type
            	<select name="isPrivate" id="isPrivate">
                	<option <?php if ($isPrivate == 0) { echo 'selected="selected"'; } ?> value="0">Public (Default)</option>
                    <option <?php if ($isPrivate == 1) { echo 'selected="selected"'; } ?> value="1"> Private </option>
                    </select>
                </label>
            </p>
            <input name="submit" id="submit" type="submit" value="submit">
          </form>
          <?php require_once('../../templates/standard/socket_footer.php'); ?>