<?php template::display('header'); ?>

<div class="fLeft" style="width: 59%;">
	<h1 class="reset">
		<a href="./forum.php">Forum</a> &rsaquo;
		<a href="./viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>"><?=template::getVar('FORUM_NAME'); ?></a> &rsaquo;
		<?=template::getVar('TOPIC_TITLE'); ?>
	</h1>
</div>

<div class="fRight" style="width: 39%; text-align: right; padding-top: 2.5px;">
	<?php if (template::getVar('FORUM_CLOSED') || template::getVar('TOPIC_CLOSED')): ?>
		Geschlossen
	<?php else: ?>
		<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>" class="button">Beitrag schreiben</a>
	<?php endif; ?></b>
</div>

<div class="clear"></div>

<div id="posts">
	<?php foreach(template::$blocks['posts'] as $posts): ?>
		<?php if($posts['FIRST_POST'] && (int)$_GET['page'] < 2): ?>
			<table width="100%" class="firstPost">
				<tr>
					<td class="user" width="40px">
						<img src="./images/avatar/<?php if ($posts['USER_ID']): ?><?=$posts['USER_AVATAR']; ?><?php else: ?><?=template::getVar('AVATAR'); ?><?php endif; ?>" border="0" height="40" width="40" />
					</td>

					<td class="user">
						<?php if ($posts['USER_ID']): ?>
							<b><a class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a></b><br />
							<small class="grey">
								<?php if ($posts['USER_LEGEND'] == 'admin'): ?><b><?php endif; ?><?=$posts['USER_RANK']; ?><?php if ($posts['USER_LEGEND'] == 'admin'): ?></b><?php endif; ?> &nbsp;&minus;&nbsp;
								<?=$posts['USER_POSTS']; ?> Beiträge
							</small>
						<?php else: ?>
							<b>Unbekannt</b>
						<?php endif; ?>

						<small class="grey">
							&nbsp;&minus;&nbsp; <?=$posts['TIME']; ?> Uhr
						</small>
					</td>

					<td class="actions" align="right">
						<?php if ($user->row): ?>
							<?php if (template::getVar('IS_MOD')): ?>
								<a href="movetopic.php?id=<?=template::getVar('TOPIC_ID'); ?>" class="button greyB">verschieben</a>
								<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&important=1" class="button greyB"><?php if (template::getVar('TOPIC_IMPORTANT')): ?>un<?php endif; ?>wichtig markieren</a>
								<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&close=1" class="button blackB"><?php if (template::getVar('TOPIC_CLOSED')): ?>öffnen<?php else: ?>schlie&szlig;en<?php endif; ?></a>
								&nbsp;&minus;&nbsp;
							<?php endif; ?>

							<?php if ($posts['USER_ID'] == $user->row['user_id'] || template::getVar('IS_MOD')): ?>
								<a href="<?php if ($posts['IS_TOPIC']): ?>newtopic.php?edit=1&id=<?=template::getVar('TOPIC_ID'); ?><?php else: ?>newpost.php?edit=1&id=<?=$posts['ID']; ?><?php endif; ?>" class="button greyB">Bearbeiten</a>
								<a href="<?php if ($posts['IS_TOPIC']): ?>viewforum.php?id=<?=template::getVar('FORUM_ID'); ?><?php else: ?>viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?><?php endif; ?>&delete=<?=$posts['ID']; ?>" class="button redB">Löschen</a>
							<?php endif; ?>

							<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>&quote=<?=$posts['ID']; ?>" class="button darkPurpleB">Zitieren</a>
						<?php endif; ?>
					</td>
				</tr>

				<tr>
					<td colspan="3" class="text">
						<?php if (template::getVar('POLL_TITLE')): ?>
							<div style="float: left; width: 45%; padding-right: 20px; word-wrap: break-word;">
								<?=$posts['TEXT']; ?>
							</div>
						<?php
							else:
								echo $posts['TEXT'];
							endif;
						?>

						<?php if (template::getVar('POLL_TITLE')): ?>
							<div class="fRight" style="width: 50%;">
								<form action="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>" method="post">
									<h2 class="title" style="margin-top: 0; padding-top: 0;">
										<b><?=template::getVar('POLL_TITLE'); ?></b>
									</h2>

									<div class="clear"></div>

									<?php if (template::getVar('USER_VOTED')): ?>
										<?php foreach (template::$blocks['options'] as $options): ?>
											<table width="100%">
												<tr>
													<td>
														<?=$options['TEXT']; ?><br />
														<small class="Grey"><?=$options['PRO']; ?>%</small>
													</td>

													<td align="right">
														<div style="height: 30px;
																	line-height: 30px;
																	padding-left: 5px;
																	padding-right: 5px;
																	background: #82323e;
																	width:<?=$options['PIXEL']; ?>px;
																	color:white;
																	margin-bottom: 5px;
														">
															<b><?=$options['VOTES']; ?></b>
														</div>

														<small class="grey"></small>
													</td>
												</tr>
											</table>
										<?php endforeach; ?>

										<div style="margin-top: 20px;">
											<small class="grey">Stimmen gesamt: <b><?=template::getVar('POLL_VOTES'); ?></b></small>
										</div>
									<?php else: ?>

										<?php foreach (template::$blocks['options'] as $options): ?>
											<div style="padding: 10px;">
											<label for="option_<?=$options['ID']; ?>" style="cursor:pointer">
												<div style="float:left;width:30px">
													<input type="radio" id="option_<?=$options['ID']; ?>" value="<?=$options['ID']; ?>" name="option" />
												</div>

												<?=$options['TEXT']; ?>
											</label>
										</div>
										<?php endforeach; ?>

										<br />

										<input type="submit" name="submit" value="Voten" />
										<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&result=1" class="button greyB">Ergebnis anzeigen</a>

									<?php endif; ?>
								</form>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			</table>
		<?php else: ?>
			<table width="100%" class="post" cellspacing="0" cellpadding="0">
				<tr>
					<td width="60px" valign="top" style="padding: 0;">
						<?php if ($posts['USER_ID']): ?>
							<a href="user.php?id=<?=$posts['USER_ID']; ?>">
								<img src="images/avatar/<?=$posts['USER_AVATAR']; ?>" border="0" height="50" width="50" />
							</a>
						<?php else: ?>
							<img border="0" height="50" width="50" src="images/avatar/<?=template::getVar('AVATAR'); ?>" />
						<?php endif; ?>
					</td>

					<td valign="top" class="postContent">
						<a name="<?=$posts['TRACK']; ?>"></a>

						<div class="user">
							<?php if ($posts['USER_ID']): ?>
								<b><a class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a></b><?php if ($posts['USER_LEGEND'] == 'admin'): ?> <small class="grey">(Administrator)</small><?php endif; ?>
							<?php else: ?>
								<b>Unbekannt</b>
							<?php endif; ?>
						</div>

						<?=$posts['TEXT']; ?>

						<?php if ($posts['EDIT_USER_ID']): ?>
							<div style="border-top:1px solid #dddddd;padding:5px;">Der Eintrag wurde am <?=$posts['EDIT_TIME']; ?> Uhr von <?php if ($posts['EDIT_USER_ID']): ?><a class="<?=$posts['EDIT_USER_LEGEND']; ?>" href="user.php?id=<?=$posts['EDIT_USER_ID']; ?>"><?=$posts['EDIT_USERNAME']; ?></a><?php else: ?><span>Unbekannt</span><?php endif; ?> geändert.</div><br />
						<?php endif; ?>

						<?php if ($posts['USER_SIGNATUR']): ?>
							<div style="border-top:1px solid #dddddd;padding:5px;"><?=$posts['USER_SIGNATUR']; ?></div>
						<?php endif; ?>

						</div>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>

					<td>
						<div class="fLeft" style="width: 49%;">
							<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&page=<?=template::getVar('PAGE'); ?>#<?=$posts['ID']; ?>">
								<small><span><?=$posts['TIME']; ?> Uhr</span></small>
							</a>
						</div>

						<div class="fRight" style="width: 49%; text-align: right;">
							<?php if ($user->row): ?>
								<small>
									<?php if($posts['USER_ID'] == $user->row['user_id'] || template::getVar('IS_MOD')): ?>

									 <a href="<?php if ($posts['IS_TOPIC']): ?>newtopic.php?edit=1&id=<?=template::getVar('TOPIC_ID'); ?><?php else: ?>newpost.php?edit=1&id=<?=$posts['ID']; ?><?php endif; ?>">Bearbeiten</a> &nbsp;&nbsp;
									 <a href="<?php if ($posts['IS_TOPIC']): ?>viewforum.php?id=<?=template::getVar('FORUM_ID'); ?><?php else: ?>viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?><?php endif; ?>&delete=<?=$posts['ID']; ?>">Löschen</a> &nbsp;&nbsp;

									<?php endif; ?>

									<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>&quote=<?=$posts['ID']; ?>">Zitieren</a>
								</small>
							<?php endif; ?>
						</div>

						<div class="clear"></div>
					</td>
				</tr>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

<br />
<table width="100%">
	<tr>
		<td valign="top">
			<?=template::getVar('TOPIC_POSTS'); ?> <?php if (template::getVar('TOPIC_POSTS') == '1'): ?>Beitrag<?php else: ?>Beiträge<?php endif; ?>

			<?php if (template::getVar('PAGES_NUM') > 1): ?>
			 | Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?>
			<?php endif; ?>
		</td>
		<td valign="top" align="right">
			<?php if (template::getVar('FORUM_CLOSED') || template::getVar('TOPIC_CLOSED')): ?>
				Geschlossen
			<?php else: ?>
				<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>" class="button">Beitrag schreiben</a>
			<?php endif; ?>
		</td>
	</tr>
</table>

<?php template::display('footer'); ?>