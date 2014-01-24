<?php defined('AM_EXEC') or die('Restricted Access');
class AM_Date{

	public static function get_months(){
		return array(
			'1' => 'January',
			'2' => 'Febuary',
			'3' => 'March',
			'4' => 'April',
			'5' => 'May',
			'6' => 'June',
			'7' => 'July',
			'8' => 'August',
			'9' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December'
		);
	}

	public static function get_short_months(){
		return array(
			'1' => 'Jan',
			'2' => 'Feb',
			'3' => 'Mar',
			'4' => 'Apr',
			'5' => 'May',
			'6' => 'Jun',
			'7' => 'Jul',
			'8' => 'Aug',
			'9' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec'
		);
	}
}
