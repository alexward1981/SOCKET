<?php 
//tells the menu which module this is
$current_module = 9;
require_once('../../templates/standard/socket_header.php'); 
?>

<h1> Edit Existing User </h1>
<?php
if(isset($_GET['ID']))
	{
	// Pulls the data from the database
	$userlookup = "SELECT * FROM core_users WHERE userID=" . $_GET['ID'];
	$userdata = mysql_query($userlookup) or die('Failed to return data: ' . mysql_error());
	$userdataArray= mysql_fetch_array($userdata, MYSQL_BOTH);
	extract($userdataArray, EXTR_PREFIX_ALL, "db");
	}
elseif (!empty($_POST['submit']))
	{
	// Gets the post data and puts it in variables.
	$db_userID = $_POST['userID'];
	$db_usr_firstname = addslashes($_POST['usr_firstname']);
	$db_usr_surname = addslashes($_POST['usr_surname']);
	$db_usr_username = addslashes($_POST['usr_username']);
	$db_usr_password = sha1(md5($_POST['usr_password']));
	$db_usr_email = addslashes($_POST['usr_email']);
	$db_usr_address_line1 = addslashes($_POST['usr_address_line1']);
	$db_usr_address_line2 = addslashes($_POST['usr_address_line2']);
	$db_usr_city = addslashes($_POST['usr_city']);
	$db_usr_country = addslashes($_POST['usr_country']);
	$db_usr_postcode = addslashes($_POST['usr_postcode']);
	$db_usr_phone = addslashes($_POST['usr_phone']);
	$db_usr_mobile = addslashes($_POST['usr_mobile']);
	$db_usr_twitter = addslashes($_POST['usr_twitter']);
	$db_usr_facebook = addslashes($_POST['usr_facebook']);	
	$db_usr_linkedin = addslashes($_POST['usr_linkedin']);
	$db_usr_biography = addslashes($_POST['usr_biography']);
	$db_usr_access_lvl = addslashes($_POST['usr_access_lvl']);
	$db_filetype = $_FILES['usr_avatar']['type'];
	if($db_filetype == 'image/jpeg' || $db_filetype == 'image/jpg' ||  $db_filetype == 'image/gif' || $db_filetype == 'image/png') 
				{
				$db_usr_avatar= $serverroot .'/socket/modules/users/avatars/' . date(U). $_FILES['usr_avatar']['name'];
				move_uploaded_file($_FILES['usr_avatar']['tmp_name'], $db_usr_avatar);
				}
		
	// Updates the database with the posted values
	$dbupdate = "UPDATE core_users SET";
	$dbupdate .= " userID = '$db_userID',";
	$dbupdate .= " usr_firstname = '$db_usr_firstname',";
	$dbupdate .= " usr_surname = '$db_usr_surname',";
	$dbupdate .= " usr_username = '$db_usr_username',";
	if ($_POST['passwordUpdate'] == 1) {
	$dbupdate .= " usr_password = '$db_usr_password',";
	}
	$dbupdate .= " usr_email = '$db_usr_email',";
	$dbupdate .= " usr_address_line1 = '$db_usr_address_line1',";
	$dbupdate .= " usr_address_line2 = '$db_usr_address_line2',";
	$dbupdate .= " usr_city = '$db_usr_city',";
	$dbupdate .= " usr_country = '$db_usr_country',";	
	$dbupdate .= " usr_postcode = '$db_usr_postcode',";
	$dbupdate .= " usr_phone = '$db_usr_phone',";
	$dbupdate .= " usr_mobile = '$db_usr_mobile',";
	$dbupdate .= " usr_twitter = '$db_usr_twitter',";
	$dbupdate .= " usr_facebook = '$db_usr_facebook',";
	$dbupdate .= " usr_linkedin = '$db_usr_linkedin',";
	$dbupdate .= " usr_biography = '$db_usr_biography',";
	$dbupdate .= " usr_access_lvl = '$db_usr_access_lvl' ";
	if ($_POST['imageUpdate'] == 1) 
		{
		$dbupdate .= ",usr_avatar = '$db_usr_avatar' ";
		}
	$dbupdate .= "WHERE userID = '" . $db_userID . "'";
	$posted = mysql_query($dbupdate) or die($message = '<div class="failure"><p><strong>Error!</strong> Your user has not been changed | ' . mysql_error() . '</p></div>' );
	}

if ($posted) 
	{
	$message = '<div class="success"><p><strong>Success!</strong> "'.$db_usr_firstname.' '.$db_usr_surname.'" has been modifed</p></div>';
	?>
	<!-- javascript send message to menu -->
<script language="JavaScript">
      window.location.href = '<?php echo $socketroot ?>/modules/users/admin_users.php?message=' + <?php echo $message; ?>;
</script>

<?php 
	}
echo $message; ?>

