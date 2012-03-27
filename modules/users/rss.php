<?php
//Pulls in the Globals
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); 

//This RSS page is included in any module which can use it please replace the name below with the name of the folder this module resides in
$modfolder = 'users';

// Selects the title and description fields from the contents table
$dblookup = "SELECT * FROM core_users WHERE userID='". $_GET['uid'] ."' OR usr_username = '".$_GET['username']."'";
$data = mysql_query($dblookup) or die('Failed to return data: ' . mysql_error());
$dataArray = mysql_fetch_array($data, MYSQL_BOTH);
extract($dataArray, EXTR_PREFIX_ALL, "dbu");
// Outputs users real name if available and the username if not.
if ($dbu_usr_firstname && $dbu_usr_surname) { $usr_realname = $dbu_usr_firstname .' '. $dbu_usr_surname; } else { $usr_realname = $dbu_usr_username; }

//Gets the data for the feed
$result = mysql_query("SELECT articleTitle, articleSummary, articleCat FROM module_blog WHERE articleAuthor =".$dbu_userID." ORDER BY articleID DESC LIMIT 10");
while ($line = mysql_fetch_assoc($result))
        {
            $return[] = $line;
        }

$now = date("D, d M Y H:i:s T");

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>".$usr_realname." @ ".$sc_sitename."</title>
                    <link>".SITEROOT."/socket/modules/".$modfolder."/rss.php</link>
					<description>".$sc_meta_desc ."</description>
                    <language>en-us</language>
                    <pubDate>$now</pubDate>
                    <lastBuildDate>$now</lastBuildDate>
                    <docs>".SITEROOT."</docs>
                    <managingEditor>".$sc_email."</managingEditor>
					<image> 
						<title>".$sc_sitename."</title>
						<url>".SITEROOT."/assets/images/logo.png</url>
						<link>".SITEROOT."</link>
					</image>
					\n";
            
foreach ($return as $line)
{
	// Returns the parent category of the article
	$catlookup = "SELECT categoryID, categoryName FROM module_blog_categories WHERE categoryID =" . $line['articleCat'];
	$catdetails = mysql_query($catlookup) or die ('failed to connect: ' .mysql_error());
	$catdata = mysql_fetch_array($catdetails, MYSQL_BOTH);
	extract($catdata, EXTR_PREFIX_ALL, "dbcat");
    $output .= "<item>\n";
	$output .= "	<title>".urldecode(stripslashes($line['articleTitle']))."</title>
	<link>".SITEROOT."/modules/".$modfolder."/article.php?cat=".$dbcat_categoryName."&amp;article=".urlencode($line['articleTitle'])."</link>
	<description>".htmlentities(strip_tags($line['articleSummary']))."</description>
</item>\n\n";
}
$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;
?>
