<?php
	$limit = 3;
	$friend_count = $user->get_friends_count();
	$playlist_count = $user->get_playlists_count();
?>
<div class="user-box">
	<div class="user-padding">
		<?php echo $user->get_cover_view('small',true); ?>
		<div class="friend-meta">							
			<div class="meta-playlists">
				<span title="Playlists owned by <?php echo $user->username; ?>"><i class="icon-align-justify"></i></span>
				<?php 	$playlists = $user->get_playlists($limit);
						if(count($playlists) > 0) { 
							foreach($playlists as $p) {
								echo $p->get_cover_view('tiny');
							}
							if($playlist_count > $limit) {
								echo '<div class="more" title="Show all '.Tools::strappends($user->username).' playlists"><a href="'.$user->get_link().'">...</a></div>';
							}
						}
						else {
							echo '<img class="img-polaroid cover-tiny cover-dummy" src="'.Kohana::$config->load('settings.playlist-default-img').'" title="No playlists yet...">';
						}
				?>
			</div>
			<div class="meta-friends">
				<span title="Friends of <?php echo $user->username; ?>"><i class="icon-user"></i></span>
				<?php 	$friends = $user->get_friends($limit);
						if(count($friends) > 0) {
							foreach($friends as $fof) {
								if($fof->id !== $user->id || true) {
									echo $fof->get_cover_view('tiny');
								}
							} 
							if($friend_count > $limit) {
								echo '<div class="more" title="Show all '.Tools::strappends($user->username).' friends"><a href="'.$user->get_link().'">...</a></div>';
							}
						}
						else {
							echo '<img class="img-polaroid cover-mini cover-dummy" src="'.Kohana::$config->load('settings.user-default-img').'" title="No friends yet...">';
						}
				?>
			</div>
		</div>
	</div>
</div>	