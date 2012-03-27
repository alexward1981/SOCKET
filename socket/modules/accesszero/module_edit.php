<?php 
//tells the menu which module this is
$current_module = 1000;
require_once('../../templates/standard/socket_header.php'); 
?>

<h1> Edit Modules </h1>

<?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$dblookup = "SELECT moduleID, module_name, stylesheet_name, display_name, has_menu, access_control, installed, compatible, active FROM core_modules WHERE moduleID=" . $_GET['ID'];
	$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
	/* sorts the data into variables and puts them in an array ready to be called when needed */
	list($moduleID, $module_name, $stylesheet_name, $display_name, $has_menu, $access_control, $installed, $compatible, $active) = mysql_fetch_array($data, MYSQL_NUM);
	}
elseif (!empty($_POST['submit'])) 
	{
	// Gets the post data and puts it in variables.
	$moduleID = $_POST['moduleID'];
	$module_name = $_POST['module_name'];
	$stylesheet_name = $_POST['stylesheet_name'];
	$display_name = $_POST['display_name'];
	$has_menu = $_POST['has_menu'];
	$access_control = $_POST['access_control'];
	$installed = $_POST['installed'];
	$compatible = $_POST['compatible'];
	$active = $_POST['active'];
	
	$dbupdate = "UPDATE core_modules SET moduleID = '$moduleID', module_name = '$module_name', stylesheet_name = '$stylesheet_name', display_name = '$display_name', has_menu = '$has_menu', access_control = '$access_control', installed = '$installed', compatible = '$compatible', active = '$active' WHERE moduleID = '$moduleID'";
	$posted = mysql_query($dbupdate) or die($message = '<div class="failure"><strong>Failure!</strong> <p>Menu items have not been added</p>' . mysql_error() . '</div>');
	}

if ($posted) 
	{
	$message = '<div class="success"><strong>Success!</strong> "'.$display_name.'" has been modifed</div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo SOCKETROOT ?>/modules/accesszero/modules.php?message=' + <?php echo $message; ?>;
</script>

<?php 
	}
echo $message; ?>
          <form enctype="multipart/form-data" id="socket_form" action="module_edit.php" method="post">
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
                <input name="module_name" type="text" id="module_name" size="50" class="required" value="<?php echo $module_name ?>">
              </label>
            </p>
            <p>
              <label>Stylesheet name
                <input name="stylesheet_name" type="text" id="stylesheet_name" size="50" value="<?php echo $stylesheet_name ?>">
              </label>
            </p>
            <p><label>Module has menu <input name="has_menu" <?php if ($has_menu == 1) { echo 'checked="checked"'; } ?> type="checkbox" value="1" /></label></p>
            <p><label>Module Installed? <input name="installed" <?php if ($installed == 1) { echo 'checked="checked"'; } ?> type="checkbox" value="1" /></label></p>
            <p><label>Module active? <input name="active" <?php if ($active == 1) { echo 'checked="checked"'; } ?> type="checkbox" value="1" /></label></p>            
            <p>
              <label>Miminum SOCKET version
                <input name="compatible" type="text" id="compatible" size="50" value="<?php echo $compatible ?>">
              </label>
            </p>           
            <input name="submit" type="submit" value="Submit">
          </form>
          
<?php require_once('../../templates/standard/socket_footer.php'); ?>