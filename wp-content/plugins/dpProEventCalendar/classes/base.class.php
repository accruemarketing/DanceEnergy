<?php
/*
 * DP Pro Event Calendar
 *
 * Copyright 2012, Diego Pereyra
 *
 * @Web: http://www.dpereyra.com
 * @Email: info@dpereyra.com
 *
 * Base Class
 */

require_once('dates.class.php');

class DpProEventCalendar {
	
	var $nonce;
	var $is_admin = false;
	var $type = 'calendar';
	var $limit = 0;
	var $limit_description = 0;
	var $category = "";
	var $event_id = "";
	var $author = "";
	var $id_calendar = null;
	var $default_date = null;
	var $calendar_obj;
	var $wpdb = null;
	var $eventsByCurrDate = null;
	var $datesObj;
	var $widget;
	var $translation = array( 
							'TXT_NO_EVENTS_FOUND' 	=> 'No Events were found.',
							'TXT_ALL_DAY' 			=> 'All Day',
							'TXT_REFERENCES' 		=> 'References',
							'TXT_VIEW_ALL_EVENTS'	=> 'View all events',
							'TXT_ALL_CATEGORIES'	=> 'All Categories',
							'TXT_MONTHLY'			=> 'Monthly',
							'TXT_DAILY'				=> 'Daily',
							'TXT_ALL_WORKING_DAYS'	=> 'All working days',
							'TXT_SEARCH' 			=> 'Search...',
							'TXT_RESULTS_FOR' 		=> 'Results: ',
							'TXT_BY' 				=> 'By',
							'TXT_CURRENT_DATE'		=> 'Current Date',
							'TXT_BOOK_EVENT'		=> 'Book Event',
							'TXT_BOOK_EVENT_REMOVE'	=> 'Remove Booking',
							'TXT_BOOK_EVENT_SAVED'	=> 'Booking saved successfully.',
							'TXT_BOOK_EVENT_REMOVED'=> 'Booking removed successfully.',
							'TXT_BOOK_EVENT_SELECT_DATE'=> 'Select Date:',
							'TXT_BOOK_EVENT_PICK_DATE'=> 'Click to book on this date.',
							'TXT_BOOK_ALREADY_BOOKED'=> 'You have already booked this event date.',
							'TXT_BOOK_EVENT_COMMENT'=> 'Leave a comment (optional):',
							'TXT_CATEGORY'			=> 'Category',
							'TXT_SUBSCRIBE'			=> 'Subscribe',
							'TXT_SUBSCRIBE_SUBTITLE'=> 'Receive new events notifications in your email.',
							'TXT_YOUR_NAME'			=> 'Your Name',
							'TXT_YOUR_EMAIL'		=> 'Your Email',
							'TXT_FIELDS_REQUIRED'	=> 'All fields are required.',
							'TXT_INVALID_EMAIL'		=> 'The Email is invalid.',
							'TXT_SUBSCRIBE_THANKS'	=> 'Thanks for subscribing.',
							'TXT_SENDING'			=> 'Sending...',
							'TXT_SEND'				=> 'Send',
							'TXT_ADD_EVENT'			=> '+ Add Event',
							'TXT_EDIT_EVENT'		=> 'Edit Event',
							'TXT_REMOVE_EVENT'		=> 'Remove Event',
							'TXT_REMOVE_EVENT_CONFIRM'=> 'Are you sure that you want to delete this event?',
							'TXT_CANCEL'			=> 'Cancel',
							'TXT_YES'				=> 'Yes',
							'TXT_NO'				=> 'No',
							'TXT_EVENT_LOGIN'		=> 'You must be logged in to submit an event.',
							'TXT_EVENT_THANKS'		=> 'Thanks for submit the event, it will be reviewed in shortly.',
							'TXT_EVENT_TITLE'		=> 'Title',
							'TXT_EVENT_DESCRIPTION'	=> 'Event Description',
							'TXT_EVENT_IMAGE'		=> 'Upload an Image (optional)',
							'TXT_EVENT_LINK'		=> 'Link (optional)',
							'TXT_EVENT_SHARE'		=> 'Text to share in social networks (optional)',
							'TXT_EVENT_LOCATION'	=> 'Location (optional)',
							'TXT_EVENT_PHONE'		=> 'Phone (optional)',
							'TXT_EVENT_GOOGLEMAP'	=> 'Google Map (optional)',
							'TXT_EVENT_START_DATE'	=> 'Start Date',
							'TXT_EVENT_ALL_DAY'		=> 'Set if the event is all the day.',
							'TXT_EVENT_START_TIME'	=> 'Start Time',
							'TXT_EVENT_HIDE_TIME'	=> 'Hide Time',
							'TXT_EVENT_END_TIME'	=> 'End Time',
							'TXT_EVENT_FREQUENCY'	=> 'Frequency',
							'TXT_NONE'				=> 'None',
							'TXT_EVENT_DAILY'		=> 'Daily',
							'TXT_EVENT_WEEKLY'		=> 'Weekly',
							'TXT_EVENT_MONTHLY'		=> 'Monthly',
							'TXT_EVENT_YEARLY'		=> 'Yearly',
							'TXT_EVENT_END_DATE'		=> 'End Date',
							'TXT_SUBMIT_FOR_REVIEW'		=> 'Submit for Review',
							'PREV_MONTH' 			=> 'Prev Month',
							'NEXT_MONTH'			=> 'Next Month',
							'PREV_DAY' 				=> 'Prev Day',
							'NEXT_DAY'				=> 'Next Day',
							'DAY_SUNDAY' 			=> 'Sunday',
							'DAY_MONDAY' 			=> 'Monday',
							'DAY_TUESDAY' 			=> 'Tuesday',
							'DAY_WEDNESDAY' 		=> 'Wednesday',
							'DAY_THURSDAY' 			=> 'Thursday',
							'DAY_FRIDAY' 			=> 'Friday',
							'DAY_SATURDAY' 			=> 'Saturday',
							'MONTHS' 				=> array(
														'January',
														'February',
														'March',
														'April',
														'May',
														'June',
														'July',
														'August',
														'September',
														'October',
														'November',
														'December'
													)
					   );
	var $table_calendar;
	var $table_subscribers_calendar;
	
	function DpProEventCalendar( $is_admin = false, $id_calendar = null, $defaultDate = null, $translation = null, $widget = '', $category = '', $event_id = '' ) 
	{
		global $table_prefix;
		
		$this->table_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		$this->table_events = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		$this->table_booking = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_BOOKING;
		$this->table_special_dates = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES;
		$this->table_special_dates_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR;
		$this->table_subscribers_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR;

		$this->widget = $widget;
		if($is_admin) { $this->is_admin = true; }
		if(is_numeric($id_calendar)) { $this->setCalendar($id_calendar); }
		if(!isset($defaultDate)) { $defaultDate = $this->getDefaultDate(); }
		$this->defaultDate = $defaultDate;
		if(isset($translation)) { $this->translation = $translation; }
		if(isset($category)) { $this->category = $category; }
		if(isset($event_id)) { $this->event_id = $event_id; }
		
		$this->nonce = rand();
		
		$this->datesObj = new DPPEC_Dates($defaultDate);
		//die(print_r($this->datesObj));
    }
	
	function setCalendar($id) {
		$this->id_calendar = $id;	
		
		$this->getCalendarData();
		
		if($this->calendar_obj->lang_month_january != '') {
			$this->translation = array( 
				'TXT_NO_EVENTS_FOUND' 	=> $this->calendar_obj->lang_txt_no_events_found,
				'TXT_ALL_DAY' 			=> $this->calendar_obj->lang_txt_all_day,
				'TXT_REFERENCES' 		=> $this->calendar_obj->lang_txt_references,
				'TXT_VIEW_ALL_EVENTS'	=> $this->calendar_obj->lang_txt_view_all_events,
				'TXT_ALL_CATEGORIES'	=> $this->calendar_obj->lang_txt_all_categories,
				'TXT_MONTHLY'			=> $this->calendar_obj->lang_txt_monthly,
				'TXT_DAILY'				=> $this->calendar_obj->lang_txt_daily,
				'TXT_ALL_WORKING_DAYS'	=> $this->calendar_obj->lang_txt_all_working_days,
				'TXT_SEARCH' 			=> $this->calendar_obj->lang_txt_search,
				'TXT_RESULTS_FOR' 		=> $this->calendar_obj->lang_txt_results_for,
				'TXT_BY' 				=> $this->calendar_obj->lang_txt_by,
				'TXT_CURRENT_DATE' 		=> $this->calendar_obj->lang_txt_current_date,
				'TXT_BOOK_EVENT'		=> $this->calendar_obj->lang_txt_book_event,
				'TXT_BOOK_EVENT_REMOVE'	=> $this->calendar_obj->lang_txt_book_event_remove,
				'TXT_BOOK_EVENT_SAVED'	=> $this->calendar_obj->lang_txt_book_event_saved,
				'TXT_BOOK_EVENT_REMOVED'=> $this->calendar_obj->lang_txt_book_event_removed,
				'TXT_BOOK_EVENT_SELECT_DATE'=> $this->calendar_obj->lang_txt_book_event_select_date,
				'TXT_BOOK_EVENT_PICK_DATE'=> $this->calendar_obj->lang_txt_book_event_pick_date,
				'TXT_BOOK_ALREADY_BOOKED'=> $this->calendar_obj->lang_txt_book_event_already_booked,
				'TXT_BOOK_EVENT_COMMENT'=> $this->calendar_obj->lang_txt_book_event_comment,
				'PREV_MONTH' 			=> $this->calendar_obj->lang_prev_month,
				'NEXT_MONTH'			=> $this->calendar_obj->lang_next_month,
				'PREV_DAY' 				=> $this->calendar_obj->lang_prev_day,
				'NEXT_DAY'				=> $this->calendar_obj->lang_next_day,
				'DAY_SUNDAY' 			=> $this->calendar_obj->lang_day_sunday,
				'DAY_MONDAY' 			=> $this->calendar_obj->lang_day_monday,
				'DAY_TUESDAY' 			=> $this->calendar_obj->lang_day_tuesday,
				'DAY_WEDNESDAY' 		=> $this->calendar_obj->lang_day_wednesday,
				'DAY_THURSDAY' 			=> $this->calendar_obj->lang_day_thursday,
				'DAY_FRIDAY' 			=> $this->calendar_obj->lang_day_friday,
				'DAY_SATURDAY' 			=> $this->calendar_obj->lang_day_saturday,
				'TXT_CATEGORY'	 		=> $this->calendar_obj->lang_txt_category,
				'TXT_SUBSCRIBE'	 		=> $this->calendar_obj->lang_txt_subscribe,
				'TXT_SUBSCRIBE_SUBTITLE'=> $this->calendar_obj->lang_txt_subscribe_subtitle,
				'TXT_YOUR_NAME'	 		=> $this->calendar_obj->lang_txt_your_name,
				'TXT_YOUR_EMAIL' 		=> $this->calendar_obj->lang_txt_your_email,
				'TXT_FIELDS_REQUIRED'	=> $this->calendar_obj->lang_txt_fields_required,
				'TXT_INVALID_EMAIL'		=> $this->calendar_obj->lang_txt_invalid_email,
				'TXT_SUBSCRIBE_THANKS'	=> $this->calendar_obj->lang_txt_subscribe_thanks,
				'TXT_SENDING'	 		=> $this->calendar_obj->lang_txt_sending,
				'TXT_SEND'		 		=> $this->calendar_obj->lang_txt_send,
				'TXT_ADD_EVENT' 		=> $this->calendar_obj->lang_txt_add_event,
				'TXT_EDIT_EVENT' 		=> $this->calendar_obj->lang_txt_edit_event,
				'TXT_REMOVE_EVENT' 		=> $this->calendar_obj->lang_txt_remove_event,
				'TXT_REMOVE_EVENT_CONFIRM'=> $this->calendar_obj->lang_txt_remove_event_confirm,
				'TXT_CANCEL' 			=> $this->calendar_obj->lang_txt_cancel,
				'TXT_YES' 				=> $this->calendar_obj->lang_txt_yes,
				'TXT_NO'	 			=> $this->calendar_obj->lang_txt_no,
				'TXT_EVENT_LOGIN' 		=> $this->calendar_obj->lang_txt_logged_to_submit,
				'TXT_EVENT_THANKS' 		=> $this->calendar_obj->lang_txt_thanks_for_submit,
				'TXT_EVENT_TITLE' 		=> $this->calendar_obj->lang_txt_event_title,
				'TXT_EVENT_DESCRIPTION' => $this->calendar_obj->lang_txt_event_description,
				'TXT_EVENT_LINK' 		=> $this->calendar_obj->lang_txt_event_link,
				'TXT_EVENT_SHARE' 		=> $this->calendar_obj->lang_txt_event_share,
				'TXT_EVENT_IMAGE'		=> $this->calendar_obj->lang_txt_event_image,
				'TXT_EVENT_LOCATION' 	=> $this->calendar_obj->lang_txt_event_location,
				'TXT_EVENT_PHONE'	 	=> $this->calendar_obj->lang_txt_event_phone,
				'TXT_EVENT_GOOGLEMAP' 	=> $this->calendar_obj->lang_txt_event_googlemap,
				'TXT_EVENT_START_DATE' 	=> $this->calendar_obj->lang_txt_event_start_date,
				'TXT_EVENT_ALL_DAY' 	=> $this->calendar_obj->lang_txt_event_all_day,
				'TXT_EVENT_START_TIME' 	=> $this->calendar_obj->lang_txt_event_start_time,
				'TXT_EVENT_HIDE_TIME' 	=> $this->calendar_obj->lang_txt_event_hide_time,
				'TXT_EVENT_END_TIME' 	=> $this->calendar_obj->lang_txt_event_end_time,
				'TXT_EVENT_FREQUENCY' 	=> $this->calendar_obj->lang_txt_event_frequency,
				'TXT_NONE' 				=> $this->calendar_obj->lang_txt_event_none,
				'TXT_EVENT_DAILY' 		=> $this->calendar_obj->lang_txt_event_daily,
				'TXT_EVENT_WEEKLY' 		=> $this->calendar_obj->lang_txt_event_weekly,
				'TXT_EVENT_MONTHLY' 	=> $this->calendar_obj->lang_txt_event_monthly,
				'TXT_EVENT_YEARLY' 		=> $this->calendar_obj->lang_txt_event_yearly,
				'TXT_EVENT_END_DATE' 	=> $this->calendar_obj->lang_txt_event_end_date,
				'TXT_SUBMIT_FOR_REVIEW' => $this->calendar_obj->lang_txt_event_submit,
				'MONTHS' 				=> array(
											$this->calendar_obj->lang_month_january,
											$this->calendar_obj->lang_month_february,
											$this->calendar_obj->lang_month_march,
											$this->calendar_obj->lang_month_april,
											$this->calendar_obj->lang_month_may,
											$this->calendar_obj->lang_month_june,
											$this->calendar_obj->lang_month_july,
											$this->calendar_obj->lang_month_august,
											$this->calendar_obj->lang_month_september,
											$this->calendar_obj->lang_month_october,
											$this->calendar_obj->lang_month_november,
											$this->calendar_obj->lang_month_december
										)
		   );
	   }
	}
	
	function getNonce() {
		if(!is_numeric($this->id_calendar)) { return false; }
		
		return $this->nonce;
	}
	
