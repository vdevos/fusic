<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

function compare_date_desc($a, $b) {
	if($a['date']  == $b['date']) { 
		return 0; 
	}
	return ($a['date'] < $b['date']) ? +1 : -1;
}	

function compare_privilege($a, $b) {    
    if($a['privilege']  == $b['privilege']) { 
		return 0; 
	}
	return ($a['privilege'] < $b['privilege']) ? -1 : +1;
}

function compare_order($a, $b) {
	if($a['order']  == $b['order']) { 
		return 0; 
	}
	return ($a['order'] < $b['order']) ? -1 : +1;
}	


class Controller_Ajax extends Controller {

	public function before()
	{
		parent::before();

		$this->data = array();
		$this->response->headers('Content-Type', 'application/json');
		
		if (!Auth::instance()->logged_in())
		{
			$this->request->redirect('/user/login');
		}	
		
		$this->limit = Arr::get($_GET, 'limit', 10);	
		$this->offset = Arr::get($_GET, 'offset', 0);	
		$this->user = Auth::instance()->get_user();
	}
	
		
	public function after() 
	{	
		$this->response->body(json_encode($this->data));
		parent::after();
	}
	
	
	public function action_index() { }
	
	public function action_get_playlist() {
		
		$sid = Arr::get($_GET, 'sid', NULL);		
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$playlist = ORM::factory('playlist',$pid);	
		
        /* Update the user information: locking playlist/song */
        
		if($sid == -1) /* the current user has no active song */			
        { 			
            /* Lock playlist */
            if($playlist->loaded()) {
				$this->user->playlist_lock = $playlist->id;
			}            
            /* Set songlock to 0 */
			$this->user->song_lock = 0;
			$this->user->last_ping = time();
			$this->user->save();
		}
		else /* set the users active song */
        {			
			$song = ORM::factory('song',$sid);			
			if($song->loaded()) {				
				$this->user->song_lock = $sid;				
				$this->user->playlist_lock = $song->playlist->id;
			}
			else {
				$this->user->playlist_lock = $playlist->id;
			}			
			$this->user->last_ping = time();
			$this->user->save();
		}
        
        /* Get all the data that is relevent for this playlist */
		
		if($playlist->loaded()) {
			
			$follower_active_count = 0;
			$follower_listening_count = 0;
			
			/* CREATOR & FOLLOWERS INFO */
            $this->data['followers'] = array();
            
			$creatordata = $playlist->creator->to_playlist_array($playlist);
            $this->data['followers'][] = $creatordata;
			if($creatordata['isactive'] == 'yes') {
                $follower_active_count++;
            }		            
                        
            $followers = $playlist->get_followers();			
			foreach($followers as $fol) {		
                $data = $fol->to_playlist_array($playlist);
				$this->data['followers'][] = $data;
                if($data['isactive'] == 'yes') {
                    $follower_active_count++;
                }		
			}
            usort($this->data['followers'], "compare_privilege");
			
			/* PLAYLIST INFO */
			$this->data['playlist'] = array('id' => $playlist->id, 'name' => $playlist->name, 'created' => $playlist->created, 'creator' => $creatordata);
			
			/* SONGS */
            $duration = 0;
			$songs = $playlist->songs->where('deleted','=',0)->order_by('id')->find_all();
			foreach($songs as $song) {            
				$this->data['songs'][] = array(
							  'id' => $song->id,
							  'title' => $song->title,
							  'date' => $song->added,                              
                              'order' => $song->order,
							  'added_by' => array('id' => $song->user->id, 'username' => $song->user->username),
							  'url' => $song->url,
							  'duration' => $this->secs2mins($song->duration),
							  'isloved' => $this->user->loves_song($song) ? 'yes' : '',
							  'lovecount' => count($song->loves->find_all()),
							  'playcount' => $song->played_count,
							  'locked_by' => $song->get_active_users(),
							);
                $duration += $song->duration;
			}	
            
            if(isset($this->data['songs'])) {
                usort($this->data['songs'], "compare_order");             
            }
            
			
			/* SONG HISTORY */
			$history = $playlist->get_song_play_history(0,50);
			$history_count = $history['info']['total'];
			$this->data['history']['info'] = $history['info'];
			if(array_key_exists('more', $history)) {
				$this->data['history']['more'] = $history['more'];
			}
			foreach($history['songs'] as $hist) {
				$song = $hist->song;
				$user = $hist->user;
				$this->data['history']['songs'][] = array(
													'on' => date('d/m/Y \o\n H:i:s', $hist->timestamp),
													'song' => array('id' => $song->id, 'title' => $song->title, 'url' => $song->url),
													'user' => array('id' => $user->id, 'username' => $user->username)
												  );
			}
			
			
			
			/* FRIENDS ONLINE
			$friends = $this->action_get_friends();
			$this->data['friends'] = $friends['friends'];
			*/
			
			/* STATS */
			$this->data['stats']['song_count'] = count($songs);
			$this->data['stats']['user_count'] = count($followers) + 1; // + 1 for the owner self
			$this->data['stats']['play_count'] = $history_count;
			$this->data['stats']['love_count'] = count($playlist->get_total_song_loves()->find_all());
			$this->data['stats']['follower_active_count'] = $follower_active_count;
            $this->data['stats']['total_duration'] = $this->secs2mins($duration);
		}
		else {
			$this->data['error'] = "No such playlist exists";
		}
	}
	
