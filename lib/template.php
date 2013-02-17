<?php
	/**
	*
	* @package com.Itschi.base.template
	* @since 2007/05/25
	*
	*/

	abstract class template {
		protected static $root;
		protected static $vars = array();
		protected static $blocks = array();
		private static $end = false;
		private static $debug = false;

		public static $style = array(
			'dir'	=>	'standard'
		);

		public static function init() {
			global $db, $root;

			$res = $db->query("SELECT * FROM " . STYLES_TABLE . " WHERE active = 1 LIMIT 1");
			$row = $db->fetch_object($res);

			self::$style = array(
				'title'		=>	$row->title,
				'author'	=>	$row->author,
				'version'	=>	$row->version,
				'dir'		=>	$root . 'styles/' . $row->directory . '/'
			);

			if (file_exists(self::$style['dir'] . 'functions.php')) {
				include self::$style['dir'] . 'functions.php';
			}

			self::$root = $root;
		}

		public static function logError($error) {
			echo '
				<link rel="stylesheet" href="styles/error.css" />

				<div id="fatalError">
					<div class="title"><h2>Es ist ein Fehler aufgetreten.</h2></div>

					<div class="error">
						<code>
							<b>TPL:</b> '.$error.'
						</code>
					</div>
					
					<div class="info">
						<small>Verursacht durch: '.self::$style['title'].'</small>
					</div>
				</div>
			';

			exit;
		}

		public static function assign($vars, $value = '') {
			if (is_array($vars)) {
				foreach($vars as $k => $v) {
					self::$vars[$k] = $v;
				}
			} else {
				self::$vars[$vars] = $value; 
			}

			return true;
		}

		public static function assignBlock($name, $values) {
			self::$blocks[$name][] = $values;
			return true;
		}

		public static function getVar($var) {
			return self::$vars[$var];
		}

		public static function getPage() {
			return self::getVar('PAGE');
		}

		public static function getMenu() {
			return array(
				'index'			=>	'Startseite',
				'forum'			=>	'Forum',
				'memberlist'	=>	'Mitglieder'
			);
		}

		public static function end() {
			self::$end = true;
		}

		public static function isDebug() {
			return self::$debug;
		}

		public static function display($section, $acp = false) {
			global $db, $user, $config, $token;

			if (!self::$end) {
				page_vars();
				ob_start();

				if (self::$debug) {
					echo '<!--';
					print_r(self::$vars);
					print_r(self::$blocks);
					echo '-->';
				}

				$file = (preg_match('^/admin/^', $_SERVER['SCRIPT_NAME'])) ? './template/' . $section . '.php' : self::$style['dir'] . $section . '.php';

				if (!file_exists($file)) {
					self::logError('File "'.$section.'.php" does not exist.');
				} else {
					include $file;
				}

				$content = ob_get_contents();
				ob_end_clean();

				if (gettype($token) == 'object') $content = $token->auto_append($content);
				echo $content;
			}
		}
	}
?>