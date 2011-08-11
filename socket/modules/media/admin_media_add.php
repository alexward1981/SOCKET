<?php 

//tells the menu which module this is
$current_module = 10;

require_once('../../templates/standard/socket_header.php'); ?>
        <?php
if(!empty($_POST['submit'])) {
//set where you want to store files
//in this example we keep file in folder upload
//$HTTP_POST_FILES['ufile']['name']; = upload file name
//for example upload file name cartoon.gif . $path will be upload/cartoon.gif

$path1= 'images/' . date(U). $HTTP_POST_FILES['ufile']['name'][0];
$userID = $_POST['userID'];
$fileTitle = $_POST['fileTitle'];
$fileDescription = $_POST['fileDescription'];
$fileType = $HTTP_POST_FILES['ufile']['type'];

if(empty($fileTitle)|| empty($path1)) { $message = '<p class="red size10font">File and File name cannot be blank!</p>';} else {

//copy file to where you want to store file
copy($HTTP_POST_FILES['ufile']['tmp_name'][0], $path1);

//$HTTP_POST_FILES['ufile']['name'] = file name
//$HTTP_POST_FILES['ufile']['size'] = file size
echo "File Name :".$HTTP_POST_FILES['ufile']['name'][0]."<BR/>";
echo "<img src=\"$path1\" width=\"150\" height=\"150\">";
echo "<P>";


$dbinsert = "INSERT INTO core_media (userID, fileType, fileTitle, fileDescription, fileURL) VALUES ('$userID', '$fileType', '$fileTitle', '$fileDescription', '$path1')";
$posted2 = mysql_query($dbinsert) or die($message = '<h3 style="color:red"> Insertion Failed! </h3>' . mysql_error());;
}

if ($posted2) {
echo '<h1>Image Added!</h1>';
echo '<p><a href="admin_media_add.php">Click here to add another image</a> </p>';
require_once('../../templates/standard/socket_footer.php');
exit;
}
}
 ?>
        <?php echo $message; ?>
        <form enctype="multipart/form-data" action="admin_media_add.php" method="post">
            <input name="userID" type="hidden" id="userID" size="50" value="<?php echo $_SESSION['userID']?>">
          <p>
            <label>Upload Image<br />
            <br />
            <input name="ufile[]" type="file" id="ufile[]" size="50">
            </label>
          </p>
          <p>
          <label>File Title<br />
	<input name="fileTitle" type="text" id="fileTitle" />
		</label></p>
          <p><label>File Description (Optional)<br />
		<textarea name="fileDescription" cols="50" rows="3" wrap="virtual" id="fileDescription"></textarea>
		</label>
          </p>
         <input name="submit" type="submit" value="Submit">
        </form>
        <?php require_once('../../templates/standard/socket_footer.php'); ?>
