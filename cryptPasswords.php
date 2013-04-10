<?php
	/**
	 *	== INFORMATION FOR CYPTING PASSWORDS ==
	 *
	 *	md5 is NO secure algorithm to safely to store passwords so my thought was to create a new algorithm for crypting
	 *	Passwords based on PHP's crypt()-function.
	 *
	 *	The idea:
	 *
	 *	Every new installation generates a unique, SHA512-crypted ID and stores it inside it's config.php.
	 *	Every password is first crypted in MD5 and then in SHA256 with a salt. The salt is the ID created at installation runtime.
	 *
	 *	Because a few people already use a copy of Itschi 3.0 this is no easy task. So here are the steps:
	 *
	 *	1. I will introduce this new method in README.md and announce it for inclusion later.
	 *	2. The installer gets updated to create the unique ID and already crypt the first user.
	 * 	3. At the same time as step #3 occurs login.php and register.php get updated to use the new method.
	 * 	4. For people that used Itschi already there will be a file update_passwords.php that runs through every password
	 *		and crypt it further with SHA256 and the special salt.
	 *
	 *	What do you think of it? Please comment in this file and/or itschi.net and write your name before every comment
	 * 	you make (only in this file).
	 */

	include 'base.php';

	$passwort = 'IchBinEinPasswort';
	echo 'Original: '.$passwort;

	function cryptPassword($pw) {
		return crypt($pw, '$5$rounds=2048$UNIQUEIDHERE$');
	}

	$crypted = cryptPassword($pw);
	echo '<br />Crypted: '.$crypted;
?>