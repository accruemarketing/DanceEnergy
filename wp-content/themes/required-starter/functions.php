<?php
/**
 * This makes the child theme work. If you need any
 * additional features or let's say menus, do it here.
 *
 * @return void
 */
function required_starter_themesetup() {

	load_child_theme_textdomain( 'requiredstarter', get_stylesheet_directory() . '/languages' );

	// Register an additional Menu Location
	register_nav_menus( array(
		'meta' => __( 'Meta Menu', 'requiredstarter' )
	) );

	// Add support for custom backgrounds and overwrite the parent backgorund color
	add_theme_support( 'custom-background', array( 'default-color' => 'f7f7f7' ) );

}
add_action( 'after_setup_theme', 'required_starter_themesetup' );


/**
 * With the following function you can disable theme features
 * used by the parent theme without breaking anything. Read the
 * comments on each and follow the link, if you happen to not
 * know what the function is for. Remove the // in front of the
 * remove_theme_support('...'); calls to make them execute.
 *
 * @return void
 */
function required_starter_after_parent_theme_setup() {

	/**
	 * Hack added: 2012-10-04, Silvan Hagen
	 *
	 * This is a hack, to calm down PHP Notice, since
	 * I'm not sure if it's a bug in WordPress or my
	 * bad I'll leave it here: http://wordpress.org/support/topic/undefined-index-custom_image_header-in-after_setup_theme-of-child-theme
	 */
	if ( ! isset( $GLOBALS['custom_image_header'] ) )
		$GLOBALS['custom_image_header'] = array();

	if ( ! isset( $GLOBALS['custom_background'] ) )
		$GLOBALS['custom_background'] = array();

	// Remove custom header support: http://codex.wordpress.org/Custom_Headers
	//remove_theme_support( 'custom-header' );

	// Remove support for post formats: http://codex.wordpress.org/Post_Formats
	//remove_theme_support( 'post-formats' );

	// Remove featured images support: http://codex.wordpress.org/Post_Thumbnails
	//remove_theme_support( 'post-thumbnails' );

	// Remove custom background support: http://codex.wordpress.org/Custom_Backgrounds
	//remove_theme_support( 'custom-background' );

	// Remove automatic feed links support: http://codex.wordpress.org/Automatic_Feed_Links
	//remove_theme_support( 'automatic-feed-links' );

	// Remove editor styles: http://codex.wordpress.org/Editor_Style
	//remove_editor_styles();

	// Remove a menu from the theme: http://codex.wordpress.org/Navigation_Menus
	//unregister_nav_menu( 'secondary' );

}
add_action( 'after_setup_theme', 'required_starter_after_parent_theme_setup', 11 );

/**
 * Add our theme specific js file and some Google Fonts
 * @return void
 */
function required_starter_scripts() {

	/**
	 * Registers the child-theme.js
	 *
	 * Remove if you don't need this file,
	 * it's empty by default.
	 */
	/*
	wp_enqueue_script(
		'child-theme-js',
		get_stylesheet_directory_uri() . '/javascripts/child-theme.js',
		array( 'theme-js' ),
		required_get_theme_version( false ),
		true
	);
	*/
	/**
	 * Registers the app.css
	 *
	 * If you don't need it, remove it.
	 * The file is empty by default.
	 */
	wp_register_style(
        'app-css', //handle
        get_stylesheet_directory_uri() . '/stylesheets/app.css',
        array( 'foundation-css' ),	// needs foundation
        required_get_theme_version( false ) //version
  	);
  	wp_enqueue_style( 'app-css' );

	/**
	 * Adding google fonts
	 *
	 * This is the proper code to add google fonts
	 * as seen in TwentyTwelve
	 */
	$protocol = is_ssl() ? 'https' : 'http';
	$query_args = array( 'family' => 'Open+Sans:300,600' );
	wp_enqueue_style(
		'open-sans',
		add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ),
		array(),
		null
	);
}
add_action('wp_enqueue_scripts', 'required_starter_scripts');

