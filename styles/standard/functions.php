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

	/**
	 *	This function must be available in EVERY style. It gets called while initializing the style.
	 */

	function initializeStyle() {
		template::registerArea(array(
			'footer',
			'header',
			'aboveContent',
			'underneathContent',
			'aboveForum'
		));
	}
?>