<?php defined('SYSPATH') or die('No direct access allowed.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_User extends Model_Auth_User {

	protected $_has_many = array(
		'user_tokens' => array('model' => 'user_token'),
		'roles'       => array('model' => 'role', 'through' => 'roles_users'),
		'follows' 	  => array('model' => 'playlist', 'far_key' => 'playlist_id', 'through' => 'contributors'),
		'added_songs' => array('model' => 'song'),
		'lovedsongs'  => array('model' => 'love'),
		'following'   => array('model' => 'contributor'),
		'playlists' => array(
			'model' => 'playlist',
			'foreign_key' => 'creator_id'
		),
		'friends'     => array(
			'model'       => 'user',
			'through'     => 'friendships',
			'far_key'     => 'user_id',
			'foreign_key' => 'friend_id',				
		),
		'requests'    => array(
			'model'       => 'user',
			'through'     => 'requests',
			'foreign_key' => 'request_from',
			'far_key'     => 'request_to'
		)
	);		
	
	public function is_friend($user) {
		return count(DB::select()->from('friendships')->where('user_id', '=', $this->id)->where('friend_id', '=',$user->id)->as_object()->execute()) > 0;
	}
	
	public function exists_username($username) { 
		$orm = ORM::factory('user')->where('username','=',$username);
		if($this->loaded()) {
			$orm = $orm->where('id','!=',$this->id);
		}			
		return $orm->find()->loaded();
	}
	
	public function exists_email($email) { 
		$orm = ORM::factory('user')->where('email','=',$email);
		if($this->loaded()) {
			$orm = $orm->where('id','!=',$this->id);
		}			
		return $orm->find()->loaded();
	}
	
	public function update_data($username, $email, $bio) 
	{
		$validated = $this->validate_fields($username, $email, $bio);
		if($validated) {
			$this->username = $username;
			$this->email = $email;
			$this->bio = $bio;
			$this->save();			
			return $this->id;
		}
		else {
			return false;
		} 	
	}
	
	public function validate_fields($username, $email, $bio) 
	{		
		if(empty($username) || strlen($username) < 3) {
			throw new Exception("Your username should be at least 3 characters long");
		}
		else if($this->exists_username($username)) {
			throw new Exception("The username <strong>".$username."</strong> has already been taken");
		}
		else if(preg_match('/[^a-zA-Z0-9]/i', $username)) {
			throw new Exception("Your username should contain alphanumeric characters only");
		}
		else if(!Tools::validatemail($email)) {
			throw new Exception("You should provide a valid email address");
		}
		else if($this->exists_email($email)) {
			throw new Exception("The email address <strong>".$email."</strong> has already been registered");
		}
		else {
			return true;
		}
	}
	
	public function get_friends_count() { return $this->friends->count_all(); }
	public function get_friends($limit=0, $notself=false) {
		$orm = $this->friends;
		if($limit > 0) {
			$orm = $orm->limit($limit);
		}
		if($notself) {
			$orm = $orm->where('friend_id','!=',Auth::instance()->get_user()->id);
		}
		return $orm->order_by('id','asc')->find_all();
	}	
	
	public function get_friend_status($friend) {
		
		if($this->is_friend($friend)) {
			return 'yes';
		}
		else {
			$pendings = Model::factory('request')->get_pending_requests_from_me($this->id)->execute();
			foreach($pendings as $pending) {
				if($friend->id == $pending->request_to) {
					return 'pending';
				}
			}
			return 'no';
		}		
	}
	
	
	public function get_playlists_count() { return $this->playlists->count_all(); }
	public function get_playlists($limit=0) {
		$orm = $this->playlists->where('deleted','=',0);
		if($limit > 0) {
			$orm = $orm->limit($limit);
		}
		return $orm->order_by('id','asc')->find_all();
	}
	
	public function get_following_playlists($limit=0) {
		return $this->follows->where('deleted','=',0)->where('contributors.following','=',1)->find_all();
	}	

	public function get_public_playlists() {
		return ORM::factory('playlist')->where('creator_id','=',$this->id)->where('deleted','=',0)->where('locked','=',0)->order_by('id','asc')->find_all();
	} 
    
	public function is_following_playlist($playlist) {
		return ORM::factory('contributor')->where('user_id','=',$this->id)->where('playlist_id','=',$playlist->id)->where('following','=',1)->find()->loaded();
	}
	
	public function is_active_in_playlist($playlist) {
		return $this->playlist_lock == $playlist->id;
	}
	
	public function can_edit_playlist($playlist) {
		
		$access = false;
		if($playlist->creator->id == $this->id) {
			return true;
		}
		else {
			return $this->get_privilege($playlist) == 'edit';
		}
		return $access;		
	}
	
	
	public function get_active_playlist() {
		if($this->playlist_lock > 0) {
			return ORM::factory('playlist',$this->playlist_lock);
		}
		else {
			return false;
		}
	}
	
	public function get_active_song() {
		if($this->song_lock > 0) {
			return ORM::factory('song',$this->song_lock);
		}
		else {
			return false;
		}
	}

	public function loves_song($song) {
		return ORM::factory('love')->where('user_id','=',$this->id)->where('song_id','=',$song->id)->find()->loaded();
	}
	
	public function love_song($song) {
		$love_relation = ORM::factory('love');
		$love_relation->user_id = $this->id;
		$love_relation->song_id = $song->id;
		$love_relation->playlist_id = $song->playlist->id;
		$love_relation->loved = time();
        $love_relation->order = ORM::factory('love')->max($this->id) + 1;
		$love_relation->save();
	}
	
	public function unlove_song($song) {
		$relation = ORM::factory('love')->where('user_id','=',$this->id)->where('song_id','=',$song->id)->find();
		if($relation->loaded()) {
			$relation->delete();
		}
	}
	
	public function follow_playlist($playlist) {
		$relation = ORM::factory('contributor')->where('playlist_id','=',$playlist->id)->where('user_id','=',$this->id)->find();
		if(!$relation->loaded()) {
			$relation = ORM::factory('contributor');
			$relation->user_id = $this->id;
			$relation->playlist_id = $playlist->id;
		}
		$relation->following = 1;
		$relation->followdate = time();			
		$relation->save();
		return true;
	}
	
	public function unfollow_playlist($playlist) {
		$relation = ORM::factory('contributor')->where('playlist_id','=',$playlist->id)->where('user_id','=',$this->id)->find();
		if($relation->loaded()) {
			$relation->unfollowdate = time();
			$relation->following = 0;
			$relation->save();
			return true;
		}
		return false;
	}
	
	public function get_privilege($playlist) {
		$relation = ORM::factory('contributor')->where('user_id','=',$this->id)->where('playlist_id','=',$playlist->id)->find();
		if($relation->loaded()) {
			return $relation->privilege;
		}
	}
	
	public function set_privilege_editor($playlist) {
		if($this->id !== $playlist->creator->id) {
			$relation = ORM::factory('contributor')->where('user_id','=',$this->id)->where('playlist_id','=',$playlist->id)->find();
			if($relation->loaded()) {
				$relation->privilege = 'edit';
				$relation->save();
				return true;
			}
			else {
				return false;
			}
		}
	}
	public function set_privilege_viewer($playlist) {
		if($this->id !== $playlist->creator->id) {
			$relation = ORM::factory('contributor')->where('user_id','=',$this->id)->where('playlist_id','=',$playlist->id)->find();
			if($relation->loaded()) {
				$relation->privilege = 'view';
				$relation->save();
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	public function isOwner($playlist) {
		return $playlist->creator->id == $this->id;
	}
	
	public function to_array() {
		return array('id' => $this->id, 'name' => $this->username, 'cover' => $this->get_cover());
	}
    
    public function to_playlist_array($playlist) {
        
        $data = array();
                
        $data['id'] = $this->id;
        $data['username'] = $this->username;
        $data['isactive'] = $this->is_active_in_playlist($playlist) ? 'yes' : 'no';
        $data['friends_count'] = $this->get_friends_count();
        $data['playlists_count'] = $this->get_playlists_count();
        $data['cover'] = $this->get_cover();
        $data['privilege'] = $this->get_privilege($playlist);
        
        if($this->isOwner($playlist)) {
            $data['isadmin'] = 'yes';
        }
        else {
            $privilege = ($data['privilege'] == 'edit') ? 'iseditor' : 'isviewer';
            $data[$privilege] = 'yes';
        }
               
        $activesong = $this->get_active_song();			
        if($activesong && $activesong->is_from_playlist($playlist)) {
            $data['listening'] = array('id'=>$activesong->id, 'title' => $activesong->title, 'url' => $activesong->url);
        }
    
        return $data;
    }
	
	public function count() {
		return ORM::factory('user')->distinct('id')->count_all();
	}

	public function get_cover_view($size='normal', $username=false) {
	
		$view = View::factory('user/cover');
		$view->size = $size;
		$view->user = $this;
		$view->username = $username;
		
		return $view;
	}
    
    public function ping($playlistid) {
        $this->playlist_lock = $playlistid;
		$this->last_ping = time();
		$this->save();	
    }
	
	public function get_link() {
		return "/user/show/".$this->username;
	}	
	
	public function has_cover() { return !empty($this->cover); }
	public function get_cover() {
		return empty($this->cover) ? Kohana::$config->load('settings.user-default-img') : "/assets/img/pictures/".$this->cover;
	}
	
}

?>