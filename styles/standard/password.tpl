<!-- INCLUDE header.tpl -->

<form action="password.php" method="post">
<div style="margin:0 auto;width:600px;">
<div class="solid"><strong>Passwort vergessen?</strong></div><br />

<!-- IF ERROR -->

<div class="info">
	<!-- IF ERROR == 1 -->		Der Link stimmt nicht
	<!-- ELSEIF ERROR == 2 -->	Benutzername und Email stimmen nicht &uuml;berein
	<!-- ELSEIF ERROR == 3 -->	Das neue Passwort muss aus mindestens 3 Zeichen bestehen
	<!-- ELSEIF ERROR == 4 -->	Die neuen Passw&ouml;rter sind nicht gleich
	<!-- ENDIF -->
</div>
<div class="info_a"></div>

<!-- ENDIF -->

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

<!-- INCLUDE footer.tpl -->