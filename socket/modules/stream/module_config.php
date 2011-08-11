<?php
// Extracts the configuration from the database and puts it into global variables.
$modConfig = mysql_query("SELECT * FROM module_stream_config");
$modConfigArray = mysql_fetch_array($modConfig, MYSQL_BOTH);
extract($modConfigArray, EXTR_PREFIX_ALL, "mcstream");

//Add This configuration
$addThisEnabled = TRUE;
$addThisUN = "digitalfusionmag";

?>