	public function action_get_loved_songs() {       
        
        $duration = 0;
        $totallovecount = 0;
        $loves = $this->user->lovedsongs->find_all();
        
        foreach($loves as $love) {            
            $song = $love->song;
            $lovecount = count($song->loves->find_all());
            $this->data['songs'][] = array(                          
                          'id' => $song->id,
                          'title' => $song->title,
                          'date' => $love->loved,
                          'order' => $love->loved, // TODO - Feature: Order - Sort by order when fully implemented // $loved->order
                          'added_on' => $song->added,
                          'added_by' => array('id' => $song->user->id, 'username' => $song->user->username),
                          'added_to' => array('id' => $song->playlist->id, 'name' => $song->playlist->name),
                          'url' => $song->url,
                          'duration' => $this->secs2mins($song->duration),
                          'isloved' => $this->user->loves_song($song) ? 'yes' : '',
                          'lovecount' => $lovecount,
                          'playcount' => $song->played_count,
                          'locked_by' => $song->get_active_users(),
                        );
            $duration += $song->duration;
            $totallovecount += $lovecount;
        }	
                
        /* STATS */
        $this->data['stats']['song_count'] = count($loves);
        $this->data['stats']['love_count'] = $totallovecount;
        $this->data['stats']['total_duration'] = $this->secs2mins($duration);
        
        $this->data['stats']['user_count'] = 0;
        $this->data['stats']['play_count'] = 0; // count($playlist->get_total_song_loves()->find_all());
        $this->data['stats']['follower_active_count'] = 0; //$follower_active_count;   
        
        if(isset($this->data['songs'])) {
            usort($this->data['songs'], "compare_order");
        }
	}
	
	/* [ 1a: YOUR PLAYLIST ACTIVITY ] */
	public function action_get_your_playlists_activity() 
	{	
		$playlists = $this->user->get_playlists();
		$this->get_playlist_activity($playlists);
	}
	
	/* [ 1b: FOLLOWING PLAYLIST ACTIVITY ] */
	public function action_get_following_playlists_activity() 
	{	
		$playlists = $this->user->get_following_playlists();
		$this->get_playlist_activity($playlists);
	}
	
	/* [ 2: WRAPPER FOR THE PLAYLIST ACTIVITIES ] */
	public function get_playlist_activity($playlists) {
		
		$count = 0;
		$this->data['activity'] = array();
		$this->limit = ($this->limit <= 25) ? $this->limit : 25;
		
		foreach($playlists as $playlist) {
			$follow_history = $playlist->get_follower_history();
			foreach($follow_history as $hist) {
				if($hist->is_following()) {
					$count++;
					$this->data['activity'][] = array('type' => 'follow', 'date' => $hist->followdate, 'hash' => 'fw'.$hist->followdate,
													  'playlist' => $playlist->to_array(),
													  'user' => $hist->user->to_array());		
				}	
				/*
				if(!$hist->is_following()) { 
					$count++;
					$this->data['activity'][] = array('type' => 'unfollow', 'date' => $hist->unfollowdate, 'hash' => 'uw'.$hist->unfollowdate,
								  'playlist' => $playlist->to_array(),
								  'user' => $hist->user->to_array());								  
				}
				*/
			}
			unset($follow_history);		
			
			$songs = $playlist->get_song_history();
			foreach($songs as $song) {	
				if(!$song->is_deleted()) {
					$count++;
					$this->data['activity'][] = array('type' => 'added', 'date' => $song->added, 'hash' => 'ad'.$song->added,
													   'song' => $song->to_array(),
													   'playlist' => $playlist->to_array(),
													   'user' => $song->user->to_array());
				}
				/*
				if($song->is_deleted()) {
					$count++;
					$this->data['activity'][] = array('type' => 'deleted', 'date' => $song->added, 'hash' => 'rm'.$song->added,
											       'song' => $song->to_array(),
												   'playlist' => $playlist->to_array(),
												   'user' => $song->user->to_array());
				}
				*/
			}
			unset($songs);	
		}			
			
		$this->data['more'] = (($count - ($this->offset + $this->limit)) > 0) ? 'yes' : 'no';
		usort($this->data['activity'], "compare_date_desc"); /* sort this array from new to old */
		$this->data['activity'] = array_slice($this->data['activity'], $this->offset, $this->limit);
		unset($playlists);	
	}
    
