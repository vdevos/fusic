<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Playlists extends Controller_Fusic {
	
	public $template = 'template';

	public function before()
	{
		parent::before();		
		if (!Auth::instance()->logged_in())
		{
			$this->request->redirect('/');
		}				
		
		/* setup the overview */
		$this->template->section = 'playlist';
		$this->user = Auth::instance()->get_user();		
		$this->template->header_includes = array('assets/css/pages/playlists.overview.css','assets/css/pages/playlists.single.css');
		
	}

	public function action_index() { 
		
		$this->template->title = 'Playlists';
		$this->template->view = $this->view = View::factory('playlists/overview');	
        $this->view->reason = $this->request->query('reason');   
        
		$this->view->playlist_view = $this->owns_view = View::factory('playlists/mainmenu');	
	}
	
	public function action_yours() {
		
		$this->template->title = 'Your playlists';
		$this->template->view = $this->view = View::factory('playlists/overview');	 	
		
		/*setup the playlists you own */
		$this->view->playlist_view = $this->owns_view = View::factory('playlists/sv_playlists');
		$this->owns_view->type = "owns";
		$this->owns_view->items_per_row = 3;
		$this->owns_view->title = "Your playlists";
		$this->owns_view->icon = "icon-list-alt";
		$this->owns_view->no_playlist_message = "You have no playlists yet :(";
		$this->owns_view->playlists = $this->user->get_playlists();
		$this->view->your_playlist_count = count($this->owns_view->playlists);
	}
	
	public function action_following() {
		
		$this->template->title = 'Following playlists';
		$this->template->view = $this->view = View::factory('playlists/overview');	 	
		
		/* setup the playlists you follow */
		$this->view->playlist_view = $this->follow_view = View::factory('playlists/sv_playlists');
		$this->follow_view->type ="follows";
		$this->follow_view->title = "Following playlists";
		$this->follow_view->icon = "icon-user";
		$this->follow_view->no_playlist_message = 'You follow no playlists yet... Start <a href="/playlists/explore" class="tag-socialx bold">exploring</a> other playlists!';
		$this->follow_view->playlists = $this->user->get_following_playlists();	
		$this->view->following_playlist_count = count($this->follow_view->playlists);
	}
	
	public function action_explore() {
		
		$this->template->title = 'Explore playlists';
		$this->template->view = $this->view = View::factory('playlists/overview');	 	
		
		/* setup the playlists you follow */
		$this->view->playlist_view = $this->explore_view = View::factory('playlists/explore');
		$this->explore_view->title = "Explore playlists";
		$this->explore_view->icon = "icon-search";
	}
		
	public function action_show() { /* TODO: seperate view for guest or loggedin user */
		
		$id = $this->request->param('id');
		$playlist = ORM::factory('playlist',$id);
        $this->template->header_js_includes = array('assets/js/jquery.sortable.js');         
		
		if($playlist->loaded()) {
            
            $isadmin = ($this->user->id == $playlist->creator->id);
            $islocked = ($playlist->locked == 1);
            
			if($playlist->deleted == 1) {
				// $this->template->view = $view = View::factory('playlists/deleted');
				// $view->playlist = $playlist;
				$this->request->redirect('/playlists/?reason=deleted');
			}
            else if(!$isadmin && $islocked) {                
                $this->request->redirect('/playlists/?reason=private');
            }
			else { 							
				$playlist = ORM::factory('playlist')->where('id','=',$id)->order_by('id','asc')->find();
				
				$this->template->view = $view = View::factory('playlists/single');
				$this->template->title = '@'.$playlist->creator->username." - ".$playlist->name;
                
                $view->user = $this->user;
				$view->playlist = $playlist;
                $view->owner = $playlist->creator;                
                
                $view->privilege = $this->user->get_privilege($playlist);
				$view->isadmin = $isadmin;                
                $view->isfollowing = $this->user->is_following_playlist($playlist);                                                              
                $view->islocked = ($playlist->locked == 1);
                $view->candelete = ($view->isadmin || $view->privilege == 'edit');                
                $view->tags = explode(',', $playlist->genres);
                $view->APIKEY = $config = Kohana::$config->load('settings')->get('YOUTUBE_API_KEY');			

				$view->ownerbox = View::factory('user/sv_ownerbox');
				$view->ownerbox->user = $this->user;			
				
				if($this->user) { /* we have a user : else guest! */
                    $this->user->ping($playlist->id);															
				}
			}
		}
		else {
			$this->request->redirect('/playlists/');
		}
	}
    
    public function action_loved() {
        
        $this->template->section = 'loved';
        $this->template->header_js_includes = array('assets/js/jquery.sortable.js');   
        
        $id = $this->request->param('id');
        $user = $this->user; // $user = $id ? ORM::factory('user',$id) : $this->user; 
        /* create a phony/temp playlist - DO NOT SAVE THIS */
		$playlist = ORM::factory('playlist');
        $playlist->id = 0;
        $playlist->name = "Loved songs";
        
        $this->template->view = $view = View::factory('playlists/single');        
        $this->template->title = 'Loved songs';
        
        $view->ploved = true;
        $view->user = $this->user;
        $view->playlist = $playlist;
        $view->owner = $playlist->creator;                
        
        $view->privilege = $this->user->get_privilege($playlist);
        $view->isadmin = true;                
        $view->isfollowing = $this->user->is_following_playlist($playlist);                                                              
        $view->islocked = ($playlist->locked == 1);
        $view->candelete = ($view->isadmin || $view->privilege == 'edit');                
        $view->tags = explode(',', $playlist->genres);
        $view->APIKEY = $config = Kohana::$config->load('settings')->get('YOUTUBE_API_KEY');			

        $view->ownerbox = View::factory('user/sv_ownerbox');
        $view->ownerbox->user = $this->user;			
        
        if($this->user) { /* we have a user : else guest! */
            $this->user->ping($playlist->id);															
        }    
    }
	
	public function action_test() {
		foreach(ORM::factory('playlist')->where('deleted','=',1)->find_all() as $p) {
			echo $p->id . '<br />';
		}
		
	}
		
	public function action_edit() {
		
		$this->template->view = $this->view = View::factory('playlists/edit');	
		$this->template->header_includes = array('assets/css/pages/playlists.create.css');		
		$edited = Arr::get($_POST,'edited',false);
		
		$id = $this->request->param('id');
		$playlist = ORM::factory('playlist',$id);
				
		if($id && $playlist->loaded()) 
		{		
			if($this->user->isOwner($playlist)) 
			{	
				$this->view->id = $id;
				if($edited == 'yes') { /* this is being saved */
					
					$title = Arr::get($_POST,'title', NULL);				
					$tags = Arr::get($_POST,'tags', NULL);		
					$cover = Arr::get($_POST,'cover', NULL);
					$descr = Arr::get($_POST,'description', NULL);
					
					try {
						$playlist->update_fields($title, $tags, $cover, $descr);
						$this->request->redirect('/playlists/yours');
					}
					catch (Exception $e) {		
						$this->view->error = $e->getMessage();
					}					
				}			
				else { /* show edit screen */					
					$this->view->playlist = $playlist;
				}
			}
			else {
				$this->request->redirect('/playlists/yours?er2');
			}
		}
		else { /* no parameter > redirect to overview */
			$this->request->redirect('/playlists/yours?er1');
		}		
	}	
	
	public function action_create() 
	{		
		$this->template->title = 'Playlist create';
		$this->template->view = $this->view = View::factory('playlists/create');	
		$this->template->header_includes = array('assets/css/pages/playlists.create.css');		
		$creation = Arr::get($_POST,'creation', false);		
		
		if($creation == 'yes') {	
		
			$title = Arr::get($_POST,'title', NULL);				
			$tags = Arr::get($_POST,'tags', NULL);		
			$cover = Arr::get($_POST,'cover', NULL);
			$descr = Arr::get($_POST,'description', NULL);
			
			if(strlen($cover) < 15) {
				$this->view->cover = Kohana::$config->load('settings.playlist-default-img');
			}
			else {
				$this->view->cover = $cover;
			}
			
			try {
				$playlist = ORM::factory('playlist');
				$pid = $playlist->create_playlist($title, $tags, $cover, $descr);		
				$this->request->redirect('/playlists/show/'.$pid);
			}
			catch (Exception $e) {		
				$this->view->error = $e->getMessage();
			}	
			
		}
	}
	
	public function action_delete()
	{
		$pid = $this->request->param('id');
		$playlist = ORM::factory('playlist', $pid);
		if($playlist->loaded()) {
			if($this->user->isOwner($playlist)) {	
				$playlist->deleted = 1;
				$playlist->save();
			}
		}
		$this->request->redirect('/playlists/yours');
	}
	
	public function action_follow() 
	{
		$pid = $this->request->param('id');
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			$this->user->follow_playlist($playlist);
		}
		$this->request->redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function action_unfollow() {
		
		$pid = $this->request->param('id');
		$playlist = ORM::factory('playlist',$pid);
		
		if($playlist->loaded()) {
			$this->user->unfollow_playlist($playlist);
		}
		$this->request->redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function action_request() {
		
		$this->view->message = "<strong>Success</strong> Your request has been send... NOT LOL -> not yet implemented!";
		
	}
	
	
	

} // End Welcome
