<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Api extends Controller {
	
	public function before()
	{
		parent::before();

		$this->data = array();
		$this->response->headers('Content-Type', 'application/json');		
		$this->limit = (int)Arr::get($_GET, 'limit', 10);		
	}	
	
	public function after() 
	{	
		$this->response->body(json_encode($this->data));
		parent::after();
	}
	
	public function action_stats_overall() 
	{
		$users = Tools::abbreviate_number(ORM::factory('user')->count(), true);
		$songs = Tools::abbreviate_number(ORM::factory('song')->count(), true);
		$playlists = Tools::abbreviate_number(ORM::factory('playlist')->count(), true);
		$plays = Tools::abbreviate_number(ORM::factory('history')->count(), true);
		$this->data['stats'] = array('users'=>$users, 'songs'=>$songs, 'playlists'=>$playlists, 'plays'=>$plays);
	}
	
	public function action_widget_playlist_covers() 
	{		
		$count = 0;
		$this->limit = ($this->limit <= 25) ? $this->limit : 25;				
		$playlists = ORM::factory('playlist')->get_latest($this->limit);
		
		foreach($playlists as $p) 
		{
			$pd = array();
			$pd['on'] = strtotime($p->created);
			$pd['playlist'] = $p->to_array();
			$pd['index'] = $count;
			$this->data['playlists'][] = $pd;
			$count++;
		}		
	}
	
	
	public function action_widget_activity() 
	{		
		$this->limit = ($this->limit <= 100) ? $this->limit : 100;
		$history = ORM::factory('history')->get_latest($this->limit);
		
		foreach($history as $hist) 
		{		
			$sd = array();
			$sd['on'] = $hist->timestamp;
			$sd['song'] = $hist->song->to_array();
			$sd['playlist'] = $hist->playlist->to_array();
			$sd['user'] = $hist->user->to_array();
			$this->data['songs'][] = $sd;
		}		
	}	
	
	public function action_widget_loved() 
	{
		$this->limit = ($this->limit <= 25) ? $this->limit : 25;				
		$loves = ORM::factory('love')->get_latest($this->limit);
		
		foreach($loves as $l) 
		{
			$ld = array();
			$ld['on'] = $l->loved;
			$ld['song'] = $l->song->to_array();
			$ld['user'] = $l->user->to_array();
			$ld['playlist'] = $l->song->playlist->to_array();
			$this->data['loves'][] = $ld;
		}		
	}	
	
	public function action_search() {
		
		$q = Arr::get($_GET,'q',null);
		$lq = "%".$q."%";
		
		if($q) {
			$users = ORM::factory('user')->or_where('username','LIKE',$lq)->or_where('email','LIKE',$lq)->or_where('bio','LIKE',$lq)->find_all();
			foreach($users as $user) {
				$this->data['users'][] = $user->to_array();
			}
			
			$playlists = ORM::factory('playlist')->or_where('name','LIKE',$lq)->or_where('genres','LIKE',$lq)->or_where('description','LIKE',$lq)->find_all();
			foreach($playlists as $playlist) {
				$this->data['playlists'][] = $playlist->to_array();
			}
			
			$this->data['songs'] = json_decode(Request::factory('/gs/search')->query(array('q'=>$q))->execute());
		}	
	
	}
	
}
