<?php 

/************************************************************************/
/*** DISPLAY START
/************************************************************************/
class dpArticleShare_wpress_display {
	
	static $js_flag;
	static $js_declaration = array();
	static $widget;
	public $html;

	function dpArticleShare_wpress_display($widget) {
		self::$widget = $widget;
		self::return_dpArticleShare();
		add_action('wp_footer', array(__CLASS__, 'add_scripts'));
		
	}
	
	function add_scripts() {
		global $dpArticleShare;
		
		if(self::$js_flag) {
			foreach( self::$js_declaration as $key) { echo $key; }
			$css = $dpArticleShare['custom_css'];
			
			if(!empty($css)) {
				echo '<style type="text/css">'.$css.'</style>';	
			}
		}
	}
	
	function return_dpArticleShare() {
		global $dpArticleShare, $wpdb;
		
		$widget = self::$widget;
		
		require_once (dirname (__FILE__) . '/../classes/base.class.php');
		$dpArticleShare_class = new DpArticleShare( false, $widget );
		
		array_walk($dpArticleShare, 'dpArticleShare_reslash_multi');
		$rand_num = rand();

		//if(!$calendar->active) { return ''; }
		
		$events_script= $dpArticleShare_class->addScripts();
		self::$js_declaration[] = $events_script;
		
		self::$js_flag = true;
		
		$html = $dpArticleShare_class->output();
					
		$this->html = $html;
	}
}

function dpArticleShare_simple_shortcode($atts) {
	global $wp_scripts, $dpArticleShare;
	
	extract(shortcode_atts(array(
		'widget' => ''
	), $atts));
	
	/* Add JS files */
	if ( !is_admin() ){ 
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'dpArticleShare', dpArticleShare_plugin_url( 'js/jquery.dpArticleShare.js' ),
			array('jquery'), DP_ARTICLE_SHARE_VER, true); 
		
		
		$data = $wp_scripts->get_data('dpArticleShare', 'data');
		if(empty($data)) {
			wp_localize_script( 'dpArticleShare', 'ArticleShareAjax', array( 
				'i18n_email_sent' => $dpArticleShare['i18n_email_sent'],
				'i18n_email_required' => $dpArticleShare['i18n_email_required'],
				'i18n_email_your_name' => $dpArticleShare['i18n_email_your_name'],
				'i18n_email_your_email' => $dpArticleShare['i18n_email_your_email'],
				'i18n_email_to' => $dpArticleShare['i18n_email_to'],
				'i18n_email_subject' => $dpArticleShare['i18n_email_subject'],
				'i18n_email_message' => $dpArticleShare['i18n_email_message'],
				'i18n_email_send' => $dpArticleShare['i18n_email_send'],
				'ajaxurl' => admin_url( 'admin-ajax.php' ), 
				'postEventsNonce' => wp_create_nonce( 'ajax-get-events-nonce' ) 
			) );
		}
		
		wp_enqueue_script( 'placeholder', dpArticleShare_plugin_url( 'js/jquery.placeholder.js' ),
				array('jquery'), DP_ARTICLE_SHARE_VER, true); 
	}
			
	wp_enqueue_style( 'dpArticleShare_headcss', dpArticleShare_plugin_url( 'css/dpArticleShare.css' ),
		false, DP_ARTICLE_SHARE_VER, 'all');


	$dpArticleShare_wpress_display = new dpArticleShare_wpress_display($widget);
	return $dpArticleShare_wpress_display->html;
}
add_shortcode('dpArticleShare', 'dpArticleShare_simple_shortcode');

/************************************************************************/
/*** DISPLAY END
/************************************************************************/

/************************************************************************/
/*** WIDGET START
/************************************************************************/

class DpArticleShare_Widget extends WP_Widget {
	function __construct() {
		$params = array(
			'description' => 'Use the Article Share as a widget',
			'name' => 'DP Article Share'
		);
		
		parent::__construct('ArticleShare', '', $params);
	}
	
