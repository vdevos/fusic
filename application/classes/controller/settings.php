<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Settings extends Controller_Fusic {
	
	public function before()
	{
		parent::before();		
		if (!Auth::instance()->logged_in())
		{
			$this->request->redirect('/user/login');
		}				
		
		/* setup the menu items */
		$this->template->menuitems = $menu_items = array(
			'playlists' => array('uri' => '/playlist/', 'active' => false),
			'songs' => array('uri' => '/songs/', 'active' => false),
			'settings' => array('uri' => '/settings/', 'active' => true),
			'logout' => array('uri' => '/user/logout', 'active' => false),
		);
		
		$this->user = Auth::instance()->get_user();
		$this->template->view = $this->view = View::factory('settings');
	}

	public function action_index() { }
	
} 