	function getDefaultDate() {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar)) { return time(); }
		
		$default_date;
		$querystr = "
		SELECT default_date
		FROM ".$this->table_calendar ."
		WHERE id = ".$this->id_calendar;
		
		$calendar_obj = $wpdb->get_results($querystr, OBJECT);
		$calendar_obj = $calendar_obj[0];	
		if(!empty($calendar_obj)) {
			foreach($calendar_obj as $key=>$value) { $$key = $value; }
		}

		if($default_date == "" || $default_date == "0000-00-00") { $default_date = time(); } else { $default_date = strtotime($default_date); }
		return $default_date;
	}
	
	function getCalendarName() {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar)) { return ""; }
		
		$default_date;
		$querystr = "
		SELECT title
		FROM ".$this->table_calendar ."
		WHERE id = ".$this->id_calendar;
		
		$calendar_obj = $wpdb->get_results($querystr, OBJECT);
		$calendar_obj = $calendar_obj[0];	
		
		if(!empty($calendar_obj)) {
			foreach($calendar_obj as $key=>$value) { $$key = $value; }
		}
		return $title;
	}
	
	function getCalendarData() {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar)) { return time(); }
		
		$querystr = "
		SELECT *
		FROM ".$this->table_calendar ."
		WHERE id = ".$this->id_calendar;
		
		$calendar_obj = $wpdb->get_results($querystr, OBJECT);
		$calendar_obj = $calendar_obj[0];	

		$this->calendar_obj = $calendar_obj;
	}
	
	function getCalendarByEvent($event_id) {
		global $wpdb;
		
		$calendar_id;
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT (".$meta_key." = 'pec_id_calendar') as id_calendar
		FROM ".$wpdb->posts." p
		WHERE p.ID = ".$event_id;
		
		$calendar_obj = $wpdb->get_results($querystr, OBJECT);
		$calendar_obj = $calendar_obj[0];	
		foreach($calendar_obj as $key=>$value) { $$key = $value; }
		
		$id_calendar = explode(',', $id_calendar);
		$id_calendar = $id_calendar[0];
		
		if($id_calendar == "") { $calendar_id = false; } else { $calendar_id = $id_calendar; }
		return $calendar_id;
	}
	
	function getEventData($event_id) {
		global $wpdb;
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT 	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				(".$meta_key." = 'pec_id_calendar' LIMIT 1) as id_calendar, 
				(".$meta_key." = 'pec_date' LIMIT 1) as date, 
				(".$meta_key." = 'pec_all_day' LIMIT 1) as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every, 
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every,
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position,
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day,
				(".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) as recurring_frecuency, 
				(".$meta_key." = 'pec_end_date' LIMIT 1) as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone
		FROM ".$wpdb->posts." p
		WHERE p.ID = ".$event_id;
		
		$event_obj = $wpdb->get_results($querystr, OBJECT);
		$event_obj = $event_obj[0];	
		
		return $event_obj;
	}
	
	function getFormattedEventData($get = "") {
		global $wpdb, $post;
		$post_id = $post->ID;

		$return = "";
		$event_data = $this->getEventData($post_id);
		switch($get) {
			case 'location':
				if($event_data->location != "") {
					$return = '<div class="pec_event_page_location"><p>'.$event_data->location.'</p></div>';
				}
				break;
			case 'phone':
				if($event_data->phone != "") {
					$return = '<div class="pec_event_page_phone"><p>'.$event_data->phone.'</p></div>';
				}
				break;
			case 'link':
				if($event_data->link != "") {
					$return = '<div class="pec_event_page_link"><p><a href="'.$event_data->link.'" target="_blank">'.$event_data->link.'</a></p></div>';
				}
				break;
			case 'categories':
				$category = get_the_terms( $post_id, 'pec_events_category' ); 
				$html = "";
				if(!empty($category)) {
					$category_count = 0;
					$html .= '
					<div class="pec_event_page_categories">
						<p>';
					foreach ( $category as $cat){
						if($category_count > 0) {
							$html .= " / ";	
						}
						$html .= $cat->name;
						$category_count++;
					}
					$html .= '
						</p>
					</div>';
				}
				$return = $html;
				break;
			case 'frequency':
				if($event_data->recurring_frecuency != "") {
					switch($event_data->recurring_frecuency) {
						case 1:
							$return = $this->translation['TXT_EVENT_DAILY'];
							break;	
						case 2:
							$return = $this->translation['TXT_EVENT_WEEKLY'];
							break;	
						case 3:
							$return = $this->translation['TXT_EVENT_MONTHLY'];
							break;	
						case 4:
							$return = $this->translation['TXT_EVENT_YEARLY'];
							break;	
					}
				}
				break;
			case 'map':
				if($event_data->map != "") {
					$return = '
					<iframe class="dp_pec_date_event_map_iframe" width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&q='.urlencode($event_data->map).'&ie=UTF8&output=embed"></iframe>';
				}
				break;
			case 'rating':
				$rate = get_post_meta($post_id, 'pec_rate', true);
				if($rate != '') {
					$return .= '
					<ul class="dp_pec_rate">
						<li><a href="javascript:void(0);" '.($rate >= 1 ? 'class="dp_pec_rate_full"' : '').'></a></li>
						<li><a href="javascript:void(0);" '.($rate >= 2 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 1 && $rate < 2 ? 'class="dp_pec_rate_h"' : '').'></a></li>
						<li><a href="javascript:void(0);" '.($rate >= 3 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 2 && $rate < 3 ? 'class="dp_pec_rate_h"' : '').'></a></li>
						<li><a href="javascript:void(0);" '.($rate >= 4 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 3 && $rate < 4 ? 'class="dp_pec_rate_h"' : '').'></a></li>
						<li><a href="javascript:void(0);" '.($rate >= 5 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 4 && $rate < 5 ? 'class="dp_pec_rate_h"' : '').'></a></li>
					</ul>
					<div class="dp_pec_clear"></div>';
				}
				break;
			case 'date':
				if($this->calendar_obj->format_ampm) {
					$time = date('h:i A', strtotime($event_data->date));
				} else {
					$time = date('H:i', strtotime($event_data->date));				
				}
				
				$end_date = '';
				$end_year = '';
				if($event_data->end_date != "" && $event_data->end_date != "0000-00-00" && $event_data->recurring_frecuency == 1) {
					$end_day = date('d', strtotime($event_data->end_date));
					$end_month = date('n', strtotime($event_data->end_date));
					$end_year = date('Y', strtotime($event_data->end_date));
					
					//$end_date = ' / <br />'.$end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3).', '.$end_year;
					$end_date = ' / '.date_i18n(get_option('date_format'), strtotime($event_data->end_date));
				}
									
				$end_time = "";
				if($event_data->end_time_hh != "" && $event_data->end_time_mm != "") { $end_time = str_pad($event_data->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($event_data->end_time_mm, 2, "0", STR_PAD_LEFT); }
				
				if($end_time != "") {
					
					if($this->calendar_obj->format_ampm) {
						$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
					} else {
						$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
					}
					$end_time = " / ".$end_time_tmp;
					if($end_time_tmp == $time) {
						$end_time = "";	
					}
				}

				if($event_data->all_day) {
					$time = $this->translation['TXT_ALL_DAY'];
					$end_time = "";
				}
				
				$all_working_days = '';
				if($event->pec_daily_working_days && $event->recurring_frecuency == 1) {
					$all_working_days = $this->translation['TXT_ALL_WORKING_DAYS'];
				}

				$return .= '<div class="pec_event_page_date">'.
								'<p>'.date_i18n(get_option('date_format'), strtotime($event_data->date)).$end_date.' - 
								'.$all_working_days.' '.((($this->calendar_obj->show_time && !$event_data->hide_time) || $event_data->all_day) ? $time.$end_time : '').'</p>'.
						   '</div>';
				if(is_user_logged_in() && (get_post_meta($post_id, 'pec_enable_booking', true) || $this->calendar_obj->booking_enable) ) {
					$calendar = explode(",", get_post_meta($post_id, 'pec_calendar', true));
					
					$return .= '<a href="javascript:void(0);" class="pec_event_page_book" data-event-id="'.$post_id.'">'.$this->translation['TXT_BOOK_EVENT'].'</a>';
					$return .= '<div class="pec_book_select_date">
						<span class="dp_pec_form_desc">'.$this->translation['TXT_BOOK_EVENT_SELECT_DATE'].'</span>
						
						'.do_shortcode('[dpProEventCalendar id="'.$calendar[0].'" event_id="'.$post_id.'"]').'

						<form>

						<input type="hidden" name="pec_event_page_book_date" id="pec_event_page_book_date" value="" />
						<input type="hidden" name="pec_event_page_book_event_id" id="pec_event_page_book_event_id" value="'.$post_id.'" />
						<input type="hidden" name="pec_event_page_book_calendar" id="pec_event_page_book_calendar" value="'.$calendar[0].'" />
						
						<br>
						';
					if($this->calendar_obj->booking_comment) {
						$return .= '
						<span class="dp_pec_form_desc">'.$this->translation['TXT_BOOK_EVENT_COMMENT'].'</span>
						<textarea name="pec_event_page_book_comment" id="pec_event_page_book_comment" class="dpProEventCalendar_textarea"></textarea>';
					}
					$return .= '
						<div class="clear"></div>
						<div class="pec-add-footer">
							<button class="pec_event_page_send_booking">'.$this->translation['TXT_SEND'].'</button>
							<div class="clear"></div>
						</div>
						</form>
					</div>';
					
				}
				$return .= '
						   <div class="dp_pec_clear"></div>';

				break;
			case 'time':
				if($this->calendar_obj->format_ampm) {
					$time = date('h:i A', strtotime($event_data->date));
				} else {
					$time = date('H:i', strtotime($event_data->date));				
				}
													
				$end_time = "";
				if($event_data->end_time_hh != "" && $event_data->end_time_mm != "") { $end_time = str_pad($event_data->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($event_data->end_time_mm, 2, "0", STR_PAD_LEFT); }
				
				if($end_time != "") {
					
					if($this->calendar_obj->format_ampm) {
						$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
					} else {
						$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
					}
					$end_time = " / ".$end_time_tmp;
					if($end_time_tmp == $time) {
						$end_time = "";	
					}
				}

				if($event_data->all_day) {
					$time = $this->translation['TXT_ALL_DAY'];
					$end_time = "";
				}

				$return .= '<div class="pec_event_page_date">'.
								'<p>'.$all_working_days.' '.((($this->calendar_obj->show_time && !$event_data->hide_time) || $event_data->all_day) ? $time.$end_time : '').'</p>'.
						   '</div>
						   <div class="dp_pec_clear"></div>';

				break;
		}
		
		return $return;
	}
	
	function getCountEventsByDate($date) {
		global $wpdb;

		if(!is_numeric($this->id_calendar)) { return false; }
		if($this->is_admin) { return 0; }
		
		$event_count = 0;
		
		$events = $this->getEventsByDate($date);
		
		foreach($events as $event) {
			if($date != "" && $event->pec_daily_working_days && $event->recurring_frecuency == 1 && (date('w', strtotime($date)) == "0" || date('w', strtotime($date)) == "6")) {
				continue;
			}
			
			if($date != "" && !$event->pec_daily_working_days && $event->recurring_frecuency == 1 && $event->pec_daily_every > 1 && 
				( ((strtotime($date) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % $event->pec_daily_every != 0 )
			) {
				continue;
			}
						
			if($date != "" && $event->recurring_frecuency == 2 && $event->pec_weekly_every > 1 && 
				( ((strtotime($date) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % ($event->pec_weekly_every * 7) != 0 )
			) {
				continue;
			}
			
			if($date != "" && $event->recurring_frecuency == 3 && $event->pec_monthly_every > 1 && 
				( !is_int (((date('m', strtotime($date)) - date('m', strtotime(substr($event->date,0,11))))) / ($event->pec_monthly_every)) )
			) {
				continue;
			}

			$event_count++;
		}

		//return $result[0]->counter;
		return $event_count;
	}
	
	function monthlyCalendarLayout() 
	{
		global $dpProEventCalendar_cache;

		$month_search = $this->datesObj->currentYear.'-'.str_pad($this->datesObj->currentMonth, 2, "0", STR_PAD_LEFT);

		if(!$this->is_admin &&
			isset($dpProEventCalendar_cache['calendar_id_'.$this->id_calendar]) && 
			isset($dpProEventCalendar_cache['calendar_id_'.$this->id_calendar]['monthlyLayout'][$month_search]) && 
			$this->calendar_obj->cache_active &&
			empty($this->category) &&
			empty($this->event_id)) {
				
			$html = $dpProEventCalendar_cache['calendar_id_'.$this->id_calendar]['monthlyLayout'][$month_search]['html'];
			//echo '<pre>';
			//print_r($dpProEventCalendar_cache['calendar_id_'.$this->id_calendar]);
			//echo '</pre>';
		} else {
		//die();
			$html = '';
			
			if($this->calendar_obj->first_day == 1) {
				if($this->datesObj->firstDayNum == 0) { $this->datesObj->firstDayNum == 7;  }
				$this->datesObj->firstDayNum--;
				
				$html .= '
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_MONDAY'].'</span>
					 </div>';
			} else {
				$html .= '
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_SUNDAY'].'</span>
					 </div>
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_MONDAY'].'</span>
					 </div>';
			}
			$html .= '
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_TUESDAY'].'</span>
					 </div>
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_WEDNESDAY'].'</span>
					 </div>
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_THURSDAY'].'</span>
					 </div>
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_FRIDAY'].'</span>
					 </div>
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_SATURDAY'].'</span>
					 </div>
					 ';
			if($this->calendar_obj->first_day == 1) {
				$html .= '
					 <div class="dp_pec_dayname">
							<span>'.$this->translation['DAY_SUNDAY'].'</span>
					 </div>';
			}
			
			$general_count = 0;
			
			if( $this->datesObj->firstDayNum != 6 ) {
				
				for($i = ($this->datesObj->daysInPrevMonth - $this->datesObj->firstDayNum); $i <= $this->datesObj->daysInPrevMonth; $i++) 
				{
					$html .= '
							<div class="dp_pec_date disabled '.($general_count % 7 == 0 ? 'first-child' : '').'">
								<div class="dp_date_head"><span>'.$i.'</span></div>
							</div>';
					
					$general_count++;
				}
				
			}
			
			for($i = 1; $i <= $this->datesObj->daysInCurrentMonth; $i++) 
			{
				$curDate = $this->datesObj->currentYear.'-'.str_pad($this->datesObj->currentMonth, 2, "0", STR_PAD_LEFT).'-'.str_pad($i, 2, "0", STR_PAD_LEFT);
				$countEvents = 0;
				
				if(!$this->is_admin) {
					$result = $this->getEventsByDate($curDate);
					$eventsCurrDate = array();
					
					if(is_array($result)) {
						foreach($result as $event) {
			
							if($event->pec_daily_working_days && $event->recurring_frecuency == 1 && (date('w', strtotime($curDate)) == "0" || date('w', strtotime($curDate)) == "6")) {
								continue;
							}
							
							if(!$event->pec_daily_working_days && $event->recurring_frecuency == 1 && $event->pec_daily_every > 1 && 
								( ((strtotime($curDate) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % $event->pec_daily_every != 0 )
							) {
								continue;
							}
							
							if($event->recurring_frecuency == 2 && $event->pec_weekly_every > 1 && 
								( ((strtotime($curDate) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % ($event->pec_weekly_every * 7) != 0 )
							) {
								continue;
							}
							
							if($event->recurring_frecuency == 3 && $event->pec_monthly_every > 1 && 
								( !is_int (((date('m', strtotime($curDate)) - date('m', strtotime(substr($event->date,0,11))))) / ($event->pec_monthly_every)) )
							) {
								continue;
							}
							
							$eventsCurrDate[] = $event;
							
							$countEvents++;
						}
					}
				}
				
				//$countEvents = $this->getCountEventsByDate($curDate);
				if($this->calendar_obj->hide_old_dates) {
					$this->calendar_obj->date_range_start = date('Y-m-d');	
				}
				if(($this->calendar_obj->date_range_start != '0000-00-00' && $this->calendar_obj->date_range_start != NULL && (strtotime($curDate) < strtotime($this->calendar_obj->date_range_start)) || ( $this->calendar_obj->date_range_end != '0000-00-00' && $this->calendar_obj->date_range_end != NULL && strtotime($curDate) > strtotime($this->calendar_obj->date_range_end))) && !$this->is_admin) {
					$html .= '
						<div class="dp_pec_date disabled '.($general_count % 7 == 0 ? 'first-child' : '').'">
							<div class="dp_date_head"><span>'.$i.'</span></div>
						</div>';
				} else {
					$special_date = "";
					$special_date_obj = $this->getSpecialDates($curDate);
					$booked_date = false;
					
					if(!empty($this->event_id)) {
						
						if($this->userHasBooking($curDate, $this->event_id)) {
							$special_date = "style='background-color: ".$this->calendar_obj->booking_event_color.";' ";
							$booked_date = true;
						}
						
					} else {
						
						if($special_date_obj->color) {
							$special_date = "style='background-color: ".$special_date_obj->color.";' ";
						}
						
						if($curDate == date("Y-m-d", current_time('timestamp'))) {
							$special_date = "style='background-color: ".$this->calendar_obj->current_date_color.";' ";
						}
						
					}
					
					$html .= '
						<div class="dp_pec_date '.($general_count % 7 == 0 ? 'first-child' : '').'" data-dppec-date="'.$curDate.'" '.$special_date.'>
							<div class="dp_date_head"><span>'.$i.'</span></div>
							'.($countEvents > 0 && !$booked_date ? ($this->calendar_obj->show_x || !empty($this->event_id) ? (!empty($this->event_id) ? '<span class="dp_book_event_radio"></span>' : '<span class="dp_count_events">X</span>') : '<span class="dp_count_events">'.$countEvents.'</span>') : '').'
							';
					if($this->is_admin) {
						$html .= '
							<div class="dp_manage_special_dates" style="display: none;">
								<div class="dp_manage_sd_head">Special Date</div>
								<select>
									<option value="">None</option>';
									foreach($this->getSpecialDatesList() as $key) {
										$html .= '<option value="'.$key->id.','.$key->color.'" '.($key->id == $special_date_obj->id ? 'selected' : '').'>'.$key->title.'</option>';
									}
						$html .= '
								</select>	
							</div>';
					}
					if($countEvents > 0 && ($this->calendar_obj->show_preview || !empty($this->event_id))) {
						$html .= '
							<div class="eventsPreview">
								<ul>
							';
							if(!empty($this->event_id)) {
								if($booked_date) {
									$html .= '<li>'.$this->translation['TXT_BOOK_ALREADY_BOOKED'].'</li>';
								} else {
									$html .= '<li>'.$this->translation['TXT_BOOK_EVENT_PICK_DATE'].'</li>';
								}
								
							} else {
								//$result = $this->getEventsByDate($curDate);
								foreach($eventsCurrDate as $event) {
			
						
									if($this->calendar_obj->format_ampm) {
										$time = date('h:i A', strtotime($event->date));
									} else {
										$time = date('H:i', strtotime($event->date));				
									}
															
									$html .= '<li>';
									if($event->all_day) {
										$time = $this->translation['TXT_ALL_DAY'];
										$end_time = "";
									}
									if($this->calendar_obj->show_time && !$event->hide_time) {
										$html .= '<span>'.$time.'</span>';
									}
			
									$html .= $event->title;
									$html .= '<div class="dp_pec_clear"></div>';
									$html .= get_the_post_thumbnail($event->id, 'medium');
									
									$html .= '</li>';
								}
							}
						$html .= '
								</ul>
							</div>';
					}
					
					$html .= '
						</div>';
				}
				
				$general_count++;
			}
			
			if( $this->datesObj->lastDayNum != ($this->calendar_obj->first_day == 1 ? 7 : 6) ) {
				
				for($i = 1; $i <= ( ($this->calendar_obj->first_day == 1 ? 7 : 6) - $this->datesObj->lastDayNum ); $i++) 
				{
					$html .= '
							<div class="dp_pec_date disabled '.($general_count % 7 == 0 ? 'first-child' : '').'">
								<div class="dp_date_head"><span>'.$i.'</span></div>
							</div>';
					
					$general_count++;
					
				}
				
			}
			$html .= '<div class="clear"></div>';
		}
		
		if(!$this->is_admin &&
			empty($this->category) &&
			empty($this->event_id)) {
		
			$cache = array(
				'calendar_id_'.$this->id_calendar => array(
					'monthlyLayout' => array(
						$month_search => array(
							'html'		  => $html,
							'lastUpdate'  => time()	
						)
					)
				)
			);
			
			if(!$dpProEventCalendar_cache) {
				update_option( 'dpProEventCalendar_cache', $cache);
			} else if($html != "") {
					
				//$dpProEventCalendar_cache[] = $cache;
				$dpProEventCalendar_cache['calendar_id_'.$this->id_calendar]['monthlyLayout'][$month_search] =  array(
						'html'		  => $html,
						'lastUpdate'  => time()	
					);
					//print_r($dpProEventCalendar_cache);
				update_option( 'dpProEventCalendar_cache', $dpProEventCalendar_cache );
			}
		}
		return $html;
	}
	
	function dailyCalendarLayout($curDate = null) 
	{
		$html = "";
		if(is_null($curDate)) {
			$curDate = $this->datesObj->currentYear.'-'.str_pad($this->datesObj->currentMonth, 2, "0", STR_PAD_LEFT).'-'.str_pad($this->datesObj->currentDate, 2, "0", STR_PAD_LEFT);
		}
		
		for($i = $this->calendar_obj->limit_time_start; $i <= $this->calendar_obj->limit_time_end; $i++) {
			$min = '00';
			$hour = $i;
			if($this->calendar_obj->format_ampm) {
				$min = ($i >= 12 ? 'PM' : 'AM');
				$hour = ($i > 12 ? $i - 12 : $i);
			}
			$html .= '<div class="dp_pec_date first-child">
						<div class="dp_date_head"><span>'.$hour.'</span><span class="dp_pec_minutes">'.$min.'</span></div>';
			$result = $this->getEventsByDate($curDate);
			foreach($result as $event) {
				if(date('G', strtotime($event->date)) != $i) { continue; }
				
				if($this->calendar_obj->format_ampm) {
					$time = date('h:i A', strtotime($event->date));
				} else {
					$time = date('H:i', strtotime($event->date));				
				}
				if($this->calendar_obj->show_time && !$event->hide_time && !$event->all_day) {
					//$html .= '<span>'.$time.'</span>';
				}
				$title = $event->title;
				$html .= '<span class="dp_daily_event" data-dppec-event="'.$event->id.'">'.$title.'</span>';
			}
			$html .= '
				</div>';
		}
		$html .= '<div class="clear"></div>';
		return $html;
	}
	
	function getSpecialDates($date) {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar) || !isset($date)) { return false; }
		
		$querystr = "
		SELECT sp.id, sp.color 
		FROM ". $this->table_special_dates ." sp
		INNER JOIN ". $this->table_special_dates_calendar ." spc ON spc.special_date = sp.`id`
		WHERE spc.calendar = ".$this->id_calendar." AND spc.`date` = '".$date."' ";
		$result = $wpdb->get_results($querystr, OBJECT);
		
		return $result[0];
	}
	
	function setSpecialDates( $sp, $date ) {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar) || !isset($date)) { return false; }
		
		$querystr = "DELETE FROM ". $this->table_special_dates_calendar ." WHERE calendar = ".$this->id_calendar." AND date = '".$date."'; ";
		$result = $wpdb->query($querystr, OBJECT);
		
		if(is_numeric($sp)) {
			$querystr = "INSERT INTO ". $this->table_special_dates_calendar ." (special_date, calendar, date) VALUES ( ".$sp.", ".$this->id_calendar.", '".$date."' );";
			$result = $wpdb->query($querystr, OBJECT);
		}
		
		return;
	}
	
	function getSpecialDatesList() {
		global $wpdb;
		
		$querystr = "
		SELECT * 
		FROM ". $this->table_special_dates ." sp ";
		$result = $wpdb->get_results($querystr, OBJECT);
		
		return $result;
	}
	
	function getEventsByDate($date, $count = false) {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar) || !isset($date)) { return false; }
		$querystr = " SET time_zone = '+00:00'";
		$wpdb->query($querystr);
		
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		if($count) {
			$querystr = "
			SELECT COUNT(p.ID) as counter ";	
		} else {
		$querystr = "
			SELECT 	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				pm_calendar.meta_value as id_calendar, 
				pm_date.meta_value as date, 
				(".$meta_key." = 'pec_all_day') as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every, 
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every,
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position,
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day,
				pm_frecuency.meta_value as recurring_frecuency, 
				pm_end_date.meta_value as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone";
		}
		$querystr .= "
		FROM ".$wpdb->posts." p
		LEFT JOIN ".$wpdb->postmeta." pm_date ON pm_date.`post_id` = p.`ID` AND pm_date.meta_key = 'pec_date'
		LEFT JOIN ".$wpdb->postmeta." pm_end_date ON pm_end_date.`post_id` = p.`ID` AND pm_end_date.meta_key = 'pec_end_date'
		LEFT JOIN ".$wpdb->postmeta." pm_frecuency ON pm_frecuency.`post_id` = p.`ID` AND pm_frecuency.meta_key = 'pec_recurring_frecuency'
		LEFT JOIN ".$wpdb->postmeta." pm_calendar ON pm_calendar.`post_id` = p.`ID` AND pm_calendar.meta_key = 'pec_id_calendar'

		WHERE (pm_date.meta_value LIKE '".$date."%' || 
				('".$date."' BETWEEN pm_date.meta_value AND pm_end_date.meta_value AND pm_frecuency.meta_value = 1) ||
				((((UNIX_TIMESTAMP('".$date."') - UNIX_TIMESTAMP(DATE(pm_date.meta_value))) % 7) = 0 || (".$meta_key." = 'pec_weekly_day' LIMIT 1) <> '') AND (UNIX_TIMESTAMP('".$date."') BETWEEN UNIX_TIMESTAMP(pm_date.meta_value) AND UNIX_TIMESTAMP(pm_end_date.meta_value) OR (pm_end_date.meta_value = '' && UNIX_TIMESTAMP('".$date."') > UNIX_TIMESTAMP(pm_date.meta_value))) AND pm_frecuency.meta_value = 2) ||
				((DAY('".$date."') = DAY(pm_date.meta_value) || (".$meta_key." = 'pec_monthly_day' LIMIT 1) <> '') AND (UNIX_TIMESTAMP('".$date."') BETWEEN UNIX_TIMESTAMP(pm_date.meta_value) AND UNIX_TIMESTAMP(pm_end_date.meta_value) OR (pm_end_date.meta_value = '' && UNIX_TIMESTAMP('".$date."') > UNIX_TIMESTAMP(pm_date.meta_value))) AND pm_frecuency.meta_value = 3) ||
				((DAY('".$date."') = DAY(pm_date.meta_value) && MONTH('".$date."') = MONTH(pm_date.meta_value)) AND (UNIX_TIMESTAMP('".$date."') BETWEEN UNIX_TIMESTAMP(pm_date.meta_value) AND UNIX_TIMESTAMP(pm_end_date.meta_value) OR (pm_end_date.meta_value = '' && UNIX_TIMESTAMP('".$date."') > UNIX_TIMESTAMP(pm_date.meta_value))) AND pm_frecuency.meta_value = 4)
		) AND (pm_calendar.meta_value = ".$this->id_calendar." OR pm_calendar.meta_value LIKE '%,".$this->id_calendar."' OR pm_calendar.meta_value LIKE '%,".$this->id_calendar.",%' OR pm_calendar.meta_value LIKE '".$this->id_calendar.",%') AND p.post_status = 'publish'
		";
		if(!empty($this->category)) {
			$querystr .= "
			AND (
				select count(*)
				from ".$wpdb->terms." wt
				inner join ".$wpdb->term_taxonomy." wtt on wt.term_id = wtt.term_id
				inner join ".$wpdb->term_relationships." wpr on wpr.term_taxonomy_id = wtt.term_taxonomy_id
				inner join ".$wpdb->posts." p2 on p2.ID = wpr.object_id
				where wtt.taxonomy= 'pec_events_category' and wt.term_id IN (".$this->category.") and p2.ID = p.ID
			) ";
		}
		if(!empty($this->event_id)) {
			$querystr .= "
			AND p.ID = ".$this->event_id;
		}
		if(!$count) {
			$querystr .= "
			ORDER BY DATE_FORMAT(pm_date.meta_value, '%T') ASC
			";
		}
		//die($querystr);
		
		$events = array();
		foreach($wpdb->get_results($querystr, OBJECT) as $event) {
			if($event->recurring_frecuency == 2 && $event->pec_weekly_day != "") {
				$original_date = $event->date;
				foreach(unserialize($event->pec_weekly_day) as $week_day) {
					$day = "";
					
					switch($week_day) {
						case 1:
							$day = "Monday";
							break;	
						case 2:
							$day = "Tuesday";
							break;	
						case 3:
							$day = "Wednesday";
							break;	
						case 4:
							$day = "Thursday";
							break;	
						case 5:
							$day = "Friday";
							break;	
						case 6:
							$day = "Saturday";
							break;	
						case 7:
							$day = "Sunday";
							break;	
					}
					
					if(date('l', strtotime($date)) == $day) {
						$original_date = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($original_date)));
						$event->date = date("Y-m-d", strtotime("next ".$day, strtotime($original_date))). ' '.date("H:i:s", strtotime($original_date));
						$events[] = $event;
						
					}
					
				}
			} elseif($event->recurring_frecuency == 3 && $event->pec_monthly_day != "" && $event->pec_monthly_position != "") {
				$original_date = $event->date;
				//echo date('l', strtotime($date));

				if(strtolower(date('Y-m-d', strtotime($date))) == date('Y-m-d', strtotime($event->pec_monthly_position.' '.$event->pec_monthly_day.' of '.date("F Y", strtotime($date))))) {
					//$event->date = date("Y-m", strtotime($original_date)). '-'.date("d", strtotime($date)). ' '.date("H:i:s", strtotime($original_date));
					//die("OKKK");
					//$original_date = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($original_date)));
					//$event->date = date('Y-m-d', strtotime($event->pec_monthly_position.' '.$event->pec_monthly_day.' of '.date("F Y", strtotime($date)))). ' '.date("H:i:s", strtotime($original_date));
					//echo date('Y-m-d', strtotime($event->pec_monthly_position.' '.$event->pec_monthly_day.' of '.date("F Y", strtotime($date)))). ' '.date("H:i:s", strtotime($original_date))." ".$event->pec_monthly_position.' '.$event->pec_monthly_day.' of '.date("F Y", strtotime($date));
					$events[] = $event;
					
				}
			} else {
				$events[] = $event;
			}
		}
		
		return $events;
	}
	
	function getEventByID($event) {
		global $wpdb;
		
		if(!is_numeric($this->id_calendar) || !isset($event)) { return false; }
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT 	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				(".$meta_key." = 'pec_id_calendar' LIMIT 1) as id_calendar, 
				(".$meta_key." = 'pec_date' LIMIT 1) as date, 
				(".$meta_key." = 'pec_all_day' LIMIT 1) as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every, 
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every,
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position,
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day,
				(".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) as recurring_frecuency, 
				(".$meta_key." = 'pec_end_date' LIMIT 1) as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone
		FROM ".$wpdb->posts." p
		WHERE p.ID = ".$event." AND ((".$meta_key." = 'pec_id_calendar' LIMIT 1) = ".$this->id_calendar." OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar."' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar.",%' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '".$this->id_calendar.",%') AND p.post_status = 'publish'
		ORDER BY DATE_FORMAT((".$meta_key." = 'pec_date'), '%T') ASC
		";
		
		return $wpdb->get_results($querystr, OBJECT);
	}
	
	function eventsListLayout($date, $return_btn = true) {
		global $wpdb;

		if(!is_numeric($this->id_calendar) || !isset($date)) { return false; }
		
		$querystr = " SET time_zone = '+00:00'";
		$wpdb->query($querystr);
		
		$html = '
			<div class="dp_pec_date_event_head dp_pec_date_event_daily dp_pec_isotope">
				<span>'.$this->parseMysqlDate($date).'</span>';
				if($return_btn) {
					$html .= '<a href="javascript:void(0);" class="dp_pec_date_event_back"></a>';
				}
		$html .= '
			</div>';
		
		$result = $this->getEventsByDate($date);
		if($this->getCountEventsByDate($date) == 0) {
			$html .= '
			<div class="dp_pec_date_event dp_pec_isotope">
				<p class="dp_pec_event_no_events">'.$this->translation['TXT_NO_EVENTS_FOUND'].'</p>
			</div>';
		} else {
			
			$html .= $this->singleEventLayout($result, false, $date);
			
		}
		
		return $html;
	}
	
	function getSearchResults($key, $type = '') {
		global $wpdb;

		if(!is_numeric($this->id_calendar) || !isset($key)) { return false; }
		
		if($type == '') {
			$html = '
			<div class="dp_pec_date_event_head dp_pec_date_event_search dp_pec_isotope">
				<span>'.$this->translation['TXT_RESULTS_FOR'].'</span><a href="" class="dp_pec_date_event_back"></a>
			</div>';
		}
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				(".$meta_key." = 'pec_id_calendar' LIMIT 1) as id_calendar, 
				(".$meta_key." = 'pec_date' LIMIT 1) as date, 
				(".$meta_key." = 'pec_all_day' LIMIT 1) as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every, 
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every,
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position,
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day,
				(".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) as recurring_frecuency, 
				(".$meta_key." = 'pec_end_date' LIMIT 1) as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone
		FROM ".$wpdb->posts." p
		WHERE (p.post_title LIKE '%".$key."%' OR (".$meta_key." = 'pec_location' LIMIT 1) LIKE '%".$key."%' OR p.post_content LIKE '%".$key."%') AND ((".$meta_key." = 'pec_id_calendar' LIMIT 1) = ".$this->id_calendar." OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar."' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '".$this->id_calendar.",%') AND p.post_status = 'publish' AND p.post_type = 'pec-events'
		ORDER BY (".$meta_key." = 'pec_date') ASC";

		$result = $wpdb->get_results($querystr, OBJECT);
		if(count($result) == 0) {
			if($type == 'accordion') {
				$html .= '<div class="dp_pec_accordion_event dp_pec_accordion_no_events"><span>'.$this->translation['TXT_NO_EVENTS_FOUND'].'</span></div>';	
			} else {
				$html .= '
				<div class="dp_pec_date_event dp_pec_isotope">
					<p class="dp_pec_event_no_events">'.$this->translation['TXT_NO_EVENTS_FOUND'].'</p>
				</div>';
			}
		} else {
				$html .= $this->singleEventLayout($result, true, null, true, $type);
		}
		
		return $html;
	}
	
	function getCategoryResults($key) {
		global $wpdb;

		if(!is_numeric($this->id_calendar) || !is_numeric($key)) { return false; }
		
		$html = '
			<div class="dp_pec_date_event_head dp_pec_date_event_search dp_pec_isotope">
				<span>'.$this->translation['TXT_RESULTS_FOR'].'</span><a href="" class="dp_pec_date_event_back"></a>
			</div>';
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				(".$meta_key." = 'pec_id_calendar' LIMIT 1) as id_calendar, 
				(".$meta_key." = 'pec_date' LIMIT 1) as date, 
				(".$meta_key." = 'pec_all_day' LIMIT 1) as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every, 
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every,
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position,
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day,
				(".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) as recurring_frecuency, 
				(".$meta_key." = 'pec_end_date' LIMIT 1) as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone
		FROM ".$wpdb->posts." p
		INNER JOIN ".$wpdb->term_relationships." tr ON tr.object_id = p.ID
		WHERE (tr.term_taxonomy_id = ".$key.") AND ((".$meta_key." = 'pec_id_calendar' LIMIT 1) = ".$this->id_calendar." OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar."' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar.",%' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '".$this->id_calendar.",%') AND p.post_status = 'publish'
		ORDER BY (".$meta_key." = 'pec_date') ASC";

		$result = $wpdb->get_results($querystr, OBJECT);
		if(count($result) == 0) {
			$html .= '
			<div class="dp_pec_date_event dp_pec_isotope">
				<p class="dp_pec_event_no_events">'.$this->translation['TXT_NO_EVENTS_FOUND'].'</p>
			</div>';
		} else {
			
			$html .= $this->singleEventLayout($result, true);
			
		}
		
		return $html;
	}
	
	function singleEventLayout($result, $search = false, $selected_date = null, $show_end_date = true, $type = '') {
		$html = "";
		
		foreach($result as $event) {
			
			if($selected_date != "" && $event->pec_daily_working_days && $event->recurring_frecuency == 1 && (date('w', strtotime($selected_date)) == "0" || date('w', strtotime($selected_date)) == "6")) {
				continue;
			}
			
			if($selected_date != "" && !$event->pec_daily_working_days && $event->recurring_frecuency == 1 && $event->pec_daily_every > 1 && 
				( ((strtotime($selected_date) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % $event->pec_daily_every != 0 )
			) {
				continue;
			}
			
			if($selected_date != "" && $event->recurring_frecuency == 2 && $event->pec_weekly_every > 1 && 
				( ((strtotime($selected_date) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % ($event->pec_weekly_every * 7) != 0)) {
				continue;
			}
			
			if($selected_date != "" && $event->recurring_frecuency == 3 && $event->pec_monthly_every > 1 && 
				( !is_int (((date('m', strtotime($selected_date)) - date('m', strtotime(substr($event->date,0,11))))) / ($event->pec_monthly_every)) )
			) {
				continue;
			}
			
			if($this->calendar_obj->format_ampm) {
					$time = date('h:i A', strtotime($event->date));
			} else {
				$time = date('H:i', strtotime($event->date));				
			}
			$start_day = date('d', strtotime($event->date));
			$start_month = date('n', strtotime($event->date));
			$start_year = date('Y', strtotime($event->date));
			
			$end_date = '';
			$end_year = '';
			if($event->end_date != "" && $event->end_date != "0000-00-00") {
				$end_day = date('d', strtotime($event->end_date));
				$end_month = date('n', strtotime($event->end_date));
				$end_year = date('Y', strtotime($event->end_date));
				
				//$end_date = ' / <br />'.$end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3).', '.$end_year;
				$end_date = ' - '.date_i18n(get_option('date_format'), strtotime($event->end_date));
			}
			
			//$start_date = $start_day.' '.substr($this->translation['MONTHS'][($start_month - 1)], 0, 3);
			$start_date = date_i18n(get_option('date_format'), strtotime($event->date));
			
			$end_time = "";
			if($event->end_time_hh != "" && $event->end_time_mm != "") { $end_time = str_pad($event->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($event->end_time_mm, 2, "0", STR_PAD_LEFT); }
			
			if($end_time != "" && $show_end_date) {
				
				if($this->calendar_obj->format_ampm) {
					$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
				} else {
					$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
				}
				$end_time = " - ".$end_time_tmp;
				if($end_time_tmp == $time) {
					$end_time = "";	
				}
			}
			
			
			if($start_year != $end_year) {
				$start_date .= ', '.$start_year;
			}
			
			if($event->all_day) {
				$time = $this->translation['TXT_ALL_DAY'];
				$end_time = "";
			}
			
			$post_thumbnail_id = get_post_thumbnail_id( $event->id );
			$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			
			$title = '<span class="dp_pec_event_title_sp">'.$event->title.'</span>';
			if($this->calendar_obj->link_post) {
				$title = '<a href="'.get_permalink($event->id).'">'.$title.'</a>';	
			}
			
			$all_working_days = '';
			if($event->pec_daily_working_days && $event->recurring_frecuency == 1) {
				$all_working_days = $this->translation['TXT_ALL_WORKING_DAYS'];
			}
			
			if($type == 'accordion') {
				$edit_button = $this->getEditRemoveButtons($event);

				$html .= '
					<div class="dp_pec_accordion_event">
						<h2>' . $title . $edit_button . '</h2>
						';
						
						if($this->calendar_obj->show_author) {
							$author = get_userdata(get_post_field( 'post_author', $event->id ));
							$html .= '<span class="pec_author">'.$this->translation['TXT_BY'].' '.$author->display_name.'</span>';
						}
						
						$html .= '
						<span class="pec_time">'.date_i18n(get_option('date_format'), strtotime($event->date)).$end_date . '<br>'. $all_working_days. ' '. ((($this->calendar_obj->show_time && !$event->hide_time) || $event->all_day) ?  $time.$end_time : '').'</span>
						';
						
						if($event->location != '') {
							$html .= '
							<span class="dp_pec_event_location">'.$event->location.'</span>';
						}
						
						if($event->phone != '') {
							$html .= '
							<span class="dp_pec_event_phone">'.$event->phone.'</span>';
						}
					
						$category = get_the_terms( $event->id, 'pec_events_category' ); 
						if(!empty($category)) {
							$category_count = 0;
							$html .= '
								<span class="dp_pec_event_categories">';
							foreach ( $category as $cat){
								if($category_count > 0) {
									$html .= " / ";	
								}
								$html .= $cat->name;
								$category_count++;
							}
							$html .= '
								</span>';
						}
						
				$html .= '
						<div class="pec_description">';
						$post_thumbnail_id = get_post_thumbnail_id( $event->id );
						$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
						if($post_thumbnail_id) {
							$html .= '<div class="dp_pec_event_photo">';
							$html .= '<img src="'.$image_attributes[0].'" alt="" />';
							$html .= '</div>';
						}
						
				$html .= '
							<p>'.do_shortcode(nl2br(strip_tags(str_replace("</p>", "<br />", preg_replace("/<p[^>]*?>/", "", $event->description)), '<iframe><strong><h1><h2><h3><h4><h5><h6><h7><b><i><em><pre><code><del><ins><img><a><ul><li><ol><blockquote><br><hr><span><p>'))).'</p>';
						
						$html .= $this->getRating($event->id);
						
						
						if($event->map != '') {
							$html .= '
							<iframe class="dp_pec_date_event_map_iframe" width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&q='.urlencode($event->map).'&ie=UTF8&output=embed"></iframe>';
						}
						$html .= '
							<div class="dp_pec_date_event_icons">';
						$html .= $this->getEventShare($event);	
						if($event->link != '') {
							$html .= '
							<a class="dp_pec_date_event_link" href="'.$event->link.'" target="_blank"></a>';
						}
						$html .= '
							</div>';
				$html .= '
						</div>
					</div>';				
			} else {
			
				$html .= '
				<div class="dp_pec_date_event '.($search ? 'dp_pec_date_eventsearch' : '').' dp_pec_isotope">';
				if($post_thumbnail_id) {
					$html .= '<div class="dp_pec_event_photo">';
					$html .= '<img src="'.$image_attributes[0].'" alt="" />';
					$html .= '</div>';
				}
				
				$edit_button = $this->getEditRemoveButtons($event);
				/*
				if(is_user_logged_in() && get_post_meta($event->id, 'pec_enable_booking', true) || $this->calendar_obj->booking_enable) {
					if($this->userHasBooking($selected_date, $event->id)) {
						$edit_button .= '<a href="javascript:void(0);" class="pec_book_event" data-event-date="'.$selected_date.'" data-event-id="'.$event->id.'">'.$this->translation['TXT_BOOK_EVENT_REMOVE'].'</a>';
					} else {
						$edit_button .= '<a href="javascript:void(0);" class="pec_book_event" data-event-date="'.$selected_date.'" data-event-id="'.$event->id.'">'.$this->translation['TXT_BOOK_EVENT'].'</a>';
					}
				}*/
				
				$html .= '
					<h1 class="dp_pec_event_title">'.$title.$edit_button.'</h1><div class="dp_pec_clear"></div>
					';
						
					if($this->calendar_obj->show_author) {
						$author = get_userdata(get_post_field( 'post_author', $event->id ));
						$html .= '<span class="pec_author">'.$this->translation['TXT_BY'].' '.$author->display_name.'</span>';
					}
				
				$all_working_days = '';
				if($event->pec_daily_working_days && $event->recurring_frecuency == 1) {
					$all_working_days = $this->translation['TXT_ALL_WORKING_DAYS'];
				}
				
				if($this->calendar_obj->show_time && !$event->hide_time) {
					if($search) {
						$html .= '<span class="dp_pec_date_event_time">'.$start_date.$end_date.'<br>'.$all_working_days. ' '. $time.'</span>';
					} else {
						$html .= '<span class="dp_pec_date_event_time">'.$time.$end_time.'</span>';
					}
				} else {
					if($search) {
						$html .= '<span class="dp_pec_date_event_time">'.$start_date.$end_date.'<br>'.$all_working_days.'</span>';
					}
				}
				if($event->location != '') {
					$html .= '
					<span class="dp_pec_event_location">'.$event->location.'</span>';
				}
				
				if($event->phone != '') {
					$html .= '
					<span class="dp_pec_event_phone">'.$event->phone.'</span>';
				}
				
				$category = get_the_terms( $event->id, 'pec_events_category' ); 
				if(!empty($category)) {
					$category_count = 0;
					$html .= '
						<span class="dp_pec_event_location">';
					if($event->location != '') {
						$html .= '<br>';	
					}
					foreach ( $category as $cat){
						if($category_count > 0) {
							$html .= " / ";	
						}
						$html .= $cat->name;
						$category_count++;
					}
					$html .= '
						</span>';
				}
				$html .= '
					<p class="dp_pec_event_description">
					';
				if($event->map != '') {
					$html .= '
					<iframe class="dp_pec_date_event_map_iframe" width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&q='.urlencode($event->map).'&ie=UTF8&output=embed"></iframe>';
				}
					$html .= '
						'.do_shortcode(nl2br(strip_tags(str_replace("</p>", "<br />", preg_replace("/<p[^>]*?>/", "", $event->description)), '<iframe><strong><h1><h2><h3><h4><h5><h6><h7><b><i><em><pre><code><del><ins><img><a><ul><li><ol><blockquote><br><hr><span><p>')));
					
					$html .= '</p>';
					$html .= $this->getRating($event->id);
					$html .= '
						<div class="dp_pec_date_event_icons">';
					$html .= $this->getEventShare($event);
					if($event->link != '') {
						$html .= '
						<a class="dp_pec_date_event_link" href="'.$event->link.'" target="_blank"></a>';
					}
					/*
					if($event->map != '') {
						$html .= '
						
						<a class="dp_pec_date_event_map" href="javascript:void(0);"></a>';
					}
					*/
					$html .= '
						</div>';
					if($this->calendar_obj->ical_active && !$search) {
						$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/ical_event.php?event_id='.$event->id.'&date='.strtotime($selected_date) ) . "'>iCal</a><br class='clear' />";
					}
	
					$html .= '
				</div>';
			}
		}
		
		return $html;
	}
	
	function upcomingCalendarLayout( $return_data = false, $limit = '', $limit_description = '', $events_month = null, $events_month_end = null, $show_end_date = true, $filter_author = false, $auto_limit = true, $filter_map = false ) {
		global $wpdb;
		
		$html = "";
		
		if(is_numeric($limit)) {
			$this->limit = $limit;	
		}
		
		if(is_numeric($limit_description)) {
			$this->limit_description = $limit_description;	
		}
		$meta_key = "SELECT meta_value FROM ".$wpdb->postmeta." WHERE post_id = p.ID AND meta_key";
		$querystr = "
		SELECT 	p.ID as id, 
				p.post_title as title, 
				p.post_content as description, 
				(".$meta_key." = 'pec_id_calendar' LIMIT 1) as id_calendar, 
				(".$meta_key." = 'pec_date' LIMIT 1) as date, 
				(".$meta_key." = 'pec_all_day' LIMIT 1) as all_day, 
				(".$meta_key." = 'pec_daily_working_days' LIMIT 1) as pec_daily_working_days, 
				(".$meta_key." = 'pec_daily_every' LIMIT 1) as pec_daily_every,
				(".$meta_key." = 'pec_weekly_every' LIMIT 1) as pec_weekly_every,
				(".$meta_key." = 'pec_weekly_day' LIMIT 1) as pec_weekly_day, 
				(".$meta_key." = 'pec_monthly_every' LIMIT 1) as pec_monthly_every, 
				(".$meta_key." = 'pec_monthly_position' LIMIT 1) as pec_monthly_position, 
				(".$meta_key." = 'pec_monthly_day' LIMIT 1) as pec_monthly_day, 
				(".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) as recurring_frecuency, 
				(".$meta_key." = 'pec_end_date' LIMIT 1) as end_date, 
				(".$meta_key." = 'pec_link' LIMIT 1) as link, 
				(".$meta_key." = 'pec_share' LIMIT 1) as share, 
				(".$meta_key." = 'pec_map' LIMIT 1) as map, 
				(".$meta_key." = 'pec_end_time_hh' LIMIT 1) as end_time_hh, 
				(".$meta_key." = 'pec_end_time_mm' LIMIT 1) as end_time_mm, 
				(".$meta_key." = 'pec_hide_time' LIMIT 1) as hide_time, 
				(".$meta_key." = 'pec_location' LIMIT 1) as location, 
				(".$meta_key." = 'pec_phone' LIMIT 1) as phone
		FROM ".$wpdb->posts." p
		WHERE 
		";
		if(isset($events_month)) {
			$querystr .= "
			(((".$meta_key." = 'pec_date' LIMIT 1) BETWEEN '".$events_month."' AND '".$events_month_end."') OR 
			((".$meta_key." = 'pec_end_date' LIMIT 1) BETWEEN '".$events_month."' AND '".$events_month_end."') 
			OR ((".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) > 0)) ";
		} else {
			$querystr .= "
			(((".$meta_key." = 'pec_date' LIMIT 1) >= '".date("Y-m-d h:i:s")."' OR ((".$meta_key." = 'pec_recurring_frecuency' LIMIT 1) > 0) AND 
			(((".$meta_key." = 'pec_end_date' LIMIT 1) >= '".date("Y-m-d h:i:s")."' OR (".$meta_key." = 'pec_end_date' LIMIT 1) = '0000-00-00' OR (".$meta_key." = 'pec_end_date' LIMIT 1) = '')))) ";
		}
		if(!empty($this->category)) {
			$querystr .= "
			AND (
				select count(*)
				from ".$wpdb->terms." wt
				inner join ".$wpdb->term_taxonomy." wtt on wt.term_id = wtt.term_id
				inner join ".$wpdb->term_relationships." wpr on wpr.term_taxonomy_id = wtt.term_taxonomy_id
				inner join ".$wpdb->posts." p2 on p2.ID = wpr.object_id
				where wtt.taxonomy= 'pec_events_category' and wt.term_id IN( ".$this->category." ) and p2.ID = p.ID
			) ";
		}
		
		if(!empty($this->event_id)) {
			$querystr .= "
			AND p.ID = ".$this->event_id;
		}
		
		if($filter_author) {
			
			if(is_author()) {
				global $author_name, $author;
				$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			} else {
				$curauth = get_userdata(intval($author));
			}
			
			if(is_numeric($this->author) && $this->author > 0) {			
				$querystr .= "
				AND p.post_author = ".$this->author."
				";
			} else {
				$querystr .= "
				AND p.post_author = ".$curauth->ID."
				";
			}
		}
		if($filter_map) {
			$querystr .= "
		AND (".$meta_key." = 'pec_map' LIMIT 1) <> '' ";
		}
		$querystr .= "
		AND ((".$meta_key." = 'pec_id_calendar' LIMIT 1) = ".$this->id_calendar." OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar."' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '%,".$this->id_calendar.",%' OR (".$meta_key." = 'pec_id_calendar' LIMIT 1) LIKE '".$this->id_calendar.",%') AND p.post_status = 'publish'
		ORDER BY (".$meta_key." = 'pec_date' LIMIT 1) ASC";

		$events_obj = $wpdb->get_results($querystr);
		
		if(count($events_obj) == 0) {
			$html .= '
			<div class="dp_pec_date_event dp_pec_isotope">
				<p class="dp_pec_event_no_events">'.$this->translation['TXT_NO_EVENTS_FOUND'].'</p>
			</div>';
			
		} else {
			
			$order_events = array();
			foreach($events_obj as $event) {
				
				if($event->recurring_frecuency > 0) {
					
					$enddate_orig = $event->end_date." 23:59:59";
					if($event->all_day) {
						$startdate_orig = date('Y-m-d'). " 00:00:00";
					} else {
						$startdate_orig = date('Y-m-d h:i:s');	
					}
					if(!is_null($events_month)) {
						$startdate_orig = $events_month;
						$enddate_orig = $events_month_end;
					}
					switch($event->recurring_frecuency) {
						case 1:
							
							for($i = 1; $i <= $this->limit; $i++) {
								
								$eventdate = date("Y-m-d H:i:s", mktime(date("H", strtotime($event->date)), date("i", strtotime($event->date)), 0, date("m", strtotime($event->date)), date("d", strtotime($event->date)) - 1 +$i, date("y", strtotime($event->date))));
								if(((strtotime($eventdate) <= strtotime($enddate_orig) && strtotime($eventdate) <= strtotime($event->end_date)) || $event->end_date == '0000-00-00') && (strtotime($eventdate) >= strtotime($startdate_orig) && strtotime($eventdate) <= strtotime($enddate_orig))) {
									$order_events[strtotime($eventdate).$event->id] = new stdClass;
									$order_events[strtotime($eventdate).$event->id]->id = $event->id;
									$order_events[strtotime($eventdate).$event->id]->recurring_frecuency = $event->recurring_frecuency;
									$order_events[strtotime($eventdate).$event->id]->date = $eventdate;
									$order_events[strtotime($eventdate).$event->id]->start_date = $event->date;
									$order_events[strtotime($eventdate).$event->id]->end_date = $event->end_date;
									$order_events[strtotime($eventdate).$event->id]->end_time_hh = $event->end_time_hh;
									$order_events[strtotime($eventdate).$event->id]->end_time_mm = $event->end_time_mm;
									$order_events[strtotime($eventdate).$event->id]->title = $event->title;
									$order_events[strtotime($eventdate).$event->id]->description = $event->description;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->hide_time = $event->hide_time;
									$order_events[strtotime($eventdate).$event->id]->location = $event->location;
									$order_events[strtotime($eventdate).$event->id]->phone = $event->phone;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->map = $event->map;
									$order_events[strtotime($eventdate).$event->id]->share = $event->share;
									$order_events[strtotime($eventdate).$event->id]->all_day = $event->all_day;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_working_days = $event->pec_daily_working_days;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_every = $event->pec_daily_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_every = $event->pec_weekly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_day = $event->pec_weekly_day;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_every = $event->pec_monthly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_position = $event->pec_monthly_position;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_day = $event->pec_monthly_day;
								}
							}
							break;
						case 2:
							
							if($event->pec_weekly_day != "") {
								$last_day = date("Y-m-d H:i:s", mktime(date("H", strtotime($event->date)), date("i", strtotime($event->date)), 0, date("m", strtotime($event->date)), date("d", strtotime($event->date)), date("y", strtotime($event->date))) - 86400);
								$original_date = $event->date;
								$original_date = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($original_date)));
								
								for($i = 1; $i <= $this->limit; $i++) {
								
									
									foreach(unserialize($event->pec_weekly_day) as $week_day) {
										$day = "";
										
										switch($week_day) {
											case 1:
												$day = "Monday";
												break;	
											case 2:
												$day = "Tuesday";
												break;	
											case 3:
												$day = "Wednesday";
												break;	
											case 4:
												$day = "Thursday";
												break;	
											case 5:
												$day = "Friday";
												break;	
											case 6:
												$day = "Saturday";
												break;	
											case 7:
												$day = "Sunday";
												break;	
										}
										
										
										$event->date = date("Y-m-d H:i:s", strtotime("next ".$day, strtotime($original_date)));
										
										$eventdate = date("Y-m-d", strtotime($last_day.' next '.date("l", strtotime($event->date))));
										$eventdate = date("Y-m-d H:i:s", mktime(date("H", strtotime($last_day)), date("i", strtotime($last_day)), 0, date("m", strtotime($eventdate)), date("d", strtotime($eventdate)), date("y", strtotime($eventdate))));
										$last_day = $eventdate;
										
										if(strtotime(($eventdate)) < strtotime($startdate_orig)) {
											$i--;
											continue;	
										}
										//echo "Event Date " . $eventdate;
										//echo " Event Date Orig " . $event->date.'<br><br>';
										
										if($event->pec_weekly_every > 1 && 
											( ((strtotime(substr($eventdate,0,11)) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % ($event->pec_weekly_every * 7) != 0 )
										) {
											continue;
										}
										
										if(isset($events_month) && strtotime(($eventdate)) > strtotime($enddate_orig)) {
											break;	
										}
		
										if(
											(
												(strtotime($eventdate) <= strtotime($enddate_orig) && strtotime($eventdate) <= strtotime($event->end_date)) 
												|| $event->end_date == '0000-00-00' 
												|| $event->end_date == ''
											) 
											&& (strtotime($eventdate) >= strtotime($startdate_orig))
										) {
											$order_events[strtotime($eventdate).$event->id] = new stdClass;
											$order_events[strtotime($eventdate).$event->id]->id = $event->id;
											$order_events[strtotime($eventdate).$event->id]->recurring_frecuency = $event->recurring_frecuency;
											$order_events[strtotime($eventdate).$event->id]->date = $eventdate;
											$order_events[strtotime($eventdate).$event->id]->end_date = $event->end_date;
											$order_events[strtotime($eventdate).$event->id]->end_time_hh = $event->end_time_hh;
											$order_events[strtotime($eventdate).$event->id]->end_time_mm = $event->end_time_mm;
											$order_events[strtotime($eventdate).$event->id]->title = $event->title;
											$order_events[strtotime($eventdate).$event->id]->description = $event->description;
											$order_events[strtotime($eventdate).$event->id]->link = $event->link;
											$order_events[strtotime($eventdate).$event->id]->hide_time = $event->hide_time;
											$order_events[strtotime($eventdate).$event->id]->location = $event->location;
											$order_events[strtotime($eventdate).$event->id]->phone = $event->phone;
											$order_events[strtotime($eventdate).$event->id]->link = $event->link;
											$order_events[strtotime($eventdate).$event->id]->map = $event->map;
											$order_events[strtotime($eventdate).$event->id]->share = $event->share;
											$order_events[strtotime($eventdate).$event->id]->all_day = $event->all_day;
											$order_events[strtotime($eventdate).$event->id]->pec_daily_working_days = $event->pec_daily_working_days;
											$order_events[strtotime($eventdate).$event->id]->pec_daily_every = $event->pec_daily_every;
											$order_events[strtotime($eventdate).$event->id]->pec_weekly_every = $event->pec_weekly_every;
											$order_events[strtotime($eventdate).$event->id]->pec_weekly_day = $event->pec_weekly_day;
											$order_events[strtotime($eventdate).$event->id]->pec_monthly_every = $event->pec_monthly_every;
											$order_events[strtotime($eventdate).$event->id]->pec_monthly_position = $event->pec_monthly_position;
											$order_events[strtotime($eventdate).$event->id]->pec_monthly_day = $event->pec_monthly_day;
										}
										
									}
								}
							} else {
							$last_day = date("Y-m-d H:i:s", mktime(date("H", strtotime($event->date)), date("i", strtotime($event->date)), 0, date("m", strtotime($event->date)), date("d", strtotime($event->date)), date("y", strtotime($event->date))) - 86400);
							for($i = 1; $i <= $this->limit; $i++) {
							
								$eventdate = date("Y-m-d", strtotime($last_day.' next '.date("l", strtotime($event->date))));
								$eventdate = date("Y-m-d H:i:s", mktime(date("H", strtotime($last_day)), date("i", strtotime($last_day)), 0, date("m", strtotime($eventdate)), date("d", strtotime($eventdate)), date("y", strtotime($eventdate))));
								$last_day = $eventdate;
								
								if(strtotime(($eventdate)) < strtotime($startdate_orig)) {
									$i--;
									continue;	
								}
								
								if($event->pec_weekly_every > 1 && 
									( ((strtotime(substr($eventdate,0,11)) - strtotime(substr($event->date,0,11))) / (60 * 60 * 24)) % ($event->pec_weekly_every * 7) != 0 )
								) {
									continue;
								}
										
								if(isset($events_month) && strtotime(($eventdate)) > strtotime($enddate_orig)) {
									break;	
								}

								if(
									(
										(strtotime($eventdate) <= strtotime($enddate_orig) && strtotime($eventdate) <= strtotime($event->end_date)) 
										|| $event->end_date == '0000-00-00' 
										|| $event->end_date == ''
									) 
									&& (strtotime($eventdate) >= strtotime($startdate_orig))
								) {
									$order_events[strtotime($eventdate).$event->id] = new stdClass;
									$order_events[strtotime($eventdate).$event->id]->id = $event->id;
									$order_events[strtotime($eventdate).$event->id]->recurring_frecuency = $event->recurring_frecuency;
									$order_events[strtotime($eventdate).$event->id]->date = $eventdate;
									$order_events[strtotime($eventdate).$event->id]->end_date = $event->end_date;
									$order_events[strtotime($eventdate).$event->id]->end_time_hh = $event->end_time_hh;
									$order_events[strtotime($eventdate).$event->id]->end_time_mm = $event->end_time_mm;
									$order_events[strtotime($eventdate).$event->id]->title = $event->title;
									$order_events[strtotime($eventdate).$event->id]->description = $event->description;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->hide_time = $event->hide_time;
									$order_events[strtotime($eventdate).$event->id]->location = $event->location;
									$order_events[strtotime($eventdate).$event->id]->phone = $event->phone;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->map = $event->map;
									$order_events[strtotime($eventdate).$event->id]->share = $event->share;
									$order_events[strtotime($eventdate).$event->id]->all_day = $event->all_day;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_working_days = $event->pec_daily_working_days;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_every = $event->pec_daily_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_every = $event->pec_weekly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_day = $event->pec_weekly_day;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_every = $event->pec_monthly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_position = $event->pec_monthly_position;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_day = $event->pec_monthly_day;
								}
							}
							
							}
							break;
						case 3:
							$counter_m = 1;
							if(isset($events_month)) {
								$counter_m = 0;	
							}
							for($i = 1; $i <= $this->limit; $i++) {
								$eventdate = date("Y-m-d H:i:s", mktime(date("H", strtotime($event->date)), date("i", strtotime($event->date)), 0, date("m")+((strtotime($event->date) < time() && !isset($events_month)) || $i > 1 ? $counter_m : 0), date("d", strtotime($event->date)), date("y", strtotime($event->date))));
								
								
								if($event->pec_monthly_every > 1 && 
									( !is_int (((date('m', strtotime($eventdate)) - date('m', strtotime(substr($event->date,0,11))))) / ($event->pec_monthly_every)) )
								) {
									continue;
								}
								//$html .= $event->pec_monthly_day. " - " .$event->pec_monthly_position;
								if($event->pec_monthly_day != "" && $event->pec_monthly_position != "") {
					
									$eventdate = str_replace(substr($eventdate, 8, 2), date('d', strtotime($event->pec_monthly_position.' '.$event->pec_monthly_day.' of '.date("F Y", strtotime($eventdate)))), $eventdate);
									//$html .= $eventdate."XXX";

								}
								
								if(isset($events_month) && strtotime(($eventdate)) > strtotime($enddate_orig)) {
									break;	
								}
								
								if(strtotime($event->date) < time() || $i > 1) {
									$counter_m++;
								}
								if(((strtotime($eventdate) <= strtotime($enddate_orig) && strtotime($eventdate) <= strtotime($event->end_date)) || $event->end_date == '0000-00-00' || $event->end_date == '') && (strtotime($eventdate) >= strtotime($startdate_orig))) {
									$order_events[strtotime($eventdate).$event->id] = new stdClass;
									$order_events[strtotime($eventdate).$event->id]->id = $event->id;
									$order_events[strtotime($eventdate).$event->id]->recurring_frecuency = $event->recurring_frecuency;									
									$order_events[strtotime($eventdate).$event->id]->date = $eventdate;
									$order_events[strtotime($eventdate).$event->id]->end_date = $event->end_date;
									$order_events[strtotime($eventdate).$event->id]->end_time_hh = $event->end_time_hh;
									$order_events[strtotime($eventdate).$event->id]->end_time_mm = $event->end_time_mm;
									$order_events[strtotime($eventdate).$event->id]->title = $event->title;
									$order_events[strtotime($eventdate).$event->id]->description = $event->description;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->hide_time = $event->hide_time;
									$order_events[strtotime($eventdate).$event->id]->location = $event->location;
									$order_events[strtotime($eventdate).$event->id]->phone = $event->phone;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->map = $event->map;
									$order_events[strtotime($eventdate).$event->id]->share = $event->share;
									$order_events[strtotime($eventdate).$event->id]->all_day = $event->all_day;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_working_days = $event->pec_daily_working_days;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_every = $event->pec_daily_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_every = $event->pec_weekly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_day = $event->pec_weekly_day;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_every = $event->pec_monthly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_position = $event->pec_monthly_position;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_day = $event->pec_monthly_day;
								}
							}
							break;	
						case 4:
							$counter_y = 1;
							if(isset($events_month)) {
								$counter_y = 0;	
							}
							for($i = 1; $i <= $this->limit; $i++) {
								$eventdate = date("Y-m-d H:i:s", mktime(date("H", strtotime($event->date)), date("i", strtotime($event->date)), 0, date("m", strtotime($event->date)), date("d", strtotime($event->date)), date("y")+((strtotime($event->date) < time() && !isset($events_month)) || $i > 1 ? $counter_y : 0)));
								
								if(strtotime(($eventdate)) > strtotime($enddate_orig)) {
									$i--;
									break;	
								}
								
								if(isset($events_month) && strtotime(($eventdate)) > strtotime($enddate_orig)) {
									break;	
								}
								
								if(strtotime($event->date) < time() || $i > 1) {
									$counter_y++;
								}
								if(((strtotime($eventdate) <= strtotime($enddate_orig) && strtotime($eventdate) <= strtotime($event->end_date)) || $event->end_date == '0000-00-00' || $event->end_date == '') && (strtotime($eventdate) >= strtotime($startdate_orig))) {
									$order_events[strtotime($eventdate).$event->id] = new stdClass;
									$order_events[strtotime($eventdate).$event->id]->id = $event->id;
									$order_events[strtotime($eventdate).$event->id]->recurring_frecuency = $event->recurring_frecuency;									
									$order_events[strtotime($eventdate).$event->id]->date = $eventdate;
									$order_events[strtotime($eventdate).$event->id]->end_date = $event->end_date;
									$order_events[strtotime($eventdate).$event->id]->end_time_hh = $event->end_time_hh;
									$order_events[strtotime($eventdate).$event->id]->end_time_mm = $event->end_time_mm;
									$order_events[strtotime($eventdate).$event->id]->title = $event->title;
									$order_events[strtotime($eventdate).$event->id]->description = $event->description;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->hide_time = $event->hide_time;
									$order_events[strtotime($eventdate).$event->id]->location = $event->location;
									$order_events[strtotime($eventdate).$event->id]->phone = $event->phone;
									$order_events[strtotime($eventdate).$event->id]->link = $event->link;
									$order_events[strtotime($eventdate).$event->id]->map = $event->map;
									$order_events[strtotime($eventdate).$event->id]->share = $event->share;
									$order_events[strtotime($eventdate).$event->id]->all_day = $event->all_day;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_working_days = $event->pec_daily_working_days;
									$order_events[strtotime($eventdate).$event->id]->pec_daily_every = $event->pec_daily_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_every = $event->pec_weekly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_weekly_day = $event->pec_weekly_day;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_every = $event->pec_monthly_every;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_position = $event->pec_monthly_position;
									$order_events[strtotime($eventdate).$event->id]->pec_monthly_day = $event->pec_monthly_day;
								}
							}
							break;
					}
					
				} else {
					
					$enddate_orig = $event->end_date." 23:59:59";
					if($event->all_day) {
						$startdate_orig = date('Y-m-d'). " 00:00:00";
					} else {
						$startdate_orig = date('Y-m-d h:i:s');	
					}
					if(!is_null($events_month)) {
						$startdate_orig = $events_month;
						$enddate_orig = $events_month_end;
					}
					
					if(strtotime(($event->date)) < strtotime($startdate_orig)) {
						continue;	
					}
					
					$order_events[strtotime($event->date).$event->id] = $event;
				}
			}
			
			if(!function_exists('dp_pec_cmp')) {
				function dp_pec_cmp($a, $b) {
					$a = strtotime($a->date);
					$b = strtotime($b->date);
					if ($a == $b) {
						return 0;
					}
					return ($a < $b) ? -1 : 1;
				}
			}
			
			usort($order_events, "dp_pec_cmp");
			
			//ksort($order_events, SORT_NUMERIC);
			if($return_data) {
				if($limit != '' && $auto_limit) { $order_events = array_slice($order_events, 0, $limit); }
				return $order_events;
			}
			
			$event_counter = 1;
			
			if(empty($order_events)) {
				$html .= '
				<div class="dp_pec_date_event dp_pec_isotope">
					<p class="dp_pec_event_no_events">'.$this->translation['TXT_NO_EVENTS_FOUND'].'</p>
				</div>';
			} else {
				$event_reg = array();
				
				foreach($order_events as $event) {

					if($event_counter > $this->limit) { break; }
					
					if($event_counter == 1 && $this->calendar_obj->ical_active && is_null($events_month)) {
						$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/ical.php?calendar_id='.$this->id_calendar ) . "'>iCal</a>";
					}
					if($event_counter == 1 && $this->calendar_obj->rss_active && is_null($events_month)) {
						$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/rss.php?calendar_id='.$this->id_calendar ) . "'>RSS</a>";
					}
					if($event_counter == 1 && $this->calendar_obj->subscribe_active && is_null($events_month)) {
						$html .= "<a class='dpProEventCalendar_feed dpProEventCalendar_subscribe' href='javascript:void(0);'>".$this->translation['TXT_SUBSCRIBE']."</a>";
					}
					
					$html .= "<div class='clear'></div>";
					
					if($this->calendar_obj->format_ampm) {
						$time = date('h:i A', strtotime($event->date));
					} else {
						$time = date('H:i', strtotime($event->date));				
					}
					$start_day = date('d', strtotime($event->date));
					$start_month = date('n', strtotime($event->date));
					
					$end_date = '';
					if($event->end_date != "" && $event->end_date != "0000-00-00" && $show_end_date) {
						$end_day = date('d', strtotime($event->end_date));
						$end_month = date('n', strtotime($event->end_date));
						
						//$end_date = ' / <br />'.$end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3);
						$end_date = ' - '.date_i18n(get_option('date_format'), strtotime($event->end_date));
					}
					
					//$start_date = $start_day.' '.substr($this->translation['MONTHS'][($start_month - 1)], 0, 3);
					$start_date = date_i18n(get_option('date_format'), strtotime($event->date));
					
					if($start_date == $end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3)) { $end_date = ""; }
					if($event->recurring_frecuency != 1) {
						$end_date = "";
					} elseif(in_array($event->id, $event_reg)) {
						continue;	
					}
					
					$end_time = "";
					if($event->end_time_hh != "" && $event->end_time_mm != "") { $end_time = str_pad($event->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($event->end_time_mm, 2, "0", STR_PAD_LEFT); }
					
					if($end_time != "") {
						
						if($this->calendar_obj->format_ampm) {
							$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
						} else {
							$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
						}
						$end_time = " - ".$end_time_tmp;
						if($end_time_tmp == $time) {
							$end_time = "";	
						}
					}
					
					if($event->all_day) {
						$time = $this->translation['TXT_ALL_DAY'];
						$end_time = "";
					}
					
					$html .= '
					<div class="dp_pec_date_event dp_pec_upcoming dp_pec_isotope">';
					
					$post_thumbnail_id = get_post_thumbnail_id( $event->id );
					$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
					if($post_thumbnail_id) {
						$html .= '<div class="dp_pec_event_photo">';
						$html .= '<img src="'.$image_attributes[0].'" alt="" />';
						$html .= '</div>';
					}
					
					//<a href="'.get_permalink($event->id).'"></a>
					$title = '<span class="dp_pec_event_title_sp">'.$event->title.'</span>';
					if($this->calendar_obj->link_post) {
						$title = '<a href="'.get_permalink($event->id).'">'.$title.'</a>';	
					}
					
					$edit_button = $this->getEditRemoveButtons($event);
					
					$html .= '
						<h1 class="dp_pec_event_title">'.$title.$edit_button.'</h1><div class="dp_pec_clear"></div>';
				
					if($this->calendar_obj->show_author) {
						$author = get_userdata(get_post_field( 'post_author', $event->id ));
						$html .= '<span class="pec_author">'.$this->translation['TXT_BY'].' '.$author->display_name.'</span>';
					}

					$all_working_days = '';
					if($event->pec_daily_working_days && $event->recurring_frecuency == 1) {
						$all_working_days = $this->translation['TXT_ALL_WORKING_DAYS'];
					}
				
					$html .= '
						<span class="dp_pec_date_event_time">'.$start_date.$end_date.'<br>'.$all_working_days;
					if(($this->calendar_obj->show_time && !$event->hide_time) || $event->all_day) {
						$html .= $time.$end_time;
					}
					$html .= '</span>';
					
					if($event->location != '') {
						$html .= '
						<span class="dp_pec_event_location">'.$event->location.'</span>';
					}
					
					if($event->phone != '') {
						$html .= '
						<span class="dp_pec_event_phone">'.$event->phone.'</span>';
					}
					
					$category = get_the_terms( $event->id, 'pec_events_category' ); 
					if(!empty($category)) {
						$category_count = 0;
						$html .= '
							<span class="dp_pec_event_location">';
						if($event->location != '') {
							$html .= '<br>';	
						}
						foreach ( $category as $cat){
							if($category_count > 0) {
								$html .= " / ";	
							}
							$html .= $cat->name;
							$category_count++;
						}
						$html .= '
							</span>';
					}
					
					$event_desc = nl2br(strip_tags(str_replace("</p>", "<br />", preg_replace("/<p[^>]*?>/", "", $event->description)), '<iframe><strong><h1><h2><h3><h4><h5><h6><h7><b><i><em><pre><code><del><ins><img><a><ul><li><ol><blockquote><br><hr><span><p>'));
					$html .= '
						<p class="dp_pec_event_description">
							'.do_shortcode(( is_numeric($this->limit_description) && $this->limit_description > 0 ? pec_truncateHtml($event_desc, $this->limit_description) : $event_desc )).'
						</p>';
						
					$html .= $this->getRating($event->id);
					
					$html .= '
						<div class="dp_pec_date_event_icons">';
					
					$html .= $this->getEventShare($event);
					
					if($event->link != '') {
						$html .= '
						<a class="dp_pec_date_event_link" href="'.$event->link.'" target="_blank"></a>';
					}
					$html .= '
						</div>';
					$html .= '
					</div>';
					$event_reg[] = $event->id;
					$event_counter++;
				}	
			}
		}

		return $html;
	}
	
	function parseMysqlDate($date) {
		
		$dateArr = explode("-", $date);
		//$newDate = $dateArr[2] . " " . $this->translation['MONTHS'][($dateArr[1] - 1)] . ", " . $dateArr[0];
		$newDate = date_i18n(get_option('date_format'), strtotime($date));
		return $newDate;
	}
	
	function addScripts( $print = false ) 
	{

		$script='<script type="text/javascript">
		// <![CDATA[
		jQuery(document).ready(function() {
			
			function startProEventCalendar() {
				
				jQuery("#dp_pec_id'.$this->nonce.'").dpProEventCalendar({
					nonce: "dp_pec_id'.$this->nonce.'", 
					monthNames: new Array("'.$this->translation['MONTHS'][0].'", "'.$this->translation['MONTHS'][1].'", "'.$this->translation['MONTHS'][2].'", "'.$this->translation['MONTHS'][3].'", "'.$this->translation['MONTHS'][4].'", "'.$this->translation['MONTHS'][5].'", "'.$this->translation['MONTHS'][6].'", "'.$this->translation['MONTHS'][7].'", "'.$this->translation['MONTHS'][8].'", "'.$this->translation['MONTHS'][9].'", "'.$this->translation['MONTHS'][10].'", "'.$this->translation['MONTHS'][11].'"), ';
				if($this->is_admin) {
					$script .= '
					draggable: false,
					isAdmin: true,
					';
				}
				if(!empty($this->event_id)) {
					$script .= '
					draggable: false,
					';
				}
				if(is_numeric($this->id_calendar)) {
					$script .= '
					calendar: '.$this->id_calendar.',
					';	
				}
				if(isset($this->calendar_obj->date_range_start) && $this->calendar_obj->date_range_start != NULL && !$this->is_admin && empty($this->event_id)) {
					$script .= '
					dateRangeStart: "'.$this->calendar_obj->date_range_start.'",
					';	
				}
				if(isset($this->calendar_obj->date_range_end) && $this->calendar_obj->date_range_end != NULL && !$this->is_admin && empty($this->event_id)) {
					$script .= '
					dateRangeEnd: "'.$this->calendar_obj->date_range_end.'",
					';	
				}
				if(isset($this->calendar_obj->skin) && $this->calendar_obj->skin != "" && !$this->is_admin && empty($this->event_id)) {
					$script .= '
					skin: "'.$this->calendar_obj->skin.'",
					';	
				}
				if(isset($this->type)) {
					$script .= '
					type: "'.$this->type.'",
					';	
				}
				$script .= '
					allow_user_add_event: "'.$this->calendar_obj->allow_user_add_event.'",
					actualMonth: '.$this->datesObj->currentMonth.',
					actualYear: '.$this->datesObj->currentYear.',
					actualDay: '.$this->datesObj->currentDate.',
					defaultDate: '.$this->defaultDate.',
					category: "'.($this->category != "" ? $this->category : '').'",
					event_id: "'.($this->event_id != "" ? $this->event_id : '').'",
					lang_sending: "'.$this->calendar_obj->lang_txt_sending.'",
					lang_subscribe: "'.$this->calendar_obj->lang_txt_subscribe.'",
					lang_subscribe_subtitle: "'.$this->calendar_obj->lang_txt_subscribe_subtitle.'",
					lang_edit_event: "'.$this->calendar_obj->lang_txt_edit_event.'",
					lang_remove_event: "'.$this->calendar_obj->lang_txt_remove_event.'",
					lang_your_name: "'.$this->calendar_obj->lang_txt_your_name.'",
					lang_your_email: "'.$this->calendar_obj->lang_txt_your_email.'",
					lang_fields_required: "'.$this->calendar_obj->lang_txt_fields_required.'",
					lang_invalid_email: "'.$this->calendar_obj->lang_txt_invalid_email.'",
					lang_txt_subscribe_thanks: "'.$this->calendar_obj->lang_txt_subscribe_thanks.'",
					lang_book_event: "'.$this->calendar_obj->lang_txt_book_event.'",
					view: "'.($this->is_admin || $this->type == "upcoming" || !empty($this->event_id) ? 'monthly' : $this->calendar_obj->view).'"
				});
				';
				if(($this->calendar_obj->allow_user_add_event || $this->type == 'add-event') && !$this->is_admin && empty($this->event_id)) {
					$script .= '
					jQuery( ".dp_pec_date_input, .dp_pec_end_date_input", "#dp_pec_id'.$this->nonce.'" ).datepicker({
						beforeShow: function(input, inst) {
						   jQuery("#ui-datepicker-div").removeClass("dp_pec_datepicker");
						   jQuery("#ui-datepicker-div").addClass("dp_pec_datepicker");
					   },
						showOn: "button",
						buttonImage: "'.dpProEventCalendar_plugin_url( 'images/admin/calendar.png' ).'",
						buttonImageOnly: false,
						minDate: 0,
						dateFormat: "yy-mm-dd"
					});
					
					jQuery( document ).on("click", ".pec_edit_event", function() {
						
						jQuery(".dp_pec_date_input_modal, .dp_pec_end_date_input_modal", ".dpProEventCalendarModalEditEvent").datepicker({
							beforeShow: function(input, inst) {
							   jQuery("#ui-datepicker-div").removeClass("dp_pec_datepicker");
							   jQuery("#ui-datepicker-div").addClass("dp_pec_datepicker");
						   },
							showOn: "button",
							buttonImage: "'.dpProEventCalendar_plugin_url( 'images/admin/calendar.png' ).'",
							buttonImageOnly: false,
							minDate: 0,
							dateFormat: "yy-mm-dd"
						});
						
					});
					';
				}
				if(!$this->is_admin && empty($this->event_id)) {
					$script .= '
					jQuery("input, textarea", "#dp_pec_id'.$this->nonce.'").placeholder();';
				}
				$script .= '
			}
			
			if(jQuery("#dp_pec_id'.$this->nonce.'").parent().css("display") == "none") {
				jQuery("#dp_pec_id'.$this->nonce.'").parent().onShowProCalendar(function(){
					startProEventCalendar();
				});
				return;
			}
						
			startProEventCalendar();
		});
		
		jQuery(window).resize(function(){
			if(jQuery(".dp_pec_layout", "#dp_pec_id'.$this->nonce.'").width() != null) {
	
				var instance = jQuery("#dp_pec_id'.$this->nonce.'");
				
				if(instance.width() < 500) {
					jQuery(instance).addClass("dp_pec_400");
	
					jQuery(".dp_pec_dayname span", instance).each(function(i) {
						jQuery(this).html(jQuery(this).html().substr(0,3));
					});
					
					jQuery(".prev_month strong", instance).hide();
					jQuery(".next_month strong", instance).hide();
					jQuery(".prev_day strong", instance).hide();
					jQuery(".next_day strong", instance).hide();
					
				} else {
					jQuery(instance).removeClass("dp_pec_400");
					jQuery(".prev_month strong", instance).show();
					jQuery(".next_month strong", instance).show();
					jQuery(".prev_day strong", instance).show();
					jQuery(".next_day strong", instance).show();
					
				}
			}
		});
		';

		if(!empty($this->event_id)) {
			$script .= '
			jQuery(".dp_pec_layout", "#dp_pec_id'.$this->nonce.'").hide();
			jQuery(".dp_pec_options_nav", "#dp_pec_id'.$this->nonce.'").hide();
			jQuery(".dp_pec_add_nav", "#dp_pec_id'.$this->nonce.'").hide();
			';
		}

		$script .= '
		
		//]]>
		</script>';
		
		if($print)
			echo $script;	
		else
			return $script;
		
	}
	
	function output( $print = false ) 
	{
		$width = "";
		$html = "";

		if($this->type == 'calendar') {
			
			if(isset($this->calendar_obj->width) && !$this->is_admin && empty($this->event_id) && !$this->widget) { $width = 'style="width: '.$this->calendar_obj->width.$this->calendar_obj->width_unity.' " '; }
			
			if($this->is_admin) {
				$html .= '
				<div class="dpProEventCalendar_ModalCalendar">';
			}
			
				$html .= '
				<div class="dp_pec_wrapper dp_pec_'.($this->is_admin || !empty($this->event_id) ? 'monthly' : $this->calendar_obj->view).'" id="dp_pec_id'.$this->nonce.'" '.$width.'>';

			if(!$this->is_admin && ($this->calendar_obj->ical_active || $this->calendar_obj->rss_active || $this->calendar_obj->subscribe_active || $this->calendar_obj->show_view_buttons)) {
				$html .= '
					<div class="dp_pec_options_nav">';
				if($this->calendar_obj->show_view_buttons) {
					$html .= '
						<a href="javascript:void(0);" class="dp_pec_view dp_pec_view_action '.($this->calendar_obj->view == "monthly" || $this->calendar_obj->view == "monthly-all-events" ? "active" : "").'" data-pec-view="monthly">'.$this->translation['TXT_MONTHLY'].'</a>
						<a href="javascript:void(0);" class="dp_pec_view dp_pec_view_action '.($this->calendar_obj->view == "daily" ? "active" : "").'" data-pec-view="daily">'.$this->translation['TXT_DAILY'].'</a>
					';
				}
				if($this->calendar_obj->ical_active) {
					$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/ical.php?calendar_id='.$this->id_calendar ) . "'>iCal</a>";
				}
				if($this->calendar_obj->rss_active) {
					$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/rss.php?calendar_id='.$this->id_calendar ) . "'>RSS</a>";
				}
				if($this->calendar_obj->subscribe_active) {
					$html .= "<a class='dpProEventCalendar_feed dpProEventCalendar_subscribe' href='javascript:void(0);'>".$this->translation['TXT_SUBSCRIBE']."</a>";
				}
				$html .= '
						<div class="clear"></div>
					</div>
				';
			}
			
			if($this->calendar_obj->allow_user_add_event && !$this->is_admin) {
				$html .= '
					<div class="dp_pec_add_nav">';
					$html .= '
						<a href="javascript:void(0);" class="dp_pec_view dp_pec_add_event dp_pec_btnright">'.$this->translation['TXT_ADD_EVENT'].'</a>
						<a href="javascript:void(0);" class="dp_pec_view dp_pec_cancel_event dp_pec_btnright">'.$this->translation['TXT_CANCEL'].'</a>
						<div class="clear"></div>
						';
					$html .= '
						<div class="dp_pec_add_form">';
					if(!is_user_logged_in() && !$this->calendar_obj->assign_events_admin) {
						$html .= '
							<div class="dp_pec_notification_box dp_pec_visible">
							'.$this->translation['TXT_EVENT_LOGIN'].'
							</div>';
					} else {
						$html .= '
							<div class="dp_pec_notification_box dp_pec_notification_event_succesfull">
							'.$this->translation['TXT_EVENT_THANKS'].'
							</div>';
						$html .= '
							<form name="dp_pec_event_form" class="add_new_event_form" enctype="multipart/form-data" method="post">
								<div class="pec-add-body">
									<div class="">
										<div class="dp_pec_row">
											<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_TITLE'].'" id="" class="dp_pec_form_title" name="title" />
										</div>
										';
										if($this->calendar_obj->form_show_description) {
											$html .= '
										<div class="dp_pec_row">
											<textarea placeholder="'.$this->translation['TXT_EVENT_DESCRIPTION'].'" id="" name="description" cols="50" rows="5"></textarea>
										</div>
										';
										}
										$html .= '
										
										<div class="dp_pec_row dp_pec_cal_new_sub">
											<div class="dp_pec_col6">
												';
												if($this->calendar_obj->form_show_category) {
													$cat_args = array(
															'taxonomy' => 'pec_events_category',
															'hide_empty' => 0
														);
													if($this->calendar_obj->category_filter_include != "") {
														$cat_args['include'] = $this->calendar_obj->category_filter_include;
													}
													$categories = get_categories($cat_args); 
													if(count($categories) > 0) {
														$html .= '
														<div class="dp_pec_row">
															<div class="dp_pec_col12">
																<span class="dp_pec_form_desc">'.$this->translation['TXT_CATEGORY'].'</span>
																';
																$html .= '
																<select name="category">
																	<option value="">'.$this->translation['TXT_NONE'].'</option>';
																	  foreach ($categories as $category) {
																		$html .= '<option value="'.$category->term_id.'">';
																		$html .= $category->cat_name;
																		$html .= '</option>';
																	  }
															$html .= '
																</select>';
																$html .= '								
															</div>
														</div>
														';
													}
												}
												$html .= '
												<div class="dp_pec_row">
													<div class="dp_pec_col6">
														<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_DATE'].'</span>
														<input type="text" readonly="readonly" name="date" maxlength="10" id="" class="large-text dp_pec_date_input" value="'.date('Y-m-d').'" style="width:80px;" />
													</div>
													
													<div class="dp_pec_col6">
														<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_DATE'].'</span>
														<input type="text" readonly="readonly" name="end_date" maxlength="10" id="" class="large-text dp_pec_end_date_input" value="" style="width:80px;" />
														<button type="button" class="dp_pec_clear_end_date">
															<img src="'.dpProEventCalendar_plugin_url( 'images/admin/clear.png' ).'" alt="Clear" title="Clear">
														</button>
													</div>
													<div class="clear"></div>
												</div>
												<div class="dp_pec_row">
													<div class="dp_pec_col6">
														<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_TIME'].'</span>
														<select name="time_hours" class="dp_pec_new_event_time" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
															for($i = 0; $i <= 23; $i++) {
																$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
																if($this->calendar_obj->format_ampm) {
																	$hour = ($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour)) . ' ' . date('A', mktime($hour, 0));
																}
																$html .= '
																<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$hour.'</option>';
															}
														$html .= '
														</select>
														<select name="time_minutes" class="dp_pec_new_event_time" id="pec_time_minutes" style="width:50px;">';
															for($i = 0; $i <= 59; $i++) {
																$html .= '
																<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
															}
														$html .= '
														</select>
													</div>
													<div class="dp_pec_col6">
														<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_TIME'].'</span>
														<select name="end_time_hh" class="dp_pec_new_event_time" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
															for($i = 0; $i <= 23; $i++) {
																$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
																if($this->calendar_obj->format_ampm) {
																	$hour = ($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour)) . ' ' . date('A', mktime($hour, 0));
																}
																$html .= '
																<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$hour.'</option>';
															}
														$html .= '
														</select>
														<select name="end_time_mm" class="dp_pec_new_event_time" id="" style="width:50px;">';
															for($i = 0; $i <= 59; $i++) {
																$html .= '
																<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
															}
														$html .= '
														</select>
													</div>
													<div class="clear"></div>
												</div>
												
												<div class="dp_pec_row">
												
												';
												if($this->calendar_obj->form_show_hide_time) {
													$html .= '
													<div class="dp_pec_col6">
														<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_FREQUENCY'].'</span>
														<select name="recurring_frecuency" id="pec_recurring_frecuency">
															<option value="0">'.$this->translation['TXT_NONE'].'</option>
															<option value="1">'.$this->translation['TXT_EVENT_DAILY'].'</option>
															<option value="2">'.$this->translation['TXT_EVENT_WEEKLY'].'</option>
															<option value="3">'.$this->translation['TXT_EVENT_MONTHLY'].'</option>
															<option value="4">'.$this->translation['TXT_EVENT_YEARLY'].'</option>
														</select>
													</div>
													';
												}
												if($this->calendar_obj->form_show_frequency) {
													$html .= '
													<div class="dp_pec_col6">
														<input type="checkbox" name="hide_time" class="checkbox" id="" value="1" /> <span class="dp_pec_form_desc dp_pec_form_desc_left">'.$this->translation['TXT_EVENT_HIDE_TIME'].'</span>
													</div>
													';
												}
												$html .= '
													<div class="clear"></div>
												</div>
												
												';
												if($this->calendar_obj->form_show_all_day) {
													$html .= '
													<div class="dp_pec_row">
														<input type="checkbox" class="dp_pec_check_all_day" name="all_day" id="" value="1" />
														<span class="dp_pec_form_desc dp_pec_form_desc_left">'.$this->translation['TXT_EVENT_ALL_DAY'].'</span>
													</div>';
												}
												$html .= '
												<div class="clear"></div>
											</div>
											<div class="dp_pec_col6">
												';
												if($this->calendar_obj->form_show_image) {
													$html .= '
													<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_IMAGE'].'</span>
													<div class="dp_pec_add_image_wrap">
														<label for="event_image_'.$this->nonce.'">
															<span class="dp_pec_add_image"></span>
														</label>
														<input type="text" class="dp_pec_new_event_text" value="" readonly="readonly" id="event_image_lbl" name="" />
													</div>
													<input type="file" name="event_image" id="event_image_'.$this->nonce.'" class="event_image" style="visibility:hidden; position: absolute;" />							
													';
												}
												if($this->calendar_obj->form_show_link) {
													$html .= '
													<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_LINK'].'" id="" name="link" />';
												}
												if($this->calendar_obj->form_show_share) {
													$html .= '
													<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_SHARE'].'" id="" name="share" />';
												}
												if($this->calendar_obj->form_show_location) {
													$html .= '
													<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_LOCATION'].'" id="" name="location" />';
												}
												if($this->calendar_obj->form_show_phone) {
													$html .= '
													<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_PHONE'].'" id="" name="phone" />';
												}
												if($this->calendar_obj->form_show_map) {
													$html .= '
													<input type="text" value="" placeholder="'.$this->translation['TXT_EVENT_GOOGLEMAP'].'" id="" name="googlemap" />';
												}
													$html .= '
												
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="pec-add-footer">
									<a href="javascript:void(0);" class="dp_pec_view dp_pec_submit_event dp_pec_btnright">'.$this->translation['TXT_SUBMIT_FOR_REVIEW'].'</a>
								</div>
							</form>';
					}
				$html .= '
						</div>';
				$html .= '
						<div class="clear"></div>
					</div>
				';
			}

			$html .= '
				<div class="dp_pec_nav dp_pec_nav_monthly" '.($this->calendar_obj->view == "monthly" || $this->is_admin || !empty($this->event_id) ? "" : "style='display:none;'").'>
					<span class="prev_month">&laquo; <strong>'.$this->translation['PREV_MONTH'].'</strong></span>
					<span class="actual_month">'.$this->translation['MONTHS'][($this->datesObj->currentMonth - 1)].' '.$this->datesObj->currentYear.'</span>
					<span class="next_month"><strong>'.$this->translation['NEXT_MONTH'].'</strong> &raquo;</span>
				</div>
			';
			
			$html .= '
				<div class="dp_pec_nav dp_pec_nav_daily" '.($this->calendar_obj->view == "daily" && !$this->is_admin && empty($this->event_id) ? "" : "style='display:none;'").'>
					<span class="prev_day">&laquo; <strong>'.$this->translation['PREV_DAY'].'</strong></span>
					<span class="actual_day">'.date_i18n(get_option('date_format'), $this->defaultDate).'</span>
					<span class="next_day"><strong>'.$this->translation['NEXT_DAY'].'</strong> &raquo;</span>
				</div>
			';
			
			if(!$this->is_admin) {
				$specialDatesList = $this->getSpecialDatesList();
				$html .= '
				<div class="dp_pec_layout">';
				
				if($this->calendar_obj->show_references) {
					$html .= '
					<a href="javascript:void(0);" class="dp_pec_references dp_pec_btnleft">'.$this->translation['TXT_REFERENCES'].'</a>
					<div class="dp_pec_references_div">
						<a href="javascript:void(0);" class="dp_pec_references_close">x</a>';
						$html .= '
						<div class="dp_pec_references_div_sp">
							<div class="dp_pec_references_color" style="background-color: '.$this->calendar_obj->current_date_color.'"></div>
							<div class="dp_pec_references_title">'.$this->translation['TXT_CURRENT_DATE'].'</div>
							<div style="clear:both;"></div>
						</div>';
				
					if(count($specialDatesList) > 0) {
						foreach($specialDatesList as $key) {
							$html .= '
							<div class="dp_pec_references_div_sp">
								<div class="dp_pec_references_color" style="background-color: '.$key->color.'"></div>
								<div class="dp_pec_references_title">'.$key->title.'</div>
								<div style="clear:both;"></div>
							</div>';
						}
					}
					$html .= '
						</div>';
				}
				$html .= '
					<a href="javascript:void(0);" class="dp_pec_view_all dp_pec_btnleft">'.$this->translation['TXT_VIEW_ALL_EVENTS'].'</a>';
				if($this->calendar_obj->show_category_filter && empty($this->category)) {
					$html .= '
						<select name="pec_categories" class="pec_categories_list">
							<option value="">'.$this->translation['TXT_ALL_CATEGORIES'].'</option>';
							$cat_args = array(
									'taxonomy' => 'pec_events_category',
									'hide_empty' => 0
								);
							if($this->calendar_obj->category_filter_include != "") {
								$cat_args['include'] = $this->calendar_obj->category_filter_include;
							}
							$categories = get_categories($cat_args); 
						  foreach ($categories as $category) {
							$html .= '<option value="'.$category->term_id.'">';
							$html .= $category->cat_name;
							$html .= '</option>';
						  }
					$html .= '
						</select>';
				}
				if($this->calendar_obj->show_search) {
					$html .= '
						
						<form method="post" class="dp_pec_search_form">
							<input type="text" class="dp_pec_search" value="" placeholder="'.$this->translation['TXT_SEARCH'].'">
							<input type="submit" class="no-replace dp_pec_search_go" value="">
						</form>
						<div style="clear:both;"></div>';
				}
				$html .= '
					</div>
					';
			}
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content">
					';
					
			if($this->calendar_obj->view == "monthly" || $this->is_admin || !empty($this->event_id)) {
				$html .= $this->monthlyCalendarLayout();
			}
			
			if($this->calendar_obj->view == "daily" && !$this->is_admin && empty($this->event_id)) {
				$html .= $this->dailyCalendarLayout();
			}
			
			$html .= '
								
				</div>
			</div>';
			
			if($this->is_admin) {
				$html .= '
				</div>';
			}
		} elseif($this->type == 'upcoming') {
			
			$html .= '
			<div class="dp_pec_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content">
					';
						
			$html .= $this->upcomingCalendarLayout();
			
			$html .= '
								
				</div>
			</div>';
			
		} elseif($this->type == 'accordion') {
			
			$html .= '
			<div class="dp_pec_accordion_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			if($this->calendar_obj->ical_active) {
				$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/ical.php?calendar_id='.$this->id_calendar ) . "'>iCal</a>";
			}
			if($this->calendar_obj->rss_active) {
				$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/rss.php?calendar_id='.$this->id_calendar ) . "'>RSS</a>";
			}
			if($this->calendar_obj->subscribe_active) {
				$html .= "<a class='dpProEventCalendar_feed dpProEventCalendar_subscribe' href='javascript:void(0);'>".$this->translation['TXT_SUBSCRIBE']."</a>";
			}
			
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content_header">
					<span class="events_loading"></span>
					<h2 class="actual_month">'.$this->translation['MONTHS'][($this->datesObj->currentMonth - 1)].' '.$this->datesObj->currentYear.'</h2>
					<div class="month_arrows">
						<span class="prev_month"></span>
						<span class="next_month"></span>
					</div>
					<span class="return_layout"></span>
				</div>
				';
			if($this->calendar_obj->show_search) {
				$html .= '
				<div class="dp_pec_content_search">
					<input type="text" class="dp_pec_content_search_input" placeholder="'.$this->translation['TXT_SEARCH'].'" />
					<a href="javascript:void(0);" class="dp_pec_icon_search" data-results_lang="'.addslashes($this->translation['TXT_RESULTS_FOR']).'"></a>
				</div>';
			}
				$html .= '
				
				<div class="dp_pec_content">
				';
				$year = $this->datesObj->currentYear;
				$next_month_days = cal_days_in_month(CAL_GREGORIAN, str_pad(($this->datesObj->currentMonth), 2, "0", STR_PAD_LEFT), $year);
				$month_number = str_pad($this->datesObj->currentMonth, 2, "0", STR_PAD_LEFT);
				
				$html .= $this->eventsMonthList($year."-".$month_number."-01 00:00:00", $year."-".$month_number."-".$next_month_days." 23:59:59");
			$html .= '
				</div>
			</div>';
			
		} elseif($this->type == 'accordion-upcoming') {
			
			$html .= '
			<div class="dp_pec_accordion_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content">
				';
				
				$html .= $this->eventsMonthList(null, null, $this->limit);
			$html .= '
				</div>
			</div>';
		
		} elseif($this->type == 'gmap-upcoming') {
			$html .= '
			<div class="dp_pec_gmap_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$event_list = $this->upcomingCalendarLayout( true, $this->limit, '', $start_search, $end_search, true, false, false, true );
			$unique_events = array();
			$event_marker = "";
			$first_loc = "";
			if(is_array($event_list)) {
				
			
				foreach ($event_list as $obj) {
					if(!is_object($unique_events[$obj->id])) {
						$unique_events[$obj->id] = $obj;
						if($this->calendar_obj->format_ampm) {
							$time = date('h:i A', strtotime($obj->date));
						} else {
							$time = date('H:i', strtotime($obj->date));				
						}
		
						$end_date = '';
						$end_year = '';
						if($obj->end_date != "" && $obj->end_date != "0000-00-00" && $obj->recurring_frecuency == 1) {
							$end_day = date('d', strtotime($obj->end_date));
							$end_month = date('n', strtotime($obj->end_date));
							$end_year = date('Y', strtotime($obj->end_date));
							
							//$end_date = ' / <br />'.$end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3).', '.$end_year;
							$end_date = ' / '.date_i18n(get_option('date_format'), strtotime($obj->end_date));
						}
											
						$end_time = "";
						if($obj->end_time_hh != "" && $obj->end_time_mm != "") { $end_time = str_pad($obj->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($obj->end_time_mm, 2, "0", STR_PAD_LEFT); }
						
						if($end_time != "") {
							
							if($this->calendar_obj->format_ampm) {
								$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
							} else {
								$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
							}
							$end_time = " / ".$end_time_tmp;
							if($end_time_tmp == $time) {
								$end_time = "";	
							}
						}
		
						if($event->all_day) {
							$time = $this->translation['TXT_ALL_DAY'];
							$end_time = "";
						}
						
						$title = $obj->title;
						if($this->calendar_obj->link_post) {
							$title = '<a href="'.get_permalink($obj->id).'">'.addslashes($title).'</a>';	
						}
						
						$event_marker .= 'codeAddress("'.$obj->map.'", \''.addslashes($title).'\', \''.get_the_post_thumbnail($obj->id, 'medium').'\', \''.date_i18n(get_option('date_format'), strtotime($obj->date)).$end_date.((($this->calendar_obj->show_time && !$event->hide_time) || $event->all_day) ? ' - '.$time.$end_time : '').'\'); ';
					}
					if($first_loc == "") {
						$first_loc = $obj->map;
					}
				}
				

			$html .= '
				<div style="clear:both;"></div>
				<div class="dp_pec_map_canvas" id="dp_pec_map_canvas'.$this->nonce.'"></div>
				
				<script type="text/javascript">
				jQuery(document).ready(function() {
					var geocoder;
					var map;
					function initialize'.$this->nonce.'() {
					 geocoder = new google.maps.Geocoder();
					 geocoder.geocode( { "address": "'.$first_loc.'"}, function(results, status) {
						  var latlng = results[0].geometry.location;
						  var mapOptions = {
							zoom: 10,
							center: latlng
						  }
						  map = new google.maps.Map(document.getElementById("dp_pec_map_canvas'.$this->nonce.'"), mapOptions);
					 });
					  ';
				$html .= $event_marker;
				$html .= '
					}
					
					var infowindow = new google.maps.InfoWindow();
					
					function getInfoWindowEvent(marker, content) {
						infowindow.close();
						infowindow.setContent(content);
						infowindow.open(map, marker);
					}

					function codeAddress(address, title, image, eventdate) {
						
					  geocoder.geocode( { "address": address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
						  map.setCenter(results[0].geometry.location);
						  var marker = new google.maps.Marker({
							  map: map,
							  position: results[0].geometry.location
						  });
						  
						  google.maps.event.addListener(marker, "click", function() {
							  infowindow.close();
							  
							  var content = \'<div class="dp_pec_map_infowindow">\'
								  		+\'<span class="dp_pec_map_title">\'+title+\'</span><span class="dp_pec_map_date">\'+eventdate+\'</span><span class="dp_pec_map_location">\'+address+\'</span><div class="dp_pec_clear"></div>\'+image+\'\'
								  	+\'</div>\';
							  getInfoWindowEvent(marker, content);
						  });
						} else {
						  console.log("Geocode was not successful for the following reason: " + status);
						}
					  });
					}
					
					google.maps.event.addDomListener(window, "load", initialize'.$this->nonce.');
				});
				</script>';
			} else {
				$html .= '<div class="dp_pec_accordion_event dp_pec_accordion_no_events"><span>'.$this->translation['TXT_NO_EVENTS_FOUND'].'</span></div>';	
			}
				
			$html .= '
			</div>';
		} elseif($this->type == 'add-event') {
			
			$html .= '
			<div class="dp_pec_new_event_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content_header">
					<span class="events_loading"></span>
					<h2 class="actual_month">'.$this->translation['TXT_ADD_EVENT'].'</h2>
				</div>
				
				<div class="dp_pec_content">
					';
				if(!is_user_logged_in() && !$this->calendar_obj->assign_events_admin) {
					$html .= '<div class="dp_pec_new_event_login"><span>'.$this->translation['TXT_EVENT_LOGIN'].'</span></div>';	
				} else {
					$html .= '
					<div class="dp_pec_new_event_login dp_pec_notification_event_succesfull">
					'.$this->translation['TXT_EVENT_THANKS'].'
					</div>';
					$html .= '
					<form enctype="multipart/form-data" method="post" class="add_new_event_form">
					<input type="text" class="dp_pec_new_event_text dp_pec_form_title" placeholder="'.$this->translation['TXT_EVENT_TITLE'].'" name="title" />
					';
					if($this->calendar_obj->form_show_description) {
						$html .= '
						<textarea placeholder="'.$this->translation['TXT_EVENT_DESCRIPTION'].'" class="dp_pec_new_event_text" id="" name="description" cols="50" rows="5"></textarea>';
					}
					$html .= '
					<div class="dp_pec_row">
						<div class="dp_pec_col6">
							';
							if($this->calendar_obj->form_show_category) {
								$cat_args = array(
										'taxonomy' => 'pec_events_category',
										'hide_empty' => 0
									);
								if($this->calendar_obj->category_filter_include != "") {
									$cat_args['include'] = $this->calendar_obj->category_filter_include;
								}
								$categories = get_categories($cat_args); 
								if(count($categories) > 0) {
									$html .= '
									<div class="dp_pec_row">
										<div class="dp_pec_col12">
											<span class="dp_pec_form_desc">'.$this->translation['TXT_CATEGORY'].'</span>
											';
											$html .= '
											<select name="category">
												<option value="">'.$this->translation['TXT_NONE'].'</option>';
												  foreach ($categories as $category) {
													$html .= '<option value="'.$category->term_id.'">';
													$html .= $category->cat_name;
													$html .= '</option>';
												  }
										$html .= '
											</select>';
											$html .= '								
										</div>
									</div>
									';
								}
							}
							$html .= '
							<div class="dp_pec_row">
								<div class="dp_pec_col6">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_DATE'].'</span>
									<div class="clear"></div>
									<input type="text" readonly="readonly" name="date" maxlength="10" id="" class="dp_pec_new_event_text dp_pec_date_input" value="'.date('Y-m-d').'" />
								</div>
								
								<div class="dp_pec_col6 dp_pec_end_date_form">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_DATE'].'</span>
									<div class="clear"></div>
									<input type="text" readonly="readonly" name="end_date" maxlength="10" id="" class="dp_pec_new_event_text dp_pec_end_date_input" value="" />
									<button type="button" class="dp_pec_clear_end_date">
										<img src="'.dpProEventCalendar_plugin_url( 'images/admin/clear.png' ).'" alt="Clear" title="Clear">
									</button>
								</div>
								<div class="clear"></div>
							</div>
							<div class="dp_pec_row">
								<div class="dp_pec_col6">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_TIME'].'</span>
									<div class="clear"></div>
									<select class="dp_pec_new_event_time" name="time_hours" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
										for($i = 0; $i <= 23; $i++) {
											$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
											if($this->calendar_obj->format_ampm) {
												$hour = date('A', mktime($hour, 0)).' '.($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour));
											}
											$html .= '
											<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$hour.'</option>';
										}
									$html .= '
									</select>
									<select class="dp_pec_new_event_time" name="time_minutes" id="pec_time_minutes" style="width:50px;">';
										for($i = 0; $i <= 59; $i++) {
											$html .= '
											<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
										}
									$html .= '
									</select>
								</div>
								<div class="dp_pec_col6">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_TIME'].'</span>
									<div class="clear"></div>
									<select class="dp_pec_new_event_time" name="end_time_hh" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
										for($i = 0; $i <= 23; $i++) {
											$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
											if($this->calendar_obj->format_ampm) {
												$hour = date('A', mktime($hour, 0)).' '.($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour));
											}
											$html .= '
											<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$hour.'</option>';
										}
									$html .= '
									</select>
									<select class="dp_pec_new_event_time" name="end_time_mm" id="" style="width:50px;">';
										for($i = 0; $i <= 59; $i++) {
											$html .= '
											<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
										}
									$html .= '
									</select>
								</div>
								<div class="clear"></div>
							</div>
							
							<div class="dp_pec_row">
							
							';
							if($this->calendar_obj->form_show_hide_time) {
								$html .= '
								<div class="dp_pec_col6">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_HIDE_TIME'].'</span>
									<select name="hide_time">
										<option value="0">'.$this->translation['TXT_NO'].'</option>
										<option value="1">'.$this->translation['TXT_YES'].'</option>
									</select>
								</div>';
							}
							
							if($this->calendar_obj->form_show_frequency) {
								$html .= '
								<div class="dp_pec_col6">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_FREQUENCY'].'</span>
									<select name="recurring_frecuency" id="pec_recurring_frecuency">
										<option value="0">'.$this->translation['TXT_NONE'].'</option>
										<option value="1">'.$this->translation['TXT_EVENT_DAILY'].'</option>
										<option value="2">'.$this->translation['TXT_EVENT_WEEKLY'].'</option>
										<option value="3">'.$this->translation['TXT_EVENT_MONTHLY'].'</option>
										<option value="4">'.$this->translation['TXT_EVENT_YEARLY'].'</option>
									</select>
								</div>';
							}
							$html .= '
								<div class="clear"></div>
							</div>
							';
							if($this->calendar_obj->form_show_all_day) {
								$html .= '
								<div class="dp_pec_row">
									<input type="checkbox" class="checkbox" name="all_day" id="" value="1" />
									<span class="dp_pec_form_desc dp_pec_form_desc_left">'.$this->translation['TXT_EVENT_ALL_DAY'].'</span>
								</div>';
							}
							$html .= '
							<div class="clear"></div>
						</div>
						<div class="dp_pec_col6">
							';
							if($this->calendar_obj->form_show_image) {
								$html .= '
								<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_IMAGE'].'</span>
								<div class="dp_pec_add_image_wrap">
									<label for="event_image">
										<span class="dp_pec_add_image"></span>
									</label>
									<input type="text" class="dp_pec_new_event_text" value="" readonly="readonly" id="event_image_lbl" name="" />
								</div>
								<input type="file" name="event_image" id="event_image" class="event_image" style="visibility:hidden; position: absolute;" />							
								';
							}
							if($this->calendar_obj->form_show_link) {
								$html .= '
								<input type="text" class="dp_pec_new_event_text" value="" placeholder="'.$this->translation['TXT_EVENT_LINK'].'" id="" name="link" />';
							}
							if($this->calendar_obj->form_show_share) {
								$html .= '
								<input type="text" class="dp_pec_new_event_text" value="" placeholder="'.$this->translation['TXT_EVENT_SHARE'].'" id="" name="share" />';
							}
							if($this->calendar_obj->form_show_location) {
								$html .= '
								<input type="text" class="dp_pec_new_event_text" value="" placeholder="'.$this->translation['TXT_EVENT_LOCATION'].'" id="" name="location" />';
							}
							if($this->calendar_obj->form_show_phone) {
								$html .= '
								<input type="text" class="dp_pec_new_event_text" value="" placeholder="'.$this->translation['TXT_EVENT_PHONE'].'" id="" name="phone" />';
							}
							if($this->calendar_obj->form_show_map) {
								$html .= '
								<input type="text" class="dp_pec_new_event_text" value="" placeholder="'.$this->translation['TXT_EVENT_GOOGLEMAP'].'" id="" name="googlemap" />';
							}
								$html .= '
						</div>
					</div>
					<div class="clear"></div>
					<div class="pec-add-footer">
						<button class="dp_pec_submit_event">'.$this->translation['TXT_SUBMIT_FOR_REVIEW'].'</button>
						<div class="clear"></div>
					</div>
					</form>';
				}
					$html .= '
				</div>
				<div class="clear"></div>
			</div>';
			
		} elseif($this->type == 'list-author') {
			$html .= '
			<div class="dp_pec_wrapper" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content">
					';
						
			$html .= $this->upcomingCalendarLayout(false, 10, '', null, null, true, true);

			$html .= '
								
				</div>
			</div>';
		} elseif($this->type == 'today-events') {
			$html .= '
			<div class="dp_pec_wrapper dp_pec_today_events" id="dp_pec_id'.$this->nonce.'" '.$width.'>
			';
			$html .= '
				<div style="clear:both;"></div>
				
				<div class="dp_pec_content">
					';
			
			date_default_timezone_set( get_option('timezone_string')); // set the PHP timezone to match WordPress
			$html .= $this->eventsListLayout(date('Y-m-d', $this->defaultDate), false);
			
			$html .= '
								
				</div>
			</div>';
		}
		
		
		if($print)
			echo $html;	
		else
			return $html;
		
	}
	
	function eventsMonthList($start_search = null, $end_search = null, $limit = 99) {
		
		$html = "";
		$daily_events = array();
		$event_counter = 1;
		
		$event_list = $this->upcomingCalendarLayout( true, $limit, '', $start_search, $end_search, true, false, false );
		if(is_array($event_list) && count($event_list) > 0) {
			foreach($event_list as $event) {
				
				if($event_counter > $limit) { break; }
				
				if($event->recurring_frecuency == 1){
					if(in_array($event->id, $daily_events)) {
						continue;	
					}
					
					$daily_events[] = $event->id;
				}
				
				if($this->calendar_obj->format_ampm) {
					$time = date('h:i A', strtotime($event->date));
				} else {
					$time = date('H:i', strtotime($event->date));				
				}

				$end_date = '';
				$end_year = '';
				if($event->end_date != "" && $event->end_date != "0000-00-00" && $event->recurring_frecuency == 1) {
					$end_day = date('d', strtotime($event->end_date));
					$end_month = date('n', strtotime($event->end_date));
					$end_year = date('Y', strtotime($event->end_date));
					
					//$end_date = ' / <br />'.$end_day.' '.substr($this->translation['MONTHS'][($end_month - 1)], 0, 3).', '.$end_year;
					$end_date = ' / '.date_i18n(get_option('date_format'), strtotime($event->end_date));
				}
									
				$end_time = "";
				if($event->end_time_hh != "" && $event->end_time_mm != "") { $end_time = str_pad($event->end_time_hh, 2, "0", STR_PAD_LEFT).":".str_pad($event->end_time_mm, 2, "0", STR_PAD_LEFT); }
				
				if($end_time != "") {
					
					if($this->calendar_obj->format_ampm) {
						$end_time_tmp = date('h:i A', strtotime("2000-01-01 ".$end_time.":00"));
					} else {
						$end_time_tmp = date('H:i', strtotime("2000-01-01 ".$end_time.":00"));				
					}
					$end_time = " / ".$end_time_tmp;
					if($end_time_tmp == $time) {
						$end_time = "";	
					}
				}

				if($event->all_day) {
					$time = $this->translation['TXT_ALL_DAY'];
					$end_time = "";
				}

				$title = $event->title;
				if($this->calendar_obj->link_post) {
					$title = '<a href="'.get_permalink($event->id).'">'.$title.'</a>';	
				}
				
				$all_working_days = '';
				if($event->pec_daily_working_days && $event->recurring_frecuency == 1) {
					$all_working_days = $this->translation['TXT_ALL_WORKING_DAYS'];
				}
				
				$edit_button = $this->getEditRemoveButtons($event);
				
				$html .= '
				<div class="dp_pec_accordion_event">
					<h2>'.$title.$edit_button.'</h2>
					';
						
					if($this->calendar_obj->show_author) {
						$author = get_userdata(get_post_field( 'post_author', $event->id ));
						$html .= '<span class="pec_author">'.$this->translation['TXT_BY'].' '.$author->display_name.'</span>';
					}
					
					$html .= '
					<span class="pec_time">'.date_i18n(get_option('date_format'), strtotime($event->date)).$end_date . '<br>'. $all_working_days.' '.((($this->calendar_obj->show_time && !$event->hide_time) || $event->all_day) ?  $time.$end_time : '').'</span>
					';
					
					if($event->location != '') {
						$html .= '
						<span class="dp_pec_event_location">'.$event->location.'</span>';
					}
					if($event->phone != '') {
						$html .= '
						<span class="dp_pec_event_phone">'.$event->phone.'</span>';
					}
					$category = get_the_terms( $event->id, 'pec_events_category' ); 
					if(!empty($category)) {
						$category_count = 0;
						$html .= '
							<span class="dp_pec_event_categories">';
						foreach ( $category as $cat){
							if($category_count > 0) {
								$html .= " / ";	
							}
							$html .= $cat->name;
							$category_count++;
						}
						$html .= '
							</span>';
					}
					
			$html .= '
					<div class="pec_description">';
					$post_thumbnail_id = get_post_thumbnail_id( $event->id );
					$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
					if($post_thumbnail_id) {
						$html .= '<div class="dp_pec_event_photo">';
						$html .= '<img src="'.$image_attributes[0].'" alt="" />';
						$html .= '</div>';
					}
			
			$html .= '
						<p>'.do_shortcode(nl2br(strip_tags(str_replace("</p>", "<br />", preg_replace("/<p[^>]*?>/", "", $event->description)), '<iframe><strong><h1><h2><h3><h4><h5><h6><h7><b><i><em><pre><code><del><ins><img><a><ul><li><ol><blockquote><br><hr><span><p>'))).'</p>';
					
					$html .= $this->getRating($event->id);
					
					if($event->map != '') {
						$html .= '
						<iframe class="dp_pec_date_event_map_iframe" width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&q='.urlencode($event->map).'&ie=UTF8&output=embed&zoom=14"></iframe>';
					}
					$html .= '
						<div class="dp_pec_date_event_icons">';
					
					$html .= $this->getEventShare($event);
					
					if($event->link != '') {
						$html .= '
						<a class="dp_pec_date_event_link" href="'.$event->link.'" target="_blank"></a>';
					}
					if($this->calendar_obj->ical_active && !$search) {
						$html .= "<a class='dpProEventCalendar_feed' href='".dpProEventCalendar_plugin_url( 'includes/ical_event.php?event_id='.$event->id.'&date='.strtotime($event->date) ) . "'>iCal</a><br class='clear' />";
					}
					$html .= '
						<a class="dp_pec_date_event_close" href="javascript:void(0);"></a>';
					$html .= '
						</div>';
			$html .= '
					</div>
				</div>';
				
				$event_counter++;
			}
		} else {
			$html .= '<div class="dp_pec_accordion_event dp_pec_accordion_no_events"><span>'.$this->translation['TXT_NO_EVENTS_FOUND'].'</span></div>';	
		}
		
		return $html;
	}
	
	function switchCalendarTo($type, $limit = 5, $limit_description = 0, $category = 0, $author = 0, $event_id = 0) {
		if(!is_numeric($limit)) { $limit = 5; }
		$this->type = $type;
		$this->limit = $limit;
		$this->limit_description = $limit_description;
		$this->category = $category;
		$this->event_id = $event_id;
		$this->author = $author;
	}
	
	function calendarSubscription($email, $name) {
		global $wpdb;
		
		require_once (dirname (__FILE__) . '/../mailchimp/miniMCAPI.class.php');

		if(stripslashes($email) != '' ){
			
			$exists = $wpdb->get_row("SELECT COUNT(*) as counter FROM ".$this->table_subscribers_calendar." WHERE calendar = ".$this->id_calendar." AND email = '".$email."'");

			if($exists->counter == 0) {
				$wpdb->insert( 
					$this->table_subscribers_calendar, 
					array( 
						'name' => $name, 
						'email' => $email, 
						'calendar' => $this->id_calendar, 
						'subscription_date' => date('Y-m-d h:i:s')
					), 
					array( 
						'%s', 
						'%s', 
						'%d', 
						'%s' 
					) 
				);
			}
				
			if($this->calendar_obj->mailchimp_api != "" && $this->calendar_obj->subscribe_active) {
				$mailchimp_class = new mailchimpSF_MCAPI($this->calendar_obj->mailchimp_api);
				
				$retval = $mailchimp_class->listSubscribe( $this->calendar_obj->mailchimp_list, $email );
		
				if(!$mailchimp_class->errorCode){	
					die('ok');
				}else{
					die('0');
				}
			}
		}
	}
	
	function getRating($eventid) {
		$rate 		= get_post_meta($eventid, 'pec_rate', true);
		$user_rate 	= get_post_meta($eventid, 'pec_user_rate', true);
		
		if($user_rate != "") {
			$star1 = count(get_post_meta($eventid, 'pec_user_rate_1star'));
			$star2 = count(get_post_meta($eventid, 'pec_user_rate_2star'));
			$star3 = count(get_post_meta($eventid, 'pec_user_rate_3star'));
			$star4 = count(get_post_meta($eventid, 'pec_user_rate_4star'));
			$star5 = count(get_post_meta($eventid, 'pec_user_rate_5star'));
			
			$total_votes = $star1 + $star2 + $star3 + $star4 + $star5;
			
			if($total_votes == 0) {
				$rate = 0;
			} else {
				$rate = ((
					$star1 +
					($star2 * 2) +
					($star3 * 3) +
					($star4 * 4) +
					($star5 * 5)) /
						$total_votes);
			}
		}
		
		if($rate != '' || $rate === 0) {
			$html = '
			<ul class="dp_pec_rate '.($user_rate != "" && is_user_logged_in() ? 'dp_pec_user_rate' : '').'">
				<li><a href="javascript:void(0);" data-rate-val="1" data-event-id="'.$eventid.'" '.($rate >= 1 ? 'class="dp_pec_rate_full"' : '').'></a></li>
				<li><a href="javascript:void(0);" data-rate-val="2" data-event-id="'.$eventid.'" '.($rate >= 2 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 1 && $rate < 2 ? 'class="dp_pec_rate_h"' : '').'></a></li>
				<li><a href="javascript:void(0);" data-rate-val="3" data-event-id="'.$eventid.'" '.($rate >= 3 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 2 && $rate < 3 ? 'class="dp_pec_rate_h"' : '').'></a></li>
				<li><a href="javascript:void(0);" data-rate-val="4" data-event-id="'.$eventid.'" '.($rate >= 4 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 3 && $rate < 4 ? 'class="dp_pec_rate_h"' : '').'></a></li>
				<li><a href="javascript:void(0);" data-rate-val="5" data-event-id="'.$eventid.'" '.($rate >= 5 ? 'class="dp_pec_rate_full"' : '').' '.($rate > 4 && $rate < 5 ? 'class="dp_pec_rate_h"' : '').'></a></li>
			</ul>';
		}
		
		return $html;
	}
	
	function rateEvent($eventid, $rate) {
		
		if(is_user_logged_in() && is_numeric($eventid)) {
			global $current_user;
			get_currentuserinfo();
			
			delete_post_meta($eventid, 'pec_user_rate_1star', $current_user->ID);
			delete_post_meta($eventid, 'pec_user_rate_2star', $current_user->ID);
			delete_post_meta($eventid, 'pec_user_rate_3star', $current_user->ID);
			delete_post_meta($eventid, 'pec_user_rate_4star', $current_user->ID);
			delete_post_meta($eventid, 'pec_user_rate_5star', $current_user->ID);
			
			add_post_meta($eventid, 'pec_user_rate_'.$rate.'star', $current_user->ID);
								
			return $this->getRating($eventid);
		}
		
	}
	
	function getEventShare($event) {
		$html = "";
		
		if($this->calendar_obj->article_share) {
			if(shortcode_exists('dpArticleShare')) {
				global $dpArticleShare;
				
				if($dpArticleShare['support_pro_event_calendar']) {
					require_once (dirname (__FILE__) . '/../../dpArticleShare/classes/base.class.php');
					$dpArticleShare_class = new DpArticleShare( false, '', $event->id );
					$html .= str_replace('icon icon-dpShareIcon-more">', 'icon icon-dpShareIcon-more">'.$dpArticleShare['i18n_share_on'], $dpArticleShare_class->output(true));
				}
			}
		} else {
			if($event->share != '') {
				$html .= '
				<a class="dp_pec_date_event_twitter" href="http://twitter.com/home?status='.urlencode($event->share).'" target="_blank"></a>';
			}
		}	
		
		return $html;
	}
	
	function getEditRemoveButtons($event) {
		$edit_button = "";
		if(is_user_logged_in() && ($this->calendar_obj->allow_user_edit_event || $this->calendar_obj->allow_user_remove_event)) {
			global $current_user;
			get_currentuserinfo();
			
			if($current_user->ID == get_post_field( 'post_author', $event->id ) && $this->calendar_obj->allow_user_edit_event) {
				$edit_button = '<a href="javascript:void(0);" class="pec_edit_event">'.$this->translation['TXT_EDIT_EVENT'].'</a><div style="display:none;">'.$this->getAddForm($event->id).'</div>';		
			}
			
			if($current_user->ID == get_post_field( 'post_author', $event->id ) && $this->calendar_obj->allow_user_remove_event) {
				$edit_button .= '<a href="javascript:void(0);" class="pec_remove_event">'.$this->translation['TXT_REMOVE_EVENT'].'</a><div style="display:none;">
				<form enctype="multipart/form-data" method="post" class="add_new_event_form remove_event_form">
			
				<input type="hidden" value="'.$this->id_calendar.'" name="remove_event_calendar">
				<input type="hidden" value="'.$event->id.'" name="remove_event">
				<p>'.$this->translation['TXT_REMOVE_EVENT_CONFIRM'].'</p>
				<div class="clear"></div>
				<div class="pec-add-footer">
					<button class="dp_pec_remove_event">'.$this->translation['TXT_REMOVE_EVENT'].'</button>
					<div class="clear"></div>
				</div>
				</form>
				</div>';		
			}
		}
		
		return $edit_button;
	}
	
	function getAddForm($edit = false) {
		$html = '';
		
		if(is_numeric($edit)) {
			$id_calendar = get_post_meta($edit, 'pec_id_calendar', true);
			$title = get_the_title($edit);
			$description = get_post_field('post_content', $edit);
			$post_category = get_the_category($edit);
			$date = get_post_meta($edit, 'pec_date', true);
			$start_date = substr($date, 0, 11);
			$start_time_hh = substr($date, 11, 2);
			$start_time_mm = substr($date, 14, 2);
			$all_day = get_post_meta($edit, 'pec_all_day', true);
			$recurring_frecuency = get_post_meta($edit, 'pec_recurring_frecuency', true);
			$end_date = get_post_meta($edit, 'pec_end_date', true);
			$link = get_post_meta($edit, 'pec_link', true);
			$share = get_post_meta($edit, 'pec_share', true);
			$map = get_post_meta($edit, 'pec_map', true);
			$end_time_hh = get_post_meta($edit, 'pec_end_time_hh', true);
			$end_time_mm = get_post_meta($edit, 'pec_end_time_mm', true);
			$hide_time = get_post_meta($edit, 'pec_hide_time', true);
			$location = get_post_meta($edit, 'pec_location', true);
			$phone = get_post_meta($edit, 'pec_phone', true);
		}
		
		$html .= '
			<form enctype="multipart/form-data" method="post" class="add_new_event_form edit_event_form">
			';
		if(is_numeric($id_calendar)) {
			$html .= '
			<input type="hidden" value="'.$id_calendar.'" name="edit_calendar" />
			<input type="hidden" value="'.$edit.'" name="edit_event" />';
		}
		$html .= '
			<input type="text" class="dp_pec_new_event_text dp_pec_form_title" value="'.$title.'" placeholder="'.$this->translation['TXT_EVENT_TITLE'].'" name="title" />
			';
			if($this->calendar_obj->form_show_description) {
				$html .= '
				<textarea placeholder="'.$this->translation['TXT_EVENT_DESCRIPTION'].'" class="dp_pec_new_event_text" id="" name="description" cols="50" rows="5">'.$description.'</textarea>';
			}
			$html .= '
			<div class="dp_pec_row">
				<div class="dp_pec_col6">
					';
					if($this->calendar_obj->form_show_category) {
						$cat_args = array(
								'taxonomy' => 'pec_events_category',
								'hide_empty' => 0
							);
						if($this->calendar_obj->category_filter_include != "") {
							$cat_args['include'] = $this->calendar_obj->category_filter_include;
						}
						$categories = get_categories($cat_args); 
						if(count($categories) > 0) {
							$html .= '
							<div class="dp_pec_row">
								<div class="dp_pec_col12">
									<span class="dp_pec_form_desc">'.$this->translation['TXT_CATEGORY'].'</span>
									';
									$html .= '
									<select name="category">
										<option value="">'.$this->translation['TXT_NONE'].'</option>';
										  foreach ($categories as $category) {
											$html .= '<option value="'.$category->term_id.'" '.($category->term_id == $post_category[0]->term_id ? 'selected="selected"' : '').'>';
											$html .= $category->cat_name;
											$html .= '</option>';
										  }
								$html .= '
									</select>';
									$html .= '								
								</div>
							</div>
							';
						}
					}
					$html .= '
					<div class="dp_pec_row">
						<div class="dp_pec_col6">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_DATE'].'</span>
							<div class="clear"></div>
							<input type="text" readonly="readonly" name="date" maxlength="10" id="" class="dp_pec_new_event_text dp_pec_date_input_modal" value="'.$start_date.'" />
						</div>
						
						<div class="dp_pec_col6 dp_pec_end_date_form">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_DATE'].'</span>
							<div class="clear"></div>
							<input type="text" readonly="readonly" name="end_date" maxlength="10" id="" class="dp_pec_new_event_text dp_pec_end_date_input_modal" value="'.$end_date.'" />
							<button type="button" class="dp_pec_clear_end_date">
								<img src="'.dpProEventCalendar_plugin_url( 'images/admin/clear.png' ).'" alt="Clear" title="Clear">
							</button>
						</div>
						<div class="clear"></div>
					</div>
					<div class="dp_pec_row">
						<div class="dp_pec_col6">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_START_TIME'].'</span>
							<div class="clear"></div>
							<select class="dp_pec_new_event_time" name="time_hours" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
								for($i = 0; $i <= 23; $i++) {
									$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
									if($this->calendar_obj->format_ampm) {
										$hour = date('A', mktime($hour, 0)).' '.($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour));
									}
									$html .= '
									<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'" '.($start_time_hh == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected="selected"' : '').'>'.$hour.'</option>';
								}
							$html .= '
							</select>
							<select class="dp_pec_new_event_time" name="time_minutes" id="pec_time_minutes" style="width:50px;">';
								for($i = 0; $i <= 59; $i++) {
									$html .= '
									<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'" '.($start_time_mm == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected="selected"' : '').'>'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
								}
							$html .= '
							</select>
						</div>
						<div class="dp_pec_col6">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_END_TIME'].'</span>
							<div class="clear"></div>
							<select class="dp_pec_new_event_time" name="end_time_hh" id="" style="width:'.($this->calendar_obj->format_ampm ? '70' : '50').'px;">';
								for($i = 0; $i <= 23; $i++) {
									$hour = str_pad($i, 2, "0", STR_PAD_LEFT);
									if($this->calendar_obj->format_ampm) {
										$hour = date('A', mktime($hour, 0)).' '.($hour > 12 ? $hour - 12 : ($hour == '00' ? '12' : $hour));
									}
									$html .= '
									<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'" '.($end_time_hh == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected="selected"' : '').'>'.$hour.'</option>';
								}
							$html .= '
							</select>
							<select class="dp_pec_new_event_time" name="end_time_mm" id="" style="width:50px;">';
								for($i = 0; $i <= 59; $i++) {
									$html .= '
									<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'" '.($end_time_mm == str_pad($i, 2, "0", STR_PAD_LEFT) ? 'selected="selected"' : '').'>'.str_pad($i, 2, "0", STR_PAD_LEFT).'</option>';
								}
							$html .= '
							</select>
						</div>
						<div class="clear"></div>
					</div>
					
					<div class="dp_pec_row">
					
					';
					if($this->calendar_obj->form_show_hide_time) {
						$html .= '
						<div class="dp_pec_col6">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_HIDE_TIME'].'</span>
							<select name="hide_time">
								<option value="0" '.($hide_time == 0 ? 'selected="selected"' : '').'>'.$this->translation['TXT_NO'].'</option>
								<option value="1" '.($hide_time == 1 ? 'selected="selected"' : '').'>'.$this->translation['TXT_YES'].'</option>
							</select>
						</div>';
					}
					
					if($this->calendar_obj->form_show_frequency) {
						$html .= '
						<div class="dp_pec_col6">
							<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_FREQUENCY'].'</span>
							<select name="recurring_frecuency" id="pec_recurring_frecuency">
								<option value="0">'.$this->translation['TXT_NONE'].'</option>
								<option value="1" '.($recurring_frecuency == 1 ? 'selected="selected"' : '').'>'.$this->translation['TXT_EVENT_DAILY'].'</option>
								<option value="2" '.($recurring_frecuency == 2 ? 'selected="selected"' : '').'>'.$this->translation['TXT_EVENT_WEEKLY'].'</option>
								<option value="3" '.($recurring_frecuency == 3 ? 'selected="selected"' : '').'>'.$this->translation['TXT_EVENT_MONTHLY'].'</option>
								<option value="4" '.($recurring_frecuency == 4 ? 'selected="selected"' : '').'>'.$this->translation['TXT_EVENT_YEARLY'].'</option>
							</select>
						</div>';
					}
					$html .= '
						<div class="clear"></div>
					</div>
					';
					if($this->calendar_obj->form_show_all_day) {
						$html .= '
						<div class="dp_pec_row">
							<input type="checkbox" class="checkbox" name="all_day" id="" value="1" '.($all_day ? 'checked="checked"' : '').' />
							<span class="dp_pec_form_desc dp_pec_form_desc_left">'.$this->translation['TXT_EVENT_ALL_DAY'].'</span>
						</div>';
					}
					$html .= '
					<div class="clear"></div>
				</div>
				<div class="dp_pec_col6">
					';
					if($this->calendar_obj->form_show_image || false) {
						$html .= '
						<span class="dp_pec_form_desc">'.$this->translation['TXT_EVENT_IMAGE'].'</span>
						<div class="dp_pec_add_image_wrap">
							<label for="event_image">
								<span class="dp_pec_add_image"></span>
							</label>
							<input type="text" class="dp_pec_new_event_text" value="" readonly="readonly" id="event_image_lbl" name="" />
						</div>
						<input type="file" name="event_image" id="event_image" class="event_image" style="visibility:hidden; position: absolute;" />							
						';
					}
					if($this->calendar_obj->form_show_link) {
						$html .= '
						<input type="text" class="dp_pec_new_event_text" value="'.$link.'" placeholder="'.$this->translation['TXT_EVENT_LINK'].'" id="" name="link" />';
					}
					if($this->calendar_obj->form_show_share) {
						$html .= '
						<input type="text" class="dp_pec_new_event_text" value="'.$share.'" placeholder="'.$this->translation['TXT_EVENT_SHARE'].'" id="" name="share" />';
					}
					if($this->calendar_obj->form_show_location) {
						$html .= '
						<input type="text" class="dp_pec_new_event_text" value="'.$location.'" placeholder="'.$this->translation['TXT_EVENT_LOCATION'].'" id="" name="location" />';
					}
					if($this->calendar_obj->form_show_phone) {
						$html .= '
						<input type="text" class="dp_pec_new_event_text" value="'.$phone.'" placeholder="'.$this->translation['TXT_EVENT_PHONE'].'" id="" name="phone" />';
					}
					if($this->calendar_obj->form_show_map) {
						$html .= '
						<input type="text" class="dp_pec_new_event_text" value="'.$map.'" placeholder="'.$this->translation['TXT_EVENT_GOOGLEMAP'].'" id="" name="googlemap" />';
					}
						$html .= '
				</div>
			</div>
			<div class="clear"></div>
			<div class="pec-add-footer">
				<button class="dp_pec_submit_event">'.$this->translation['TXT_SEND'].'</button>
				<div class="clear"></div>
			</div>
			</form>';
		return $html;	
	}
	
	function userHasBooking($date, $event_id) {
		global $current_user, $wpdb, $dpProEventCalendar, $table_prefix;
		
		$querystr = "
            SELECT count(*) as counter
            FROM ".$this->table_booking."
			WHERE id_event = ".$event_id." AND id_user = ".$current_user->ID." AND event_date = '".$date."'
            ";
        $bookings_obj = $wpdb->get_results($querystr, OBJECT);
		
		return $bookings_obj[0]->counter;
	}
	
}
?>