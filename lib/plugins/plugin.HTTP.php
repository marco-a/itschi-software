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

	/*
		+------------------------------------------------------------+
		| THIS FILE IS UNDER CONSTRUCTION, EXAMPLE ON BOTTOM OF FILE |
		+------------------------------------------------------------+
	*/

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
			@name	RAW
		*/
		const OPT_RAW = 0x10;

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
			@name	OPT_DATA
		*/
		const OPT_DATA = 0xF3;

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
			@name	allocData
			allocates HTTPRequestData instance
		*/
		public static function allocData();

		/*
			@name	init
			initializes instance
		*/
		public static function init(HTTPRequest $obj);

		/*
			@name	dealloc
			deallocates HTTPRequest instance
		*/
		public static function dealloc(HTTPRequest $obj);

	}

	interface HTTPRequestDataInterface {
		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	__construct
		*/
		public function __construct();

		/*
			@name	add
			adds a field
		*/
		public function add($name, $value);

		/*
			@name	remove
			removes a field
		*/
		public function remove($name);

		/*
			@name	fieldByValue
			gets a field by its value
		*/
		public function fieldByValue($value, $type = false);

		/*
			@name	getFields
			returns fields
		*/
		public function getFields();
	}

	final class HTTPRequestData implements HTTPRequestDataInterface {
		/*
			@name	HTTPRequest
		*/
		private $HTTPRequest = NULL;

		/*
			@name	fields
		*/
		private $fields = NULL;

		/*
			@name	__construct
		*/
		public function __construct() {
			$this->fields = array();
		}

		/*
			@name	add
			adds a field
		*/
		public function add($name, $value) {
			if (isset($this->fields[$name])) return false;

			$this->fields[$name] = $value;

			return true;
		}

		/*
			@name	remove
			removes a field
		*/
		public function remove($name) {
			if (!isset($this->fields[$name])) return false;

			$this->fields[$name] = NULL;
			unset($this->fields[$name]);

			return true;
		}

		/*
			@name	fieldByValue
			gets a field by its value
		*/
		public function fieldByValue($value, $type = false) {
			if (!is_array($this->fields)) return false;

			$field = false;

			foreach ($this->fields as $fieldName => $fieldValue) {
				if ($type == true) {
					if ($fieldValue === $value) {
						$field = $fieldName;

						break;
					}
				} else {
					if ($fieldValue == $value) {
						$field = $fieldName;

						break;
					}
				}
			}

			return $field;
		}

		/*
			@name	getFields
			returns fields
		*/
		public function getFields() {
			return $this->fields;
		}
	}

	interface HTTPRequestInterface {
		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	setOpt
			sets option
		*/
		public function setOpt($key, $value);

		/*
			@name	getOpt
			gets option
		*/
		public function getOpt($name);

		/*
			@name	setOpts
			sets options
		*/
		public function setOpts($options);

		/*
			@name	addHeader
			adds a header
		*/
		public function addHeader($name, $value);

		/*
			@name	send
			sends request
		*/
		public function send($callback);
	}

	interface HTTPResponseInterface {
		/*
			@name	__construct
		*/
		public function __construct();

		/*
			@name	setItem
			sets an item
		*/
		public function setItem($name, $value);

		/*
			@name	lock
			locks instance
		*/
		public function lock();

		/*
			@name	__call
		*/
		public function __call($method, $args);
	}

	final class HTTPResponse implements HTTPResponseInterface {
		/*
			@name	locked
		*/
		private $locked = NULL;

		/*
			@name	items
		*/
		private $items = NULL;

		/*
			@name	__construct
		*/
		public function __construct() {
			$this->locked = false;
			$this->items = array();
		}

		/*
			@name	setItem
			sets an item
		*/
		public function setItem($name, $value) {
			if ($this->locked == true || isset($this->items[$name])) return false;

			$this->items[$name] = $value;

			return true;
		}

		/*
			@name	lock
			locks instance
		*/
		public function lock() {
			$this->locked = true;
		}

		/*
			@name	__call
		*/
		public function __call($method, $args) {
			$item = str_replace('get', '', $method);
			$item = lcfirst($item);

			if (!isset($this->items[$item])) return NULL;

			return $this->items[$item];
		}
	}

	final class HTTPRequest implements HTTPRequestInterface {
		/*
			@name	inited
		*/
		public $inited = false;

		/*
			@name	options
		*/
		private $options = NULL;

		/*
			@name	method
		*/
		private $method = NULL;

		/*
			@name	use_raw
		*/
		private $use_raw = NULL;

		/*
			@name	use_utf
		*/
		private $use_utf = NULL;

		/*
			@name	headers
		*/
		private $headers = NULL;

		/*
			@name	response
		*/
		private $response = NULL;

		/*
			@name	init
		*/
		public function init() {
			$this->inited = true;
			$this->options = array();
			$this->headers = array();
		}

		/*
			@name	setOpt
			sets option
		*/
		public function setOpt($key, $value) {
			if (isset($this->options[$key])) return false;

			$this->options[$key] = $value;

			return true;
		}

		/*
			@name	getOpt
			gets option
		*/
		public function getOpt($name) {
			if (!isset($this->options[$name])) return NULL;

			return $this->options[$name];
		}

		/*
			@name	setOpts
			sets options
		*/
		public function setOpts($options) {
			if ($this->inited == false) return false;

			/*
				METHOD_GET				0000 0001 -> 0x 0 1
				METHOD_POST				0000 0011 -> 0x 0 3

				METHOD_GET | RAW		0001 0001 -> 0x 1 1
				METHOD_GET | USE_UTF    0011 0001 -> 0x 3 1

				METHOD_POST | RAW		0001 0011 -> 0x 1 3
				METHOD_POST | USE_UTF   0011 0011 -> 0x 3 3
			*/

			// get lower tetrad
			$lowTetrad = $options & 0x0F;

			// get higher tetrad
			$highTetrad = $options & 0xF0;

			if ($lowTetrad != 0x01 && $lowTetrad != 0x03) {
				return false;
			}

			if ($highTetrad != 0x10 && $highTetrad != 0x30) {
				return false;
			}

			$this->method = $lowTetrad;
			$this->use_raw = ($highTetrad == 0x10);
			$this->use_utf = ($highTetrad == 0x30);

			return true;
		}

		/*
			@name	addHeader
			adds a header
		*/
		public function addHeader($name, $value) {
			if (isset($this->headers[$name])) return false;

			$this->headers[$name] = $value;

			return true;
		}

		/*
			@name	getHeaders
			gets headers
		*/
		public function getHeaders($parsed) {
			if ($parsed == false) return $this->headers;

			$payload = '';

			foreach ($this->headers as $headerName => $headerValue) {
				$payload .= sprintf('%s: %s%c%c', $headerName, $headerValue, HTTP::CR, HTTP::LF);
			}

			return $payload;
		}

		/*
			@name	send
			sends request
		*/
		public function send($callback) {
			if (!is_callable($callback)) return false;

			$host = $port = $reqFile = $data = NULL;

			if (($host = $this->getOpt(HTTP::OPT_HOST)) == NULL) return false;

			if (($port = $this->getOpt(HTTP::OPT_PORT)) == NULL) {
				$port = 80;
			}

			if (($reqFile = $this->getOpt(HTTP::OPT_REQ_FILE)) != NULL) {

			}

			$this->addHeader('Host', $host);

			$reqFile = sprintf('/%s', $reqFile);

			$CRLF = sprintf('%c%c', HTTP::CR, HTTP::LF);

			if (($data = $this->getOpt(HTTP::OPT_DATA)) != NULL) {
				if (!($data instanceof HTTPRequestDataInterface)) return false;

				$fields = $data->getFields();

				$fieldsPayload = ($this->method == HTTP::OPT_METHOD_POST ? sprintf('%s%s', $CRLF, $CRLF) : '');

				foreach ($fields as $fieldName => $fieldValue) {
					$fieldsPayload .= sprintf('%s=%s&', $fieldName, $fieldValue);
				}

				$fieldsPayload = mb_substr($fieldsPayload, 0, mb_strlen($fieldsPayload, 'UTF-8') - 1, 'UTF-8');

				if ($this->method == HTTP::OPT_METHOD_POST) {
					$this->addHeader('Content-Length', mb_strlen($fieldsPayload, 'UTF-8'));
					$this->addHeader('Content-Type', 'application/x-www-form-urlencoded');

					$fieldsPayload .= sprintf('%s', $CRLF);
				}

			}

			$plainHeaders = $this->getHeaders(true);

			if (isset($fieldsPayload)) {
				if ($this->method == HTTP::OPT_METHOD_POST) {
					$plainHeaders .= $fieldsPayload;
				} else {
					$getParams = explode('?', $reqFile);

					if (sizeof($getParams) == 1) {
						$reqFile .= '?'.$fieldsPayload;
					} else {
						$reqFile .= '&'.$fieldsPayload;
					}
				}
			}

			$plainHeaders = sprintf('%s %s HTTP/1.1%s%s', ($this->method == HTTP::OPT_METHOD_GET ? 'GET' : ($this->method == HTTP::OPT_METHOD_POST ? 'POST' : 'GET')), $reqFile, $CRLF, $plainHeaders);

			$handle = fsockopen($host, $port);

			if ($handle == false) {
				return $callback(NULL);
			}

			if ($this->method == HTTP::OPT_METHOD_GET) {
				$plainHeaders .= sprintf('%s%s', $CRLF, $CRLF);
			}

			fwrite($handle, $plainHeaders);

			$response = '';

			while (feof($handle) == false) {
				$response .= fgets($handle, 8);
			}

			fclose($handle);

			$responseSplitted = explode(sprintf('%s%s', $CRLF, $CRLF), $response);

			/*
				get data from headers
			*/
			$headers = $responseSplitted[0];
			$responseSplitted[0] = NULL;
			unset($responseSplitted[0]);

			$response = implode($responseSplitted, sprintf('%s%s', $CRLF, $CRLF));

			$GLOBALS['responseCode'] = 0;

			preg_replace_callback('~HTTP\/1\.1 ([0-5]{3})~Ui', function($match) {
				$GLOBALS['responseCode'] = $match[1];
			}, $headers);

			$GLOBALS['mimeType'] = '';

			preg_replace_callback(sprintf('~Content\-Type\: (.*)%s~Ui', $CRLF), function($match) {
				$GLOBALS['mimeType'] = str_replace($CRLF, '', $match[1]);
			}, $headers);

			/*
				build response
			*/
			$this->response = new HTTPResponse();
			$this->response->setItem('responseCode', (int)$GLOBALS['responseCode']);
			$this->response->setItem('response', $response);
			$this->response->setItem('mimeType', $GLOBALS['mimeType']);
			$this->response->lock();

			/*
				TO BE CONTINUED
			*/

			return $callback($this->response);
		}
	}

	final class HTTP implements HTTPInterface {
		/*
			@name	alloc
			allocates HTTPRequest instance
		*/
		public static function alloc() {
			return new HTTPRequest();
		}

		/*
			@name	allocData
			allocates HTTPRequestData instance
		*/
		public static function allocData() {
			return new HTTPRequestData($obj);
		}

		/*
			@name	init
			initializes instance
		*/
		public static function init(HTTPRequest $obj) {
			if ($obj->inited == true) return false;

			$obj->init();

			$obj->addHeader('Connection', 'close');

			return true;
		}

		/*
			@name	dealloc
			deallocates HTTPRequest instance
		*/
		public static function dealloc(HTTPRequest $obj) {
			$obj->inited = false;

			/*
				TO BE CONTINUED
			*/
		}
	}

	$HTTPRequest = HTTP::alloc();

	HTTP::init($HTTPRequest);

	$HTTPRequest->setOpts(HTTP::OPT_METHOD_GET | HTTP::OPT_USE_UTF);

	$HTTPRequest->setOpt(HTTP::OPT_HOST, 'gidix.de');
	$HTTPRequest->setOpt(HTTP::OPT_REQ_FILE, 'index.php');

	$HTTPRequestData = HTTP::allocData();
	$HTTPRequestData->add('getParam', 'value');
	$HTTPRequestData->add('anotherGetParam', 'test');

	$HTTPRequest->setOpt(HTTP::OPT_DATA, $HTTPRequestData);

	$HTTPRequest->send(function(HTTPResponse $response) {
		echo 'responseCode: '.$response->getResponseCode().PHP_EOL;
		echo 'mimeType: '.$response->getMimeType().PHP_EOL;
		echo 'response: '.$response->getResponse();
	});

	HTTP::dealloc($HTTPRequest);
?>
