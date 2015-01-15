<?php

//Include Configuration
require_once (dirname (__FILE__) . '/../../../../wp-load.php');
require_once(dirname (__FILE__) . '/../classes/base.class.php');

global $dpProEventCalendar, $wpdb, $table_prefix;

if(!is_numeric($_GET['event_id']) || $_GET['event_id'] <= 0 || !is_numeric($_GET['date']) || $_GET['date'] <= 0) { 
	die(); 
}

$event_id = $_GET['event_id'];
$date = $_GET['date'];
$event_date = date('Y-m-d', $_GET['date']);
$dpProEventCalendar = new DpProEventCalendar( false );

$calendar_id = $dpProEventCalendar->getCalendarByEvent( $event_id );

if(!$calendar_id)
	die();

$dpProEventCalendar->setCalendar($calendar_id);

if(!$dpProEventCalendar->calendar_obj->ical_active) 
	die();
	
$limit = $dpProEventCalendar->calendar_obj->ical_limit;
if( !is_numeric($limit) || $limit <= 0 ) {
	$limit = '';	
}

$event = $dpProEventCalendar->getEventData( $event_id );
$event_date .= ' '.substr($event->date, 11);

//timezone
$tz = get_option('timezone_string'); // get current PHP timezone

function timezoneDoesDST($tzId, $time = "") {
    $tz = new DateTimeZone($tzId);
    $date = new DateTime($time != "" ? $time : "now",$tz);  
    $trans = $tz->getTransitions();
    foreach ($trans as $k => $t) 
      if ($t["ts"] > $date->format('U')) {
          return $trans[$k-1]['isdst'];    
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

		/* @var $event event */
		//date_default_timezone_set('UTC'); // set the PHP timezone to UTC, we already calculated event    
		if($event->all_day){
			$dateStart	= ';VALUE=DATE:'.date('Ymd',strtotime($event_date)); //all day
			$dateEnd	= ';VALUE=DATE:'.date('Ymd',strtotime($event_date) + 86400); //add one day
		} else {
			$dateStart	= ':'.date('Ymd\THis',strtotime($event_date));
			if($event->end_time_hh != "" && $event->end_time_mm != "") {
				$dateEnd = ':'.date('Ymd\THis',strtotime(substr($event_date, 0, 11). ' '. $event->end_time_hh . ':' . $event->end_time_mm .':00'));
			} else {
				$dateEnd = ':'.date('Ymd\THis',strtotime($event_date));
			}
			if(timezoneDoesDST($tz, substr($event_date, 0, 11))) {
				$dateStart = ':'.date('Ymd\THis', strtotime ( '-1 hour' , strtotime($event_date) )) ;
				if($event->end_time_hh != "" && $event->end_time_mm != "") {
					$dateEnd = ':'.date('Ymd\THis',strtotime( '-1 hour' , strtotime(substr($event_date, 0, 11). ' '. $event->end_time_hh . ':' . $event->end_time_mm .':00')));
				} else {
					$dateEnd = ':'.date('Ymd\THis',strtotime( '-1 hour' , strtotime($event_date)));
				}
			}
		}
		//echo "strtotime ".strtotime($event_date);
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

echo "
END:VCALENDAR";
date_default_timezone_set($tz); // set the PHP timezone back the way it was