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

		/**
		 *	@name 	menu
		 *			Holds all menu-items so plugins can add some.
		 */

		protected static $menu = array(
			'index'			=>	'Startseite',
			'forum'			=>	'Forum',
			'memberlist'	=>	'Mitglieder'
		);

		/**
		 *	@name 	style
		 *			Holds style-directory and style-directory's name.
		 */

		public static $style = array(
			'dir'	=>	'standard',
			'dirName'	=>	'standard',
		);

		public static function init() {
			global $db, $root;

			$res = $db->query("SELECT * FROM " . STYLES_TABLE . " WHERE active = 1 LIMIT 1");
			$row = $db->fetch_object($res);

			self::$style = array(
				'title'		=>	$row->title,
				'author'	=>	$row->author,
				'version'	=>	$row->version,
				'dir'		=>	$root . 'styles/' . $row->directory . '/',
				'dirName'	=>	$row->directory,
			);

			if (file_exists(self::$style['dir'] . 'functions.php')) {
				include self::$style['dir'] . 'functions.php';
			}

			self::$root = $root;
		}

		/**
		 *	@name 	logError
		 *			prints an error
		 *
		 *	@param 	string $error
		 *	@return void
		 */

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

		/**
		 *	@name 	assign
		 *			Assigns one ore more variables for use in a template.
		 *
		 *	@param 	mixed $vars
		 *	@param 	string $value
		 *	@return true
		 */

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

		/**
		 *	@name 	assignBlock
		 *			Assigns an array (a block) for use in a template (mainly for foreach()).
		 *
		 *	@param 	string $name
		 *	@param 	array $values
		 *	@return true
		 */

		public static function assignBlock($name, $values) {
			self::$blocks[$name][] = $values;
			return true;
		}

		/**
		 *	@name 	getVar
		 *			Returns the value of a variable assigned with assign.
		 *	
		 *	@param 	string $var
		 *	@return string
		 */

		public static function getVar($var) {
			return self::$vars[$var];
		}

		/**
		 *	@name 	getPage
		 *			Gets the name of the file currently displayed.
		 *
		 *	@return string
		 */

		public static function getPage() {
			return self::getVar('PAGE');
		}

		/**
		 *	@name 	getStyleDirName
		 *			Returns style's directory name.
		 *
		 *	@return string
		 */

		public static function getStyleDirName() {
			return self::$style['dirName'];
		}

		/**
		 *	@name 	addToMenu
		 *			Adds an item to the template's menu.
		 *
		 *	@param 	string $title
		 *	@param 	string $link
		 *	@return boolean
		 */

		public static function addToMenu($title, $link) {
			if (!preg_match('^(ht|f)tps?\:\/\/^', $link)) {
				self::$menu[$link] = $title;
				return true;
			}

			return false;
		}

		/**
		 *	@name 	getMenu
		 *			Returns an array containing all menu items for use in a template.
		 *
		 *	@return array
		 */

		public static function getMenu() {
			return self::$menu;
		}

		/**
		 *	@name 	end
		 *			Prohibits more templates to display.
		 *
		 *	@return void
		 */

		public static function end() {
			self::$end = true;
		}

		/**
		 *	@name 	isDebug
		 *			Returns true if template-system is in debug-mode.
		 *
		 *	@return boolean
		 */

		public static function isDebug() {
			return self::$debug;
		}

		/**
		 *	@name 	display
		 *			Displays a template-file.
		 *
		 *	@param 	string $section
		 * 	@return void
		 */

		public static function display($section) {
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