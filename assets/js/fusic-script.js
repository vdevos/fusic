var params = { allowScriptAccess: "always" };
var atts = { id: "fusic-player" };
var looping = false;
var shuffeling = false;
var activeTab = 'playlist';

var count = 0;
var player;
var playlist = {
	'songs' : '',
	'results' : '',
	'count' : function(text,render) {
		return count++;
		
	}
}

var session = '';
var regexyt = "[\\?&]v=([^&#]*)"
var refreshrate = 10000;
var index = -1;
var loaded = false;

var refreshid = setInterval(function() { reloadData(); },  refreshrate);

Handlebars.registerHelper('count', function() {
    return count++;
});

Handlebars.registerHelper('minsToSecs', function(seconds) {
	return secondsToMinutes(seconds);
});


function reloadData() {
	
	if(session != '') {
		
		if(session == 'random') {
			$('#data').load('api.php?action=random&rcount=20', function() {
				parseData();
				setupButtons();
				setSelected();
				clearInterval(refreshid);
				refreshid = -1;
			});
		}
		else {
			$('#data').load('api.php?action=playlist&session='+session, function() {
				parseData();
				setupButtons();
				setSelected();
				if(refreshid == -1) {
					refreshid = setInterval(function() { reloadData(); },  refreshrate);
				}
			});
		}
	}
	else {
		setupButtons();
		setSelected();
	}
}

function lockChannel() {	
	
	activatePlaylistTab();	
	session = $('input#channel').val().toLowerCase();
	if(session == '') {
		$('#link').attr('disabled','');
	}
	else {
		$('#link').removeAttr('disabled');
	}
	
	reloadData();
	
	index = -1;	
}


function onYouTubePlayerReady(playerId) {

	player = document.getElementById(atts.id);
	player.playVideo();
	
	player.addEventListener('onStateChange', '(function(state) { return playerStateChange(state, "' + playerId + '"); })');
}

function embedVideo(videoid) {
	
	swfobject.embedSWF("http://www.youtube.com/v/"+videoid+"?enablejsapi=1&playerapiid=fusic-player&showinfo=1&modestbranding=1&autohide=0&iv_load_policy=3&autoplay=1&rel=0&fs=1&version=3&hd=1",
                    atts.id, "700", "390", "8", null, null, params, atts);
	
	setupButtons();
	setSelected();
}

function playerStateChange(state, playerid) {
	
	if(state == -1) { /* unstarted */ }
	else if(state == 0) { // ended
		playNext();
	}
	else if(state == 1) { /* playing */ }
	else if(state == 3) { /* buffering */ }
	else if(state == 5) { /* qued */ }
}

function startPlaying() {
	
	if(playlist.songs.length > 0) {
		index = 0;
		embedVideo(playlist.songs[index].link);
	}
}

function playNext() {

	if(songsLeft() > 0) {
		index++;
		embedVideo(playlist.songs[index].link);
	}
	else {
		if(looping) {
			index = 0;
			embedVideo(playlist.songs[index].link);
		}
	}
}

function playPrev() {
	
	if(index >= 1) {
		index--;
		embedVideo(playlist.songs[index].link);
	}
	else {
		if(looping) {
			index = playlist.songs.length - 1;
			embedVideo(playlist.songs[index].link);
		}
	}
}

function songsLeft() {
	return playlist.songs.length - (index + 1);
}

function getLinkId(link) {
	
	var id = link.match(regexyt);
	if(id != null) {
		return id[1];
	}
	else {
		return null;
	}
	
}



function setupButtons() {

	$('button#prev').removeAttr('disabled');
	$('button#next').removeAttr('disabled');
	
	if(session == '') {
		$('#link').attr('disabled','');
		$('ul#tabbar').hide(); 
		$('ul#results').hide();
		$('ul#songs').hide();
	}
	else if(playlist.songs) {
		
		if((index - 1) > playlist.songs.length) {
			index = playlist.songs.length;
		}
		if((index == 0 || index == -1) && !looping) { /* initial or 1st index */
			$('button#prev').attr('disabled','');
		}
		if(songsLeft() == 0 && !looping) { /* last index */
			$('button#next').attr('disabled','');
		}

	}
	else {
		$('#link').removeAttr('disabled');	
		$('ul#tabbar').show('fast');
		if(activeTab == 'playlists') {
			activatePlaylistTab();
		}
		else if(activeTab == 'results'){
			activateResultsTab();
		}
	}
}

function parseData() {
	
	count = 0;
	playlist['songs'] = jQuery.parseJSON($('#data').html()).playlist;
	
	var source = $('#template-songs').html();
	source = source.replace('<!--','').replace('-->','');
	var template = Handlebars.compile(source);
	var result = template(playlist);
	
	$('ul#songs').html(result);
	setSelected();	
}

function setSelected() {
	
	if(index != -1) {
		/* all none active */
		$('.song-entry input[name="song-index"]').parents('li').find('div').removeClass('active-song');
		$('.song-entry input[name="song-index"]').parents('li').find('i').removeClass('icon-play');
		$('.song-entry input[name="song-index"]').parents('li').find('i').addClass('icon-remove');
		
		/* the active entry */
		$('.song-entry input[name="song-index"][value='+index+']').parents('li').find('i').addClass('icon-play');
		$('.song-entry input[name="song-index"][value='+index+']').parents('li').find('i').removeClass('icon-remove');
		$('.song-entry input[name="song-index"][value='+index+']').parents('li').find('div').addClass('active-song');		
	}
}