/**
 * Overwrite the default continue reading link
 *
 * This function is an example on how to overwrite
 * the parent theme function to create continue reading
 * links.
 *
 * @return string HTML link with text and permalink to the post/page/cpt
 */
function required_continue_reading_link() {
	return ' <a class="read-more" href="'. esc_url( get_permalink() ) . '">' . __( ' Read on! &rarr;', 'requiredstarter' ) . '</a>';
}

/**
 * Overwrite the defaults of the Orbit shortcode script
 *
 * Accepts all the parameters from http://foundation.zurb.com/docs/orbit.php#optCode
 * to customize the options for the orbit shortcode plugin.
 *
 * @param  array $args default args
 * @return array       your args
 */
function required_orbit_script_args( $defaults ) {
	$args = array(
		'animation' 	=> 'fade',
		'advanceSpeed' 	=> 8000,
	);
	return wp_parse_args( $args, $defaults );
}
add_filter( 'req_orbit_script_args', 'required_orbit_script_args' );
/**
 * Register Sidebar
 */
function textdomain_register_sidebars() {
	/* Register the Home page sidebar. */
	register_sidebar(
		array(
			'id' => 'home-sidebar',
			'name' => __( 'Home Sidebar', 'textdomain' ),
			'description' => __( 'Home page sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	/* Register the Footer Widget areas. */
	register_sidebar(
		array(
			'id' => 'footer-widget-one',
			'name' => __( 'Footer Sidebar', 'textdomain' ),
			'description' => __( 'Footer first sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'footer-widget-two',
			'name' => __( 'Footer Sidebar', 'textdomain' ),
			'description' => __( 'Footer second sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'footer-widget-thre',
			'name' => __( 'Footer Sidebar', 'textdomain' ),
			'description' => __( 'Footer third sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'shop-page',
			'name' => __( 'Shop Sidebar', 'textdomain' ),
			'description' => __( 'Shop sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'dance-style',
			'name' => __( 'Dance Style Sidebar', 'textdomain' ),
			'description' => __( 'Dance Style Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'instructor',
			'name' => __( 'Instructor Sidebar', 'textdomain' ),
			'description' => __( 'instructor Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'about',
			'name' => __( 'About Sidebar', 'textdomain' ),
			'description' => __( 'About Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);	
/*
	DANCE STYLES SIDEBAR
*/		
	register_sidebar(
		array(
			'id' => 'learn-dance',
			'name' => __( 'Learn Dance generic Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance generic Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'learn-dance-fun',
			'name' => __( 'Learn Dance fun Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance fun Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);	
	register_sidebar(
		array(
			'id' => 'learn-dance-wedding',
			'name' => __( 'Learn Dance wedding Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance wedding Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);	
	register_sidebar(
		array(
			'id' => 'learn-dance-fit',
			'name' => __( 'Learn Dance fit Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance fit Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);	
	register_sidebar(
		array(
			'id' => 'learn-dance-people',
			'name' => __( 'Learn Dance people Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance people Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'learn-dance-competition',
			'name' => __( 'Learn Dance competition Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance competition Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'learn-dance-kids',
			'name' => __( 'Learn Dance kids Sidebar', 'textdomain' ),
			'description' => __( 'Learn Dance kids Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'contact',
			'name' => __( 'Contact Sidebar', 'textdomain' ),
			'description' => __( 'Contact Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'id' => 'carrers',
			'name' => __( 'Carrers Sidebar', 'textdomain' ),
			'description' => __( 'Carrers Sidebar.', 'textdomain' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);							

	/* Repeat register_sidebar() code for additional sidebars. */
}
add_action( 'widgets_init', 'textdomain_register_sidebars' );

function required_themesetup() {
	/* Make required+ Foundation available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on required+ Foundation, use a find and replace
	 * to change 'required' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'requiredfoundation', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in two locations by default.
	add_theme_support('menus');

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'requiredfoundation' ),
		'secondary' => __( 'Secondary Menu', 'requiredfoundation' )
	) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'link', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds. (The wp-head-callback is to make sure nothing happens, when we remove the action in the child theme)
	add_theme_support( 'custom-background', array( 'default-color' => 'ffffff', 'wp-head-callback' => '_custom_background_cb' ) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

}

function add_theme_menu_pages(){
	add_menu_page( 'Main options', 'Theme settings', 'manage_options', 'dance_theme_settings', 'dance_theme_settings');
	add_submenu_page( 'dance_theme_settings', 'Shop page settings', 'Shop page settings', 'manage_options', 'shop-page-options', 'shop_page_options_func' );

}
function dance_theme_settings(){
	echo "dance_theme_settings....";
}

function shop_page_options_func(){
if(function_exists( 'wp_enqueue_media' )){
    wp_enqueue_media();
}else{
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}

if (isset($_POST["update_settings"])) {
    // Do the saving
    $custom_media_url_shop = esc_attr($_POST["custom_media_url_shop"]);
	update_option("custom_media_url_shop", $custom_media_url_shop);
    
    $custom_shop_page_title = esc_attr($_POST["custom_shop_page_title"]);
	update_option("custom_shop_page_title", $custom_shop_page_title);

	$custom_shop_page_sub_title = esc_attr($_POST["custom_shop_page_sub_title"]);
	update_option("custom_shop_page_sub_title", $custom_shop_page_sub_title);
	//images
    $shop_page_image_one = esc_attr($_POST["shop_page_image_one"]);
	update_option("shop_page_image_one", $shop_page_image_one);
    $shop_page_link_one = esc_attr($_POST["shop_page_link_one"]);
	update_option("shop_page_link_one", $shop_page_link_one);	
    $shop_page_text_one = esc_attr($_POST["shop_page_text_one"]);
	update_option("shop_page_text_one", $shop_page_text_one);

    $shop_page_image_two = esc_attr($_POST["shop_page_image_two"]);
	update_option("shop_page_image_two", $shop_page_image_two);
    $shop_page_link_two = esc_attr($_POST["shop_page_link_two"]);
	update_option("shop_page_link_two", $shop_page_link_two);
    $shop_page_text_two = esc_attr($_POST["shop_page_text_two"]);
	update_option("shop_page_text_two", $shop_page_text_two);


    $shop_page_image_three = esc_attr($_POST["shop_page_image_three"]);
	update_option("shop_page_image_three", $shop_page_image_three);
    $shop_page_link_three = esc_attr($_POST["shop_page_link_three"]);
	update_option("shop_page_link_three", $shop_page_link_three);
    $shop_page_text_three = esc_attr($_POST["shop_page_text_three"]);
	update_option("shop_page_text_three", $shop_page_text_three);


    $shop_page_image_four = esc_attr($_POST["shop_page_image_four"]);
	update_option("shop_page_image_four", $shop_page_image_four);
    $shop_page_link_four = esc_attr($_POST["shop_page_link_four"]);
	update_option("shop_page_link_four", $shop_page_link_four);
    $shop_page_text_four = esc_attr($_POST["shop_page_text_four"]);
	update_option("shop_page_text_four", $shop_page_text_four);



	?>
	<div id="message" class="updated">Settings saved</div>
	<?php
}else{
	$custom_media_url_shop = get_option("custom_media_url_shop");
	$custom_shop_page_title = get_option("custom_shop_page_title");
	$custom_shop_page_sub_title = get_option("custom_shop_page_sub_title");
	//ad images/links
	$shop_page_image_one = get_option("shop_page_image_one");
	$shop_page_link_one = get_option("shop_page_link_one");
	$shop_page_text_one = get_option("shop_page_text_one");

	$shop_page_image_two = get_option("shop_page_image_two");
	$shop_page_link_two = get_option("shop_page_link_two");
	$shop_page_text_two = get_option("shop_page_text_two");
	
	$shop_page_image_three = get_option("shop_page_image_three");
	$shop_page_link_three = get_option("shop_page_link_three");
	$shop_page_text_three = get_option("shop_page_text_three");
	

	$shop_page_image_four = get_option("shop_page_image_four");
	$shop_page_link_four = get_option("shop_page_link_four");
	$shop_page_text_four = get_option("shop_page_text_four");


}
?>
    <div class="wrap">
        <?php screen_icon('themes'); ?> <h2>Shop, Cart and checkout page options</h2>
 	<script type="text/javascript">
			jQuery(document).ready(function($){

			$('.custom_media_upload').click(function() {

			        var send_attachment_bkp = wp.media.editor.send.attachment;
			        var button = $(this);

			        wp.media.editor.send.attachment = function(props, attachment) {

			            $(button).prev().prev().attr('src', attachment.url);
			            $(button).prev().val(attachment.url);

			            wp.media.editor.send.attachment = send_attachment_bkp;
			        }

			        wp.media.editor.open(button);

			        return false;       
			    });
			$('.shop_page_image_one').click(function() {

			        var send_attachment_bkp = wp.media.editor.send.attachment;
			        var button = $(this);

			        wp.media.editor.send.attachment = function(props, attachment) {

			            $(button).prev().prev().attr('src', attachment.url);
			            $(button).prev().val(attachment.url);

			            wp.media.editor.send.attachment = send_attachment_bkp;
			        }

			        wp.media.editor.open(button);

			        return false;       
			    });
			$('.shop_page_image_two').click(function() {

			        var send_attachment_bkp = wp.media.editor.send.attachment;
			        var button = $(this);

			        wp.media.editor.send.attachment = function(props, attachment) {

			            $(button).prev().prev().attr('src', attachment.url);
			            $(button).prev().val(attachment.url);

			            wp.media.editor.send.attachment = send_attachment_bkp;
			        }

			        wp.media.editor.open(button);

			        return false;       
			    });	

			$('.shop_page_image_three').click(function() {

			        var send_attachment_bkp = wp.media.editor.send.attachment;
			        var button = $(this);

			        wp.media.editor.send.attachment = function(props, attachment) {

			            $(button).prev().prev().attr('src', attachment.url);
			            $(button).prev().val(attachment.url);

			            wp.media.editor.send.attachment = send_attachment_bkp;
			        }

			        wp.media.editor.open(button);

			        return false;       
			    });	
			$('.shop_page_image_four').click(function() {

			        var send_attachment_bkp = wp.media.editor.send.attachment;
			        var button = $(this);

			        wp.media.editor.send.attachment = function(props, attachment) {

			            $(button).prev().prev().attr('src', attachment.url);
			            $(button).prev().val(attachment.url);

			            wp.media.editor.send.attachment = send_attachment_bkp;
			        }

			        wp.media.editor.open(button);

			        return false;       
			    });				    		    			    			    		

			});
 	</script>
 	<style>
		.form-table.shopagesettings tr.lasttr{ border: 0; border-bottom: 2px solid #ddd; }
 	</style>
 	<h4>Please keep all these settintgs filled...</h4>
        <form method="POST" action="">
        	<input type="hidden" name="update_settings" value="Y" />
            <table class="form-table shopagesettings">
	           <tr valign="top">
                    <th scope="row">
                        <label for="shoppage_head_img">
                            Shop page header image:
                        </label> 
                    </th>
                    <td>
					<!-- Image Thumbnail -->
					<img class="custom_media_image" src="<?php echo $custom_media_url_shop;?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />
					<!-- Upload button and text field -->
					<input class="custom_media_url_shop" id="" type="text" name="custom_media_url_shop" value="<?php echo $custom_media_url_shop;?>" style="margin-bottom:10px; clear:right;">
					<a href="#" class="button custom_media_upload">Upload</a>
                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="custom_shop_page_title">
                            Shop page title:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="custom_shop_page_title" placeholder="shop page header text" value="<?php echo  $custom_shop_page_title; ?>">
                    </td>
                </tr>
            	<tr valign="top" class="lasttr">
                    <th scope="row">
                        <label for="custom_shop_page_sub_title">
                            Shop page sub title:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="custom_shop_page_sub_title" placeholder="shop page sub header" value="<?php echo  $custom_shop_page_sub_title; ?>">
                    </td>
                </tr>                
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_image_one">
                            First intro image:
                        </label> 
                    </th>
                    <td>
						<!-- Image Thumbnail -->
						<img class="shop_page_image_one" src="<?php echo $shop_page_image_one;?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />
						<!-- Upload button and text field -->
						<input class="shop_page_image_one" id="" type="text" name="shop_page_image_one" value="<?php echo $shop_page_image_one;?>" style="margin-bottom:10px; clear:right;">
						<a href="#" class="button custom_media_upload">Upload</a>

                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_link_one">
                            First intro image link:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_link_one" placeholder="link url" value="<?php echo  $shop_page_link_one; ?>">
                    </td>
                </tr>
            	<tr valign="top" class="lasttr">
                    <th scope="row">
                        <label for="shop_page_text_one">
                            First intro image text:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_text_one" placeholder="Advert Text" value="<?php echo  $shop_page_text_one; ?>">
                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_image_two">
                            Second intro image:
                        </label> 
                    </th>
                    <td>
						<!-- Image Thumbnail -->
						<img class="shop_page_image_two" src="<?php echo $shop_page_image_two;?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />
						<!-- Upload button and text field -->
						<input class="shop_page_image_two" id="" type="text" name="shop_page_image_two" value="<?php echo $shop_page_image_two;?>" style="margin-bottom:10px; clear:right;">
						<a href="#" class="button custom_media_upload">Upload</a>						
                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_link_two">
                            Second intro image link:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_link_two" placeholder="link url" value="<?php echo  $shop_page_link_two; ?>">
                    </td>
                </tr>
            	<tr valign="top" class="lasttr">
                    <th scope="row">
                        <label for="shop_page_text_two">
                            Second intro image text:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_text_two" placeholder="Advert Text" value="<?php echo  $shop_page_text_two; ?>">
                    </td>
                </tr>                                         
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_image_three">
                            Third intro image:
                        </label> 
                    </th>
                    <td>
						<!-- Image Thumbnail -->
						<img class="shop_page_image_three" src="<?php echo $shop_page_image_three;?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />
						<!-- Upload button and text field -->
						<input class="shop_page_image_three" id="" type="text" name="shop_page_image_three" value="<?php echo $shop_page_image_three;?>" style="margin-bottom:10px; clear:right;">
						<a href="#" class="button custom_media_upload">Upload</a>						
                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_link_three">
                            Third intro image link:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_link_three" placeholder="link url" value="<?php echo  $shop_page_link_three; ?>">
                    </td>
                </tr>     
            	<tr valign="top" class="lasttr">
                    <th scope="row">
                        <label for="shop_page_text_three">
                            Third intro image text:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_text_three" placeholder="Advert Text" value="<?php echo  $shop_page_text_three; ?>">
                    </td>
                </tr>
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_image_four">
                            Fourth intro image:
                        </label> 
                    </th>
                    <td>
						<!-- Image Thumbnail -->
						<img class="shop_page_image_four" src="<?php echo $shop_page_image_four;?>" style="max-width:100px; float:left; margin: 0px     10px 0px 0px; display:inline-block;" />
						<!-- Upload button and text field -->
						<input class="shop_page_image_four" id="" type="text" name="shop_page_image_four" value="<?php echo $shop_page_image_four;?>" style="margin-bottom:10px; clear:right;">
						<a href="#" class="button custom_media_upload">Upload</a>						
                    </td>
                </tr>           
            	<tr valign="top">
                    <th scope="row">
                        <label for="shop_page_link_four">
                            Fourth intro image link:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_link_four" placeholder="link url" value="<?php echo  $shop_page_link_four; ?>">
                    </td>
                </tr>                   
              	<tr valign="top" class="lasttr">
                    <th scope="row">
                        <label for="shop_page_text_four">
                            Fourth intro image text:
                        </label> 
                    </th>
                    <td>
						<input type="text" name="shop_page_text_four" placeholder="Advert Text" value="<?php echo  $shop_page_text_four; ?>">
                    </td>
                </tr>     
            </table>
                <input type="submit" value="Save settings" class="button-primary"/>
        </form>
    </div>
<?php







}
add_action( 'admin_menu', 'add_theme_menu_pages' );


//Remove woocommerce stylesheets 
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

//custom woocommerce stuff
function woocommerce_template_loop_product_thumbnail(){
 echo woocommerce_get_product_thumbnail();
}
function woocommerce_template_single_price() {

}



add_action( 'after_setup_theme', 'my_child_theme_setup' );
function my_child_theme_setup() {
      // We are providing our own filter for excerpt_length (or using the unfiltered value)
      remove_filter( 'excerpt_more', 'required_auto_excerpt_more' ); 
}
function new_excerpt_more( $more ) {
	return ' <br><a class="button" href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'Wordpress-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );


/**
 * Plugin Name: WooCommerce - List Products by Tags
 * Plugin URI: http://www.remicorson.com/list-woocommerce-products-by-tags/
 * Description: List WooCommerce products by tags using a shortcode, ex: [woo_products_by_tags tags="shoes,socks"]
 * Version: 1.0
 * Author: Remi Corson
 * Author URI: http://remicorson.com
 * Requires at least: 3.5
 * Tested up to: 3.5
 *
 * Text Domain: -
 * Domain Path: -
 *
 */
 
/*
 * List WooCommerce Products by tags
 *
 * ex: [woo_products_by_tags tags="shoes,socks"]
 */
function woo_products_by_tags_shortcode( $atts, $content = null ) {
  
	// Get attribuets
	extract(shortcode_atts(array(
		"tags" => ''
	), $atts));
	
	ob_start();
 
	// Define Query Arguments
	$args = array( 
				'post_type' 	 => 'product', 
				'posts_per_page' => 5, 
				'product_tag' 	 => $tags 
				);
	
	// Create the new query
	$loop = new WP_Query( $args );
	
	// Get products number
	$product_count = $loop->post_count;
	
	// If results
	if( $product_count > 0 ) :
	
		echo '<ul class="products">';
		
			// Start the loop
			while ( $loop->have_posts() ) : $loop->the_post(); global $product;
			
				global $post;
				
				echo "<p>" . $thePostID = $post->post_title. " </p>";
				
				if (has_post_thumbnail( $loop->post->ID )) 
					echo  get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); 
				else 
					echo '<img src="'.$woocommerce->plugin_url().'/assets/images/placeholder.png" alt="" width="'.$woocommerce->get_image_size('shop_catalog_image_width').'px" height="'.$woocommerce->get_image_size('shop_catalog_image_height').'px" />';
		
			endwhile;
		
		echo '</ul><!--/.products-->';
	
	else :
	
		_e('No product matching your criteria.');
	
	endif; // endif $product_count > 0
	
	return ob_get_clean();
 
}
 
add_shortcode("woo_products_by_tags", "woo_products_by_tags_shortcode");



/****
	*stop tinymce from fucking shit up... 
 	* see: http://wordpress.stackexchange.com/questions/59772/stop-editor-from-removing-p-tags-and-replacing-them-with-nbsp
 	****/
function tinymce_config( $init ) {
   // Don't remove line breaks
   $init['remove_linebreaks'] = false; 
   // Convert newline characters to BR tags
   $init['convert_newlines_to_brs'] = true; 
   // Do not remove redundant BR tags
   $init['remove_redundant_brs'] = false;

   // Pass $init back to WordPress
   return $init;
}
add_filter('tiny_mce_before_init', 'tinymce_config');







/**
 *
 *Remove add to cart buttons prior to launch
 *
 *
 */
function removeLoopButton() {
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}
add_action( 'init', 'removeLoopButton' );