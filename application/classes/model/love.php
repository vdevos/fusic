<?php defined('SYSPATH') or die('No direct script access.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_Love extends ORM {
	
	protected $_belongs_to = array(
		'user' => array('model' => 'user', 'foreign_key' => 'user_id'),
		'song' => array('model' => 'song', 'foreign_key' => 'song_id')
	);
	
	public function get_latest($limit) {
		return ORM::factory('love')->limit($limit)->order_by('id','desc')->find_all();	
	}	
    
    public function max($userid)
    {        
       return (int)DB::select('order')->from('loves')->where('user_id','=',$userid)->order_by('order', 'desc')->limit(1)->execute()->get('order');
    }
}