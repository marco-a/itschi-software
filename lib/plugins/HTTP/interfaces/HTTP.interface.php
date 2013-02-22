<?php

	/**
		@author		< marco.a >

		< HTTP Interface >
	**/

	interface HTTPInterface {

		/**
			**************
			# constants  #
			**************
		**/

		/*
			@name	METHOD_GET
		*/
		const OPT_METHOD_GET = 0x01;

		/*
			@name	METHOD_POST
		*/
		const OPT_METHOD_POST = 0x03;

		/*
			@name	MULTIPART
		*/
		const OPT_MULTIPART = 0x10;

		/*
			@name	USE_UTF
		*/
		const OPT_USE_UTF = 0x30;

		/*
			@name	OPT_HOST
		*/
		const OPT_HOST = 0xF0;

		/*
			@name	OPT_PORT
		*/
		const OPT_PORT = 0xF1;

		/*
			@name	OPT_REQ_FILE
		*/
		const OPT_REQ_FILE = 0xF2;

		/*
			@name	OPT_TIMEOUT
		*/
		const OPT_TIMEOUT = 0xF3;

		/*
			@name	OPT_DATA
		*/
		const OPT_DATA = 0xF4;

		/*
			@name	CR
		*/
		const CR = 0x0D;

		/*
			@name	LF
		*/
		const LF = 0x0A;

		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	alloc
			allocates HTTPRequest instance
		*/
		public static function alloc();

		/*
			@name	init
			initializes instance
		*/
		public static function init($obj);

		/*
			@name	dealloc
			deallocates HTTPRequest instance
		*/
		public static function dealloc(HTTPRequest $obj);

		/*
			@name	getMimeType
			gets mime type for a file by its extension
		*/
		public static function getMimeType($path);

	}

?>