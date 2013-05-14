<div class="row">
	<div class="span6 offset3 well">
		<div class="row-fluid">		
			<h2>Create a new playlist</h2>
			<hr style="margin-top:0px; margin-bottom:10px;">
			<img id="playlist-cover" src="<?php echo (Arr::get($_POST,'cover',false) == '') ? '/assets/img/cover.gif' : Arr::get($_POST,'cover',false); ?>" class="span4 img-polaroid">
			<form class="form form-playlist-create span8" method="post" action="/playlist/create" style="margin-left:0px;">	
				<input type="hidden" name="creation" value="yes">
				<input type="text" class="input span12" name="playlist" placeholder="&nbsp;Playlist name" style="float:left;" value="<?php echo Arr::get($_POST,'playlist',''); ?>">
				<input type="text" class="input span12" name="genres" placeholder="Add tags e.g. techno, sport, study, drumnbass, tempo" style="float:left;" value="<?php echo Arr::get($_POST,'genres',''); ?>">	
				<input type="text" class="input span12" name="cover" placeholder="URL to playlist cover/image" style="float:left;" value="<?php echo Arr::get($_POST,'cover', NULL); ?>">
				<div style="float:right;">
					<a href="/playlist/" class="btn">Cancel</a>
					<button id="create" type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Create</button>
				</div>
			</form>	
			<?php if(isset($error)) { ?>
				<div class="alert alert-error span12" style="margin-top: 10px; margin-left:0px;">
					<?php echo $error; ?>
				</div>
			<?php } ?>
		</div>
	</div>	
</div>

<script>
	$('input[name="cover"]').change(function() {
		var src = $(this).val();
		if(src.length > 10) {
			$('img').attr('src', $(this).val());
		}
		else {
			$('img').attr('src', '/assets/img/cover.gif');
		}
	});
</script>