    public function action_swap_position_lovedsong() {
        
        $songid     = Arr::get($_GET, 'sid', NULL);        
        $fromindex  = Arr::get($_GET, 'from', NULL);
        $toindex    = Arr::get($_GET, 'to', NULL);
        
        if($songid) 
        {   
            $loves   = $this->user->lovedsongs->order_by('order','desc')->find_all();
            $song    = ORM::factory('love')->where('song_id','=',$songid)->where('user_id','=',$this->user->id)->find();           
            /*
            if($song->loaded()) 
            {                
                if($fromindex > $toindex) // moved up the list
                {                      
                    foreach($loves as $love) {
                        $this->data['loved'][] = array('order' => $love->song->order, 'data' => $love->song->to_array());
                    }
                }
                else if($fromindex < $toindex) { // moved down the list
                
                }             
                $this->data['success'] = "Yes";
            }
            else {
                $this->data['error'] = "Invalid song id(s)";
            }   
            */
        }
        else {
            $this->data['error'] = "Invalid song id(s)";
        }    
    }  
    
    public function action_swap_position_song() 
    {      
        $pid = Arr::get($_GET, 'pid', NULL);
        $playlist = ORM::factory('playlist',$pid);
        $songidfrom = Arr::get($_GET, 'from', NULL);
        $songidto = Arr::get($_GET, 'to', NULL);
        /*
        
        if($playlist && $this->user->can_edit_playlist($playlist)) {
        
            if($songidfrom && $songidto) 
            {            
                $songfrom = ORM::factory('song')->where('id','=',$songidfrom)->where('playlist_id','=',$pid)->find();
                $songto   = ORM::factory('song')->where('id','=',$songidfrom)->where('playlist_id','=',$pid)->find();
                
                if($songfrom->loaded() && $songto->loaded()) 
                {                
                    $fromorder = $songfrom->order;
                    $songfrom->order = $songto->order;
                    $songto->order = $fromorder;
                    $songfrom->save();
                    $songto->save();
                    $this->data['success'] = "Yes";
                }
                else {
                    $this->data['error'] = "Invalid song id(s)";
                }           
            }
            else {
                $this->data['error'] = "Invalid song id(s)";
            }   
        }
        */
    }
	
	public function action_user_search() {
		
		/* search users where the use is not the owner of the playlist and not already occurs as contributor */		
		$q = Arr::get($_GET,'q',NULL);
		
		if(strlen($q) >= 1) {
			
			$curFriends = $this->user->friends->find_all();
			$potFriends = ORM::factory('user')->where('username','like',"%$q%")->where('username','!=', 'system')->find_all();
			
			foreach($potFriends as $potFriend) {
				$exists = false;
				if($this->user->id !== $potFriend->id) {
					foreach($curFriends as $curFriend) {
						if($potFriend->id == $curFriend->id) {
							$exists = true;
						}
					}
					if(!$exists) {
						$this->data[$potFriend->id] = $potFriend->username;
					}
				}
			}
		}
	}
	
	public function action_playlist_user_add() {
	
		$uid = Arr::get($_GET, 'uid', NULL);
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$playlist = ORM::factory('playlist',$pid);
		$user = ORM::factory('user',$uid);
		
		if(!$playlist->loaded()) {
			$this->data['error'] = "there exists no playlist with this id";
		}
		else if(!$user->loaded()) {
			$this->data['error'] = "there exists no user with this id";
		}
		else {
			$contributor = ORM::factory('contributor');
			$contributor->playlist_id = $playlist;
			$contributor->user_id = $user;
			$contributor->save();
			$this->data['success'] = 'User added to playlist';
		}
	}
	
