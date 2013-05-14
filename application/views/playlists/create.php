<div class="row-fluid">
	<div class="span2">&nbsp;</div>
	<div class="span8">
		<div id="widget-playlist-create" class="widget">
			<div class="widget-header">
				<i class="icon-ok tag-collab"></i>
				<h3>Create playlist</h3>
			</div> <!-- /widget-header -->		
			
			<div class="widget-content bg-tile">
				<form action="/playlists/create" method="post">
					<input type="hidden" name="creation" value="yes">
					<table class="playlist-create-container">
						<tr><td colspan="2">
							<div class="alert" id="error" style="<?php echo isset($error) ? '' : 'display:none;'; ?>"><button type="button" class="close" data-dismiss="alert">&times;</button><span><?php if(isset($error)) { echo $error; } ?></span></div>
							<div class="alert alert-info" id="img-loading" style="display:none;"><button type="button" class="close" data-dismiss="alert">&times;</button> Downloading image...</div>
						</td></tr>
						<tr><td colspan="2" class="playlist-create-title"><input type="text" name="title" placeholder="Untitled playlist" value="<?php echo Arr::get($_POST,'title'); ?>"></td></tr>
						<tr>
							<td class="playlist-create-cover-container" valign="top">					
								<img class="playlist-create-cover img-polaroid" src="<?php echo isset($cover) ? $cover : Kohana::$config->load('settings.playlist-default-img'); ?>">
								<!--
								<div class="playlist-create-cover-actions">
									<a class="btn"><i class="icon-upload icon-white"></i> Upload image</a>
								</div>-->
							</td>
							<td class="playlist-create-actions" valign="top">
								<input type="text" name="cover" placeholder="Playlist cover URL" value="<?php echo Arr::get($_POST,'cover'); ?>">
								<input type="text" name="tags" placeholder="Tags: techno, upbeat, moody, sport, party, etc." value="<?php echo Arr::get($_POST,'tags'); ?>">
								<textarea name="description" rows="4" placeholder="A brief description of your playlist"><?php echo Arr::get($_POST,'description'); ?></textarea>
								<div class="playlist-buttons">
									<button id="create" class="btn btn-success"><i class="icon-ok icon-white"></i> Create</button>
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
				$('#error').hide();
				$('.playlist-create-cover').attr('src',src)
					.load(function(response, status, xhr) {						
						 if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
							$('#img-loading').hide();
							$('#error').find('span:first').html('error1');
							$('#error').show();
							$('input[name="cover"]').removeClass('success').addClass('error');
							$('#create').addClass('disabled');
							resetImage();
							
						 } else {
							if(!error) {
								$('input[name="cover"]').removeClass('error').addClass('success');
								$('#error').hide();
								$('#img-loading').hide();
								$('#create').removeClass('disabled');		
							}						
						 }
					})
					.error(function() {
						error = true;
						$('#img-loading').hide();
						$('#error').find('span:first').html('You should provide a valid picture URL');
						$('#error').show();
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
	
</script>