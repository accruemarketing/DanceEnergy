<?php
/*
Plugin Name: Thumbnail Gallery (WordPress Plugin)
Version: 2.4
Plugin URI: http://codecanyon.net/item/thumbnail-gallery-wordpress-plugin/294024?ref=DOTonPAPER
Description: This Plugin will help you to easily add a thumbnail gallery to your WordPress website or blog. The gallery is completely customizable, resizable and is compatible with all browsers and devices (iPhone, iPad and Android smartphones). You will be able to insert it in any page or post you want with an inbuilt short code generator.<br /><br />If you like this plugin, feel free to rate it five stars at <a href="http://codecanyon.net/item/wallgrid-gallery-wordpress-plugin/270895?ref=DOTonPAPER" target="_blank">CodeCanyon</a> in your downloads section. If you encounter any problems please do not give a low rating but <a href="http://envato-support.dotonpaper.net">visit</a> our <a href="http://envato-support.dotonpaper.net">Support Forums</a> first so we can help you.
Author: Dot on Paper
Author URI: http://www.dotonpaper.net

Change log:

        2.4 (2012-10-01)

                * Admin interface changes.
                * CSS fixes.
                * Delete/Enable/Disable multiple images.
                * Help tooltip updated.
                * Small bugs fixed.
                * Translation structure has changed.
               
        2.3 (2012-05-05)

                * AddThis fixed.
                * Responsive Media bug fix.
                * SEO fixes.

        2.2 (2013-03-29)

                * Database is deleted when you delete the plugin.
                * Display a list with default settings & all settings you created.
                * Gallery resize on hidden elements bug fix.
                * Slow admin bug fix.
                * Small Admin changes.
                * Update notification added.
                * Uploading Settings Images on MU bug fix.

        2.1 (2013-01-11)
                
                * Caption can be positioned over the image. 
                * Lightbox display bug on Chrome is fixed.
                * Option to move navigation buttons outside the image.
                * You can navigate the gallery with arrows + enter to open the lightbox.
                * Remove lightbox margins on mobile devices.
                * Set thumbnails size when gallery is responsive on mobile devices.
                * Vertical thumbnails now position correctly after loading.

        2.0 (2012-12-05)

                * Small bugs fixes.

        1.9 (2012-10-11)

                * Data can be parsed in the gallery using HTML.
                * Small bugs fixes.
                * Upload methods script changes.

        1.8 (2012-06-20)

                * AddThis Social Share added.
                * Small bugs fixes.
 
        1.7 (2012-06-17)

                * Uploadify security fix.
 
        1.6 (2012-04-25)

                * Minor bugs fixes. 
                * Responsive layout added.
         
        1.5 (2012-01-25)

                * Slideshow Play/Pause added.
                * Small navigation fixes.
                * Swipe image/lightbox on touch devices added.
                * Thumbnails navigation arrows added.

        1.4 (2012-01-07)

                * Captions will now show over media.
                * Edit image area fixes.
                * FTP upload added.
                * IE 8 thumbnails resize fix.
                * Integrate AJAX+JSON in Back End section.
                * Jcrop updated.
                * Lightbox added.
                * Navigation buttons are now images.
                * Settings Edit fixes.
                * Simple AJAX file upload fix.
                * Thumbnails position changes to show current item thumbnail.
                * Update system removed (the Envato API didn't support the number of connections made and it become confusing for users).
                * Use WordPress native file upload system.

        1.3 (2011-10-13)

                * Simple AJAX file upload added.

	1.2 (2011-08-31)

                * Admin sprite updated.
                * Caption allows all characters.
                * Caption "new line" bug fixed.
                * Change tables prefix fixed.
                * Initial thumbnails have better quality.
                * Install plugin by uploading fixed.
                * Integrate AJAX+JSON in Front End gallery.
                * Plugin update notifications added.
                * You can add Youtube & Vimeo videos, HTML, Flash ...
                * You can disable/enable images/videos.
                * Slideshow "Fast Speed" fixed.
                * Use WordPress native jQuery.

	1.1 (2011-06-27)

		* Compatibility bug fixed.
		* Sorting bug fixed.
	
	1.0 (2011-06-05)
	
		* Initial release.
		
Installation: Upload the folder doptg from the zip file to "wp-content/plugins/" and activate the plugin in your admin panel or upload doptg.zip in the "Add new" section.
*/

    include_once "views/lang.php";
    include_once "views/templates.php";
    include_once "doptg-update.php";
    include_once "doptg-frontend.php";
    include_once "doptg-backend.php";
    include_once "doptg-widget.php";
    
