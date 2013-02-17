<?php
	/**
	 *	@package	com.Itschi.ACP.groups
	 */

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	template::display('groups', true);
?>