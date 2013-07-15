<?php
	/**
	*
	* @package com.Itschi.base.user.UserData
	* @since 2013/07/15
	*
	*/

	class UserData {
		protected $userID;
		protected $username;
		protected $avatar;
		protected $email;
		protected $level;
		protected $signature;
		protected $status;
		protected $points;
		protected $posts;
		protected $banned;
		protected $IP;
		protected $website;
		protected $skype;
		protected $lastLogin;
		protected $register;

		/**
		 * 	@name 	__construct
		 *			Constructor for UserData.
		 *
		 *	@param 	See below.
		 *	@return UserData
		 */

		public function __construct($userID, $username, $avatar, $email, $level, $signature, $status, $points, $posts, $banned, $IP, $website, $skype, $lastLogin, $register) {
			$this->userID = $userID;
			$this->username = $username;
			$this->avatar = $avatar;
			$this->email = $email;
			$this->level = $level;
			$this->signature = $signature;
			$this->status = $status;
			$this->points = (int)$points;
			$this->posts = (int)$posts;
			$this->banned = (bool)$banned;
			$this->IP = $IP;
			$this->website = $website;
			$this->skype = $skype;
			$this->lastLogin = (int)$lastLogin;
			$this->register = (int)$register;
		}

		/**
		 *	@name 	getUserID
		 *
		 *	@return int
		 */

		public function getUserID() {
			return $this->userID;
		}

		/**
		 *	@name 	getUsername
		 *
		 *	@return string
		 */

		public function getUsername() {
			return $this->username;
		}

		/**
		 *	@name 	getAvatar
		 *			Returns name of the avatar's image file (not the complete path!).
		 *
		 *	@return string
		 */

		public function getAvatar() {
			return $this->avatar;
		}

		/**
		 *	@name 	getEmailAdress
		 *
		 *	@return string
		 */

		public function getEmailAdress() {
			return $this->email;
		}

		/**
		 *	@name 	getSignature
		 *
		 *	@return string
		 */

		public function getSignature() {
			return $this->signature;
		}

		/**
		 *	@name 	getStatus
		 *
		 *	@return string
		 */

		public function getStatus() {
			return $this->status;
		}

		/**
		 *	@name 	getPoints
		 *
		 *	@return int
		 */

		public function getPoints() {
			return $this->points;
		}

		/**
		 *	@name 	hasEnoughPoints
		 *
		 *	@param 	int points
		 *	@return bool
		 */

		public function hasEnoughPoints($p) {
			return $this->points >= $p;
		}

		/**
		 *	@name 	getPostsNum
		 *
		 *	@return int
		 */

		public function getPostsNum() {
			return $this->posts;
		}

		/**
		 *	@name 	getIPAdress
		 *
		 *	@return string
		 */

		public function getIPAdress() {
			return $this->IP;
		}

		/**
		 *	@name 	getWebsite
		 *
		 *	@return string
		 */

		public function getWebsite() {
			return $this->website;
		}

		/**
		 *	@name 	getSkype
		 *
		 *	@return string
		 */

		public function getSkype() {
			return $this->skype;
		}

		/**
		 *	@name 	getLastLogin()
		 *
		 *	@return int
		 */

		public function getLastLogin() {
			return $this->lastLogin;
		}

		/**
		 *	@name 	getRegister
		 *
		 *	@return int
		 */

		public function getRegister() {
			return $this->register;
		}

		/**
		 *	@name 	getLevel
		 *
		 *	@return int
		 */

		public function getLevel() {
			return $this->level;
		}

		/**
		 *	@name 	isAdmin
		 *
		 *	@return bool
		 */

		public function isAdmin() {
			return $this->level == ADMIN;
		}

		/**
		 *	@name 	isMod
		 *
		 *	@return bool
		 */

		public function isMod() {
			return $this->level == MOD;
		}

		/**
		 *	@name 	isBanned
		 *
		 *	@return bool
		 */

		public function isBanned() {
			return $this->banned;
		}
	}
?>