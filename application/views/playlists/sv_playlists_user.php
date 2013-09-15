<table class="table table-condensed playlist-list playlist-owns">
	<thead><tr><th colspan="2" class="playlist-header"><h3><?php echo $title; ?></h3></th></tr></thead>
	<?php foreach($playlists as $playlist) { 
			
			$type_owns = ($type == 'owns');
			$type_follows = ($type == 'follows');
				
			$play_count = $playlist->get_song_play_history(0,50);
			$play_count = $play_count['info']['total'];
			$follow_count = count($playlist->get_contributors()); 
			$song_count = count($playlist->get_songs()); 
			$following = Auth::instance()->get_user()->is_following_playlist($playlist);
	
			$owner = ($playlist->creator->id == Auth::instance()->get_user()->id);
			$owned_by = $owner ? 'You' : $playlist->creator->username;
			$follow = $following ? 'Unfollow' : 'Follow';
			$disabled =  $following ? 'active' : '';
			$href = $following ? '#' : '/playlist/show/'.$playlist->id;
			$icontitle = $following ? 'You have already joined this playlist' : 'Ask permission to join this playlist'; 
			$tags = explode(',', $playlist->genres); ?>
			
			<tr class="playlist-row">
				<td class="span12">
					<img class="span3 img-polaroid playlist-cover" src="<?php echo ($playlist->cover == '') ? '/assets/img/cover.gif' : $playlist->cover; ?>">
					
					<div class="span9">
						<!-- PLAYLIST INFO -->
						<h2 class="playlist-title"><a href="/playlist/show/<?php echo $playlist->id; ?>"><?php echo $playlist->name; ?></a></h2>					
						<?php if($type_follows) { ?>
							Owned by <a href="/user/show/<?php echo $playlist->creator->id; ?>"><?php echo $playlist->creator->username; ?></a><br />
						<?php } else { ?>
							Created on <acronym title="<?php echo date("d/m/Y-m-d H:i:s",  strtotime($playlist->created)); ?>"><?php echo date("d/m/Y",  strtotime($playlist->created)); ?></acronym><br />
						<?php }						
							$tagcontent = '';
							foreach($tags as $tag) { 
								if($tag !== '') { 
									$tagcontent .= '<span class="label label-warning">'.$tag.'</span> '; 
								}
							} 
							$tagcontent = ($tagcontent == '') ? '<br />' : $tagcontent;
							echo $tagcontent; 
						?>
						<div class="btn-group playlist-group-info">
							<a class="btn meta-button span3 disabled" title="<?php echo $play_count; ?> songs played"><span><i class="icon-play"></i> <?php echo $play_count; ?></span></a>
							<a class="btn meta-button span2 disabled" title="<?php echo $song_count; ?> songs"><span><i class="icon-music"></i> <?php echo $song_count; ?></span></a>
							<a class="btn meta-button span2 disabled" title="<?php echo $follow_count; ?> followers"><span><i class="icon-user"></i> <?php echo $follow_count; ?></span></a>
							<?php if($type_owns) { ?>						
								<?php if($following) { ?>
									<a href="#" class="btn btn-success meta-button span4 follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="yes"><i class="icon-ok-circle icon-white"></i> <span>Following</span></a>
								<?php } else { ?>
									<a href="#" class="btn meta-button span4 follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="no"><i class="icon-ok"></i> <span>Follow</span></a>
								<?php } ?>
							<?php } else if($type_follows) { ?>
								<?php if(!$owner) { ?>
									<?php if($following) { ?>
										<a href="#" class="btn btn-success meta-button span4 follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="yes"><i class="icon-ok-circle icon-white"></i> <span>Following</span></a>
									<?php } else { ?>
										<a href="#" class="btn meta-button span4 follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="no"><i class="icon-ok"></i> <span>Follow</span></a>
									<?php } ?>
								<?php } else { ?>
									<a class="btn btn-info meta-button span4 disabled" title="You own this playlist"><i class="icon-user icon-white"></i> Yours</a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</td>
			</tr>
			
	<?php } ?>
	<?php if(count($playlists) == 0) { ?>
		<tr><td><?php echo $no_playlist_message; ?></td></tr>
	<?php } ?>
</table>	