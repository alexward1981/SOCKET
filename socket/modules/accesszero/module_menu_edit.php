<?php 
//tells the menu which module this is
$current_module = 1000;
require_once('../../templates/standard/socket_header.php'); 
?>
          <h1> Edit Module Menu </h1>
          <?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$modulelookup = "SELECT moduleID, menu_item_name, menu_path, access_control FROM core_modules_menus WHERE menuID=" . $_GET['ID'];
	$moduledata = mysql_query($modulelookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	list($moduleID, $menu_item_name, $menu_path, $access_control) = mysql_fetch_array($moduledata, MYSQL_NUM);
	}
// checks to see if the form has already been submitted
elseif (!empty($_POST['submit'])) {
// Gets the post data and puts it in variables.
$moduleID = $_POST['moduleID'];
$menu_item_name = $_POST['menu_item_name'];
$menu_path = $_POST['menu_path'];
$access_control = $_POST['access_control'];

$dbinsert = "UPDATE core_modules_menus SET (moduleID, menu_item_name, menu_path, access_control) VALUES ('$moduleID', '$menu_item_name', '$menu_path', '$access_control')";
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Failure!</strong> Menu items have not been added' . mysql_error() . '</div>');
}

if ($posted) {
$message = '<div class="success"><strong>Success!</strong> "'.$menu_item_name.'" has been modifed</div>';
}
		
 ?>
          <?php echo $message; ?>
          <form enctype="multipart/form-data" id="socket_form" action="module_menu_edit.php" method="post">

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
				  if ($option['access_level'] == $_POST['access_level']) { echo 'selected="selected"'; }
				  echo ';">' .$option['level_name'].'</option>';
                  }
                  ?>
           </select></label>
           <p>
              <label>Menu Item Name<br />
                <input name="menu_item_name" type="text" id="menu_item_name" size="50" class="required" value="<?php echo $menu_item_name ?>">
              </label>
           </p>
            <p>
              <label>Menu Item Path (after /socket/modules/)<br />
                <input name="menu_path" type="text" id="menu_path" size="50" class="required" value="<?php echo $menu_item_name ?>">
              </label>
            </p>
            <input name="submit" type="submit" value="Submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>