	public function action_playlist_user_remove() {
	
		$this->data = array();
		$uid = Arr::get($_GET, 'uid', NULL);
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$playlist = ORM::factory('playlist',$pid);
		$user = ORM::factory('user',$uid);
		
		if(!$playlist->loaded()) {
			$this->data['error'] = "there exists no playlist with this id";
		}
		else if(!$user->loaded()) {
			$this->data['error'] = "there exists no user with this id";
		}
		else {
			$cobj = ORM::factory('contributor')->where('playlist_id','=',$playlist->id)->where('user_id','=',$user->id)->find();
			if($cobj->loaded()) {
				$cobj->delete();
				$this->data['success'] = "removed user from playlist";
			}
			else {
				$this->data['error'] = "Playlist contains no such user";
			}
		}
	}
	
	public function action_playlist_follow() 
	{
		$pid = Arr::get($_GET, 'pid', NULL);	
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			$this->user->follow_playlist($playlist);
			$this->datas['success'] = 'yes';
		}
		else {
			$this->data['error'] = 'yes';
		}
	}
	
	public function action_playlist_unfollow()
	{
		$this->data = array();
		$pid = Arr::get($_GET, 'pid', NULL);	
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			if($this->user->unfollow_playlist($playlist)) {
				$this->data['success'] = 'yes';
			}
			else {
				$this->data['error'] = 'yes';
			}
		}
		else {
			$this->data['error'] = 'yes';
		}
	}
	
	public function action_playlist_lock() 
	{
		$pid = Arr::get($_GET, 'pid', NULL);	
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			$playlist->lock();
			$this->data['success'] = 'yes';
		}
		else {
			$this->data['error'] = 'yes';
		}
	}
	
	public function action_playlist_unlock() 
	{
		$pid = Arr::get($_GET, 'pid', NULL);	
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			$playlist->unlock();
			$this->data['success'] = 'yes';
		}
		else {
			$this->data['error'] = 'yes';
		}
	}
	
	
	public function action_user_active_song() 
	{	
		$sid = Arr::get($_GET, 'sid', NULL);
		$song = ORM::factory('song',$sid);
		if($song->loaded()) {
			$this->user->song_lock = $sid;	
			$this->user->save();
			$this->data['success'] = "yes";
		}
		else {
			$this->data['error'] = "song does not exist";
		}
	}
	
	public function action_song_add_played() {
		
		$sid = Arr::get($_GET, 'sid', NULL);
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$song = ORM::factory('song',$sid);
		$playlist = ORM::factory('playlist',$pid);		
		
		if(!$song->loaded()) {
			$this->data['error'] = "there exists no song with this id";
		}	
		else if(!$playlist->loaded()) 
		{
			$this->data['error'] = "there exists no playlist with this id";	
		}
		else {
			$hist = ORM::factory('history');
			$hist->user_id = $this->user->id;
			$hist->playlist_id = $playlist->id;
			$hist->song_id = $sid;
			$hist->timestamp = time();
			$hist->save();
		
			$song->played_count++;
			$song->save();

			$this->data['success'] = "yes";
		}
	}
	
	
	
	public function action_playlist_song_add() {
		
		$pid = Arr::get($_GET, 'pid', NULL);
		$url = Arr::get($_GET, 'url', NULL);		
		$playlist = ORM::factory('playlist',$pid);
		
		if(!$playlist->loaded()) {
			$this->data['error'] = "there exists no playlist with this id";
		}
		
		$ytdata = $this->get_youtube_data($url);
		
		if($this->user->can_edit_playlist($playlist)) {
			$song = ORM::factory('song');
			$song->playlist = $playlist;
			$song->user = $this->user;
			$song->url = $url;
			$song->title = $ytdata['title'];
			$song->description = $ytdata['description'];
			$song->keywords = $ytdata['keywords'];
            $song->order = $playlist->maxorder() + 1;
			$song->thumbnail = $ytdata['thumbnail'];
			$song->duration = $ytdata['duration'];
			$song->added = time();
			$song->save();
			$this->data['success'] = 'Song added to playlist';
		}
		else {
			$this->data['error'] = "user has no permission to edit this playlist";
		}	
	}
	
	public function action_playlist_song_remove() {
	
		$sid = Arr::get($_GET, 'sid', NULL);
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$playlist = ORM::factory('playlist',$pid);		
		$song = ORM::factory('song',$sid);
		
		if(!$playlist->loaded()) {
			$this->data['error'] = "a playlist with this id does not exist... at least not in this universe!";
		}		
		else if(!$song->loaded()) {
			$this->data['error'] = "a song with this id does not exist... at least not in this universe!";
		}
		else {
			if($this->user->can_edit_playlist($playlist)) {	
				$song->remove_from_playlist();
			}
			else {
				$this->data['error'] = "user has no permission to edit this playlist";
			}
		}
	}
	
	public function action_get_friends() 
	{		
		/* get all friends for user */
		foreach($this->user->friends->find_all() as $friend) {
						
			$rdata = array();
			$rdata['uid'] = $friend->id;
			$rdata['username'] = $friend->username;
			$rdata['playlists'] = array();
			$rdata['follows'] = array();
			
			foreach($friend->get_playlists() as $playlist) {
				$rdata['playlists'][] = array('id'=>$playlist->id,'name'=>$playlist->name);
			}
			
			foreach($friend->follows->find_all() as $playlist) {
				$rdata['follows'][] = array('id'=>$playlist->id,'name'=>$playlist->name);
			} 			
			
			$song = $friend->get_active_song();
			if($song) {
				$rdata['activity']['playlist'] = array('id' => $song->playlist->id, 'name' => $song->playlist->name);
				$rdata['activity']['song'] = array('id' => $song->id, 'title' => $song->title, 'url' => $song->url);
			}
						
			$this->data['friends'][] = $rdata;
		}
		
		/* get all friend requests for user */
		$requests = Model::factory('request')->get_pending_requests_to_me($this->user->id)->execute();
		foreach($requests as $request) {
			$from = ORM::factory('user',$request->request_from);
			$this->data['requests'][] = array('id' => $from->id, 'username' => $from->username);
		}
		
		$pendings = Model::factory('request')->get_pending_requests_from_me($this->user->id)->execute();
		foreach($pendings as $pending) {
			$to = ORM::factory('user',$pending->request_to);
			$this->data['pendings'][] = array('id' => $to->id, 'username' => $to->username);
		}
		return $this->data;
	}
	
	public function action_user_add_friend() 
	{	
		$fid = Arr::get($_GET,'fid',NULL);
		$invitee = ORM::factory('user')->where('id', '=', $fid)->find();
	 
		if (!$this->user->has('friends', $invitee) && !$this->user->has('requests', $invitee)) {
			$this->user->add('requests', $invitee);		
			$this->data['success'] = 'yes';
		}
		else {
			$this->data['error'] = 'friendship already exists';
		}
	}
	
	public function action_user_accept_friend() {
		
		$fid = Arr::get($_GET,'fid',NULL);
		$friend = ORM::factory('user',$fid);
	 
		if (!$this->user->has('friends', $friend)) { /* add relation to both sides */
			$this->user->add('friends', $friend); 
			$friend->add('friends', $this->user); 
		}
	 
		$obj = new Model_Request();
		$request = $obj->get_request($this->user->id, $fid)->execute()->current();
		DB::delete('requests')->where('id', '=', $request->id)->execute();
	}
	
	public function action_user_reject_friend() {

		$fid = Arr::get($_GET,'fid',NULL);
		$friend = ORM::factory('user',$fid);
	 
		if($friend->has('requests', $this->user)) {
			$friend->remove('requests', $this->user);
			$this->data['success'] = 'yes';
		}		
		else {
			$this->data['error'] = 'There are no requests that can be rejected';
		}
	}	
	
	public function action_user_undo_request() {
		$fid = Arr::get($_GET,'fid',NULL);
		$friend = ORM::factory('user',$fid);
		if($this->user->has('requests', $friend)) {
			$this->user->remove('requests', $friend);
			$this->data['success'] = 'yes';
		}
		else {
			$this->data['error'] = 'There are no requests that can be undone';
		}
	}
	
	public function action_user_remove_friend()
	{   
		$fid = Arr::get($_GET,'fid',NULL);
		$friend = ORM::factory('user',$fid);
	 
		if ($friend->loaded()) {
			if ($this->user->has('friends', $friend)) { /* remove the relation to both sides */
				$this->user->remove('friends', $friend); 
				$friend->remove('friends',$this->user);
				$this->data['success'] = 'yes';
			}
			else {
				$this->data['error'] = "friendship does not exist";
			}
		}	
		else {
			$this->data['error'] = "there exists no user with this id";
		}
	}	
		
	public function action_user_love_song() 
	{
		$sid = Arr::get($_GET, 'sid', NULL);
		$arg = Arr::get($_GET, 'arg', NULL);
		
		$song = ORM::factory('song',$sid);
		
		if(!$song->loaded()) {
			$this->data['error'] = "a song with this id does not exist";
		}
		else {
			if($arg == 'yes') {
				$this->user->love_song($song);
			}
			else if($arg == 'no') {
				$this->user->unlove_song($song);
			}
			else {
				$this->data['error'] = "wrong argument";
			}
		}
	}
	
	public function action_set_playlist_viewer() {
		
		$uid = Arr::get($_GET, 'uid', NULL);
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$user = ORM::factory('user',$uid);
		if(!$user->loaded()) {
			$this->data['error'] = 'user with this id does not exist';
		}
		
		$playlist = ORM::factory('playlist',$pid);
		if(!$playlist->loaded()) {
			$this->data['error'] = 'playlist with this id does not exist';
		}
		
		
		$this->data['success'] = ($user->set_privilege_viewer($playlist)) ? 'yes' : 'no';
	}
	
	public function action_set_playlist_editor() {
	
		$uid = Arr::get($_GET, 'uid', NULL);	
		$pid = Arr::get($_GET, 'pid', NULL);
		
		$user = ORM::factory('user',$uid);
		if(!$user->loaded()) {
			$this->data['error'] = 'user with this id does not exist';
		}
		
		$playlist = ORM::factory('playlist',$pid);
		if(!$playlist->loaded()) {
			$this->data['error'] = 'playlist with this id does not exist';
		}
		
		$user->set_privilege_editor($playlist);
		$this->data['success'] = 'yes';	
	}
	
	private function get_youtube_data($video_url) {
		
		$api = 'https://gdata.youtube.com/feeds/api/videos/';
		$params = 'v=2&alt=json';
		
		parse_str(parse_url($video_url, PHP_URL_QUERY), $results);
		$api_video_url = $api . $results['v'] . '?' . $params;
				
		$response = file_get_contents($api_video_url);
		$rdata = json_decode($response, true);
		
		$data = array();
		$data['id'] = $results['v'];
		$data['title'] = $rdata['entry']['title']['$t'];
		$data['description'] = $rdata['entry']['media$group']['media$description']['$t'];
		$data['keywords'] = (isset($rdata['entry']['media$group']['media$keywords']['$t'])) ? $rdata['entry']['media$group']['media$keywords']['$t'] : '';
		$data['author'] = array('name' => $rdata['entry']['author'][0]['name']['$t'], 'url' => $rdata['entry']['author'][0]['uri']['$t']);
		$data['thumbnail'] = $rdata['entry']['media$group']['media$thumbnail'][0]['url'];
		$data['duration'] = $rdata['entry']['media$group']['yt$duration']['seconds'];
		
		return $data;
	}
	
	public function action_get_playlist_stats() {
		
		$pid = Arr::get($_GET, 'pid', NULL);
		$playlist = ORM::factory('playlist',$pid);
		
		$min = 0;
		$max = 0;
		
		$this->data['stats'] = array();
		foreach($playlist->songs->find_all() as $song) 
		{		
			if($min == 0 && $max == 0) {
				$min = $song->added;
				$max = $song->added;
			}	
			else {
				if($song->added < $min) {
					$min = $song->added;
				}
				if($song->added > $max) {
					$max = $song->added;
				}
			}			
		
			$date = gmdate('Y/m/d', $song->added);
			if(isset($this->data['stats'][$date])) {
				$this->data['stats'][$date] += 1;
			}
			else {
				$this->data['stats'][$date] = 1;
			}
		}
		
		$this->data['count'] = count($this->data['stats']) - 1;
		$this->data['xmin'] = $min;
		$this->data['xmax'] = $max;
		$this->data['ymax'] = 100;
		
		//usort($this->data['songs'], "compare_date_desc");
		
			
	}
	
	function secs2mins($seconds=0)
	{
		if($seconds >= 3600) {
			return gmdate("H:i:s", $seconds);
		}
		else {
			return gmdate("i:s", $seconds);
		}
	}
	
}
