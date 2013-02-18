<?php
	use \Itschi\lib as lib;

	session_start();

	/**
	*
	* @package com.Itschi.base
	* @since 2013/02/15
	*
	*/

	error_reporting(E_ALL & ~E_NOTICE);

	header('Content-Type: text/html; charset=utf-8');

	$root = dirname(__FILE__) . '/';

	require $root.'config.php';
	require $root.'lib/user.php';
	require $root.'lib/mysql.php';
	require $root.'lib/token.php';
	require $root.'lib/cache.php';
	require $root.'lib/template.php';
	require $root.'lib/functions/global.php';
	require $root.'lib/constants.php';
	require $root.'lib/functions/date.php';
	require $root.'lib/plugins/plugins.php';

	if (empty($prefix)) {
		header('Location: install.php');
		exit;
	}

	$db = new lib\mysql($hostname, $username, $password, $database);
	$user = new lib\user();
	$cache = new lib\cache();
	$token = new lib\token();
	$plugins = new lib\plugins();
	$config = config_vars();
	$phpdate = new PHPDateTime();
	$page = basename($_SERVER['SCRIPT_FILENAME']);

	/**
	 *	@description 	Plugin and template functions
	 *				 	Do NOT delete these lines!
	 */

	template::init();

	require $root.'lib/plugins/plugin.php';
	require $root.'lib/plugins/plugin.SQL.php';
	require $root.'lib/plugins/plugin.TPL.php';
	require $root.'lib/plugins/plugin.utils.php';

	unset($password);

	$admin_tpl = ($user->row['user_level'] == ADMIN && (substr(dirname($_SERVER['PHP_SELF']), -5) == 'admin'));

	//$tpl->dir = ($admin_tpl) ? 'admin/template/' : 'styles/' . $config['theme'] . '/';

	if ($config['enable'] && $user->row['user_level'] != ADMIN && basename($_SERVER['PHP_SELF']) != 'login.php') {
		$message = ($config['enable_text']) ? $config['enable_text'] : 'Das Forum wurde deaktiviert';

		message_box($message, '', '');
	}

	/** initiate plugins **/
	$self = $_SERVER['PHP_SELF'];

	if (!preg_match('^\/admin\/^', $self)) {
		$pRes = $db->query("SELECT package FROM " . PLUGINS_TABLE . " WHERE installed = 1");

		plugin::init_classes();

		while ($pRow = $db->fetch_object($pRes)) {
			plugin::init($pRow->package);
			plugin::run();
		}
	}

	template::assign('admin', ($user->row['user_level'] == ADMIN));

	$token->_('user.php', 'GET', USER);
	$token->_('search.php', 'GET, POST', USER);
	$token->_('viewforum.php', 'GET', USER);
	$token->_('viewtopic.php', 'GET', USER);
	$token->_('status.php', 'POST', USER);

	$token->check('POST', $_POST);
	$token->check('GET', $_GET);
?>
