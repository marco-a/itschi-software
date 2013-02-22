<?php
	/**
	*
	* 	@package com.Itschi.base.plugins.HTTP
	* 	@since 2013/02/06
	*
	*	DO NOT MODIFY ANY OF THESE FUNCTIONS.
	*	These functions are essential for the use of plugins.
	*	Editing may cause your cat to be eaten by your subwoofer
	*	or serious frozen air around your head.
	*	It may also cause headaches.
	*   Dafuq did I just read?
	*/

	$_root = dirname(__FILE__).DIRECTORY_SEPARATOR.'HTTP'.DIRECTORY_SEPARATOR;

	/*
		+-----------------+
		| load interfaces |
		+-----------------+
	*/
	require_once($_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTP.interface.php');
	require_once($_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPRequest.interface.php');
	require_once($_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPRequestData.interface.php');
	require_once($_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPResponse.interface.php');

	/*
		+--------------+
		| load classes |
		+--------------+
	*/
	require_once($_root.'HTTP.class.php');
	require_once($_root.'HTTPRequest.class.php');
	require_once($_root.'HTTPRequestData.class.php');
	require_once($_root.'HTTPResponse.class.php');

	// init HTTP
	HTTP::init();

	/*
		+---------+
		| example |
		+---------+
	*/
	/*
	$HTTPRequest = HTTPRequest::alloc(HTTP::OPT_METHOD_POST | HTTP::OPT_MULTIPART);

	$HTTPRequest->setOpt(HTTP::OPT_HOST, 'pro-fusion.ch');
	$HTTPRequest->setOpt(HTTP::OPT_REQ_FILE, 'test_http.php');

	$HTTPRequestData = HTTPRequestData::alloc();
	$HTTPRequestData->add('getParam', 'value');
	$HTTPRequestData->add('anotherGetParam', 'test');
	$HTTPRequestData->addFile('logo', '../../styles/standard/images/icons/topics/topic.png');

	$HTTPRequest->setOpt(HTTP::OPT_DATA, $HTTPRequestData);

	$HTTPRequest->send(function(HTTPResponse $response) {
		if ($response->getErrorCode() == 0) {
			echo 'responseCode: '.$response->getResponseCode().PHP_EOL;
			echo 'mimeType: '.$response->getMimeType().PHP_EOL;
			echo 'response: '.$response->getResponse();
		} else {
			echo 'an error occurred: '.$response->getErrorString();
		}
	});


	EXAMPLE OUTPUT:


	responseCode: 200
	mimeType: text/html
	response: 121

	post:

	Array
	(
	    [getParam] => value
	    [anotherGetParam] => test
	)

	files:

	Array
	(
	    [logo] => Array
	        (
	            [name] => topic.png
	            [type] => image/png
	            [tmp_name] => [tmp dir]
	            [error] => 0
	            [size] => 1327
	        )

	)

	0

	*/
?>
