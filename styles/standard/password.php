<?php template::display('header'); ?>

<form action="password.php" method="post">
<div style="margin:0 auto;width:600px;">
<div class="solid"><strong>Passwort vergessen?</strong></div><br />

<?php if (template::getVar('ERROR')): ?>
<div class="info">
<?php
switch(template::getVar('ERROR')) {
  case '1': echo "Der Link stimmt nicht"; break;
	case '2': echo "Benutzername und Email stimmen nicht überein"; break;
	case '3': echo "Das neue Passwort muss aus mindestens 3 Zeichen bestehen"; break;
	case '4': echo "Die neuen Passwörter sind nicht gleich"; break;
	default:  echo "Unbekannter Fehler"; break;
}
?>
</div>
<div class="info_a"></div>
<?php endif; ?>

<div style="text-align:right">Ist Dir Dein Passwort wieder eingefallen? <b><a href="login.php">zum Login</a></b></div>

	<br />
	<table width="100%" cellpadding="7">
		<tr>
			<td align="right">Benutzername:</td>
			<td><input type="text" name="user" size="25" /></td>
		</tr>
		<tr>
			<td align="right">E-Mail:</td>
			<td><input type="text" name="email" size="25" /></td>
		</tr>
		<tr>
			<td height="10" colspan="2"></td>
		</tr>
		<tr>
			<td align="right">Neues Passwort:</td>
			<td><input type="password" name="pw" size="25" /></td>
		</tr>
		<tr>
			<td align="right">wiederholen:</td>
			<td><input type="password" name="pw2" size="25" /></td>
		</tr>
		<tr>
			<td height="10" colspan="2"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="send" value="Absenden" /> &nbsp; <a href="forum.php">Abbrechen</a></td>
		</tr>
	</table><br />
</div>
</form>

<?php template::display('footer'); ?>
