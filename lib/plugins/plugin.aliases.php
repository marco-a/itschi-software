<?php
	/**
	 *	@package	com.Itschi.base.plugins.Aliases
	 *	@since 		2013/06/22
	 *
	 *	This file contains a list of functions that act as an alias to various plugin functions.
	 *	These are usually shorter but have to be included separately for each plugin that wants to use them.
	 *	Utils functions do *NOT* have an alias. You will have to use the full class path for them.
	 */

	/*
	 *	SQL functions
	 */

	function SQL_fetch_array($res) {
		return plugin::SQL()->fetch_array($res);
	}

	function SQL_fetch_object($res) {
		return plugin::SQL()->fetch_object($res);
	}

	function SQL_num_rows($res) {
		return plugin::SQL()->num_rows($res);
	}

	function SQL_insertID() {
		return plugin::SQL()->insertID();
	}

	function SQL_chars($str) {
		return plugin::SQL()->chars($str);
	}

	function SQL_affected_rows() {
		return plugin::SQL()->affected_rows();
	}

	function SQL_query($qry, $unbuffered = false) {
		return plugin::SQL()->query($qry, $unbuffered);
	}

	function SQL_insert($table, $values) {
		return plugin::SQL()->insert($table, $values);
	}

	/*
	 *	TPL functions
	 */

	function TPL_areaAvailable($area) {
		return plugin::TPL()->areaAvailable($area);
	}

	function TPL_addToArea($area, $content) {
		return plugin::TPL()->addToArea($area, $content);
	}
?>