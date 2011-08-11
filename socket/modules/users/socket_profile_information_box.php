<div id="profile_information_box">
<div class="left">&nbsp;</div>
<table border="0">
  <tr>
    <td><?php echo '<p class="full_name">' . $_SESSION['usr_firstname'] . '&nbsp;' . $_SESSION['usr_surname'] . '</p>'; ?></td>
    <td class="righttd" width="100" rowspan="4"><?php 
	if($_SESSION['usr_avatar']) {
		echo '<img width="100" height="100" src="'.$siteroot.'/Scripts/phpThumb/phpThumb.php?src='.$_SESSION['usr_avatar'].'&amp;w=100&amp;h=100&amp;zc=c" alt="'.$_SESSION['username'].'"/>';
	} else {
	echo '<img width="100" height="100" src="'.$socketroot.'/modules/users/avatars/no_avatar.jpg" alt="No Profile Pic"/>';	
	}
	?></td>
  </tr>
  <tr>
    <td class="lefttd"><p class="very_small_bottom">Subscription Plan: </p><p class="small_bottom"><?php if ($subscription_tier >= 1){ echo $sub_package; } else { echo 'Invasion Staff'; }?></p></td>
    </tr>
  <tr>
    <td class="lefttd"><p class="very_small_bottom">Payment Due Date: </p><p class="small_bottom"> <?php if ($subscription_tier >= 1){echo addOrdinal($sub_due_date).' of each month';} else { echo 'Not Applicable'; } ?> </p><div id="button_holder">
<p class="button">
<a href="<?php echo $socketroot; ?>/modules/users/admin_users_edit.php?ID=<?php echo $_SESSION['userID']; ?>
">Edit profile</a></p><p class="button"><a href="<?php echo $socketroot; ?>/logout.php">Logout</a></p></div></td>
    </tr>
</table>
<div class="right">&nbsp;</div>
</div>