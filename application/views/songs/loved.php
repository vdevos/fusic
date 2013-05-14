<?php 
	$user = Auth::instance()->get_user();	
?>

<div class="row">
	
	<!-- USERS OVERVIEW -->
	<div class="span4" style="position:fixed;">			
		<div class="well well-small">
			<div class="row-fluid">
				<div class="span12" style="padding:0px;">					
					<!-- PLAYLIST INFO -->
					<div class="span12" style="margin-left:0px;">						
						<div class="span3">
							<button id="stats-loved" class="btn btn-primary span12 disabled" style="margin-left:0px; margin-top:5px;">
								<i class="icon-heart icon-white"></i><span>...</span></button>	
						</div>
						<div class="span9">
							<h2 class="playlist-title">Loved Songs</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="well well-small video-box" style="padding-bottom:0px;">
			<div class="row-fluid" style="margin-bottom: 5px;">
				<div class="btn-group span8">
					<button id="player-prev" class="btn btn-primary span4 meta-button">
						<i class="icon-step-backward icon-white"></i></button>
					<button id="player-play" class="btn btn-primary span4 meta-button" data-status="stopped">
						<i class="icon-stop icon-white"></i></button>
					<button id="player-next" class="btn btn-primary span4 meta-button">
						<i class="icon-step-forward icon-white"></i></button>
				</div>
			</div>
			<div id="fusic-player" style="height: 260px; min-height: 240px; max-height: 240px;">
				<iframe id="fusic-player-iframe" style="max-height: 260px; display:none; margin-bottom:0px;" frameborder="0" allowfullscreen title="Fusic Player" height="100%" width="100%" src="http://www.youtube.com/embed/?playerapiid=fusic-player&showinfo=1&modestbranding=1&autohide=1&iv_load_policy=3&autoplay=1&wmode=opaque&rel=0&fs=1&version=3&hd=1&enablejsapi=1&vq=hd720&origin=<?php echo BASE_URL; ?>"></iframe>
			</div>
			<script>
				var tag = document.createElement('script');
				tag.src = "http://www.youtube.com/player_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

				var player;
				function onYouTubeIframeAPIReady() {
					player = new YT.Player('fusic-player', {
						width: '100%',
						height: '100%',
						playerVars: {
							playerapiid: 'fusic-player',
							showinfo: 1,
							modestbranding: 1,
							autohide: 1,
							iv_load_policy: 3,
							autoplay: 1,
							wmode: 'opaque',
							rel: 0,
							fs: 1,
							version: 3,
							hd: 1
						},
						events: {
							'onReady': onPlayerReady,
							'onStateChange': onPlayerStateChange
						}
					});
					$('#fusic-player-iframe').hide();
				}
			</script>
		</div>
		
	</div>
	
	<!-- PLAYLIST -->
	<div class="span8 offset4 well well-small pull-right"></span>	
		<div class="row-fluid">
			<table id="playlist" class="table well playlist-list" style="border-radius:5px; margin-bottom:4px;">
				<tr><td>Loading...</td></tr>			
			</table>		
			<table id="followers" class="table well playlist-list" style="border-radius:5px; margin-bottom:4px; display:none;">
			
			</table>
			<table id="history" class="table well playlist-list" style="border-radius:5px; margin-bottom:4px; display:none;">
			
			</table>
		</div>
	</div>	

