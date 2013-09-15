<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 10/11/2012

class Controller_Gs extends Controller {
	
	public function before()
	{
		parent::before();

		/* Setup the response array and format-type: JSON */
		$this->data = array();
		$this->response->headers('Content-Type', 'application/json');
		$this->limit = Arr::get($_GET, 'limit', 10);	
		
		/* Get the current session and the API keys needed for Grooveshark */
		$this->session = Session::instance();
		$this->API_KEY = Kohana::$config->load('settings')->get('GROOVESHARK_API_KEY');
		$this->API_SECRET = Kohana::$config->load('settings')->get('GROOVESHARK_API_SECRET');
		
		/* Init the Grooveshark API class */
		$this->gsAPI = new Gsapi($this->API_KEY, $this->API_SECRET);
			
		/* GET/SET the session for the Grooveshark API */
		if ($this->session->get('gs-session',null) !== null) {
			$this->gsAPI->setSession($this->session->get('gs-session'));
		} else {
			$this->session->set('gs-session', $this->gsAPI->startSession());
		}
		
		/* SET the country for the Grooveshark API */
		$this->gsAPI->getCountry($_SERVER['REMOTE_ADDR']);
	}	
	
	public function after() 
	{	
		$this->response->body(json_encode($this->data));
		parent::after();
	}
	
	public function action_search() 
	{
		$search = Arr::get($_GET,'q',null);
		
		if($search) 
		{
			/* SEARCH within the Grooveshark API */
			$this->gsSearch = new Gssearch();
			$this->gsSearch->setTitle($search);
			$this->data = $this->gsSearch->songSearchResults($this->limit);		
		}
		else {
			$this->data['error'] = 'Wrong parameter(s) provided.';
		}	
	}
	
}
	