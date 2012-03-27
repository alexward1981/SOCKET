<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); //Sets the static pageID 
$pageID = 13;

// Selects the title and description fields from the contents table
$dblookup = "SELECT articleTitle,articleBody,meta_key,meta_desc FROM core_content WHERE(core_content.pageID = '$pageID')";
$data = mysql_query($dblookup, $conn) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list( $article_title, $article_body, $meta_key, $meta_desc) = mysql_fetch_array($data, MYSQL_NUM))
{
$meta_title = "$article_title";
$meta_keywords = "$meta_key";
$meta_description = "$meta_desc";
$title = "$article_title";
$body = "$article_body";
};
require_once('' . SERVERROOT . '/assets/style/standard/head.php');
require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
require_once('/assets/style/standard/header.php');


if(isset($_POST['submit'])) {

$to = $sc_email;
$subject = $_POST['your_query'];
$name_field = $_POST['your_name'];
$email_field = $_POST['your_email'];
$tel_field = $_POST['your_telephone'];
$message = $_POST['your_details'];
 
$body = "From: $name_field\n";
$body .= "E-Mail: $email_field\n";
$body .= "Telephone: $tel_field\n";
$body .= "Message:\n $message";

echo '<h1> Email Sent! </h1>';
echo '<p>Thank you for your enquiry.</p> <p>If it is required a representative will contact you as soon as possible</p><p><strong>Please note:</strong></p>  <p>We endeavour to respond to all requests within ' . $sc_response_time . ' however during busy or holiday periods this may increase. </p> <p> Kind Regards, </p> <p>' . $sc_sitemanager . '</p>';
mail($to, $subject, $body);

} else {

//Main content starts here
?>
<div id="bodytext">
<?php if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 3) {echo '<span class="socket_action_button"> <a href="'.SITEROOT.'/socket/pages_edit.php?ID='.$pageID.'"><img src="' . SITEROOT . '/socket/assets/images/buttons/button_edit.png" width="15" height="15" alt="Edit Page" /></a></span>';}?>
  <h1><?php echo stripslashes($title); ?></h1>
  <?php echo stripslashes($body); ?>
<div class="clear"></div>
<div id="contactbox">
<h2> Enquiry Form </h2>
<form id="contact_form" name="contact_form" method="post" action="<?php $_SERVER['SCRIPT_NAME']?>">
  <table border="0"  cellpadding="0" cellspacing="5">
    <tr>
      <td><label for="your_name">Your Name</label></td>
      <td><input type="text" name="your_name" id="your_name" /></td>
    </tr>
    <tr>
      <td><label for="your_email">Email Address</label></td>
      <td><input type="text" name="your_email" id="your_email" /></td>
    </tr>
    <tr>
      <td><label for="your_telephone">Telephone Number</label></td>
      <td><input type="text" name="your_telephone" id="your_telephone" /></td>
    </tr>
    <tr>
      <td><label for="your_query">Your Enquiry</label></td>
      <td><select name="your_query" id="your_query">
        <option value="Did not receive activation email">I did not receive my activation email</option>
        <option value="Website Problem">Website Problem</option>        
        <option value="Bug Report">Bug Report</option>        
        <option value="General Enquiry" selected="selected">General Enquiry</option>
        <option value="Feedback/Complaint">Feedback / Complaint</option>
      </select></td>
    </tr>
    <tr>
      <td valign="top"><label for="your_details">Details of Enquiry</label></td>
      <td><textarea name="your_details" id="your_details" cols="45" rows="5"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="form_button" type="submit" name="submit" id="submit" value="Submit" /><p class="red size10font"><?php echo $error ?></p></td>
    </tr>
  </table>
</form>

</div></div>
<?php } //Main content ends here
require_once('/assets/style/standard/footer.php');
?>