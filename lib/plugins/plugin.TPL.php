<?php
	/**
	*
	* @package com.Itschi.base.plugins.TPL
	* @since 2007/05/25
	*
	*/

	final class TPL extends plugin {
		protected static $vars = array();
		protected static $blocks = array();

		function template() {
			global $root;

			$this->root = $root;
		}

		public function assign($vars, $value = false) {
			if (is_array($vars)) {
				self::$vars = array_merge($this->vars, $vars);
			} else if ($value) {
				self::$vars[$vars] = $value;
			}
		}

		public function assignBlock($name, $array) {
			self::$blocks[$name][] = (array)$array;
			return true;
		}

		protected function compile_vars($var) {
			return $template->compile_vars($var);
		}

		protected function compile_var($var) {
			return $template->compile_var($var);
		}

		protected function compile_tags($match) {
			switch ($match[1]) {
				case 'INCLUDE':
					return "<?php echo \$this->compile('" . $match[2] . "'); ?>";
				break;

				case 'IF':
					return $this->compile_if($match[2], false);
				break;

				case 'ELSEIF':
					return $this->compile_if($match[2], true);
				break;

				case 'ELSE':
					return "<?php } else { ?>";
				break;

				case 'ENDIF':
					return "<?php } ?>";
				break;

				case 'BEGIN':
					return "<?php if (isset(\$this->blocks['" . $match[2] . "'])) { foreach (\$this->blocks['" . $match[2] . "'] as \$_" . $match[2] . ") { ?>";
				break;

				case 'BEGINELSE':
					return "<?php } } else { { ?>";
				break;

				case 'END':
					return "<?php } } ?>";
				break;
			 }
		}

		protected function compile_if($code, $elseif) {
			$ex = explode(' ', trim($code));
			$code = '';

			foreach ($ex as $value) {
				$chars = strtolower($value);

				switch ($chars) {
					case 'and':
					case '&&':
					case 'or':
					case '||':
					case '==':
					case '!=':
					case '>':
					case '<':
					case '>=':
					case '<=':
					case '0':
					case is_numeric($value):
						$code .= $value;
					break;

					case 'not':
					case '!':
						$code .= '!';
					break;

					default:

						if (preg_match('/^[A-Za-z0-9_\-\.]+$/i', $value)) {
							$var = self::compile_var($value);

							$code .= "(isset(" . $var . ") ? " . $var . " : '')";
						} else {
							$code .= '\'' . preg_replace("#(\\\\|\'|\")#", '', $value) . '\'';
						}

					break;
				}

				$code .= ' ';
			}

			return '<?php ' . (($elseif) ? '} else ' : '') . 'if (' . trim($code) . ") { ?>";
		}

		protected function compile_multiline_comments($match) {
			return $template->compile_multiline_comments($match);
		}

		protected function compile_singleline_comments($match) {
			return $template->compile_singleline_comments($match);
		}

		public function compile($file) {
			// $abs_file = self::root . self::dir . $file;
			$abs_file = self::root . $file;
			
			$tpl = $uncompiled = @file_get_contents($abs_file);
			$tpl = preg_replace("#<\?(.*)\?>#", '', $tpl);
			$tpl = preg_replace_callback("#<!-- ([A-Z]+) (.*)? ?-->#U", array(self, 'compile_tags'), $tpl);
			$tpl = preg_replace_callback("#{([A-Za-z0-9_\-.]+)}#U", array(self, 'compile_vars'), $tpl);
			$tpl = preg_replace_callback("#\/\*(.*)\*\/#Umsi", array(self, 'compile_multiline_comments'), $tpl);
			$tpl = preg_replace_callback("^##(.*)^", array(self, 'compile_singleline_comments'), $tpl);

			if (eval(' ?>' . $tpl . '<?php ') === false) {
				self::error($file, $uncompiled);
			}
		}

		public function error($file, $tpl) {
			exit('<h1>Fehler im Template:</h1> <b>' . $file . '</b><hr /><pre>' . preg_replace("#&lt;!-- ([A-Z]+) (.*)? ?--&gt;#U", '<font color="red">$0</font>', htmlspecialchars($tpl)) . '</pre>');
		}
	}
?>