	public function form($instance) {
		global $wpdb;
		
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
            
            
        <?php
	}
	
	public function widget($args, $instance) {
		global $wpdb, $table_prefix;
		$table_name = $table_prefix.DP_ARTICLE_SHARE_TABLE_EVENTS;
		
		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		$description = apply_filters('widget_description', $description);
		
		//if(empty($title)) $title = 'DP Article Share';
		
		echo $before_widget;
			if(!empty($title))
				echo $before_title . $title . $after_title;
			echo '<p>'. $description. '</p>';
			echo do_shortcode('[dpArticleShare widget=1]');
		echo $after_widget;
		
	}
}

add_action('widgets_init', 'dpArticleShare_register_widget');
function dpArticleShare_register_widget() {
	register_widget('DpArticleShare_Widget');
}

/************************************************************************/
/*** WIDGET END
/************************************************************************/

function dpArticleShare_enqueue_scripts() {
	
}
add_action( 'init', 'dpArticleShare_enqueue_scripts' );

//admin settings
function dpArticleShare_admin_scripts($force = false) {
	global $dpArticleShare;
	if ( is_admin() ){ // admin actions
		// Settings page only

		if ( $force || (isset($_GET['page']) && ('dpArticleShare-settings' == $_GET['page']))  ) {
			wp_register_script('jquery', false, false, false, false);
			
			wp_enqueue_script( 'tablednd_0_5', dpArticleShare_plugin_url( 'js/jquery.tablednd_0_5.js' ),
			array('jquery'), DP_ARTICLE_SHARE_VER, false); 
			wp_enqueue_script( 'dpArticleShare', dpArticleShare_plugin_url( 'js/jquery.dpArticleShare.js' ),
				array('jquery'), DP_ARTICLE_SHARE_VER, true); 
			wp_localize_script( 'dpArticleShare', 'ArticleShareAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'postEventsNonce' => wp_create_nonce( 'ajax-get-events-nonce' ) ) );
			wp_enqueue_script( 'placeholder', dpArticleShare_plugin_url( 'js/jquery.placeholder.js' ),
				array('jquery'), DP_ARTICLE_SHARE_VER, true); 
			wp_enqueue_script( 'colorpicker2', dpArticleShare_plugin_url( 'js/colorpicker.js' ),
				array('jquery'), DP_ARTICLE_SHARE_VER, false); 
			wp_enqueue_script ( 'dpArticleShare_admin', dpArticleShare_plugin_url( 'js/admin_settings.js' ), array('jquery-ui-dialog') ); 
			wp_enqueue_style( 'dpArticleShare_headcss', dpArticleShare_plugin_url( 'css/dpArticleShare.css' ),
				false, DP_ARTICLE_SHARE_VER, 'all');
			wp_enqueue_style( 'colorpicker', dpArticleShare_plugin_url( 'css/colorpicker.css' ),
				false, DP_ARTICLE_SHARE_VER, 'all');
		};
		wp_enqueue_style( 'dpArticleShare_admin_head_css', dpArticleShare_plugin_url( 'css/admin-styles.css' ),
			false, DP_ARTICLE_SHARE_VER, 'all');
  	}
}

add_action( 'admin_init', 'dpArticleShare_admin_scripts' );
add_action( 'pec_enqueue_admin', 'dpArticleShare_admin_scripts' );

function dpArticleShare_admin_head() {
	global $dpArticleShare;
	if ( is_admin() ){ // admin actions

	   // Settings page only
		if ( isset($_GET['page']) && 'dpArticleShare-settings' == $_GET['page'] ) {
		?>
			<script type="text/javascript">
			// <![CDATA[
				jQuery(document).ready(function() {
					
				});
			//]]>
			</script>
	<?php
	   } //Settings page only
	   
	 }//only for admin
}
add_action('admin_head', 'dpArticleShare_admin_head');
?>