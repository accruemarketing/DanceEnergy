<?php
/*
Plugin Name: DP Article Social Share
Description: The Article Social Share plugin adds a sleek social bar in your articles and pages, to burst your page views.
Version: 1.2.7
Author: Diego Pereyra
Author URI: http://www.dpereyra.com/
Wordpress version supported: 3.0 and above
*/
@error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);

//on activation
//defined global variables and constants here
global $dpArticleShare, $table_prefix, $wpdb;
$dpArticleShare = get_option('dpArticleShare_options');
define("DP_ARTICLE_SHARE_VER","1.2.7",false);//Current Version of this plugin
if ( ! defined( 'DP_ARTICLE_SHARE_PLUGIN_BASENAME' ) )
	define( 'DP_ARTICLE_SHARE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'DP_ARTICLE_SHARE_CSS_DIR' ) ){
	define( 'DP_ARTICLE_SHARE_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/' );
}
// Create Text Domain For Translations
load_plugin_textdomain('dpArticleShare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

function checkMU_install_dpArticleShare($network_wide) {
	global $wpdb;
	if ( $network_wide ) {
		$blog_list = get_blog_list( 0, 'all' );
		foreach ($blog_list as $blog) {
			switch_to_blog($blog['blog_id']);
			install_dpArticleShare();
		}
		switch_to_blog($wpdb->blogid);
	} else {
		install_dpArticleShare();
	}
}

function install_dpArticleShare() {
	global $wpdb, $table_prefix;

   $default_events = array();
   $default_events = array(
   						   'version' 			=> 		DP_ARTICLE_SHARE_VER,
						   'user_roles'			=>		array(),
						   'position'			=>		'vertical-inside',
						   'vertical_offset'	=>		'0',
						   'skin'				=>		'light',
						   'show_counter'		=>		1,
						   'show_tooltips'		=>		1,
						   'disqus_enabled'		=>		false,
						   'disqus_api_key'		=>		'',
						   'show_counter_stats'	=>		false,
						   'limit_icons'		=>		false,
						   'limit_icons_number' =>		'',
						   'disqus_shortname'	=>		'',
						   'twitter_handle'		=>		'',
						   'i18n_share_on'		=>		'Share on',
						   'i18n_print'			=>		'Print',
						   'i18n_email'			=>		'Tell a Friend',
						   'i18n_more'			=>		'More Sharing Options',
						   'i18n_comments'		=>		'Comments',
						   'i18n_email_sent'	=>		'Email sent successfully!',
						   'i18n_email_required'=>		'All fields are required!',
						   'i18n_email_email_sent_by'=>	'This email has been sent by',
						   'i18n_email_your_name'=>		'Your Name',
						   'i18n_email_your_email'=>	'Your Email',
						   'i18n_email_to'		=>		'Introduce emails separated by comma (mail1@mail.com, mail2@mail.com)',
						   'i18n_email_subject'	=>		'Subject',
						   'i18n_email_message' =>		'Message',
						   'i18n_email_send'	=>		'Send',
						   'i18n_email_body'	=>		'Hi, I found this website and thought you might like it',
						   'scope'				=>		array('post', 'page', 'home'),
						   'social_icons_arr'=> array(
						   		"twitter" => array( "active" => 1, "name" => "twitter" ),
								"facebook" => array( "active" => 1, "name" => "facebook" ),
								"google" => array( "active" => 1, "name" => "google" ),
								"linkedin" => array( "active" => 1, "name" => "linkedin" ),
								"pinterest" => array( "active" => 1, "name" => "pinterest" ),
								"delicious" => array( "active" => 0, "name" => "delicious" ),
								"stumbleupon" => array( "active" => 0, "name" => "stumbleupon" ),
								"digg" => array( "active" => 0, "name" => "digg" ),
								"tumblr" => array( "active" => 0, "name" => "tumblr" ),
								"reddit" => array( "active" => 0, "name" => "reddit" ),
								"buffer" => array( "active" => 0, "name" => "buffer" ),
								"blogger" => array( "active" => 0, "name" => "blogger" ),
								"vk" => array( "active" => 0, "name" => "vk" ),
								"email" => array( "active" => 0, "name" => "email" ),
								"print" => array( "active" => 1, "name" => "print" ),
								"comments" => array( "active" => 1, "name" => "comments" )
						   ),
						   'icons_name'			=> true,
						   'support_pro_event_calendar'	=> true
			              );
   
	$dpArticleShare = get_option('dpArticleShare_options');
	
	if(!$dpArticleShare) {
	 $dpArticleShare = array();
	}
	
	foreach($default_events as $key=>$value) {
	  if(!isset($dpArticleShare[$key])) {
		 $dpArticleShare[$key] = $value;
	  }
	}
	
	delete_option('dpArticleShare_options');	  
	update_option('dpArticleShare_options',$dpArticleShare);
}
register_activation_hook( __FILE__, 'checkMU_install_dpArticleShare' );

/* Uninstall */
function checkMU_uninstall_dpArticleShare($network_wide) {
	global $wpdb;
	if ( $network_wide ) {
		$blog_list = get_blog_list( 0, 'all' );
		foreach ($blog_list as $blog) {
			switch_to_blog($blog['blog_id']);
			uninstall_dpArticleShare();
		}
		switch_to_blog($wpdb->blogid);
	} else {
		uninstall_dpArticleShare();
	}
}

function uninstall_dpArticleShare() {
	global $wpdb, $table_prefix;
	delete_option('dpArticleShare_options'); 
	
}
register_uninstall_hook( __FILE__, 'checkMU_uninstall_dpArticleShare' );

/* Add new Blog */

add_action( 'wpmu_new_blog', 'newBlog_dpArticleShare', 10, 6); 		
 
function newBlog_dpArticleShare($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;
 
	if (is_plugin_active_for_network('dpArticleShare/dpArticleShare.php')) {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		install_dpArticleShare();
		switch_to_blog($old_blog);
	}
}

//require_once (dirname (__FILE__) . '/update-notifier.php');
require_once (dirname (__FILE__) . '/functions.php');
require_once (dirname (__FILE__) . '/includes/core.php');
require_once (dirname (__FILE__) . '/settings/settings.php');


/*******************/
/* UPDATES 
/*******************/

if(!isset($dpArticleShare['support_pro_event_calendar'])) {
	$dpArticleShare['support_pro_event_calendar']	= true;

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_email_email_sent_by'])) {
	$dpArticleShare['i18n_email_email_sent_by']	= 'This email has been sent by';
	$dpArticleShare['i18n_email_your_name']	= 'Your Name';
	$dpArticleShare['i18n_email_your_email'] = 'Your Email';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_email_sent'])) {
	
	$dpArticleShare['i18n_email_sent']	= 'Email sent successfully!';
	$dpArticleShare['i18n_email_required'] = 'All fields are required!';
	$dpArticleShare['i18n_email_to'] = 'Introduce emails separated by comma (mail1@mail.com, mail2@mail.com)';
	$dpArticleShare['i18n_email_subject'] = 'Subject';
	$dpArticleShare['i18n_email_message'] = 'Message';
	$dpArticleShare['i18n_email_send']	= 'Send';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_more'])) {
	
	$dpArticleShare['i18n_more'] = 'More Sharing Options';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_email_body'])) {
	
	$dpArticleShare['i18n_email_body'] = 'Hi, I found this website and thought you might like it';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_email'])) {
	
	$dpArticleShare['i18n_email'] = 'Tell a Friend';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['i18n_share_on'])) {
	
	$dpArticleShare['i18n_share_on'] = 'Share on';
	$dpArticleShare['i18n_print'] = 'Print';
	$dpArticleShare['i18n_comments'] = 'Comments';

	update_option('dpArticleShare_options',$dpArticleShare);
}

if(!isset($dpArticleShare['icons_name'])) {
	
	$dpArticleShare['icons_name'] = true;
	$dpArticleShare['social_icons_arr'] = array(
			"twitter" => array( "active" => 1, "name" => "twitter" ),
			"facebook" => array( "active" => 1, "name" => "facebook" ),
			"google" => array( "active" => 1, "name" => "google" ),
			"linkedin" => array( "active" => 1, "name" => "linkedin" ),
			"pinterest" => array( "active" => 1, "name" => "pinterest" ),
			"print" => array( "active" => 1, "name" => "print" ),
			"comments" => array( "active" => 1, "name" => "comments" )
	   );

	update_option('dpArticleShare_options',$dpArticleShare);
}

?>