<?php 
//tells the menu which module this is
$current_module = 12;

require_once('../../templates/standard/socket_header.php');
?>
          <h1> Add Module </h1>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
	$moduleID = $_POST['moduleID'];
	$folder_name = $_POST['folder_name'];
	$stylesheet_name = $_POST['stylesheet_name'];
	$display_name = $_POST['display_name'];
	$has_menu = $_POST['has_menu'];
	$access_control = $_POST['access_control'];
	$compatible = $_POST['compatible'];
	$active = $_POST['active'];
	
$dbinsert = "INSERT INTO core_modules (moduleID, folder_name, stylesheet_name, display_name, compatible, active, access_control) VALUES ('$moduleID', '$folder_name', '$stylesheet_name', '$display_name', '$compatible', '$active', '$access_control')";
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Failure!</strong> Menu items have not been added' . mysql_error() . '</div>');
}

if ($posted) {
$message = '<div class="success"><strong>Success!</strong> "'.$display_name.'" has been added</div>';
}
		
 ?>
          <?php echo $message; ?>
          <form enctype="multipart/form-data" id="socket_form" action="module_add.php" method="post">
<input name="moduleID" type="hidden" value="<?php echo $moduleID ?>" />
            <p><label for="access_control">Who has access to this module?<select name="access_control">
                  <?php 
                  $dblookup = "SELECT level_name, access_level FROM core_access_levels";
                  $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
                  while($option = mysql_fetch_array($data)) {
                  echo '<option value="' . $option['access_level'];
				  if ($option['access_level'] == $access_control) { echo 'selected="selected"'; }
				  echo ';">' .$option['level_name'].'</option>';
                  }
                  ?>
           </select></label></p>
           <p>
              <label>Module name 
                <input name="display_name" type="text" id="display_name" size="50" class="required" value="<?php echo $display_name ?>">
              </label>
           </p>
            <p>
              <label>Folder name
                <input name="folder_name" type="text" id="folder_name" size="50" class="required" value="<?php echo $folder_name ?>">
              </label>
            </p>
            <p>
              <label>Stylesheet name
                <input name="stylesheet_name" type="text" id="stylesheet_name" size="50" value="<?php echo $stylesheet_name ?>">
              </label>
            </p>
            <p><label>Module has menu <input name="has_menu" <?php if ($has_menu == 1) { echo 'checked="checked"'; } ?> type="checkbox" value="1" /></label></p>
            <p><label>Module active? <input name="active" <?php if ($active == 1) { echo 'checked="checked"'; } ?> type="checkbox" value="1" /></label></p>            
            <p>
              <label>Miminum SOCKET version
                <input name="compatible" type="text" id="compatible" size="50" value="<?php echo $compatible ?>">
              </label>
            </p>           
            <input name="submit" type="submit" value="Submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>