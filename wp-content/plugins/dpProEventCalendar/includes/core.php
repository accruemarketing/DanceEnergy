<?php 

/************************************************************************/
/*** DISPLAY START
/************************************************************************/
class dpProEventCalendar_wpress_display {
	
	static $js_flag;
	static $js_declaration = array();
	static $id_calendar;
	static $type;
	static $limit;
	static $widget;
	static $limit_description;
	static $category;
	static $event_id;
	static $author;
	static $get;
	public $events_html;

	function dpProEventCalendar_wpress_display($id, $type, $limit, $widget, $limit_description, $category, $author, $get = "", $event_id = "") {
		self::$id_calendar = $id;
		self::$type = $type;
		self::$limit = $limit;
		self::$widget = $widget;
		self::$limit_description = $limit_description;
		self::$category = $category;
		self::$event_id = $event_id;
		self::$author = $author;
		self::$get = $get;
		self::return_dpProEventCalendar();
		add_action('wp_footer', array(__CLASS__, 'add_scripts'), 100);
		
	}
	
	function add_scripts() {
		global $dpProEventCalendar;
		
		if(self::$js_flag) {
			foreach( self::$js_declaration as $key) { echo $key; }
			echo '<style type="text/css">'.$dpProEventCalendar['custom_css'].'</style>';
		}
	}
	
	function return_dpProEventCalendar() {
		global $dpProEventCalendar, $wpdb, $table_prefix, $post;
		
		$id = self::$id_calendar;
		$type = self::$type;
		$limit = self::$limit;
		$author = self::$author;
		$get = self::$get;
		$widget = self::$widget;
		$limit_description = self::$limit_description;
		$category = self::$category;
		$event_id = self::$event_id;
		
		if($id == "") {
			$id = get_post_meta($post->ID, 'pec_id_calendar', true);
		}
		
		require_once (dirname (__FILE__) . '/../classes/base.class.php');
		$dpProEventCalendar_class = new DpProEventCalendar( false, $id, null, null, $widget, $category, $event_id );
		
		if($get != "") { 
			
			$this->events_html = $dpProEventCalendar_class->getFormattedEventData($get); return; 
		}
		
		if($type != "") { $dpProEventCalendar_class->switchCalendarTo($type, $limit, $limit_description, $category, $author, $event_id); }
		
		array_walk($dpProEventCalendar, 'dpProEventCalendar_reslash_multi');
		$rand_num = rand();

		//if(!$calendar->active) { return ''; }
		
		$events_script= $dpProEventCalendar_class->addScripts();
		self::$js_declaration[] = $events_script;
		
		self::$js_flag = true;
		
		$events_html = $dpProEventCalendar_class->output();
					
		$this->events_html = $events_html;
	}
}

