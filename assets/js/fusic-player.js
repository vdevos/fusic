/* Import the YouTube iframe API */
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

/* Setup the player and callback ready function */
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('fusic-player', {
        width: '100%',
        height: '240',
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
            hd: 1,
            key: 'AI39si6_jLKsZB-7LloDMxUV2BUdxoHs5sxjMgStK9LhxqqKHwY3p99Zw6KhQAJ_DvduN8YIvX89u7h-vm6AXo00GCin83b-KQ'
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
    $('#fusic-player-iframe').hide();
}

/* Default YouTube callbacks  */
function onPlayerReady(event) {
    event.target.playVideo();
}
        
function onPlayerStateChange(event) {			
    switch (event.data) {
        case YT.PlayerState.ENDED:
            Player.Next();
            break;					
        case YT.PlayerState.PAUSED:
            Player.button.play.btnSetPause();
            break;
        case YT.PlayerState.PLAYING:
            Player.button.play.btnSetPlay();
            break;					
        default:
            break;
    }
    Player.UpdateStatus();
}

var API = {
    SongAdd : function (pid, title, url) {
        $.getJSON('/ajax/playlist_song_add', { 	pid:pid, title:title, url: url }, function(data) {	});
    },
    SongRemove : function(pid, sid) {
        $.getJSON('/ajax/playlist_song_remove', { pid:pid, sid:sid }, function(data) { });
    },
    SongPlayed : function(pid, sid) {
        $.getJSON('/ajax/song_add_played', { pid:pid, sid:sid }, function(data) { });
    },
    SongLoved : function(sid, loved) {	
        $.getJSON('/ajax/user_love_song', { sid:sid, arg:loved }, function(data) { });		
    },
};

/* Song and Player class implementation hiding all the player logic */
var Song = {
    GetSongElementByID : function(id) { 
        return $('li[data-song-id="'+id+'"]'); 
    },
    GetSongElementByIndex : function(index) {
    
    },
    GetLinkId : function(link) {
        var regexyt = "[\\?&]v=([^&#]*)"
        var id = link.match(regexyt);
        return (id != null) ? id[1] : null;
    }
};

var Player = {
    id : 0,
    list : null, 
    history : [],
    shuffledbuffer : [],
    index : -1, 
    activeid : -1,
    dragstart: -1,
    dragend: -1,
    shuffle : false, 
    loop : false,
    button : { previous : $('#player-prev'), next : $('#player-next'), play : $('#player-play'), random : $('#player-random') },
    songElements : $('.song-row'),
    Init : function(pid) { 
        var self = this;      
        self.id = pid;        
        /* Setup Player button actions */
        self.button.play.live('click',      function(e) { self.Play();          self.UpdateStatus(); }); 
        self.button.previous.live('click',  function(e) { self.Previous();      self.UpdateStatus(); });
        self.button.next.live('click',      function(e) { self.Next();          self.UpdateStatus(); }); 
        self.button.random.live('click',    function(e) { self.Shuffle();        self.UpdateStatus(); }); 
        /* Setup song element actions */
        $('.sr-item').live('click', function(e) { self.SongAdd(e); });
        $('a[class="song-play"]').live('click', function(e) { self.SongPlay(e); });
        $('div[data-action="play"]').live('click', function(e) { self.SongPlay(e); });        
        $('div[data-action="remove"]').live('click', function(e) { self.SongRemove(e); });
        $('div[data-action="love"]').live('click', function(e) { self.SongLove(e); });
        self.songElements.live('mousedown', function(e) { self.SongDragStart(e); });
    },
    UpdateStatus : function() {
        this.SetActiveSong();
        var songsLeft = this.GetSongsLeft();        
        if(this.shuffle) {
            this.button.previous.SetDisabled();
            if(songsLeft == 0) {
                this.button.next.SetDisabled();
            }
            else if(songsLeft > 0) {
                this.button.next.SetEnabled();
            }
            if(this.shuffledbuffer.length == 0 && this.index == -1) {
                this.button.next.SetDisabled();
            }
        }
        else {
            if(this.index == -1) { /* stopped status */
                this.button.previous.SetDisabled();
                this.button.next.SetDisabled();
                this.button.play.btnSetStop();
            }
            else if(this.index == 0) {
                this.button.previous.SetDisabled();
                if(songsLeft == 0) {
                    this.button.next.SetDisabled();
                }
                else {
                    this.button.next.SetEnabled();
                }
            }
            else {
                if(songsLeft == 0) {
                    if(this.list.length > 1) {
                        this.button.previous.SetEnabled();
                    }
                    this.button.next.SetDisabled();
                }
                else if(songsLeft > 0) {
                    this.button.previous.SetEnabled();
                    this.button.next.SetEnabled();
                }				
            }
        }
    },
    Play : function() {
        var isPlaying = (this.button.play.attr('data-status') == 'playing');	
        if(isPlaying) {
            /* set button to pause-mode and pause the song */
            this.button.play.btnSetPause();
            player.pauseVideo();
        }
        else {
            /* set the button to play-mode and continue song (if no active song yet; player won't continue anyway) */
            this.button.play.btnSetPlay();
            player.playVideo();
            if(this.index == -1) { 
                /* The player has not yet started/finished or stopped */
                this.index = this.shuffle ? this.GetRandomSong().index : 0;                           
                this.EmbedVideo();                
            }
        }			
    },
    Next : function() {               
        if(this.GetSongsLeft() > 0) {
            /* if we have songs left: increment index, get new activeid and embed this song */                         
            this.index = this.shuffle ? this.GetRandomSong().index : (this.index + 1);            
            this.EmbedVideo();
        }
        else { 
            /* no more songs left: reset index and shuffle buffer */
            this.Reset();
        }        
    },
    Reset : function() {
        this.index = -1;
        this.shuffledbuffer = [];
        this.button.play.btnSetStop();
        $('.song-row').removeClass('song-active');
        $('html, body').animate({scrollTop:0}, 'fast');
    },
    Previous : function() {
        if(!this.button.previous.hasClass('disabled')) {
            if(this.shuffle) {
                // TODO: previous song from shufflebuffer
            }
            else {
                if(this.index > 0) {
                    this.index--;
                    this.activeid = this.list[this.index].id;
                    this.EmbedVideo();
                }
            }
        }   
    },    
    Shuffle : function() {
        if(this.shuffle) {
            this.button.random.SetEnabled();
            this.button.random.SetTitle('Enable shuffle playing');
            this.shuffle = false;
        }
        else {
            this.button.random.SetDisabled();
            this.button.random.SetTitle('Disable shuffle playing');
            this.shuffle = true;
        }
    },
    SongPlay : function (e) {    
        this.index = $(e.currentTarget).parents('li').index();
        if(this.shuffle) {
            this.shuffledbuffer.push(this.list[this.index].id);
        }     
        this.EmbedVideo();          
    },
    SongAdd : function(e) {
        var item = $(e.currentTarget);
        if(!item.hasClass('no-embed') && !item.hasClass('isadded')) {
            item.addClass('isadded');
            var title = item.attr('data-song-title');
            var url = item.attr('data-song-url');
            $.getJSON('/ajax/playlist_song_add', { 	pid:this.id, title:title, url: url }, function(data) {		
                reloadData();
            });
        } 
    },
    SongRemove : function (e) {  
        var item = $(e.currentTarget).parents('li');
        var removeindex = item.index();
        var sid = this.list[removeindex].id;        
		
        item.remove();
        this.list.splice(removeindex,1);        
        this.SongRemoveFromShufflebuffer(sid);
        
        this.UpdateStatus();
        
        if(removeindex < this.index) {
            this.index--;
        }
        else if(removeindex == this.index) {
            if(this.GetSongsLeft() > 0) {
                this.index = this.shuffle ? this.GetRandomSong().index : this.index;
                this.EmbedVideo();
            }
            else {
                this.Reset();
            }
        }        
        API.SongRemove(this.id, sid);
    },  
    SongRemoveFromShufflebuffer : function(sid) {
        var shuffleindex = this.GetSongIdShuffleIndex(sid);
        if(shuffleindex >= 0) {
            this.shuffledbuffer.splice(shuffleindex, 1);
        }
    },
    SongLove : function(e) {	
        var item = $(e.currentTarget).parents('li').eq(0);
        var loveindex = item.index();
        var sid = this.list[loveindex].id;
        var arg = item.hasClass('loved-yes') ? 'no' : 'yes';
        item = (arg == 'no') ? item.removeClass('loved-yes') : item.addClass('loved-yes');        
        
        if(this.id == 0 && arg == 'no') { // we are dealing with loved playlist and unloving a song (thus removing it from the list                         
            item.remove();
            this.list.splice(this.index,1);
            if(this.shuffle) {
                this.SongRemoveFromShufflebuffer(sid);
            }
            
            if(loveindex < this.index) {
                this.index--;
            }
            else if(loveindex == this.index) {                                                
                if(this.GetSongsLeft() > 0) {
                    this.index = this.shuffle ? this.GetRandomSong().index : this.index;
                    this.EmbedVideo();
                }
                else {
                    this.Reset();
                }              
            }            
        }        
        API.SongLoved(sid, arg);
    },
    SongDragStart : function(e) {
        this.dragstart = $(e.currentTarget).index();
    },
    SongDragEnd : function(e, ui) {
        var item = ui.item.context;
        this.dragend = $(item).index();
        if(this.dragstart != this.dragend) {            
            var song = this.list[this.dragstart];            
            this.list[this.dragstart] = this.list[this.dragend];
            this.list[this.dragend] = song;
            if($(item).hasClass('song-active')) {
                this.index = this.dragend;
            }            
            this.UpdateStatus();
            
            if(this.id == 0) { // loved playlist
                $.getJSON('/ajax/swap_position_lovedsong', { pid:this.id, sid:song.id, from:this.dragstart, to:this.dragend }, function(data) { });		
            }
            else { // regular playlist
                $.getJSON('/ajax/swap_position_song', { pid:this.id, from:this.dragstart, to:this.dragend }, function(data) { });		
            }
            
        }
    },
    GetSongsLeft : function() {
        if(this.shuffle) {            
            return this.list ? (this.list.length - this.shuffledbuffer.length) : 0;
        }
        else {
            return this.list ? (this.list.length - (this.index + 1)) : 0;
        }	
    },
    GetActiveSong : function(index) {
        index = index || false;
        if(index) {
            return this.list[index];
        }
        else {
            return (this.index == -1) ? this.list[0] : this.list[this.index];
        }
    },
    GetRandomSong : function() {
        var index = 0;
        var song = 0;
        while(true) {
            index = Math.floor(Math.random() * globdata.songs.length);
            song = this.list[index];
            song.index = index;
            if(jQuery.inArray(song.id, this.shuffledbuffer) == -1) {
                this.shuffledbuffer.push(song.id);
                return song;
            }
        }
    },
    HasBeenShuffled : function(id) {
        var isShuffled = false;
        for(var i = 0; i < this.shufflebuffer.length; i++) {
            if(this.shufflebuffer[i].id == id) {
                isShuffled = true;
            }
        }
        return isShuffled;
    },
    GetSongIdIndex : function(id) {
        for(var i = 0; i < this.list.length; i++) {
            if(this.list[i].id == id) {
                return i;
            }
        }
        return -1;
    },
    GetSongIdShuffleIndex : function(id) {
        for(var i = 0; i < this.shuffledbuffer.length; i++) {
            if(this.shuffledbuffer[i].id == id) {
                return i;
            }
        }
        return -1;
    },
    SetActiveSong : function(index) { // TODO: werkt nog niet        
        $('.song-row').removeClass('song-active');
        if(this.index >= 0) {
            $('.song-row').eq(this.index).addClass('song-active');
        }
        
        // IMPORTANT TODO -  Someone might have changed the playlist order
        // Get the new index from the current active song and update the current index)
        // this.index = this.GetSongIdIndex(this.activeid);        
    },
    EmbedVideo : function(song) {
        song = song || this.GetActiveSong();
        this.activeid = song.id;
        var videoid = Song.GetLinkId(song.url);          
        this.SetActiveSong();
        player.loadVideoById(videoid);

        API.SongPlayed(this.id, this.activeid);
        this.ScrollToIndex(this.index);
        
        /* Call from the main view */
        reloadData();
    },
    ScrollToIndex : function(index) {
        $('html, body').animate({scrollTop: $('ul#songlist').find('li').eq(index).offset().top - 200}, 'fast');
    },
    Load : function(data) {
        this.list = data.songs;
    },
    Exists : function(id) {
        return 0;
    }
};


