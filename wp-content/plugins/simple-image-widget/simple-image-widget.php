<?php
/**
 * Simple Image Widget
 *
 * @package   SimpleImageWidget
 * @author    Brady Vercher
 * @copyright Copyright (c) 2014, Blazer Six, Inc.
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Simple Image Widget
 * Plugin URI: https://wordpress.org/extend/plugins/simple-image-widget/
 * Description: A simple image widget utilizing the new WordPress media manager.
 * Version: 4.1.2
 * Author: Blazer Six
 * Author URI: http://www.blazersix.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-image-widget
 * Domain Path: /languages
 */

/**
 * Main plugin instance.
 *
 * @since 4.0.0
 * @type Simple_Image_Widget $simple_image_widget
 */
global $simple_image_widget;

if ( ! defined( 'SIW_DIR' ) ) {
	/**
	 * Plugin directory path.
	 *
	 * @since 4.0.0
	 * @type string SIW_DIR
	 */
	define( 'SIW_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Check if the installed version of WordPress supports the new media manager.
 *
 * @since 3.0.0
 */
function is_simple_image_widget_legacy() {
	/**
	 * Whether the installed version of WordPress supports the new media manager.
	 *
	 * @since 4.0.0
	 *
	 * @param bool $is_legacy
	 */
	return apply_filters( 'is_simple_image_widget_legacy', version_compare( get_bloginfo( 'version' ), '3.4.2', '<=' ) );
}

/**
 * Include functions and libraries.
 */
require_once( SIW_DIR . 'includes/class-simple-image-widget.php' );
require_once( SIW_DIR . 'includes/class-simple-image-widget-legacy.php' );
require_once( SIW_DIR . 'includes/class-simple-image-widget-plugin.php' );
require_once( SIW_DIR . 'includes/class-simple-image-widget-template-loader.php' );

/**
 * Deprecated main plugin class.
 *
 * @since      3.0.0
 * @deprecated 4.0.0
 */
class Simple_Image_Widget_Loader extends Simple_Image_Widget_Plugin {}

// Initialize and load the plugin.
$simple_image_widget = new Simple_Image_Widget_Plugin();
add_action( 'plugins_loaded', array( $simple_image_widget, 'load' ) );
