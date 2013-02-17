<?php template::display('header'); ?>

<div class="fLeft">
	<h1 class="title">Forum</h1>
</div>

<div class="fRight" style="width: 49%; text-align: right; padding-top: 10px;">
	<a href="forum.php?mark=1" class="button greyB">Alle Foren als gelesen markieren</a>
	<a href="search.php" class="button">Suchen</a>
</div>

<div class="clear"></div>

<div id="forums">
	<?php foreach(template::$blocks['forums'] AS $forum): ?>
		<?php if ($forum['IS_CATEGORY']): ?>
			<h2 class="title"><?=$forum['NAME']; ?></h2>
		<?php else: ?>
			<div class="item">
				<table width="100%" border="0">
					<tr>
						<td class="center" width="6%">
							<img src="styles/standard/images/icons/topics/<?=$forum['ICON']; ?>.png">
						</td>

						<td style="padding: 10px;">
							<h3>
								<a class="forum" href="viewforum.php?id=<?=$forum['ID']; ?>" width="50%">
									<?=$forum['NAME']; ?>
								</a>
							</h3>

							<?=$forum['DESCRIPTION']; ?>

							<?php
								if (count($forum['SUBFORUMS']) > 0) {
									echo '
										<br /><br />
										<b class="grey">Unterforen:</b><br />
									';

									$subforums = '';
									foreach ($forum['SUBFORUMS'] as $s) {
										$subforums .= '<a href="./viewforum.php?id='.$s['forum_id'].'">'.$s['forum_name'].'</a>, ';
									}

									echo mb_substr($subforums, 0, mb_strlen($subforums) - 2);
								}
							?>
						</td>

						<td width="9%" class="center">
							<b style="font-size: 16px;"><?=$forum['TOPICS']; ?></b><br />
							<small class="grey">Them<?=(($forum['TOPICS'] == 1) ? 'a' : 'en'); ?></small>
						</td>

						<td width="9%" class="center">
							<b style="font-size: 16px;"><?=$forum['POSTS']; ?></b><br />
							<small class="grey">Beitr<?=(($forum['POSTS'] == 1) ? 'ag' : '&auml;ge'); ?></small>
						</td>

						<td width="20%">
							<?php if ($forum['LAST_POST_ID']): ?>
								von

								<?php if ($forum['LAST_POST_USER_ID']): ?>
									<a class="<?=$forum['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$forum['LAST_POST_USER_ID']; ?>"><?=$forum['LAST_POST_USERNAME']; ?></a>
								<?php else: ?>
									<span>Unbekannt</span>
								<?php endif; ?>

								<a href="viewtopic.php?id=<?=$forum['LAST_POST_TOPIC_ID']; ?>&p=<?=$forum['LAST_POST_ID']; ?>#<?=$forum['LAST_POST_ID']; ?>">
									<img title="neusten Beitrag anzeigen" border="0" src="./styles/standard/images/neubeitrag.gif" />
								</a>
								<br />

								<span>
									<small class="grey"><?=$forum['LAST_POST_TIME']; ?> Uhr</small>
								</span>
							<?php else: ?>
								<small class="grey">-- kein Beitrag</small>
							<?php endif; ?>
						</td>
					</tr>
				</table>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<br />

<h2 class="title">Wer ist online?</h2>

<table width="100%">
	<tr>
		<td>
			Legende: <font color="#26677f">Administrator</font>, <font color="#3eb289">Moderator</font>, <font color="#aaaaaa">Bot</font>
			<br /><br />Mitglieder:

			<?php
			if (count(template::$blocks['online']) == 0) {
				echo '-- Niemand';
			} else {
				foreach(template::$blocks['online'] as $online): ?>
					<?=$online['SEPARATOR']; ?>

					<?php if ($online['IS_BOT']): ?>
						<span style="color:#aaa"><?=$online['BOT_NAME']; ?></span>
						<?php else: ?>
						<a class="<?=$online['LEGEND']; ?>" href="user.php?id=<?=$online['ID']; ?>"><?=$online['USERNAME']; ?></a>
						<?php endif; ?>
					<?php endforeach;
			} ?>
		</td>
	</tr>
</table>
<br />

<h2 class="title">Statistik</h2>

<table class="form" width="100%">
	<tr>
		<td class="inhalt">
			<b><?=template::getVar('USERS'); ?></b> Mitglieder &nbsp;| &nbsp;
			<b><?=template::getVar('TOPICS'); ?></b> Themen &nbsp;|&nbsp;
			<b><?=template::getVar('POSTS'); ?></b> Beitr&auml;ge &nbsp;|&nbsp;
			Neuster Benutzer: <a class="<?=template::getVar('NEWEST_USER_LEGEND'); ?>" href="user.php?id=<?=template::getVar('NEWEST_USER_ID'); ?>"><?=template::getVar('NEWEST_USERNAME'); ?></a>
		</td>
	</tr>
</table>

<?php template::display('footer'); ?>