<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo strip_tags($meta_keywords) ?>" />
<meta name="description" content="<?php echo strip_tags($meta_description)?>" />
<meta content="Alexander Ward" name="author"/>
<meta content="english" name="language"/>
<meta content="general" name="rating"/>
<meta content="global" name="distribution"/>
<meta content="30 days" name="revisit-after"/>
<meta content="index, follow" name="robots"/>
<meta name="google-site-verification" content="AD33ynDFErJz5BnY7Bt5o_aRkuJl6LugS7_i2Y894nc" />
<meta name="verify-v1" content="X7MBU21OVg6pMrt1Xe7DKkiLYVEQDwWW+cmHDeSEZjE=" />
<link rel="shortcut icon" href="<?php echo SITEROOT ?>/favicon.ico" />
<link rel="apple-touch-icon-precomposed" href="<?php echo SITEROOT ?>/iphone-icon.png" />
<?php if ($devMode) { $devTag = '[DEV] '; }
if ($pageID == 1) { ?>
<title><?php echo $devTag ?><?php echo $sc_sitename ?>  |  <?php echo $meta_title ?></title>
<?php } else { ?>
<title><?php echo $devTag ?><?php echo $meta_title ?>  |  <?php echo $sc_sitename ?></title>
<?php } ?>
<!-- RSS Announce Feed -->
<link rel="alternate" type="application/rss+xml" title="Digital Fusion Magazine" href="<?php echo SITEROOT ?>/rss.php" /> 
<!-- Stylesheets -->
<link href="/assets/cdnfiles/global.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo SITEROOT ?>/assets/style/standard/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo SITEROOT ?>/assets/style/standard/print.css" rel="stylesheet" type="text/css" media="print" />

<!-- load assets from google CDN -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("chrome-frame", "1.0.2");
	google.load("jquery", "1.7.1");
//	google.load("swfobject", "2.2");
	google.load("webfont", "1.0.26");
</script>

<?php //Checks to see which modules are installed and includes the stylesheet if required

$modulecheck = "SELECT folder_name, stylesheet_name, active FROM core_modules";
$mcheck = mysql_query($modulecheck) or die('Failed to return data: ' . mysql_error());
while(list($folder_name, $stylesheet_name, $active) = mysql_fetch_array($mcheck, MYSQL_NUM))
{
if ($active == 1) { 
if (!empty($stylesheet_name)) {echo "<link href=\"" . SITEROOT . "/socket/modules/". $folder_name ."/styles/" . $stylesheet_name . "\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />\n"; }}

};



if ($mceInit == 1) { 

?>


<!-- TinyMCE -->
<script type="text/javascript" src="<?php echo SITEROOT ?>/assets/scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "simple",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template"
});
</script>
 <?php
}
?>

<script language="javascript" src="<?php echo SITEROOT ?>/assets/scripts/tweet/jquery.tweet.js" type="text/javascript"></script> 
<script type='text/javascript'>
    $(document).ready(function(){
        $(".tweet").tweet({
            username: "invasionmedia",
            join_text: "auto",
            avatar_size: 32,
            count: 3,
            auto_join_text_default: " ",
            auto_join_text_ed: " ",
            auto_join_text_ing: " ",
            auto_join_text_reply: " ",
            auto_join_text_url: " ",
            loading_text: "loading tweets..."
           });
    });
</script> 

<!--[if lt IE 8]> 
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" 
type="text/javascript"></script> 
<![endif]-->
<?php if ($module_ID == 2) { ?>
<!-- Digg button -->
<script type="text/javascript">
(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();
</script>
<?php } ?>
