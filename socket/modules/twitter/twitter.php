<?php
$twitterAuthors = mysql_query("SELECT usr_twitter FROM core_users WHERE usr_access_lvl <=4");
$t = mysql_num_rows($twitterAuthors);
while(list($usr_twitter) = mysql_fetch_array($twitterAuthors, MYSQL_NUM)) {
$tweetCount++;
$twits .= $usr_twitter;
if ($tweetCount != $t) {
	$twits .= ' ';
}
}
/*
	Parse Twitter Feeds
*/
	$usernames = $twits;
	$limit = 6;
	$username_for_feed = str_replace(" ", "+OR+from%3A", $usernames);
	$feed = "http://search.twitter.com/search.atom?q=from%3A" . $username_for_feed . "&rpp=" . $limit;
	$usernames_for_file = str_replace(" ", "-", $usernames);
	$cache_file = dirname(__FILE__).'/cache/' . $usernames_for_file . '-twitter-cache';
	$last = filemtime($cache_file);
	$now = time();
	$interval = 600; // ten minutes
	// check the cache file
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache file doesn't exist, or is old, so refresh it
		$cache_rss = file_get_contents($feed);
		if (!$cache_rss) {
			// we didn't get anything back from twitter
			echo "<!-- ERROR: Twitter feed was blank! Using cache file. -->";
		} else {
			// we got good results from twitter
			echo "<!-- SUCCESS: Twitter feed used to update cache file -->";
			$cache_static = fopen($cache_file, 'wb');
			fwrite($cache_static, serialize($cache_rss));
			fclose($cache_static);
		}
		// read from the cache file
		$rss = @unserialize(file_get_contents($cache_file));
	}
	else {
		// cache file is fresh enough, so read from it
		echo "<!-- SUCCESS: Cache file was recent enough to read from -->";
		$rss = @unserialize(file_get_contents($cache_file));
	}
	// clean up and output the twitter feed
	$feed = str_replace("&amp;", "&", $rss);
	$feed = str_replace("&lt;", "<", $feed);
	$feed = str_replace("&gt;", ">", $feed);
	$clean = explode("<entry>", $feed);
	$clean = str_replace("&quot;", "'", $clean);
	$clean = str_replace("&apos;", "'", $clean);
	$amount = count($clean) - 1;
	if ($amount) { // are there any tweets?
		for ($i = 1; $i <= $amount; $i++) {
			$entry_close = explode("</entry>", $clean[$i]);
			$clean_content_1 = explode("<content type=\"html\">", $entry_close[0]);
			$clean_content = explode("</content>", $clean_content_1[1]);
			$clean_name_2 = explode("<name>", $entry_close[0]);
			$clean_name_1 = explode("(", $clean_name_2[1]);
			$clean_name = explode(")</name>", $clean_name_1[1]);
			$clean_user = explode(" (", $clean_name_2[1]);
			$clean_lower_user = strtolower($clean_user[0]);
			$clean_uri_1 = explode("<uri>", $entry_close[0]);
			$clean_uri = explode("</uri>", $clean_uri_1[1]);
			$clean_time_1 = explode("<published>", $entry_close[0]);
			$clean_time = explode("</published>", $clean_time_1[1]);
			$twitterPics = mysql_query("SELECT usr_avatar FROM core_users WHERE usr_twitter = '".$clean_lower_user."'");
			list($usr_avatar) = mysql_fetch_array($twitterPics, MYSQL_NUM);
				?>
<div class="tweet noPrint"><a href="http://www.twitter.com/<?php echo $clean_lower_user; ?>"><img src="<?php echo SITEROOT ?>/assets/scripts/timthumb/timthumb.php?src=<?php echo $usr_avatar ?>&amp;w=40&amp;h=40&amp;zc=c" title="View <?php echo $clean_lower_user; ?>'s profile" alt="<?php echo $clean_lower_user; ?>'s profile picture" /></a><span><?php echo $clean_content[0]; ?></span></div>
<?php }
	} else { // if there aren't any tweets
		?>
<div class="tweet noPrint"><span> It's hard to believe but we have not posted any tweets lately. I hope we are not dead!</span> </div>
<?php
	}
?>
