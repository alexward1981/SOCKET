<?php session_start(); 
$setSocket = 1; //Tells the page that it is inside the SOCKET environment
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); ?>
<?php // Checks to ensure the user is logged in, if they are not, redirect them to the login page
if (!isset($_SESSION['userID'])) { 
redirect_to("{$socketroot}/login.php"); 
} 
if ($gadminonly == 1) { 

	if ($_SESSION['usr_access_lvl'] != 0) 
	{
	$toast = '<div class="toast"> <p> SOCKET is currently undergoing maintainance: Your account will be reactivated shortly. We apologise for any inconvenience.</p> </div>';
	} else {
	$toast = '<div class="toast"> <p> Super admin mode </p> </div>';
	}
}

?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="noindex, nofollow" name="robots"/>
<title>Socket | Website Administration Software</title>
<link href="<?php echo $socketroot?>/templates/standard/socket.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php echo $socketroot ?>/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo $socketroot ?>/iphone-icon.png" />
<SCRIPT LANGUAGE="JavaScript">
function ClipBoard()
{
Copied = url.createTextRange();
Copied.execCommand("Copy");
}
</SCRIPT>
<script type="text/javascript">
function kfm_for_tiny_mce(field_name, url, type, win){
  window.SetUrl=function(url,width,height,caption){
   win.document.forms[0].elements[field_name].value = url;
   if(caption){
    win.document.forms[0].elements["alt"].value=caption;
    win.document.forms[0].elements["title"].value=caption;
   }
  }
  window.open('../../../kfm/index.php?mode=selector&type='+type,'kfm','modal,width=800,height=600');
}
</script>


<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo $siteroot ?>/Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	mode : "textareas",
	editor_deselector : "noMCE",
	theme : "advanced",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,syntaxhl",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,syntaxhl,|,visualchars,nonbreaking,template,pagebreak",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	external_image_list_url : "admin/tiny_mce_images.php",
    file_browser_callback : "kfm_for_tiny_mce",
	// Example content CSS (should be your site CSS)
	content_css : "css/example.css",
	remove_linebreaks : false, 
    extended_valid_elements : "pre[cols|rows|disabled|name|readonly|class]", 


	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",

	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
	
	
});
</script>
<?php //begin jQuery inclusions ?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js" type="text/javascript"></script>
<?php if ($twitterPage) { ?>
<!-- jQuery Validation Script - Only load on twitter page -->
<link rel="stylesheet" href="<?php echo $siteroot ?>/Scripts/formValidator/css/validationEngine.jquery.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo $siteroot ?>/Scripts/formValidator/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $siteroot ?>/Scripts/formValidator/js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?php echo $siteroot ?>/Scripts/formValidator/js/jquery.validationEngine.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript"> 
$(document).ready(function() {
        $("#twitter_form").validationEngine()
       });
</script>
<?php } ?>
<script type="text/javascript"> // Adds striping to tables
$(document).ready(function(){
$(".stripeMe tr").mouseover(function() {$(this).addClass("over");}).mouseout(function() {$(this).removeClass("over");});
$(".stripeMe tr:even").addClass("alt");
$(".stripeErr tr").mouseover(function() {$(this).addClass("over");}).mouseout(function() {$(this).removeClass("over");});
$(".stripeErr tr:even").addClass("alt");
$(".stripeDead tr").mouseover(function() {$(this).addClass("over");}).mouseout(function() {$(this).removeClass("over");});
$(".stripeDead tr:even").addClass("alt");
});
</script>