function addSongToPlaylist(external) {
	
	var src = $('input#link');
	var link = (typeof external !== 'undefined' ? external : src.val());	
	var id = getLinkId(link);
	var idt = id;
	
	if(id != null) { /* we have a YouTube URL */
		
		activatePlaylistTab();

		$('#songs').show();
		$('#results').hide();
		
		if(id.indexOf('-') == 0) { id = id.replace('-',''); }
		
		var url = 'http://gdata.youtube.com/feeds/api/videos?q='+id+'&alt=json&callback=?';
		$.getJSON(url, function(data) {
			
			var response = data;
			var title = data.feed.entry[0].title.$t;
		
			var jqxhr = $.get('api.php', { session: session, action:'add', title: title, link: idt}, function(data) {
				reloadData();
				// console.log(data);
			});	
			
		});	
		
		/* make post request to API */
		src.val('');
		src.removeClass('alert-success');
		src.removeClass('alert-error');		
	}
	else { /* Search the YouTube API with a query */
		
		activateResultsTab();
		
		var results = $('#results');
		results.html('');

		$.getJSON('http://gdata.youtube.com/feeds/api/videos?callback=?', { q: link, alt: 'json', orderby:'relevance' }, function(data) {
			
			playlist['results'] = data.feed.entry;
			var source = $('#template-results').html();
			source = source.replace('<!--','').replace('-->','');
			var template = Handlebars.compile(source);
			var result = template(playlist);
			
			$('ul#results').html(result);			

		});
		
	}
}

function removeSongFromPlaylist(id) {
	
	console.log(session);
	
	var jqxhr = $.get('api.php', { session: session, action:'remove', id:id }, function(data) {
		reloadData();
		console.log(data);
	});
	
}

function setLooping() {
	
	looping = !looping;
	
	if(looping) {
		$('button#loop').addClass('btn btn-info');
	}
	else {
		$('button#loop').removeClass('btn btn-info');
	}
	
	setupButtons();
}

function setShuffeling() {
	
	shuffeling = !shuffeling;
	if(shuffeling) {
		$('button#shuffle').addClass('btn btn-info');
	}
	else {
		$('button#shuffle').removeClass('btn btn-info');
	}	
}

function activatePlaylistTab() {
	activeTab = 'playlist';
	var playlistTab = $('#tb-playlist');
	var resultsTab = $('#tb-results');
	playlistTab.addClass('active');
	resultsTab.removeClass('active');
	$('#songs').show();
	$('#results').hide();
}

function activateResultsTab() {
	activeTab = 'results';
	var playlistTab = $('#tb-playlist');
	var resultsTab = $('#tb-results');
	resultsTab.addClass('active');
	playlistTab.removeClass('active');
	$('#results').show();
	$('#songs').hide();
}


$(document).ready(function() {

	/* Load data the 1st time manualy */
	reloadData();
    
	/* playlist tabbar item clicked */
	$('#playlist #tabbar li').click(function() {		
		var sender = $(this).attr('id');		
		if(sender == 'tb-playlist') {
			activatePlaylistTab();
		}
		else if(sender == 'tb-results') {
			activateResultsTab();
		}		
	});
	
	$('.song-entry a').live('click', function(e) {
		var objref = $(this).parents('li');
		var song = objref.find('[name="song-name"]').val();
		var src = objref.find('[name="song-url"]').val();		
		index = parseInt(objref.find('[name="song-index"]').val());
		embedVideo(src);
	});	
	
	$('.result-entry').live('click', function(e) {
		var href = $(this).find('input[name="result-href"]').val();
		addSongToPlaylist(href);
		$('#results').hide();
		$('#songs').show();		
	});
	
	$('div#fusic-player img[src]').live('click', function(e) { startPlaying(); });
	$('button#next').live('click', function(e) { playNext(); });
	$('button#prev').live('click', function(e) { playPrev(); });
	$('button#add').live('click', function(e)  { addSongToPlaylist(); });
	$('button#loop').live('click', function(e) { setLooping(); });
	$('button#shuffle').live('click', function(e) { setShuffeling(); });
	$('button#lockin').live('click', function(e) { lockChannel(); });
	
	$('i[class=icon-remove]').live('click', function(e) { 
		var objref = $(this).parents('li');
		var id = objref.find('[name=song-id]').val();
		objref.hide('slow');
		removeSongFromPlaylist(id); 
		reloadData();	
	});
	
	$('#channel').keyup(function(event) {
        
		/*
        clearTimeout($.data(this, 'timer'));
        var wait = setTimeout(lockChannel, 500);
        $(this).data('timer', wait);
		*/
		lockChannel();
		
	});
	
	$('#link').keyup(function(event) 
	{	
		var link = $(this).val();
		
		if(link != 0) {
			var id = link.match(regexyt);			
			if(id != null) {								
				$(this).removeClass('alert-error');
				$(this).addClass('alert-success');
			}
			else {
				$(this).removeClass('alert-success');
				$(this).addClass('alert-error');
			}
		}
		else {
			$(this).removeClass('alert-success');
			$(this).removeClass('alert-error');
		}
		
		if(event.keyCode == 13) {
			addSongToPlaylist();
		}			
	});
	
});

function getParam(name) {
    return decodeURI(
        (RegExp('[?|&]'+name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
};

function secondsToMinutes(seconds) {

    var t = parseInt(seconds, 10);
    var h = Math.floor(t / 3600);
    t %= 3600;
    var m = Math.floor(t / 60);
    var s = Math.floor(t % 60);
    var rval = m + ':' + ((s <= 9) ? ('0' + s) : s);

    return rval;
};
