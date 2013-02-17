<?php template::display('header'); ?>

<?php if (template::getVar('IS_ONLINE')): ?>
	<style type="text/css">
		#profile .header {
			border-bottom: 5px solid green;
		}
	</style>
<?php endif; ?>

<style>
	#profile {
		margin-top: -40px;
	}
</style>

<div id="profile">
	<div class="header">
		<div class="avatar">
			<img class="img" src="images/avatar/<?=template::getVar('AVATAR'); ?>" border="0" height="100px" width="100" />
		</div>

		<div class="user">
			<h1>
				<?=template::getVar('USER_USERNAME'); ?>

				<?php if (template::getVar('IS_ONLINE')): ?>
					<small style="font-size: 12px;">&minus; <b>Online</b> seit <?=template::getVar('ONLINE_TIME'); ?> Minuten</small>
				<?php else: ?>
					<small style="font-size: 12px;">&minus; <b>Offline</b> seit <?=template::getVar('ONLINE_TIME'); ?> Uhr</small>
				<?php endif; ?>
			</h1>

			<span class="status"><?=template::getVar('USER_USERSTATUS'); ?></span>
		</div>

		<div class="clear"></div>
	</div>
</div>

<?php if (template::getVar('BAN')): ?>
	<div class="info">Das Mitglied ist gesperrt</div>
<?php endif; ?>

<div style="width:100%;">
	<table align="center" cellpadding="10" cellspacing="0">
		<tr>
			<td width="25%" valign="top">
				<table class="userProfile">
					<tr>
						<td width="100">
							<span>Rang:</span>
						</td>

						<td>
							<?=template::getVar('RANK'); ?>
							<?php if (template::getVar('RANK_ICON')): ?>
								<br /><img src="images/ranks/<?=template::getVar('RANK_ICON'); ?>" border="0" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><span>Registriert:</span></td>
						<td><?=template::getVar('REGISTER'); ?></td>
					</tr>
					<tr>
						<td><span>Punkte:</span></td>
						<td><?=template::getVar('POINTS'); ?></td>
					</tr>
					<tr>
						<td valign="top">
							<span>Beitr&auml;ge:</span>
						</td>
						
						<td>
							<?=template::getVar('POSTS'); ?> | <a href="search.php?user=<?=template::getVar('USER_USERNAME'); ?>">Beitr&auml;ge des Mitglieds</a><br />
							<span class="grey"><?=template::getVar('PRO'); ?>% aller Beitr&auml;ge<br />
							<?=template::getVar('PRODAY'); ?> Beitr&auml;ge pro Tag</span>
						</td>
					</tr>
				</table>
			</td>
			
			<td width="25%" valign="top">
				<table class="userProfile">
					<tr>
						<td><span>Homepage:</span></td>
						<td>
							<?php if (template::getVar('WEBSITE')): ?>

							<a target="_blank" href="<?=template::getVar('WEBSITE'); ?>"><?=template::getVar('WEBSITE'); ?></a>

							<?php else: ?>

							Keine

							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><span>ICQ:</span></td>
						<td><?php if (template::getVar('ICQ')): ?><?=template::getVar('ICQ'); ?><?php else: ?>Keine Angabe<?php endif; ?></td>
					</tr>
					<tr>
						<td><span>Skype:</span></td>
						<td><?php if (template::getVar('MSN')): ?><?=template::getVar('SKYPE'); ?><?php else: ?>Keine Angabe<?php endif; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<?php if (template::getVar('SIGNATUR')): ?>

	<div class="solid">&nbsp;</div><?=template::getVar('SIGNATUR'); ?></span><br /><br />

	<?php endif; ?>
</div>

<?php template::display('footer'); ?>