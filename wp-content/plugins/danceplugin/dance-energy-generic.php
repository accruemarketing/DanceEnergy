<?php
/**
 * Dance Energy Generic
 *
 * Include custom widget's and stuff
 *
 * @package   dance-energy-generic
 * @author    Christopher Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://buildawebdoctor.com
 * @copyright 8-27-2014 BAWD
 *
 * @wordpress-plugin
 * Plugin Name: Dance Energy Generic
 * Plugin URI:  http://buildawebdoctor.com
 * Description: Include custom widget's and stuff
 * Version:     1.0.0
 * Author:      Christopher Churchill
 * Author URI:  http://buildawebdoctor.com
 * Text Domain: dance-energy-generic-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
	die;
}

require_once(plugin_dir_path(__FILE__) . "DanceEnergyGeneric.php");
require_once(plugin_dir_path(__FILE__) . "classes/testimonial_widget_info.php");
require_once(plugin_dir_path(__FILE__) . "classes/instructors_widget_info.php");
require_once(plugin_dir_path(__FILE__) . "classes/faq_widget_info.php");
require_once(plugin_dir_path(__FILE__) . "classes/carrer_widget_info.php");
require_once(plugin_dir_path(__FILE__) . "classes/find_partner.php");
require_once(plugin_dir_path(__FILE__) . "classes/ajax_calls_shop.php");

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array("DanceEnergyGeneric", "activate"));
register_deactivation_hook(__FILE__, array("DanceEnergyGeneric", "deactivate"));

DanceEnergyGeneric::get_instance();
FindPartner::get_instance();
ajaxclass::get_instance();
