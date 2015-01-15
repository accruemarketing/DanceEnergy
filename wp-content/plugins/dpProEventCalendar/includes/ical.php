<?php

//Include Configuration
require_once (dirname (__FILE__) . '/../../../../wp-load.php');
require_once(dirname (__FILE__) . '/../classes/base.class.php');

global $dpProEventCalendar, $wpdb, $table_prefix;

if(!is_numeric($_GET['calendar_id']) || $_GET['calendar_id'] <= 0) { 
	die(); 
}

$calendar_id = $_GET['calendar_id'];

$dpProEventCalendar = new DpProEventCalendar( false, $calendar_id );

if(!$dpProEventCalendar->calendar_obj->ical_active) 
	die();
	
$limit = $dpProEventCalendar->calendar_obj->ical_limit;
if( !is_numeric($limit) || $limit <= 0 ) {
	$limit = 99;	
}

$cal_events = $dpProEventCalendar->upcomingCalendarLayout( true, $limit );

//timezone
$tz = get_option('timezone_string'); // get current PHP timezone
if($tz == "") {
	$tz = date_default_timezone_get();	
}
function timezoneDoesDST($tzId, $time = "") {
	if(class_exists('DateTimeZone') && $tzId != "") {
		$tz = new DateTimeZone($tzId);
		$date = new DateTime($time != "" ? $time : "now",$tz);  
		$trans = $tz->getTransitions();
		foreach ($trans as $k => $t) 
		  if ($t["ts"] > $date->format('U')) {
			  return $trans[$k-1]['isdst'];    
		}
	} else {
		return false;	
	}
}

date_default_timezone_set( get_option('timezone_string')); // set the PHP timezone to match WordPress
//send headers
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="events.ics"');
		
$blog_desc = ent2ncr(convert_chars(strip_tags(get_bloginfo()))) . " - " . __('Calendar','dbem');


echo "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//DP Pro Event Calendar//".DP_PRO_EVENT_CALENDAR_VER."//EN";

$processed = array();

if(is_array($cal_events)) {
	foreach ( $cal_events as $key => $event ) {
		/* @var $event event */
		//date_default_timezone_set('UTC'); // set the PHP timezone to UTC, we already calculated event    
		
		if($event->end_date != "" && $event->end_date != "0000-00-00") {
			$endDate = $event->end_date;
		} else {
			$endDate = $event->date;
		}
		
		if($event->recurring_frecuency == 1 && in_array($event->id, $processed)) {
			continue;
		}
		
		$processed[] = $event->id;
		
		if($event->all_day){
			$dateStart	= ';VALUE=DATE:'.date('Ymd',strtotime($event->date)); //all day
			$dateEnd	= ';VALUE=DATE:'.date('Ymd',strtotime($endDate) + 86400); //add one day
		} else {
			$dateStart	= ':'.date('Ymd\THis',strtotime($event->date));
			if($event->end_time_hh != "" && $event->end_time_mm != "") {
				$dateEnd = ':'.date('Ymd\THis',strtotime(substr($endDate, 0, 11). ' '. $event->end_time_hh . ':' . $event->end_time_mm .':00'));
			} else {
				$dateEnd = ':'.date('Ymd\THis',strtotime(substr($endDate, 0, 11). ' 23:59:00'));
			}
			if(timezoneDoesDST($tz, substr($event_date, 0, 11))) {
				$dateStart = ':'.date('Ymd\THis', strtotime ( '-1 hour' , strtotime($event->date) )) ;
				if($event->end_time_hh != "" && $event->end_time_mm != "") {
					$dateEnd = ':'.date('Ymd\THis',strtotime( '-1 hour' , strtotime(substr($endDate, 0, 11). ' '. $event->end_time_hh . ':' . $event->end_time_mm .':00')));
				} else {
					$dateEnd = ':'.date('Ymd\THis',strtotime( '-1 hour' , strtotime(substr($endDate, 0, 11). ' 23:59:00')));
				}
			}
		}
		
		
		date_default_timezone_set( get_option('timezone_string')); // set the PHP timezone to match WordPress
		
		//formats
		$summary = $event->title;
		$summary = str_replace("\n", '', $summary);
		$summary = str_replace("\r", '', $summary);
		$summary = str_replace("\\","\\\\",strip_tags(nl2br($summary)));
		$summary = str_replace(';','\;',$summary);
		$summary = str_replace(',','\,',$summary);
		
		$description = $event->description;
		$description = str_replace("\n", '', $description);
		$description = str_replace("\r", '', $description);
		$description = str_replace("\\","\\\\",strip_tags(nl2br($description)));
		$description = str_replace(';','\;',$description);
		$description = str_replace(',','\,',$description);
		
		$location = $event->location;
		$location = str_replace("\n", '', $location);
		$location = str_replace("\r", '', $location);
		$location = str_replace("\\","\\\\",strip_tags(nl2br($location)));
		$location = str_replace(';','\;',$location);
		$location = str_replace(',','\,',$location);
		
		$link = $event->link;
		
		$UID = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,
			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
echo "
BEGIN:VEVENT
UID:{$UID}
DTSTART;TZID={$tz}{$dateStart}
DTEND;TZID={$tz}{$dateEnd}
DTSTAMP{$dateStart}
SUMMARY:{$summary}
DESCRIPTION:{$description}
LOCATION:{$location}
URL:{$link}
END:VEVENT";
	}
}
echo "
END:VCALENDAR";
date_default_timezone_set($tz); // set the PHP timezone back the way it was