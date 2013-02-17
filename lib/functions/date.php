<?php
/**
 * IA FrameWork
 * @package: Classes & Object Oriented Programming
 * @subpackage: Date & Time Manipulation
 * @author: ItsAsh <ash at itsash dot co dot uk>
 */

final class PHPDateTime extends DateTime {

    // Public Methods
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   
    /**
     * Calculate time difference between two dates
     * ...
     */
   
    public static function TimeDifference($date1, $date2, $whole = false) {
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
   
    /**
     * Formatted time difference between two dates
     *
     * ...
     */
   
    public static function StringTimeDifference($date1, $date2, $whole = false) {
        $i = array();
        list($d, $h, $m, $s) = (array) self::TimeDifference($date1, $date2);
       
		if ($whole) {
			if ($d > 0)
				$i[] = sprintf('%d Days', $d);
			if ($h > 0)
				$i[] = sprintf('%d Hours', $h);
			if (($d == 0) && ($m > 0))
				$i[] = sprintf('%d Minutes', $m);
			if (($h == 0) && ($s > 0))
				$i[] = sprintf('%d Seconds', $s);
		} else {
			$i[] = sprintf('%d', $d);
		}
       
        return count($i) ? implode(' ', $i) : 'Gerade eben';
    }
   
    /**
     * Calculate the date next month
     *
     * ...
     */
   
    public static function DateNextMonth($now, $date = 0) {
        $mdate = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        list($y, $m, $d) = explode('-', (is_int($now) ? strftime('%F', $now) : $now));
       
        if ($date)
            $d = $date;
       
        if (++$m == 2)
            $d = (($y % 4) === 0) ? (($d <= 29) ? $d : 29) : (($d <= 28) ? $d : 28);
        else
            $d = ($d <= $mdate[$m]) ? $d : $mdate[$m];
       
        return strftime('%F', mktime(0, 0, 0, $m, $d, $y));
    }
   
}
?>