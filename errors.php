<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); 
//Sets the static pageID 
$pageID = 1;
$dynamicID = $pageID;
// Selects the title and description fields from the contents table
$dblookup = "SELECT articleTitle,articleBody,meta_key,meta_desc FROM core_content WHERE(core_content.pageID = '$pageID')";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());

/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($article_title, $article_body, $meta_key, $meta_desc) = mysql_fetch_array($data, MYSQL_NUM))
{
$meta_title = $sc_meta_title;
$meta_keywords = "$meta_key";
$meta_description = "$meta_desc";
$title = "$article_title";
$body = "$article_body";
};
require_once('' . SERVERROOT . '/assets/style/standard/head.php');
require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
require_once('/assets/style/standard/header.php');
if ($_GET['message']) {
	echo $_GET['message'];
}
switch ($_GET['type']) {
case 401:
	echo '<h1> 401 - Well thats impressive. </h1>';
	echo '<div id="bodytext">';
	echo '<p> <strong>How the hell did you do that?!</strong>.</p>
 <p>I have to hand it to you, you managed to break something I never thought possible, there is literally nothing on this site which could possibly cause this error so congratulations. Do you want to go back to the home page? If so <a class="bold italic" href="'.SITEROOT.'">click here</a>. </p>';
	break;
	case 403:
	echo '<h1> 403 - OI! What do you think you are doing? </h1>';
	echo '<div id="bodytext">';
	echo '<p> <strong>You are not allowed to be in here</strong>.</p>
 <p>Now this is interesting, you seem to be snooping in places you should not be? I hope you are not trying to hack this site! We\'ll give you the benefit of the doubt as you are so pretty</a>. I suggest you <a class="bold italic" href="'.SITEROOT.'">return to the homepage sharpish</a>';
	echo '<p> If there was something particular you were looking for then try the search bar above, that should help. </p>';
	break;
	case 404:
	echo '<h1> 404 - Page died a brutal death. </h1>';
	echo '<div id="bodytext">';
	echo '<p> <strong>This page angered the Gods so we killed it</strong>.</p>
 <p>Don\'t worry though, it was a bad page and it hurt a lot of people. Do you want to go back to the home page? If so <a class="bold italic" href="'.SITEROOT.'">click here</a>. The home page has always been good to us and we\'ve got a little deal where it brings us visitors and we don\'t let it die.</p>';
	echo '<p> If there was something particular you were looking for then try the search bar above, that should help. If it doesn\'t then check the address bar. Are you sure you have the correct site?!</p>';
	break;
case 500:
	echo '<h1> 500 - Everything has gone wrong. </h1>';
	echo '<div id="bodytext">';
	echo '<p> <strong>You just deleted the entire internet!!!</strong>.</p>
 <p>Only kidding! We\'ve done something wrong somewhere and its cause this error. We may just be over capacity. </p>
 <p>If you get this error a lot then please let us know by emailing <a class="bold italic" href="mailto:support@digitalfusionmag.com"> support@digitalfusionmag.com </a></p>';
	break;
	default: 
		echo '<h1> 403 - OI! What do you think you are doing? </h1>';
		echo '<div id="bodytext">';
	echo '<p> <strong>You are not allowed to be in here</strong>.</p>
 <p>Now this is interesting, you seem to be snooping in places you should not be? I hope you are not trying to hack this site! We\'ll give you the benefit of the doubt as you are so pretty</a>. I suggest you <a class="bold italic" href="'.SITEROOT.'">return to the homepage sharpish</a>';
	echo '<p> If there was something particular you were looking for then try the search bar above, that should help. </p>';
	break;
}
echo '</div>';
//Main content ends here
require_once('/assets/style/standard/footer.php');
?>
