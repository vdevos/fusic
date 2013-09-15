<div class="row">    
	<div class="playlists-container">	
        
        <? 
        if(isset($reason)) {             
            if($reason == 'deleted') {
                echo '<div class="alert alert-error"><strong>Warning: </strong> the playlist you requested has been deleted!</div>';
            }
            else if($reason == 'private') {
                 echo '<div class="alert alert-error"><strong>Warning: </strong> the playlist you requested is private!</div>';
            }
        }      
        ?>
    
		<?php echo $playlist_view; ?>
        
	</div>	
</div>