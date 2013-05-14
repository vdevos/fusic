<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Friends extends Controller_Fusic {
	
	public function before()
	{
		parent::before();		
		if (!Auth::instance()->logged_in())
		{
			$this->request->redirect('/user/login');
		}				
		
		$this->template->title = "Friends";
		$this->template->section = "friends";
		$this->template->header_includes = array('assets/css/pages/friends.css');
		$this->template->header_js_includes = array('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
		
		$this->user = Auth::instance()->get_user();
		
	}

	public function action_index() 
	{ 
		$this->template->view = $this->view = View::factory('friends/overview');
		$this->view->user = $this->user;
		$this->view->friends = $this->user->friends->find_all();
		$this->view->row_limit = 8;
		
	}
}