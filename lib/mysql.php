<?php
	/**
	*
	* @package com.Itschi.base.MySQL(i)
	* @since 2013/06/22
	*
	*/

	namespace Itschi\lib;

	class mysql {
		private $connection;

		public function __construct($host, $user, $pw, $db) {
			$this->connection = mysqli_connect($host, $user, $pw, $db);

			if (mysqli_connect_error()) {
				die(mysqli_connect_error());
			}

			mysqli_set_charset($this->connection, 'utf8');
		}

		protected function error($sql) {
			exit('
				<title>SQL Error</title>
				<link rel="stylesheet" href="./styles/error.css" />
				<div id="fatalError">
					<div class="title"><h2>SQL-Error <span>(' . mysqli_errno($this->connection) . ')</span></h2></div>

					<div class="error MySQL">
						' . mysqli_error($this->connection) . '

						<div class="code"><code>' . htmlspecialchars($sql) . '</code></div>
					</div>
				</div>
			');
		}

		public function query($sql) {
			if (preg_match('^SELECT COUNT\(([a-zA-Z0-9*]+)\) FROM^', $sql, $matches)) {
				$sql = "SELECT ".$matches[1]." FROM" . preg_replace('^SELECT COUNT\(([a-zA-Z0-9*]+)\) FROM^', '', $sql);
			}

			if (($result = mysqli_query($this->connection, $sql)) == FALSE) {
				$this->error($sql);
			}

			return $result;
		}

		public function unbuffered_query($sql) {
			return $this->query($sql);
		}

		public function fetch_object($res) {
			return mysqli_fetch_object($res);
		}

		public function fetch_array($res) {
			return mysqli_fetch_assoc($res);
		}

		public function num_rows($res) {
			return $res->num_rows;
		}

		public function insert_id() {
			return mysqli_insert_id($this->connection);
		}

		public function affected_rows() {
			return mysqli_affected_rows($this->connection);
		}

		public function free_result($res) {
			// return mysqli_free_result($res);
		}

		public function result($res, $int) {
			// return mysqli_data_seek($res, $int);
			return $this->num_rows($res);
		}

		public function chars($str) {
			return mysqli_real_escape_string($this->connection, $str);
		}
	}
?>
