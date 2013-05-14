<div id="widget-<?php echo $type; ?>-playlist-activity" class="widget widget-table action-table">				
	<div id="<?php echo $type; ?>-top" class="widget-header">
		<i class="icon-play tag-music"></i>
		<h3><?php echo ucfirst($type); ?> playlists activity</h3>
	</div> <!-- /widget-header -->
	
	<div class="widget-content">		
		<table class="table table-striped table-bordered">
			<tbody id="widget-<?php echo $type; ?>-playlist-activity-container">
				<tr><td>Loading...</td></tr>				
			</tbody>
			<tbody>
				<tr><td colspan="3" class="text-center more-td" style="display:none;">
					<span class="badge bg-header-color"><a id="widget-<?php echo $type; ?>-playlist-activity-more" href="javascript:;" class="tag-social">Show more</a></span>
				</td></tr>
			</tbody>
		</table>						
	</div> <!-- /widget-content -->

</div>

<script id="widget-<?php echo $type; ?>-playlist-activity-template" type="text/x-handlebars-template">
	{{#if activity}}
		{{#each activity}}
			<tr class="activity-row" data-on="{{date}}" data-hash="{{hash}}" title="{{#ifequal type "follow"}}{{user.name}} follows {{playlist.name}}{{/ifequal}}{{#ifequal type "unfollow"}}{{user.name}} unfollowed {{playlist.name}}{{/ifequal}}{{#ifequal type "added"}}song added to playlist{{/ifequal}}{{#ifequal type "deleted"}}song removed from playlist{{/ifequal}} {{fuzzySpan date}}">
					
					{{#ifequal type "follow"}}
						<td width="1%">
							<i class="icon-user" title="User follows playlist"></i>
						</td>
						<td width="99%" colspan="2">
							<a href="/user/show/{{user.name}}" class="tag-socialx ">{{user.name}}</a>
							&nbsp;<i class="icon-arrow-right"></i>&nbsp; 
							<a href="/playlists/show/{{playlist.id}}" class="tag-collabx">{{playlist.name}}</a>
						</td>
					{{/ifequal}}				
					{{#ifequal type "added"}}
						<td width="1%">
							<i class="icon-music" title="Song added to playlist"></i>
						</td>
						<td width="75%">
							<a href="{{song.url}}" data-song-id="{{song.id}}" class="tag-music">{{song.title}}</a>						
						</td>
						<td width="24%">
							<span title="Added by user: {{user.name}}"><i class="icon-user"></i> <a href="/user/show/{{user.name}}" class="tag-socialx">{{user.name}}</a></span><br />
							<span title="Added to playlist: {{playlist.name}}"><i class="icon-align-justify"></i> <a href="/playlists/show/{{playlist.id}}" class="tag-collabx">{{playlist.name}}</a></span>
						</td>
					{{/ifequal}}
			</tr>
		{{/each}}
	{{else}}
	<?php 
	/*
	if($type == 'your') {
		echo '<tr><td>You have no playlist activity yet, try to <a href="/playlists/create" class="tag-musicx bold">create</a> a playlist</td></tr>';
	}
	else if($type == 'following') {
		echo '<tr><td>There is no playlist activity yet, start <a href="/playlists/explore" class="tag-musicx bold">exploring</a> other playlists!</td></tr>';
	}
	*/		
	?>
	{{/if}}

</script>

<script>

	var offset = <?php echo $limit; ?>;
	var isLoading = false;
	var widget_<?php echo $type; ?>_playlist_activity_id = setInterval(function() { reload_widget_<?php echo $type; ?>_playlist_activity(); },  <?php echo Kohana::$config->load('settings.refresh.'.$type.'_playlists_activity'); ?> );
	
	$(document).ready(function() {
		reload_widget_<?php echo $type; ?>_playlist_activity(true);
	});
		
	function reload_widget_<?php echo $type; ?>_playlist_activity(initial) {
		initial = initial || false;
		$.getJSON('/ajax/get_<?php echo $type; ?>_playlists_activity', { 'limit': <?php echo $limit; ?> }, function(data) {
			var results = $('#widget-<?php echo $type; ?>-playlist-activity-container');
			var source = $("#widget-<?php echo $type; ?>-playlist-activity-template").html();
			var template = Handlebars.compile(source);
			if(data.more == 'yes') {
				$('.more-td').show();
			}
			if(initial) { /* just set the first data into the DOM */
				results.html(template(data));
			}
			else { /* check if we have new activity hashes, if yes: prepend them to the DOM */
				if(data.activity.length > 0) {
					$('#widget-<?php echo $type; ?>-playlist-activity-container .activity-row').each(function() {
						for(var i = 0; i < data.activity.length; i++) {
							var hash = $(this).attr('data-hash');
							if(hash == data.activity[i].hash) {
								data.activity.splice(i,1);
							}
						}
					});				
					results.prepend(template(data));
				}
			}
		});
	}
	
	$('#widget-<?php echo $type; ?>-playlist-activity-more').live('click', function() {	
		var item = $(this);
		if(!isLoading) {
			isLoading = true;
			item.html('<i class="icon-refresh icon-white"></i> Loading...');
			$.getJSON('/ajax/get_<?php echo $type; ?>_playlists_activity', {'limit': <?php echo $limit; ?>, 'offset': offset}, function(data) {
				var results = $('#widget-<?php echo $type; ?>-playlist-activity-container');
				var source = $("#widget-<?php echo $type; ?>-playlist-activity-template").html();
				var template = Handlebars.compile(source);
				results.append(template(data));
				if(data.more == 'yes') {
					isLoading = false;
					item.html("Show more");
				}
				else {
					$('#widget-<?php echo $type; ?>-playlist-activity .more-td').html('End of <?php echo $type; ?> playlist activity');
				}
				offset += <?php echo $limit; ?>;
			});	
		}
	});
	
		
</script>