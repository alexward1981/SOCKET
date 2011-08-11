<div id="menu_bar">
<ul id="navigation" class="menu noaccordion">

<?php 
$modulecheck = "SELECT moduleID, folder_name, display_name, has_menu, access_control, installed, active FROM core_modules ORDER BY menuPos ASC";
$mcheck = mysql_query($modulecheck) or die('Failed to return data: ' . mysql_error());
while(list($moduleID, $folder_name, $display_name, $has_menu, $access_control, $installed, $active) = mysql_fetch_array($mcheck, MYSQL_NUM))
{
	if ($active && $has_menu == 1) 
	{ 
		if ($access_control >= $_SESSION['usr_access_lvl']) 
		{
		 	?>
    		<li>
				<a href="#"><?php echo $display_name; ?><img class="float_right" src="<?php echo $socketroot ?>/elements/buttons/expand_menu.png" width="16" height="16" alt="Expand Menu" /></a>
					<ul<?php if ($moduleID == $current_module) { echo ' class="active"'; } ?>>
						<?php 

							$menucheck = "SELECT moduleID, menu_item_name, menu_path, access_control FROM core_modules_menus ORDER BY menuID ASC";
							$menulist = mysql_query($menucheck) or die('Failed to return data: ' . mysql_error());
							while(list($moduleID2, $menu_item_name, $menu_path, $access_control2) = mysql_fetch_array($menulist, MYSQL_NUM))
							{
								if ($access_control2 >= $_SESSION['usr_access_lvl'] && $moduleID == $moduleID2 ) 
								{
									?>
    								<li><a href="<?php echo $siteroot?>/socket/modules/<?php echo $folder_name . '/' . $menu_path ?>"><?php echo $menu_item_name ?></a></li>
    								<?php	
								}
							}
					echo '</ul>';
		echo '</li>';
		}
	}
}
?>
</ul>	
</div>
