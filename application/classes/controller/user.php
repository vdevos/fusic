<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_User extends Controller_Template {
	
	public $template = 'template';

	public function before()
	{
		parent::before();
		
		$this->template->view = "";
		$this->template->menuitems = array();
		$this->template->header_includes = array();
		$this->template->logged_in = Auth::instance()->logged_in();
	}

	public function action_index()
	{
		
	}
	
	public function action_login() {
		
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect('/overview/');
			}
		
		$this->template->title = "Fusic collaborative & social music playlists";
		$this->template->view = $view = View::factory('user/login');
		$this->template->islogin = true;
		$view->loginbox = View::factory('widgets/login');
		
		$this->template->menuitems = array();	
		$this->template->header_includes = array('assets/css/pages/user.signin.css');
		
		if($_POST) {
			$post = $this->request->post();
			$success = Auth::instance()->login(strtolower($post['username']), $post['password']);
			if($success) {
				$this->request->redirect('/overview/');
			}
			else {
				$view->loginbox->error = "Wrong username or password";
			}
		}		
	}
	
	public function action_create() {
		
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect('/');
		}
		
		$this->template->title = "Create account";
		$this->template->view = $view = View::factory('user/create');
		$view->createbox = View::factory('widgets/create');
		$this->template->header_includes = array('assets/css/pages/user.create.css');
		$this->template->iscreating = true;
		$view->error = NULL;
		
		if($_POST) {
			
			$username = strtolower(Arr::get($_POST,'username'));
			$email = strtolower(Arr::get($_POST,'email'));
			$pwd1 = Arr::get($_POST,'password');
			$pwd2 = Arr::get($_POST,'confirm_password');			
			
			try {
				ORM::factory('user')->validate_fields($username, $email, '');
				if($pwd1 !== $pwd2) {
					$view->createbox->error = "Your passwords do not match<br />";
				}
				else if(strlen($pwd1) < 6 || strlen($pwd2) < 6) {
					$view->createbox->error = "Your password should be at least 6 characters<br />";
				}
				else {			
					
					$user = ORM::factory('user')->values(array('username'=>$username,'email'=>$email,'password'=>$pwd1), array('username','email','password'));
					try {
						/* save account credentials and add login role */
						$user->creation = time();
						$user->save();
						$user->add('roles', ORM::factory('role', array('name' => 'login')));						
						
						/* loging after succesfull account creation */				
						Auth::instance()->login($username, $pwd1);
						$this->request->redirect('/');
					}
					catch (ORM_Validation_Exception $e) {	
						if($view->error == NULL) {
							foreach($e->errors('models') as $type=>$value) {
								if($value == 'models/user.username.unique') {
									$view->createbox->error .= "This username already exists<br />";
								}
								else if($value == 'models/user.email.unique') { 
									$view->createbox->error .= "This email is already registered<br />";
								}								
								else {
									$view->createbox->error .= "$value<br />";
								}
							}	
						}
					}				
				}		
			}
			catch (Exception $e) {		
				$view->createbox->error = $e->getMessage();
			}			
		}	
	}
	
	public function action_show() {	
			
		if(!Auth::instance()->logged_in())
		{
			$this->request->redirect('/user/login');
		}
				
		$id = $this->request->param('id');		
		$user = null;
		
		/* try to parse either userid or username */
		if(is_numeric($id)) {
			$user = ORM::factory('user',$id);
		}
		else {
			$user = ORM::factory('user')->where('username','=',$id)->find();
		}
			
		if(!$user->loaded()) {
			$this->request->redirect('/playlists/?msg=wrong+user+id');
		}
		else {
			
			/* setup the template parameters */			
			$this->template->title = $user->username;
			$this->template->section = '';
			$this->template->view = $view = View::factory('/user/show');
			$this->template->header_includes = array('assets/css/pages/user.show.css');
			$this->template->user = Auth::instance()->get_user();
			
			/* set the users into the views */						
			$view->user = $user; 
			
			$friends = $user->get_friends(1000);
			$friend_count = $user->get_friends_count();
			$playlists = $user->get_playlists();
			$following_playlists = $user->get_following_playlists();
			
			$view->friends_view = View::factory('/widgets/user_friends');
			$view->friends_view->friends = $friends;
			$view->friends_view->title = "Friends (".$friend_count.")";
			$view->friend_count = $friend_count;
			$view->playlist_count = count($playlists);
			
			$view->playlists = $playlists;		
			$view->following_playlists = $following_playlists;
			
		}
	}

	public function action_profile() {
		
		if(!Auth::instance()->logged_in())
		{
			$this->request->redirect('/');
		}
		else {
			$user = Auth::instance()->get_user();
			$this->template->title = "Profile";
			$this->template->section = 'profile';
			$this->template->user = $user;				
			$this->template->header_includes = array('assets/css/pages/user.profile.css');
			$this->template->header_js_includes = array('assets/js/fileuploader.js');
			
			$this->template->view = $view = View::factory('user/profile');
			$view->user = $user;		
			
			if($_POST) {
				$username = $this->request->post('username');
				$email = $this->request->post('email');
				$bio = $this->request->post('bio');
				try {
					$user->update_data($username, $email, $bio);	
					$view->message = "<strong>Success</strong> Your profile has been saved!";
				}
				catch (Exception $e) {		
					$view->error = $e->getMessage();
				}				
			}
			
		}
	}
	
	public function action_logout() {
	
		if (Auth::instance()->logged_in())
		{
			Auth::instance()->logout();
		}
		$this->request->redirect('/');
	}
	
} 
