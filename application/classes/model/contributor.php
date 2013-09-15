<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_Contributor extends ORM {
	
	protected $_belongs_to = array(
		'playlist' => array('model' => 'playlist'),
		'user' => array('model' => 'user')
	);	

	protected $_has_many = array(
		'playlists' => array('model' => 'playlist', 'foreign_key' => 'id')
	);
	
	public function is_following() {
		return ($this->following == 1);
	}

}