<script type="text/javascript"> // Expandable menu
function initMenus() {
	$('ul.menu ul').hide();
	$('ul.menu ul.active').show();
	$.each($('ul.menu'), function(){
		$('#' + this.id + '.expandfirst ul:first').show();
	});
	$('ul.menu li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if($('#' + parent).hasClass('noaccordion')) {
				$(this).next().slideToggle('normal');
				return false;
			}
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				if($('#' + parent).hasClass('collapsible')) {
					$('#' + parent + ' ul:visible').slideUp('normal');
				}
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
$(document).ready(function() {initMenus();});
</script>
  
 <script type="text/javascript"> // Password strength indicator
$.fn.passwordStrength = function( options ){
	return this.each(function(){
		var that = this;that.opts = {};
		that.opts = $.extend({}, $.fn.passwordStrength.defaults, options);
		
		that.div = $(that.opts.targetDiv);
		that.defaultClass = that.div.attr('class');
		
		that.percents = (that.opts.classes.length) ? 100 / that.opts.classes.length : 100;

		 v = $(this)
		.keyup(function(){
			if( typeof el == "undefined" )
				this.el = $(this);
			var s = getPasswordStrength (this.value);
			var p = this.percents;
			var t = Math.floor( s / p );
			
			if( 100 <= s )
				t = this.opts.classes.length - 1;
				
			this.div
				.removeAttr('class')
				.addClass( this.defaultClass )
				.addClass( this.opts.classes[ t ] );
				
		})
		.after('')
		.next()
		.click(function(){
			$(this).prev().val( randomPassword() ).trigger('keyup');
			return false;
		});
	});

	function getPasswordStrength(H){
		var D=(H.length);
		if(D>5){
			D=5
		}
		var F=H.replace(/[0-9]/g,"");
		var G=(H.length-F.length);
		if(G>3){G=3}
		var A=H.replace(/\W/g,"");
		var C=(H.length-A.length);
		if(C>3){C=3}
		var B=H.replace(/[A-Z]/g,"");
		var I=(H.length-B.length);
		if(I>3){I=3}
		var E=((D*10)-20)+(G*10)+(C*15)+(I*10);
		if(E<0){E=0}
		if(E>100){E=100}
		return E
	}

	function randomPassword() {
		var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$_+";
		var size = 10;
		var i = 1;
		var ret = ""
		while ( i <= size ) {
			$max = chars.length-1;
			$num = Math.floor(Math.random()*$max);
			$temp = chars.substr($num, 1);
			ret += $temp;
			i++;
		}
		return ret;
	}

};
	
$.fn.passwordStrength.defaults = {
	classes : Array('is10','is20','is30','is40','is50','is60','is70','is80','is90','is100'),
	targetDiv : '#passwordStrengthDiv',
	cache : {}
}
$(document)
.ready(function(){
	$('input[name="usr_password"]').passwordStrength();

});
</script>
<style>
.is0{background:url(<?php echo $siteroot?>/socket/elements/progressImg1.png) no-repeat 0 0;width:244px;height:14px;}
.is10{background-position:0 -14px;}
.is20{background-position:0 -28px;}
.is30{background-position:0 -42px;}
.is40{background-position:0 -56px;}
.is50{background-position:0 -70px;}
.is60{background-position:0 -84px;}
.is70{background-position:0 -98px;}
.is80{background-position:0 -112px;}
.is90{background-position:0 -126px;}
.is100{background-position:0 -140px;}
</style>
<script src="<?php echo $siteroot ?>/Scripts/idle-timer.js" type="application/javascript"></script>
<script type="application/javascript"> // Session idle timer / logout
$(function(){
 
	var $bar = $("#idletimeout"), // id of the warning div
		$countdown = $bar.find('span'), // span tag that will hold the countdown value
		redirectAfter = 120, // number of seconds to wait before redirecting the user
		redirectTo = '/socket/logout.php', // URL to relocate the user to once they have timed out
		keepAliveURL = '#', // URL to call to keep the session alive, if the link in the warning bar is clicked
		expiredMessage = 'Your session has expired.  You are being logged out for security reasons.', // message to show user when the countdown reaches 0
		running = false, // var to check if the countdown is running
		timer; // reference to the setInterval timer so it can be stopped
 
	// start the idle timer.  the user will be considered idle after 1 hour (3600000 ms)
	$.idleTimer(3600000);
 
	// bind to idleTimer's idle.idleTimer event
	$(document).bind("idle.idleTimer", function(){
 
		// if the user is idle and a countdown isn't already running
		if( $.data(document,'idleTimer') === 'idle' && !running ){
			var counter = redirectAfter;
			running = true;
 
			// set inital value in the countdown placeholder
			$countdown.html( redirectAfter );
 
			// show the warning bar
			$bar.slideDown();
 
			// create a timer that runs every second
			timer = setInterval(function(){
				counter -= 1;
 
				// if the counter is 0, redirect the user
				if(counter === 0){
					$bar.html( expiredMessage );
					window.location.href = redirectTo;
				} else {
					$countdown.html( counter );
				};
			}, 1000);
		};
	});
 
	// if the continue link is clicked..
	$("a", $bar).click(function(){
 
		// stop the timer
		clearInterval(timer);
 
		// stop countdown
		running = false;
 
		// hide the warning bar
		$bar.slideUp();
 
		// ajax call to keep the server-side session alive
		$.get( keepAliveURL );
 
		return false;
	});
});
</script>

<?php // end jQuery inclusions ?>
  </head>
<body id="socket">
<?php if($conn) { //Connection Test 
if ($toast) { 
echo $toast; 
if ($_SESSION['usr_access_lvl'] != 0) {exit();}
}
?>
<div id="idletimeout">
	You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
	<a href="#" style="color:#fff; font-weight:bold;">Click here to continue using this web page</a>.
</div>
<div class="failure" id="noscript"> <strong>SOCKET Failure: </strong><p>SOCKET requires JavaScript to run, please enable it in your browser.</p></div>
<div id="wrapper">
<div id="header">
<?php require_once('' . $serverroot . '/socket/modules/users/socket_profile_information_box.php'); ?>
<a href="<?php echo $siteroot ?>/socket/index.php"><img id="socketlogo" src="<?php echo $siteroot ?>/socket/elements/socket_logo.png" alt="SOCKET Logo" title="Return to SOCKET homepage" /></a>
<div id="primary_nav">

<a href="<?php echo $siteroot ?>/socket/index.php"> SOCKET Home </a><span class="divider"> &nbsp; </span><a href="<?php echo $siteroot ?>"> Return to website </a> <span class="divider"> &nbsp; </span><a href="<?php echo $siteroot ?>"> Contact support </a> <span class="divider"> &nbsp; </span><a href="<?php echo $siteroot ?>"> Module Catalogue </a>
</div>
</div>
<div id="container">

<?php 

} else { echo '<h1> Connection to the database has been lost!!</h1> <br /> Please <a href="mailto:'.$owner_email.'">Contact '.$owner_company_name.' </a> to let them know.'; } // End connection test
require_once('' . $serverroot . '/socket/templates/standard/menu_bar.php'); ?>
<div id="content_viewport">