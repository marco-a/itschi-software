<?php
	/**
	 *
	 */

	abstract class standard {
		public static function menu() {
			$pages = template::getMenu();

			foreach ($pages as $k => $v) {
				if ($k == 'forum') {
					$forumPages = array(
						'forum', 'viewtopic', 'viewforum', 'newtopic', 'newpost', 'search'
					);
					
					echo '
						<li><a href="./'.$k.'.php" '.((in_array(template::getPage(), $forumPages)) ? 'class="active"' : '').'>'.$v.'</a></li>
					';
				} else {
					echo '
						<li><a href="./'.$k.'.php" '.((template::getPage() == $k) ? 'class="active"' : '').'>'.$v.'</a></li>
					';
				}
			}
		}
	}
?>