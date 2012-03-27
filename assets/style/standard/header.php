<?php if ($devMode == 1) { echo '<img class="float_left fixed" src="'.SITEROOT.'/assets/images/devModeFlag.png" alt="Dev Mode" title="This is the development site" />'; } ?>
<!DOCTYPE html>
<html lang="en">
     <head>
        <meta charset="utf-8">
		<meta name="description" content="Read design, technology and marketing articles written by two Yorkshire based web designer/developers. Register for FREE and become part of the community." />
		<meta content="Alexander Ward" name="author"/>
		<meta content="english" name="language"/>
		<meta content="general" name="rating"/>
		<meta content="global" name="distribution"/>
		<meta content="30 days" name="revisit-after"/>
		<meta content="index, follow" name="robots"/>
        <title>HTML5 Jumping off point</title>
        <link rel="stylesheet" href="<?php echo SITEROOT; ?>/assets/templates/standard/style.css" type="text/css" media="screen">
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("chrome-frame", "1.0.2");
			google.load("jquery", "1.7.1");
		</script>
        <script type="text/javascript" src="<?php echo SITEROOT; ?>/assets/scripts/custom.js"></script>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
     </head>
     <body <?php if ($pageID == 1) echo 'id="home"'; ?>>
		<header>
			<div class="container">
				<hgroup>
					<h1><a href="/">LXWord</a></h1>
					<h2> - Where my mind wanders to</h2>
				</hgroup>
				<div class="controls">
					<div class="profileBox">
						<img src="<?php echo SITEROOT; ?>/assets/images/temp/avatar-mini.jpg" class="avatar" alt="Alex Ward's profile picture" title="View your profile" />
						<a href="" class="profileLink">alexward1981</a>
						<ul class="profileMenu"> 
							<li>View Profile</li>
							<li>Logout</li>
						</ul>
					</div>
					<div class="searchShareBox">
						<div class="topIcons">
							<a href="http://www.twitter.com/digital_fusion" title="Follow us on Twitter" class="twitterLink"></a> <span class="hidden"> | </span>
							<a href="http://www.facebook.com/pages/Digital-Fusion-Mag/10150135774240361" title="Find us on Facebook" class="facebookLink"></a> <span class="hidden"> | </span>
							<a href="<?php echo SITEROOT; ?>/rss.php" title="Subscribe to our complete RSS feed" class="rssLink"></a>
						</div>
						<div class="search">
							<form action="<?php echo SITEROOT; ?> '/modules/search/results.php" method="post" id="topSearch">
								<fieldset>
									<label class="hidden" for="searchField">Search:</label>
									<input type="text" name="searchField" id="searchField" value="Search" />
									<label><input type="submit" class="searchButton" value="Go" /></label>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</header>
		<div id="banner">
			<div class="container">
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in velit sem. Duis aliquam sagittis erat, vitae pellentesque odio tempus et. Fusce purus justo, porta ut auctor id, placerat dictum arcu. In in sapien at tortor fringilla fermentum. Fusce nec placerat sapien. Praesent ipsum quam, vehicula eu blandit non, lobortis eu sem. Maecenas pulvinar feugiat est, eget suscipit eros convallis id. Donec ultrices posuere lacus.
				</p>
			</div>
		</div>
		<div id="mainBody">
			<div class="mainContent">
				<ul class="filter">
					<?php 
					$dblookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryName != 'News' AND isPrivate != 1";
					$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
					/* sorts the data into variables and puts them in an array ready to be called when needed */
					while(list($categoryID, $categoryName) = mysql_fetch_array($data, MYSQL_NUM)) 	{
					?>
					<li <?php if ($_GET['cat'] == strtolower($categoryName) || $_GET['cat'] == $categoryID || $dbcat_categoryName == strtolower($categoryName)) { echo 'class="selected"'; } ?>><a href="<?php echo SITEROOT ?>/blog/<?php echo strtolower($categoryName) ?>"><?php echo $categoryName ?></a></li>
					<?php 	}	?>
				</ul>

<?php 
if ($loginPage != 1) {
if ($_SESSION['fblogged']) {
	echo '<div class="fbc"> You are currently logged in using your Facebook account </div>';
	if (empty($_SESSION['usr_location'])) { // if the user does not have a complete profile. Give them the option to update it.
	echo '<div class="fbc"> Your profile is not complete. <a href="'.SITEROOT.'/modules/users/editprofile.php">Click here to edit it</a> </div>';
						}
	unset($_SESSION['fblogged']);
}
}
?>