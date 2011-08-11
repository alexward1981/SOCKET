<?php
// Extracts the configuration from the database and puts it into global variables.
$modConfig = mysql_query("SELECT * FROM module_blog_config");
$modConfigArray = mysql_fetch_array($modConfig, MYSQL_BOTH);
extract($modConfigArray, EXTR_PREFIX_ALL, "mcblog");

//Add This configuration
$addThisEnabled = TRUE;
$addThisUN = "digitalfusionmag";

?>