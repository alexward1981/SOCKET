<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); // Selects the title and description fields from the contents table

// Processes a comment if it has been posted
if (!empty($_POST['submit'])) {
	if (!$_POST['user_comment']) {
		$message = '<div class="failure"><strong>Comment Empty!</strong><p>It appears that you did not post anything</p></div>';
	} else {
		//Validates the comment
		$profanity = array('/shit/i', '/fuck/i');
		$replacements = array('S**t', 'f**k');
		$filteredInput = addslashes(preg_replace($profanity, $replacements, $_POST['user_comment']));
		$insertArticleID = $_POST['articleID'];
		$insertUserID = $_POST['commenterID'];
		if ($_POST['inReplyTo']) {
			$inReplyTo = $_POST['inReplyTo'];
		} else { 
			$inReplyTo = 0;
		}
		
		// Checks for potential spammers and marks the post for moderation if suspect
		//Set blacklists
   		$badwords = "/(adult|beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|hardcode|jism|jiz|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx|viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|holdem|poker|carisoprodol|ciara|ciprofloxacin|debt|dating|porn|voyeur)/i";
   		$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|http|javascript)/i";
  		$bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer|T8Abot|Syntryx|WinHttp|WebBandit|nicebot)/i";
	   //Check for any bots
  	   if(preg_match($bots, $_SERVER['HTTP_USER_AGENT'])) {
     	 die("<p>Spam bots are not allowed.</p>");
   		}
   if(trim($_POST['user_comment']) == '' ) {
      $error['user_comment'] = "- You didn't enter your Full Name.<br>";
   } else if (preg_match($badwords, trim($_POST['user_comment'])) !== 0 || preg_match($exploits, trim($_POST['user_comment'])) !== 0) {
      $redFlag = 1;
   } else {
      $redFlag = 0;
   }	
		$commentInsert = "INSERT INTO module_stream_comments (commentDetail, articleID, inReplyTo, userID, timeStamp, modRequired) VALUES ('$filteredInput', '$insertArticleID', '$inReplyTo', '$insertUserID', now(), '$redFlag')";
	$posted = mysql_query($commentInsert) or ($message = '<div class="failure"><strong>Failure!</strong> Your comment was not added - ' . mysql_error() . '</div>');
	if ($posted) { 
		if (!$redFlag) {
	$message = '<div class="success"><strong>Comment Posted!</strong><p>Thanks for being a part of the solution</p></div>'; 
		} else {
	$message = '<div class="success"><strong>Comment Sent for Moderation!</strong><p>Thanks for your post, it has been sent for moderation</p></div>'; 
		}
	}
	}// check they typed something
} // end if submitted

