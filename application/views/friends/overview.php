<div class="row">
	<div class="friends-container">
		<div id="widget-friends" class="widget widget-table">	
			<div class="widget-header">
				<i class="icon-user tag-social"></i> <h3>Friends</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content">					
				<?php foreach($friends as $f) { 
					
					$friend_count = $f->get_friends_count();
					$playlist_count = $f->get_playlists_count();
					
					?>
					<div class="friend-container bg-tile">
						<div class="friend-padding">
							<?php echo $f->get_cover_view('small',true); ?>
							<div class="friend-meta">							
								<div class="meta-playlists">
									<span title="Playlists owned by <?php echo $f->username; ?>"><i class="icon-align-justify"></i></span>
									<?php 	$playlists = $f->get_playlists($row_limit);
											if(count($playlists) > 0) { 
												foreach($playlists as $p) {
													echo $p->get_cover_view('tiny');
												}
												if($playlist_count > $row_limit) {
													echo '<div class="more" title="Show all '.Tools::strappends($f->username).' playlists"><a href="/user/playlists/'.$f->username.'">...</a></div>';
												}
											}
											else {
												echo '<img class="img-polaroid cover-tiny cover-dummy" src="'.Kohana::$config->load('settings.playlist-default-img').'" title="No playlists yet...">';
											}
									?>
								</div>
								<div class="meta-friends">
									<span title="Friends of <?php echo $f->username; ?>"><i class="icon-user"></i></span>
									<?php 	$friends = $f->get_friends($row_limit);
											if(count($friends) > 0) {
												foreach($friends as $fof) {
													if($fof->id !== $user->id || true) {
														echo $fof->get_cover_view('tiny');
													}
												} 
												if($friend_count > $row_limit) {
													echo '<div class="more" title="Show all '.Tools::strappends($f->username).' friends"><a href="/user/friends/'.$f->username.'">...</a></div>';
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
				<?php } ?>	
				<?php if(count($friends) == 0) { ?>
					<div class="no-friends">
						<a href="javascript:;" class="friend-search tag-socialx">Search</a> for friends or find users by <a href="/playlists/explore" class="tag-collabx">exploring</a> playlists!
					</div>
				<?php } ?>
			</div>
		
		</div>
	</div>
</div>

<script id="search-results-template" type="text/x-handlebars-template">
	
	
</script>

<script>
		
	$(document).ready(function() {
		
		$('.friend-search').click(function(e) {
			$('#nav-search').effect("shake", { times:3 }, 100).focus();
		});
		
		
	});	
	
	
</script>