</div>
	
	<script id="playlist-songs" type="text/x-handlebars-template">
		{{#if songs}}
			{{#each songs}}
			<tr class="song-row">
				<td class="span9">
					<h4><a href="#" data-url="{{url}}" class="song-play">{{title}}</a></h4>
					<div><acronym title="Duration">{{duration}}</acronym> - 
						<acronym data-timestamp="{{loved_on}}" title="{{readableDate loved_on}}">{{fuzzySpan loved_on}}</acronym>
						{{#if locked_by}}
							<b>|</b> <i class="icon-headphones" style="margin-top: 2px;"></i> 
							{{#commaSeperated locked_by}} <a href="/user/show/{{this.id}}">{{this.username}}</a>{{/commaSeperated}}
						{{/if}}
					</div>
				</td>	
				<td class="span3">
					<div class="span4"></div>
					<div class="song-buttons btn-group span8 pull-right">
					  <button class="btn btn-mini song-play meta-button span6" title="Song played {{playcount}} times">
					  	<i class="icon-play" style="margin-top:-1px;"></i> {{playcount}}</button>
							<button class="btn btn-mini meta-button btn-danger love-song loved-yes span6" data-song-id="{{id}}" title="Unlove this song"><i class="icon-heart icon-white" style="margin-top:-1px;"></i> {{lovecount}}</button>
					  <!--
					  <a class="btn dropdown-toggle span2" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="#"><i class="icon-pencil icon-remove-playlist"></i> Edit</a></li>									
						<li><a href="#"><i class="icon-remove icon-remove-playlist"></i> Remove</a></li>									
					  </ul>-->
				</td>
			</tr>
			{{/each}}
		{{else}}
			<?php $emptyplaylist = true; ?>
			<tr>
				<td colspan="2" class="span12">					
					You have no loved songs yet
				</td>
			</tr>
		{{/if}}
</script>
	

<script>

	var prevBtn = $('#player-prev');
	var nextBtn = $('#player-next');
	var playBtn = $('#player-play');
	var randomBtn = $('#player-random');
		
	function onPlayerReady(event) {
		event.target.playVideo();
    }
    		
	function onPlayerStateChange(event) {			
		switch (event.data) {
			case YT.PlayerState.ENDED:
				playNext();
				break;					
			case YT.PlayerState.PAUSED:
				playBtn.btnSetPause();
				break;
			case YT.PlayerState.PLAYING:
				playBtn.btnSetPlay();
				break;					
			default:
				break;
		}
		updatePlayerButtons();
	}	
	
	
	$(playBtn).live('click', function(e) 
	{				
		var isPlaying = ($(this).attr('data-status') == 'playing');
		if(isPlaying) {
			playBtn.btnSetPause();
			player.pauseVideo();
		}
		else {
			playBtn.btnSetPlay();
			player.playVideo();
			if(index == -1) {
				index = 0;
				embedVideo();
			}
		}			
	});
	$(nextBtn).live('click', function(e) { 
		if(!$(this).hasClass('disabled')) {
			playNext(); 
		}
	});		
	$(prevBtn).live('click', function(e) { 
		if(!$(this).hasClass('disabled')) {
			playPrev(); 
		}
	});
	$(randomBtn).live('click', function(e) {  
		if($(this).hasClass('disabled')) {
			randomBtn.btnEnable();
		}
		else {
			randomBtn.btnDisable();
		}
	});
	
	
	function songsLeft() { 
		return globdata.songs.length - (index + 1); 
	}
	
	function playNext() {			
		if(songsLeft() > 0) {
			index++;
			embedVideo();
		}
		else { /* no more songs left: reset index and set NO active song */
			index = -1;
			reloadData();
			playBtn.btnSetStop();
			$('html, body').animate({scrollTop:0}, 'fast');
		}
	}
	
	function playPrev() {
		if(index > 0) {
			index--;
			embedVideo();
		}		
	}		
	
	function updatePlayerButtons() {		
		if(index == -1) { /* stopped status */
			prevBtn.btnDisable();
			nextBtn.btnDisable();
			playBtn.btnSetStop();
		}
		else if(index == 0) {
			prevBtn.btnDisable();
			if(songsLeft() == 0) {
				nextBtn.btnDisable();
			}
			else {
				nextBtn.btnEnable();
			}
		}
		else {
			if(songsLeft() == 0) {
				nextBtn.btnDisable();
			}
			else if(songsLeft() > 0) {
				prevBtn.btnEnable();
				nextBtn.btnEnable();
			}				
		}			
	}		
	
    
	var index = -1;
	var globdata = -1;
	
	var refreshrate = 30000;
	var refreshid = setInterval(function() { reloadData(); },  refreshrate);
	
	$(document).ready(function() {
		$.getJSON('/ajax/get_loved_songs', { }, function(data) {				
			globdata = data;
			reloadStats();
			
			var playlistresults = $('#playlist');
			var playlistsource = $("#playlist-songs").html();
			var playlisttemplate = Handlebars.compile(playlistsource);
			playlistresults.html(playlisttemplate(data));
				
		});			
	});
	
	function embedVideo() {
		playBtn.btnSetPause();	
		if(index >= 0 && index <= globdata.songs.length - 1) {		
			var vid = getLinkId(globdata.songs[index].url);
			player.loadVideoById(vid);
			showPlayer();
			addSongPlayed();
			setActiveSong();	
			playBtn.btnSetPlay();			
			$('html, body').animate({scrollTop: $('#playlist').find('tr').eq(index).offset().top - 60}, 'fast');
		}	
	}		
	
	function reloadData(removeindex, embed) {
		removeindex = removeindex || -1;	
		embed = embed || false;
		var sid = (index == -1) ? -1 : globdata.songs[index].id;
		$.getJSON('/ajax/get_loved_songs', { }, function(data) {
			globdata = data;
			reloadStats();
			reloadSongs(removeindex > -1);
			if(embed) {	
				embedVideo();
			}
		});
	}	
	
	$('.song-play').live('click', function(e) 
	{
		index = $(this).parents('tr').eq(0).index();			
		embedVideo();
	});
	
	function showPlayer() {
		$('.video-box').show();
		$('#fusic-player').css('margin-bottom','10px').show();
		$('#fusic-player-iframe').show();
	}
			
	function setActiveSong() {			
		if(index != -1) {
			$('.song-play').parents('tr').removeClass('song-active');
			$('.song-play').parents('tr').eq(-(index+1)).addClass('song-active');
		}		
		else { /* if no active index set, make sure no song is indicated as actived */
			$('.song-play').parents('tr').removeClass('song-active');
		}				
	}
	
	function getLinkId(link) {
		var regexyt = "[\\?&]v=([^&#]*)"
		var id = link.match(regexyt);
		if(id != null) {
			return id[1];
		}
		else {
			return null;
		}			
	}
	
	
	function addSongPlayed() {
		var sid = globdata.songs[index].id;
		$.getJSON('/ajax/song_add_played', { sid:sid, pid:1 }, function(data) {
			reloadData();
		});
	}		

	
	/* RELOAD STATS */
	function reloadStats() {
		$('#stats-loved').find('span:first').html(globdata.stats.love_count);
	}
	
	/* RELOAD SONGS */
	function disableSongs() { $('#playlist').fadeTo(1, 0.3); }
	function enableSongs() { $('#playlist').fadeTo(1, 1); }
	function reloadSongs(display) {
		display = display || false;
		
		var playlistresults = $('#playlist');
		var playlistsource = $("#playlist-songs").html();
		var playlisttemplate = Handlebars.compile(playlistsource);
		playlistresults.html(playlisttemplate(globdata));
		
		setActiveSong();		
		if(display) {
			enableSongs();
		}	
	}		
	
	/* LOVE SONG */
	$('.love-song').live('click', function() { 
		var item = $(this);
		var sid = item.attr('data-song-id');
		var arg = item.hasClass('loved-yes') ? 'no' : 'yes';
		if(arg == 'yes') {
			item.removeClass('loved-yes');
		}
		else {
			item.addClass('loved-yes');
		}
		
		$.getJSON('/ajax/user_love_song', { sid:sid, arg:arg }, function(data) {
			reloadData(1);	
		})								
	});
	
</script>
				
			
