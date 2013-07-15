<!-- INCLUDE header.tpl -->

<h1 class="reset">Letzte Beitr&auml;ge</h1>

<br />

<table width="100%" cellspacing="0" cellpadding="5">
	<tr>
		<td class="inhalt" width="70%" valign="top">
			<div id="feed" style="width:100%">
				<!-- INCLUDE feed.tpl -->
			</div>
		</td>
		
		<td class="side" width="30%" valign="top">
			<!-- IF USER_ID -->
				<h2 class="reset">
					<a class="{USER_LEGEND}" href="user.php?id={USER_ID}" style="font-weight:normal;">{USERNAME}</a>
				</h2>

				<small class="grey">
					Beitr&auml;ge: <span>{USER_POSTS}</span> -
					Punkte: <span>{USER_POINTS}</span><br /><br />
					
					<input name="status" type="text" id="statusInput" placeholder="Status..." value="{USER_STATUS}" />
					<input type="button" value="Update" id="statusUpdate" onClick="user.statusUpdate()" />
				</small>
			<!-- ELSE -->
				<h2>Login</h2>

				<form method="post" action="login.php">
					<input type="text" placeholder="Benutzername" name="username" style="width:95%;" />
					<input type="password" placeholder="Passwort" name="password" style="width:95%;" />

					<input type="hidden" name="merke" value="1" /><br /><br />
					<input type="submit" name="submit" value="Login" />
				</form>
			<!-- ENDIF -->
		</td>
	</tr>
</table>

<!-- INCLUDE footer.tpl -->