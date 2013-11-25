<style>
.navbar { position:fixed!important; }
.navbar .navbar-fixed-top { position:fixed; }
.subnavbar { position: fixed; width: 100%; z-index: 1000; margin-top:56px; } 
#sp { margin-top:140px; } 
</style>

  
<?php if(!$isadmin && $islocked) { ?>
    <div id="sp" class="row">       
        <div id="container-left" class="span" style="margin-top:20px;">
        <h1>Thy shall not try to view a locked playlist...</h1>
    </div>
<?php } else { ?>

<div id="sp" class="row">
	<div id="container-left" class="span" style="margin-top:20px;">
		<div id="player-info-box" class="well well-small bg-tile" style="margin-bottom:10px; margin-left:0px;">
			<div id="video-box" class="span">	
				<h1><?php echo $playlist->name; ?></h1>
                <div id="fusic-player"></div>
				<div id="fusic-controls" class="btn-group">
					<button id="player-prev" class="btn btn-inverse meta-button">
						<i class="icon-step-backward icon-white"></i></button>
					<button id="player-play" class="btn btn-inverse meta-button" data-status="stopped">
						<i class="icon-stop icon-white"></i></button>
					<button id="player-next" class="btn btn-inverse meta-button">
						<i class="icon-step-forward icon-white"></i></button>	
					<button id="player-random" class="btn btn-inverse meta-button" title="Enable shuffle playing" data-shuffle="no">
						<i class="icon-random icon-white"></i>
					</button>
					<button id="btn-search-songs" class="btn btn-inverse meta-button" title="Search for songs to add">
						<i class="icon-repeat icon-white"></i>
					</button>
				</div>
			</div>
		</div>
        <?php if(!isset($ploved)) { ?>
		<div class="well well-small bg-tile" style="margin-bottom:10px; margin-left:0px;">
			<?php echo $ownerbox; ?>		
		</div>
        <?php } ?>

	</div>

	<div id="container-right">
	
		<ul id="meta-tabs" class="nav nav-tabs margin-bottom:-3px;">
			<?php if(!isset($ploved)) { ?>
				<li class="meta-tab" data-id="search"><a href="javascript:;"><i class="icon-search" style="font-size:18px;"></i></a></li>
			<?php } ?>          
				<li class="meta-tab active" data-id="playlist">
				<a href="javascript:;"><i class="icon-align-justify"></i> Playlist</a>
				</li>
			<?php if(!isset($ploved)) { ?>
				<li class="meta-tab" data-id="followers"><a href="javascript:;"><i class="icon-user"></i> Followers</a></li>
			<?php } ?>
				<li class="meta-tab" data-id="stats"><a href="javascript:;"><i class="icon-signal"></i> Stats</a></li>
			<?php if ($isadmin) { ?>
				<li class="meta-tab-no-js" title="You own this playlist" style="float:right;"><a href="javascript:;"><i class="icon-asterisk"></i> Admin</a></li>
			<?php } else if ($isfollowing) { ?>
				<li class="meta-tab-no-js" title="Unfollow this playlist" style="float:right;"><a href="/playlists/unfollow/<?php echo $playlist->id ?>"><i class="icon-minus"></i> Unfollow</a></li>
			<?php } else { ?>
				<li class="meta-tab-no-js" title="Follow this playlist" style="float:right;"><a href="/playlists/follow/<?php echo $playlist->id ?>"><i class="icon-plus"></i> Follow</a></li>
			<?php } ?>
		</ul>
		
		<div id="information-box" class="span well well-small">

			<div id="meta-information-box">
                <div id="meta-box-search" style="display:none;">  
					<?php if ($user->can_edit_playlist($playlist)) { ?>                   
						<input id="song-search" type="text" class="input-medium search-query" placeholder="Search for songs">
						<ul id="song-search-results" class="nav" style="border-radius: 5px; display:none;"></ul>
					<?php } else { ?>
						<div class="alert alert-info text-center"><strong>You can't add any songs to this playlist because you have no editing privileges yet</strong></div>
					<?php } ?>
                </div>
				<div id="meta-box-playlist" class="span"><div class="alert alert-info text-center"><strong>Loading...</strong></div></div>
				
				<div id="meta-box-stats" class="span" style="display:none;">		
					<?php if(!$isadmin) { ?>
						<?php if($isfollowing) { ?>
							<a href="#" class="btn btn-success follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="yes"><i class="icon-ok-circle icon-white"></i> <span>Following</span></a>
						<?php } else { ?>
							<a href="#" class="btn meta-button follow-playlist" data-playlist-id="<?php echo $playlist->id; ?>" data-following="no"><i class="icon-ok"></i> <span>Follow</span></a>
						<?php } ?>			
					<?php } ?>
					Stats		
				</div>
				
				<div id="meta-box-followers" class="span" style="display:none;">Followers</div>
				
				<div id="meta-box-stats" class="span" style="display:none;">Stats</div>
				
				<div id="meta-box-chat" class="span" style="display:none;">Chat</div>

			</div>
		</div>
		
	</div>
</div>
	
<script id="playlist-songs" type="text/x-handlebars-template">  
   
    {{#if songs}}
        <!--
        <div class="btn-toolbar" style="margin: 0;">
          <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-filter"></i> Sort <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="#"><i class="icon-arrow-up"></i> Date (asc)</a></li>
              <li><a href="#"><i class="icon-arrow-down"></i> Date (desc)</a></li>                  
              <li><a href="#"><i class="icon-arrow-up"></i> Title (asc)</a></li>
              <li><a href="#"><i class="icon-arrow-down"></i> Title (desc)</a></li>
              <li><a href="#"><i class="icon-arrow-up"></i> Duration (asc)</a></li>
              <li><a href="#"><i class="icon-arrow-down"></i> Duration (desc)</a></li>
              <li class="divider"></li>
              <li><a href="#"><i class="icon-heart"></i> Loved only</a></li>                  
            </ul>         
        </div>-->   
         <ul id="songlist" class="sortable grid row">
        {{#each songs}}			
            <li class="span bg-tile song-row row-fluid loved-{{isloved}}" draggable="true" data-song-id="{{id}}" data-song-url="{{url}}">
                <div class="span10">
                    <h4><a href="javascript:;" class="song-play">{{title}}</a></h4>
                    <div><acronym title="Duration">{{duration}}</acronym> - 
                        Added by <a href="/user/show/{{added_by.id}}">{{added_by.username}}</a>
                        <?php if(isset($ploved)) { ?> in <a href="/playlists/show/{{added_to.id}}">{{added_to.name}}</a> <?php } ?>
                        <acronym data-timestamp="{{date}}" title="{{readableDate date}}">{{fuzzySpan date}}</acronym>                         
                        {{#if locked_by}}
                            <b>|</b> <i class="icon-headphones" style="margin-top: 2px;"></i> 
                            {{#commaSeperated locked_by}} <a href="/user/show/{{this.id}}">{{this.username}}</a>{{/commaSeperated}}
                        {{/if}}
                    </div>
                </div>
                <div class="span2 row-fluid">                   
                    <?php if(!isset($ploved)) { ?>
                        <div class="span4 sbutton" data-action="play"><div><i class="icon-play"></i></div></div>                        
                        <div class="span4 sbutton" data-action="love"><div><i class="icon-heart"></i></div></div>
                        <div class="span4 sbutton" data-action="remove"><div><i class="icon-remove"></i></div></div>
                    <?php } else { ?>                        
                        <div class="span6 sbutton" data-action="play"><div><i class="icon-play"></i></div></div>                        
                        <div class="span6 sbutton" data-action="love"><div><i class="icon-heart"></i></div></div>
                    <?php } ?>
                </div>
            </li>
        {{/each}}
        </ul>
    {{else}}
        <?php $emptyplaylist = true; ?>
        <?php if(!isset($ploved)) { ?>
        <tr>
            <td colspan="2" class="span">					
                <a href="javascript:;" id="btn-search-songs-new" class="btn btn-inverse">
                    <i class="icon-search icon-white"></i> Search for songs to add
                </a>
        </tr>
        <?php } else { ?>
            <div class="alert alert-error"><strong>You have no loved songs yet!</strong></div>
        <?php } ?>
    {{/if}}
</script>

<?php if(!isset($ploved)) { ?>
<script id="playlist-followers" type="text/x-handlebars-template">
    <ul id="followerslist" class="row">
    {{#if followers}}
        {{#each followers}}			
            <li class="span bg-tile follower-row row-fluid" data-user-id="{{id}}">
                <div class="span1"><i class="online online-{{isactive}}"></i>&nbsp;</div>
                <div class="follower-main span9">
                    <h3><a href="/user/show/{{username}}">{{username}}</a></h3>
                    {{#if listening}} 
                        <span class="listening"><i class="icon-headphones"></i> <a href="{{listening.url}}" target="_blank">{{listening.title}}</a></span> 
                    {{/if}}
                </div>                    
                <div class="follower-button span2 row-fluid">
                    {{#if isviewer}}
                        <button class="btn btn-warning meta-button playlist-privilege <?php echo (!$isadmin) ? 'disabled' : ''; ?>" 
                            data-privilege="viewer" data-playlist-id="<?php echo $playlist->id; ?>" data-user-id="{{id}}">
                            <i class="icon-eye-open icon-white" title="Give user 'editor' privilege"></i> <span>Viewer</span></button>                            
                    {{/if}}
                    {{#if iseditor}}
                        <button class="btn btn-primary meta-button playlist-privilege <?php (!$isadmin) ? 'disabled' : ''; ?>"
                            data-privilege="editor" data-playlist-id="<?php echo $playlist->id; ?>" data-user-id="{{id}}">
                            <i class="icon-pencil icon-white" title="Give user 'viewer' privilege"></i> <span>Editor</span></button>	                           
                    {{/if}}
                    {{#if isadmin}}
                        <button class="btn btn-inverse meta-button disabled" data-privilege="admin">
                            <i class="icon-user icon-white" title="Give user 'viewer' privilege"></i> <span>Admin</span></button>	
                    {{/if}}                    
                </div>
            </li>
        {{/each}}
    {{/if}}
    </ul>
</script>
<?php } ?>
	
	
<script id="playlist-stats" type="text/x-handlebars-template">
    
    <div class="widget">
        <div class="widget-content">          
            <div class="stats <?php if(isset($ploved)) { echo 'loved'; } ?>">                
                <div class="stat"><span class="stat-value">{{stats.song_count}}</span><i class="icon-music"></i> Songs</div>                 
                <div class="stat"><span class="stat-value">{{stats.total_duration}}</span><i class="icon-time"></i> Duration</div>                 
                <?php if(!isset($ploved)) { ?>
                <div class="stat"><span class="stat-value">{{stats.play_count}}</span><i class="icon-play"></i> Plays</div>                 
                <div class="stat"><span class="stat-value">{{stats.user_count}}</span><i class="icon-user"></i> Followers</div>                 
                <div class="stat"><span class="stat-value">{{stats.love_count}}</span><i class="icon-heart"></i> Loved songs</div>                
                <?php } ?>
            </div>
        </div>         
        
        <?php if(!isset($ploved)) { ?>
        <hr />
        <div class="widget-content"> 
            <table class="table table-condensed span8" style="margin-bottom:0px;">
                <thead>
                    <tr>
                        <th width="65%">History</th>
                        <th width="15%">User</th>					
                        <th width="20%">Date</th>					
                    </tr>
                </thead>
                {{#each history.songs}}
                    <tr>
                        <td><a href="{{song.url}}" target="_blank">{{song.title}}</a></td>
                        <td><a href="/user/show/{{user.username}}">{{user.username}}</a></td>
                        <td>{{on}}</td>
                    </tr>
                {{/each}}
            </table>
        </div>
       <?php } ?>
    </div>
 
</script>

<?php if(!isset($ploved)) { ?>
<script id="song-search-results-template" type="text/x-handlebars-template">
    {{#entry}}
    <li class="sr-item {{#if this.yt$noembed}}no-embed{{/if}} bg-tile" data-song-url="{{link.0.href}}" data-song-title="{{media$group.media$title.$t}}">
        <!-- {{#if this.yt$noembed}}<b style="color:red;">[No embedd]</b>{{/if}} -->
        <img src="{{media$group.media$thumbnail.0.url}}">
        <div style="height: 80px;">
            <h3><a href="#">{{media$group.media$title.$t}}</a></h3>
            <span class="sr-result-meta-duration">Duration: {{secondsToMinutes media$group.yt$duration.seconds}}</span><br />
            <span class="sr-result-meta-views">Views: {{parseViews yt$statistics.viewCount}}</span>
        </div>	
        {{#if gd$rating}}
        <div class="sr-result-rating">
            <span class="positive" style="width: {{positivesFromRating gd$rating.average}}%"></span>
            <span class="negative" style="width: {{negativesFromRating gd$rating.average}}%"></span>
        </div>
        {{/if}}			
    </li>
    {{/entry}}
</script>	
<?php } ?>
      
<script type="text/javascript" src="/assets/js/fusic-player.js"></script>

<script>
    
    $('.meta-tab').live('click', function(e) {  ShowBox($(this).attr('data-id')); });	
    $('#btn-search-songs-new').live('click', function(e) { ShowBox('search'); });	
    
    function ShowBox(item) {            
        // hide all the other meta-boxes and disable active tabs
        $('.meta-tab').each(function(index) {                
            $(this).removeClass('active');                
            $('#meta-box-' + $(this).attr('data-id')).hide();
        });

        // show meta box and activate tab
        $('.meta-tab[data-id="'+item+'"]').addClass('active');
        $('#meta-box-'+item).show();  
    }
    
    function reloadPlaylistView(display) {
        display = display || false;			
        var playlistresults = $('#meta-box-playlist');
        var playlistsource = $("#playlist-songs").html();
        var playlisttemplate = Handlebars.compile(playlistsource);
        playlistresults.html(playlisttemplate(globdata));
        
        Player.Load(globdata);    
        $('.sortable').sortable().unbind('sortupdate').bind('sortupdate',function(e, ui) { Player.SongDragEnd(e, ui); });
    }

    function reloadFollowersView(display) {
        display = display || false;			
        var followerresults = $('#meta-box-followers');
        var followersource = $("#playlist-followers").html();
        var followertemplate = Handlebars.compile(followersource);
        followerresults.html(followertemplate(globdata));	
    }

    function reloadStatsView(display) {
        display = display || false;			
        var results = $('#meta-box-stats');
        var source = $("#playlist-stats").html();
        var template = Handlebars.compile(source);
        results.html(template(globdata));
    }		
    
    
    var globdata = -1;		
    var songid = -1;
    
    var refreshrate = 1000 * 30;
    var refreshid = 0;    
    
    $(document).ready(function() 
    {
        Player.Init(<?php echo $playlist->id; ?>);
        refreshid = setInterval(function() { reloadData(); },  refreshrate);
        reloadData();
    });
    
    function reloadData() {
        <?php if(!isset($ploved)) { ?>
        var feed = '/ajax/get_playlist';
        <?php } else { ?>
        var feed = '/ajax/get_loved_songs';
        <?php } ?>
        $.getJSON(feed, { pid: <?php echo $playlist->id; ?>, sid:Player.activeid }, function(data) 
        {				
            globdata = data;                
            reloadPlaylistView();
            reloadStatsView();		
            <?php if(!isset($ploved)) { ?> reloadFollowersView(); <?php } ?>            
            
            Player.UpdateStatus();
            
            $('.dropdown-toggle').dropdown();
        });	
    }		
            
    /* SONG SEARCH */
    var timer;
    $('#song-search').keyup(function() { 

        clearTimeout(timer);		
        var input = $(this);
        var results = $('#song-search-results');
        var val = $(this).val();

        if (val.length >= 1) {
            timer = setTimeout(function() {
                results.html('');
                $.getJSON('http://gdata.youtube.com/feeds/api/videos?callback=?', { q: val, alt: 'json', orderby:'relevance' }, function(data) {
                    data = data['feed'];
                    var source = $("#song-search-results-template").html();
                    var template = Handlebars.compile(source);
                    results.html(template(data));
                    results.show();
                });					
            }, 300);
        }
        else {
            results.hide();
            input.removeClass('dropdown-open');
        }
    });	
    
</script>
<?php } ?>	
				
			
