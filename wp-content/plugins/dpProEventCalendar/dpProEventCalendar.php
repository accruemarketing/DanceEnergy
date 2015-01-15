<?php
/*
Plugin Name: DP Pro Event Calendar
Description: The Pro Event Calendar plugin adds a professional and sleek calendar to your posts or pages. 100% Responsive, also you can use it inside a widget.
Version: 2.2.5
Author: Diego Pereyra
Author URI: http://www.dpereyra.com/
Wordpress version supported: 3.0 and above
*/
@error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);

//on activation
//defined global variables and constants here
global $dpProEventCalendar, $dpProEventCalendar_cache, $table_prefix, $wpdb;
$dpProEventCalendar = get_option('dpProEventCalendar_options');
$dpProEventCalendar_cache = get_option( 'dpProEventCalendar_cache');
define('DP_PRO_EVENT_CALENDAR_TABLE_EVENTS','dpProEventCalendar_events'); //events TABLE NAME
define('DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS','dpProEventCalendar_calendars'); //calendar TABLE NAME
define('DP_PRO_EVENT_CALENDAR_TABLE_BOOKING','dpProEventCalendar_booking'); //booking TABLE NAME
define('DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES','dpProEventCalendar_special_dates'); //special dates TABLE NAME
define('DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR','dpProEventCalendar_special_dates_calendar'); //special dates TABLE NAME
define('DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR','dpProEventCalendar_subscribers_calendar'); //special dates TABLE NAME
define("DP_PRO_EVENT_CALENDAR_VER","2.2.5",false);//Current Version of this plugin
if ( ! defined( 'DP_PRO_EVENT_CALENDAR_PLUGIN_BASENAME' ) )
	define( 'DP_PRO_EVENT_CALENDAR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'DP_PRO_EVENT_CALENDAR_CSS_DIR' ) ){
	define( 'DP_PRO_EVENT_CALENDAR_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/' );
}
// Create Text Domain For Translations
load_plugin_textdomain('dpProEventCalendar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

function checkMU_install_dpProEventCalendar($network_wide) {
	global $wpdb;
	if ( $network_wide ) {
		$blog_list = get_blog_list( 0, 'all' );
		foreach ($blog_list as $blog) {
			switch_to_blog($blog['blog_id']);
			install_dpProEventCalendar();
		}
		switch_to_blog($wpdb->blogid);
	} else {
		install_dpProEventCalendar();
	}
}

function install_dpProEventCalendar() {
	global $wpdb, $table_prefix;
	$table_name_booking = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_BOOKING;
	$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
	$table_name_special_dates = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES;
	$table_name_special_dates_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR;
	$table_name_subscribers_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR;
	
	if($wpdb->get_var("show tables like '$table_name_booking'") != $table_name_booking) {
		$sql = "CREATE TABLE $table_name_booking (
					id int(11) NOT NULL AUTO_INCREMENT,
					id_calendar int(11) NOT NULL,
					id_event int(11) NOT NULL,
					booking_date datetime NOT NULL,
					event_date date NOT NULL,
					id_user int(11) NOT NULL,
					comment text NULL,
					UNIQUE KEY id(id)
				) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
		$rs = $wpdb->query($sql);
	}
	
	if($wpdb->get_var("show tables like '$table_name_calendars'") != $table_name_calendars) {
		$sql = "CREATE TABLE $table_name_calendars (
					id int(11) NOT NULL AUTO_INCREMENT,
					active tinyint(1) NOT NULL,
					title varchar(80) NOT NULL,
					description varchar(255) NOT NULL,
					width char(5) NOT NULL,
					width_unity char(2) NOT NULL DEFAULT 'px',
					default_date date NULL,
					date_range_start date NULL,
					date_range_end date NULL,
					ical_active tinyint(1) NOT NULL,
					ical_limit varchar(80) NOT NULL,
					rss_active tinyint(1) NOT NULL,
					rss_limit varchar(80) NOT NULL,
					link_post tinyint(1) NOT NULL,
					email_admin_new_event tinyint(1) NOT NULL,
					hide_old_dates TINYINT(1) NOT NULL DEFAULT 0,
					limit_time_start TINYINT(2) NOT NULL DEFAULT 0,
					limit_time_end TINYINT(2) NOT NULL DEFAULT 0,
					lang_txt_view_all_events VARCHAR(80) NOT NULL DEFAULT 'View all events',
					lang_txt_monthly VARCHAR(80) NOT NULL DEFAULT 'Monthly',
					lang_txt_daily VARCHAR(80) NOT NULL DEFAULT 'Daily',
					lang_txt_all_working_days VARCHAR(80) NOT NULL DEFAULT 'All working days',
					view VARCHAR(80) NOT NULL DEFAULT 'monthly',
					format_ampm TINYINT(1) NOT NULL DEFAULT 0,
					show_time TINYINT(1) NOT NULL DEFAULT 1,
					show_preview TINYINT(1) NOT NULL DEFAULT 0,
					show_references TINYINT(1) NOT NULL DEFAULT 1,
					show_author TINYINT(1) NOT NULL DEFAULT 0,
					show_search TINYINT(1) NOT NULL DEFAULT 0,
					show_category_filter TINYINT(1) NOT NULL DEFAULT 0,
					booking_enable TINYINT(1) NOT NULL DEFAULT 0,
					booking_comment TINYINT(1) NULL DEFAULT 0,
					booking_event_color VARCHAR(80) NOT NULL DEFAULT '#e14d43',
					lang_txt_book_event_select_date VARCHAR(80) NOT NULL DEFAULT 'Select Date:',
					lang_txt_book_event_pick_date VARCHAR(80) NOT NULL DEFAULT 'Click to book on this date.',
					lang_txt_book_event_already_booked VARCHAR(80) NOT NULL DEFAULT 'You have already booked this event date.',
					lang_txt_book_event_comment VARCHAR(80) NOT NULL DEFAULT 'Leave a comment (optional):',
					category_filter_include text NULL,
					lang_txt_book_event VARCHAR(80) NOT NULL DEFAULT 'Book Event',
					lang_txt_book_event_remove VARCHAR(80) NOT NULL DEFAULT 'Remove Booking',
					lang_txt_book_event_saved VARCHAR(80) NOT NULL DEFAULT 'Booking saved successfully.',
					lang_txt_book_event_removed VARCHAR(80) NOT NULL DEFAULT 'Booking removed successfully.',
					article_share TINYINT(1) NOT NULL DEFAULT 0,
					cache_active TINYINT(1) NOT NULL DEFAULT 0,
					allow_user_add_event TINYINT(1) NOT NULL DEFAULT 0,
					publish_new_event TINYINT(1) NOT NULL DEFAULT 0,
					form_show_description TINYINT(1) NOT NULL DEFAULT 1,
					form_show_category TINYINT(1) NOT NULL DEFAULT 1,
					form_show_hide_time TINYINT(1) NOT NULL DEFAULT 1,
					form_show_frequency TINYINT(1) NOT NULL DEFAULT 1,
					form_show_all_day TINYINT(1) NOT NULL DEFAULT 1,
					form_show_image TINYINT(1) NOT NULL DEFAULT 1,
					form_show_link TINYINT(1) NOT NULL DEFAULT 1,
					form_show_share TINYINT(1) NOT NULL DEFAULT 1,
					form_show_location TINYINT(1) NOT NULL DEFAULT 1,
					form_show_phone TINYINT(1) NOT NULL DEFAULT 1,
					form_show_map TINYINT(1) NOT NULL DEFAULT 1,
					show_x TINYINT(1) NOT NULL DEFAULT 1,
					allow_user_edit_event TINYINT(1) NOT NULL DEFAULT 0,
					allow_user_remove_event TINYINT(1) NOT NULL DEFAULT 0,
					show_view_buttons TINYINT(1) NOT NULL DEFAULT 1,
					assign_events_admin INT(11) NOT NULL DEFAULT 0,
					first_day tinyint(1) NOT NULL DEFAULT '0',
					lang_txt_no_events_found varchar(80) NOT NULL,
					lang_txt_all_day varchar(80) NOT NULL,
					lang_txt_references varchar(80) NOT NULL,
					lang_txt_search varchar(80) NOT NULL,
					lang_txt_all_categories varchar(80) NOT NULL DEFAULT 'All Categories',
					lang_txt_results_for varchar(80) NOT NULL,
					lang_prev_month varchar(80) NOT NULL,
					lang_next_month varchar(80) NOT NULL,
					lang_day_sunday varchar(80) NOT NULL,
					lang_day_monday varchar(80) NOT NULL,
					lang_day_tuesday varchar(80) NOT NULL,
					lang_day_wednesday varchar(80) NOT NULL,
					lang_day_thursday varchar(80) NOT NULL,
					lang_day_friday varchar(80) NOT NULL,
					lang_day_saturday varchar(80) NOT NULL,
					lang_month_january varchar(80) NOT NULL,
					lang_month_february varchar(80) NOT NULL,
					lang_month_march varchar(80) NOT NULL,
					lang_month_april varchar(80) NOT NULL,
					lang_month_may varchar(80) NOT NULL,
					lang_month_june varchar(80) NOT NULL,
					lang_month_july varchar(80) NOT NULL,
					lang_month_august varchar(80) NOT NULL,
					lang_month_september varchar(80) NOT NULL,
					lang_month_october varchar(80) NOT NULL,
					lang_month_november varchar(80) NOT NULL,
					lang_month_december varchar(80) NOT NULL,
					lang_next_day VARCHAR(80) NOT NULL DEFAULT 'Next Day',
					lang_prev_day VARCHAR(80) NOT NULL DEFAULT 'Prev Day',
					current_date_color VARCHAR(10) NOT NULL DEFAULT '#C4C5D1',
					lang_txt_current_date VARCHAR(80) NOT NULL DEFAULT 'Current Date',
					lang_txt_sending VARCHAR(80) NOT NULL DEFAULT 'Sending...',
					lang_txt_send VARCHAR(80) NOT NULL DEFAULT 'Send',
					lang_txt_add_event VARCHAR(80) NOT NULL DEFAULT '+ Add Event',
					lang_txt_edit_event VARCHAR(80) NOT NULL DEFAULT 'Edit Event',
					lang_txt_remove_event VARCHAR(80) NOT NULL DEFAULT 'Remove Event',
					lang_txt_remove_event_confirm VARCHAR(80) NOT NULL DEFAULT 'Are you sure that you want to delete this event?',
					lang_txt_cancel VARCHAR(80) NOT NULL DEFAULT 'Cancel',
					subscribe_active tinyint(1) NOT NULL DEFAULT 0,
					mailchimp_api varchar(80) NULL,
					mailchimp_list varchar(80) NULL,
					lang_txt_category VARCHAR(80) NOT NULL DEFAULT 'Category',
					lang_txt_subscribe VARCHAR(80) NOT NULL DEFAULT 'Subscribe',
					lang_txt_subscribe_subtitle VARCHAR(255) NOT NULL DEFAULT 'Receive new events notifications in your email.',
					lang_txt_subscribe_thanks VARCHAR(80) NOT NULL DEFAULT 'Thanks for subscribing.',
					lang_txt_your_name VARCHAR(80) NOT NULL DEFAULT 'Your Name',
					lang_txt_your_email VARCHAR(80) NOT NULL DEFAULT 'Your Email',
					lang_txt_fields_required VARCHAR(80) NOT NULL DEFAULT 'All fields are required.',
					lang_txt_invalid_email VARCHAR(80) NOT NULL DEFAULT 'The Email is invalid.',
					lang_txt_logged_to_submit VARCHAR(80) NOT NULL DEFAULT 'You must be logged in to submit an event.',
					lang_txt_thanks_for_submit VARCHAR(80) NOT NULL DEFAULT 'Thanks for submit the event, it will be reviewed in shortly.',
					lang_txt_event_title VARCHAR(80) NOT NULL DEFAULT 'Title',
					lang_txt_event_description VARCHAR(80) NOT NULL DEFAULT 'Event Description',
					lang_txt_event_link VARCHAR(80) NOT NULL DEFAULT 'Link (optional)',
					lang_txt_event_share VARCHAR(80) NOT NULL DEFAULT 'Text to share in social networks (optional)',
					lang_txt_event_image VARCHAR(80) NOT NULL DEFAULT 'Upload an Image (optional)',
					lang_txt_event_location VARCHAR(80) NOT NULL DEFAULT 'Location (optional)',
					lang_txt_event_phone VARCHAR(80) NOT NULL DEFAULT 'Phone (optional)',
					lang_txt_event_googlemap VARCHAR(80) NOT NULL DEFAULT 'Google Map (optional)',
					lang_txt_event_start_date VARCHAR(80) NOT NULL DEFAULT 'Start Date',
					lang_txt_event_all_day VARCHAR(80) NOT NULL DEFAULT 'Set if the event is all the day.',
					lang_txt_event_start_time VARCHAR(80) NOT NULL DEFAULT 'Start Time',
					lang_txt_event_hide_time VARCHAR(80) NOT NULL DEFAULT 'Hide Time',
					lang_txt_event_end_time VARCHAR(80) NOT NULL DEFAULT 'End Time',
					lang_txt_event_frequency VARCHAR(80) NOT NULL DEFAULT 'Frequency',
					lang_txt_event_none VARCHAR(80) NOT NULL DEFAULT 'None',
					lang_txt_event_daily VARCHAR(80) NOT NULL DEFAULT 'Daily',
					lang_txt_event_weekly VARCHAR(80) NOT NULL DEFAULT 'Weekly',
					lang_txt_event_monthly VARCHAR(80) NOT NULL DEFAULT 'Monthly',
					lang_txt_event_yearly VARCHAR(80) NOT NULL DEFAULT 'Yearly',
					lang_txt_event_end_date VARCHAR(80) NOT NULL DEFAULT 'End Date',
					lang_txt_event_submit VARCHAR(80) NOT NULL DEFAULT 'Submit for Review',
					lang_txt_yes VARCHAR(80) NOT NULL DEFAULT 'Yes',
					lang_txt_no VARCHAR(80) NOT NULL DEFAULT 'No',
					lang_txt_by VARCHAR(80) NOT NULL DEFAULT 'By',
					skin varchar(80) NOT NULL,
					UNIQUE KEY id(id)
				) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
		$rs = $wpdb->query($sql);
	}
	
	if($wpdb->get_var("show tables like '$table_name_special_dates'") != $table_name_special_dates) {
		$sql = "CREATE TABLE $table_name_special_dates (
					id int(11) NOT NULL AUTO_INCREMENT,
					title varchar(80) NOT NULL,
					color varchar(10) NOT NULL,
					UNIQUE KEY id(id)
				) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
		$rs = $wpdb->query($sql);
	}
	
	if($wpdb->get_var("show tables like '$table_name_special_dates_calendar'") != $table_name_special_dates_calendar) {
		$sql = "CREATE TABLE $table_name_special_dates_calendar (
					special_date int(11) NOT NULL,
					calendar int(11) NOT NULL,
					date date NOT NULL,
					PRIMARY KEY (special_date,calendar,date)
				) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
		$rs = $wpdb->query($sql);
	}
	
	if($wpdb->get_var("show tables like '$table_name_subscribers_calendar'") != $table_name_subscribers_calendar) {
		$sql = "CREATE TABLE $table_name_subscribers_calendar (
					id int(11) NOT NULL AUTO_INCREMENT,
					calendar int(11) NOT NULL,
					name varchar(80) NOT NULL,
					email varchar(80) NOT NULL,
					subscription_date datetime NOT NULL,
					UNIQUE KEY id(id)
				) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
		$rs = $wpdb->query($sql);
	}

   $default_events = array();
   $default_events = array(
   						   'version' 				=> 		DP_PRO_EVENT_CALENDAR_VER,
						   'first_day' 				=>		true,
						   'current_date_color' 	=> 		true,
						   'charset'				=> 		true,
						   'skin'					=>		true,
						   'map'					=>		true,
						   'show_x'					=>		true,
						   'show_preview'			=>		true,
						   'end_time'				=>		true,
						   'ical'					=>		true,
						   'rss'					=>		true,
						   'hide_time'				=>		true,
						   'location'				=> 		true,
						   'user_roles'				=>		array(),
						   'post_type'				=> 		true,
						   'lang_day'				=> 		true,
						   'view'					=> 		true,
						   'view_all_events'		=>		true,
						   'lang_monthly_daily'		=>		true,
						   'show_view_buttons'		=>		true,
						   'allow_user_add_event'	=>		true,
						   'lang_user_submit'		=>		true,
						   'show_references'		=>		true,
						   'lang_yes_no'			=>		true,
						   'link_post'				=>		true,
						   'category_filter'		=>		true,
						   'email_admin_new_event'	=>		true,
						   'publish_new_event'		=>		true,
						   'lang_txt_event_image'	=> 		true,
						   'subscribers_option'		=>		true,
						   'article_share'			=>		true,
						   'category_filter_include'=>		true,
						   'assign_events_admin'	=>		true,
						   'all_working_days'		=>		true,
						   'hide_old_dates'			=>		true,
						   'limit_time_start'		=>		true,
						   'lang_txt_event_phone'	=>		true,
						   'form_show_fields'		=>		true,
						   'allow_users_edit_event'	=>		true,
						   'show_author'			=>		true,
						   'remove_events'			=>		true,
						   'cache_active'			=>		true,
						   'booking'				=>		true,
						   'booking_lang'			=>		true
			              );
   
	$dpProEventCalendar = get_option('dpProEventCalendar_options');
	
	if(!$dpProEventCalendar) {
	 $dpProEventCalendar = array();
	}
	
	foreach($default_events as $key=>$value) {
	  if(!isset($dpProEventCalendar[$key])) {
		 $dpProEventCalendar[$key] = $value;
	  }
	}
	
	delete_option('dpProEventCalendar_options');	  
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}
register_activation_hook( __FILE__, 'checkMU_install_dpProEventCalendar' );

