<div class="row">
	<div class="span8 offset2" style="background-color: #efefef; padding: 20px 20px; border-radius: 3px;"></span>	
		<h2>Song activity</h2>
		<hr style="margin: 5px 0;">
		<!-- PLAYLIST -->
		<table id="songs-added" class="table table-condensed table-striped well playlist-list" style="border-radius: 5px; margin-bottom: 0px;">
			<thead style="background-color:white;">
				<th>&nbsp;</th><th width="50%">Songs</th><th width="25%">Playlist</th><th>Date</th>
			</thead>
			<?php 
				foreach($songs as $time => $song) { 
					$type = $song['type'];
					$song = $song['song'];
					if($type == 'add') {
						echo '<tr>
								<td><i class="icon-plus" title="Added song" style="margin-top:1px;"></i></td>
								<td style="padding-left:0px;"><a href="'.$song->url.'" target="_blank" title="Played count: '.$song->played_count.'">'.$song->title.'</td>
								<td><a href="/playlist/show/'.$song->playlist->id.'">'.$song->playlist->name.'</a></td>
								<td>'.strftime("%d/%m/%Y on %H:%M", $time).'</td>
							  </tr>';
					}
					else if($type == 'love') {
						echo '<tr>
							<td><i class="icon-heart" title="Loved song" style="margin-top:1px;"></i></td>
							<td style="padding-left:0px;"><a href="'.$song->url.'" target="_blank" title="Played count: '.$song->played_count.'">'.$song->title.'</td>
							<td><a href="/playlist/show/'.$song->playlist->id.'">'.$song->playlist->name.'</a></td>
							<td>'.strftime("%d/%m/%Y on %H:%M", $time).'</td>
						 </tr>';
					}
					else if($type == 'playz') {
						echo '<tr>
								<td><i class="icon-play" title="Listened song" style="margin-top:1px;"></i></td>
								<td style="padding-left:0px;"><a href="'.$song->url.'" target="_blank" title="Played count: '.$song->played_count.'">'.$song->title.'</td>
								<td><a href="/playlist/show/'.$song->playlist->id.'">'.$song->playlist->name.'</a></td>
								<td>'.strftime("%d/%m/%Y on %H:%M", $time).'</td>
							  </tr>';
					}
				}
			?>
		</table>			
		
	</div>	

</div>

<script>
	

</script>
