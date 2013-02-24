<?php
	/**
	*
	* @package com.Itschi.base.install
	* @since 2007/05/25
	*
	*/

	if (is_file('config.php')) {
		require_once 'config.php';

		if (!empty($prefix)) {
			header('Location: index.php');
			exit;
		}
	} else {
		$fd = @fopen('config.php', 'w+');

		if ($fd !== false) {
			fwrite($fd, '');
			fclose($fd);
		}
	}

	function read_ini($key) {
		$read = ini_get($key);

		if ($read == '1' || $read == 1 || $read == 'On' || $read == 'on') return true;
		if ($read == '0' || $read == 0 || $read == 'Off' || $read == 'off') return false;

		return $read;
	}

	$submit = (isset($_POST['submit'])) ? true : false;
	$db_host = (isset($_POST['db_host'])) ? $_POST['db_host'] : 'localhost';
	$db_username = (isset($_POST['db_username'])) ? $_POST['db_username'] : '';
	$db_pw = (isset($_POST['db_pw'])) ? $_POST['db_pw'] : '';
	$db_database = (isset($_POST['db_database'])) ? $_POST['db_database'] : '';
	$prefix = (isset($_POST['prefix'])) ? $_POST['prefix'] : 'itschi_';
	$username = (isset($_POST['username'])) ? $_POST['username'] : '';
	$email = (isset($_POST['email'])) ? $_POST['email'] : '';
	$password =	(isset($_POST['password'])) ? $_POST['password'] : '';
	$password2 = (isset($_POST['password2'])) ? $_POST['password2'] : '';
	$settings_title = (isset($_POST['settings_title']) ? $_POST['settings_title'] : '');
	$error = 0;
	$chmod = substr(sprintf('%o', fileperms(dirname(__FILE__))), -4);
	$chmod = ($chmod == '0777');
	$config_writable = is_writable('config.php');
	$php_v = sprintf('%.1lf', phpversion()); // SPRINTF FTW :3
	$php_version = ($php_v >= 5.3);
	$imagecreatefromgif = function_exists('imagecreatefromgif');

	@ini_set('allow_url_fopen', 1);

	$allow_url_fopen = read_ini('allow_url_fopen') == true ? true : false;

	if ($submit) {
		if ($chmod && $config_writable && $imagecreatefromgif && $allow_url_fopen) {
			$error = install();
		} else {
			$error = 10;
		}
	}

	function install() {
		global $username, $email, $password, $password2, $db_host, $db_username, $db_pw, $db_database, $prefix;

		$error = 0;

		$connect = @mysql_connect($db_host, $db_username, $db_pw) or ($error = 1);
		@mysql_select_db($db_database, $connect) or ($error = 1);

		if ($error == 1) {
			return 1;
		}

		if (!preg_match('#[a-z_-]+$#i', $prefix)) {
			return 2;
		}

		if (!$username || !$email || !$password) {
			return 3;
		}

		if (!preg_match('#^[a-z]{1,2}[a-z0-9-_]+$#i', $username)) {
			return 4;
		}

		if (mb_strlen($username) < 3 || mb_strlen($username) > 15) {
			return 5;
		}

		if (!preg_match('#^[a-z0-9_.-]+@([a-z0-9_.-]+\.)+[a-z]{2,4}$#si', $email)) {
			return 6;
		}

		if (mb_strlen($password) < 6) {
			return 7;
		}

		if ($password != $password2) {
			return 8;
		}

		@chmod(dirname(__FILE__) . '/', 0755);
		@chmod('images/avatar/', 0755);
		@chmod('lib/cache/', 0755);

		if (!is_writable('config.php')) {
			return 9;
		}

		$file = fopen('config.php', 'w');
		$bytes = fwrite($file, "<?php\r\n\r\n\$hostname = '" . $db_host . "';\r\n\$username = '" . $db_username . "';\r\n\$password = '" . $db_pw . "';\r\n\$database = '" . $db_database . "';\r\n\$prefix = '" . $prefix . "';\r\n\r\n?>");
		fclose($file);

		mysql_unbuffered_query('SET NAMES UTF8');
		mysql_unbuffered_query('BEGIN');

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "sessions` (
			  `session_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
			  `session_expire` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`session_id`),
			  KEY `session_expire` (`session_expire`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "banlist` (
			  `ban_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `ban_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `ban_reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `by_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ban_id`),
			  KEY `ban_time` (`ban_time`),
			  KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."permissions` (
				`permission_id` int(10) NOT NULL AUTO_INCREMENT,
				`group_id` int(10) NOT NULL,
				`permission_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				PRIMARY KEY (`permission_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."groups` (
			  	`group_id` int(10) NOT NULL AUTO_INCREMENT,
			  	`group_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '--',
			  	PRIMARY KEY (`group_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "bots` (
			  `bot_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `bot_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `bot_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`bot_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "bots` (`bot_id`, `bot_name`, `bot_agent`) VALUES
			(1, 'AdsBot (Google)', 'AdsBot-Google'),
			(2, 'Alexa (Bot)', 'ia_archiver'),
			(3, 'Alta Vista (Bot)', 'Scooter/'),
			(4, 'Ask Jeeves (Bot)', 'Ask Jeeves'),
			(5, 'Baidu (Spider)', 'Baiduspider+('),
			(6, 'Exabot (Bot)', 'Exabot/'),
			(7, 'FAST Enterprise (Crawler)', 'FAST Enterprise Crawler'),
			(8, 'FAST WebCrawler (Crawler)', 'FAST-WebCrawler/'),
			(9, 'Francis (Bot)', 'http://www.neomo.de/'),
			(10, 'Gigabot (Bot)', 'Gigabot/'),
			(11, 'Google Adsense (Bot)', 'Mediapartners-Google'),
			(12, 'Google Desktop', 'Google Desktop'),
			(13, 'Google Feedfetcher', 'Feedfetcher-Google'),
			(14, 'Google (Bot)', 'Googlebot'),
			(15, 'Heise IT-Markt (Crawler)', 'heise-IT-Markt-Crawler'),
			(16, 'Heritrix (Crawler)', 'heritrix/1.'),
			(17, 'IBM Research (Bot)', 'ibm.com/cs/crawler'),
			(18, 'ICCrawler - ICjobs', 'ICCrawler - ICjobs'),
			(19, 'ichiro (Crawler)', 'ichiro/'),
			(20, 'Majestic-12 (Bot)', 'MJ12bot/'),
			(21, 'Metager (Bot)', 'MetagerBot/'),
			(22, 'MSN NewsBlogs', 'msnbot-NewsBlogs/'),
			(23, 'MSN (Bot)', 'msnbot/'),
			(24, 'MSNbot Media', 'msnbot-media/'),
			(25, 'NG-Search (Bot)', 'NG-Search/'),
			(26, 'Nutch (Bot)', 'http://lucene.apache.org/nutch/'),
			(27, 'Nutch/CVS (Bot)', 'NutchCVS/'),
			(28, 'OmniExplorer (Bot)', 'OmniExplorer_Bot/'),
			(29, 'Online link (Validator)', 'online link validator'),
			(30, 'psbot (Picsearch)', 'psbot/0'),
			(31, 'Seekport (Bot)', 'Seekbot/'),
			(32, 'Sensis (Crawler)', 'Sensis Web Crawler'),
			(33, 'SEO (Crawler)', 'SEO search Crawler/'),
			(34, 'Seoma (Crawler)', 'Seoma (SEO Crawler)'),
			(35, 'SEOSearch (Crawler)', 'SEOsearch/'),
			(36, 'Snappy (Bot)', 'Snappy/1.1 ( http://www.urltrends.com/ )'),
			(37, 'Steeler (Crawler)', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/'),
			(38, 'Synoo (Bot)', 'SynooBot/'),
			(39, 'Telekom (Bot)', 'crawleradmin.t-info@telekom.de'),
			(40, 'TurnitinBot (Bot)', 'TurnitinBot/'),
			(41, 'Voyager (Bot)', 'voyager/1.0'),
			(42, 'W3 (Sitesearch)', 'W3 SiteSearch Crawler'),
			(43, 'W3C (Linkcheck)', 'W3C-checklink/'),
			(44, 'W3C (Validator)', 'W3C_*Validator'),
			(45, 'WiseNut (Bot)', 'http://www.WISEnutbot.com'),
			(46, 'YaCy (Bot)', 'yacybot'),
			(47, 'Yahoo MMCrawler (Bot)', 'Yahoo-MMCrawler/'),
			(48, 'Yahoo Slurp (Bot)', 'Yahoo! DE Slurp'),
			(49, 'Yahoo (Bot)', 'Yahoo! Slurp'),
			(50, 'YahooSeeker (Bot)', 'YahooSeeker/'),
			(51, 'Voila (Bot)', 'VoilaBot'),
			(52, 'Twiceler (Bot)', 'Twiceler');
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "config` (
			  `config_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `config_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `is_dynamic` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`config_name`),
			  KEY `is_dynamic` (`is_dynamic`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "config` (`config_name`, `config_value`, `is_dynamic`) VALUES
			('newest_user_id', '1', 1),
			('newest_user_level', '2', 1),
			('newest_username', '" . $username . "', 1),
			('posts_num', '0', 1),
			('topics_num', '1', 1),
			('users_num', '1', 1),
			('title', 'Titel der Seite', 0),
			('description', 'Ein Text der dein Forum beschreibt', 0),
			('theme', 'standard', 0),
			('email', '" . $email . "', 0),
			('topics_perpage', '20', 0),
			('posts_perpage', '10', 0),
			('points_topic', '1', 0),
			('points_post', '2', 0),
			('enable_captcha', '1', 0),
			('enable', '0', 0),
			('enable_avatars', '1', 0),
			('posts_perday', '30', 0),
			('enable_text', '', 0),
			('enable_unlock', '0', 0),
			('enable_bots', '1', 0),
			('unlock_delete', '7', 0),
			('avatar_min_height', '50', 0),
			('avatar_min_width', '50', 0),
			('avatar_max_width', '160', 0),
			('avatar_max_height', '180', 0),
			('enable_delete', '1', 0),
			('mail_limit', '200', 0),
			('max_post_chars', '50000', 0),
			('default_avatar', 'default.png', 0);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."forums` (
			  `forum_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `forum_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `is_category` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_toplevel` int(255) DEFAULT '0',
			  `forum_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_last_post_username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `forum_last_post_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `is_news` int(1) DEFAULT '0',
			  PRIMARY KEY (`forum_id`),
			  KEY `forum_order` (`forum_order`),
			  KEY `forum_level` (`forum_level`)
			) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "forums` (`forum_id`, `forum_name`, `forum_description`, `forum_order`, `is_category`, `forum_level`, `forum_posts`, `forum_topics`, `forum_closed`, `forum_last_post_id`, `forum_last_post_user_id`, `forum_last_post_time`, `forum_last_post_topic_id`, `forum_last_post_username`, `forum_last_post_user_level`) VALUES
			(1, 'Erste Kategorie', '', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', 0),
			(2, 'Erstes Forum', 'Ein Text der das Forum beschreibt', 2, 0, 0, 0, 1, 0, 1, 1, " . time() . ", 1, '" . $username . "', 2);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "forums_track` (
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`forum_id`,`user_id`),
			  KEY `forum_id` (`forum_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "forums_track` (`forum_id`, `user_id`, `mark_time`) VALUES
			(2, 1, " . time() . ");
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "mails` (
			  `mail_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `to_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mail_text` text COLLATE utf8_unicode_ci NOT NULL,
			  `mail_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `enable_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_signatur` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `mail_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `mail_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`mail_id`),
			  KEY `user_id` (`user_id`),
			  KEY `to_user_id` (`to_user_id`),
			  KEY `user_read` (`to_user_id`,`mail_read`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "online` (
			  `online_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
			  `online_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `online_agent` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  KEY `online_lastvisit` (`online_lastvisit`),
			  KEY `user_id` (`user_id`),
			  KEY `ip_userid` (`online_ip`,`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "plugin_server` (
			  `server_id` int(5) NOT NULL AUTO_INCREMENT,
			  `server_name` varchar(30) NOT NULL DEFAULT 'NoName',
			  `server_url` varchar(120) DEFAULT NULL,
			  `server_status` int(2) NOT NULL DEFAULT '0',
			  `server_plugins` int(5) NOT NULL,
			  `new_plugin` int(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`server_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."plugins` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `package` varchar(255) DEFAULT NULL,
			  `permissions` text,
			  `dependencies` text,
			  `minVersion` varchar(255) DEFAULT NULL,
			  `maxVersion` varchar(255) DEFAULT NULL,
			  `URL` text,
			  `datum` int(255) DEFAULT NULL,
			  `version` varchar(255) DEFAULT NULL,
			  `installed` int(1) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "poll_options` (
			  `topic_id` mediumint(8) unsigned NOT NULL,
			  `option_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `option_votes` mediumint(8) unsigned NOT NULL,
			  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`option_id`),
			  KEY `topic_id` (`topic_id`),
			  KEY `topicid_optionid` (`topic_id`,`option_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "poll_votes` (
			  `topic_id` mediumint(8) unsigned NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL,
			  PRIMARY KEY (`topic_id`,`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "posts` (
			  `post_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `post_text` text COLLATE utf8_unicode_ci NOT NULL,
			  `enable_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `enable_signatur` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `is_topic` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `post_edit_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `post_edit_username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `post_edit_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `post_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`post_id`),
			  KEY `topic_id` (`topic_id`),
			  KEY `forum_id` (`forum_id`),
			  KEY `user_id` (`user_id`),
			  KEY `topic_post_id` (`topic_id`,`post_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "posts` (`post_id`, `topic_id`, `forum_id`, `user_id`, `post_text`, `enable_bbcodes`, `enable_smilies`, `enable_urls`, `enable_signatur`, `is_topic`, `post_time`, `post_edit_user_id`, `post_edit_username`, `post_edit_user_level`, `post_edit_time`) VALUES
			(1, 1, 2, 1, 'Die Installation war erfolgreich.\r\n\r\nVielen Dank für das Nutzen des Itschi-Forums!', 1, 1, 1, 1, 1, " . time() . ", 0, '', 0, 0);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "ranks` (
			  `rank_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `rank_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `rank_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
			  `rank_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `rank_special` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`rank_id`),
			  KEY `rank_posts` (`rank_posts`),
			  KEY `rank_special` (`rank_special`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "ranks` (`rank_id`, `rank_image`, `rank_title`, `rank_posts`, `rank_special`) VALUES
			(1, 'gold4.gif', 'Stammgast', 100, 0),
			(2, 'gold3.gif', 'Stammgast', 90, 0),
			(3, 'gold2.gif', 'Stammgast', 80, 0),
			(4, 'gold1.gif', 'Betriebsnudel', 70, 0),
			(5, 'silber4.gif', 'Betriebsnudel', 60, 0),
			(6, 'silber3.gif', 'Betriebsnudel', 50, 0),
			(7, 'silber2.gif', 'Betriebsnudel', 40, 0),
			(8, 'silber1.gif', 'Neuling', 30, 0),
			(9, 'bronze4.gif', 'Neuling', 20, 0),
			(10, 'bronze3.gif', 'Neuling', 10, 0),
			(11, 'bronze2.gif', 'Neuling', 5, 0),
			(12, 'bronze1.gif', 'Neuling', 2, 0),
			(13, 'bronze_hidden.gif', 'Anfänger', 0, 0),
			(14, '', 'Administrator', 0, 1),
			(15, '', 'Moderator', 0, 1);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "smilies` (
			  `smilie_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `smilie_emotion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `smilie_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`smilie_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 AUTO_INCREMENT=24 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "smilies` (`smilie_id`, `smilie_emotion`, `smilie_image`) VALUES
			(1, ':D', 'biggrin.gif'),
			(2, ':-D', 'biggrin.gif'),
			(3, ':)', 'biggrin.gif'),
			(4, ':-)', 'biggrin.gif'),
			(5, ':P', 'razz.gif'),
			(6, ':-P', 'razz.gif'),
			(7, ':(', 'sad.gif'),
			(8, ':-(', 'sad.gif'),
			(9, ':oops:', 'redface.gif'),
			(10, ':shock:', 'eek.gif'),
			(11, ':o', 'eek.gif'),
			(12, ':evil:', 'evil.gif'),
			(13, ':roll:', 'rolleyes.gif'),
			(14, ';)', 'wink.gif'),
			(15, ';-)', 'wink.gif'),
			(16, '8)', 'cool.gif'),
			(17, ':lol:', 'lol.gif'),
			(18, ';(', 'cry.gif'),
			(19, ':!:', 'exclaim.gif'),
			(20, ':?:', 'question.gif'),
			(21, ':arrow:', 'arrow.gif'),
			(22, ':idea:', 'idea.gif'),
			(23, ':|', 'neutral.gif');
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `".$prefix."styles` (
			  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `author` varchar(255) DEFAULT NULL,
			  `version` varchar(255) DEFAULT NULL,
			  `directory` varchar(255) DEFAULT NULL,
			  `active` int(1) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
		");

		mysql_unbuffered_query("
			INSERT INTO `".$prefix."styles` (`id`, `title`, `author`, `version`, `directory`, `active`)
			VALUES
				(1, 'Standard', 'Itschi', '1.0.0', 'standard', 1);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "topics` (
			  `topic_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `topic_important` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_views` int(11) unsigned NOT NULL DEFAULT '0',
			  `poll_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `poll_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `poll_votes` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `topic_last_post_user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `topic_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`topic_id`),
			  KEY `forum_id` (`forum_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "topics` (`topic_id`, `forum_id`, `topic_title`, `user_id`, `username`, `user_level`, `topic_time`, `topic_important`, `topic_closed`, `topic_posts`, `topic_views`, `poll_title`, `poll_time`, `poll_votes`, `topic_last_post_user_id`, `topic_last_post_time`, `topic_last_post_post_id`, `topic_last_post_username`, `topic_last_post_user_level`, `topic_last_post_id`) VALUES
			(1, 2, 'Erstes Thema', 1, '" . $username . "', 2, " . time() . ", 0, 0, 0, 0, '', 0, 0, 1, " . time() . ", 0, '" . $username . "', 2, 1);
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "topics_track` (
			  `topic_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `mark_time` int(11) unsigned NOT NULL DEFAULT '0',
			  `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`topic_id`,`user_id`),
			  KEY `forum_id` (`forum_id`),
			  KEY `topic_id` (`topic_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");

		mysql_unbuffered_query("
			CREATE TABLE IF NOT EXISTS `" . $prefix . "users` (
			  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			  `user_lastvisit` int(11) unsigned NOT NULL DEFAULT '0',
			  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_rank` mediumint(8) NOT NULL DEFAULT '0',
			  `user_signatur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `user_signatur_bbcodes` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_signatur_smilies` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_signatur_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
			  `user_points` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
			  `user_ban` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `user_ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
			  `user_website` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_icq` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
			  `user_skype` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
			  `user_login` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `user_register` int(11) unsigned NOT NULL DEFAULT '0',
			  `user_mails` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `user_unlock` varchar(6) CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`user_id`),
			  UNIQUE KEY `username` (`username`),
			  UNIQUE KEY `email` (`user_email`),
			  KEY `ip` (`user_ip`),
			  KEY `user_lastvisit` (`user_lastvisit`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=1 AUTO_INCREMENT=2 ;
		");

		mysql_unbuffered_query("
			INSERT INTO `" . $prefix . "users` (`user_id`, `user_lastvisit`, `username`, `user_password`, `user_email`, `user_avatar`, `user_rank`, `user_signatur`, `user_signatur_bbcodes`, `user_signatur_smilies`, `user_signatur_urls`, `user_points`, `user_posts`, `user_ban`, `user_ip`, `user_website`, `user_icq`, `user_skype`, `user_login`, `user_level`, `user_register`, `user_mails`, `user_unlock`) VALUES
			(1, " . time() . ", '" . $username . "', '" . md5($password) . "', '" . $email . "', '', 14, '', 1, 1, 1, 10, 1, 0, '" . $_SERVER['REMOTE_ADDR'] . "', '', '', '', 0, 2, " . time() . ", 0, '');
		");

		mysql_unbuffered_query('COMMIT');

		return 11;
	}

	?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Itschi &rsaquo; Installation</title>
		<link rel="stylesheet" href="styles/standard/style.css" />
		<link rel="stylesheet" href="styles/installer.css" />

		<style>
		.textStatusOK {
			color: #0c7f09;
		}

		.textStatusNotOK {
			color: #a00000;
		}
		</style>
	</head>

	<body>
		<div id="wrapper">
			<div class="content">
				<form action="install.php" method="post">
					<img src="./images/logo.png" alt="Itschi - Setup" style="margin-bottom: 40px;" />

					<?php if ($error): ?>
					<div class="info">
						<?php if ($error == 1): ?>		Verbindung zur Datenbank fehlgeschlagen - Überprüfe die MySQL-Daten
						<?php elseif ($error == 2): ?>		Der Prefix darf nur aus folgenden Zeichen bestehen: A-Za-z_
						<?php elseif ($error == 3): ?>		Bitte Username, Email und Passwort eingeben!
						<?php elseif ($error == 4): ?>		Der Username darf keine Sonderzeichen, Umlaute oder Leerzeichen enthalten
						<?php elseif ($error == 5): ?>		Der Username muss 3 - 15 Zeichen lang sein!
						<?php elseif ($error == 6): ?>		Die Email ist ungültig!
						<?php elseif ($error == 7): ?>		Das Passwort muss mindestens 6 Zeichen lang sein!
						<?php elseif ($error == 8): ?>		Die Passwörter sind nicht gleich!
						<?php elseif ($error == 9): ?>		“config.php” ist nicht beschreibbar!
						<?php elseif ($error == 10): ?>		Es sind nicht alle Voraussetzungen erfüllt!
						<?php elseif ($error == 11): ?>		Das Forum wurde erfolgreich installiert! Lösche die Datei install.php! <a href="index.php">zum Forum</a>
						<?php endif; ?>
					</div>
					<div class="info_a"></div>
					<br /><br />
					<?php endif; ?>

					<section>
						<h2>Voraussetzungen</h2>

						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr>
								<td width="40%">CHMOD 0777</td>
								<td class="textStatus<?php echo ($chmod ? 'OK' : 'NotOK'); ?>">ist <?php echo ($chmod ? 'gesetzt' : 'nicht gesetzt');?></td>
							</tr>

							<tr>
								<td>config.php</td>
								<td class="textStatus<?php echo ($config_writable ? 'OK' : 'NotOK'); ?>">ist <?php echo ($config_writable ? 'beschreibbar' : 'nicht beschreibbar');?></td>
							</tr>

							<tr>
								<td>PHP Version (<?php echo $php_v;?>)</td>
								<td class="textStatus<?php echo ($php_version ? 'OK' : 'NotOK'); ?>">ist <?php echo ($php_version ? 'ausreichend' : 'nicht ausreichend');?></td>
							</tr>

							<tr>
								<td>imagecreatefromgif()</td>
								<td class="textStatus<?php echo ($imagecreatefromgif ? 'OK' : 'NotOK'); ?>">ist <?php echo ($imagecreatefromgif ? 'vorhanden' : 'nicht vorhanden');?></td>
							</tr>

							<tr>
								<td>allow_url_fopen [php.ini]</td>
								<td class="textStatus<?php echo ($allow_url_fopen ? 'OK' : 'NotOK'); ?>">ist <?php echo ($allow_url_fopen ? 'aktiviert' : 'nicht aktiviert');?></td>
							</tr>
						</table>
					</section>

					<section>
						<h2>MySQL</h2>

						<table cellspacing="0" cellpadding="5" width="100%" border="0">
							<tr>
								<td width="25%">Host:</td>
								<td><input type="text" name="db_host" value="<?php echo htmlspecialchars($db_host); ?>" size="30" /></td>
							</tr>

							<tr>
								<td>Username:</td>
								<td><input type="text" name="db_username" value="<?php echo htmlspecialchars($db_username); ?>" size="30" /></td>
							</tr>

							<tr>
								<td>Passwort:</td>
								<td><input type="password" name="db_pw" value="<?php echo htmlspecialchars($db_pw); ?>" size="30" /></td>
							</tr>

							<tr>
								<td>Datenbank:</td>
								<td><input type="text" name="db_database" value="<?php echo htmlspecialchars($db_database); ?>" size="30" /></td>
							</tr>

							<tr>
								<td>Prefix:</td>
								<td><input type="text" name="prefix" value="<?php echo htmlspecialchars($prefix); ?>" size="30" /></td>
							</tr>
						</table>
					</section>

					<section>
						<h2>Administrator-Zugang</h2>

						<table cellspacing="0" cellpadding="5" width="100%" border="0">
							<tr>
								<td width="25%">Benutzername:</td>
								<td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" /></td>
							</tr>

							<tr>
								<td>E-Mail-Adresse:</td>
								<td><input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>" /></td>
							</tr>

							<tr>
								<td>Passwort:</td>
								<td><input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" /></td>
							</tr>

							<tr>
								<td>Passwort wiederholen:</td>
								<td><input type="password" name="password2" value="<?php echo htmlspecialchars($password2); ?>" /></td>
							</tr>
						</table>
					</section>

					<section>
						<h2>Allgemeine Einstellungen</h2>

						<table cellspacing="0" cellpadding="5" width="100%" border="0">
							<tr>
								<td width="25%">
									Titel des Forums:
								</td>

								<td>
									<input type="text" name="settings_title" value="<?=htmlspecialchars($settings_title); ?>" />
								</td>
							</tr>

							<tr>
								<td colspan="2"><input type="submit" name="submit" value="Installieren" /></td>
							</tr>
						</table>
					</section>
				</form>
			</div>
		</div>
	</body>
</html>
