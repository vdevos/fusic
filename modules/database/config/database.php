<?php defined('SYSPATH') or die('No direct access allowed.');


if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') /* local development */
{	
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => 'fusicnl_master',
				 'username'   => 'root',
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
else if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'fusic.vdevos.nl') /* production */
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
else if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'dev.fusic.vdevos.nl') /* development */
{
	return array
	(
		'default' => array
		(
			'type'       => 'mysql',
			'connection' => array(
				 'hostname'   => 'localhost',
				 'database'   => 'vdevos_fusicdev',
				 'username'   => 'fusicdev',
				 'password'   => 'fusicdev',
				 'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'    => TRUE,
		),
	);	
}