function dpProEventCalendar_simple_shortcode($atts) {
	global $dpProEventCalendar;
	
	extract(shortcode_atts(array(
		'id' => '',
		'type' => '',
		'category' => '',
		'event_id' => '',
		'author' => '',
		'get' => '',
		'limit' => '',
		'widget' => '',
		'limit_description' => ''
	), $atts));

	/* Add JS files */
	if ( !is_admin() ){ 
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker', dpProEventCalendar_plugin_url( 'ui/jquery.ui.datepicker.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script( 'placeholder', dpProEventCalendar_plugin_url( 'js/jquery.placeholder.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script( 'selectric', dpProEventCalendar_plugin_url( 'js/jquery.selectric.min.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script( 'jquery-form', dpProEventCalendar_plugin_url( 'js/jquery.form.min.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script( 'icheck', dpProEventCalendar_plugin_url( 'js/jquery.icheck.min.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script( 'dpProEventCalendar', dpProEventCalendar_plugin_url( 'js/jquery.dpProEventCalendar.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		
		wp_localize_script( 'dpProEventCalendar', 'ProEventCalendarAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'postEventsNonce' => wp_create_nonce( 'ajax-get-events-nonce' ) ) );

		wp_enqueue_script( 'gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false',
			null, DP_PRO_EVENT_CALENDAR_VER, false); 

	}
	
	wp_enqueue_style( 'jquery-ui-core-pec', dpProEventCalendar_plugin_url( 'themes/base/jquery.ui.core.css' ),
		false, DP_PRO_EVENT_CALENDAR_VER, 'all' );
	wp_enqueue_style( 'jquery-ui-theme-pec', dpProEventCalendar_plugin_url( 'themes/base/jquery.ui.theme.css' ),
		false, DP_PRO_EVENT_CALENDAR_VER, 'all' );
	wp_enqueue_style( 'jquery-ui-datepicker-pec', dpProEventCalendar_plugin_url( 'themes/base/jquery.ui.datepicker.css' ),
		false, DP_PRO_EVENT_CALENDAR_VER, 'all' );
		
	wp_enqueue_style( 'dpProEventCalendar_headcss', dpProEventCalendar_plugin_url( 'css/dpProEventCalendar.css' ),
		false, DP_PRO_EVENT_CALENDAR_VER, 'all');
	
	if($dpProEventCalendar['rtl_support']) {
		wp_enqueue_style( 'dpProEventCalendar_rtlcss', dpProEventCalendar_plugin_url( 'css/rtl.css' ),
			false, DP_PRO_EVENT_CALENDAR_VER, 'all');
	}
	
	$dpProEventCalendar_wpress_display = new dpProEventCalendar_wpress_display($id, $type, $limit, $widget, $limit_description, $category, $author, $get, $event_id);
	return $dpProEventCalendar_wpress_display->events_html;
}
add_shortcode('dpProEventCalendar', 'dpProEventCalendar_simple_shortcode');

/************************************************************************/
/*** DISPLAY END
/************************************************************************/

/************************************************************************/
/*** WIDGET START
/************************************************************************/

class DpProEventCalendar_Widget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Use the calendar as a widget',
			'name' => 'DP Pro Event Calendar'
		);
		
		parent::__construct('EventsCalendar', '', $params);
	}
	
	public function form($instance) {
		global $wpdb, $table_prefix;
		$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		
		extract($instance);
		?>
        	<p>
            	<label for="<?php echo $this->get_field_id('title');?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php if(isset($title)) echo esc_attr($title); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('description');?>">Description: </label>
                <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description');?>" name="<?php echo $this->get_field_name('description');?>"><?php if(isset($description)) echo esc_attr($description); ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('calendar');?>">Calendar: </label>
            	<select name="<?php echo $this->get_field_name('calendar');?>" id="<?php echo $this->get_field_id('calendar');?>">
                    <?php
                    $querystr = "
                    SELECT *
                    FROM $table_name_calendars
                    ORDER BY title ASC
                    ";
                    $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                    foreach($calendars_obj as $calendar_key) {
                    ?>
                        <option value="<?php echo $calendar_key->id?>" <?php if($calendar == $calendar_key->id) {?> selected="selected" <?php } ?>><?php echo $calendar_key->title?></option>
                    <?php }?>
                </select>
            </p>
            
            
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		
		//if(empty($title)) $title = 'DP Pro Event Calendar';
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpProEventCalendar id='.$calendar.' widget=1]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpProEventCalendar_register_widget');
function dpProEventCalendar_register_widget() {
	register_widget('DpProEventCalendar_Widget');
}

/************************************************************************/
/*** WIDGET END
/************************************************************************/

/************************************************************************/
/*** WIDGET UPCOMING EVENTS START
/************************************************************************/

class DpProEventCalendar_UpcomingEventsWidget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Display the upcoming events of a calendar.',
			'name' => 'DP Pro Event Calendar - Upcoming Events'
		);
		
		parent::__construct('EventsCalendarUpcomingEvents', '', $params);
	}
	
	public function form($instance) {
		global $wpdb, $table_prefix;
		$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		
		extract($instance);
		?>
        	<p>
            	<label for="<?php echo $this->get_field_id('title');?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php if(isset($title)) echo esc_attr($title); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('description');?>">Description: </label>
                <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description');?>" name="<?php echo $this->get_field_name('description');?>"><?php if(isset($description)) echo esc_attr($description); ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('calendar');?>">Calendar: </label>
            	<select name="<?php echo $this->get_field_name('calendar');?>" id="<?php echo $this->get_field_id('calendar');?>">
                    <?php
                    $querystr = "
                    SELECT *
                    FROM $table_name_calendars
                    ORDER BY title ASC
                    ";
                    $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                    foreach($calendars_obj as $calendar_key) {
                    ?>
                        <option value="<?php echo $calendar_key->id?>" <?php if($calendar == $calendar_key->id) {?> selected="selected" <?php } ?>><?php echo $calendar_key->title?></option>
                    <?php }?>
                </select>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('layout');?>"><?php _e('Layout')?>: </label>
            	<select name="<?php echo $this->get_field_name('layout');?>" id="<?php echo $this->get_field_id('layout');?>">
                	<option value=""><?php _e('Default')?></option>
                    <option value="accordion-upcoming" <?php if($layout == 'accordion-upcoming') {?> selected="selected" <?php } ?>><?php _e('Accordion')?></option>
                    <option value="gmap-upcoming" <?php if($layout == 'gmap-upcoming') {?> selected="selected" <?php } ?>><?php _e('Google Map')?></option>
                </select>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('category');?>"><?php _e('Category')?>: </label>
            	<select name="<?php echo $this->get_field_name('category');?>" id="<?php echo $this->get_field_id('category');?>">
                	<option value=""><?php _e('All')?></option>
                    <?php
                    $categories=  get_categories('taxonomy=pec_events_category'); 
					foreach ($categories as $cat) {
                    ?>
                        <option value="<?php echo $cat->term_id?>" <?php if($category == $cat->term_id) {?> selected="selected" <?php } ?>><?php echo $cat->name?></option>
                    <?php }?>
                </select>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('events_count');?>">Number of events to retrieve: </label>
                <input type="number" class="widefat" style="width:40px;" min="1" max="10" id="<?php echo $this->get_field_id('events_count');?>" name="<?php echo $this->get_field_name('events_count');?>" value="<?php echo !empty($events_count) ? $events_count : 5; ?>"s />
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('limit_description');?>">Limit Description: </label>
                <input type="number" min="0" max="500" id="<?php echo $this->get_field_id('limit_description');?>" name="<?php echo $this->get_field_name('limit_description');?>" value="<?php if(isset($limit_description)) echo esc_attr($limit_description); ?>" />&nbsp;chars
            </p>
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		$type = 'upcoming';
		
		//if(empty($title)) $title = 'DP Pro Event Calendar - Upcoming Events';
		if(!is_numeric($events_count)) { $events_count = 5; }
		
		if($layout != "") {
			$type = $layout;
		}
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpProEventCalendar id='.$calendar.' type="'.$type.'" category="'.$category.'" limit="'.$events_count.'" limit_description="'.$limit_description.'"]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpProEventCalendar_register_upcomingeventswidget');
function dpProEventCalendar_register_upcomingeventswidget() {
	register_widget('DpProEventCalendar_UpcomingEventsWidget');
}

/************************************************************************/
/*** WIDGET UPCOMING EVENTS END
/************************************************************************/

/************************************************************************/
/*** WIDGET ACCORDION EVENTS START
/************************************************************************/

class DpProEventCalendar_AccordionWidget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Display events in an Accordion list.',
			'name' => 'DP Pro Event Calendar - Accordion List'
		);
		
		parent::__construct('EventsCalendarAccordion', '', $params);
	}
	
	public function form($instance) {
		global $wpdb, $table_prefix;
		$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		
		extract($instance);
		?>
        	<p>
            	<label for="<?php echo $this->get_field_id('title');?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php if(isset($title)) echo esc_attr($title); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('description');?>">Description: </label>
                <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description');?>" name="<?php echo $this->get_field_name('description');?>"><?php if(isset($description)) echo esc_attr($description); ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('calendar');?>">Calendar: </label>
            	<select name="<?php echo $this->get_field_name('calendar');?>" id="<?php echo $this->get_field_id('calendar');?>">
                    <?php
                    $querystr = "
                    SELECT *
                    FROM $table_name_calendars
                    ORDER BY title ASC
                    ";
                    $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                    foreach($calendars_obj as $calendar_key) {
                    ?>
                        <option value="<?php echo $calendar_key->id?>" <?php if($calendar == $calendar_key->id) {?> selected="selected" <?php } ?>><?php echo $calendar_key->title?></option>
                    <?php }?>
                </select>
            </p>
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		
		//if(empty($title)) $title = 'DP Pro Event Calendar - Upcoming Events';
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpProEventCalendar id='.$calendar.' type="accordion" category="'.$category.'"]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpProEventCalendar_register_accordionwidget');
function dpProEventCalendar_register_accordionwidget() {
	register_widget('DpProEventCalendar_AccordionWidget');
}

/************************************************************************/
/*** WIDGET ACCORDION END
/************************************************************************/

/************************************************************************/
/*** WIDGET ADD EVENTS START
/************************************************************************/

class DpProEventCalendar_AddEventsWidget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Allow logged in users to submit events.',
			'name' => 'DP Pro Event Calendar - Add Events'
		);
		
		parent::__construct('EventsCalendarAddEvents', '', $params);
	}
	
	public function form($instance) {
		global $wpdb, $table_prefix;
		$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		
		extract($instance);
		?>
        	<p>
            	<label for="<?php echo $this->get_field_id('title');?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php if(isset($title)) echo esc_attr($title); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('description');?>">Description: </label>
                <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description');?>" name="<?php echo $this->get_field_name('description');?>"><?php if(isset($description)) echo esc_attr($description); ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('calendar');?>">Calendar: </label>
            	<select name="<?php echo $this->get_field_name('calendar');?>" id="<?php echo $this->get_field_id('calendar');?>">
                    <?php
                    $querystr = "
                    SELECT *
                    FROM $table_name_calendars
                    ORDER BY title ASC
                    ";
                    $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                    foreach($calendars_obj as $calendar_key) {
                    ?>
                        <option value="<?php echo $calendar_key->id?>" <?php if($calendar == $calendar_key->id) {?> selected="selected" <?php } ?>><?php echo $calendar_key->title?></option>
                    <?php }?>
                </select>
            </p>
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		
		//if(empty($title)) $title = 'DP Pro Event Calendar - Upcoming Events';
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpProEventCalendar id='.$calendar.' type="add-event" category="'.$category.'"]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpProEventCalendar_register_addeventswidget');
function dpProEventCalendar_register_addeventswidget() {
	register_widget('DpProEventCalendar_AddEventsWidget');
}

