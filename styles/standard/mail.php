<?php template::display('header'); ?>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">

		<?php if (template::getVar('ERROR') == '1'): ?>		Die Nachricht wurde gel&ouml;scht
		<?php elseif (template::getVar('ERROR') == '2'): ?>	Die Nachrichten wurden gel&ouml;scht
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Der Ordner ist voll - Bitte l&ouml;sche einige Nachrichten
		<?php endif; ?>

	</div>
<?php endif; ?>

<div class="sections">
	<ul>
		<li><a href="./mail.php" <?php if (template::getVar('DIR') == 1): ?>class="active"<?php endif; ?>>Posteingang</a></li>
		<li><a href="./mail.php?dir=2" <?php if (template::getVar('DIR') != 1): ?>class="active"<?php endif; ?>>Gesendet</a></li>
		
		<li style="float: right;"><a href="./mail.php?dir=<?=template::getVar('DIR'); ?>&mode=new">Neue Mail</a></li>
	</ul>

	<div class="clear"></div>
</div>

<form method="post" action="mail.php?dir=<?=template::getVar('DIR'); ?>">
	<table class="form" cellpadding="5" cellspacing="0">
		<tr>
			<td class="title" colspan="2">Nachricht</td>
			<td class="title" width="120">Markiert</td>
		</tr>

		<?php
			if (isset(template::$blocks['mails'])):
				foreach(template::$blocks['mails'] as $mails):
		?>

		<tr>
			<td class="inhalt center" width="50"><img src="styles/itschi/images/<?php if (!$mails['READ']): ?>new<?php endif; ?>topic.gif" border="0" /></td>
			<td class="inhalt"><b><a class="forum" href="mail.php?dir=<?=template::getVar('DIR'); ?>&mode=view&id=<?=$mails['ID']; ?>"><?=$mails['TITLE']; ?></a></b><br /><?php if (template::getVar('DIR') == '1'): ?>von<?php else: ?>an<?php endif; ?> <?php if ($mails['USERNAME']): ?><a class="<?=$mails['USER_LEGEND']; ?>" href="user.php?id=<?=$mails['USER_ID']; ?>"><?=$mails['USERNAME']; ?></a><?php else: ?><span>Unbekannt</span><?php endif; ?> - <small><span><?=$mails['TIME']; ?> Uhr</span></small></td>
			<td class="inhalt"><input type="checkbox" class="checkbox" name="<?=$mails['ID']; ?>" value="true"></td>
		</tr>

		<?php
				endforeach;
			else:
		?>

		<tr>
			<td class="inhalt center" width="22">&nbsp;</td>
			<td class="inhalt top" height="60"><br />In diesem Ordner befinden sich keine Nachrichten<br /><br /></td>
			<td class="inhalt">&nbsp;</td>
		</tr>

		<?php
			endif;
		?>

	</table>

	<table width="100%">
		<tr>
			<td>
				<?=template::getVar('MAILS_NUM'); ?> Nachricht<?php if (template::getVar('MAILS_NUM') != 1): ?>en<?php endif; ?>
				
				<?php if (template::getVar('PAGES_NUM') > '1'): ?>
					|
					Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> |
					<?=template::getVar('PAGES'); ?>
				<?php endif; ?>
			</td>

			<td valign="top" align="right">
				<a onclick="return Mark(1);" href="#" class="button greyB">Alle markieren</a>
				<a onclick="return Mark(0);" href="#" class="button greyB">Alle unmarkieren</a> &nbsp;&nbsp;
				<input type="submit" name="markierte" value="Markierte l&ouml;schen" />
			</td>
		</tr>
	</table>
</form>

<?php template::display('footer'); ?>