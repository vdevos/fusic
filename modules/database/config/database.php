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
				 'database'   => 'vdevos_fusic',
				 'username'   => 'vdevos',
				 'password'   => 'vps8310',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
else if(isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'www.fusic.nl' || $_SERVER['HTTP_HOST'] == 'fusic.nl'))
{
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => 'vdevos_fusic',
				 'username'   => 'vdevos',
				 'password'   => 'vps8310',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
else if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'fusic.vdevos.nl')
{
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => 'vdevos_fusic',
				 'username'   => 'vdevos',
				 'password'   => 'vps8310',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
