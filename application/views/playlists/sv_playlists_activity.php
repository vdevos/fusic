<table class="table table-condensed playlist-list playlist-owns">
	<thead><tr><th colspan="2" class="playlist-header"><h3><?php echo $title; ?></h3></th></tr></thead>
	<?php foreach($playlists as $playlist) { ?>
										
			<tr class="playlist-row">
				<td class="span1">
				
				</td>
				<td class="span11">
				
				</td>				
			</tr>
			
	<?php } ?>
	<?php if(count($playlists) == 0) { ?>
		<tr><td><?php echo $no_playlist_message; ?></td></tr>
	<?php } ?>
</table>	