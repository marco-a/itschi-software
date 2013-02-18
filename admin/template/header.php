<!DOCTYPE html>
<html>
	<head>
		<title><?=template::getVar('PAGE_TITLE'); ?> - Administrationsbereich</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="./template/style.css" />
		<script type="text/javascript">
			token = '<?=template::getVar('USER_TOKEN'); ?>';
		</script>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../styles/standard/style.js"></script>
	</head>
	<body>
		<div id="user_wrap">
			<ul class="user">
				<li style="float: left;">
					<a class="noLink"><?=template::getVar('PAGE_TITLE'); ?></a>
				</li>

				<li><a href="../user.php?id=<?=$user->row['user_id']; ?>">Angemeldet als <b><?=$user->row['username']; ?></b></a></li>
				<li><a href="../">Zurück zur Hauptseite</a></li>

				<div class="clear"></div>
			</ul>
		</div>

		<div id="header_wrap">
			<header>
				<div class="logo">
					<img src="../styles/standard/images/ACP.png" alt="Logo" />
				</div>
			</header>
		</div>

		<section id="content">
			<div id="nav">
				<nav>
					<ul>
						<li><a href="index.php"<?php if (template::getVar('PAGE') == 'index'): ?>class="active"<?php endif; ?>>Startseite</a></li>
						<li><a href="settings.php"<?php if (template::getVar('PAGE') == 'settings'): ?>class="active"<?php endif; ?>>Einstellungen</a></li>
						<li><a href="users.php"<?php if (template::getVar('PAGE') == 'users' ||template::getVar('PAGE') == 'user'): ?>class="active"<?php endif; ?>>Mitglieder</a></li>
						<li><a href="banlist.php"<?php if (template::getVar('PAGE') == 'banlist' ||template::getVar('PAGE') == 'banlist-new'): ?>class="active"<?php endif; ?>>Sperrungen</a></li>
						<li><a href="forums.php"<?php if (template::getVar('PAGE') == 'forums' ||template::getVar('PAGE') == 'forum-new'): ?>class="active"<?php endif; ?>>Foren</a></li>
						<li><a href="bots.php"<?php if (template::getVar('PAGE') == 'bots' ||template::getVar('PAGE') == 'bot-new'): ?>class="active"<?php endif; ?>>Bots</a></li>
						<li><a href="smilies.php"<?php if (template::getVar('PAGE') == 'smilies' ||template::getVar('PAGE') == 'smilie-new'): ?>class="active"<?php endif; ?>>Smilies</a></li>
						<li><a href="ranks.php"<?php if (template::getVar('PAGE') == 'ranks' ||template::getVar('PAGE') == 'rank-new'): ?>class="active"<?php endif; ?>>Ränge</a></li>
						<li><a href="groups.php"<?php if (template::getVar('PAGE') == 'groups'): ?>class="active"<?php endif; ?>>Gruppen</a></li>
						<li><a href="plugins.php"<?php if (template::getVar('PAGE') == 'plugins'): ?>class="active"<?php endif; ?>>Plugins</a></li>
					</ul>

					<div class="clear"></div>
				</nav>

				<div class="clear"></div>
			</div>

			<div class="content">