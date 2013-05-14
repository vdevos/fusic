<?php defined('SYSPATH') or die('No direct access allowed.');

// Copyright (c) 2012 All Right Reserved, Vincent de Vos
// Author: Vincent de Vos
// Date: 12/08/2012

class Model_Friendship extends Model {   
	protected $_belongs_to = array(
		'user' => array('model' => 'user', 'foreign_key' => 'user_id'),
		'friend' => array('model' => 'user', 'foreign_key' => 'friend_id')
	);
}

?>