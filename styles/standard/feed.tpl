<!-- BEGIN feed -->
	<div class="solid item">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="70" valign="top">
					<!-- IF feed.USER_ID -->
						<a href="user.php?id={feed.USER_ID}"><img class="img" height="50" width="50" src="images/avatar/mini/{feed.AVATAR}" /></a>
					<!-- ELSE -->
						<img class="img" height="50" width="50" src="images/avatar/mini/{feed.AVATAR}" />
					<!-- ENDIF -->
				</td>
				
				<td>
					<!-- IF feed.USER_ID -->
						<a class="{feed.USER_LEGEND}" href="user.php?id={feed.USER_ID}">{feed.USERNAME}</a>
					<!-- ELSE -->
						<span>Unbekannt</span>
					<!-- ENDIF -->
					
					in <b><a href="viewtopic.php?id={feed.TOPIC_ID}&p={feed.POST_ID}#{feed.POST_ID}">{feed.TOPIC_TITLE}</a></b><br />
					{feed.POST_TEXT}<!-- IF feed.more -->... <a href="viewtopic.php?id={feed.TOPIC_ID}&p={feed.POST_ID}#{feed.POST_ID}">mehr</a><!-- ENDIF --><br />
					
					<div class="footerBar">
						<small class="grey"><span>{feed.POST_TIME} | <a href="viewforum.php?id={feed.FORUM_ID}">{feed.FORUM_NAME}</a></span></small>
					</div>
				</td>
				
				<td width="25px" class="tdMore" onClick="self.location.href = 'viewtopic.php?id={feed.TOPIC_ID}&p={feed.POST_ID}#{feed.POST_ID}';">
					&nbsp;
				</td>
			</tr>
		</table>
	</div>
<!-- END feed -->

<!-- IF MORE --> 
	<div style="padding:5px 0">
		<a href="#" onclick="return feed.more({LIMIT})"><img src="styles/itschi/images/icon_more.gif" /> mehr anzeigen</a>
	</div>
<!-- ENDIF -->