<form enctype="multipart/form-data" id="socket_form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post">
 <input name="userID" id="userID" type="hidden" size="3" value="<?php echo $db_userID ?>">
 <p>
    <label>First Name<br />
      <input name="usr_firstname" type="text" id="usr_firstname" size="50" class="required" value="<?php echo $db_usr_firstname ?>">
    </label>
  </p>
  <p>
    <label>Surname<br />
      <input name="usr_surname" type="text" id="usr_surname" size="50" class="required" value="<?php echo $db_usr_surname ?>">
    </label>
  </p>
  <p>
    <label>Username<br />
      <input name="usr_username" type="text" id="usr_username" size="50" class="required" value="<?php echo $db_usr_username ?>">
    </label>
  </p>
    <label>Update Password? 
    <input onclick="if (this.checked) {document.getElementById('usr_password_box').style.display='inline'; } else { document.getElementById('usr_password_box').style.display='none'; } return true;" name="passwordUpdate" type="checkbox" value="1" />
  </label>
  <div id="usr_password_box" style="display:none">
    <label for="usr_password" style="display:none"> Edit Password </label>
  <p>
    <label>Password<br />
      <input name="usr_password" type="password" id="usr_password" autocomplete="off" size="50" value="">
    </label>
  <div id="passwordStrengthDiv" class="is0"></div>
  </div>
  <p>
    <label>Email Address<br />
      <input name="usr_email" type="text" id="usr_email" size="50" class="required email"  value="<?php echo $db_usr_email ?>">
    </label>
  </p>
  <p>Note: The following fields are optional but recommended </p>
  <p>
    <label>Address Line 1<br />
      <input name="usr_address_line1" type="text" id="usr_address_line1" size="50" value="<?php echo $db_usr_address_line1 ?>">
    </label>
  </p>
  <p>
    <label>Address Line 2<br />
      <input name="usr_address_line2" type="text" id="usr_address_line2" size="50" value="<?php echo $db_usr_address_line2 ?>">
    </label>
  </p>
  <p>
    <label>City<br />
      <input name="usr_city" type="text" id="usr_city" size="50" value="<?php echo $db_usr_city ?>">
    </label>
  </p>
    <p>
    <label>Country<br />
      <input name="usr_country" type="text" id="usr_country" size="50" value="<?php echo $db_usr_country ?>">
    </label>
  </p>
  <p>
    <label>Postcode<br />
      <input name="usr_postcode" type="text" id="usr_postcode" size="50" value="<?php echo $db_usr_postcode ?>">
    </label>
  </p>
  <p>
    <label>Phone number<br />
      <input name="usr_phone" type="text" id="usr_phone" size="50" value="<?php echo $db_usr_phone ?>">
    </label>
  </p>
  <p>
    <label>Mobile number<br />
      <input name="usr_mobile" type="text" id="usr_mobile" size="50" value="<?php echo $db_usr_mobile ?>">
    </label>
  </p>
    <p>
    <label>Twitter username<br />
      <input name="usr_twitter" type="text" size="50" value="<?php echo $db_usr_twitter ?>">
    </label>
  </p>
    <p>
    <label>Facebook Username<br />
      <input name="usr_facebook" type="text" size="50" value="<?php echo $db_usr_facebook ?>">
    </label>
  </p>
    <p>
    <label>Linkedin Full address<br />
      <input name="usr_linkedin" type="text" size="50" value="<?php echo $db_usr_linkedin ?>">
    </label>
  </p>
    <p>
    <label>Biography<br />
		<textarea id="usr_biography"  name="usr_biography" cols="75" rows="5"><?php echo $db_usr_biography ?></textarea>
    </label>
  </p>

  <label>Update Image? 
    <input onclick="if (this.checked) {document.getElementById('usr_avatar').style.display='inline'; } else { document.getElementById('usr_avatar').style.display='none'; } return true;" name="imageUpdate" type="checkbox" value="1" />
  </label>
  </p>
  <p>
    <label for="usr_avatar" style="display:none"> Edit Profile image (Avatar) </label>
    <input style="display:none" name="usr_avatar" type="file" id="usr_avatar" size="50" value="usr_avatar">
  </p>
  <?php if ($_SESSION['usr_access_lvl'] == 0 || $_SESSION['usr_access_lvl'] == 2) { ?>
    <p>
    <label for="usr_access_lvl">Access Level <select name="usr_access_lvl">
		  <?php 
          $dblookup = "SELECT level_name, access_level FROM core_access_levels";
          $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
          while($option = mysql_fetch_array($data)) {
          echo '<option value="' . $option['access_level'].'"';
          if ($_SESSION['usr_access_lvl'] != 0) {
		  if ($option['access_level'] <= 1) { echo 'class="hidden" disabled="disabled"'; }
		  }
		  if ($option['access_level'] == $db_usr_access_lvl) { echo 'selected="selected"'; }
          echo '>' .$option['level_name'].'</option>';
          }
          ?>
   </select></label> 
  </p>
  <?php } ?>
  <input name="submit" id="submit" type="submit" value="Submit">
</form>
<?php require_once('../../templates/standard/socket_footer.php'); ?>
