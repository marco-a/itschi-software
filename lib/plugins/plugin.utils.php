<?php
	/**
	*
	* 	@package com.Itschi.base.plugins.utils
	* 	@since 2013/02/06
	*
	*	DO NOT MODIFY ANY OF THESE FUNCTIONS.
	*	These functions are essential for the use of plugins.
	*	Editing may cause your cat to be eaten by your subwoofer
	*	or serious frozen air around your head.
	*	It may also cause headaches.
	*/

	// TMP CHARSET
	if (!defined('CHARSET')) {
		define('CHARSET', 'UTF-8');
	}

	final class utils extends plugin {
		/*
			String functions
		*/

		public function makeClickable($str) {
			return make_clickable($str);
		}

		public function replace($text, $bbcodes, $smilies, $make_clickable, $html = true) {
			return replace($text, $bbcodes, $smilies, $make_clickable, $html);
		}

		public function strToUpper($str) {
			return mb_strtoupper($str, CHARSET);
		}

		public function strToLower($str) {
			return mb_strtolower($str, CHARSET);
		}

		public function strLength($str) {
			return mb_strlen($str, CHARSET);
		}

		public function strSubstr($str, $start, $length = NULL) {
			if ($length == NULL) $length = $this->strLength($str);

			return mb_substr($str, $start, $length, CHARSET);
		}

		/*
			site functions
		*/

		public function loggedIn() {
			global $user;

			return $user->row;
		}

		public function login_box() {
			if (!$this->loggedIn()) login_box();
		}

		public function message_box($message, $link, $link_text, $link2 = '', $link2_text = '', $refresh = false) {
			message_box($message, $link, $link_text, $link2, $link2_text, $refresh);
		}

		public function pages($gesamt, $pages, $link) {
			pages($gesamt, $pages, $link);
		}

		public function strip($var) {
			return (STRIP) ? stripslashes($var) : $var;
		}
	}


?>
