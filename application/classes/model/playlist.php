<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_Playlist extends ORM {

	protected $_belongs_to = array(
		'creator' => array('model' => 'user', 'foreign_key' => 'creator_id')
	);
	protected $_has_many = array(
		'users' => array('model' => 'user', 'far_key' => 'user_id', 'through' => 'contributors'),
		'songs' => array('model' => 'song', 'foreign_key' => 'playlist_id'),
		'histories'=> array('model' => 'history', 'foreign_key' => 'playlist_id'),
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 3)),
				array('max_length', array(':value', 64)),
			)	
		);
	}
	
	public function get_songs() {
		return $this->songs->order_by('id')->where('deleted','=',0)->find_all();
	}
	
	public function get_followers() {
		return $this->users->find_all();
	}
	
	public function get_contributors() {
		return $this->users->find_all();
	}
	
	public function get_listeners() {
		return ORM::factory('user')->where('playlist_lock','=',$this->id)->where('song_lock','!=',0)->find_all();
	}
	
	public function get_follower_history() {
		return ORM::factory('contributor')->where('playlist_id','=',$this->id)->order_by('followdate','DESC')->find_all();
	}
	
	public function get_song_history() {
		return $this->songs->order_by('added','DESC')->find_all();
	}
	
	
	public function create_playlist($name, $tags, $cover, $description)
	{
		$validated = $this->validate_fields($name, $tags, $cover, $description);
		if($validated) {
			$this->name = $name;
			$this->genres = $tags;
			$this->cover = $cover;
			$this->description = $description;
			$this->creator_id = Auth::instance()->get_user()->id;
			$this->save();
			
			return $this->id;
		}
		else {
			return false;
		} 	
	}	
	
	public function update_fields($name, $tags, $cover, $description) {
		$validated = $this->validate_fields($name, $tags, $cover, $description);
		if($validated) {
			$this->name = $name;
			$this->genres = $tags;
			$this->cover = $cover;
			$this->description = $description;
			$this->save();
			
			return $this->id;
		}
		else {
			return false;
		} 	
	}	
	
	public function validate_fields($name, $tags, $cover, $description) 
	{
		$tagarr = explode(',',$tags);
		$tagcount = count($tagarr);
		$descarr = explode(' ',$description);
		$descwordcount = count($descarr);
		
		if(strlen($name) < 3) {
			throw new Exception("Your playlist should be at least 3 characters long");
		}
		else {	
			if(strlen($cover) < 15) {
				throw new Exception("You should provide a valid cover URL");
			}
			else {
				if($tagcount < 3 || $tagcount > 5) {
					throw new Exception("You should provide at least 3 and up to 5 tags (comma sepperated)");
				}
				else {
					foreach($tagarr as $tag) {
						if(strlen($tag) < 2) {
							throw new Exception("One of the tags you provided is too short. A tag should be at least 2 characters long");
						}
						if(strlen($tag) > 15) {
							throw new Exception("One of the tags you provided is too long. A tag should be up to 15 characters long");
						}
					}					
				
					if($descwordcount < 5) {
						throw new Exception("You should provide a brief playlist description containing at least 5 words");
					}
					else {	
						return true;
					}
				}	
			}
		}		
	}
	
	
	public function get_song_play_history($offset=0, $limit=50) 
	{
		/* get the full playlist history */
		$result = ORM::factory('history')->where('playlist_id','=',$this->id)->order_by('timestamp','DESC')->find_all()->as_array();
		
		/* determine stats for further searches etc. */
		$count = count($result);
		$subset = array_slice($result, $offset, $limit);
		$left = $count - ($limit + $offset);
		
		$rdata = array();
		$rdata['info'] = array('total' => count($result), 'offset' => $offset, 'limit' => $limit);
		$rdata['songs'] = $subset;
		if($left > 0) {
			$rdata['more'] = array('offset' => $offset + $limit, 'limit' => $limit);
		}
		
		return $rdata;
	}
	
	public function get_total_song_loves() 
	{
		return ORM::factory('love')->where('playlist_id','=',$this->id);
	}
	
	/* [ LOCKING ] */
	
	public function lock() {
		$user = Auth::instance()->get_user();
		if($user->isOwner($this)) {
			$this->locked = 1;
			$this->save();
		}
		else {
			throw new Kohana_User_Exception('Cannot lock playlist', 'You are not the owner of this playlist');
		}
	}
		
	public function unlock() {
		$user = Auth::instance()->get_user();
		if($user->isOwner($this)) {
			$this->locked = 0;
			$this->save();	
		}
		else {
			throw new Kohana_User_Exception('Cannot unlock playlist', 'You are not the owner of this playlist');	
		}
	}	
	
	public function get_latest($limit) {
		return ORM::factory('playlist')->limit($limit)->order_by('id','desc')->where('deleted','=',0)->find_all();
	}
	
	
	public function get_cover_view($size='normal', $dummy=false) {
	
		$view = null;
		if($dummy) {
			$view = View::factory('playlists/cover.dummy');
			$view->size = $size;
		}
		else {
			$view = View::factory('playlists/cover');
			$view->size = $size;
			$view->playlist = $this;
		}
		return $view;
	}
	
	public function tags_to_array() 
	{
		return array_map('trim', explode(",", $this->genres));
	}
	
	
	
	/* [ GETTERS ] */
	
	public function get_cover() {
		return empty($this->cover) ? Kohana::$config->load('settings.playlist-default-img') : $this->cover;
	}
	
	public function is_public() { return ($this->locked == 0); }
	public function is_private() { return ($this->locked == 1); }
	
	
	/* [ STATS / COUNTING ] */
	
	public function get_follower_count() {
		return $this->users->count_all();
	}
	
	public function get_song_count() {
		return $this->songs->where('deleted','=',0)->count_all();
	}
	
	public function get_song_play_count() {
		return ORM::factory('history')->where('playlist_id','=',$this->id)->distinct('id')->count_all();
	}
	
	public function count() {
		return ORM::factory('playlist')->distinct('id')->count_all();
	}
    
    public function maxorder() {
        return (int)DB::select('order')->from('songs')->where('playlist_id','=',$this->id)->order_by('order', 'desc')->limit(1)->execute()->get('order');
    }
	
	public function get_link() {
		return "/playlist/show/".$this->id;
	}
	
	public function to_array() {
		return array('id' => $this->id, 
					 'name' => $this->name, 
					 'cover' => $this->get_cover(), 
					 'tags' => $this->tags_to_array(),
					 'creator' => $this->creator->to_array());
	}
	
}