<?php defined('SYSPATH') or die('No direct access allowed.');


if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') /* this is for local development */
{	
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => '',
				 'username'   => '',
				 'password'   => '',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
else if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'fusic.website.nl') /* this is your dev website */
{
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => '',
				 'username'   => '',
				 'password'   => '',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
