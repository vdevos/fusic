<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Songs extends Controller_Fusic {

	public function before()
	{
		parent::before();		
		if (!Auth::instance()->logged_in())
		{
			$this->request->redirect('/user/login');
		}		

		$this->template->menuitems['songs']['active'] = true;

	}

	public function action_index() {
		
		$this->template->title = 'Song activity';		
		$this->template->view = $this->view = View::factory('songs/overview');
		
		$this->view->songs = array();
		$this->user = Auth::instance()->get_user();
		
		$listened_songs = ORM::factory('history')->where('user_id','=',$this->user->id)->find_all();
		$added_songs = $this->user->added_songs->where('deleted','=',0)->find_all();
		$loves = $this->user->loves->find_all();
		
		$songs = array();
		foreach($listened_songs as $history) {
			$songs[$history->timestamp] = array('song' => $history->song, 'type'=>'play');
		}
		foreach($added_songs as $song) {
			$songs[$song->added] = array('song'=>$song, 'type'=>'add');
		}
		foreach($loves as $love) {
			$songs[$love->loved] = array('song'=>$love->song, 'type'=>'love');
		}
		
		krsort($songs);
		$this->view->songs = $songs;			
	}
	
	public function action_loved() {
		
		$this->template->title = 'Songs loved';		
		$this->template->section = 'loved';
		$this->template->view = $this->view = View::factory('songs/loved');
		
		$this->view->songs = array();
		$this->user = Auth::instance()->get_user();

		$songs = array();
		$loves = $this->user->loves->find_all();	
		foreach($loves as $love) {
			$songs[$love->loved] = array('song' => $love->song, 'type' => 'love');
		}		
		krsort($songs);
		
		$this->view->lovedsongs = $songs;
	}		
} 
