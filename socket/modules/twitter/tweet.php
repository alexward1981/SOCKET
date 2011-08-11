<?php 

//tells the menu which module this is
$current_module = 7;

require_once('../../templates/standard/socket_header.php');
if ($_POST['tweet']) {

// Takes the url of the article and converts it into a is.gd url
	$status = htmlentities($_POST['tweet']);
	$tweetUrl = 'http://www.twitter.com/statuses/update.xml';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "$tweetUrl");
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "status=$status");
	curl_setopt($curl, CURLOPT_USERPWD, "$sc_twitter:$sc_twitter_pw");
	
	$result = curl_exec($curl);
	$resultArray = curl_getinfo($curl);
	
	if ($resultArray['http_code'] == 200) {
	$message = '<div class="success"> <strong> Tweet Posted </strong> <p> Your tweet has been posted </p></div>';
	} else {
	$message =  '<div class="failure"> <strong> Tweet Failed </strong> <p> Could not post Tweet to Twitter right now. Try again later.</p></div>';
	}
	curl_close($curl);
}
?>
<h1>Post a tweet </h1>
<p>Any messages posted here will appear in your websites Twitter feed </p>
<?php
if ($_GET['message']) { echo $_GET['message']; }
if ($message) { echo $message; }
?>
<form method="POST" id="twitter_form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" enctype="multipart/form-data">
<textarea class="validate[required,length[1,140]] noMCE fullwidth" name="tweet" cols="20" rows="3"></textarea>
<input name="submit" type="submit" value="Tweet this!">
</form>
<?php
require_once('../../templates/standard/socket_footer.php'); ?>