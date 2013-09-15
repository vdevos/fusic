<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Home extends Controller_Fusic {
	
	public function before()
	{
		parent::before();		
		$this->template->section = 'overview';
	}
	
	public function action_index() {
		
		if(Auth::instance()->logged_in())
		{
			$this->request->redirect('/overview/');
		}	
		
		$this->template->title = 'Social Music Collaboration';
		$this->template->frontpage = true;
		$this->template->view = $this->view = View::factory('homepage');	
		$this->template->header_includes =  array('assets/css/pages/user.signin.css',
												 'assets/css/pages/home.css', 
												 'assets/css/pages/playlists.cover.css');
		
		/* widgets */
		$this->view->whatisfusic = View::factory('/widgets/whatisfusic');	
		$this->view->loginbox = View::factory('/widgets/login');	
		
		$this->view->stats_overall = View::factory('/widgets/overall');	
		
		$this->view->activity = View::factory('/widgets/activity');
		$this->view->activity->limit = 10;
		
		$this->view->loved = View::factory('/widgets/loved');			
		$this->view->loved->limit = 10;
		
		$this->view->playlist_covers = View::factory('/widgets/playlist_covers');
		$this->view->playlist_covers->playlists = ORM::factory('playlist')->get_latest(10);
	}
	
	public function action_overview() {
		
		if(!Auth::instance()->logged_in())
		{
			$this->request->redirect('/');
		}	
		
		$this->template->title = 'Overview';
		$this->template->view = $this->view = View::factory('overview');
		
		$this->view->your_playlist_activity = View::factory('/widgets/playlist_activity');
		$this->view->your_playlist_activity->type = "your";
		$this->view->your_playlist_activity->limit = 10;
		
		$this->view->following_playlist_activity = View::factory('/widgets/playlist_activity');
		$this->view->following_playlist_activity->type = "following";
		$this->view->following_playlist_activity->limit = 10;
		
		$this->view->jumptotop = View::factory('/widgets/jumptotop');
	
	}	
}