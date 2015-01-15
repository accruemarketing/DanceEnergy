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

if(!$dpProEventCalendar->calendar_obj->rss_active) 
	die();
	
$limit = $dpProEventCalendar->calendar_obj->rss_limit;
if( !is_numeric($limit) || $limit <= 0 ) {
	$limit = 99;	
}

$cal_events = $dpProEventCalendar->upcomingCalendarLayout( true, $limit );
$blog_desc = ent2ncr(convert_chars(strip_tags(get_bloginfo()))) . " - " . __('Calendar','dbem');

$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>'.$blog_desc.'</title>
<link>'.home_url().'</link>
<atom:link type="application/rss+xml" href="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'" rel="self"/>
<description>'.$blog_desc.'</description>
<language>en-us</language>
<ttl>40</ttl>';

if(is_array($cal_events)) {
	foreach ( $cal_events as $event ) {
		
		$rssfeed .= '
		<item>
		<title>' . $event->title . '</title>
		<description><![CDATA[' . $event->description . ']]></description>
		<link>'.home_url().'</link>
		<pubDate>' . date("D, d M Y H:i:s O", strtotime($event->date)) . '</pubDate>
		</item>';
	}
}
$rssfeed .= '
</channel>
</rss>';

//date_default_timezone_set($tz); // set the PHP timezone back the way it was
header("Content-Type: application/rss+xml; charset=UTF-8");
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo $rssfeed;