/************************************************************************/
/*** WIDGET ADD EVENTS END
/************************************************************************/

/************************************************************************/
/*** WIDGET TODAY EVENTS START
/************************************************************************/

class DpProEventCalendar_TodayEventsWidget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Display today\'s events in a list.',
			'name' => 'DP Pro Event Calendar - Today\'s Events'
		);
		
		parent::__construct('EventsCalendarTodayEvents', '', $params);
	}
	
	public function form($instance) {
		global $wpdb, $table_prefix;
		$table_name_calendars = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_CALENDARS;
		
		extract($instance);
		?>
        	<p>
            	<label for="<?php echo $this->get_field_id('title');?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php if(isset($title)) echo esc_attr($title); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('description');?>">Description: </label>
                <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description');?>" name="<?php echo $this->get_field_name('description');?>"><?php if(isset($description)) echo esc_attr($description); ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('calendar');?>">Calendar: </label>
            	<select name="<?php echo $this->get_field_name('calendar');?>" id="<?php echo $this->get_field_id('calendar');?>">
                    <?php
                    $querystr = "
                    SELECT *
                    FROM $table_name_calendars
                    ORDER BY title ASC
                    ";
                    $calendars_obj = $wpdb->get_results($querystr, OBJECT);
                    foreach($calendars_obj as $calendar_key) {
                    ?>
                        <option value="<?php echo $calendar_key->id?>" <?php if($calendar == $calendar_key->id) {?> selected="selected" <?php } ?>><?php echo $calendar_key->title?></option>
                    <?php }?>
                </select>
            </p>
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_PRO_EVENT_CALENDAR_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		
		//if(empty($title)) $title = 'DP Pro Event Calendar - Upcoming Events';
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpProEventCalendar id='.$calendar.' type="today-events"]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpProEventCalendar_register_todayeventswidget');
function dpProEventCalendar_register_todayeventswidget() {
	register_widget('DpProEventCalendar_TodayEventsWidget');
}

