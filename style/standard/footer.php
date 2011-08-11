</div>
<?php require_once($serverroot . '/style/standard/modulebar.php'); ?>
<div class="clear noPrint"></div>
<div id="footer" class="noPrint">
<ul class="left">
    
    <?php
	if (!$_SESSION['userID']) {
	?>
    <li><a href="<?php echo $siteroot?>/register.php" title="Register an Account">Register</a></li>
    <li><a href="<?php echo $siteroot?>/login.php" title="Member Login">Login</a></li>
    <?php
	} else {
 if (!$fb_user) { // If the user has connected using Facebook Connect they cannot logout via the site?>
     <li><a href="<?php echo $siteroot?>/logout.php" title="Logout">Log out</a></li>
<?php } else { ?>
      <li><a href="#" onclick="FB.Connect.logoutAndRedirect('<?php echo $siteroot?>/logout.php')"  title="Logout via Facebook">Logout via Facebook</a></li>
   <?php } 
	}
	?>
    <li><a href="<?php echo $siteroot?>/authors.php" title="Our Authors">Our Authors</a></li>
    <li><a href="<?php echo $siteroot?>/users.php" title="All Users">All Users</a></li>
    <li><a href="<?php echo $siteroot?>/advertising.php" title="Advertising">Advertising</a></li>
    <li><a href="<?php echo $siteroot?>/blog/news" title="News">News</a></li>
    <li><a href="<?php echo $siteroot?>/contactus.php" title="Contact Us">Contact Us</a></li>
    <li style="color: #FFFF00;">BETA</li>
</ul>
<div class="right">
    All Content &copy; <?php echo $sc_sitename; ?> <?php echo date(Y);?>
</div>
</div>
</div><!-- close maincontent div-->
</div><!-- close wrapper div -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15109319-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
