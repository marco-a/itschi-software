<?php
	/**
	*
	* @package com.Itschi.base.functions.date
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib\functions;

	class date extends \functions {
		public static function timeDifference($date1, $date2, $whole = false) {
			$date1 = is_int($date1) ? $date1 : strtotime($date1);
			$date2 = is_int($date2) ? $date2 : strtotime($date2);

			if (($date1 !== false) && ($date2 !== false)) {
				if ($date2 >= $date1) {
					$diff = ($date2 - $date1);
				   
					if ($days = intval((floor($diff / 86400))))
						$diff %= 86400;
					if ($hours = intval((floor($diff / 3600))))
						$diff %= 3600;
					if ($minutes = intval((floor($diff / 60))))
						$diff %= 60;
				   
					if ($whole) {
						return array($days, $hours, $minutes, intval($diff));
					} else {
						return $days;
					}
				}
			}
		   
			return false;
		}
	   
		public static function strTimeDifference($date1, $date2) {
			$i = array();
			list($d, $h, $m, $s) = (array)self::timeDifference($date1, $date2);
			$i[] = sprintf('%d', $d);
			
			return count($i) ? implode(' ', $i) : 'Gerade eben';
		}
	}
?>