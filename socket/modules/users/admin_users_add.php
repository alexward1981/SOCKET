<?php 

//tells the menu which module this is
$current_module = 9;

require_once('../../templates/standard/socket_header.php'); ?>

<h1> Add New User </h1>
<p>From here you can add new users to your website</p>
<?php
// checks to see if the form has already been submitted
if (!empty($_POST['submit'])) {

// Gets the post data and puts it in variables.
$db_usr_firstname = $_POST['usr_firstname'];
$db_usr_surname = $_POST['usr_surname'];
$db_usr_username = $_POST['usr_username'];
$db_usr_password = sha1(md5($_POST['usr_password']));
$db_usr_email = $_POST['usr_email'];
$db_usr_address_line1 = $_POST['usr_address_line1'];
$db_usr_address_line2 = $_POST['usr_address_line2'];
$db_usr_city = $_POST['usr_city'];
$db_usr_country = $_POST['usr_country'];
$db_usr_postcode = $_POST['usr_postcode'];
$db_usr_phone = $_POST['usr_phone'];
$db_usr_mobile = $_POST['usr_mobile'];
$db_usr_twitter = $_POST['usr_twitter'];
$db_usr_facebook = $_POST['usr_facebook'];	
$db_usr_linkedin = $_POST['usr_linkedin'];
$db_usr_biography = $_POST['usr_biography'];
$db_usr_access_lvl = $_POST['usr_access_lvl'];
if (!empty($_FILES['usr_avatar'])) 
			{
			$filetype = $_FILES['usr_avatar']['type'];
			if($filetype == 'image/jpeg' || $filetype == 'image/jpg' || $filetype == 'image/gif' || $filetype == 'image/png') 
				{
			$db_usr_avatar= $serverroot .'/socket/modules/users/avatars/' . date(U) . $_FILES['usr_avatar']['name'];
			move_uploaded_file($_FILES['usr_avatar']['tmp_name'], $db_usr_avatar);
				}
			}


$dbinsert = "INSERT INTO core_users (usr_firstname, usr_surname, usr_username, usr_password, usr_email, usr_address_line1, usr_address_line2, usr_city, usr_country, usr_postcode, usr_phone, usr_mobile, usr_twitter, usr_facebook, usr_linkedin, usr_biography, usr_access_lvl, usr_avatar) VALUES ('$db_usr_firstname', '$db_usr_surname', '$db_usr_username', '$db_usr_password', '$db_usr_email', '$db_usr_address_line1', '$db_usr_address_line2', '$db_usr_city', '$db_usr_country', '$db_usr_postcode', '$db_usr_phone', '$db_usr_mobile', '$db_usr_twitter', '$db_usr_facebook', '$db_usr_linkedin', '$db_usr_biography', '$db_usr_access_lvl', '$db_usr_avatar')";
$posted = mysql_query($dbinsert) or die($message = '<div class="failure"><strong>Failure!</strong> Your new user has not been added' . mysql_error() . '</div>');
}

if ($posted) {
$message = '<div class="success"><strong>Success!</strong> ' . $db_usr_firstname .'&nbsp;'. $db_usr_surname . ' has been added</div>';
}
		
 ?>
<?php echo $message; ?>
<form enctype="multipart/form-data" id="socket_form" action="admin_users_add.php" method="post">
  <p>
    <label>First Name<br />
      <input name="usr_firstname" type="text" id="usr_firstname" size="50" class="required" value="">
    </label>
  </p>
  <p>
    <label>Surname<br />
      <input name="usr_surname" type="text" id="usr_surname" size="50" class="required" value="">
    </label>
  </p>
  <p>
    <label>Username<br />
      <input name="usr_username" type="text" id="usr_username" size="50" class="required" value="">
    </label>
  </p>
  <p>
    <label>Password<br />
      <input name="usr_password" type="password" id="usr_password" class="required" autocomplete="off" size="50" value="">
    </label>
  <div id="passwordStrengthDiv" class="is0"></div>
  </p>
  <p>
    <label>Email Address<br />
      <input name="usr_email" type="text" id="usr_email" size="50" class="required email"  value="">
    </label>
  </p>
  <p>Note: The following fields are optional but recommended </p>
  <p>
    <label>Address Line 1<br />
      <input name="usr_address_line1" type="text" id="usr_address_line1" size="50" value="">
    </label>
  </p>
  <p>
    <label>Address Line 2<br />
      <input name="usr_address_line2" type="text" id="usr_address_line2" size="50" value="">
    </label>
  </p>
  <p>
    <label>City<br />
      <input name="usr_city" type="text" id="usr_city" size="50" value="">
    </label>
  </p>
  <p>
    <label>Country<br />
      <input name="usr_country" type="text" size="50" value="">
    </label>
  </p>
  <p>
    <label>Postcode<br />
      <input name="usr_postcode" type="text" id="usr_postcode" size="50" value="">
    </label>
  </p>
  <p>
    <label>Phone number<br />
      <input name="usr_phone" type="text" id="usr_phone" size="50" value="">
    </label>
  </p>
  <p>
    <label>Mobile number<br />
      <input name="usr_mobile" type="text" id="usr_mobile" size="50" value="">
    </label>
  </p>
  <p>
    <label>Twitter username<br />
      <input name="usr_twitter" type="text" size="50" value="">
    </label>
  </p>
  <p>
    <label>Facebook Username<br />
      <input name="usr_facebook" type="text" size="50" value="">
    </label>
  </p>
  <p>
    <label>Linkedin Full address<br />
      <input name="usr_linkedin" type="text" size="50" value="">
    </label>
  </p>
  <p>
    <label>Biography<br />
      <textarea name="usr_biography" cols="75" rows="3"></textarea>
    </label>
  </p>
  <p>
    <label>Profile Image (Avatar)<br />
      <input name="usr_avatar" type="file" id="usr_avatar" size="50" value="usr_avatar">
    </label>
  </p>
  <p>
    <label for="usr_access_lvl">Access Level
      <select name="usr_access_lvl">
        <?php 
          $dblookup = "SELECT level_name, access_level FROM core_access_levels";
          $data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
          while($option = mysql_fetch_array($data)) {
          echo '<option value="' . $option['access_level'].'"';
          if ($_SESSION['usr_access_lvl'] != 0) {
		  if ($option['access_level'] <= 1) { echo 'class="hidden" disabled="disabled"'; }
		  }
          echo '>' .$option['level_name'].'</option>';
          }
          ?>
      </select>
    </label>
  </p>
  <input name="submit" type="submit" value="Submit">
</form>
<?php require_once('../../templates/standard/socket_footer.php'); ?>