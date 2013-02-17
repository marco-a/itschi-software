<?php
	/**
	 *	@package	com.Itschi.ACP.index
	 */

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zur&uuml;ck');
		exit;
	}

	template::display('groups', true);
?>