<?php 
// Gets the breadcrumbs for the current page
$this_page = basename($_SERVER['REQUEST_URI']);
if (strpos($this_page, "?") !== false) $this_page = reset(explode("?", $this_page));
if ($_SERVER['REQUEST_URI'] != '/') {
if ($special_crumb) { $special_crumb = $special_crumb . '<span class="divider"> » </span>';  } //checks for an extra level (specified on page)
	$breadcrumb = '<a class="bold" href="'.$siteroot.'">Home</a><span class="divider"> » </span>' . $special_crumb . $meta_title;
} else {
$breadcrumb = 	'<a class="bold" href="'.$siteroot.'">Home</a>';
}

?>

<div class="noPrint" id="breadcrumbs"><?php echo $breadcrumb; ?></div>