/* Uninstall */
function checkMU_uninstall_dpProEventCalendar($network_wide) {
	global $wpdb;
	if ( $network_wide ) {
		$blog_list = get_blog_list( 0, 'all' );
		foreach ($blog_list as $blog) {
			switch_to_blog($blog['blog_id']);
			uninstall_dpProEventCalendar();
		}
		switch_to_blog($wpdb->blogid);
	} else {
		uninstall_dpProEventCalendar();
	}
}

function uninstall_dpProEventCalendar() {
	global $wpdb, $table_prefix;
	delete_option('dpProEventCalendar_options'); 
	
	$events_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
	$sql = "DROP TABLE $events_table;";
	$wpdb->query($sql);
	
	$calendars_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
	$sql = "DROP TABLE $calendars_table;";
	$wpdb->query($sql);
	
	$booking_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_BOOKING;
	$sql = "DROP TABLE $booking_table;";
	$wpdb->query($sql);
	
	$special_dates_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES;
	$sql = "DROP TABLE $special_dates_table;";
	$wpdb->query($sql);
	
	$special_dates_calendar_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR;
	$sql = "DROP TABLE $special_dates_calendar_table;";
	$wpdb->query($sql);
	
	$subscribers_calendar_table = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR;
	$sql = "DROP TABLE $subscribers_calendar_table;";
	$wpdb->query($sql);
}
register_uninstall_hook( __FILE__, 'checkMU_uninstall_dpProEventCalendar' );

