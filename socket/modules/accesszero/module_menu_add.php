<?php 
//tells the menu which module this is
$current_module = 1000;

require_once('../../templates/standard/socket_header.php');
?>
          <h1> Add Module Menu Item </h1>
          <p>From here you can add new users to your website</p>
          <?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
$moduleID = $_POST['moduleID'];
$menu_item_name = $_POST['menu_item_name'];
$menu_path = $_POST['menu_path'];
$access_control = $_POST['access_control'];

$dbinsert = "INSERT INTO core_modules_menus (moduleID, menu_item_name, menu_path, access_control) VALUES ('$moduleID', '$menu_item_name', '$menu_path', '$access_control')";
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Failure!</strong> Menu items have not been added' . mysql_error() . '</div>');
}

if ($posted) {
$message = '<div class="success"><strong>Success!</strong> "'.$menu_item_name.'" has been added</div>';
}
		
 ?>
          <?php echo $message; ?>
          <form enctype="multipart/form-data" id="socket_form" action="module_menu_add.php" method="post">

            <label for="moduleID"><select name="moduleID">
                  <?php 
                  $dblookup = "SELECT moduleID, module_name FROM core_modules";
                  $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
                  while($option = mysql_fetch_array($data)) {
                  echo '<option value="' . $option['moduleID'];
				  if ($option['moduleID'] == $_POST['moduleID']) { echo 'selected="selected"'; }
				  echo '">' .$option['module_name'].'</option>';
                  }
                  ?>
           </select></label>
            <label for="access_control"><select name="access_control">
                  <?php 
                  $dblookup = "SELECT level_name, access_level FROM core_access_levels";
                  $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
                  while($option = mysql_fetch_array($data)) {
                  echo '<option value="' . $option['access_level'];
				  if ($option['access_level'] == 4) { echo 'selected="selected"'; }
				  echo ';">' .$option['level_name'].'</option>';
                  }
                  ?>
           </select></label>
           <p>
              <label>Menu Item Name<br />
                <input name="menu_item_name" type="text" id="menu_item_name" size="50" class="required" value="">
              </label>
           </p>
            <p>
              <label>Menu Item Path (after /socket/modules/)<br />
                <input name="menu_path" type="text" id="menu_path" size="50" class="required" value="">
              </label>
            </p>
            <input name="submit" type="submit" value="Submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>