// Paths
    
    if (!defined('DOPTG_Plugin_URL')){
        define('DOPTG_Plugin_URL', plugin_dir_url(__FILE__));
    }
    if (!defined('DOPTG_Plugin_AbsPath')){
        define('DOPTG_Plugin_AbsPath', str_replace('\\', '/', plugin_dir_path(__FILE__)));
    }

    if (is_admin()){// If admin is loged in admin init administration panel.
        if (class_exists("DOPThumbnailGalleryBackEnd")){
            $DOPTG_pluginSeries = new DOPThumbnailGalleryBackEnd();
        }

        if (!function_exists("DOPThumbnailGalleryBackEnd_ap")){// Initialize the admin panel.
            function DOPThumbnailGalleryBackEnd_ap(){
                global $DOPTG_pluginSeries;

                if (!isset($DOPTG_pluginSeries)){
                    return;
                }
                if (function_exists('add_options_page')){
                    add_menu_page(DOPTG_TITLE, DOPTG_TITLE, 'edit_posts', 'doptg', array(&$DOPTG_pluginSeries, 'printAdminPage'), 'div');
                }
            }
        }

        if (isset($DOPTG_pluginSeries)){// Init AJAX functions.
            add_action('admin_menu', 'DOPThumbnailGalleryBackEnd_ap');
            add_action('wp_ajax_doptg_show_galleries', array(&$DOPTG_pluginSeries, 'showGalleries'));
            add_action('wp_ajax_doptg_add_gallery', array(&$DOPTG_pluginSeries, 'addGallery'));
            add_action('wp_ajax_doptg_delete_gallery', array(&$DOPTG_pluginSeries, 'deleteGallery'));
            add_action('wp_ajax_doptg_show_gallery_settings', array(&$DOPTG_pluginSeries, 'showGallerySettings'));
            add_action('wp_ajax_doptg_edit_gallery_settings', array(&$DOPTG_pluginSeries, 'editGallerySettings'));
            add_action('wp_ajax_doptg_update_settings_image', array(&$DOPTG_pluginSeries, 'updateSettingsImage'));
            add_action('wp_ajax_doptg_show_images', array(&$DOPTG_pluginSeries, 'showImages'));
            add_action('wp_ajax_doptg_add_image_wp', array(&$DOPTG_pluginSeries, 'addImageWP'));
            add_action('wp_ajax_doptg_add_image_ftp', array(&$DOPTG_pluginSeries, 'addImageFTP'));
            add_action('wp_ajax_doptg_add_image', array(&$DOPTG_pluginSeries, 'addImage'));
            add_action('wp_ajax_doptg_edit_images', array(&$DOPTG_pluginSeries, 'editImages'));
            add_action('wp_ajax_doptg_sort_images', array(&$DOPTG_pluginSeries, 'sortImages'));
            add_action('wp_ajax_doptg_show_image', array(&$DOPTG_pluginSeries, 'showImage'));
            add_action('wp_ajax_doptg_edit_image', array(&$DOPTG_pluginSeries, 'editImage'));
            add_action('wp_ajax_doptg_delete_image', array(&$DOPTG_pluginSeries, 'deleteImage'));
        }
    }
    else{// If you view the WordPress website init the gallery.
        if (class_exists("DOPThumbnailGalleryFrontEnd")){
            $DOPTG_pluginSeries = new DOPThumbnailGalleryFrontEnd();
        }

        if (isset($DOPTG_pluginSeries)){// Init AJAX functions.
            add_action('wp_ajax_doptg_get_gallery_data', array(&$DOPTG_pluginSeries, 'getGalleryData'));
        }
    }
                
    add_action('widgets_init', create_function('', 'return register_widget("DOPThumbnailGalleryWidget");'));

// Uninstall

    if (!function_exists("DOPThumbnailGalleryUninstall")){
        function DOPThumbnailGalleryUninstall() {
            global $wpdb;

            $tables = $wpdb->get_results('SHOW TABLES');

            foreach ($tables as $table){
                $table_name = $table->Tables_in_studios_wp;

                if (strrpos($table_name, 'doptg_settings') !== false ||
                    strrpos($table_name, 'doptg_galleries') !== false ||
                    strrpos($table_name, 'doptg_images') !== false){
                    $wpdb->query("DROP TABLE IF EXISTS $table_name");
                }
            }
            
            delete_option('DOPTG_db_version');
        }
        
        register_uninstall_hook(__FILE__, 'DOPThumbnailGalleryUninstall');
    }
    
?>