<?php
	return array(
		'username' => array(
			'not_empty' => 'You must provide a username.',
			'min_length' => 'The username must be at least :param2 characters.',
			'max_length' => 'The username must be less than :param2 characters.',
			'username_available' => 'This username is not available.',
		),
		'password' => array(
			'not_empty' => 'You must provide a password.',
			'min_length' => 'Your password must be at least :param2 characters.',
			'max_length' => 'Your Password must be less than :param2 characters.',
		),
		'email' => array(
			'not_empty' => 'You must provide a email.',
			'min_length' => 'Your email must be at least :param2 characters.',
			'max_length' => 'Your email must be less than :param2 characters.',
			'email' => 'Your have entered a invalid email',
			'email_available' => 'This email is already in use.',
		),
	);
?>