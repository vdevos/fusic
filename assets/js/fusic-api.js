/*  
	Loves or unloves a specific song
	@param sid (str) - value: song-id 
	@param loved (str) - value: yes OR no 
*/
function addLovedSong(sid, loved) 
{	
	$.getJSON('/ajax/user_love_song', { sid:sid, arg:loved }, function(data) {
		
	});		
}

/* 
	Adds a song to the users "played" history
	@param sid (str) - value: song-id
	@param pid (str) - value: playlist-id
*/
function addSongPlayed(sid, pid) 
{
	$.getJSON('/ajax/song_add_played', { sid:sid, pid:pid }, function(data) {
		
	});
}