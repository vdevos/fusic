<div class="row-fluid">
	<div class="span2">&nbsp;</div>
	<div class="span8">
		<div id="widget-profile-create" class="widget">
			<div class="widget-header">
				<i class="icon-user tag-social"></i>
				<h3>Profile</h3>
			</div> <!-- /widget-header -->		
			
			<div class="widget-content bg-tile">
					<input type="hidden" name="edited" value="yes">
					<table class="profile-create-container">						
						<tr><td colspan="2">						
							<div class="alert" id="error" style="<?php echo isset($error) ? '' : 'display:none;'; ?>"><button type="button" class="close" data-dismiss="alert">&times;</button><span><?php if(isset($error)) { echo $error; } ?></span></div>
							<div class="alert alert-success" style="<?php echo isset($message) ? '' : 'display:none;'; ?>"><button type="button" class="close" data-dismiss="alert">&times;</button><span><?php if(isset($message)) { echo $message; } ?></span></div>
							<div class="alert alert-info" id="img-loading" style="display:none;">Uploading profile image...</div>
						</td></tr>
						<tr>
							<td class="profile-create-cover-container" valign="top">	
								<img class="profile-create-cover img-polaroid" src="<?php echo isset($user) ? $user->get_cover() : Kohana::$config->load('settings.user-default-img'); ?>">
								<div class="profile-create-cover-actions">									
									<div class="btn-group">																						
										<button class="btn dropdown-toggle" data-toggle="dropdown">Change picture <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a id="user-pic-uploader" href="javascript:;">Upload picture</a></li>
											<li><a id="remove-pic" href="javascript:;" style="<?php echo (!$user->has_cover()) ? 'display:none;' : ''; ?>">Remove picture</a></li>
										</ul>
									</div>
								</div>								
							</td>
						<form action="/user/profile/" method="post" class="form-horizontal">
							<td class="profile-create-actions" valign="top">
								<div class="control-group">
									<label class="control-label" for="input-username">Username</label>
									<div class="controls">
										<input type="text" id="input-username" name="username" placeholder="Your username" value="<?php echo Arr::get($_POST,'username', $user->username); ?>">
									</div>
							    </div>
								<div class="control-group">
									<label class="control-label" for="input-email">Email</label>
									<div class="controls">
										<input type="text" id="input-email" name="email" placeholder="Your email" value="<?php echo Arr::get($_POST,'email', $user->email); ?>">
									</div>
							    </div>		
								<div class="control-group">
									<label class="control-label" for="input-bio">Bio</label>
									<div class="controls">
										<textarea id="input-bio" name="bio" rows="4" placeholder="About yourself"><?php echo Arr::get($_POST,'bio', $user->bio); ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">&nbsp;</label>
									<div class="controls profile-buttons">
										<button id="create" type="submit" class="btn btn-success"><i class="icon-edit icon-white"></i> Save</button>
										<button id="return" type="button" class="btn">Return</button>
									</div>
								</div>
							</td>
						</form>
						</tr>
					</table>
			</div> <!-- /widget-content -->
		</div>
	</div>
</div>

<script>

	
	var uploader = new qq.FileUploader({
		element: document.getElementById('user-pic-uploader'),
		action: 'tools/upload_user_picture',
		sizeLimit: 2621440,
		debug: true,
		onComplete : function(id, fileName, responseJSON) {
			$('#img-error').hide();
			$('#img-loading').hide();
			if(responseJSON.success) {
				var d = new Date();
				$('ul.qq-upload-list').remove()
				$('img.profile-create-cover').attr('src',responseJSON.src + '?' + d.getTime());
				$('#remove-pic').show();
			}
			else if(responseJSON.error) {
				$('#img-error').show();
				$('#img-error .alert').html(responseJSON.error);
			}
		},
		onUpload : function(id, fileName) {
			$('#img-error').hide();
			$('#img-loading').show();
		},
	});
	
	$('#remove-pic').click(function() {
		$.getJSON('tools/remove_user_picture', function(data) {
			var d = new Date();
			<?php echo "var img = '".Kohana::$config->load('settings.user-default-img')."';"; ?>
			$('img.profile-create-cover').attr('src', img + '?' + d.getTime());
			$('#remove-pic').hide();
		});
	});
	
	
	function resetImage() {
		<?php echo 'var defaultimage = "'.trim(Kohana::$config->load('settings.profile-default-img')).'";'; ?>
		$('.profile-create-cover').attr('src', defaultimage);
	}

	$('#return').click(function() { window.location = "/playlists/yours"; });	
	
</script>