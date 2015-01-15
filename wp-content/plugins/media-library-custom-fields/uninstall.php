<?php
/**
 * Uninstall plugin. Fired when the plugin is uninstalled.s
 *
 * @package     Media Library Custom Fields
 * @since       1.0.2
*/


// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

delete_option( 'abcfmlcf_optns' );