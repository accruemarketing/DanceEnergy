<?php
/**
 * Dance Energy Generic
 *
 * @package   dance-energy-generic
 * @author    Christopher Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://buildawebdoctor.com
 * @copyright 8-27-2014 BAWD
 */

/**
 * Dance Energy Generic class.
 *
 * @package DanceEnergyGeneric
 * @author  Christopher Churchill <churchill.c.j@gmail.com>
 */
class DanceEnergyGeneric{
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = "1.0.0";

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = "dance-energy-generic";

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action("init", array($this, "load_plugin_textdomain"));

		// Add the options page and menu item.
		add_action("admin_menu", array($this, "add_plugin_admin_menu"));

		// Load admin style sheet and JavaScript.
		add_action("admin_enqueue_scripts", array($this, "enqueue_admin_styles"));
		add_action("admin_enqueue_scripts", array($this, "enqueue_admin_scripts"));

		// Load public-facing style sheet and JavaScript.
		add_action("wp_enqueue_scripts", array($this, "enqueue_styles"));
		add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));
		add_action( 'widgets_init', array($this, 'instructors_widget'));
		add_action( 'widgets_init', array($this, 'faq_widget'));
		add_action( 'widgets_init', array($this, 'carrer_widget'));
		add_action( 'widgets_init', array($this, 'testimonial_widget'));

		add_shortcode( 'testimonials_loop', array($this, 'testimonials_shrt'));

		add_shortcode( 'find_partner_loop', array($this, 'find_partner_loop'));

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action("TODO", array($this, "action_method_name"));
		add_filter("TODO", array($this, "filter_method_name"));
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn"t been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate($network_wide) {
		// TODO: Define activation functionality here
	}
    /**
     * Register the front end short code.
     *
     *  [bartag foo="foo-value"]
     *
     * @since    1.0.0
     */ 
    public function testimonials_shrt( $atts ) {
        	ob_start();

         	include_once("includes/testimonial-short.php");
         	
         	return ob_get_clean();
    }
    /**
      *
      *Find a partner Loop
      *
      * @params $atts
      *
      **/
    public function find_partner_loop($atts){
        	ob_start();

         	include_once("includes/find-partner-short.php");
         	
         	return ob_get_clean();
    }
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate($network_wide) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters("plugin_locale", get_locale(), $domain);

		load_textdomain($domain, WP_LANG_DIR . "/" . $domain . "/" . $domain . "-" . $locale . ".mo");
		load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . "/lang/");
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();
		if ($screen->id == $this->plugin_screen_hook_suffix) {
			wp_enqueue_style($this->plugin_slug . "-admin-styles", plugins_url("css/admin.css", __FILE__), array(),
				$this->version);
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();
		if ($screen->id == $this->plugin_screen_hook_suffix) {
			wp_enqueue_script($this->plugin_slug . "-admin-script", plugins_url("js/dance-energy-generic-admin.js", __FILE__),
				array("jquery"), $this->version);
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_slug . "-plugin-styles", plugins_url("css/public_generic.css", __FILE__), array(), $this->version);

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-jqueryui", "//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js",array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-ui", "//ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js",array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular", "http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.9.0.js",array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-animate", "//ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-animate.js",array("jquery"), $this->version);
        wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-sanitze", "//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular-sanitize.min.js", array("jquery"), $this->version);
        //full cal
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-full-moment", plugins_url("js/calendar/moment.min.js", __FILE__),array("jquery"), $this->version);		
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-fullcal", plugins_url("js/calendar/fullcalendar/dist/fullcalendar.js", __FILE__),array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-full-gcal", plugins_url("js/calendar/fullcalendar/dist/gcal.js", __FILE__),array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-spin", plugins_url("js/spin.js", __FILE__),array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-spinner", plugins_url("js/angular-spinner.min.js", __FILE__),array("jquery"), $this->version);
        wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-ui-cal", plugins_url("js/calendar.js", __FILE__),array("jquery"), $this->version);
        wp_enqueue_script( $this->plugin_slug . "-plugin-script-bootstrap", '//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js',array("jquery"), $this->version);
        //main app file
        wp_enqueue_script($this->plugin_slug . "-plugin-script", plugins_url("js/public.js", __FILE__), array("jquery"), $this->version, true);
        //Shop page
        wp_enqueue_script( $this->plugin_slug . "-plugin-script-isotope", plugins_url("js/jquery.isotope.min.js", __FILE__),array("jquery"), $this->version);
		wp_enqueue_script( $this->plugin_slug . "-plugin-script-angular-isotope", plugins_url("js/angular-isotope.min.js", __FILE__),array("jquery"), $this->version);
		wp_enqueue_script($this->plugin_slug . "-plugin-script-jquery-slider", plugins_url("js/bjqs-1.3.min.js", __FILE__), array("jquery"), $this->version);
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_plugins_page(__("Dance Energy Generic - Administration", $this->plugin_slug),
			__("Dance Energy Generic", $this->plugin_slug), "read", $this->plugin_slug, array($this, "display_plugin_admin_page"));
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once("views/admin.php");
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
	/**
	*
	*Include meet our instructors widget
	*
	*
	*/
	/****
		*
		*
		*Instructors widget init!
		*
		*
		**/
	public	function instructors_widget() {
	
		register_widget( 'instructors_widget_info' );
	
	}
	/****
		*
		*
		*FAQ widget init!
		*
		*
		**/
	public	function faq_widget() {
	
		register_widget( 'faq_widget_info' );
	
	}
	/****
		*
		*
		*carrer widget init!
		*
		*
		**/
	public	function carrer_widget() {
	
		register_widget( 'carrer_widget_info' );
	
	}
	/****
		*
		*
		*carrer widget init!
		*
		*
		**/
	public	function testimonial_widget() {
	
		register_widget( 'testimonial_widget_info' );
	
	}			
}
// Additing Action hook widgets_init
// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function myTruncate($string, $limit, $break=".", $pad="..."){
	// return with no change if string is shorter than $limit
	if(strlen($string) <= $limit) return $string;
	// is $break present between $limit and the end of the string?
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	}
	return $string;
}