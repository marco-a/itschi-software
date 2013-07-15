<?php
	/**
	*
	* @package com.Itschi.base.user.UserDataWritable
	* @since 2013/07/15
	*
	*/

	final class UserDataWritable extends UserData {
		/**
		 *	@name 	__construct
		 *			Constructor for UserDataWritable.
		 *
		 *	@param 	See below.
		 *	@return UserDataWritable
		 */

		public function __construct($username, $avatar, $email, $level, $signature, $status, $points, $banned, $website, $skype) {
			$this->username = $username;
			$this->avatar = $avatar;
			$this->email = $email;
			$this->level = $level;
			$this->signature = $signature;
			$this->status = $status;
			$this->points = $points;
			$this->posts = $posts;
			$this->banned = $banned;
			$this->website = $website;
			$this->skype = $skype;
		}

		/**
		 *	@name 	getIP
		 *			Purpose: Reset function, because it is not needed.
		 *
		 *	@return null
		 */

		public function getIP() {
			return null;
		}

		/**
		 *	@name 	getUserID
		 *			Purpose: Reset function, because it is not needed.
		 *
		 *	@return null
		 */

		public function getUserID() {
			return null;
		}
	}
?>