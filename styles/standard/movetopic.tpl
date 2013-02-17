<!-- INCLUDE header.tpl -->

<form action="movetopic.php?id={TOPIC_ID}" method="post">
<table class="form" cellpadding="5" cellspacing="0">
	<tr>
		<td colspan="4"><b><a href="forum.php">Forum</a></b> / <b><a class="forum" href="viewforum.php?id={FORUM_ID}">{FORUM_NAME}</a></b></td>
	</tr>
	<tr>
		<td class="title" colspan="2">Titel</td>
		<td class="title" align="center">Aufrufe</td>
		<td class="title" align="center">Beitr&auml;ge</td>
		<td width="22%" class="title">Letzter Beitrag</td>
	</tr>

	<!-- BEGIN topics -->

	<tr>
		<td class="inhalt" align="center" width="46"><input class="checkbox" type="checkbox" value="true" name="{topics.ID}"<!-- IF topics.ID == TOPIC_ID --> checked<!-- ENDIF -->></td>
		<td class="inhalt"><a class="forum" href="viewtopic.php?id={topics.ID}">{topics.TITLE}</a><br />von <a class="{topics.USER_LEGEND}" href="user.php?id={topics.USER_ID}">{topics.USERNAME}</a> - <small><span>{topics.TIME} Uhr</span></small></td>
		<td class="inhalt center" width="10%">{topics.POSTS}</td>
		<td class="inhalt center" width="10%">{topics.VIEWS}</td>
		<td class="inhalt" style="padding-left:10px">von <a class="{topics.LAST_POST_USER_LEGEND}" href="user.php?id={topics.LAST_POST_USER_ID}">{topics.LAST_POST_USERNAME}</a>&nbsp;<a href="viewtopic.php?id={topics.ID}&p={topics.LAST_POST_ID}#{topics.LAST_POST_ID}"><img src="themes/itschi/images/neubeitrag.gif" border="0" title="Letzter Beitrag" /></a><br /><span><small>{topics.LAST_POST_TIME} Uhr</small></span></td>
	</tr>

	<!-- END topics -->

</table>

<table width="100%">
	<tr>
		<td valign="top">

			{FORUM_TOPICS} <!-- IF FORUM_TOPICS == 1 -->Thema<!-- ELSE -->Themen<!-- ENDIF -->
			<!-- IF PAGES_NUM > 1 --> | Seite {PAGE} von {PAGES_NUM} | {PAGES}<!-- ENDIF -->

		</td>
		<td align="right"><a onclick="return Mark(1);" href="#">Alle markieren</a> | <a onclick="return Mark(0);" href="#">Alle unmarkieren</a><br /><br />in Forum 

			<select class="select" name="forum_id">

			<!-- BEGIN forums -->

			<option value="{forums.ID}">{forums.NAME}</option>

			<!-- END forums -->

			</select>
			<input type="submit" value="Verschieben" />
		</td>
	</tr>
</table>
</form>

<!-- INCLUDE footer.tpl -->