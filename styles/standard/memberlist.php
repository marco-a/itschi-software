<?php template::display('header'); ?>

<div class="sections">
	<ul>
		<li><a href="memberlist.php" <?php if (!template::getVar('MODE')): ?>class="active"<?php endif; ?>>Mitglieder</a></li>
		<li><a href="memberlist.php?mode=team" <?php if (template::getVar('MODE') == 'team'): ?>class="active"<?php endif; ?>>Team</a></li>
	</ul>

	<div class="clear"></div>
</div>

<div class="tabs noJS">
	<?php if (template::getVar('MODE') == 'team'): ?>
		<ul>
			<li><a href="memberlist.php?mode=team" <?php if (!template::getVar('CHAR')): ?>class="active"<?php endif; ?>>Alle</a></li>
			<li><a href="memberlist.php?mode=team&q=14" <?php if (template::getVar('CHAR') == '14'): ?>class="active"<?php endif; ?>>Administrator</a></li>
			<li><a href="memberlist.php?mode=team&q=15" <?php if (template::getVar('CHAR') == '15'): ?>class="active"<?php endif; ?>>Moderator</a></li>
		</ul>
		<?php else: ?>
		<ul>
			<li><a href="memberlist.php" <?php if (!template::getVar('CHAR')): ?>class="active"<?php endif; ?>>Alle</a></li>
			<li><a href="memberlist.php?q=a" <?php if (template::getVar('CHAR') == 'a'): ?>class="active"<?php endif; ?>>A</a></li>
			<li><a href="memberlist.php?q=b" <?php if (template::getVar('CHAR') == 'b'): ?>class="active"<?php endif; ?>>B</a></li>
			<li><a href="memberlist.php?q=c" <?php if (template::getVar('CHAR') == 'c'): ?>class="active"<?php endif; ?>>C</a></li>
			<li><a href="memberlist.php?q=d" <?php if (template::getVar('CHAR') == 'd'): ?>class="active"<?php endif; ?>>D</a></li>
			<li><a href="memberlist.php?q=e" <?php if (template::getVar('CHAR') == 'e'): ?>class="active"<?php endif; ?>>E</a></li>
			<li><a href="memberlist.php?q=f" <?php if (template::getVar('CHAR') == 'f'): ?>class="active"<?php endif; ?>>F</a></li>
			<li><a href="memberlist.php?q=g" <?php if (template::getVar('CHAR') == 'g'): ?>class="active"<?php endif; ?>>G</a></li>
			<li><a href="memberlist.php?q=h" <?php if (template::getVar('CHAR') == 'h'): ?>class="active"<?php endif; ?>>H</a></li>
			<li><a href="memberlist.php?q=i" <?php if (template::getVar('CHAR') == 'i'): ?>class="active"<?php endif; ?>>I</a></li>
			<li><a href="memberlist.php?q=j" <?php if (template::getVar('CHAR') == 'j'): ?>class="active"<?php endif; ?>>J</a></li>
			<li><a href="memberlist.php?q=k" <?php if (template::getVar('CHAR') == 'k'): ?>class="active"<?php endif; ?>>K</a></li>
			<li><a href="memberlist.php?q=l" <?php if (template::getVar('CHAR') == 'l'): ?>class="active"<?php endif; ?>>L</a></li>
			<li><a href="memberlist.php?q=m" <?php if (template::getVar('CHAR') == 'm'): ?>class="active"<?php endif; ?>>M</a></li>
			<li><a href="memberlist.php?q=n" <?php if (template::getVar('CHAR') == 'n'): ?>class="active"<?php endif; ?>>N</a></li>
			<li><a href="memberlist.php?q=o" <?php if (template::getVar('CHAR') == 'o'): ?>class="active"<?php endif; ?>>O</a></li>
			<li><a href="memberlist.php?q=p" <?php if (template::getVar('CHAR') == 'p'): ?>class="active"<?php endif; ?>>P</a></li>
			<li><a href="memberlist.php?q=q" <?php if (template::getVar('CHAR') == 'q'): ?>class="active"<?php endif; ?>>Q</a></li>
			<li><a href="memberlist.php?q=r" <?php if (template::getVar('CHAR') == 'r'): ?>class="active"<?php endif; ?>>R</a></li>
			<li><a href="memberlist.php?q=s" <?php if (template::getVar('CHAR') == 's'): ?>class="active"<?php endif; ?>>S</a></li>
			<li><a href="memberlist.php?q=t" <?php if (template::getVar('CHAR') == 't'): ?>class="active"<?php endif; ?>>T</a></li>
			<li><a href="memberlist.php?q=u" <?php if (template::getVar('CHAR') == 'u'): ?>class="active"<?php endif; ?>>U</a></li>
			<li><a href="memberlist.php?q=v" <?php if (template::getVar('CHAR') == 'v'): ?>class="active"<?php endif; ?>>V</a></li>
			<li><a href="memberlist.php?q=w" <?php if (template::getVar('CHAR') == 'w'): ?>class="active"<?php endif; ?>>W</a></li>
			<li><a href="memberlist.php?q=x" <?php if (template::getVar('CHAR') == 'x'): ?>class="active"<?php endif; ?>>X</a></li>
			<li><a href="memberlist.php?q=y" <?php if (template::getVar('CHAR') == 'y'): ?>class="active"<?php endif; ?>>Y</a></li>
			<li><a href="memberlist.php?q=z" <?php if (template::getVar('CHAR') == 'z'): ?>class="active"<?php endif; ?>>Z</a></li>
		</ul>
	<?php endif; ?>

	<div class="content" <?php if (template::getVar('MODE') == 'team'): ?>style="border-top:1px solid #e0e0e0;"<?php endif; ?>>
		<div id="members" class="tabContent">
			<?php
				if (isset(template::$blocks['members'])):
					foreach(template::$blocks['members'] as $members):
			?>

						<div style="float:left;padding:15px 0;width:33%">
							<a style="float:left;margin-right:10px;width: 50px;height: 50px;" href="user.php?id=<?=$members['ID']; ?>"><img class="img" src="images/avatar/mini/<?=$members['AVATAR']; ?>" alt="<?=$members['USERNAME']; ?>" /></a>
							<b><a class="<?=$members['LEGEND']; ?>" href="user.php?id=<?=$members['ID']; ?>"><?=$members['USERNAME']; ?></a></b><br /><span><?=$members['RANK']; ?></span>
						</div>

			<?php
					endforeach;
				endif;
			?>

			<div class="clear"></div>
		</div>
	</div>
</div>

<?php if (template::getVar('PAGES_NUM') > '1'): ?>Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>

<?php template::display('footer'); ?>
