<div id="widget-playlist-covers" class="widget big-stats-container widget-playlist-covers-container">	      			
	<div class="widget-header">
		<i class="icon-align-justify"></i>
		<h3>Newest playlists</h3>
	</div>
	<div class="widget-content">				
		<div id="widget-playlist-covers-container">
			<?php foreach($playlists as $playlist) {
				echo $playlist->get_cover_view('small');				
			} ?>
		</div>			
	</div> <!-- /widget-content -->
</div>