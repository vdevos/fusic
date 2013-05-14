<?php defined('SYSPATH') or die('No direct script access.');
// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Controller_Tools extends Controller_Template {

	public $template = 'tools';
	
	public function before()
	{
		parent::before();	
		$this->template->content = '';
	}
		
	public function action_updatesongs() { /* make sure to add the parameter: type=admin for extra safety */
		
		
		if (Auth::instance()->logged_in()) {
			 if(Auth::instance()->get_user()->username !== 'vdevos') {
				$this->request->redirect('/user/login');
			 }
		}	
		else {
			$this->request->redirect('/user/login');
		}
		
		$this->template->content = '';
		
		if(Arr::get($_GET, 'type', NULL) == 'admin') {			
			$songs = ORM::factory('song')->find_all();
			foreach($songs as $song) {							
				if($song->duration == 0) {
					$this->template->content .= 'Updating ['.$song->id.'] '.$song->title.'<br />';
					$ytdata = Tools::get_youtube_data($song->url);
					if($ytdata) {
						$song->title = $ytdata['title'];
						$song->description = $ytdata['description'];
						$song->keywords = $ytdata['keywords'];
						$song->thumbnail = $ytdata['thumbnail'];
						$song->duration = $ytdata['duration'];
						$song->save();
					}					
				}				
			}			
			$this->template->content .= '<b>DONE!</b>';
		}			
	}
	
	public function action_clean() 
	{		
		$count = 0;
		$users = ORM::factory('user')->find_all();
		foreach($users as $user) {
			if($user->playlist_lock > 0 || $user->song_lock > 0) {
				$diff = time() - $user->last_ping;
				if($diff > 180) {
					$user->playlist_lock = 0;
					$user->song_lock = 0;
					$user->save();
					$count++;
				}			
				// DEBUG INFO
				// $this->template->content .= '['.$user->id.'] '.$user->username.' - Diff: '.$diff.'</br />';
			}
		}
		if($count > 0) {
			$this->template->content = date("D j M Y H:i:s", time()).' - Cleaned '.$count.' accounts';
		}
	}
	
	public function action_upload_user_picture() {
		
		$this->data = array();
		$this->response->headers('Content-Type', 'application/json');
		
		if(!Auth::instance()->logged_in())
		{
			$this->data = array('error' => 'Not authorized');
		}
		else {
			
			$user = Auth::instance()->get_user();
			$file = arr::get($_GET,'qqfile',false);
			if(isset($file)) {				
				
				/* CHECK VALID EXTENSION */
				$valid = false;
				foreach(array('.png','.jpg','.gif') as $ext) {
					if(Tools::strendswith($file,$ext)) {						
						$valid = true;
					}
				}
				if(!$valid) {
					$this->data = array('error' => 'Your picture should have a .png .jpg or .gif extension');
				}
				else {				
					/* VALID PICTURE SIZE */
					$size = $this->request->headers('Content-Length');
					if($size == 0 || $size > (10*512*512)) {
						$this->data = array('error' => 'The size of your picture is too large');
					}
					else {					
						$filename = strtolower(sha1('user-picture-'.$user->id)).'.jpg';
						$directory = DOCROOT.'assets/img/pictures/';
						$saved = Uploader::save($directory.$filename);
						if($saved > 0) {
							if($saved > (10*512*512)) {
								$this->data = array('error' => 'The size of your picture is too large');								
							}
							else {
								$user->cover = $filename;
								$user->save();
								$this->data = array('success' => 'Your picture has been saved', 'src' => '/assets/img/pictures/'.$filename);
							}
						}
						else {
							$this->data = array('error' => 'Your picture has not been saved...');
						}
					}
				}
			}
			else {
				$this->data = array('error' => 'No file has been given?');
			}
		}
		echo $this->response->body(json_encode($this->data));
	}
	
	public function action_remove_user_picture() {
		
		$this->data = array();
		
		if(!Auth::instance()->logged_in())
		{
			$this->data = array('error' => 'Not authorized');
		}
		else {
			$user = Auth::instance()->get_user();
			$file = strtolower(sha1('user-picture-'.$user->id)).'.jpg';
			$directory = DOCROOT.'assets/img/pictures/';
			$path = $directory.$file;
			if(file_exists($path)) {
				unlink($path);
				$user->cover = '';
				$user->save();
				$this->data = array('success' => 'Picture has been removed');								
			}
			else {

			}		
		}
		echo $this->response->body(json_encode($this->data));
	}	
	
}