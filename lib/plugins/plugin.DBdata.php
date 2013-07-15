<?php
	/**
	*
	* @package com.Itschi.base.plugins.DBdata
	* @since 2013/07/15
	*
	*/

	include_once $root.'/lib/user/UserData.php';

	class DBdata extends plugin {
		/**
		 * 	@name checkPermission
		 *
		 *	@return bool
		 */

		private function checkPermission() {
			if (!parent::hasPermission('DATA')) {
				parent::logError('Access to data-functions denied.', 'SQL');
				return false;
			}

			return true;
		}

		/**
		 * 	@name 	getUserByID
		 *
		 *	@param 	int userID
		 *	@return object UserData
		 */

		public function getUserByID($userID) {
			$this->checkPermission();

			global $db, $prefix, $config;

			$res = $db->query("SELECT * FROM ".$prefix."users WHERE user_id = '".(int)$userID."'");
			$row = $db->fetch_object($res);

			if (isset($row->user_id)) {
				return new UserData(
					$row->user_id,
					$row->username,
					(empty($row->user_avatar) ? $config['default_avatar'] : $row->user_avatar),
					$row->user_email,
					$row->user_level,
					$row->user_signatur,
					$row->user_status,
					$row->user_points,
					$row->user_posts,
					$row->user_ban,
					$row->user_ip,
					$row->user_website,
					$row->user_skype,
					$row->user_login,
					$row->user_register
				);
			} else {
				return null;
			}
		}

		/**
		 * 	@name 	getUserByName
		 *
		 *	@param 	string username
		 *	@return object UserData
		 */

		public function getUserByName($username) {
			$this->checkPermission();

			global $db, $prefix, $config;

			$res = $db->query("SELECT * FROM ".$prefix."users WHERE username = '".$db->chars($username)."'");
			$row = $db->fetch_object($res);

			if (isset($row->user_id)) {
				return new UserData(
					$row->user_id,
					$row->username,
					(empty($row->user_avatar) ? $config['default_avatar'] : $row->user_avatar),
					$row->user_email,
					$row->user_level,
					$row->user_signatur,
					$row->user_status,
					$row->user_points,
					$row->user_posts,
					$row->user_ban,
					$row->user_ip,
					$row->user_website,
					$row->user_skype,
					$row->user_login,
					$row->user_register
				);
			} else {
				return null;
			}
		}

		/**
		 *	@name 	getBannedUsers()
		 *			Returns a list of all banned users (if any).
		 *
		 *	@return array filled with UserData objects (if any)
		 */

		public function getBannedUsers() {
			$this->checkPermission();

			global $db, $prefix, $config;

			$res = $db->query("SELECT * FROM ".$prefix."users WHERE user_ban = 1");

			$users = array();
			while ($row = $db->fetch_object($res)) {
				$users[] = new UserData(
					$row->user_id,
					$row->username,
					(empty($row->user_avatar) ? $config['default_avatar'] : $row->user_avatar),
					$row->user_email,
					$row->user_level,
					$row->user_signatur,
					$row->user_status,
					$row->user_points,
					$row->user_posts,
					$row->user_ban,
					$row->user_ip,
					$row->user_website,
					$row->user_skype,
					$row->user_login,
					$row->user_register
				);
			}

			return $users;
		}
	}
?>