<?php
	/**
	*
	* @package com.Itschi.ACP.plugins
	* @since 2007/05/25
	*
	*/
	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zur&uuml;ck');
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
							if ($t != 'config' && $t != 'plugins' && $t != 'users') $pL .= '<li>'.$prefix.$t.'</li>';
						}

						$pL .= '
								</ul>
							</li>
						';
					}

				$pL .= '</ul>';
			}

			if ($p['TPL']) {
				$pL .= '<li class="main">HTML und JavaScript in Templates einf&uuml;gen</li>';
			}

			if ($p['HTTP']) {
				$pL .= '
					<li class="main">Verbindung zu diesen externen Servern aufnehmen:
						<ul>
				';

				foreach($p['HTTP'] as $s) {
					$pL .= '<li>'.$s.'</li>';
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
			'NAME'			=>	$title,
			'PACKAGE'		=>	$package,
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

	template::display('plugins', true);
?>