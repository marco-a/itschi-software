<?php
	/**
	*
	* @package com.Itschi.base.plugins.ACP
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class plugins {
		public function install($id) {

		}

		public static function removeFolder($dir) {
			if (!is_dir($dir) || is_link($dir)) {
				return unlink($dir);
			}
			foreach (scandir($dir) as $file) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				if (!self::removeFolder($dir . DIRECTORY_SEPARATOR . $file)) {
					chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
					if (!self::removeFolder($dir . DIRECTORY_SEPARATOR . $file)) {
						return false;
					}
				};
			}

			return rmdir($dir);
		}

		/**
		 * get all plugins from directory for available plugins
		 */
		public function synchronizeLocalPlugins()
		{
			global $db;

			$files = glob('../plugins/*', GLOB_ONLYDIR);
			foreach ($files as $file) {
				$json = @json_decode(file_get_contents($file . '/plugin.json'), true);

				if ($json) {
					$package = $db->chars($json['package']);
					$name = $db->chars($json['name']);
					$permissions = @json_encode($json['permissions']);
					$dependencies = @json_encode($json['dependencies']);
					$minVersion = $db->chars($json['minVersion']);
					$maxVersion = $db->chars($json['maxVersion']);
					$URL = $db->chars($json['URL']);

					$res = $db->query("
						SELECT id
						FROM " . PLUGINS_TABLE . "
						WHERE package = '".$package."'
					");

					$row = $db->fetch_object($res);
					if (!isset($row->id)) {
						$db->query("
							INSERT INTO " . PLUGINS_TABLE . "
							(title, package, permissions, dependencies, minVersion, maxVersion, URL, datum, installed)
							VALUES ('".$name."', '".$package."', '".$permissions."', '".$dependencies."', '".$minVersion."', '".$maxVersion."', '".$URL."', '".time()."', '0')
						");
					} else {
						$db->query("
							UPDATE " . PLUGINS_TABLE . " SET
								`title` = '".$name."',
								`permissions` = '".$permissions."',
								`dependencies` =  '".$dependencies."',
								`minVersion` =  '".$minVersion."',
								`maxVersion` =  '".$maxVersion."',
								`URL` =  '".$URL."',
								`datum` =  '".time()."'
							WHERE `id` = " . $row->id
						);
					}
				}
			}
		}
	}
?>