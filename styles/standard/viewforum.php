<?php template::display('header'); ?>

<div class="fLeft" style="width: 59%;">
	<h1 class="reset"><a href="./forum.php">Forum</a> &rsaquo; <?=template::getVar('FORUM_NAME'); ?></h1>
</div>

<div class="fRight" style="width: 39%; text-align: right; padding-top: 2.5px;">
	<a href="viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>&mark=1" class="button greyB">Alle Themen als gelesen markieren</a>
	&nbsp;

	<?php if (template::getVar('FORUM_CLOSED')): ?>
		Geschlossen
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button">Neues Thema</a>
	<?php endif; ?>
</div>

<div class="clear"></div>

<br /><br />

<div id="forums">
	<?php
		if (count(template::getVar('SUBFORUMS')) > 0) {
			echo '
				<h2 class="title" style="margin-top: 0; padding-top: 0;">Unterforen</h2>
			';

			foreach (template::getVar('SUBFORUMS') as $s) {
				echo '
					<div class="item">
						<table width="100%" border="0">
							<tr>
								<td class="center" width="6%">
									<img src="./styles/standard/images/icons/topics/'.$s['forum_icon'].'.png">
								</td>

								<td style="padding: 10px;" width="50%">
									<h3>
										<a class="forum" href="viewforum.php?id='.$s['forum_id'].'" width="50%">
											'.$s['forum_name'].'
										</a>
									</h3>

									'.$s['forum_description'].'
								</td>

								<td width="10%" class="center">
									<b style="font-size: 16px;">'.$s['TOPICS'].'</b><br />
									<small class="grey">Them'.(($s['TOPICS'] == 1) ? 'a' : 'en').'</small>
								</td>

								<td width="10%" class="center">
									<b style="font-size: 16px;">'.$s['POSTS'].'</b><br />
									<small class="grey">Beitr'.(($s['POSTS'] == 1) ? 'ag' : 'äge').'</small>
								</td>

								<td width="22%" style="padding-left:10px">
							';
							
								if ($s['LAST_POST_ID']):
									echo '
										von
									';

									if ($s['LAST_POST_USER_ID']):
										echo '
											<a class="'.$s['LAST_POST_USER_LEGEND'].'" href="user.php?id='.$s['LAST_POST_USER_ID'].'">
												'.$s['LAST_POST_USERNAME'].'
											</a>
										';
									else:
										echo '
											<span>Unbekannt</span>
										';
									endif;
								
									echo '
										<a href="viewtopic.php?id='.$s['LAST_POST_TOPIC_ID'].'&p='.$s['LAST_POST_ID'].'#'.$s['LAST_POST_ID'].'">
											<img title="neusten Beitrag anzeigen" border="0" src="./styles/standard/images/neubeitrag.gif" />
										</a>
										<br />

										<span>
											<small class="grey">'.$s['LAST_POST_TIME'].' Uhr</small>
										</span>
									';
								else:
									echo '
										<small class="grey">-- kein Beitrag</small>
									';
								endif;
				echo '
						</td>
							</tr>
						</table>
					</div>
				';
			}

			echo '
				<div class="clear"></div>
				<h2 class="title">Themen</h2>
			';
		}

		if (isset(template::$blocks['topics'])) {
			foreach (template::$blocks['topics'] as $topic):
	?>
			<div class="item">
				<table class="form" width="100%">
					<tr>
						<?php if ($topic['NEW']): ?>
							<td class="status unread center" width="6%">
						<?php else: ?>
							<td class="status center" width="6%">
						<?php endif; ?>

							<img src="styles/standard/images/icons/topics/<?=$topic['ICON']; ?>topic.png" border="0" />
						</td>

						<td width="50%">
							<?php if ($topic['NEW']): ?>
								<a href="viewtopic.php?id=<?=$topic['ID']; ?>&view=track#post">
									<img alt="Neuster ungelesener Beitrag" src="./styles/standard/images/neubeitrag.gif" border="0" />
								</a>
							<?php endif; ?>

							<a class="forum" href="viewtopic.php?id=<?=$topic['ID']; ?>"><?=$topic['TITLE']; ?></a> <?=$topic['PAGES']; ?>

							von
							<?php if ($topic['USER_ID']): ?>
								<a class="<?=$topic['USER_LEGEND']; ?>" href="user.php?id=<?=$topic['USER_ID']; ?>"><?=$topic['USERNAME']; ?></a>
							<?php else: ?>
								<span>Unbekannt</span>
							<?php endif; ?>

							<br />
							<span>
								<small class="grey"><?=$topic['TIME']; ?> Uhr</small>
							</span>
						</td>

						<td class="center" width="10%">
							<b style="font-size: 16px;"><?=$topic['POSTS']; ?></b><br />
							<small class="grey">Beitr<?php if ($topic['POSTS'] == 1): ?>ag<?php else: ?>äge<?php endif; ?></small>
						</td>

						<td class="center" width="10%">
							<b style="font-size: 16px;"><?=$topic['VIEWS']; ?></b><br />
							<small class="grey">Besuch<?php if ($topic['VIEWS'] == 1): ?><?php else: ?>er<?php endif; ?></small>
						</td>

						<td width="22%" style="padding-left:10px">
							von
							<?php if ($topic['LAST_POST_USER_ID']): ?>
								<a class="<?=$topic['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$topic['LAST_POST_USER_ID']; ?>"><?=$topic['LAST_POST_USERNAME']; ?></a>
							<?php else: ?>
								<span>Unbekannt</span>
							<?php endif; ?>&nbsp;

							<a href="viewtopic.php?id=<?=$topic['ID']; ?>&p=<?=$topic['LAST_POST_ID']; ?>#<?=$topic['LAST_POST_ID']; ?>">
								<img src="./styles/standard/images/neubeitrag.gif" border="0" title="Letzter Beitrag" />
							</a><br />

							<span><small class="grey"><?=$topic['LAST_POST_TIME']; ?> Uhr</small></span>
						</td>
					</tr>
				</table>
			</div>
	<?php
			endforeach;
		} else {
	?>

		<div class="info">In diesem Forum existieren (noch) keine Themen.</div>

	<?php
		}
	?>
</div>

<br /><br />

<div class="fLeft" style="width: 59%;">
	<span class="grey"><?=template::getVar('TOPICS'); ?> Them<?php if (template::getVar('TOPICS') == 1): ?>a<?php else: ?>en<?php endif; ?></span>

	<?php if (template::getVar('PAGES_NUM') > 1): ?>

	 | Seite <?=template::getVar('PAGE'); ?> von {PAGES_NUM} | <?=template::getVar('PAGES'); ?>

	<?php endif; ?>
</div>

<div class="fRight" style="width: 39%; text-align: right;">
	<a href="viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>&mark=1" class="button greyB">Alle Themen als gelesen markieren</a>
	&nbsp;
	
	<?php if (template::getVar('FORUM_CLOSED')): ?>
		Geschlossen
	<?php else: ?>
		<a href="newtopic.php?id=<?=template::getVar('FORUM_ID'); ?>" class="button">Neues Thema</a>
	<?php endif; ?>
</div>

<div class="clear"></div>

<?php template::display('footer'); ?>