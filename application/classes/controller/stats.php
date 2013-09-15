<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Stats extends Controller_Fusic {

	public function before()
	{
		parent::before();
		
		if (!Auth::instance()->logged_in() && !Auth::instance()->get_user()->username !== 'vdevos')
		{
			$this->request->redirect('/user/login');
		}		
	}
	
	public function action_index() {			
					
		$this->template->title = 'Stats';
		$this->template->view = $this->view = View::factory('stats');	
		$this->template->menuitems = array();
		$this->template->section = 'stats';
	
	}
}