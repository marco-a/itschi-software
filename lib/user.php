<?php
	/**
	*
	* @package com.Itschi.base.user
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class user {
		public $row = false;
		private $session = 'forum_user_id';
		private $cookie_lifetime = 2678400;
		public $ranks = false;
		private $ranks_cache = array();
		private $session_started = false;

		function user() {
			global $db;

			if (!empty($_REQUEST[session_name()])) {
				$this->session_started = true;
				session_start();
			}

			if (empty($_SESSION[$this->session])) {
				if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
					$this->login($_COOKIE['username'], $_COOKIE['password']);
				}
			} else {
				$this->update_vars();

				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET user_lastvisit = ' . time() . '
					WHERE user_id = ' . (int)$this->row['user_id']
				);

				if ($this->row['user_ban']) {
					$this->check_ban();
				}
			}

			$this->online_global();

			$db->query('
				DELETE
				FROM ' . ONLINE_TABLE . '
				WHERE online_lastvisit < ' . (time() - 300)
			);
		}

		function login($username, $password, $autologin = false, $redirect = '') {
			global $db, $token;

			$res = $db->query('
				SELECT *
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $db->chars($username) . "'
					AND user_password = '" . $db->chars($password) . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			if (!$row) {
				return false;
			}

			if ($row['user_unlock']) {
				message_box('Du hast Deine E-Mail noch nicht best&auml;tigt', '/', 'zur&uuml;ck zur Startseite');
			}

			if (!$this->session_started) {
				session_start();
				$this->session_started = true;
			}

			$_SESSION[$this->session] = $row['user_id'];
			$this->row = $row;

			if ($row['user_ban']) {
				$this->check_ban();
			}

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET	user_login = ' . time() . ",
					user_ip = '" . $_SERVER['REMOTE_ADDR'] . "',
					user_lastvisit = " . time() . '
				WHERE user_id = ' . $row['user_id']
			);

			if ($autologin) {
				setCookie('username', $row['username'], time() + $this->cookie_lifetime, '/');
				setCookie('password', $row['user_password'], time() + $this->cookie_lifetime, '/');
			}

			$db->query('
				DELETE FROM ' . ONLINE_TABLE . "
				WHERE online_ip = '" . $_SERVER['REMOTE_ADDR'] . "'
					AND user_id = " . $row['user_id']
			);

			$db->query('
				UPDATE ' . ONLINE_TABLE . '
				SET user_id = ' . $row['user_id'] . "
				WHERE online_ip = '" . $_SERVER['REMOTE_ADDR'] . "'
					AND user_id = 0
			");

			$this->online_global();

			setCookie('is_user', $this->row['username'], time() + 3600*24*30, '/');
			
			# $token->regenerate();
			
			if ($redirect) {
				header('Location: ' . $redirect);
			}

			return true;
		}

		function logout() {
			global $db, $token;

			if (empty($_SESSION[$this->session])) {
				return false;
			}

			$db->query('
				UPDATE ' . ONLINE_TABLE . '
				SET user_id = 0
				WHERE user_id = ' . $this->row['user_id']
			);
		
			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET user_lastvisit = 0
				WHERE user_id = ' . $this->row['user_id']
			);

			$this->row = false;
			session_destroy();
			unset($_SESSION[$this->session]);

			setCookie('username', '', -3600, '/');
			setCookie('password', '', -3600, '/');
			setCookie(session_name(), '', -3600, '/');

			$this->online_global();
			$token->regenerate();

			return true;
		}

		function online_global() {
			global $db;

			$user_id = (int)$this->row['user_id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$agent = $db->chars(trim(substr($_SERVER['HTTP_USER_AGENT'], 0, 149)));

			if ($user_id) {
				$res = $db->query('
					SELECT user_id
					FROM ' . ONLINE_TABLE . '
					WHERE user_id = ' . $user_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row) {
					$db->query('

						UPDATE ' . ONLINE_TABLE . '
						SET	online_lastvisit = ' . time() . ",
							online_agent = '" . $agent . "'
						WHERE user_id = " . $user_id
					);

					return;
				}
			}

			$res = $db->query('
				SELECT user_id
				FROM ' . ONLINE_TABLE . "
				WHERE online_ip = '" . $ip . "'
					AND user_id = 0
			");
			$row = $db->fetch_array($res);
			$db->free_result($res);

			if ($row) {
				$db->query('
					UPDATE ' . ONLINE_TABLE . '
					SET	user_id = ' . $user_id . ',
						online_lastvisit = ' . time() . ",
						online_agent = '" . $agent . "'
					WHERE online_ip = '" . $ip . "'
						AND user_id = 0
				");
			} else {
				$db->query('
					INSERT INTO ' . ONLINE_TABLE . '
					(user_id, online_lastvisit, online_ip, online_agent) VALUES
					(' . $user_id . ', ' . time() . ", '" . $ip . "', '" . $agent . "')
				");
			}
		}

		function update_vars() {
			global $db;

			if (!isset($_SESSION[$this->session])) {
				return false;
			}

			$res = $db->query('
				SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int)$_SESSION[$this->session]
			);

			$this->row = $db->fetch_array($res);
			$db->free_result($res);
		}

		function check_ban() {
			global $db;

			$res = $db->query('
				SELECT ban_id, ban_time, ban_reason
				FROM ' . BANLIST_TABLE . '
				WHERE user_id = ' . $this->row['user_id']
			);

			$row = $db->fetch_array($res);
			$db->free_result($res);

			if ($row && $row['ban_time'] > time())
			{
				$this->logout();

				message_box('Du wurdest gesperrt bis: ' . date('d.m.Y H:i', $row['ban_time']) . ' Uhr<br />Grund: <i>' . htmlspecialchars($row['ban_reason']) . '</i>', '/', 'zur&uuml;ck zur Startseite');
			}

			$db->query('

				UPDATE ' . USERS_TABLE . '
				SET user_ban = 0
				WHERE user_id = ' . $this->row['user_id']
			);

			$this->row['user_ban'] = 0;
		}

		function legend($level) {
			switch ($level)
			{
				case USER:	return '';
				case MOD:	return 'mod';
				case ADMIN:	return 'admin';
			}
		}

		function set_rank($user_id, $rank_id, $posts) {
			if (!$this->ranks)
			{
				global $cache;

				$this->ranks = $cache->get('ranks');
			}

			if ($rank_id)
			{
				$this->ranks_cache[$user_id] = array($this->ranks[$rank_id]['rank_title'], $this->ranks[$rank_id]['rank_image']);
			}
			else
			{
				foreach ($this->ranks[0] as $p => $rank)
				{
					if ($posts >= $p)
					{
						$this->ranks_cache[$user_id] = array($rank['rank_title'], $rank['rank_image']);
						return;
					}
				}
			}

			$this->ranks[$user_id] = array('', '');
		}

		function rank($user_id, $rank_id, $posts) {
			if (!isset($this->ranks_cache[$user_id]))
			{
				$this->set_rank($user_id, $rank_id, $posts);
			}

			return $this->ranks_cache[$user_id][0];
		}

		function rank_icon($user_id, $rank_id, $posts) {
			if (!isset($this->ranks_cache[$user_id]))
			{
				$this->set_rank($user_id, $rank_id, $posts);
			}

			return $this->ranks_cache[$user_id][1];
		}

		function online() {
			global $db;

			$res = $db->query('

				SELECT COUNT(*)
				FROM ' . ONLINE_TABLE
			);

			$row = $db->result($res, 0);
			$db->free_result($res);

			return $row;
		}
	}

?>