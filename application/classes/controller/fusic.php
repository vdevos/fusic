<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Fusic extends Controller_Template {

	public $template = 'template';
	
	public function before()
	{
		parent::before();
		
		define('BASE_URL', substr(URL::base(TRUE,FALSE), 0, count(URL::base(TRUE,FALSE)) - 2));
		
		$this->template->logged_in = Auth::instance()->logged_in();
				
		/* reset the lock for a user on every controller call and set the lock afterwards on the points needed */
		if ($this->template->logged_in) {
			$this->template->user = $user = Auth::instance()->get_user();
			$user->playlist_lock = 0;
			$user->song_lock = 0;
			$user->save();
		}
		
		/* setup the menu items */
		
	}

}