<?php
//Pulls in the Globals
require_once($_SERVER['DOCUMENT_ROOT'] . '/socket/globals.php'); 

//This RSS page is included in any module which can use it please replace the name below with the name of the folder this module resides in
$modfolder = 'blog';

//Gets the data for the feed
$query = "SELECT * FROM module_blog WHERE articlePosted = 1 ORDER BY articleID DESC LIMIT 10";
$result = mysql_query($query);

while ($line = mysql_fetch_assoc($result))
        {
            $return[] = $line;
        }

$now = date("D, d M Y H:i:s T");

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>".$sc_sitename."</title>
                    <link>".SITEROOT."/modules/".$modfolder."/rss.php</link>
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
	$output .= "	<title>".html_entity_decode(stripslashes($line['articleTitle']))."</title>
		<link>".SITEROOT."/blog/".strtolower($dbcat_categoryName)."/".$line['permaLink']."</link>
	<description>".htmlentities(strip_tags($line['articleSummary']))."</description>
</item>\n\n";
}
$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;
?>