/* Add new Blog */

add_action( 'wpmu_new_blog', 'newBlog_dpProEventCalendar', 10, 6); 		
 
function newBlog_dpProEventCalendar($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;
 
	if (is_plugin_active_for_network('dpProEventCalendar/dpProEventCalendar.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		install_dpProEventCalendar();
		switch_to_blog($old_blog);
	}
}

//require_once (dirname (__FILE__) . '/update-notifier.php');
require_once (dirname (__FILE__) . '/functions.php');
require_once (dirname (__FILE__) . '/includes/core.php');
require_once (dirname (__FILE__) . '/settings/settings.php');
require_once (dirname (__FILE__) . '/mailchimp/miniMCAPI.class.php');


/*******************/
/* UPDATES 
/*******************/

$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
$table_name_events = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
$table_name_booking = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_BOOKING;
$table_name_special_dates = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES;
$table_name_special_dates_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR;
$table_name_subscribers_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR;

if(!isset($dpProEventCalendar['booking_lang'])) {
	$dpProEventCalendar['booking_lang'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (booking_enable TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (booking_comment TINYINT(1) NULL DEFAULT 0);";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (booking_event_color VARCHAR(80) NOT NULL DEFAULT '#e14d43');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_select_date VARCHAR(80) NOT NULL DEFAULT 'Select Date:');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_pick_date VARCHAR(80) NOT NULL DEFAULT 'Click to book on this date.');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_already_booked VARCHAR(80) NOT NULL DEFAULT 'You have already booked this event date.');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_comment VARCHAR(80) NOT NULL DEFAULT 'Leave a comment (optional):');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['booking'])) {
	$dpProEventCalendar['booking'] = true;
	
	$sql = "CREATE TABLE $table_name_booking (
				id int(11) NOT NULL AUTO_INCREMENT,
				id_calendar int(11) NOT NULL,
				id_event int(11) NOT NULL,
				booking_date datetime NOT NULL,
				event_date date NOT NULL,
				id_user int(11) NOT NULL,
				comment text NULL,
				UNIQUE KEY id(id)
			) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
	$rs = $wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event VARCHAR(80) NOT NULL DEFAULT 'Book Event');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_remove VARCHAR(80) NOT NULL DEFAULT 'Remove Booking');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_saved VARCHAR(80) NOT NULL DEFAULT 'Booking saved successfully.');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_book_event_removed VARCHAR(80) NOT NULL DEFAULT 'Booking removed successfully.');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['cache_active'])) {
	$dpProEventCalendar['cache_active'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (cache_active TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['remove_events'])) {
	$dpProEventCalendar['remove_events'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_remove_event VARCHAR(80) NOT NULL DEFAULT 'Remove Event');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_remove_event_confirm VARCHAR(80) NOT NULL DEFAULT 'Are you sure that you want to delete this event?');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (allow_user_remove_event TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['show_author'])) {
	$dpProEventCalendar['show_author'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_author TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_by VARCHAR(80) NOT NULL DEFAULT 'By');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['allow_users_edit_event'])) {
	$dpProEventCalendar['allow_users_edit_event'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_send VARCHAR(80) NOT NULL DEFAULT 'Send');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_edit_event VARCHAR(80) NOT NULL DEFAULT 'Edit Event');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (allow_user_edit_event TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['form_show_fields'])) {
	$dpProEventCalendar['form_show_fields'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_description TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_category TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_hide_time TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_frequency TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_all_day TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_image TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_link TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_share TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_location TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_phone TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (form_show_map TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);

	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}
					
if(!isset($dpProEventCalendar['lang_txt_event_phone'])) {
	$dpProEventCalendar['lang_txt_event_phone'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_phone VARCHAR(80) NOT NULL DEFAULT 'Phone (optional)');";
	$wpdb->query($sql);

	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['limit_time_start'])) {
	$dpProEventCalendar['limit_time_start'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (limit_time_start TINYINT(2) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (limit_time_end TINYINT(2) NOT NULL DEFAULT 23);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['hide_old_dates'])) {
	$dpProEventCalendar['hide_old_dates'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (hide_old_dates TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['all_working_days'])) {
	$dpProEventCalendar['all_working_days'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_all_working_days VARCHAR(80) NOT NULL DEFAULT 'All working days');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['assign_events_admin'])) {
	$dpProEventCalendar['assign_events_admin'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (assign_events_admin INT(11) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['category_filter_include'])) {
	$dpProEventCalendar['category_filter_include'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (category_filter_include text NULL);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['article_share'])) {
	$dpProEventCalendar['article_share'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (article_share TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['subscribers_option'])) {
	$dpProEventCalendar['subscribers_option'] = true;
	
	$sql = "CREATE TABLE $table_name_subscribers_calendar (
				id int(11) NOT NULL AUTO_INCREMENT,
				calendar int(11) NOT NULL,
				name varchar(80) NOT NULL,
				email varchar(80) NOT NULL,
				subscription_date datetime NOT NULL,
				UNIQUE KEY id(id)
			) DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
	$wpdb->query($sql);
		
	$sql = "ALTER TABLE $table_name_calendars ADD (subscribe_active tinyint(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
					
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_subscribe VARCHAR(80) NOT NULL DEFAULT 'Subscribe');";
	$wpdb->query($sql);

	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_subscribe_subtitle VARCHAR(255) NOT NULL DEFAULT 'Receive new events notifications in your email.');";
	$wpdb->query($sql);

	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_your_name VARCHAR(80) NOT NULL DEFAULT 'Your Name');";
	$wpdb->query($sql);

	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_your_email VARCHAR(80) NOT NULL DEFAULT 'Your Email');";
	$wpdb->query($sql);

	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_fields_required VARCHAR(80) NOT NULL DEFAULT 'All fields are required.');";
	$wpdb->query($sql);

	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_invalid_email VARCHAR(80) NOT NULL DEFAULT 'The Email is invalid.');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_subscribe_thanks VARCHAR(80) NOT NULL DEFAULT 'Thanks for subscribing.');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_category VARCHAR(80) NOT NULL DEFAULT 'Category');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (mailchimp_api varchar(80) NULL);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (mailchimp_list varchar(80) NULL);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['lang_txt_event_image'])) {
	$dpProEventCalendar['lang_txt_event_image'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_image VARCHAR(80) NOT NULL DEFAULT 'Upload an Image (optional)');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['publish_new_event'])) {

	$dpProEventCalendar['publish_new_event'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (publish_new_event tinyint(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['email_admin_new_event'])) {

	$dpProEventCalendar['email_admin_new_event'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (email_admin_new_event tinyint(1) NOT NULL);";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['category_filter'])) {
	$dpProEventCalendar['category_filter'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_category_filter TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_all_categories varchar(80) NOT NULL DEFAULT 'All Categories');";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['link_post'])) {
	$dpProEventCalendar['link_post'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (link_post tinyint(1) NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['lang_yes_no'])) {
	$dpProEventCalendar['lang_yes_no'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_yes VARCHAR(80) NOT NULL DEFAULT 'Yes');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_no VARCHAR(80) NOT NULL DEFAULT 'No');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['show_references'])) {
	$dpProEventCalendar['show_references'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_references TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['lang_user_submit'])) {
	$dpProEventCalendar['lang_user_submit'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_sending VARCHAR(80) NOT NULL DEFAULT 'Sending...');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_add_event VARCHAR(80) NOT NULL DEFAULT '+ Add Event');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_cancel VARCHAR(80) NOT NULL DEFAULT 'Cancel');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_logged_to_submit VARCHAR(80) NOT NULL DEFAULT 'You must be logged in to submit an event.');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_thanks_for_submit VARCHAR(80) NOT NULL DEFAULT 'Thanks for submit the event, it will be reviewed in shortly.');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_title VARCHAR(80) NOT NULL DEFAULT 'Title');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_description VARCHAR(80) NOT NULL DEFAULT 'Event Description');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_link VARCHAR(80) NOT NULL DEFAULT 'Link (optional)');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_share VARCHAR(80) NOT NULL DEFAULT 'Text to share in social networks (optional)');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_location VARCHAR(80) NOT NULL DEFAULT 'Location (optional)');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_googlemap VARCHAR(80) NOT NULL DEFAULT 'Google Map (optional)');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_start_date VARCHAR(80) NOT NULL DEFAULT 'Start Date');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_all_day VARCHAR(80) NOT NULL DEFAULT 'Set if the event is all the day.');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_start_time VARCHAR(80) NOT NULL DEFAULT 'Start Time');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_hide_time VARCHAR(80) NOT NULL DEFAULT 'Hide Time');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_end_time VARCHAR(80) NOT NULL DEFAULT 'End Time');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_frequency VARCHAR(80) NOT NULL DEFAULT 'Frequency');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_none VARCHAR(80) NOT NULL DEFAULT 'None');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_daily VARCHAR(80) NOT NULL DEFAULT 'Daily');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_weekly VARCHAR(80) NOT NULL DEFAULT 'Weekly');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_monthly VARCHAR(80) NOT NULL DEFAULT 'Monthly');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_yearly VARCHAR(80) NOT NULL DEFAULT 'Yearly');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_end_date VARCHAR(80) NOT NULL DEFAULT 'End Date');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_event_submit VARCHAR(80) NOT NULL DEFAULT 'Submit for Review');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['allow_user_add_event'])) {
	$dpProEventCalendar['allow_user_add_event'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (allow_user_add_event TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['show_view_buttons'])) {
	$dpProEventCalendar['show_view_buttons'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_view_buttons TINYINT(1) NOT NULL DEFAULT 1);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['lang_monthly_daily'])) {
	$dpProEventCalendar['lang_monthly_daily'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_monthly VARCHAR(80) NOT NULL DEFAULT 'Monthly');";
	$wpdb->query($sql);
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_daily VARCHAR(80) NOT NULL DEFAULT 'Daily');";
	$wpdb->query($sql);
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['view_all_events'])) {
	$dpProEventCalendar['view_all_events'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_view_all_events VARCHAR(80) NOT NULL DEFAULT 'View all events');";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['view'])) {
	$dpProEventCalendar['view'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (view VARCHAR(80) NOT NULL DEFAULT 'monthly');";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['lang_day'])) {
	$dpProEventCalendar['lang_day'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_next_day VARCHAR(80) NOT NULL DEFAULT 'Next Day');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_prev_day VARCHAR(80) NOT NULL DEFAULT 'Prev Day');";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['post_type'])) {
	require_once (dirname (__FILE__) . '/classes/base.class.php');
	
	$dpProEventCalendar['post_type'] = true;
	$pec = new DpProEventCalendar();
	
	$querystr = "
	SELECT *
	FROM ".$pec->table_events;
	
	$events_list = $wpdb->get_results($querystr, OBJECT);
	if(!empty($events_list)) {
		foreach($events_list as $event) {
			// Create post object
			$old_event = array(
			  'post_title'    => $event->title,
			  'post_content'  => $event->description,
			  'post_status'   => 'publish',
			  'post_type'	  => 'pec-events'
			);
			
			// Insert the post into the database
			$post_id = wp_insert_post( $old_event );
			update_post_meta($post_id, 'pec_id_calendar', $event->id_calendar);
			update_post_meta($post_id, 'pec_date', $event->date);
			update_post_meta($post_id, 'pec_all_day', $event->all_day);
			update_post_meta($post_id, 'pec_recurring_frecuency', $event->recurring_frecuency);
			update_post_meta($post_id, 'pec_end_date', $event->end_date);
			update_post_meta($post_id, 'pec_link', $event->link);
			update_post_meta($post_id, 'pec_share', $event->share);
			update_post_meta($post_id, 'pec_map', $event->map);
			update_post_meta($post_id, 'pec_end_time_hh', $event->end_time_hh);
			update_post_meta($post_id, 'pec_end_time_mm', $event->end_time_mm);
			update_post_meta($post_id, 'pec_hide_time', $event->hide_time);
			update_post_meta($post_id, 'pec_location', $event->location);
		}
	}
		
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['user_roles'])) {
	$dpProEventCalendar['user_roles'] = array();
	
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['location'])) {
	$dpProEventCalendar['location'] = true;
	
	$sql = "ALTER TABLE $table_name_events ADD (location text NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['hide_time'])) {
	$dpProEventCalendar['hide_time'] = true;
	
	$sql = "ALTER TABLE $table_name_events ADD (hide_time tinyint(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['rss'])) {
	$dpProEventCalendar['rss'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (rss_active tinyint(1) NOT NULL, rss_limit varchar(80) NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['ical'])) {
	$dpProEventCalendar['ical'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (ical_active tinyint(1) NOT NULL, ical_limit varchar(80) NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['end_time'])) {
	$dpProEventCalendar['end_time'] = true;
	
	$sql = "ALTER TABLE $table_name_events ADD (end_time_hh tinyint(2) NULL, end_time_mm tinyint(2) NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['show_preview'])) {
	$dpProEventCalendar['show_preview'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_preview TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['show_x'])) {
	$dpProEventCalendar['show_x'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (show_x TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['map'])) {
	$dpProEventCalendar['map'] = true;
	
	$sql = "ALTER TABLE $table_name_events ADD (map text NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['skin'])) {
	$dpProEventCalendar['skin'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (skin varchar(80) NOT NULL);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['charset'])) {
	$dpProEventCalendar['charset'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_events CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_special_dates CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_special_dates_calendar CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['first_day'])) {
	$dpProEventCalendar['first_day'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (first_day TINYINT(1) NOT NULL DEFAULT 0);";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

if(!isset($dpProEventCalendar['current_date_color'])) {
	$dpProEventCalendar['current_date_color'] = true;
	
	$sql = "ALTER TABLE $table_name_calendars ADD (current_date_color VARCHAR(10) NOT NULL DEFAULT '#C4C5D1');";
	$wpdb->query($sql);
	$sql = "ALTER TABLE $table_name_calendars ADD (lang_txt_current_date VARCHAR(80) NOT NULL DEFAULT 'Current Date');";
	$wpdb->query($sql);
	update_option('dpProEventCalendar_options',$dpProEventCalendar);
}

?>