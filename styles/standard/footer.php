				<?php template::displayArea('underneathContent'); ?>
			</section>
			
			<div class="clear"></div>
		</div>
		
		<footer>
			<small>Forensoftware &copy; by <a target="_blank" href="http://www.it-talent.de/">IT-Talent.de</a></small>

			<?php if ($user->row['user_level'] == ADMIN): ?>
				<br /><small><b><a href="admin/index.php">Adminpanel</a></b></small>
			<?php endif; ?>

			<?php template::displayArea('footer'); ?>
		</footer>
	</body>
</html>
