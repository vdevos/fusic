<?php
	$friend_count = $user->get_friends_count();
	$playlist_count = $user->get_playlists_count();
?>

<?php if($size == 'tiny' || $size == 'tinytiny') { ?>
	<a class="cover-link" href="/user/show/<?php echo $user->username; ?>" title="Show <?php echo Tools::strappends($user->username); ?> profile">
		<?php if($size == 'tiny') { ?>
		<div class="cover-hover cover-<?php echo $size; ?>">
			<div class="hover-info">
				<div class="hover-item-user"><i class="icon-user tag-social"></i></div>						
			</div>
		</div>
		<img class="img-polaroid cover cover-<?php echo $size; ?>" src="<?php echo $user->get_cover(); ?>">								
		<?php } else { ?>
		<img class="img cover cover-<?php echo $size; ?>" src="<?php echo $user->get_cover(); ?>">
		<?php } ?>
	</a>
<?php } else { ?>
	<a class="cover-link" href="/user/show/<?php echo $user->username; ?>" title="Show <?php echo Tools::strappends($user->username); ?> profile">
		<div class="cover-hover cover-<?php echo $size; ?>">
			<div class="hover-info">
				<div class="hover-item-friends" title="<?php echo $user->username.' has '.$friend_count.' friends'; ?>"><i class="icon-user tag-social"></i> <span><?php echo $friend_count; ?></span></div>
				<div class="hover-item-playlists" title="<?php echo $user->username.' has '.$playlist_count.' playlists'; ?>"><i class="icon-align-justify tag-collab"></i> <span><?php echo $playlist_count; ?></span></div>						
			</div>
		</div>
		<?php if($username) { echo '<span class="friend-title">'. $user->username .'</span>'; } ?>
		<img class="img-polaroid cover cover-<?php echo $size; ?>" src="<?php echo $user->get_cover(); ?>">								
	</a>
<?php } ?>