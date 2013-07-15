<?php
	/**
	*
	* @package com.Itschi.base.plugins.DBdata
	* @since 2013/07/15
	*
	*/

	include_once $root.'/lib/user/UserData.php';
	include_once $root.'/lib/user/UserDataWritable.php';

	final class DBdataWritable extends plugin {
		/**
		 * 	@name checkPermission
		 *
		 *	@return bool
		 */

		private function checkPermission() {
			if (!parent::hasPermission('DATA_WRITABLE')) {
				parent::logError('Access to dataWritable-functions denied.', 'DATA');
				return false;
			}

			return true;
		}

		/**
		 *	@name 	updateUserByID
		 *			Updates a user's data by his user ID.
		 *
		 *	@param 	int userID
		 *	@param 	UserDataWritable obj
		 *
		 *	@return null
		 */

		public function updateUserByID($userID, UserDataWritable $obj) {
			$this->checkPermission();

			global $db, $prefix;

			$db->query("
				UPDATE ".$prefix."users
				SET username = '".$obj->getUsername()."',
					user_avatar = '".$obj->getAvatar()."',
					user_email = '".$obj->getEmailAdress()."',
					user_level = '".$obj->getLevel()."',
					user_signatur = '".$obj->getSignature()."',
					user_status = '".$obj->getStatus()."',
					user_points = '".$obj->getPoints()."',
					user_ban = ".($obj->isBanned() ? 1 : 0).",
					user_website = '".$obj->getWebsite()."',
					user_skype = '".$obj->getSkype()."'
				WHERE user_id = '".(int)$userID."'
			");
		}
	}
?>