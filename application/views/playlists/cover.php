<?php if($size == 'tiny' || $size == 'tinytiny') { ?>
	
	<a class="cover-link" href="/playlists/show/<?php echo $playlist->id; ?>" title="Open playlist: <?php echo $playlist->name; ?>">
		<?php if($size == 'tiny') { ?>
		<div class="cover-hover cover-<?php echo $size; ?>">
			<div class="hover-info">
				<div class="hover-item-follows"><i class="icon-play tag-collab"></i></div>
			</div>
		</div>
		<img class="img-polaroid cover cover-<?php echo $size; ?>" src="<?php echo $playlist->get_cover(); ?>">	
		<?php } else { ?>
		<img class="img cover cover-<?php echo $size; ?>" src="<?php echo $playlist->get_cover(); ?>">								
		<?php } ?>
	</a>

<?php } else { ?>
	<a class="cover-link" href="/playlists/show/<?php echo $playlist->id; ?>" title="Open playlist: <?php echo $playlist->name; ?>">
		<div class="cover-hover cover-<?php echo $size; ?>">
			<div class="hover-info">
				<div class="hover-item-follows" title="Number of playlist followers"><i class="icon-user tag-social"></i> <span><?php echo $playlist->get_follower_count(); ?></span></div>
				<div class="hover-item-songs" title="Number of songs in playlist"><i class="icon-music tag-music"></i> <span><?php echo $playlist->get_song_count(); ?></span></div>
				<div class="hover-item-plays" title="Number of songs played from playlist"><i class="icon-play tag-collab"></i> <span><?php echo $playlist->get_song_play_count(); ?></span></div>								
			</div>
			<div class="hover-tags">																	
				<?php 
					$tagcontent = '';
					foreach($playlist->tags_to_array() as $tag) { 
						if($tag !== '') { 
							$tagcontent .= '<span class="label label-warning">'.strtolower($tag).'</span> '; 
						}
					} 
					$tagcontent = ($tagcontent == '') ? '<br />' : $tagcontent;
					echo $tagcontent;
				?>
			</div>
		</div>
		<img class="img-polaroid cover cover-<?php echo $size; ?>" src="<?php echo $playlist->get_cover(); ?>">								
	</a>
<?php } ?>