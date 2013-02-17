<?php
	/**
	*
	* @package com.Itschi.base.plugins
	* @since 2007/05/25
	*
	*/
	
	require 'plugin.interface.php';

	abstract class plugin implements pluginInterface {
		protected static $package = 'com.gidix.examplePlugin';
		protected static $permissions = array();

		/**
		 *	@name init
		 *
		 *	@return void
		 */

		public static function init($package) {
			self::$package = $package;
			self::$permissions = self::getPermissionList();
		}

		/**
		 *	@name logError
		 *
		 *	@return void
		 */

		protected static function logError($error, $type = NULL) {
			if (isset($type)) {
				$type = '<b>'.$type.'</b>: ';
			}

			echo '
				<link rel="stylesheet" href="styles/error.css" />

				<div id="fatalError">
					<div class="title"><h2>Es ist ein Fehler aufgetreten.</h2></div>

					<div class="error">
						<code>
							'.$type.''.$error.'
						</code>
					</div>
					
					<div class="info">
						<small>Verursacht durch: '.self::$package.'</small>
					</div>
				</div>
			';

			exit;
		}

		/**
		 *	@name getPermissions
		 *
		 *	@return array	
		 */

		protected static function getPermissionList() {
			global $db;

			$res = $db->query("SELECT permissions FROM " . PLUGINS_TABLE . " WHERE package = '" . self::$package . "'");
			$row = $db->fetch_object($res);

			if (isset($row->permissions)) {
				return json_decode($row->permissions, true);
			} else {
				return array();
			}
		}

		/**
		 * @name hasPermission
		 *
		 * @return bool
		 */

		protected static function hasPermission($type, $options = array()) {
			global $prefix;

			$permissions = self::$permissions;

			switch ($type) {
				case 'TPL':
				case 'HTTP':
					return isset($permissions[$type]);
					break;

				case 'SQL':
					if ($options[0] == 'accessTables') {
						foreach($options[1] as $t) {
							$k = array_search($t, $permissions['SQL']['accessTables']);

							if ($k === false || $t == 'config' || $t == 'users') {
								self::logError('Access denied for query "<b>' . $options[2] . '</b>"', 'SQL');
								return false;
							}
						}

						return true;
					} else if ($options[0] == 'createTables') {
						if ($permissions['SQL']['createTables'] != 1) {
							self::logError('This plugin does not have the required permissions to create tables.', 'SQL');
							return false;
						}

						return true;
					} else {
						return (isset($permissions['SQL']) && (count($permissions['SQL']['accessTables'] > 0) || $permissions['SQL']['createTables'] == 1));
					}

					break;

				default:
					return false;
			}
		}

		/**
		 *	@name run
		 *
		 *	@return void
		 */

		public static function run() {
			$pluginFile = './plugins/'.self::$package.'/files/main.php';

			if (@file_exists($pluginFile)) {

				// to be changed
				include $pluginFile;

			} else {
				echo '
					<div class="pluginInfo"><b>Warnung:</b> Plugin "'.self::$package.'" ist unvollst&auml;ndig. (<i>main.php missing</i>)</div>
				';
			}
		}

		/*
			== Class Instances =========================================
			   Do not modify or web server will explode.
			   It is highly recommended to avoid even looking at this code.
			   Why are you still reading this?
			============================================================
		*/

		/**
		 *	@name SQL
		 *
		 *	@return object
		 */

		public static function SQL() {
			return new SQL();
		}

		/**
		 *	@name TPL
		 *
		 *	@return object
		 */

		public static function TPL() {
			return new TPL();
		}

		/**
		 *	@name utils
		 *	
		 *	@return object
		 */

		public static function utils() {
			return new utils();
		}

	}
?>