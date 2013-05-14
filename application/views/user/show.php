<?php
	$self = $user->id == Auth::instance()->get_user()->id;
	$friend_status = Auth::instance()->get_user()->get_friend_status($user);
?>

<div class="row">
	<div class="user-data-container">
		
		<div class="user-show-container bg-tile">
			<div class="user-cover">
				<!--<span class="user-title"><?php echo $user->username; ?></span>-->
				<img class="img-polaroid cover-normal" src="<?php echo $user->get_cover(); ?>" title="<?php echo Tools::strappends($user->username); ?> profile">
			</div>
			<div class="user-meta">
				<h1><?php echo $user->username; ?></h1>
				<p><small title="<?php echo Tools::timestamptodescriptiveformat($user->creation); ?>">Joined on <?php echo Tools::timestamptoshortformat($user->creation); ?></small></p>
				<span title="Friends"><i class="icon-user"></i> Friends: <?php echo $friend_count; ?></span> | 
				<span title="Playlists"><i class="icon-align-justify"></i> Playlists: <?php echo $playlist_count; ?></span><br />
				<?php if(!$self) { ?>
					<a href="javascript:;" class="btn user-friend" title="" data-friend="<?php echo $friend_status; ?>" data-user-id="<?php echo $user->id; ?>">
						<i></i> <span>...</span>
					</a>
				<?php } ?>				
		   </div>
		</div>
		
		
		<div class="playlists widget widget-box">
			<div class="widget-header">
				<i class="icon-align-justify tag-collab"></i>
				<h3>Playlists</h3>
			</div>
			<div class="widget-content">
				<?php foreach($playlists as $p) { ?>
					<?php echo $p->get_cover_view('small'); ?>
				<?php } ?>
			</div>
		</div>
		
		
		<div class="playlists widget widget-box">
			<div class="widget-header">
				<i class="icon-align-justify tag-collab"></i>
				<h3>Following playlists</h3>
			</div>
			<div class="widget-content">
				<?php foreach($following_playlists as $p) { ?>
					<?php echo $p->get_cover_view('small'); ?>
				<?php } ?>
			</div>
		</div>		
		
	</div>
	<div class="user-friends-container">
		<?php echo $friends_view; ?>
	</div>
	
</div>

<script>
	$(document).ready(function() {
		$('.user-friend').btnLoadUserFriend();
	});	
</script>