/************************************************************************/
/*** WIDGET ADD EVENTS END
/************************************************************************/


/*
function dpProEventCalendar_enqueue_scripts() {
	
}

add_action( 'init', 'dpProEventCalendar_enqueue_scripts' );

function dpProEventCalendar_enqueue_styles() {	
  global $post, $dpProEventCalendar, $wp_registered_widgets,$wp_widget_factory;
  
  
}
add_action( 'wp', 'dpProEventCalendar_enqueue_styles' );
*/

//admin settings
function dpProEventCalendar_admin_scripts($force = false) {
	global $dpProEventCalendar;
	if ( is_admin() ){ // admin actions
		// Settings page only

		if ( $force || (isset($_GET['page']) && ('dpProEventCalendar-admin' == $_GET['page'] or 'dpProEventCalendar-settings' == $_GET['page'] or 'dpProEventCalendar-events' == $_GET['page'] or 'dpProEventCalendar-special' == $_GET['page'] or 'dpProEventCalendar-import' == $_GET['page'] or 'dpProEventCalendar-custom-shortcodes' == $_GET['page'] or 'dpProEventCalendar-eventdata' == $_GET['page'] ))  ) {
		wp_register_script('jquery', false, false, false, false);
		wp_enqueue_style( 'dpProEventCalendar_admin_head_css', dpProEventCalendar_plugin_url( 'css/admin-styles.css' ),
			false, DP_PRO_EVENT_CALENDAR_VER, 'all');
		
		wp_enqueue_script( 'dpProEventCalendar', dpProEventCalendar_plugin_url( 'js/jquery.dpProEventCalendar.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_localize_script( 'dpProEventCalendar', 'ProEventCalendarAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'postEventsNonce' => wp_create_nonce( 'ajax-get-events-nonce' ) ) );
		wp_enqueue_script( 'colorpicker2', dpProEventCalendar_plugin_url( 'js/colorpicker.js' ),
			array('jquery'), DP_PRO_EVENT_CALENDAR_VER, false); 
		wp_enqueue_script ( 'dpProEventCalendar_admin', dpProEventCalendar_plugin_url( 'js/admin_settings.js' ), array('jquery-ui-dialog') ); 
    	wp_enqueue_style ('wp-jquery-ui-dialog');
		wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload', 'word-count', 'post'));
		wp_enqueue_style( 'dpProEventCalendar_headcss', dpProEventCalendar_plugin_url( 'css/dpProEventCalendar.css' ),
			false, DP_PRO_EVENT_CALENDAR_VER, 'all');
		wp_enqueue_style( 'colorpicker', dpProEventCalendar_plugin_url( 'css/colorpicker.css' ),
			false, DP_PRO_EVENT_CALENDAR_VER, 'all');
		};
		wp_enqueue_style('thickbox');
  	}
}

