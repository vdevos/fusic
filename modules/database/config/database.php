<?php defined('SYSPATH') or die('No direct access allowed.');


if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') 
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
else if(isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'www.domain.nl' || $_SERVER['HTTP_HOST'] == 'domain.nl'))
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
