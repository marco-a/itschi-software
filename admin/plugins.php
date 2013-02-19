<?php
	/**
	*
	* @package com.Itschi.ACP.plugins
	* @since 2007/05/25
	*
	*/
	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	function getPluginListURL($server_url) {
		$server_url = str_replace('http://', '', $server_url);

		$slash = substr($server_url, mb_strlen($server_url) - 1, 1);

		if ($slash != '/') {
			$server_url .= '/';
		}

		return sprintf('http://%s%s', $server_url, 'plugins.json');
	}

	if (isset($_GET['remove'])) {
		$id = (int)$_GET['remove'];

		$db->unbuffered_query(sprintf('DELETE FROM `%s` WHERE `server_id` = %d', SERVER_TABLE, $id));
	}

	if (isset($_GET['list'])) {
		$id = (int)$_GET['list'];

		if($id <= 0) {
			message_box('Es ist ein Fehler aufgetreten', './plugins.php', 'Zurück');
			exit;
		}

		$res = $db->query('
			SELECT *
			FROM ' . SERVER_TABLE . '
			WHERE server_id = ' . $id
		);

		$row = $db->fetch_object($res);
		$db->free_result($res);

		$server_url = getPluginListURL($row->server_url);
		$server_data = @json_decode(@file_get_contents($server_url));

		$plugin_mess = NULL;
		$error = FALSE;


		/*
		 *		TODO: Code aufräumen und auf sicherheit prüfen!
		 */
		if(isset($_GET['install'])) {
			$plugin_pack	=	htmlspecialchars($_GET['install']);
			$plugin_file 	= 	htmlspecialchars(urldecode($_GET['install'] . '.zip'));
			$plugin_url 	=	htmlspecialchars(urldecode($row->server_url));
            
			if(@copy($plugin_url . $plugin_file, $plugin_file)) {
				$plugin_mess = 'Download von "' . $plugin_url . $plugin_file .'" erfolgreich.<br />Datei wird entpackt.<br />';
	            $zip = new ZipArchive;
	            if ($zip->open($plugin_file) === TRUE) { 
	                $zip->extractTo('../plugins/'.$plugin_pack.'/'); 
	                $zip->close();
	                if(file_exists($plugin_file)){
					    unlink($plugin_file);
					}

					$plugin_mess .= 'Plugin wird installiert....'; // TODO: Installation!
				} else {
					$plugin_mess = '<strong>FEHLER:</strong> Datei konnte nicht entpackt werden.';
					$error = TRUE;
	                $zip->close();
	                if(file_exists($plugin_file)){
					    unlink($plugin_file);
					}
				}
			} else {
				$plugin_mess = '<strong>FEHLER:</strong> Download von "' . $plugin_url . $plugin_file .'" fehlgeschlagen';
				$error = TRUE;
			}
		}

		$plugincount = 0;

		foreach ($server_data as $data) {
			++$plugincount;

			$res = $db->query('
				SELECT `package`
				FROM ' . PLUGINS_TABLE . '
				WHERE `package` = \'' . $db->chars($data->package) . '\'');

			// never trust user data
			template::assignBlock('plugins', array(
				'NAME'			=>	htmlspecialchars($data->name),
				'VERSION'		=>	htmlspecialchars($data->version),
				'DESCRIPTION'	=>	htmlspecialchars($data->description),
				'LASTUPDATE'	=>	htmlspecialchars($data->lastUpdate),
				'DEVELOPER'		=>	htmlspecialchars($data->developer),
				'PACKAGE'		=>	htmlspecialchars($data->package),
				'INSTALLED'		=>	(bool) $db->num_rows($res),
			));

			$db->free_result($res);
		}

		template::assign(array(
			'SERVERID'		=>	htmlspecialchars($row->server_id),
			'SERVERNAME'	=>	htmlspecialchars($row->server_name),
			'PLUGINCOUNT'	=>	$plugincount, // count is nasty
			'MESSAGE'		=>	$plugin_mess
		));

		template::display('plugin-list');
		exit;
	}

	// get all plugins from directory for available plugins
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
			}
		}
	}


	// get plugin server from database
	$res = $db->query("
		SELECT *
		FROM " . SERVER_TABLE . "
		ORDER BY server_id ASC
	");

	$count = 0;

	while ($row = $db->fetch_object($res)) {
		$server_id 			= $row->server_id;
		$server_name 		= $row->server_name;
		$server_url 		= $row->server_url;

		$server_plugin_file = getPluginListURL($server_url);
		$server_content = @file_get_contents($server_plugin_file);
		$server_status = @json_decode($server_content);
		unset($server_content);
		$server_status = ($server_status == NULL || $server_status == false ? false : true);


		// assign
		template::assignBlock('server', array(
			'ID'			=>	$server_id,
			'NAME'			=>	htmlspecialchars($server_name),
			'URL'			=>	htmlspecialchars(urldecode($server_url)),
			'SERVERSTATUS'	=> 	$server_status
		));
		++$count;
	}

	template::assign('pluginServerCount', $count);

	// get plugins from database
	$res = $db->query("
		SELECT *
		FROM " . PLUGINS_TABLE . "
		ORDER BY title ASC, installed ASC
	");

	while ($row = $db->fetch_object($res)) {
		$title = $row->title;
		$permissions = @json_decode($row->permissions, true);
		$p = $permissions;
		$dependencies = @json_decode($row->dependencies, true);
		$minVersion = $row->minVersion;
		$maxVersion = $row->maxVersion;
		$package = $row->package;
		$URL = $row->URL;
		$version = $row->version;

		// compatible?
		$minVersion = str_replace('.', '', $minVersion);
		$maxVersion = str_replace('.', '', $maxVersion);
		$currVersion = str_replace('.', '', VERSION);

		if ($minVersion && $maxVersion) {
			$compatible = ($currVersion <= $maxVersion && $currVersion >= $minVersion);
		} else if ($minVersion) {
			$compatible = $currVersion >= $minVersion;
		} else if ($maxVersion) {
			$compatible = $currVersion <= $maxVersion;
		} else {
			$compatible = true;
		}

		if ($permissions && ($p['SQL'] || $p['TPL'] || $p['HTTP'] || $p['FILES'] || $p['CACHE'])) {
			$pL = '<ul>';

			if ($p['SQL']) {
				$pL .= '
					<li class="main">Datenbank-Zugriff
						<ul>
				';

					if ($p['SQL']['createTables']) {
						$pL .= '<li>Tabellen erstellen</li>';
					}

					if ($p['SQL']['accessTables']) {
						$pL .= '<li>Diese Tabellen lesen und beschreiben: <ul>';

						foreach($p['SQL']['accessTables'] as $t) {
							if ($t != 'config' && $t != 'plugins' && $t != 'users') $pL .= '<li>'.$prefix.htmlspecialchars($t).'</li>';
						}

						$pL .= '
								</ul>
							</li>
						';
					}

				$pL .= '</ul>';
			}

			if ($p['TPL']) {
				$pL .= '<li class="main">HTML und JavaScript in Templates einfügen</li>';
			}

			if ($p['HTTP']) {
				$pL .= '
					<li class="main">Verbindung zu diesen externen Servern aufnehmen:
						<ul>
				';

				foreach($p['HTTP'] as $s) {
					$pL .= '<li>'.htmlspecialchars($s).'</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}

			if ($p['FILES']) {
				$pL .= '
					<li class="main">Dateien im Plugin-Ordner:
						<ul>
				';

				if ($p['FILES']['accessFiles']) {
					$pL .= '<li>Lesen</li>';
				}

				if ($p['FILES']['writeFiles']) {
					$pL .= '<li>Beschreiben</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}

			if ($p['CACHE']) {
				$pL .= '
					<li class="main">Cache...
						<ul>
				';

				if ($p['CACHE']['readCache']) {
					$pL .= '<li>Lesen</li>';
				}

				if ($p['CACHE']['writeCache']) {
					$pL .= '<li>Beschreiben</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}
		}

		// assign
		template::assignBlock(($row->installed) ? 'plugins' : 'available', array(
			'NAME'			=>	htmlspecialchars($title),
			'PACKAGE'		=>	htmlspecialchars($package),
			'COMPATIBLE'	=>	$compatible,
			'VERSION'		=>	$version,
			'PERMISSIONS'	=>	$pL,
			'ID'			=>	$row->id
		));


		// count
		if ($row->installed) {
			$installed++;
		} else {
			$available++;
		}
	}

	template::assign(array(
		'AVAILABLE'	=>	$available > 0,
		'INSTALLED'	=>	$installed > 0
	));

	template::display('plugins');
?>