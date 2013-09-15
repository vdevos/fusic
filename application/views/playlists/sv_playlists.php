<?php 
	$count = 1;
	$playlist_count = count($playlists);
	
	$dummy_count = 1;
	/*
	if(($playlist_count % 5) !== 0) {
		$dummy_count = (5 - ($playlist_count % 5));
	}
	*/
	$type_owns = ($type == 'owns');
	$type_follows = ($type == 'follows');
	$typeid = $type_owns ? "yours" : "following";
	
?>

<div id="widget-playlist" class="widget widget-table">
	
	<div class="widget-header">
		<i class="<?php echo $icon; ?> tag-collab"></i>
		<h3><?php echo $title; ?></h3>
	</div> <!-- /widget-header -->
	
	<div class="widget-content">				
			<?php foreach($playlists as $playlist) { 
					$lastitem = ($count == count($playlists)); ?>
		
					<div class="playlist-item bg-tile">
						<div class="playlist-title widget-header-mini">
							<?php if($type_owns) { ?>
								<a class="playlist-edit" href="/playlists/edit/<?php echo $playlist->id; ?>">
									<i class="icon-edit tag-collabx"></i> </a>
							<?php } /* TYPE OWNS */ ?>
							<a class="playlist-open" href="/playlists/show/<?php echo $playlist->id; ?>">
								<?php echo $playlist->name; ?></a>
						</div>
						<!--
						<span class="playlist-info">
							<?php if($type_follows) { ?>
								Owned by <a href="/user/show/<?php echo $playlist->creator->id; ?>"><?php echo $playlist->creator->username; ?></a><br />
							<?php } else { /* TYPE FOLLOWS */ ?>
								Created on <acronym title="<?php echo date("d/m/Y-m-d H:i:s",  strtotime($playlist->created)); ?>">
									<?php echo date("d/m/Y",  strtotime($playlist->created)); ?></acronym><br />
							<?php } ?>
						</span>-->
						<div class="playlist-cover-container">
							<?php echo $playlist->get_cover_view('normal'); ?>							
							<div class="playlist-buttons btn-toolbar">
								<div class="btn-group type-<?php echo $type; ?>">
									<?php if($type_owns) { ?>
										<?php if(!$playlist->is_public()) { ?>
											<a class="btn meta-button btn-info playlist-lock" title="Make playlist public" 
														data-playlist-id="<?php echo $playlist->id; ?>" data-locked="yes">
												<i class="icon-lock icon-white"></i> <span>Private</span></a>
										<?php } else { ?>
											<a class="btn meta-button btn-success playlist-lock" title="Make playlist private" 
														data-playlist-id="<?php echo $playlist->id; ?>" data-locked="no">
												<i class="icon-ok-circle icon-white"></i> <span>Public</span></a>
										<?php } ?>
									<?php } else if($type_follows) { ?>
										<a class="btn meta-button btn-success follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" 
											data-following="yes">
											<i class="icon-ok-circle icon-white" title="Unfollow playlist"></i> <span>Following</span></a>
									<?php } ?>	
								</div>
							</div>		
						</div>									
					</div>	
				<?php $count++; } ?>
				<?php if($type_owns) { for($i = 0; $i < $dummy_count; $i++) { ?>
					<div class="playlist-item<?php echo (count($playlists) == 0) ? '' : ' dummy-item'; ?> bg-tile">
						<div class="playlist-title widget-header-mini" title="Click to add a new playlist">
							<a href="/playlists/create">Add new playlist</a>
						</div>
						<div class="playlist-cover-container">	
							<?php echo ORM::factory('playlist')->get_cover_view('normal', true); ?>	
							<div class="playlist-buttons btn-toolbar">
								<div class="btn-group type-<?php echo $type; ?>">
									<a class="btn meta-button disabled">
										<i class="icon-ok-circle icon-white"></i> <span>Public</span>
									</a>
								</div>
							</div>		
						</div>									
					</div>	
				<?php }} ?>
			
				<?php if(count($playlists) == 0 && $type_follows) { ?>
					<div class="alert alert-info"><?php echo $no_playlist_message; ?></div>
				<?php } ?>
			</div>
			</tbody>
		</table>	
	</div> <!-- /widget-content -->
</div>