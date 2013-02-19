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
	}
?>