<?php 
// BEGIN SOCKET IMPLEMENTATION 
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php');
if ($_GET['article']) {
	if ($_GET['revision']) { // If viewing a revision from SOCKET
		$dblookup = "SELECT articleID, datePosted, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, articleCat FROM module_blog_revisions WHERE(revisionID ='".$_GET['revision']."') LIMIT 1";		
	} else {
		$dblookup = "SELECT articleID, datePosted, articleTitle, permaLink, articleBody, articleSummary, articleImage, articleImagePos, articleImageAlt, articleAuthor, articleCat FROM module_blog WHERE(articleID ='".$_GET['article']."' OR permaLink ='".$_GET['article']."') LIMIT 1";
	}
	$dataScraper = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());	
	$data = mysql_fetch_array($dataScraper, MYSQL_BOTH);
	extract($data, EXTR_OVERWRITE);
	$meta_title = html_entity_decode(stripslashes("$articleTitle"));
	$meta_keywords = $default_keywords ;
	$meta_description = "$articleSummary";
	$module_ID = 2;
	$mceInit = 1;	//Activates TinyMCE
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryID =" . $articleCat;
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
	if ($_GET['short'] == 1) redirect_to($mainURL."/blog/".strtolower($dbcat_categoryName)."/".$permaLink);
	$special_crumb = '<a href="'. SITEROOT . '/blog/'.$dbcat_categoryName.'">'.$dbcat_categoryName.'</a>';
	// Insert the header information
	require_once('' . SERVERROOT . '/assets/style/standard/head.php');
	echo '<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:\'0af7abb9-0ce7-49b9-8400-5387910df340\'});</script>';
	require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
	// Create a hidden thumbnail of the article image for Social sharing sites
	if (!$articleImagePos) $articleImagePos = 'c';
	echo '<link rel="image_src" href="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?w=100&h=100&zc='.$articleImagePos.'&src='.$articleImage.'" />';
	require_once('' . SERVERROOT . '/assets/style/standard/header.php');
	$postTimestamp = convert_datetime($datePosted);
	$cleanDayPosted = date( 'j', $postTimestamp);
	$cleanMonthPosted = date( 'M', $postTimestamp);
	$cleanYearPosted = date( 'y', $postTimestamp);
	$getComments = mysql_query("SELECT commentID FROM module_blog_comments WHERE articleID =" . $articleID);
	$commentCount = mysql_num_rows($getComments);
	/****************************/
	/*    Page starts here 		*/
	/****************************/
	echo '
	<article>
		<div class="articleInfo">
			<div class="dateComments">
				<span class="d">'.$cleanDayPosted.'</span>
				<span class="my">'.$cleanMonthPosted.' '.$cleanYearPosted.'</span>
				<div class="comments">
					<a href="">0</a>
				</div>
			</div>
		</div>';
		include_once(SERVERROOT . '/socket/modules/breadcrumbs/breadcrumbs.php') ;
		//echo '<img class="hidden" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.SITEROOT.'/assets/scripts/timthumb/timthumb.php?w=100&h=100&zc='.$articleImagePos.'&src='.$articleImage.'" />';
		// checks the databases and returns the authors avatar if one exists.
		$userlookup = "SELECT usr_firstname, usr_surname, usr_username, usr_avatar FROM core_users WHERE userID =" . $articleAuthor;
		$userdata = mysql_query($userlookup) or die('<h3 style="color:red"> Local retrieval Failed! </h3>' . mysql_error());	
		$userdataArray = mysql_fetch_array($userdata, MYSQL_BOTH);
		extract($userdataArray, EXTR_PREFIX_ALL, "db");
		$authorLink = SITEROOT.'/users/'.$db_usr_username;
		echo '<h1>' . stripslashes(html_entity_decode($articleTitle)) . '</h1>';
		if (!$articleImageAlt) $articleImageAlt = urldecode($articleTitle);
		echo '<div class="articleDetail">';
		echo '<div class="articleSummary">' . stripslashes($articleSummary) .'</div>';
		if ($articleImage) {
			echo '<img class="firstImage" src="'.SITEROOT.'/assets/scripts/timthumb/timthumb.php?src='.$articleImage.'&amp;w=621&amp;h=159" alt="'.$articleImageAlt.'" title="'.urldecode($articleTitle).'" />';
		}
		echo '<div class="mainBody">';
		echo stripslashes($articleBody);
		echo '</div>';
		// Add poll module
		require_once(SERVERROOT.'/modules/poll/widget_article_results.php');
?>
	</article>
	<?php if ($addThisEnabled == TRUE) { // Begin Add this code ?>
	<section>
		<div class="addthis_toolbox addthis_default_style noPrint">
			<a class="addthis_button_twitter"></a>
			<a class="addthis_button_facebook"></a>
			<a class="addthis_button_digg"></a>
			<a class="addthis_button_print"></a>
			<span class="addthis_separator">|</span>
			<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=digitalfusionmag" class="addthis_button_expanded">More</a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=digitalfusionmag"></script></span>
	</section>
	<?php }
	$commentForm = '
	<section>
	<form class="noPrint" enctype="multipart/form-data" id="commentForm" action="/blog/comments/'.strtolower($_GET['cat']).'/'.$_GET['article'].'" method="post">
		<label for="user_comment">Post a comment</label>
		'.$message.'
		<input name="articleID" type="hidden" value="'.$articleID.'" />
		<input name="commenterID" type="hidden" value="'.$_SESSION['userID'].'" />
		<textarea name="user_comment" id="user_comment" cols="75" rows="7">'.$_POST['user_comment'].'</textarea>
		<input class="submit" name="submit" type="submit" value="Post Comment" />
	</form>';
	echo '</section>';
	if ($_SESSION['userID']) echo $commentForm;
} else {
	// Insert the header information
	require_once('' . SERVERROOT . '/assets/style/standard/head.php');
	require_once('' . SERVERROOT . '/assets/style/standard/head2.php');
	require_once('' . SERVERROOT . '/assets/style/standard/header.php');
	echo '<div> <h1>Thank you for visiting '.$sc_sitename.'. </h1>  <p> It appears you have accessed this page through invalid means, <a href="'.SITEROOT.'">please click here to return to the home page</p></a></div>';
}
//Main content ends here
require_once('' . SERVERROOT . '/assets/style/standard/footer.php');
?>