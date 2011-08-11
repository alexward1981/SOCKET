<?php if ($devMode == 1) { echo '<img class="float_left fixed" src="'.$siteroot.'/elements/devModeFlag.png" alt="Dev Mode" title="This is the development site" />'; } ?>
<div id="wrapper">
<div id="header">
  <div id="logo" class="noPrint"><a href="<?php echo $siteroot ?>" title="Digital Fusion web &amp; tech ezine"><img src="<?php echo $siteroot ?>/elements/logo.png" width="232" height="56" alt="Digital Fusion" /></a></div>
  <div class="noPrint" id="topIcons">
  	<a href="http://www.twitter.com/digital_fusion" title="Follow us on Twitter" class="twitterLink"></a> <span class="hidden"> | </span>
    <a href="http://www.facebook.com/pages/Digital-Fusion-Mag/10150135774240361" title="Find us on Facebook" class="facebookLink"></a> <span class="hidden"> | </span>
    <a href="<?php echo $siteroot ?>/rss.php" title="Subscribe to our complete RSS feed" class="rssLink"></a>
  </div>
  <!-- Close topIcons div -->
  <div class="noPrint" id="search">
    <form action="<?php echo $siteroot ?>/modules/search/results.php" method="post" id="topSearch">
      <fieldset>
        <label class="hidden" for="searchField">Search:</label>
        <input type="text" name="searchField" id="searchField" value="Search" onfocus="this.value=''" />
        <label><input type="submit" class="searchButton" value="Go" /></label>
      </fieldset>
    </form>
  </div>
  <!-- close search div -->
  <div id="mainMenu" class="noPrint">
    <ul>
    <li <?php if (!$_GET['cat']) { echo 'class="selected"'; } ?>> <a href="<?php echo $siteroot; ?>"> Home </a> </li>
<?php 
$dblookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryName != 'News' AND isPrivate != 1";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
/* sorts the data into variables and puts them in an array ready to be called when needed */
while(list($categoryID, $categoryName) = mysql_fetch_array($data, MYSQL_NUM))
{
?>
<li <?php if ($_GET['cat'] == strtolower($categoryName) || $_GET['cat'] == $categoryID || $dbcat_categoryName == strtolower($categoryName)) { echo 'class="selected"'; } ?>><a href="<?php echo $siteroot ?>/blog/<?php echo strtolower($categoryName) ?>"><?php echo $categoryName ?></a></li>
<?php
}
?>
    </ul>
  </div>
  <!-- Close mainMenu div -->
</div>
<!-- Close header div -->
<?php include_once($serverroot . '/socket/modules/breadcrumbs/breadcrumbs.php') ?>
<div id="main_content">
<div id="leftColumn">
<?php 
if ($loginPage != 1) {
if ($_SESSION['fblogged']) {
	echo '<div class="fbc"> You are currently logged in using your Facebook account </div>';
	if (empty($_SESSION['usr_location'])) { // if the user does not have a complete profile. Give them the option to update it.
	echo '<div class="fbc"> Your profile is not complete. <a href="'.$siteroot.'/modules/users/editprofile.php">Click here to edit it</a> </div>';
						}
	unset($_SESSION['fblogged']);
}
}
?>