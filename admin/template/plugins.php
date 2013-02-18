<?php template::display('header'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.permissionToggle').click(function() {
			var href = $(this).attr('data-id');
			var container = $('.permissionBox[data-id=' + href + ']');

			if (container.is(':hidden')) {
				container.slideDown();
				$(this).text('‹ Rechte & Abhängigkeiten ausblenden');
			} else {
				container.slideUp();
				$(this).text('Rechte & Abhängigkeiten einblenden ›');
			}
		});
	});
</script>

<div id="plugins">
	<div class="h2box" style="margin-bottom: 0;">
		<div class="fLeft" style="width: 40%">
			<h1>Installierte Plugins</h1>
		</div>

		<div class="fRight" style="padding-top: 7px;">

		</div>

		<div class="clear"></div>
	</div>

	<?php if (!template::getVar('INSTALLED')): ?>
		<div class="info">Es sind keine Plugins installiert.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['plugins'])):
			foreach(template::$blocks['plugins'] as $plugins):
	?>
		<div class="item">
			<div class="fLeft" style="width: 69%;">
				<span class="package"><?=$plugins['PACKAGE']; ?></span>
				<h2><?=$plugins['NAME']; ?> <span class="version"><?=$plugins['VERSION']; ?></span></h2>

				<a href="javascript:void(0);" data-id="<?=$plugins['ID']; ?>" class="permissionToggle">Rechte &amp; Abhängigkeiten einblenden &rsaquo;</a><br /><br />

				<div class="permissionBox" data-id="<?=$plugins['ID']; ?>" style="display: none;">
					<div class="fLeft" style="width: 48%;">
						<div class="permissions">
							<?php if (!$plugins['PERMISSIONS']): ?>
								<span class="grey">
									Dieses Plugin benötigt keine besonderen Rechte.
								</span>
							<?php else: ?>
								<b>Berechtigungen:</b><br />
								<?=$plugins['PERMISSIONS']; ?>
							<?php endif; ?>
						</div>
					</div>

					<div class="fRight" style="width: 48%;">
						<b>Abhängigkeiten:</b><br /><br />

						<?php if (!$plugins['DEPENDENCIES']): ?>
							<span class="grey">Keine Abhängigkeiten von anderen Plugins</span>
						<?php else: ?>

						<?php endif; ?>
					</div>

					<div class="clear"></div>
				</div>
			</div>

			<div class="fRight" style="width: 29%; text-align: right;">
				<a href="./plugins_uninstall.php?id=<?=$plugins['ID']; ?>" class="button">Deinstallieren</a>
			</div>

			<div class="clear"></div>
		</div>
	<?php
			endforeach;
		endif;
	?>

	<br /><br />

	<div class="h2box">
		<h1 class="fLeft" style="width:30%;">Plugin Server <span class="light">(<?=template::getVar('pluginServerCount');?>)</span></h1>

		<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
			<a href="server-new.php" class="button greenB">Server hinzufügen</a>
		</div>

		<div class="clear"></div>
	</div>

	<?php if (template::getVar('pluginServerCount') == 0): ?>
		<div class="info">Es sind keine Plugin Server eingetragen.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['server'])):
			foreach(template::$blocks['server'] as $server):
	?>
				<div class="item">
					<div class="fLeft" style="width: 69%;">
						<strong><?=$server['NAME']; ?></strong> - <?php echo ($server['SERVERSTATUS'] ? '<small class="green">Online</small>' : '<small class="red">Offline</small>');?><br />
						<small><?=$server['URL']; ?><span class="grey">plugins.json</span></small>
					</div>

					<div class="fRight" style="width: 29%; text-align: right;">
						<?php if ($server['SERVERSTATUS']): ?>
							<a href="plugins.php?list=<?=$server['ID']; ?>" class="button greyB">Plugin Liste</a>
						<?php endif; ?>
							<a href="server-new.php?id=<?=$server['ID']; ?>" class="button greyB">bearbeiten</a>
							<a href="plugins.php?remove=<?=$server['ID'];?>" class="button redB">entfernen</a>
					</div>

					<div class="clear"></div>
				</div>
	<?php
			endforeach;
		endif;
	?>

	<br /><br />

	<div class="h2box">
		<div class="fLeft" style="width: 40%;">
			<h1>Verfügbare Plugins</h1>
		</div>

		<div class="fRight" style="padding-top: 7px;">

		</div>

		<div class="clear"></div>
	</div>

	<?php if (!template::getVar('AVAILABLE')): ?>
		<div class="info">Es sind keine Plugins verfügbar.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['available'])):
			foreach(template::$blocks['available'] as $available):
	?>
				<div class="item">
					<div class="fLeft" style="width: 69%;">
						<span class="package"><?=$available['PACKAGE']; ?></span>
						<h2><?=$available['NAME']; ?> <span class="version"><?=$available['VERSION']; ?></span></h2>

						<div class="fLeft" style="width: 48%;">
							<div class="permissions">
								<?php if (!$available['PERMISSIONS']): ?>
									<span class="grey">
										Dieses Plugin benötigt keine besonderen Rechte.
									</span>
								<?php else: ?>
									<div>
										<span onclick="return Spoiler(this);"><b>Berechtigungen: </b><a href="#" onclick="return false;" style="font-weight:bold;">Anzeigen</a></span>
										<div style="display:none" class="bbcode_box">
											<?=$available['PERMISSIONS']; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="fRight" style="width: 48%;">
							<b>Abhängigkeiten:</b><br /><br />

							<?php if (!$available['DEPENDENCIES']): ?>
								<span class="grey">Keine Abhängigkeiten von anderen Plugins</span>
							<?php else: ?>

							<?php endif; ?>
						</div>
					</div>

					<div class="fRight" style="width: 29%; text-align: right;">
						<?php if ($available['COMPATIBLE']): ?>
							<a href="./plugins_install.php?id=<?=$available['ID']; ?>" class="button">Installieren</a>
						<?php else: ?>
							<small class="red">Nicht kompatibel mit dieser Version.</small>
						<?php endif; ?>
					</div>

					<div class="clear"></div>
				</div>
	<?php
			endforeach;
		endif;
	?>
</div>

<?php template::display('footer'); ?>