if ($_GET['cat']) {
	$catlookup = "SELECT * FROM module_stream_categories WHERE (categoryID = '".$_GET['cat']."' OR categoryName = '".$_GET['cat']."')";
	$catdetails = mysql_query($catlookup) or die ('fained to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
}
$getTitle = mysql_query("SELECT articleID, articleTitle, permaLink, articleCat, articleAuthor, datePosted FROM module_stream WHERE (articleID = '".$_GET['article']."' OR permaLink = '".$_GET['article']."')");
$getTitleArray = mysql_fetch_array($getTitle, MYSQL_BOTH);
extract($getTitleArray, EXTR_PREFIX_ALL, "p");
	$module_ID = 2;
	$meta_title = "Comments";
	$meta_keywords = "$meta_key";
	$meta_description = "$dbcat_categoryDesc";
	$mceInit = 1;	//Activates TinyMCE
	$postTimestamp = convert_datetime($p_datePosted);
	$cleanDatePosted = date( 'l, j M Y', $postTimestamp);
	$special_crumb = '<a href="'. $siteroot . '/stream/'.strtolower($dbcat_categoryName).'">'.strtolower($dbcat_categoryName).'</a> <span class="divider"> &raquo; </span> <a href="' .$siteroot .'/stream/'.strtolower($dbcat_categoryName).'/'.$p_permaLink.'">'.stripslashes(html_entity_decode($p_articleTitle)).'</a>';
// imports header information
require_once('' . $serverroot . '/style/standard/head.php');
require_once('' . $serverroot . '/style/standard/head2.php');
require_once('' . $serverroot . '/style/standard/header.php');
// checks the databases and returns the authors avatar if one exists.
$userlookup = "SELECT usr_firstname, usr_surname, usr_username, usr_avatar FROM core_users WHERE userID =" . $p_articleAuthor;
$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> User Retrieval Failed! </h3>' . mysql_error());	
$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
extract($userdataArray, EXTR_PREFIX_ALL, "db");
if ($db_usr_avatar) {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$db_usr_avatar.'&amp;w=90&amp;h=90&amp;zc=c" alt="'.$db_usr_firstname.' '.$db_usr_surname.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$serverroot.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=50&amp;h=50&amp;zc=c" alt="'.$cu_usr_username.'\'s profile picture"/>';	
}
$authorLink = $siteroot.'/users/'.$db_usr_username;
echo '<div class="articleHeader">' . $userAvatar . '<h1>' . urldecode($p_articleTitle) . '</h1>';
echo '<div class="articleInfo">By <a href="'.$authorLink.'">'.$db_usr_firstname.' '.$db_usr_surname.'</a> | <a href="'. $siteroot . '/stream/'.strtolower($dbcat_categoryName).'">'.$dbcat_categoryName.'</a> | '.$cleanDatePosted.'</div>';
echo '</div>';
// Selects all the comments in the database which reply to the article
$allComments = mysql_query("SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE modRequired = 0 AND articleID =" . $p_articleID . " ORDER BY commentID");
$fullCommentCount = mysql_num_rows($allComments);
?>
<div class="articleTabs">
<ul> <li><a href="<?php echo $siteroot ?>/stream/<?php echo strtolower($dbcat_categoryName); ?>/<?php echo $p_permaLink; ?>"> Article </a></li> <li class="Cselected"> Comments (<?php echo $fullCommentCount ?>)</li></ul>
</div>
<div class="articleComments">

<?php
if ($_GET['cid']) {
$commentFormLabel = '<label for="user_comment">Post a reply</label>';
} else {
	$commentFormLabel = '<label for="user_comment">Post a comment</label>';
}
$commentForm = '
<form enctype="multipart/form-data" id="commentForm" action="/stream/comments/'.strtolower($_GET['cat']).'/'.$_GET['article'].'" method="post">
'.$commentFormLabel.'
	'.$message.'
	<input name="articleID" type="hidden" value="'.$p_articleID.'" />
	<input name="inReplyTo" type="hidden" value="'.$_GET['cid'].'" />
	<input name="commenterID" type="hidden" value="'.$_SESSION['userID'].'" />
     <textarea name="user_comment" id="user_comment" cols="75" rows="7">'.$_POST['user_comment'].'</textarea>
		<input class="submit" name="submit" type="submit" value="Post Comment" />
</form>';
// Selects all the comments in the database which reply to the article
$userComments = mysql_query("SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE inReplyTo = 0 AND modRequired = 0 AND articleID =" . $p_articleID . " ORDER BY commentID");
$commentCount = mysql_num_rows($userComments);
$comCount = 0;
while(list($c_commentID, $c_articleID, $c_userID, $c_commentDetail, $c_timeStamp, $c_modRequired) = mysql_fetch_array($userComments, MYSQL_NUM)) {
	$getCommenter = mysql_query("SELECT * FROM core_users WHERE userID =" . $c_userID);
	$commenterArray = mysql_fetch_array($getCommenter, MYSQL_BOTH);
	extract($commenterArray, EXTR_PREFIX_ALL, "cu");
$comRatio = count($likedBy) - count($dislikedBy);	
$comCount++;
$commentActions  = '<div class="commentActions">';
//$commentActions .= '<a href="javascript:likeComment(\''. $c_commentID.'\');"><img src="'.$siteroot.'/modules/stream/elements/btn_like.png" alt="Like comment" title="Like comment" /></a>';
//$commentActions .= '<a href="javascript:dislikeComment(\''. $c_commentID.'\');"><img src="'.$siteroot.'/modules/stream/elements/btn_dislike.png" alt="Dislike comment" title="Dislike comment" /></a>';
$commentActions .= '<a href="/stream/comments/'.strtolower($_GET['cat']).'/'.$_GET['article'].'/'.$c_commentID.'"><img src="'.$siteroot.'/modules/stream/elements/btn_reply.png" alt="reply to comment" title="reply to comment" /></a>';
$commentActions .= '</div>';
if ($cu_usr_avatar) {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$cu_usr_avatar.'&amp;w=50&amp;h=50&amp;zc=c" alt="'.$cu_usr_username.'\'s profile picture"/>';
} else {
$userAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$serverroot.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=50&amp;h=50&amp;zc=c" alt="'.$cu_usr_username.'\'s profile picture"/>';	
}
if(checkNum($comCount) === TRUE){
  echo '<div class="commentWrapper">';
} else {
 echo '<div class="commentWrapperALT">';
}
echo '<div class="commentProfile">';
echo '<a class="hidden" name="'.$c_commentID.'" id="'.$c_commentID.'">Comment #'.$comCount.'</a>';
if ($_SESSION['userID']) {
echo $commentActions; //Add the comment actions
}
echo '<a href="'.$siteroot.'/users/'.$cu_usr_username.'">'.$userAvatar.'</a>';
echo '<a class="name" href="'.$siteroot.'/users/'.$cu_usr_username.'">'.$cu_usr_username.'</a><br />';
$dateStamp = date( 'l, d M Y % H:i', convert_datetime($c_timeStamp));
echo '<span class="dateStamp">' . str_replace ('%', '<br />', $dateStamp) . '</span>';
echo '</div>';
echo '<div class="commentBox">';
echo $c_commentDetail;
echo '</div>';
if ($_GET['cid'] == $c_commentID) {
	echo '<div class="repliesWrapper">';
echo $commentForm;
echo '</div>';
			  }
echo '</div>';
// Selects all the comments in the database which reply to the above comment
$repCount = $comCount;
$userReply = mysql_query("SELECT commentID, articleID, userID, commentDetail, timeStamp, modRequired FROM module_stream_comments WHERE inReplyTo = ".$c_commentID." AND modRequired = 0 AND articleID =" . $p_articleID . " ORDER BY commentID");
$replyCount = mysql_num_rows($userReply);
if ($replyCount != 0) {
	echo '<div class="repliesWrapper">';
while(list($r_commentID, $r_articleID, $r_userID, $r_commentDetail, $r_timeStamp, $r_modRequired) = mysql_fetch_array($userReply, MYSQL_NUM)) {
	$getReplies = mysql_query("SELECT * FROM core_users WHERE userID =" . $r_userID);
	$repliesArray = mysql_fetch_array($getReplies, MYSQL_BOTH);
extract($repliesArray, EXTR_PREFIX_ALL, "ru");
$repCount++;
$replyActions  = '<div class="commentActions">';
//$replyActions .= '<a href="javascript:likeComment(\''. $r_commentID.'\');"><img src="'.$siteroot.'/modules/stream/elements/btn_like.png" alt="Like comment" title="Like comment" /></a>';
//$replyActions .= '<a href="javascript:dislikeComment(\''. $r_commentID.'\');"><img src="'.$siteroot.'/modules/stream/elements/btn_dislike.png" alt="Dislike comment" title="Dislike comment" /></a>';
$replyActions .= '<a href="/stream/comments/'.strtolower($_GET['cat']).'/'.$_GET['article'].'/'.$c_commentID.'"><img src="'.$siteroot.'/modules/stream/elements/btn_reply.png" alt="reply to comment" title="reply to comment" /></a>';
$replyActions .= '</div>';
if ($ru_usr_avatar) {
$ruserAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$ru_usr_avatar.'&amp;w=50&amp;h=50&amp;zc=c" alt="'.$ru_usr_username.'\'s profile picture"/>';
} else {
$ruserAvatar = '<img class="userAvatar" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$serverroot.'/socket/modules/users/avatars/no_avatar.jpg&amp;w=50&amp;h=50&amp;zc=c" alt="'.$ru_usr_username.'\'s profile picture"/>';	
}
echo '<div class="replyTo">';
if(checkNum($repCount) === TRUE){
  echo '<div class="commentWrapper">';
} else {
 echo '<div class="commentWrapperALT">';
}
echo '<div class="commentProfile">';
echo '<a class="hidden" name="'.$r_commentID.'" id="'.$r_commentID.'">Comment #'.$repCount.'</a>';
if ($_SESSION['userID']) {
echo $replyActions; //Add the reply actions
}
echo '<a href="'.$siteroot.'/users/'.$ru_usr_username.'">'.$ruserAvatar.'</a>';
echo '<a class="name" href="'.$siteroot.'/users/'.$ru_usr_username.'">'.$ru_usr_username.'</a><br />';
$dateStamp = date( 'l, d M Y % H:i', convert_datetime($r_timeStamp));
echo '<span class="dateStamp">' . str_replace ('%', '<br />', $dateStamp) . '</span>';
echo '</div>';
echo '<div class="commentBox">';
echo $r_commentDetail;
echo '</div>';
echo '</div>';
echo '</div>';
} // end reply loop
echo '</div>';
} // End the if replies

} // end comment loop
if (!$commentCount) { echo '<div class="articleCompact"><span>No comments yet</span></div>'; }
?>
</div><!-- Close articleDetail -->

<?php 
if (!$_GET['cid']) {
if ($_SESSION['userID']) {
echo $commentForm;
			  } else {
 echo '<div> <p> You must be logged in to post a comment. <a href="'.$siteroot.'/modules/users/login.php?r='.$_SERVER['SCRIPT_NAME'].'">Click here to login </a></p> </div>';
}
}
//Main content ends here
require_once('' . $serverroot . '/style/standard/footer.php');
?>