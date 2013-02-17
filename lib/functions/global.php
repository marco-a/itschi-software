<?php
	/**
	*
	* @package com.Itschi.base.functions.global
	* @since 2007/05/25
	*
	*/

	function page_vars() {
		global $user, $config;

		template::assign(array(
			'PAGE_ONLINE'		=>	$user->online(),
			'PAGE_TITLE'		=>	htmlspecialchars($config['title']),
			'PAGE_DESCRIPTION'	=>	htmlspecialchars($config['description']),
			'TITLE_TAG'			=>	'',
			'PAGE'				=>	str_replace('.php', '', basename($_SERVER['SCRIPT_FILENAME'])),
			'DEFAULT_AVATAR'	=>	$config['default_avatar']
		));
	}

	function config_vars() {
		global $db, $cache;

		$config = array();

		$res = $db->query('
			SELECT config_name, config_value
			FROM ' . CONFIG_TABLE . '
			WHERE is_dynamic = 1
		');

		while ($row = $db->fetch_array($res)) {
			$config[$row['config_name']] = $row['config_value'];
		}

		$db->free_result($res);

		return array_merge($cache->get('config'), $config);
	}

	function config_set($name, $value) {
		global $db;

		$db->query('
			UPDATE ' . CONFIG_TABLE . "
			SET config_value = '" . $db->chars($value) . "'
			WHERE config_name = '" . $db->chars($name) . "'
		");
	}

	function config_set_count($name, $count) {
		global $db;

		$db->query('
			UPDATE ' . CONFIG_TABLE . '
			SET config_value = config_value + ' . (int)$count . "
			WHERE config_name = '" . $db->chars($name) . "'
		");
	}

	function strip($var) {
		return (STRIP) ? stripslashes($var) : $var;
	}

	function pages($gesamt, $pages, $link) {
		$anhang = '';
		$seite = '';
		$x = $gesamt - 1;
		$y = $pages - 4;
		$z = $pages + 4;

		for ($i = 1; $i <= $gesamt; $i++) {
			if ($i <= 1 || $i > $x || $i >= $y && $i <= $z) {
				$seite .= $anhang . '<a ' . ($i == $pages ? 'class="hide"' : 'class="seite" href="' . $link . $i . '"') . '>' . $i . '</a> ';
				$anhang = '';
			} else {
				$anhang = ' ... ';
			}
		}

		return $seite;
	}

	function message_box($message, $link, $link_text, $link2 = '', $link2_text = '', $refresh = false) {
		global $tpl, $config, $user;

		template::assign(array(
			'MESSAGE'	=>	$message,
			'LINK'		=>	$link,
			'LINK_TEXT'	=>	$link_text,
			'LINK2'		=>	$link2,
			'LINK2_TEXT'	=>	$link2_text,
			'REFRESH'	=>	$refresh
		));

		template::display('message', preg_match('^/admin/^', $_SERVER['SCRIPT_NAME']));
		exit;
	}

	function login_box() {
		global $tpl, $config, $user;

		template::assign(array(
			'REDIRECT'	=>	htmlspecialchars($_SERVER['REQUEST_URI']),
			'ERROR'		=>	3
		));

		template::display('login');
	}

	function make_clickable($str) {
		$str = preg_replace('#(^|\n| )([a-z]+://?(www.)?\S+)#i', '$1<a target="_blank" href="$2">$2</a>', $str);
		$str = preg_replace('#(^|\n| )((?<!//)www.\S+)#i', '$1<a target="_blank" href="http://$2">$2</a>', $str);
		$str = preg_replace('/((^| )@([a-z]{1,2}[a-z0-9-_]+)($| |\?|\!|\.|:))/ie', "usernameCheck('\\2', '\\3', '\\4')", $str);

		return $str;
	}

	function bbcode_init() {
		return array(
			'\[code\](<br />|)(.*)\[/code\]'		=>	'<b>Code:</b><br /><div class="bbcode_box">$2</div>',
			'\[quote=(.*)\](<br />|)(.*)\[/quote\]'		=>	'<b>Zitat von $1:</b><br /><div class="bbcode_box">$3</div>',
			'\[quote\](<br />|)(.*)\[/quote\]'		=>	'<b>Zitat:</b><br /><div class="bbcode_box">$2</div>',
			'\[url=([a-z]+://?(www.)?\S+)\](.*)\[/url\]'	=>	'<a href="$1" target="_blank"><u>$3</u></a>',
			'\[url\]([a-z]+://?(www.)?\S+)\[/url\]'		=>	'<a href="$1" target="_blank"><u>$1</u></a>',
			'\[url=(.*)\](.*)\[/url\]'			=>	'<a href="http://$1" target="_blank"><u>$2</u></a>',
			'\[url\](.*)\[/url\]'				=>	'<a href="http://$1" target="_blank"><u>$1</u></a>',
			'\[b\](.*)\[/b\]'				=>	'<b>$1</b>',
			'\[i\](.*)\[/i\]'				=>	'<i>$1</i>',
			'\[u\](.*)\[/u\]'				=>	'<u>$1</u>',
			'\[img\](.*)\[/img\]'				=>	'<img src="$1" border="0" />',
			'\[color=(.*)\](.*)\[/color\]'			=>	'<font color="$1">$2</font>',
			'\[size=([0-9]{2,3})\](.*)\[/size\]'		=>	'<font style="font-size:$1%;">$2</font>',
			'\[s\](.*)\[/s\]'				=>	'<div><div class="dotted"><span onclick="return Spoiler(this);"><b>Spoiler: </b><a href="#" onclick="return false;"><b>Anzeigen</b></a></span></div><div style="display:none" class="bbcode_box">$1</div></div>'
		);
	}

	function replace($text, $bbcodes, $smilies, $make_clickable, $html = true) {
		global $bbcodes_array;

		if (!$bbcodes_array) {
			$bbcodes_array = bbcode_init();
		}

		if ($html) {
			$text = htmlspecialchars($text);
		}

		if ($make_clickable) {
			$text = make_clickable($text);
		}

		$text = nl2br($text);

		if ($bbcodes) {
			foreach ($bbcodes_array as $key => $value) {
				while (preg_match('#' . $key . '#Uis', $text)) {
					$text = preg_replace('#' . $key . '#Uis', $value, $text);
				}
			}
		}

		if ($smilies) {
			global $smilies_cache;

			if (!$smilies_cache) {
				global $cache;

				$smilies_cache = $cache->get('smilies');
			}

			foreach ($smilies_cache as $row) {
				$text = str_replace($row['smilie_emotion'], '<img src="images/smilies/' . $row['smilie_image'] . '" border="0" />', $text);
			}
		}

		return $text;
	}

	function usernameCheck($before, $username, $after) {
		global $db;

		$res = $db->query("SELECT user_id FROM " . USERS_TABLE . " WHERE username = '" . $db->chars($username) . "'");
		$row = $db->fetch_array($res);
		$db->free_result($res);

		if ($row) {
			return $before . '<span>@</span><a href="/' . $username . '">' . $username . '</a>' . $after;	
		}

		return '<span>@</span>' . $username;
	}

	function getTimeDifference($old, $new) {
		global $phpdate;
		
		$timeDiff = $phpdate->StringTimeDifference(date('d.m.Y', $old), date('d.m.Y', $new), false);
		if ($timeDiff == 0) {
			$time = date('H:i', $old);
		} elseif ($timeDiff == 1) {
			$time = 'Gestern, '.date('H:i', $old);
		} else {
			$time = 'Vor '.$timeDiff.' Tagen, '.date('H:i', $old);
		}
		
		return $time;
	}

	function getPage($var = 'page') {
		$page = (int)$_GET[$var];

		if ($page > 0) {
			return $page;
		} else {
			return 1;
		}
	}
?>