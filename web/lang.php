<?php
class lang{
	private static $t = array(
		'live_header' => array(
			'no' => 'Direkte nå: ',
			'en' => 'Live now: ',
		),
		'upcoming_header' => array(
			'no' => 'Snart: ',
			'en' => 'Upcoming: ',
		),
		'video_header' => array(
			'no' => '',
			'en' => '',
		),
		'other_live' => array(
			'no' => 'Flere direktesendte:',
			'en' => 'Also live:',
		),
		'other_upcoming' => array(
			'no' => 'Snart live',
			'en' => 'Upcoming',
		),
		'other_video' => array(
			'no' => 'Tidligere',
			'en' => 'Previously',
		),
		'no_videos' => array(
			'no' => '',
			'en' => '',
		),
		'other_live' => array(
			'no' => '',
			'en' => '',
		),
		'other_live' => array(
			'no' => '',
			'en' => '',
		),
	);
	
	static function g($string){
		return self::$t[$string][LANGUAGE];
	}
}
?>