add_action( 'admin_init', 'dpProEventCalendar_admin_scripts' );
add_action( 'pec_enqueue_admin', 'dpProEventCalendar_admin_scripts' );

function dpProEventCalendar_admin_head() {
	global $dpProEventCalendar;
	if ( is_admin() ){ // admin actions
	   
	  	// Special Dates page only
		if ( isset($_GET['page']) && 'dpProEventCalendar-special' == $_GET['page'] ) {
		?>
			<script type="text/javascript">
			// <![CDATA[
				function confirmSpecialDelete()
				{
					var agree=confirm("Delete this Special Date?");
					if (agree)
					return true ;
					else
					return false ;
				}
				
				function special_checkform ()
				{
					if (document.getElementById('dpProEventCalendar_title').value == "") {
						alert( "Please enter the title of the special date." );
						document.getElementById('dpProEventCalendar_title').focus();
						return false ;
					}
					return true ;
				}
				
				function special_checkform_edit ()
				{
					if (document.getElementById('dpPEC_special_title').value == "") {
						alert( "Please enter the title of the special date." );
						document.getElementById('dpPEC_special_title').focus();
						return false ;
					}
					return true ;
				}
				
				jQuery(document).ready(function() {
					jQuery('#specialDate_colorSelector').ColorPicker({
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							jQuery('#specialDate_colorSelector div').css('backgroundColor', '#' + hex);
							jQuery('#dpProEventCalendar_color').val('#' + hex);
						}
					});
					
					jQuery('#specialDate_colorSelector_Edit').ColorPicker({
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							jQuery('#specialDate_colorSelector_Edit div').css('backgroundColor', '#' + hex);
							jQuery('#dpPEC_special_color').val('#' + hex);
						}
					});
				});
			//]]>
			</script>
	<?php
	   } 
	   
	   // Calendars page only
		if ( isset($_GET['page']) && 'dpProEventCalendar-admin' == $_GET['page'] ) {
		?>
			<script type="text/javascript">
			// <![CDATA[
				function confirmCalendarDelete()
				{
					var agree=confirm("All the events in this calendar will be deleted. Are you sure?");
					if (agree)
					return true ;
					else
					return false ;
				}
				
				function calendar_checkform ()
				{
					if (document.getElementById('dpProEventCalendar_title').value == "") {
						alert( "Please enter the title of the calendar." );
						document.getElementById('dpProEventCalendar_title').focus();
						return false ;
					}
					
					if (document.getElementById('dpProEventCalendar_description').value == "") {
						alert( "Please enter the description of the calendar." );
						document.getElementById('dpProEventCalendar_description').focus();
						return false ;
					}
					
					if (document.getElementById('dpProEventCalendar_width').value == "") {
						alert( "Please enter the width of the calendar." );
						document.getElementById('dpProEventCalendar_width').focus();
						return false ;
					}
					return true ;
				}
				
				function toggleFormat() {
					if(jQuery('#dpProEventCalendar_show_time').attr("checked")) {
						jQuery('#div_format_ampm').slideDown('fast');
					} else {
						jQuery('#div_format_ampm').slideUp('fast');
					}
				}
				
				function toggleFormatCategories() {
					if(jQuery('#dpProEventCalendar_show_category_filter').attr("checked")) {
						jQuery('#div_category_filter').slideDown('fast');
					} else {
						jQuery('#div_category_filter').slideUp('fast');
					}
				}
				
				function showAccordion(div) {
					if(jQuery('#'+div).css('display') == 'none') {
						jQuery('#'+div).slideDown('fast');
					} else {
						jQuery('#'+div).slideUp('fast');
					}
				}
				
				jQuery(document).ready(function() {
					jQuery('#currentDate_colorSelector').ColorPicker({
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							jQuery('#currentDate_colorSelector div').css('backgroundColor', '#' + hex);
							jQuery('#dpProEventCalendar_current_date_color').val('#' + hex);
						}
					});
					
					jQuery('#bookedEvent_colorSelector').ColorPicker({
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							jQuery('#bookedEvent_colorSelector div').css('backgroundColor', '#' + hex);
							jQuery('#dpProEventCalendar_booking_event_color').val('#' + hex);
						}
					});
					
				});
			//]]>
			</script>
	<?php
	   } //Calendars page only
	   
	   // Events page only
		if ( isset($_GET['page']) && 'dpProEventCalendar-events' == $_GET['page'] ) {
			add_action("admin_head","myplugin_load_tiny_mce");
			
			// TinyMCE: First line toolbar customizations
			if( !function_exists('base_extended_editor_mce_buttons') ){
				function base_extended_editor_mce_buttons($buttons) {
					// The settings are returned in this array. Customize to suite your needs.
					return array(
						'formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'unlink', 'blockquote', 'spellchecker', 'fullscreen', 'wp_help'
					);
					/* WordPress Default
					return array(
						'bold', 'italic', 'strikethrough', 'separator', 
						'bullist', 'numlist', 'blockquote', 'separator', 
						'justifyleft', 'justifycenter', 'justifyright', 'separator', 
						'link', 'unlink', 'wp_more', 'separator', 
						'spellchecker', 'fullscreen', 'wp_adv'
					); */
				}
				add_filter("mce_buttons", "base_extended_editor_mce_buttons", 0);
			}
			 
			// TinyMCE: Second line toolbar customizations
			if( !function_exists('base_extended_editor_mce_buttons_2') ){
				function base_extended_editor_mce_buttons_2($buttons) {
					// The settings are returned in this array. Customize to suite your needs. An empty array is used here because I remove the second row of icons.
					return array();
					/* WordPress Default
					return array(
						'formatselect', 'underline', 'justifyfull', 'forecolor', 'separator', 
						'pastetext', 'pasteword', 'removeformat', 'separator', 
						'media', 'charmap', 'separator', 
						'outdent', 'indent', 'separator', 
						'undo', 'redo', 'wp_help'
					); */
				}
				add_filter("mce_buttons_2", "base_extended_editor_mce_buttons_2", 0);
			}
			
			// Customize the format dropdown items
			if( !function_exists('base_custom_mce_format') ){
				function base_custom_mce_format($init) {
					// Add block format elements you want to show in dropdown
					$init['theme_advanced_blockformats'] = 'p,h2,h3,h4,h5';
					// Add elements not included in standard tinyMCE dropdown p,h1,h2,h3,h4,h5,h6
					//$init['extended_valid_elements'] = 'code[*]';
					return $init;
				}
				add_filter('tiny_mce_before_init', 'base_custom_mce_format' );
			}
			
			function myplugin_load_tiny_mce() {
			
				wp_tiny_mce( false ); // true gives you a stripped down version of the editor
			
			}
		?>
			<script type="text/javascript">
			// <![CDATA[
			function confirmEventDelete()
			{
				var agree=confirm("Delete this Event?");
				if (agree)
				return true ;
				else
				return false ;
			}

			function event_checkform ()
			{
			  	if (document.getElementById('dpProEventCalendar_id_calendar').value == "") {
					alert( "Please select a calendar." );
					document.getElementById('dpProEventCalendar_id_calendar').focus();
					return false ;
			  	}
				
				if (document.getElementById('dpProEventCalendar_title').value == "") {
					alert( "Please enter the title of the event." );
					document.getElementById('dpProEventCalendar_title').focus();
					return false ;
			  	}
				
				if (document.getElementById('dpProEventCalendar_description').value == "") {
					alert( "Please enter the description of the event." );
					document.getElementById('dpProEventCalendar_description').focus();
					return false ;
			  	}
				
				if (document.getElementById('dpProEventCalendar_date').value == "") {
					alert( "Please select the date of the event." );
					document.getElementById('dpProEventCalendar_date').focus();
					return false ;
			  	}
			  	return true ;
			}
			//]]>
			</script>
	<?php
	   } //Events page only
	   
	   // Settings page only
		if ( isset($_GET['page']) && 'dpProEventCalendar-settings' == $_GET['page'] ) {
		?>
			<script type="text/javascript">
			// <![CDATA[
				jQuery(document).ready(function() {
					jQuery('#holidays_colorSelector').ColorPicker({
						onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
						},
						onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
						},
						onChange: function (hsb, hex, rgb) {
							jQuery('#holidays_colorSelector div').css('backgroundColor', '#' + hex);
							jQuery('#dpProEventCalendar_holidays_color').val('#' + hex);
						}
					});
				});
			//]]>
			</script>
	<?php
	   } //Settings page only
	   
	   // Import page only
		if ( isset($_GET['page']) && 'dpProEventCalendar-import' == $_GET['page'] ) {
		?>
			<script type="text/javascript">
			// <![CDATA[
				function import_checkform ()
				{
					return true;
				}
			//]]>
			</script>
	<?php
	   } //Settings page only
	   
	 }//only for admin
}
add_action('admin_head', 'dpProEventCalendar_admin_head');
?>