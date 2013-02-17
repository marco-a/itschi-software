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

	if (!defined('CRLF')) {
		define('CRLF', sprintf('%c%c', 0x0D, 0x0A));
	}

	final class HTTP extends plugin {

		/*
			@POST
		*/
		const POST = 1;

		/*
			@GET
		*/
		const GET = 3;

		/*
			@makeHTTPRequest
		*/
		final public function makeHTTPRequest($type, $host, $file, $data = array()) {
			$type = (int)$type;

			if ($type != self::POST || $type != self::GET) return false;

			$URL = sprintf('http://%s/%s', $host, $file); // set url


		}

	}


?>
