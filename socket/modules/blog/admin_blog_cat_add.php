<?php 

//tells the menu which module this is
$current_module = 2;

require_once('../../templates/standard/socket_header.php'); ?>
                 <p class="float_right button"> <a href="<?php echo $siteroot?>/socket/index.php">Discard</a></p>   <h1> Add Category </h1>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
$categoryName = addslashes($_POST['categoryName']);
$categoryDesc = addslashes($_POST['categoryDesc']);
$isPrivate = addslashes($_POST['isPrivate']);
$dbinsert = "INSERT INTO module_blog_categories (categoryName, categoryDesc, isPrivate) VALUES ('$categoryName', '$categoryDesc', '$isPrivate')";
$posted = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted) {
$message = '<strong><p class="success">Category added</p></strong>';
}
?>
          <form enctype="multipart/form-data" id="socket_form" action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="post">
            <p>
              <label>Category Name<br />
                <input class="required" name="categoryName" type="text" id="categoryName" size="50" value="">
              </label>
            </p>
            <p>
              <label>Category Description (Optional)
                <textarea name="categoryDesc" id="categoryDesc" cols="75" rows="5"></textarea>
              </label>
            </p>
            <p><label> Select category type
            	<select name="isPrivate" id="isPrivate">
                	<option selected="selected" value="0">Public (Default)</option>
                    <option value="1"> Private </option>
                    </select>
                </label>
            </p>
            <input name="submit" type="submit" value="Submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>