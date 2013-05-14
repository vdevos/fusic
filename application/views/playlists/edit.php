<div class="row-fluid">
	<div class="span2">&nbsp;</div>
	<div class="span8">
		<div id="widget-playlist-create" class="widget">
			<div class="widget-header">
				<i class="icon-edit tag-collab"></i>
				<h3>Edit playlist</h3>
			</div> <!-- /widget-header -->		
			
			<div class="widget-content">
				<form action="/playlists/edit/<?php echo $id; ?>" method="post">
					<input type="hidden" name="edited" value="yes">
					<table class="playlist-create-container">
						<?php if(isset($error)) { echo '<tr><td colspan="2"><div class="alert">'.$error.'</div></td></tr>'; } ?>
						<tr id="img-error" style="display:none;"><td colspan="2"><div class="alert">You have to provide a valid image URL</div></td></tr>
						<tr id="img-loading" style="display:none;"><td colspan="2"><div class="alert alert-info">Downloading image...</div></td></tr>
						<tr><td colspan="2" class="playlist-create-title"><input type="text" name="title" placeholder="Untitled playlist" value="<?php echo isset($playlist) ? $playlist->name : Arr::get($_POST,'title'); ?>"></td></tr>
						<tr>
							<td class="playlist-create-cover-container" valign="top">					
								<img class="playlist-create-cover img-polaroid" src="<?php echo isset($playlist) ? $playlist->cover : Arr::get($_POST,'cover'); ?>">
								<div class="playlist-create-cover-actions">
									<button id="remove" type="button" class="btn btn-danger"><i class="icon-trash icon-white"></i> Remove playlist</button>
								</div>
							</td>
							<td class="playlist-create-actions" valign="top">
								<input type="text" name="cover" placeholder="Playlist cover URL" value="<?php echo isset($playlist) ? $playlist->cover : Arr::get($_POST,'cover'); ?>">
								<input type="text" name="tags" placeholder="Tags: techno, upbeat, moody, sport, party, etc." value="<?php echo isset($playlist) ? $playlist->genres : Arr::get($_POST,'tags'); ?>">
								<textarea name="description" rows="4" placeholder="A brief description of your playlist"><?php echo isset($playlist) ? $playlist->description : Arr::get($_POST,'description'); ?></textarea>
								<div class="playlist-buttons">
									<button id="create" type="submit" class="btn btn-success"><i class="icon-edit icon-white"></i> Save</button>
									<button id="return" type="button" class="btn">Return</button>
								</div>
							</td>
						</tr>
					</table>
				</form>
			</div> <!-- /widget-content -->
		</div>
	</div>
</div>

<script>
	
		$('input[name="cover"]').change(function() {
			var src = $(this).val();
			if(src.length > 8) {
				var error = false;
				$('#img-loading').show();
				$('#img-error').hide();
				$('.playlist-create-cover').attr('src',src)
					.load(function(response, status, xhr) {						
						 if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
							$('#img-loading').hide();
							$('#img-error').show();
							$('input[name="cover"]').removeClass('success').addClass('error');
							$('#create').addClass('disabled');
							resetImage();
							
						 } else {
							if(!error) {
								$('input[name="cover"]').removeClass('error').addClass('success');
								$('#img-error').hide();
								$('#img-loading').hide();
								$('#create').removeClass('disabled');		
							}							
						 }
					})
					.error(function() {
						error = true;
						$('#img-loading').hide();
						$('#img-error').show();
						$('input[name="cover"]').removeClass('success').addClass('error');
						$('#create').addClass('disabled');
						resetImage();
					});
			}
			else {
				resetImage();
			}
		});
		
		function resetImage() {
			<?php echo 'var defaultimage = "'.trim(Kohana::$config->load('settings.playlist-default-img')).'";'; ?>
			$('.playlist-create-cover').attr('src', defaultimage);
		}

		$('#return').click(function() { window.location = "/playlists/yours"; });
		$('#remove').click(function() {
			var answer = confirm("Are you sure you want to remove this playlist?\n\nThis action cannot be undone!");
			if (answer){
				window.location = "/playlists/delete/<?php echo $id; ?>";
			}
		});
	
	
</script>