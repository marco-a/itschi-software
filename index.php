<?php
	/**
	*
	* @package com.Itschi.Index
	* @since 2013/02/13
	*
	*/

	require 'base.php';
	include 'lib/feed.php';

	//feed(5);

	template::assign(array(
		'TITLE_TAG'	=>	'Startseite | ',
		'USER_LEGEND'	=>	$user->legend($user->row['user_level'])
	));

	template::display('index');
?>