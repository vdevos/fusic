<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_Song extends ORM {
	
	protected $_has_many = array(	
		'loves' => array('model' => 'user', 'far_key' => 'user_id', 'through' => 'loves')
	);

	protected $_belongs_to = array(
		'playlist' => array('model' => 'playlist', 'foreign_key' => 'playlist_id'),
		'user' => array('model' => 'user', 'foreign_key' => 'user_id'),
	);
	
	public function get_active_users() {
		$result = array();
		$users = ORM::factory('user')->where('song_lock','=',$this->id)->find_all();
		foreach($users as $user) {
			$result[] = array('id'=>$user->id, 'username'=>$user->username);
		}
		return $result;
	}
	
	public function is_from_playlist($playlist) {
		return ($this->playlist->id == $playlist->id);
	}
	
	public function is_deleted() {
		return ($this->deletedate > 0);
	}
	
	public function remove_from_playlist() {
		$this->deletedate = time();
		$this->deleteby = Auth::instance()->get_user()->id;
		$this->deleted = 1;
		$this->save();		
	}
	
	public function to_array() {
		return array('id' => $this->id, 'title' => $this->title, 'url' => $this->url);
	}
	
	public function count() {
		return ORM::factory('song')->distinct('id')->count_all();
	}

}