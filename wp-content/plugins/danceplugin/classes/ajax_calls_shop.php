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
 * ajaxclass class.
 *
 * @package FindPartner
 * @author  Christopher Churchill <churchill.c.j@gmail.com>
 */
class ajaxclass{
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
		//shop page and categories
		add_action('wp_ajax_nopriv_cats_loop', array($this, 'cats_loop') );
		add_action( 'wp_ajax_cats_loop', array($this,  'cats_loop') );
		add_action('wp_ajax_nopriv_cats_child_loop', array($this,  'cats_child_loop') );
		add_action( 'wp_ajax_cats_child_loop', array($this,  'cats_child_loop') );
		add_action('wp_ajax_nopriv_shop_page_loop', array($this, 'shop_page_loop') );
		add_action( 'wp_ajax_shop_page_loop', array($this,  'shop_page_loop') );
		//partners
		add_action('wp_ajax_nopriv_get_partners_loop', array($this, 'get_partners_loop') );
		add_action( 'wp_ajax_get_partners_loop', array($this,  'get_partners_loop') );
		add_action('wp_ajax_nopriv_get_partner_loop', array($this, 'get_partner_loop') );
		add_action( 'wp_ajax_get_partner_loop', array($this,  'get_partner_loop') );	
		add_action('wp_ajax_nopriv_save_partner', array($this, 'save_partner') );
		add_action( 'wp_ajax_save_partner', array($this,  'save_partner') );	
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
	 * 
	 */
	public function get_partners_loop(){
		$current_user_ID = get_current_user_id();

		$get_saved = get_user_meta( $current_user_ID, 'wpcf-dance-partners-json' );
        $decodemeta = json_decode( $get_saved[0] );

		$usepartnersloop = array();
		$error = '';
		if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : 
		 global $members_template;
		  	while ( bp_members() ) : bp_the_member(); 
		  		
		  		$profile_data = BP_XProfile_ProfileData::get_all_for_user( $members_template->member->ID );
		  		if($profile_data["Please Include Me On The Dance Partner Wanted Directory"]['field_data'] === 'Yes'){
		  			$usepartnersloop[ $profile_data['user_nicename'] ] = $profile_data;
		  			$userid = $members_template->member->ID;
		  			//TODO IMPLEMENT CURRENT USER AND RETURN TRUE/FALSE IF THEY ALREADY HAVE A PARTNER...
		  			if($decodemeta->$userid) {
					    // It exists!
		  				$usepartnersloop[ $profile_data['user_nicename'] ]['selected'] = true;
		  				$usepartnersloop[ $profile_data['user_nicename'] ]['saved'] = true;
					}else{
		  				$usepartnersloop[ $profile_data['user_nicename'] ]['selected'] = false;
		  				$usepartnersloop[ $profile_data['user_nicename'] ]['saved'] = false;

					}


		  			$usepartnersloop[ $profile_data['user_nicename'] ]['descclean'] = stripslashes( $profile_data['Description']['field_data'] );
		  			$usepartnersloop[ $profile_data['user_nicename'] ]['ID'] = $members_template->member->ID;
		  			$usepartnersloop[ $profile_data['user_nicename'] ]['permalink'] = bp_core_get_user_domain( $members_template->member->id, $members_template->member->user_nicename, $members_template->member->user_login ) ;
		  			$usepartnersloop[ $profile_data['user_nicename'] ]['avatar'] = bp_core_fetch_avatar( array( 'item_id' => $members_template->member->id, 'type' => $type, 'alt' => $alt, 'css_id' => $id, 'class' => $class, 'width' => $width, 'height' => $height, 'email' => $members_template->member->user_email ) ) ;
		  		}

		    endwhile;

		    $usepartnersloop = json_encode($usepartnersloop);
			
			echo  $usepartnersloop;
		
		else: 
			$error = "Sorry, no members were found.";  
			echo $error;
		endif;

		die(); // this is required to return a proper result
	}
	/**
	 * 
	 */
	public function get_partner_loop(){
		$request_body = file_get_contents( 'php://input' );
        $decodeit     = json_decode( $request_body );
        $user_id           = $decodeit->ID;
		$usepartnersloop = array();
		$error = '';
		$profile_data = BP_XProfile_ProfileData::get_all_for_user( $user_id );
		if($profile_data["Please Include Me On The Dance Partner Wanted Directory"]['field_data'] === 'Yes'){
			$usepartnersloop[ 'user' ] = $profile_data;
		  	$usepartnersloop[ 'user' ]['descclean'] = stripslashes( $profile_data['Description']['field_data'] );
		  	$usepartnersloop[ 'user' ]['ID'] = $user_id;
		  	$usepartnersloop[ 'user' ]['permalink'] = bp_core_get_user_domain( $user_id, $profile_data['user_nicename'], $profile_data['user_login'] ) ;
		  	$usepartnersloop[ 'user' ]['avatar'] = bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => $type, 'alt' => $alt, 'css_id' => $id, 'class' => $class, 'width' => $width, 'height' => $height, 'email' => $profile_data['user_email'] ) ) ;
		}
		
		$usepartnersloop = json_encode($usepartnersloop);

		echo  $usepartnersloop;
		
		die(); // this is required to return a proper result
	}
	/**
	 * 
	 */
	public function save_partner(){
		$current_user_ID = get_current_user_id();
		$request_body = file_get_contents( 'php://input' );
        $decodeit     = json_decode( $request_body );

        update_user_meta( $current_user_ID, 'wpcf-dance-partners-json', $request_body );

        $get_saved = get_user_meta( $current_user_ID, 'wpcf-dance-partners-json' );
        $decodemeta = json_decode( $get_saved[0] );
		var_dump( $decodemeta );
		die(); // this is required to return a proper result
	}	
	/****
	 	* 
		*Cart Page
	 	***/
	public function cats_loop() {
		// Include the client library
		$catreturn = array();
		
		$categories = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );

		foreach ($categories as $key => $value) {

			if($value->parent == '0'){

				$catreturn[$key] = $value;

			}
		}

			$catreturn = json_encode( $catreturn );
			
			echo $catreturn;
		die(); // this is required to return a proper result
	}
	/**
	 * 
	 */
	public function cats_child_loop() {
		// Include the client library
		$catreturn = array();
		$categories = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );

			foreach ($categories as $key => $value) {

				if($value->parent != '0'){
					$catreturn[$key] = $value;
				}

			}
			$catreturn = json_encode( $catreturn );
			
			echo $catreturn;
		die(); // this is required to return a proper result
	}
	/**
	 * 
	 */
	public function shop_page_loop() {
		$args = array( 'post_type' => 'product' );
		$query = new WP_Query( $args );
	  	$justmeta = array();
	  	$justcats = array();
	  	$prodsandmeta =  array( );

		foreach ($query->posts as $key => $value) {
		 	$classes = '';
		 	# code...
		 	$prodsandmeta[$key] = $value;
		 	$post_id = $value->ID;
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );	 	
			$getprodmet = get_post_meta( $post_id );
		 	$categories = get_the_terms( $post_id, 'product_cat' );
		 	
		 	//var_dump($categories);

		 	foreach ($getprodmet as $subkey => $subvalue){

		 		$justmeta[str_replace('-', '_', $subkey)] = $subvalue[0];
		 	
		 	}
		 	foreach ($categories as $subcatkey => $subcatvalue) {
		 		
		 		$classes = $classes . ' ' . $subcatvalue->slug;
		 		$justcats[$subcatkey] = $subcatvalue;

		 	}

		 	//	var_dump($prodsandmeta);

		 	$prodsandmeta[$key]->classes = $classes;
		 	$prodsandmeta[$key]->product_meta = $justmeta;
		 	$prodsandmeta[$key]->prod_cat = $justcats;
		 	if( $image === false ){
		 		$prodsandmeta[$key]->prod_img = '/wp-content/plugins/woocommerce/assets/images/placeholder.png';
		 	}else{
		 		$prodsandmeta[$key]->prod_img = $image[0];
		 	}

		 	//var_dump($key);
		}

		$backtoangular = json_encode( $prodsandmeta );
		
		echo $backtoangular ;
		
		die(); // this is required to return a proper result
	}
}