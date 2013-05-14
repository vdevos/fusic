<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_History extends ORM {

	protected $_has_many = array(
		'users' => array('model' => 'user')
	);

	protected $_belongs_to = array(
		'user' => array('model' => 'user'),
		'playlist' => array('model' => 'playlist'),
		'song' => array('model' => 'song')
	);
	
	public function get_latest($limit) {
		return ORM::factory('history')->limit($limit)->order_by('id','desc')->find_all();
	}
	
	public function count() {
		return ORM::factory('history')->distinct('id')->count_all();
	}	

}