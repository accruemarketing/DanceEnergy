<?php 
// This function displays the admin page content
function dpProEventCalendar_calendars_page() {
	global $dpProEventCalendar, $dpProEventCalendar_cache, $wpdb, $table_prefix;
	$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
	$table_name_events = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
	$table_name_special_dates_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SPECIAL_DATES_CALENDAR;
	$table_name_subscribers_calendar = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_SUBSCRIBERS_CALENDAR;
	
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);

	if ($_POST['submit']) {
	   
	   foreach($_POST as $key=>$value) { $$key = $value; }
	   
	   if($active != 1) { $active = 0; }
	   if($format_ampm != 1) { $format_ampm = 0; }
	   if($show_time != 1) { $show_time = 0; }
	   if($show_search != 1) { $show_search = 0; }
	   if($show_category_filter != 1) { $show_category_filter = 0; }
	   if($show_x != 1) { $show_x = 0; }
	   if($allow_user_add_event != 1) { $allow_user_add_event = 0; }
	   if($allow_user_edit_event != 1) { $allow_user_edit_event = 0; }
	   if($allow_user_remove_event != 1) { $allow_user_remove_event = 0; }
	   if($publish_new_event != 1) { $publish_new_event = 0; }
	   if($show_view_buttons != 1) { $show_view_buttons = 0; }
	   if($show_preview != 1) { $show_preview = 0; }
	   if($show_references != 1) { $show_references = 0; }
	   if($show_author != 1) { $show_author = 0; }
	   if($cache_active != 1) { $cache_active = 0; }
	   if($ical_active != 1) { $ical_active = 0; }
	   if($rss_active != 1) { $rss_active = 0; }
	   if($subscribe_active != 1) { $subscribe_active = 0; }
       if($link_post != 1) { $link_post = 0; }
	   if($email_admin_new_event != 1) { $email_admin_new_event = 0; }
	   if($article_share != 1) { $article_share = 0; }
	   if($hide_old_dates != 1) { $hide_old_dates = 0; }
	   if(!is_numeric($limit_time_start)) { $limit_time_start = 0; }
	   if(!is_numeric($limit_time_end)) { $limit_time_end = 23; }
	   if($assign_events_admin == "") { $assign_events_admin = 0; }
	   if($form_show_description != 1) { $form_show_description = 0; }
	   if($form_show_category != 1) { $form_show_category = 0; }
	   if($form_show_hide_time != 1) { $form_show_hide_time = 0; }
	   if($form_show_frequency != 1) { $form_show_frequency = 0; }
	   if($form_show_all_day != 1) { $form_show_all_day = 0; }
	   if($form_show_image != 1) { $form_show_image = 0; }
	   if($form_show_link != 1) { $form_show_link = 0; }
	   if($form_show_share != 1) { $form_show_share = 0; }
	   if($form_show_location != 1) { $form_show_location = 0; }
	   if($form_show_phone != 1) { $form_show_phone = 0; }
	   if($form_show_map != 1) { $form_show_map = 0; }
	   if($booking_enable != 1) { $booking_enable = 0; }
	   if($booking_comment != 1) { $booking_comment = 0; }
	   
	   if(is_array($category_filter_include)) {
	   	$category_filter_include = implode(",", $category_filter_include);
	   } else {
		$category_filter_include =  "";
	   }
	   	   
	   if (is_numeric($_POST['calendar_id']) && $_POST['calendar_id'] > 0) {
	   	   $wpdb->query("SET NAMES utf8");
		   
	   	   $sql = "UPDATE $table_name SET ";
		   $sql .= "title = '$title', ";
		   $sql .= "description = '$description', ";
		   $sql .= "width = '$width', ";
		   $sql .= "width_unity = '$width_unity', ";
		   if($default_date != '') {
		     	$sql .= "default_date = '$default_date', ";
		   } else {
		     	$sql .= "default_date = null, ";
		   }
		   if($date_range_start != '') {
		   		$sql .= "date_range_start = '$date_range_start', ";
		   } else {
		   		$sql .= "date_range_start = null, ";
		   }
		   if($date_range_end != '') {
		   		$sql .= "date_range_end = '$date_range_end', ";
		   } else {
		   		$sql .= "date_range_end = null, ";
		   }
		   $sql .= "current_date_color = '$current_date_color', ";
		   $sql .= "active = $active, ";
		   $sql .= "hide_old_dates = $hide_old_dates, ";
		   $sql .= "limit_time_start = $limit_time_start, ";
		   $sql .= "limit_time_end = $limit_time_end, ";
		   $sql .= "assign_events_admin = $assign_events_admin, ";
		   $sql .= "cache_active = $cache_active, ";
		   $sql .= "ical_active = $ical_active, ";
		   $sql .= "ical_limit = '$ical_limit', ";
		   $sql .= "rss_active = $rss_active, ";
		   $sql .= "booking_enable = $booking_enable, ";
		   $sql .= "booking_comment = $booking_comment, ";
		   $sql .= "booking_event_color = '$booking_event_color', ";
		   $sql .= "lang_txt_book_event_comment = '$lang_txt_book_event_comment', ";
		   $sql .= "lang_txt_book_event_select_date = '$lang_txt_book_event_select_date', ";
		   $sql .= "lang_txt_book_event_pick_date = '$lang_txt_book_event_pick_date', ";
		   $sql .= "lang_txt_book_event_already_booked = '$lang_txt_book_event_already_booked', ";
		   $sql .= "subscribe_active = $subscribe_active, ";
		   $sql .= "lang_txt_book_event = '$lang_txt_book_event', ";
		   $sql .= "lang_txt_book_event_remove = '$lang_txt_book_event_remove', ";
		   $sql .= "lang_txt_book_event_saved = '$lang_txt_book_event_saved', ";
		   $sql .= "lang_txt_book_event_removed = '$lang_txt_book_event_removed', ";
		   $sql .= "mailchimp_api = '$mailchimp_api', ";
		   $sql .= "mailchimp_list = '$mailchimp_list', ";
		   $sql .= "rss_limit = '$rss_limit', ";
		   $sql .= "link_post = $link_post, ";
		   $sql .= "article_share = $article_share, ";
		   $sql .= "email_admin_new_event = $email_admin_new_event, ";
		   $sql .= "view = '$view', ";
		   $sql .= "format_ampm = $format_ampm, ";
		   $sql .= "show_time = $show_time, ";
		   $sql .= "show_category_filter = $show_category_filter, ";
		   $sql .= "category_filter_include = '$category_filter_include', ";
		   $sql .= "show_search = $show_search, ";
		   $sql .= "show_x = $show_x, ";
		   $sql .= "allow_user_add_event = $allow_user_add_event, ";
		   $sql .= "allow_user_edit_event = $allow_user_edit_event, ";
		   $sql .= "allow_user_remove_event = $allow_user_remove_event, ";
		   $sql .= "publish_new_event = $publish_new_event, ";
		   $sql .= "show_view_buttons = $show_view_buttons, ";
		   $sql .= "show_preview = $show_preview, ";
		   $sql .= "show_references = $show_references, ";
		   $sql .= "show_author = $show_author, ";
		   $sql .= "form_show_description = $form_show_description, ";
		   $sql .= "form_show_category = $form_show_category, ";
		   $sql .= "form_show_hide_time = $form_show_hide_time, ";
		   $sql .= "form_show_frequency = $form_show_frequency, ";
		   $sql .= "form_show_all_day = $form_show_all_day, ";
		   $sql .= "form_show_image = $form_show_image, ";
		   $sql .= "form_show_link = $form_show_link, ";
		   $sql .= "form_show_share = $form_show_share, ";
		   $sql .= "form_show_location = $form_show_location, ";
		   $sql .= "form_show_phone = $form_show_phone, ";
		   $sql .= "form_show_map = $form_show_map, ";
		   $sql .= "first_day = $first_day, ";
		   $sql .= "lang_txt_no_events_found = '$lang_txt_no_events_found', ";
		   $sql .= "lang_txt_all_day = '$lang_txt_all_day', ";
		   $sql .= "lang_txt_references = '$lang_txt_references', ";
		   $sql .= "lang_txt_view_all_events = '$lang_txt_view_all_events', ";
		   $sql .= "lang_txt_all_categories = '$lang_txt_all_categories', ";
		   $sql .= "lang_txt_monthly = '$lang_txt_monthly', ";
		   $sql .= "lang_txt_daily = '$lang_txt_daily', ";
		   $sql .= "lang_txt_all_working_days = '$lang_txt_all_working_days', ";
		   $sql .= "lang_txt_search = '$lang_txt_search', ";
		   $sql .= "lang_txt_results_for = '$lang_txt_results_for', ";
		   $sql .= "lang_txt_by = '$lang_txt_by', ";
		   $sql .= "lang_txt_current_date = '$lang_txt_current_date', ";
		   $sql .= "lang_prev_month = '$lang_prev_month', ";
		   $sql .= "lang_next_month = '$lang_next_month', ";
		   $sql .= "lang_prev_day = '$lang_prev_day', ";
		   $sql .= "lang_next_day = '$lang_next_day', ";
		   $sql .= "lang_day_sunday = '$lang_day_sunday', ";
		   $sql .= "lang_day_monday = '$lang_day_monday', ";
		   $sql .= "lang_day_tuesday = '$lang_day_tuesday', ";
		   $sql .= "lang_day_wednesday = '$lang_day_wednesday', ";
		   $sql .= "lang_day_thursday = '$lang_day_thursday', ";
		   $sql .= "lang_day_friday = '$lang_day_friday', ";
		   $sql .= "lang_day_saturday = '$lang_day_saturday', ";
		   $sql .= "lang_month_january = '$lang_month_january', ";
		   $sql .= "lang_month_february = '$lang_month_february', ";
		   $sql .= "lang_month_march = '$lang_month_march', ";
		   $sql .= "lang_month_april = '$lang_month_april', ";
		   $sql .= "lang_month_may = '$lang_month_may', ";
		   $sql .= "lang_month_june = '$lang_month_june', ";
		   $sql .= "lang_month_july = '$lang_month_july', ";
		   $sql .= "lang_month_august = '$lang_month_august', ";
		   $sql .= "lang_month_september = '$lang_month_september', ";
		   $sql .= "lang_month_october = '$lang_month_october', ";
		   $sql .= "lang_month_november = '$lang_month_november', ";
		   $sql .= "lang_month_december = '$lang_month_december', ";
		   $sql .= "lang_txt_category = '$lang_txt_category', ";
		   $sql .= "lang_txt_subscribe = '$lang_txt_subscribe', ";
		   $sql .= "lang_txt_subscribe_subtitle = '$lang_txt_subscribe_subtitle', ";
		   $sql .= "lang_txt_your_name = '$lang_txt_your_name', ";
		   $sql .= "lang_txt_your_email = '$lang_txt_your_email', ";
		   $sql .= "lang_txt_fields_required = '$lang_txt_fields_required', ";
		   $sql .= "lang_txt_invalid_email = '$lang_txt_invalid_email', ";
		   $sql .= "lang_txt_subscribe_thanks = '$lang_txt_subscribe_thanks', ";
		   $sql .= "lang_txt_sending = '$lang_txt_sending', ";
		   $sql .= "lang_txt_send = '$lang_txt_send', ";
		   $sql .= "lang_txt_add_event = '$lang_txt_add_event', ";
		   $sql .= "lang_txt_edit_event = '$lang_txt_edit_event', ";
		   $sql .= "lang_txt_remove_event = '$lang_txt_remove_event', ";
		   $sql .= "lang_txt_remove_event_confirm = '$lang_txt_remove_event_confirm', ";
		   $sql .= "lang_txt_cancel = '$lang_txt_cancel', ";
		   $sql .= "lang_txt_logged_to_submit = '$lang_txt_logged_to_submit', ";
		   $sql .= "lang_txt_thanks_for_submit = '$lang_txt_thanks_for_submit', ";
		   $sql .= "lang_txt_event_title = '$lang_txt_event_title', ";
		   $sql .= "lang_txt_event_description = '$lang_txt_event_description', ";
		   $sql .= "lang_txt_event_link = '$lang_txt_event_link', ";
		   $sql .= "lang_txt_event_share = '$lang_txt_event_share', ";
		   $sql .= "lang_txt_event_image = '$lang_txt_event_image', ";
		   $sql .= "lang_txt_event_location = '$lang_txt_event_location', ";
		   $sql .= "lang_txt_event_phone = '$lang_txt_event_phone', ";
		   $sql .= "lang_txt_event_googlemap = '$lang_txt_event_googlemap', ";
		   $sql .= "lang_txt_event_start_date = '$lang_txt_event_start_date', ";
		   $sql .= "lang_txt_event_all_day = '$lang_txt_event_all_day', ";
		   $sql .= "lang_txt_event_start_time = '$lang_txt_event_start_time', ";
		   $sql .= "lang_txt_event_hide_time = '$lang_txt_event_hide_time', ";
		   $sql .= "lang_txt_event_end_time = '$lang_txt_event_end_time', ";
		   $sql .= "lang_txt_event_frequency = '$lang_txt_event_frequency', ";
		   $sql .= "lang_txt_event_none = '$lang_txt_event_none', ";
		   $sql .= "lang_txt_event_daily = '$lang_txt_event_daily', ";
		   $sql .= "lang_txt_event_weekly = '$lang_txt_event_weekly', ";
		   $sql .= "lang_txt_event_monthly = '$lang_txt_event_monthly', ";
		   $sql .= "lang_txt_event_yearly = '$lang_txt_event_yearly', ";
		   $sql .= "lang_txt_event_end_date = '$lang_txt_event_end_date', ";
		   $sql .= "lang_txt_event_submit = '$lang_txt_event_submit', ";
		   $sql .= "lang_txt_yes = '$lang_txt_yes', ";
		   $sql .= "lang_txt_no = '$lang_txt_no', ";
		   $sql .= "skin = '$skin' ";
		   $sql .= "WHERE id = $calendar_id ";
		   $result = $wpdb->query($sql);

	   } else {
		   
		   $sql = "INSERT INTO $table_name (";
		   $sql .= "title, ";
		   $sql .= "description, ";
		   $sql .= "width, ";
		   $sql .= "width_unity, ";
		   if($default_date != '') {
		   	$sql .= "default_date, ";
		   }
		   if($date_range_start != '') {
		   	$sql .= "date_range_start, ";
		   }
		   if($date_range_end != '') {
		   	$sql .= "date_range_end, ";
		   }
		   $sql .= "current_date_color, ";
		   $sql .= "active, ";
		   $sql .= "hide_old_dates, ";
		   $sql .= "limit_time_start, ";
		   $sql .= "limit_time_end, ";
		   $sql .= "assign_events_admin, ";
		   $sql .= "cache_active, ";
		   $sql .= "ical_active, ";
		   $sql .= "ical_limit, ";
		   $sql .= "rss_active, ";
		   $sql .= "booking_enable, ";
		   $sql .= "booking_comment, ";
		   $sql .= "booking_event_color, ";
		   $sql .= "lang_txt_book_event_comment, ";
		   $sql .= "lang_txt_book_event_select_date, ";
		   $sql .= "lang_txt_book_event_pick_date, ";
		   $sql .= "lang_txt_book_event_already_booked, ";
		   $sql .= "subscribe_active, ";
		   $sql .= "lang_txt_book_event, ";
		   $sql .= "lang_txt_book_event_remove, ";
		   $sql .= "lang_txt_book_event_saved, ";
		   $sql .= "lang_txt_book_event_removed, ";
		   $sql .= "mailchimp_api, ";
		   $sql .= "mailchimp_list, ";
		   $sql .= "rss_limit, ";
		   $sql .= "link_post, ";
		   $sql .= "article_share, ";
		   $sql .= "email_admin_new_event, ";
		   $sql .= "view, ";
		   $sql .= "format_ampm, ";
		   $sql .= "show_time, ";
		   $sql .= "show_category_filter, ";
		   $sql .= "category_filter_include, ";
		   $sql .= "show_search, ";
		   $sql .= "show_x, ";
		   $sql .= "allow_user_add_event, ";
		   $sql .= "allow_user_edit_event, ";
		   $sql .= "allow_user_remove_event, ";
		   $sql .= "publish_new_event, ";
		   $sql .= "show_view_buttons, ";
		   $sql .= "show_preview, ";
		   $sql .= "show_references, ";
		   $sql .= "show_author, ";
		   $sql .= "form_show_description, ";
		   $sql .= "form_show_category, ";
		   $sql .= "form_show_hide_time, ";
		   $sql .= "form_show_frequency, ";
		   $sql .= "form_show_all_day, ";
		   $sql .= "form_show_image, ";
		   $sql .= "form_show_link, ";
		   $sql .= "form_show_share, ";
		   $sql .= "form_show_location, ";
		   $sql .= "form_show_phone, ";
		   $sql .= "form_show_map, ";
		   $sql .= "first_day, ";
		   $sql .= "lang_txt_no_events_found, ";
		   $sql .= "lang_txt_all_day, ";
		   $sql .= "lang_txt_references, ";
		   $sql .= "lang_txt_view_all_events, ";
		   $sql .= "lang_txt_all_categories, ";
		   $sql .= "lang_txt_monthly, ";
		   $sql .= "lang_txt_daily, ";
		   $sql .= "lang_txt_all_working_days, ";
		   $sql .= "lang_txt_search, ";
		   $sql .= "lang_txt_results_for, ";
		   $sql .= "lang_txt_by, ";
		   $sql .= "lang_txt_current_date, ";
		   $sql .= "lang_prev_month, ";
		   $sql .= "lang_next_month, ";
		   $sql .= "lang_prev_day, ";
		   $sql .= "lang_next_day, ";
		   $sql .= "lang_day_sunday, ";
		   $sql .= "lang_day_monday, ";
		   $sql .= "lang_day_tuesday, ";
		   $sql .= "lang_day_wednesday, ";
		   $sql .= "lang_day_thursday, ";
		   $sql .= "lang_day_friday, ";
		   $sql .= "lang_day_saturday, ";
		   $sql .= "lang_month_january, ";
		   $sql .= "lang_month_february, ";
		   $sql .= "lang_month_march, ";
		   $sql .= "lang_month_april, ";
		   $sql .= "lang_month_may, ";
		   $sql .= "lang_month_june, ";
		   $sql .= "lang_month_july, ";
		   $sql .= "lang_month_august, ";
		   $sql .= "lang_month_september, ";
		   $sql .= "lang_month_october, ";
		   $sql .= "lang_month_november, ";
		   $sql .= "lang_month_december, ";
		   $sql .= "lang_txt_category, ";
		   $sql .= "lang_txt_subscribe, ";
		   $sql .= "lang_txt_subscribe_subtitle, ";
		   $sql .= "lang_txt_your_name, ";
		   $sql .= "lang_txt_your_email, ";
		   $sql .= "lang_txt_fields_required, ";
		   $sql .= "lang_txt_invalid_email, ";
		   $sql .= "lang_txt_subscribe_thanks, ";
		   $sql .= "lang_txt_sending, ";
		   $sql .= "lang_txt_add_event, ";
		   $sql .= "lang_txt_edit_event, ";
		   $sql .= "lang_txt_remove_event, ";
		   $sql .= "lang_txt_remove_event_confirm, ";
		   $sql .= "lang_txt_cancel, ";
		   $sql .= "lang_txt_yes, ";
		   $sql .= "lang_txt_no, ";
		   $sql .= "lang_txt_logged_to_submit, ";
		   $sql .= "lang_txt_thanks_for_submit, ";
		   $sql .= "lang_txt_event_title, ";
		   $sql .= "lang_txt_event_description, ";
		   $sql .= "lang_txt_event_link, ";
		   $sql .= "lang_txt_event_share, ";
		   $sql .= "lang_txt_event_image, ";
		   $sql .= "lang_txt_event_location, ";
		   $sql .= "lang_txt_event_phone, ";
		   $sql .= "lang_txt_event_googlemap, ";
		   $sql .= "lang_txt_event_start_date, ";
		   $sql .= "lang_txt_event_all_day, ";
		   $sql .= "lang_txt_event_start_time, ";
		   $sql .= "lang_txt_event_hide_time, ";
		   $sql .= "lang_txt_event_end_time, ";
		   $sql .= "lang_txt_event_frequency, ";
		   $sql .= "lang_txt_event_none, ";
		   $sql .= "lang_txt_event_daily, ";
		   $sql .= "lang_txt_event_weekly, ";
		   $sql .= "lang_txt_event_monthly, ";
		   $sql .= "lang_txt_event_yearly, ";
		   $sql .= "lang_txt_event_end_date, ";
		   $sql .= "lang_txt_event_submit, ";
		   $sql .= "skin ";
		   $sql .= ") VALUES ( ";
		   $sql .= "'$title', ";
		   $sql .= "'$description', ";
		   $sql .= "'$width', ";
		   $sql .= "'$width_unity', ";
		   if($default_date != '') {
		   	$sql .= "'$default_date', ";
		   }
		   if($date_range_start != '') {
		   	$sql .= "'$date_range_start', ";
		   }
		   if($date_range_end != '') {
		   	$sql .= "'$date_range_end', ";
		   }
		   $sql .= "'$current_date_color', ";
		   $sql .= "$active, ";
		   $sql .= "$hide_old_dates, ";
		   $sql .= "$limit_time_start, ";
		   $sql .= "$limit_time_end, ";
		   $sql .= "$assign_events_admin, ";
		   $sql .= "$cache_active, ";
		   $sql .= "$ical_active, ";
		   $sql .= "'$ical_limit', ";
		   $sql .= "$rss_active, ";
		   $sql .= "$booking_enable, ";
		   $sql .= "$booking_comment, ";
		   $sql .= "'$booking_event_color', ";
		   $sql .= "'$lang_txt_book_event_comment', ";
		   $sql .= "'$lang_txt_book_event_select_date', ";
		   $sql .= "'$lang_txt_book_event_pick_date', ";
		   $sql .= "'$lang_txt_book_event_already_booked', ";
		   $sql .= "$subscribe_active, ";
		   $sql .= "'$lang_txt_book_event', ";
		   $sql .= "'$lang_txt_book_event_remove', ";
		   $sql .= "'$lang_txt_book_event_saved', ";
		   $sql .= "'$lang_txt_book_event_removed', ";
		   $sql .= "'$mailchimp_api', ";
		   $sql .= "'$mailchimp_list', ";
		   $sql .= "'$rss_limit', ";
		   $sql .= "$link_post, ";
		   $sql .= "$article_share, ";
		   $sql .= "$email_admin_new_event, ";
		   $sql .= "'$view', ";
		   $sql .= "$format_ampm, ";
		   $sql .= "$show_time, ";
		   $sql .= "$show_category_filter, ";
		   $sql .= "'$category_filter_include', ";
		   $sql .= "$show_search, ";
		   $sql .= "$show_x, ";
		   $sql .= "$allow_user_add_event, ";
		   $sql .= "$allow_user_edit_event, ";
		   $sql .= "$allow_user_remove_event, ";
		   $sql .= "$publish_new_event, ";
		   $sql .= "$show_view_buttons, ";
		   $sql .= "$show_preview, ";
		   $sql .= "$show_references, ";
		   $sql .= "$show_author, ";
		   $sql .= "$form_show_description, ";
		   $sql .= "$form_show_category, ";
		   $sql .= "$form_show_hide_time, ";
		   $sql .= "$form_show_frequency, ";
		   $sql .= "$form_show_all_day, ";
		   $sql .= "$form_show_image, ";
		   $sql .= "$form_show_link, ";
		   $sql .= "$form_show_share, ";
		   $sql .= "$form_show_location, ";
		   $sql .= "$form_show_phone, ";
		   $sql .= "$form_show_map, ";
		   $sql .= "$first_day, ";
		   $sql .= "'$lang_txt_no_events_found', ";
		   $sql .= "'$lang_txt_all_day', ";
		   $sql .= "'$lang_txt_references', ";
		   $sql .= "'$lang_txt_view_all_events', ";
		   $sql .= "'$lang_txt_all_categories', ";
		   $sql .= "'$lang_txt_monthly', ";
		   $sql .= "'$lang_txt_daily', ";
		   $sql .= "'$lang_txt_all_working_days', ";
		   $sql .= "'$lang_txt_search', ";
		   $sql .= "'$lang_txt_results_for', ";
		   $sql .= "'$lang_txt_by', ";
		   $sql .= "'$lang_txt_current_date', ";
		   $sql .= "'$lang_prev_month', ";
		   $sql .= "'$lang_next_month', ";
		   $sql .= "'$lang_prev_day', ";
		   $sql .= "'$lang_next_day', ";
		   $sql .= "'$lang_day_sunday', ";
		   $sql .= "'$lang_day_monday', ";
		   $sql .= "'$lang_day_tuesday', ";
		   $sql .= "'$lang_day_wednesday', ";
		   $sql .= "'$lang_day_thursday', ";
		   $sql .= "'$lang_day_friday', ";
		   $sql .= "'$lang_day_saturday', ";
		   $sql .= "'$lang_month_january', ";
		   $sql .= "'$lang_month_february', ";
		   $sql .= "'$lang_month_march', ";
		   $sql .= "'$lang_month_april', ";
		   $sql .= "'$lang_month_may', ";
		   $sql .= "'$lang_month_june', ";
		   $sql .= "'$lang_month_july', ";
		   $sql .= "'$lang_month_august', ";
		   $sql .= "'$lang_month_september', ";
		   $sql .= "'$lang_month_october', ";
		   $sql .= "'$lang_month_november', ";
		   $sql .= "'$lang_month_december', ";
		   $sql .= "'$lang_txt_category', ";
		   $sql .= "'$lang_txt_subscribe', ";
		   $sql .= "'$lang_txt_subscribe_subtitle', ";
		   $sql .= "'$lang_txt_your_name', ";
		   $sql .= "'$lang_txt_your_email', ";
		   $sql .= "'$lang_txt_fields_required', ";
		   $sql .= "'$lang_txt_invalid_email', ";
		   $sql .= "'$lang_txt_subscribe_thanks', ";
		   $sql .= "'$lang_txt_sending', ";
		   $sql .= "'$lang_txt_add_event', ";
		   $sql .= "'$lang_txt_edit_event', ";
		   $sql .= "'$lang_txt_remove_event', ";
		   $sql .= "'$lang_txt_remove_event_confirm', ";
		   $sql .= "'$lang_txt_cancel', ";
		   $sql .= "'$lang_txt_yes', ";
		   $sql .= "'$lang_txt_no', ";
		   $sql .= "'$lang_txt_logged_to_submit', ";
		   $sql .= "'$lang_txt_thanks_for_submit', ";
		   $sql .= "'$lang_txt_event_title', ";
		   $sql .= "'$lang_txt_event_description', ";
		   $sql .= "'$lang_txt_event_link', ";
		   $sql .= "'$lang_txt_event_share', ";
		   $sql .= "'$lang_txt_event_image', ";
		   $sql .= "'$lang_txt_event_location', ";
		   $sql .= "'$lang_txt_event_phone', ";
		   $sql .= "'$lang_txt_event_googlemap', ";
		   $sql .= "'$lang_txt_event_start_date', ";
		   $sql .= "'$lang_txt_event_all_day', ";
		   $sql .= "'$lang_txt_event_start_time', ";
		   $sql .= "'$lang_txt_event_hide_time', ";
		   $sql .= "'$lang_txt_event_end_time', ";
		   $sql .= "'$lang_txt_event_frequency', ";
		   $sql .= "'$lang_txt_event_none', ";
		   $sql .= "'$lang_txt_event_daily', ";
		   $sql .= "'$lang_txt_event_weekly', ";
		   $sql .= "'$lang_txt_event_monthly', ";
		   $sql .= "'$lang_txt_event_yearly', ";
		   $sql .= "'$lang_txt_event_end_date', ";
		   $sql .= "'$lang_txt_event_submit', ";
		   $sql .= "'$skin' ";
		   $sql .= ");";
		   $result = $wpdb->query($sql);
//die($sql."<br>".mysql_error());
		   $calendar_id = $wpdb->insert_id;
	   }
	   
   	   if(isset($dpProEventCalendar_cache['calendar_id_'.$calendar_id])) {
		   $dpProEventCalendar_cache['calendar_id_'.$calendar_id] = array();
		   update_option( 'dpProEventCalendar_cache', $dpProEventCalendar_cache );
	   }
	   
	   wp_redirect( admin_url('admin.php?page=dpProEventCalendar-admin&settings-updated=1') );
	   exit;
	}
	
	if(!empty($_FILES['pec_ical_file']['name'])) {
		$calendar_id = $_POST['pec_id_calendar_ics'];
		$category_ics = $_POST['pec_category_ics'];
		
		$extensions = array('.ics');
		$extension = strrchr($_FILES['pec_ical_file']['name'], '.'); 
		if(in_array($extension, $extensions)) {
			include(dirname(__FILE__) . '/../includes/ical_parser.php');
			$ical = new ICal($_FILES['pec_ical_file']['tmp_name']);
			$feed = $ical->cal;
			if(!empty($feed)) {
				foreach($feed['VEVENT'] as $key) {
					
					foreach($key as $k => $v) {
						$key[substr($k, 0, strpos($k, ';'))] = $v;	
					}
					
					$args = array( 
						'posts_per_page' => 1, 
						'post_type'=> 'pec-events', 
						"meta_query" => array(
							'relation' => 'AND',
							array(
							   'key' => 'pec_id_calendar',
							   'value' => $calendar_id,
							),
							array(
							   'key' => 'pec_ics_uid',
							   'value' => $key['UID'],
							)
						)
					);
					
					$imported_posts = get_posts( $args );
					
					// Create post object
					$ics_event = array(
					  'post_title'    => $key['SUMMARY'],
					  'post_content'  => $key['DESCRIPTION'],
					  'post_status'   => 'publish',
					  'tax_input' 	  => array( 'pec_events_category' => $category_ics ),
					  'post_type'	  => 'pec-events'
					);
					
					if(!empty($imported_posts)) {
						$ics_event['ID'] = $imported_posts[0]->ID;
					}
					
					$rrule = explode(';', $key['RRULE']);
					$rrule_arr = array();
					if(is_array($rrule)) {
						foreach($rrule as $rule) {
							$rrule_arr[substr($rule, 0, strpos($rule, '='))] = substr($rule, strrpos($rule, '=') + 1);
						}
					}
					//print_r( $key );
					//echo 'Event Created: '.$key['SUMMARY'].'<br/>';
					//die();
					
					// Insert the post into the database
					$post_id = wp_insert_post( $ics_event );
					
					update_post_meta($post_id, 'pec_id_calendar', $calendar_id);
					update_post_meta($post_id, 'pec_date', date("Y-m-d h:i:s", strtotime($key['DTSTART'])));
					update_post_meta($post_id, 'pec_all_day', '');
					
					if(is_array($rrule_arr)) {
						
						foreach($rrule_arr as $key2 => $value) {
							
							if($key2 == 'FREQ') {
								$recurring_frecuency = '';
								switch($value) {
									case 'DAILY':
										$recurring_frecuency = '1';
										break;
									case 'WEEKLY':
										$recurring_frecuency = '2';
										break;
									case 'MONTHLY':
										$recurring_frecuency = '3';
										break;
									case 'YEARLY':
										$recurring_frecuency = '4';
										break;
								}
								update_post_meta($post_id, 'pec_recurring_frecuency', $recurring_frecuency);
							}
							
							if($key2 == 'FREQ' && $value == 'DAILY') {
								update_post_meta($post_id, 'pec_daily_every', $rrule_arr['INTERVAL']);

								update_post_meta($post_id, 'pec_daily_working_days', '');
							}
							
							if($key2 == 'FREQ' && $value == 'WEEKLY') {
								$day_arr = array();
								foreach(explode(',', $rrule_arr['BYDAY']) as $day) {
									switch($day) {
										case 'MO':
											$day_arr[] = 1;
											break;
										case 'TU':
											$day_arr[] = 2;
											break;
										case 'WE':
											$day_arr[] = 3;
											break;
										case 'TH':
											$day_arr[] = 4;
											break;
										case 'FR':
											$day_arr[] = 5;
											break;
										case 'SA':
											$day_arr[] = 6;
											break;
										case 'SU':
											$day_arr[] = 7;
											break;
									}
									
									update_post_meta($post_id, 'pec_weekly_day', $day_arr);
								}

								update_post_meta($post_id, 'pec_weekly_every', $rrule_arr['INTERVAL']);

							}
							
							if($key2 == 'FREQ' && $value == 'MONTHLY') {
								
								update_post_meta($post_id, 'pec_monthly_every', $rrule_arr['INTERVAL']);
								
								$setpos = "";
								switch($rrule_arr['BYSETPOS']) {
									case '1':
										$setpos = 'first';
										break;
									case '2':
										$setpos = 'second';
										break;
									case '3':
										$setpos = 'third';
										break;
									case '4':
										$setpos = 'fourth';
										break;
									case '-1':
										$setpos = 'last';
										break;
								}
								update_post_meta($post_id, 'pec_monthly_position', $setpos);
								
								$day_arr = '';
								foreach(explode(',', $rrule_arr['BYDAY']) as $day) {
									switch($day) {
										case 'MO':
											$day_arr = 'monday';
											break;
										case 'TU':
											$day_arr = 'tuesday';
											break;
										case 'WE':
											$day_arr = 'wednesday';
											break;
										case 'TH':
											$day_arr = 'thursday';
											break;
										case 'FR':
											$day_arr = 'friday';
											break;
										case 'SA':
											$day_arr = 'saturday';
											break;
										case 'SU':
											$day_arr = 'sunday';
											break;
									}
								}
								update_post_meta($post_id, 'pec_monthly_day', $day_arr);
							}
						}
					}
					update_post_meta($post_id, 'pec_end_date', ($recurring_frecuency != '' ? '' : date("Y-m-d", strtotime($key['DTEND']))));
					update_post_meta($post_id, 'pec_link', $key['URL']);
					update_post_meta($post_id, 'pec_share', '');
					update_post_meta($post_id, 'pec_map', '');
					update_post_meta($post_id, 'pec_end_time_hh', date("h", strtotime($key['DTEND'])));
					update_post_meta($post_id, 'pec_end_time_mm', date("i", strtotime($key['DTEND'])));
					update_post_meta($post_id, 'pec_hide_time', '');
					update_post_meta($post_id, 'pec_location', $key['LOCATION']);	
					update_post_meta($post_id, 'pec_ics_uid', $key['UID']);						
				}

			}
		}
	   	wp_redirect( admin_url('admin.php?page=dpProEventCalendar-admin&settings-updated=1') );
	   exit;
   }
	
	if ($_GET['delete_calendar']) {
	   $calendar_id = $_GET['delete_calendar'];
	   
	   $args = array( 
			'posts_per_page' => -1, 
			'post_type' => 'pec-events', 
			'meta_key' => 'pec_id_calendar',
			'meta_value' => $calendar_id
		);
					
	   $delete_posts = get_posts( $args );
	   if(!empty($delete_posts)) {
		   foreach($delete_posts as $key) {
	   			wp_delete_post($key->ID);
		   }
	   }
	   	
	   $sql = "DELETE FROM $table_name WHERE id = $calendar_id;";
	   $result = $wpdb->query($sql);
	   
	   $sql = "DELETE FROM $table_name_special_dates_calendar WHERE calendar = $calendar_id;";
	   $result = $wpdb->query($sql);
	   	   
	   wp_redirect( admin_url('admin.php?page=dpProEventCalendar-admin&settings-updated=1') );
	   exit;
	}
	
	if ($_GET['delete_subscriber']) {
	   $subscriber_id = $_GET['delete_subscriber'];
	   $calendar_id = $_GET['edit'];
	   	
	   $sql = "DELETE FROM $table_name_subscribers_calendar WHERE calendar = ".$calendar_id." AND id = ".$subscriber_id.";";
	   $result = $wpdb->query($sql);
	   	   
	   wp_redirect( admin_url('admin.php?page=dpProEventCalendar-admin&edit='.$calendar_id.'&settings-updated=1') );
	   exit;
	}
	
	
	require_once (dirname (__FILE__) . '/../classes/base.class.php');
	
	
	?>
    <script type="text/javascript">
	function MailChimp_getList() {
		jQuery('#div_mailchimp_list').hide();
		
		if(jQuery('#mailchimp_api_key').val() != "") {
			jQuery.post("<?php echo dpProEventCalendar_plugin_url('ajax/MailChimp_getLists.php')?>", { mailchimp_api: jQuery('#mailchimp_api_key').val() },
			   function(data) {
				 jQuery('#mailchimp_list').html(data);
				 jQuery('#div_mailchimp_list').show();
			   }
			);
			
		}
	}
	</script>

	<div class="wrap" style="clear:both;" id="dp_options">
    <h2></h2>
	<div style="clear:both;"></div>
 	<!--end of poststuff --> 
 	<div id="dp_ui_content">
    	
        <div id="leftSide">
        	<div id="dp_logo"></div>
            <p>
                Version: <?php echo DP_PRO_EVENT_CALENDAR_VER?><br />
            </p>
            <ul id="menu" class="nav">
            	<li><a href="admin.php?page=dpProEventCalendar-settings" title=""><span><?php _e('General Settings','dpProEventCalendar'); ?></span></a></li>
                <li><a href="javascript:void(0);" class="active" title=""><span><?php _e('Calendars','dpProEventCalendar'); ?></span></a></li>
                <li><a href="edit.php?post_type=pec-events" title=""><span><?php _e('Events','dpProEventCalendar'); ?></span></a></li>
                <li><a href="admin.php?page=dpProEventCalendar-special" title=""><span><?php _e('Special Dates','dpProEventCalendar'); ?></span></a></li>
                <li><a href="admin.php?page=dpProEventCalendar-custom-shortcodes" title=""><span><?php _e('Custom Shortcodes','dpProEventCalendar'); ?></span></a></li>
            </ul>
            
            <div class="clear"></div>
		</div>     
		<?php if(!is_numeric($_GET['add']) && !is_numeric($_GET['edit'])) {	?>
 
        
        <div id="rightSide">
        	<div id="menu_general_settings">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h2><?php _e('Calendars List','dpProEventCalendar'); ?></h2>
                            <span><?php _e('Use the shortcode in your posts or pages.','dpProEventCalendar'); ?></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">

                <form action="" method="post" enctype="multipart/form-data">
					<?php settings_fields('dpProEventCalendar-group'); ?>
                    
                    <input type="hidden" name="remove_posts_calendar" value="1" />
                    
                    	<div class="submit">
                        
                        <input type="button" value="<?php echo __( 'Add new calendar', 'dpProEventCalendar' )?>" name="add_calendar" onclick="location.href='<?php echo dpProEventCalendar_admin_url( array( 'add' => '1' ) )?>';" />
                        
                        </div>
                        <table class="widefat" cellpadding="0" cellspacing="0" id="sort-table">
                        	<thead>
                        		<tr style="cursor:default !important;">
                                	<th><?php _e('ID','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Default Shortcode','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Title','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Description','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Events','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Actions','dpProEventCalendar'); ?></th>
                                 </tr>
                            </thead>
                            <tbody>
                        <?php 
						$counter = 0;
						$cal_output = "";
                        $querystr = "
                        SELECT calendars.*
                        FROM $table_name calendars
                        ORDER BY calendars.title ASC
                        ";
                        $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                        foreach($calendars_obj as $calendar) {
							$dpProEventCalendar_class = new DpProEventCalendar( true, (is_numeric($calendar->id) ? $calendar->id : null) );
							
							$dpProEventCalendar_class->addScripts(true);
							
							$calendar_nonce = $dpProEventCalendar_class->getNonce();
							$args = array( 'numberposts' => -1, 'meta_key'=> 'pec_id_calendar', 'meta_value' => $calendar->id, 'post_status' => 'publish', 'post_type' => 'pec-events' );

							$events_cal = get_posts( $args );
							$events_count = count($events_cal);
							
                            echo '<tr id="'.$calendar->id.'">
									<td width="5%">'.$calendar->id.'</td>
									<td width="20%">[dpProEventCalendar id='.$calendar->id.']</td>
									<td width="20%">'.$calendar->title.'</td>
									<td width="20%">'.$calendar->description.'</td>
									<td width="5%"><a href="'.admin_url('edit.php?s&post_status=all&post_type=pec-events&action=-1&m=0&pec_id_calendar='.$calendar->id.'&paged=1').'">'.$events_count.'</a></td>
									<td width="30%">
										<input type="button" value="'.__( 'Edit', 'dpProEventCalendar' ).'" name="edit_calendar" class="button-secondary" onclick="location.href=\''.admin_url('admin.php?page=dpProEventCalendar-admin&edit='.$calendar->id).'\';" />
										<input type="button" value="'.__( 'Special Dates', 'dpProEventCalendar' ).'" name="sp_calendar" data-calendar-id="'.$calendar->id.'" data-calendar-nonce="'.$calendar_nonce.'" class="btn_manage_special_dates button-secondary" />
										<input type="button" value="'.__( 'Delete', 'dpProEventCalendar' ).'" name="delete_calendar" class="button-secondary" onclick="if(confirmCalendarDelete()) { location.href=\''.admin_url('admin.php?page=dpProEventCalendar-admin&delete_calendar='.$calendar->id.'&noheader=true').'\'; }" />
									</td>
								</tr>'; 
							$counter++;
							$cal_output .= $dpProEventCalendar_class->output();
                        }
                        ?>
                        
                    		</tbody>
                            <tfoot>
                            	<tr style="cursor:default !important;">
                                	<th><?php _e('ID','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Default Shortcode','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Title','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Description','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Events','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Actions','dpProEventCalendar'); ?></th>
                                 </tr>
                            </tfoot>
                        </table>
                        
                        <div class="submit">
                        
                        <input type="button" value="<?php echo __( 'Add new calendar', 'dpProEventCalendar' )?>" name="add_calendar" onclick="location.href='<?php echo dpProEventCalendar_admin_url( array( 'add' => '1' ) )?>';" />
                        
                        </div>
                        <div class="clear"></div>
                        
                        <h2><?php _e('Import iCal Feed','dpProEventCalendar'); ?></h2>
                        
                        
                        <select name="pec_id_calendar_ics" id="pec_id_calendar_ics">
                            <option value=""><?php _e('Select a Calendar','dpProEventCalendar'); ?></option>
                            <?php
                            $querystr = "
                            SELECT *
                            FROM $table_name
                            ORDER BY title ASC
                            ";
                            $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                            if(is_array($calendars_obj)) {
                                foreach($calendars_obj as $calendar) {
                            ?>
                                <option value="<?php echo $calendar->id?>"><?php echo $calendar->title?></option>
                            <?php }
                            }?>
                        </select>
                        &nbsp;&nbsp;
                        <select name="pec_category_ics" id="pec_category_ics">
                            <option value=""><?php _e('Select a Category (optional)','dpProEventCalendar'); ?></option>
                            <?php
                           $categories = get_categories('taxonomy=pec_events_category'); 
						   if(is_array($categories)) {
							  foreach ($categories as $category) {
								$option = '<option value="'.$category->term_id.'">';
								$option .= $category->cat_name;
								$option .= ' ('.$category->category_count.')';
								$option .= '</option>';
								echo $option;
							  }
						   }?>
                        </select>
                        &nbsp;&nbsp;
                        <?php _e('Select the .ics file. ','dpProEventCalendar'); ?>(<?php _e('Max', 'theme')?> <?php echo $upload_mb?>mb)
                        <input type="file" name="pec_ical_file" id="pec_ical_file" />

                        <div class="submit">
                        
                        <input type="submit" value="<?php echo __( 'Import Events', 'dpProEventCalendar' )?>" name="import_events"  />
                        
                        </div>
                        <div class="clear"></div>
                 </form>
                 <?php echo $cal_output?>
             	</div>
            </div> 
        </div>
        <?php } elseif(is_numeric($_GET['add']) || is_numeric($_GET['edit'])) {
		
		if(is_numeric($_GET['edit'])){
			$calendar_id = $_GET['edit'];
			$querystr = "
			SELECT *
			FROM $table_name 
			WHERE id = $calendar_id
			";
			$calendar_obj = $wpdb->get_results($querystr, OBJECT);
			$calendar_obj = $calendar_obj[0];	
			foreach($calendar_obj as $key=>$value) { $$key = $value; }
			
			$category_filter_include = explode(',', $category_filter_include);
		} else {
			$width_unity = '%';
			$width = 100;	
			$booking_event_color = '#e14d43';
		}
		
		$dpProEventCalendar_class = new DpProEventCalendar( true, (is_numeric($calendar_id) ? $calendar_id : null) );
		
		$dpProEventCalendar_class->addScripts(true);
		?>
        <div id="rightSide">
        	<div id="menu_general_settings">
                <div class="titleArea">
                    <div class="wrapper">
                        <div class="pageTitle">
                            <h2><?php _e('Calendar','dpProEventCalendar'); ?></h2>
                            <span><?php _e('Customize the Calendar.','dpProEventCalendar'); ?></span>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div class="wrapper">
        
       		<form method="post" action="<?php echo admin_url('admin.php?page=dpProEventCalendar-admin&noheader=true'); ?>" onsubmit="return calendar_checkform();" enctype="multipart/form-data">
            <input type="hidden" name="submit" value="1" />
            <?php if(is_numeric($id) && $id > 0) {?>
            	<input type="hidden" name="calendar_id" value="<?php echo $id?>" />
            <?php }?>
            <?php settings_fields('dpProEventCalendar-group'); ?>
            <div style="clear:both;"></div>
             <!--end of poststuff --> 
             	
                <h2 class="subtitle accordion_title" onclick="showAccordion('div_general_settings');">General Settings</h2>
                <div id="div_general_settings">
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Active','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="active" id="dpProEventCalendar_active" class="checkbox" <?php if($active) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('On/Off the calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
        
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Title','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="title" maxlength="80" id="dpProEventCalendar_title" class="large-text" value="<?php echo $title?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Introduce the title (80 chars max.)','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Description','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="description" id="dpProEventCalendar_description" class="large-text" value="<?php echo $description?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Introduce the description','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Preselected Date','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" readonly="readonly" maxlength="10" class="large-text"  name="default_date" id="dpProEventCalendar_default_date" value="<?php echo $default_date != '0000-00-00' ? $default_date : '' ?>" style="width:100px;" />
                                    <button type="button" class="dpProEventCalendar_btn_getDate">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/calendar.png' ); ?>" alt="Calendar" title="Calendar">
                                    </button>
                                    <button type="button" onclick="jQuery('#dpProEventCalendar_default_date').val('');">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/clear.png' ); ?>" alt="Clear" title="Clear">
                                    </button>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the preselected date.(optional)<br />Leave blank to NOT preselect any date.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Active iCal Feed','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="ical_active" id="dpProEventCalendar_ical_active" class="checkbox" <?php if($ical_active) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('On/Off the ical feed for this calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('iCal Limit','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="number" min="0" max="999" name="ical_limit" id="dpProEventCalendar_ical_limit" value="<?php echo $ical_limit?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Limits the number of future events shown (0 = unlimited).','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Active RSS Feed','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="rss_active" id="dpProEventCalendar_rss_active" class="checkbox" <?php if($rss_active) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('On/Off the rss feed for this calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('RSS Limit','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="number" min="0" max="999" name="rss_limit" id="dpProEventCalendar_rss_limit" value="<?php echo $rss_limit?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Limits the number of future events shown (0 = unlimited).','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                                        
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Link Events to Single Post','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="link_post" id="dpProEventCalendar_link_post" class="checkbox" <?php if($link_post) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Adds a link in the event title to the post type single page.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Include share buttons','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="article_share" id="dpProEventCalendar_article_share" class="checkbox" <?php if($article_share) {?> checked="checked" <?php } if ( !is_plugin_active( 'dpArticleShare/dpArticleShare.php' ) ) {?> disabled="disabled" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Adds a share bar in the events content using the Wordpress Article Social Share plugin','dpProEventCalendar'); ?></div>
                                <?php if ( !is_plugin_active( 'dpArticleShare/dpArticleShare.php' ) ) {?>
                                	<div class="errorCustom"><p><?php _e('Notice: This feature requires the <a href="http://codecanyon.net/item/wordpress-article-social-share/6247263" target="_blank">
Wordpress Article Social Share plugin</a>.','dpMaintenance'); ?></p></div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                   
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Date Range','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" readonly="readonly" maxlength="10" class="large-text"  name="date_range_start" id="dpProEventCalendar_date_range_start" value="<?php echo $date_range_start != '0000-00-00' ? $date_range_start : '' ?>" style="width:100px;" />
                                    <button type="button" class="dpProEventCalendar_btn_getDateRangeStart">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/calendar.png' ); ?>" alt="Calendar" title="Calendar">
                                    </button>
                                    <button type="button" onclick="jQuery('#dpProEventCalendar_date_range_start').val('');">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/clear.png' ); ?>" alt="Clear" title="Clear">
                                    </button>
                                    
                                    &nbsp;&nbsp;to&nbsp;&nbsp;
                                    
                                    <input type="text" readonly="readonly" maxlength="10" class="large-text"  name="date_range_end" id="dpProEventCalendar_date_range_end" value="<?php echo $date_range_end != '0000-00-00' ? $date_range_end : '' ?>" style="width:100px;" />
                                    <button type="button" class="dpProEventCalendar_btn_getDateRangeEnd">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/calendar.png' ); ?>" alt="Calendar" title="Calendar">
                                    </button>
                                    <button type="button" onclick="jQuery('#dpProEventCalendar_date_range_end').val('');">
                                        <img src="<?php echo dpProEventCalendar_plugin_url( 'images/admin/clear.png' ); ?>" alt="Clear" title="Clear">
                                    </button>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the date range.(optional)','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Hide Old Dates','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="hide_old_dates" id="dpProEventCalendar_hide_old_dates" class="checkbox" <?php if($hide_old_dates) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Hide old dates in calendar view.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Limit Time in daily View','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="number" name="limit_time_start" id="dpProEventCalendar_limit_time_start" style="width: 60px;" maxlength="2" min="0" max="23" value="<?php echo $limit_time_start?>" />:00 hs /
                                    &nbsp;
                                    <input type="number" name="limit_time_end" id="dpProEventCalendar_limit_time_end" style="width: 60px;" maxlength="2" min="0" max="23" value="<?php echo $limit_time_end?>" />:00 hs
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set a range of time to display in the daily view.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('First Day','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name="first_day" id="dpProEventCalendar_first_day" class="large-text">
                                    	<option value="0" <?php if($first_day == "0") { echo 'selected="selected"'; }?>><?php _e('Sunday','dpProEventCalendar'); ?></option>
                                        <option value="1" <?php if($first_day == "1") { echo 'selected="selected"'; }?>><?php _e('Monday','dpProEventCalendar'); ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the first day to display in the calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Default View','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name="view" id="dpProEventCalendar_view" class="large-text">
                                    	<option value="monthly" <?php if($view == "monthly") { echo 'selected="selected"'; }?>><?php _e('Calendar','dpProEventCalendar'); ?></option>
                                        <option value="monthly-all-events" <?php if($view == "monthly-all-events") { echo 'selected="selected"'; }?>><?php _e('Monthly All Events','dpProEventCalendar'); ?></option>
                                        <option value="daily" <?php if($view == "daily") { echo 'selected="selected"'; }?>><?php _e('Daily','dpProEventCalendar'); ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the default view.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Monthly/Daily Buttons','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_view_buttons" class="checkbox" id="dpProEventCalendar_show_view_buttons" value="1" <?php if($show_view_buttons) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set if Show/Hide the Monthly/Daily Buttons.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <h2 class="dp_subsection"><?php _e('User\'s events','dpProEventCalendar'); ?></h2>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Allow users to add events?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="allow_user_add_event" class="checkbox" id="dpProEventCalendar_allow_user_add_event" value="1" <?php if($allow_user_add_event) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Adds the possibility for registered users to add events.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Assign new events from a non-logged in user to an admin','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name="assign_events_admin" id="dpProEventCalendar_assign_events_admin">
                                    	<option value="" <?php if(empty($assign_events_admin)) {?>selected="selected"<?php }?>><?php _e('None','dpContentGallery'); ?></option>
										<?php 
                                          $users = get_users('role=administrator'); 
                                          foreach ($users as $user) {
                                            $option = '<option value="'.$user->ID.'" ';
											if($user->ID == $assign_events_admin) {
												$option .= 'selected="selected"';
											}
											$option .= '>';
                                            $option .= $user->display_name;
                                            $option .= '</option>';
                                            echo $option;
                                          }
                                         ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('This will allow non-logged in users to submit new events.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Allow users to edit their events?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="allow_user_edit_event" class="checkbox" id="dpProEventCalendar_allow_user_edit_event" value="1" <?php if($allow_user_edit_event) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Adds the possibility for logged in users to edit their events.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Allow users to remove their events?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="allow_user_remove_event" class="checkbox" id="dpProEventCalendar_allow_user_remove_event" value="1" <?php if($allow_user_remove_event) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Adds the possibility for logged in users to remove their events.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Publish automatically?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="publish_new_event" class="checkbox" id="dpProEventCalendar_publish_new_event" value="1" <?php if($publish_new_event) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Publish events submitted by users automatically?','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Send Email to Admin when a user submits a new event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="email_admin_new_event" class="checkbox" id="dpProEventCalendar_email_admin_new_event" value="1" <?php if($email_admin_new_event) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Email will be sent to ('.get_bloginfo('admin_email').')','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Form Customization','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="form_show_description" class="checkbox" id="dpProEventCalendar_form_show_description" value="1" <?php if($form_show_description) {?>checked="checked" <?php }?> /> <?php _e('Show Event Decription','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_category" class="checkbox" id="dpProEventCalendar_form_show_category" value="1" <?php if($form_show_category) {?>checked="checked" <?php }?> /> <?php _e('Show Category Dropdown','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_hide_time" class="checkbox" id="dpProEventCalendar_form_show_hide_time" value="1" <?php if($form_show_hide_time) {?>checked="checked" <?php }?> /> <?php _e('Show \'Hide Time\' option','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_frequency" class="checkbox" id="dpProEventCalendar_form_show_frequency" value="1" <?php if($form_show_frequency) {?>checked="checked" <?php }?> /> <?php _e('Show Frequency','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_all_day" class="checkbox" id="dpProEventCalendar_form_show_all_day" value="1" <?php if($form_show_all_day) {?>checked="checked" <?php }?> /> <?php _e('Show All Day option','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_image" class="checkbox" id="dpProEventCalendar_form_show_image" value="1" <?php if($form_show_image) {?>checked="checked" <?php }?> /> <?php _e('Allow to upload an image','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_link" class="checkbox" id="dpProEventCalendar_form_show_link" value="1" <?php if($form_show_link) {?>checked="checked" <?php }?> /> <?php _e('Show Link field','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_share" class="checkbox" id="dpProEventCalendar_form_show_share" value="1" <?php if($form_show_share) {?>checked="checked" <?php }?> /> <?php _e('Show Share Text option','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_location" class="checkbox" id="dpProEventCalendar_form_show_location" value="1" <?php if($form_show_location) {?>checked="checked" <?php }?> /> <?php _e('Show Location field','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_phone" class="checkbox" id="dpProEventCalendar_form_show_phone" value="1" <?php if($form_show_phone) {?>checked="checked" <?php }?> /> <?php _e('Show Phone option','dpProEventCalendar'); ?>
                                    <br>
                                    <input type="checkbox" name="form_show_map" class="checkbox" id="dpProEventCalendar_form_show_map" value="1" <?php if($form_show_map) {?>checked="checked" <?php }?> /> <?php _e('Show Map option','dpProEventCalendar'); ?>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Customize the frontend form','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    
                    <h2 class="dp_subsection"><?php _e('MailChimp Subscription','dpProEventCalendar'); ?></h2>
                	
                    <div class="option option-checkbox no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Active Subscribe Button','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="subscribe_active" id="dpProEventCalendar_subscribe_active" class="checkbox" <?php if($subscribe_active) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('On/Off the "subscribe" button for this calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('API Key','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type='text' name='mailchimp_api' id="mailchimp_api_key" value="<?php echo $mailchimp_api?>"/>&nbsp;&nbsp;
                                    <input type="button" onclick="MailChimp_getList(); return false;" style="width: auto;padding: 0 10px;margin: 0 !important;height: 34px;" class="button" value="<?php _e('Get Lists') ?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Introduce your MailChimp API key.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select option_w no_border" id="div_mailchimp_list" style="display: <?php echo ($mailchimp_api != "") ? 'block' : 'none'?>;">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('List:','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp" id="mailchimp_list">
                                    <select name='mailchimp_list'>
                                        <?php 
                                        if($mailchimp_api != "") {
                                            $mailchimp_class = new mailchimpSF_MCAPI($mailchimp_api);
                                            
                                            $retval = $mailchimp_class->lists();
                                            
                                            if (!$mailchimp_class->errorCode){
                                                foreach ($retval['data'] as $list){
                                            ?>
                                            <option value="<?php echo $list['id']?>" <?php if( $list['id'] == $mailchimp_list ) {?>selected="selected"<?php }?>><?php echo $list['name']?></option>
                                            <?php 
                                                }	
                                            } else {
                                                echo "Error: ".$mailchimp_class->errorMessage;
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select a list to add the new suscribers.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                	</div>
                    <div class="clear"></div>
                </div>
                
                <h2 class="subtitle accordion_title" onclick="showAccordion('div_display_settings');"><?php _e('Display Settings','dpProEventCalendar'); ?></h2>
                
                <div id="div_display_settings" style="display: none;">
                	<div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Skin','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name="skin" id="dpProEventCalendar_skin" class="large-text">
                                    	<option value="light" <?php if($skin == 'light') { echo 'selected="selected"'; }?>><?php _e('Light','dpProEventCalendar'); ?></option>
                                        <option value="dark" <?php if($skin == 'dark') { echo 'selected="selected"'; }?>><?php _e('Dark','dpProEventCalendar'); ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select the skin theme','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Time','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_time" class="checkbox" id="dpProEventCalendar_show_time" value="1" <?php if($show_time) {?>checked="checked" <?php }?> onclick="toggleFormat();" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set if Show/Hide the events time.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox" id="div_format_ampm" style="display:none;">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Hour Format AM/PM','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="format_ampm" id="dpProEventCalendar_format_ampm" class="checkbox" <?php if($format_ampm) {?> checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set the hour format to AM/PM, if disabled the format will be 24 hours','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Search','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_search" class="checkbox" id="dpProEventCalendar_show_search" value="1" <?php if($show_search) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set if Show/Hide the search input.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Category Filter','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_category_filter" class="checkbox" id="dpProEventCalendar_show_category_filter" value="1" <?php if($show_category_filter) {?>checked="checked" <?php }?>  onclick="toggleFormatCategories();" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Show/Hide the categories dropdown.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox" id="div_category_filter" style="display:none;">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Categories to display','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <select name="category_filter_include[]" id="dpProEventCalendar_category_filter_include" multiple="multiple">
                                    	<option value="" <?php if(empty($category_filter_include)) {?>selected="selected"<?php }?>><?php _e('All','dpContentGallery'); ?></option>
										<?php 
                                          $categories = get_categories('taxonomy=pec_events_category&hide_empty=0'); 
                                          foreach ($categories as $category) {
                                            $option = '<option value="'.$category->term_id.'" ';
											if(in_array($category->term_id, $category_filter_include)) {
												$option .= 'selected="selected"';
											}
											$option .= '>';
                                            $option .= $category->cat_name;
                                            $option .= '</option>';
                                            echo $option;
                                          }
                                         ?>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Select specific categories to display. To select multiple categories, keep pressing ctrl.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show X in dates with events?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_x" class="checkbox" id="dpProEventCalendar_show_x" value="1" <?php if($show_x) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set if Show a X instead of the number of events in a date.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show Events Preview?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_preview" class="checkbox" id="dpProEventCalendar_show_preview" value="1" <?php if($show_preview) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Display a list of event in a day on mouse over','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Show References Button?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_references" class="checkbox" id="dpProEventCalendar_show_references" value="1" <?php if($show_references) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Display the references button','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Display the Event Author','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="show_author" class="checkbox" id="dpProEventCalendar_show_author" value="1" <?php if($show_author) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Display the event author','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Current Date Color','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <div id="currentDate_colorSelector" class="colorSelector"><div style="background-color: <?php echo $current_date_color?>"></div></div>
                                    <input type="hidden" name="current_date_color" id="dpProEventCalendar_current_date_color" value="<?php echo $current_date_color?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set the Current date color.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select no_border">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Width','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="width" id="dpProEventCalendar_width" maxlength="4" style="width:50px;" class="large-text" value="<?php echo $width?>" /> 
                                    <select name="width_unity" id="dpProEventCalendar_width_unity" style="width:60px;" class="large-text">
                                        <option value="px" <?php if($width_unity == 'px') {?> selected="selected" <?php }?>>px</option>
                                        <option value="%" <?php if($width_unity == '%') {?> selected="selected" <?php }?>>%</option>
                                    </select>
                                    <br>
                                </div>
                                <div class="desc" style="width: 400px;"><?php _e('Set the width of the calendar','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <h2 class="subtitle accordion_title" onclick="showAccordion('div_booking');"><?php _e('Booking','dpProEventCalendar'); ?></h2>

                <div id="div_booking" style="display: none;">
                
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Enable booking for all the events','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="booking_enable" class="checkbox" id="dpProEventCalendar_booking_enable" value="1" <?php if($booking_enable) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('A "Book Event" button will be displayed on the event page for logged in users.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Enable comment option in booking form','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="booking_comment" class="checkbox" id="dpProEventCalendar_booking_comment" value="1" <?php if($booking_comment) {?>checked="checked" <?php }?> />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Enables a comment text field.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Booked Event Color','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <div id="bookedEvent_colorSelector" class="colorSelector"><div style="background-color: <?php echo $booking_event_color?>"></div></div>
                                    <input type="hidden" name="booking_event_color" id="dpProEventCalendar_booking_event_color" value="<?php echo $booking_event_color?>" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('Set the booked event color.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <h2 class="subtitle accordion_title" onclick="showAccordion('div_translations');"><?php _e('Translations','dpProEventCalendar'); ?></h2>
                
                <div id="div_translations" style="display: none;">
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Prev Month','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_prev_month" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['PREV_MONTH']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Next Month','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_next_month" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['NEXT_MONTH']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Prev Day','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_prev_day" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['PREV_DAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Next Day','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_next_day" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['NEXT_DAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('No Events Found','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_no_events_found" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_NO_EVENTS_FOUND']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('All Day','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_all_day" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_ALL_DAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('References','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_references" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_REFERENCES']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('View All Events','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_view_all_events" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_VIEW_ALL_EVENTS']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('All Categories','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_all_categories" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_ALL_CATEGORIES']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Monthly','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_monthly" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_MONTHLY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Daily','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_daily" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_DAILY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('All working days','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_all_working_days" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_ALL_WORKING_DAYS']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Category','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_category" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_CATEGORY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Subscribe','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_subscribe" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SUBSCRIBE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Subscribe - Subtitle','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_subscribe_subtitle" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SUBSCRIBE_SUBTITLE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Your Name','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_your_name" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_YOUR_NAME']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Your Email','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_your_email" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_YOUR_EMAIL']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Fields Required','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_fields_required" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_FIELDS_REQUIRED']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Invalid Email','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_invalid_email" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_INVALID_EMAIL']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('New Subscriber - Thanks','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_subscribe_thanks" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SUBSCRIBE_THANKS']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Sending','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_sending" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SENDING']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Send','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_send" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SEND']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Add Event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_add_event" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_ADD_EVENT']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Edit Event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_edit_event" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EDIT_EVENT']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Remove Event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_remove_event" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_REMOVE_EVENT']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Are you sure that you want to delete this event?','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_remove_event_confirm" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_REMOVE_EVENT_CONFIRM']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Cancel','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_cancel" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_CANCEL']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Yes','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_yes" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_YES']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('No','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_no" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_NO']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Login to submit an event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_logged_to_submit" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_LOGIN']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Thanks for submit an event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_thanks_for_submit" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_THANKS']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Event Title','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_title" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_TITLE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Event Description','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_description" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_DESCRIPTION']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Link','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_link" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_LINK']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Share','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_share" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_SHARE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Add Image','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_image" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_IMAGE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Location','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_location" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_LOCATION']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Phone','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_phone" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_PHONE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Google Map','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_googlemap" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_GOOGLEMAP']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Start Date','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_start_date" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_START_DATE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('All Day','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_all_day" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_ALL_DAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Start Time','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_start_time" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_START_TIME']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Hide Time','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_hide_time" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_HIDE_TIME']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('End Time','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_end_time" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_END_TIME']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Frequency','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_frequency" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_FREQUENCY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('None','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_none" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_NONE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Daily Frequency','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_daily" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_DAILY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Weekly Frequency','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_weekly" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_WEEKLY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                                       
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Monthly Frequency','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_monthly" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_MONTHLY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Yearly Frequency','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_yearly" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_YEARLY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('End Date','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_end_date" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_EVENT_END_DATE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Submit for Review','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_event_submit" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SUBMIT_FOR_REVIEW']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Search','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_search" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_SEARCH']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Results','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_results_for" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_RESULTS_FOR']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('By','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_by" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Current Date','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_current_date" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_CURRENT_DATE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Book Event','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Remove Booking','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_remove" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_REMOVE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Event saved successfully.','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_saved" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_SAVED']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Event removed successfully.','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_removed" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_REMOVED']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Select Date','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_select_date" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_SELECT_DATE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Click to book on this date.','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_pick_date" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_PICK_DATE']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('You have already booked this event date.','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_already_booked" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_ALREADY_BOOKED']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Leave a comment (optional):','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_txt_book_event_comment" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['TXT_BOOK_EVENT_COMMENT']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Sunday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_sunday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_SUNDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Monday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_monday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_MONDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Tuesday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_tuesday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_TUESDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Wednesday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_wednesday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_WEDNESDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Thursday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_thursday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_THURSDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Friday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_friday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_FRIDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Saturday','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_day_saturday" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['DAY_SATURDAY']?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('January','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_january" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][0]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('February','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_february" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][1]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('March','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_march" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][2]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('April','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_april" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][3]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('May','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_may" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][4]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('June','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_june" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][5]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('July','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_july" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][6]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('August','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_august" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][7]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('September','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_september" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][8]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('October','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_october" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][9]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('November','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_november" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][10]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    
                    <div class="option option-select">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('December','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="text" name="lang_month_december" class="large-text" value="<?php echo $dpProEventCalendar_class->translation['MONTHS'][11]?>" />
                                    <br>
                                </div>
                                <div class="desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
               </div>
               
               <h2 class="subtitle accordion_title" onclick="showAccordion('div_cache');"><?php _e('Cache','dpProEventCalendar'); ?></h2>
                
                <div id="div_cache" style="display: none;">
                    <div class="option option-checkbox">
                        <div class="option-inner">
                            <label class="titledesc"><?php _e('Active Cache','dpProEventCalendar'); ?></label>
                            <div class="formcontainer">
                                <div class="forminp">
                                    <input type="checkbox" name="cache_active" id="dpProEventCalendar_cache_active" class="checkbox" <?php if($cache_active) {?>checked="checked" <?php }?> value="1" />
                                    <br>
                                </div>
                                <div class="desc"><?php _e('On/Off the cache feature for this calendar. The cache will be cleared every time you edit the calendar settings and when you add / edit an event.','dpProEventCalendar'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
               
               <?php if(is_numeric($_GET['edit'])) {?>
               <h2 class="subtitle accordion_title" onclick="showAccordion('div_subscribers');"><?php _e('Subscribers','dpProEventCalendar'); ?></h2>
                
                <div id="div_subscribers" style="display: none;">
                                        
                        <table class="widefat" cellpadding="0" cellspacing="0" id="sort-table">
                        	<thead>
                        		<tr style="cursor:default !important;">
                                	<th><?php _e('Name','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Email','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Subscription Date','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Actions','dpProEventCalendar'); ?></th>
                                 </tr>
                            </thead>
                            <tbody>
                            	<?php
                                $querystr = "
                                SELECT *
                                FROM $table_name_subscribers_calendar 
                                ORDER BY subscription_date ASC
                                ";
                                $subscribers_obj = $wpdb->get_results($querystr, OBJECT);
                                foreach($subscribers_obj as $subscriber) {
									?>
                        		<tr>
                                	<td><?php echo $subscriber->name?></td>
                                    <td><?php echo $subscriber->email?></td>
                                    <td><?php echo $subscriber->subscription_date?></td>
                                    <td><input type="button" value="<?php echo __( 'Delete', 'dpProEventCalendar' )?>" name="delete_calendar" class="button-secondary" onclick="if(confirm('<?php echo __( 'Are you sure that you want to remove this subscriber?', 'dpProEventCalendar' )?>')) { location.href='<?php echo admin_url('admin.php?page=dpProEventCalendar-admin&edit='.$_GET['edit'].'&delete_subscriber='.$subscriber->id.'&noheader=true')?>'; }" /></td>
                                </tr>
                                <?php }?>
                    		</tbody>
                            <tfoot>
                            	<tr style="cursor:default !important;">
                                	<th><?php _e('Name','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Email','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Subscription Date','dpProEventCalendar'); ?></th>
                                    <th><?php _e('Actions','dpProEventCalendar'); ?></th>
                                 </tr>
                            </tfoot>
                        </table>
                        
                        <div class="clear"></div>
                </div>
                <?php }?>
               
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
                        <input type="button" class="button" value="<?php _e('Back') ?>" onclick="history.back();" />
                    </p>
                </form>
                <script type="text/javascript">
					toggleFormat();
					toggleFormatCategories();
				</script>
            </div>
        </div>
    </div>
        <?php $dpProEventCalendar_class->output(true);?>
        <?php }?>
	 <!--end of poststuff --> 
	
	
	</div> <!--end of float wrap -->
    <div class="clear"></div>
	

	<?php	
}
?>