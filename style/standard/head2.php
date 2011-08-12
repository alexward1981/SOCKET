</head>
<body <?php if ($pageID == 13) {echo 'onload="initialize()"';} ?> onload="initFB()">
<!-- Facebook connect -->
<?php if ($loginPage == 1) { ?>
<script type="text/javascript">
function initFB() {
	FB.init("<?php echo $api_key ?>", "<?php echo $siteroot ?>/modules/users/xd_receiver.htm",{"reloadIfSessionStateChanged":true});
}
</script>
<?php } else { ?>

<?php } ?>
<script type="text/javascript">
function initFB() {
	FB_RequireFeatures(["XFBML"], function(){
		FB.init("<?php echo $api_key ?>", "<?php echo $siteroot ?>/modules/users/xd_receiver.htm");
	});}
</script>
 <?php 
//If the user is logged in, display the socket access button
if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 5) { ?> 
<div class="socket_button">
<a class="noPrint" href="<?php echo $siteroot ?>/socket/index.php"><img class="float_right inline" src="http://www.socketcms.co.uk/cdn/socket/socket_icon.png" alt="Click here to access your SOCKET control panel" /></a></div>
<?php 
}
//Turns the site off and on.
if($_GET['site_status']) { $site_status = $_GET['site_status']; } else if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 3){ $site_status = 1; }
if (isset($_SESSION['userID']) && $_SESSION['usr_access_lvl'] <= 5) {?>

<?php 
echo '<div class="noPrint" id="site_status">';
switch($real_site_status) {

case 1: 
	echo '<span class="green bold size10font"> LIVE </span>';
break;
case 2: 
	echo '<span class="yellow bold size10font"> UNDER CONSTRUCTION </span>';
break;
case 3: 
	echo '<span class="red bold size10font"> DEACTIVATED </span>';
break;
case 4: 
	echo '<span class="yellow bold size10font"> UNDERGOING MAINTAINANCE </span>';
break;
}
echo '</div>';
}
?>
<?php
if($site_status == 2) { ?> 
<div id="outterwrapper">
<div id="UC_float"><img src="<?php echo $siteroot ?>/elements/uc.jpg" alt="Digital Fusion, coming soon.">
</div></div></div>
 <?php exit; } 
 if($site_status == 3) { ?>
<div id="deactivated">
 <img src="http://www.socketcms.co.uk/cdn/elements/logo_white.jpg" alt="Invasion Media" />
 <h1>WEBSITE DEACTIVATED!</h1>
 <p>This site has been deactivated by the Invasion Media Accounts Department</p><br>

 <p>If you are the site owner and would like the site to be reactivated please contact Invasion Media as soon as possible.</p>
 </div>
 <?php exit; } 
 
  if($site_status == 4) { ?>
<div id="outterwrapper">
<div id="UC_float"><img src="<?php echo $siteroot ?>/elements/um.jpg" alt="Digital Fusion, be back soon.">
</div></div></div>
 <?php exit; } 



 // Page markup starts here
 ?>