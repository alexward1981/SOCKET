<div class="noPrint" id="rightColumn">
        <ul>
        	<?php if (!$_SESSION['userID']) { ?>
            <li><a href="<?php echo $siteroot?>/login.php?r=<?php echo $_SERVER['REQUEST_URI'] ?>" title="Login">Login / Register</a></li>
			<?php	} else { ?>
     		<?php if (!$fb_user) { // If the user has connected using Facebook Connect they cannot logout via the site?>
            <li><a href="<?php echo $siteroot?>/modules/users/logout.php" title="Logout">Log out</a></li>
			<?php } else { ?>
            <li><a href="#" onclick="FB.Connect.logoutAndRedirect('<?php echo $siteroot?>/modules/users/logout.php')">Log out via Facebook</a></li>
            <?php } 
			$findUserName = mysql_query("SELECT usr_username FROM core_users WHERE userID =" . $_SESSION['userID']);
			$findUserArray = mysql_fetch_array($findUserName, MYSQL_BOTH);
			extract($findUserArray, EXTR_PREFIX_ALL, "foundyou");
			?>
        	<li><a href="<?php echo $siteroot?>/users/<?php echo $foundyou_usr_username;?>" title="My Profile">My Profile</a></li>
            <?php } ?>
            <?php if ($_SESSION['userID'] && $_SESSION['usr_access_lvl'] <= 2) { ?>
            <li><a href="<?php echo $siteroot?>/socket/modules/blog/admin_blog_add.php" title="Add new post">Add a New Post</a></li>
            <?php } ?>
        	<li><a href="<?php echo $siteroot?>/aboutus.php" title="About Us">About Us</a></li>
            <li><a href="<?php echo $siteroot?>/authors.php" title="Our Authors">Our Authors</a></li>
        </ul>     
<?php 
require_once($serverroot.'/modules/adsense/widget_blockOne.php');

require_once($serverroot.'/modules/poll/widget_poll.php');

if ($devMode != 1) { // Add a google adSense banner
require_once($serverroot.'/modules/adsense/widget_large_square.php');
}
?>
</div> 