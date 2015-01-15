<?php

/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : doptg-backend.php
* File Version            : 1.9
* Created / Last Modified : 01 October 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Back End Class.
*/

    if (!class_exists("DOPThumbnailGalleryBackEnd")){
        class DOPThumbnailGalleryBackEnd{
            private $DOPTG_AddEditGalleries;
            private $DOPTG_db_version = 2.4;

            function DOPThumbnailGalleryBackEnd(){// Constructor.
                if (is_admin()){
                    add_action('admin_enqueue_scripts', array(&$this, 'addWPAdminStyles'));
                    
                    if ($this->validPage()){
                        $this->DOPTG_AddEditGalleries = new DOPTGTemplates();
                        add_action('admin_enqueue_scripts', array(&$this, 'addStyles'));
                        add_action('admin_enqueue_scripts', array(&$this, 'addScripts'));
                    }

                    $this->addDOPTGtoTinyMCE();
                    $this->init();
                }
            }
            
            function addWPAdminStyles(){
                // Register Styles.
                wp_register_style('DOPTG_WPAdminStyle', plugins_url('assets/gui/css/backend-wp-admin-style.css', __FILE__));

                // Enqueue Styles.
                wp_enqueue_style('DOPTG_WPAdminStyle');
            }
            
            function addStyles(){
                // Register Styles.
                wp_register_style('DOPTG_UploadifyStyle', plugins_url('libraries/gui/css/uploadify.css', __FILE__));
                wp_register_style('DOPTG_JcropStyle', plugins_url('libraries/gui/css/jquery.Jcrop.css', __FILE__));
                wp_register_style('DOPTG_ColorPickerStyle', plugins_url('libraries/gui/css/colorpicker.css', __FILE__));
                wp_register_style('DOPTG_AdminStyle', plugins_url('assets/gui/css/backend-style.css', __FILE__));

                // Enqueue Styles.
                wp_enqueue_style('thickbox');
                wp_enqueue_style('DOPTG_UploadifyStyle');
                wp_enqueue_style('DOPTG_JcropStyle');
                wp_enqueue_style('DOPTG_ColorPickerStyle');
                wp_enqueue_style('DOPTG_AdminStyle');
            }
            
            function addScripts(){
                // Register JavaScript.
                wp_register_script('DOPTG_SwfJS', plugins_url('libraries/js/swfobject.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_UploadifyJS', plugins_url('libraries/js/jquery.uploadify.min.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_JcropJS', plugins_url('libraries/js/jquery.Jcrop.min.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_ColorPickerJS', plugins_url('libraries/js/colorpicker.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_DOPImageLoaderJS', plugins_url('libraries/js/jquery.dop.ImageLoader.min.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_DOPTGJS', plugins_url('assets/js/doptg-backend.js', __FILE__), array('jquery'), false, true);

                // Enqueue JavaScript.
                if (!wp_script_is('jquery', 'queue')){
                    wp_enqueue_script('jquery');
                }
                
                if (!wp_script_is('jquery-ui-sortable', 'queue')){
                    wp_enqueue_script('jquery-ui-sortable');
                }
                
                wp_enqueue_script('media-upload');
                
                if (!wp_script_is('thickbox', 'queue')){
                    wp_enqueue_script('thickbox');
                }
                wp_enqueue_script('my-upload');
                wp_enqueue_script('DOPTG_SwfJS');
                wp_enqueue_script('DOPTG_UploadifyJS');
                wp_enqueue_script('DOPTG_JcropJS');
                wp_enqueue_script('DOPTG_ColorPickerJS');
                wp_enqueue_script('DOPTG_DOPImageLoaderJS');
                wp_enqueue_script('DOPTG_DOPTGJS');
            }
            
            function init(){// Admin init.
                $this->initConstants();
                $this->initTables();

                if (strrpos(strtolower(php_uname()), 'windows') === false && $this->validPage()){
                    $this->initUploadFolders();
                }
            }

            function initConstants(){// Constants init.
                global $wpdb;
                
                // Tables
                define('DOPTG_Settings_table', $wpdb->prefix.'doptg_settings');
                define('DOPTG_Galleries_table', $wpdb->prefix.'doptg_galleries');
                define('DOPTG_Images_table', $wpdb->prefix.'doptg_images');
            }

            function validPage(){// Valid Admin Page.
                if (isset($_GET['page'])){
                    if ($_GET['page'] == 'doptg' || $_GET['page'] == 'doptg-purchase-code' || $_GET['page'] == 'doptg-help'){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
            }

            function initTables(){// Tables init.
                //update_option('DOPTG_db_version', '1.0');
                $current_db_version = get_option('DOPTG_db_version');
                
                if ($this->DOPTG_db_version != $current_db_version){
                    require_once(str_replace('\\', '/', ABSPATH).'wp-admin/includes/upgrade.php');

                    $sql_settings = "CREATE TABLE " . DOPTG_Settings_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        name VARCHAR(128) DEFAULT '" . DOPTG_ADD_GALLERY_NAME . "' COLLATE utf8_unicode_ci NOT NULL,
                                        gallery_id int DEFAULT 0 NOT NULL,
                                        data_parse_method VARCHAR(4) DEFAULT 'ajax' COLLATE utf8_unicode_ci NOT NULL,
                                        width int DEFAULT 900 NOT NULL,
                                        height int DEFAULT 600 NOT NULL,
                                        bg_color VARCHAR(6) DEFAULT 'f1f1f1' COLLATE utf8_unicode_ci NOT NULL,
                                        bg_alpha int DEFAULT 100 NOT NULL,
                                        images_order VARCHAR(6) DEFAULT 'normal' COLLATE utf8_unicode_ci NOT NULL,
                                        responsive_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_position VARCHAR(6) DEFAULT 'bottom' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_over_image VARCHAR(6) DEFAULT 'false' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_bg_color VARCHAR(6) DEFAULT 'f1f1f1' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_bg_alpha int DEFAULT 100 NOT NULL,
                                        thumbnails_spacing int DEFAULT 5 NOT NULL,
                                        thumbnails_padding_top int DEFAULT 0 NOT NULL,
                                        thumbnails_padding_right int DEFAULT 5 NOT NULL,
                                        thumbnails_padding_bottom int DEFAULT 5 NOT NULL,
                                        thumbnails_padding_left int DEFAULT 5 NOT NULL,
                                        thumbnails_navigation VARCHAR(6) DEFAULT 'mouse' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_navigation_prev VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailsPrev.png' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_navigation_prev_hover VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailsPrevHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_navigation_next VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailsNext.png' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_navigation_next_hover VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailsNextHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_loader VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailLoader.gif' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_width int DEFAULT 60 NOT NULL,
                                        thumbnail_height int DEFAULT 60 NOT NULL,
                                        thumbnail_width_mobile int DEFAULT 60 NOT NULL,
                                        thumbnail_height_mobile int DEFAULT 60 NOT NULL,
                                        thumbnail_alpha int DEFAULT 50 NOT NULL,
                                        thumbnail_alpha_hover int DEFAULT 100 NOT NULL,
                                        thumbnail_alpha_selected int DEFAULT 100 NOT NULL,
                                        thumbnail_bg_color VARCHAR(6) DEFAULT 'f1f1f1' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_bg_color_hover VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_bg_color_selected VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_border_size int DEFAULT 2 NOT NULL,
                                        thumbnail_border_color VARCHAR(6) DEFAULT 'f1f1f1' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_border_color_hover VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_border_color_selected VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_padding_top int DEFAULT 0 NOT NULL,
                                        thumbnail_padding_right int DEFAULT 0 NOT NULL,
                                        thumbnail_padding_bottom int DEFAULT 0 NOT NULL,
                                        thumbnail_padding_left int DEFAULT 0 NOT NULL,
                                        image_loader VARCHAR(128) DEFAULT 'assets/gui/images/ImageLoader.gif' COLLATE utf8_unicode_ci NOT NULL,
                                        image_bg_color VARCHAR(6) DEFAULT 'afafaf' COLLATE utf8_unicode_ci NOT NULL,
                                        image_bg_alpha int DEFAULT 100 NOT NULL,
                                        image_display_type VARCHAR(6) DEFAULT 'fit' COLLATE utf8_unicode_ci NOT NULL,
                                        image_display_time int DEFAULT 1000 NOT NULL,
                                        image_margin_top int DEFAULT 20 NOT NULL,
                                        image_margin_right int DEFAULT 20 NOT NULL,
                                        image_margin_bottom int DEFAULT 20 NOT NULL,
                                        image_margin_left int DEFAULT 20 NOT NULL,
                                        image_padding_top int DEFAULT 5 NOT NULL,
                                        image_padding_right int DEFAULT 5 NOT NULL,
                                        image_padding_bottom int DEFAULT 5 NOT NULL,
                                        image_padding_left int DEFAULT 5 NOT NULL,
                                        navigation_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_over_image VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_prev VARCHAR(128) DEFAULT 'assets/gui/images/Prev.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_prev_hover VARCHAR(128) DEFAULT 'assets/gui/images/PrevHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_next VARCHAR(128) DEFAULT 'assets/gui/images/Next.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_next_hover VARCHAR(128) DEFAULT 'assets/gui/images/NextHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_lightbox VARCHAR(128) DEFAULT 'assets/gui/images/Lightbox.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_lightbox_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        navigation_touch_device_swipe_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_width int DEFAULT 900 NOT NULL,
                                        caption_height int DEFAULT 75 NOT NULL,
                                        caption_title_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_text_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_bg_color VARCHAR(6) DEFAULT 'ffffff' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_bg_alpha int DEFAULT 50 NOT NULL,
                                        caption_position VARCHAR(12) DEFAULT 'bottom' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_over_image VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_scroll_scrub_color VARCHAR(6) DEFAULT '777777' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_scroll_bg_color VARCHAR(6) DEFAULT 'e0e0e0' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_margin_top int DEFAULT 0 NOT NULL,
                                        caption_margin_right int DEFAULT 0 NOT NULL,
                                        caption_margin_bottom int DEFAULT 0 NOT NULL,
                                        caption_margin_left int DEFAULT 0 NOT NULL,
                                        caption_padding_top int DEFAULT 10 NOT NULL,
                                        caption_padding_right int DEFAULT 10 NOT NULL,
                                        caption_padding_bottom int DEFAULT 10 NOT NULL,
                                        caption_padding_left int DEFAULT 10 NOT NULL,
                                        lightbox_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_window_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_window_alpha int DEFAULT 80 NOT NULL,
                                        lightbox_loader VARCHAR(128) DEFAULT 'assets/gui/images/LightboxLoader.gif' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_bg_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_bg_alpha int DEFAULT 100 NOT NULL,
                                        lightbox_margin_top int DEFAULT 70 NOT NULL,
                                        lightbox_margin_right int DEFAULT 70 NOT NULL,
                                        lightbox_margin_bottom int DEFAULT 70 NOT NULL,
                                        lightbox_margin_left int DEFAULT 70 NOT NULL,
                                        lightbox_padding_top int DEFAULT 10 NOT NULL,
                                        lightbox_padding_right int DEFAULT 10 NOT NULL,
                                        lightbox_padding_bottom int DEFAULT 10 NOT NULL,
                                        lightbox_padding_left int DEFAULT 10 NOT NULL,
                                        lightbox_navigation_prev VARCHAR(128) DEFAULT 'assets/gui/images/LightboxPrev.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_prev_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxPrevHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_next VARCHAR(128) DEFAULT 'assets/gui/images/LightboxNext.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_next_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxNextHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_close VARCHAR(128) DEFAULT 'assets/gui/images/LightboxClose.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_close_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxCloseHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_info_bg_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_info_text_color VARCHAR(6) DEFAULT 'dddddd' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_touch_device_swipe_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        social_share_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        social_share VARCHAR(128) DEFAULT 'assets/gui/images/SocialShare.png' COLLATE utf8_unicode_ci NOT NULL,
                                        social_share_lightbox VARCHAR(128) DEFAULT 'assets/gui/images/SocialShareLightbox.png' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_enabled VARCHAR(6) DEFAULT 'false' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_bg_color VARCHAR(6) DEFAULT 'ffffff' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_stroke_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_text_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow VARCHAR(6) DEFAULT 'false' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_time int DEFAULT 5000 NOT NULL,
                                        slideshow_autostart VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_loop VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_play VARCHAR(128) DEFAULT 'assets/gui/images/Play.png' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_play_hover VARCHAR(128) DEFAULT 'assets/gui/images/PlayHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_pause VARCHAR(128) DEFAULT 'assets/gui/images/Pause.png' COLLATE utf8_unicode_ci NOT NULL,
                                        slideshow_pause_hover VARCHAR(128) DEFAULT 'assets/gui/images/PauseHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        auto_hide VARCHAR(6) DEFAULT 'false' COLLATE utf8_unicode_ci NOT NULL,
                                        auto_hide_time int DEFAULT 2000 NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    $sql_galleries = "CREATE TABLE " . DOPTG_Galleries_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        name VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    $sql_images = "CREATE TABLE " . DOPTG_Images_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        gallery_id int DEFAULT 0 NOT NULL,
                                        name VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        title VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        caption TEXT COLLATE utf8_unicode_ci NOT NULL,
                                        media TEXT COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_media TEXT COLLATE utf8_unicode_ci NOT NULL,
                                        enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        position int DEFAULT 0 NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    dbDelta($sql_settings);
                    dbDelta($sql_galleries);
                    dbDelta($sql_images);

                    if ($current_db_version == ''){
                        add_option('DOPTG_db_version', $this->DOPTG_db_version);
                    }
                    else{
                        update_option('DOPTG_db_version', $this->DOPTG_db_version);
                    }
                    
                    $this->initTablesData();
                }
            }
            
            function initTablesData(){
                global $wpdb;

                $settings = $wpdb->get_results('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id=0');
                
                if ($wpdb->num_rows == 0){
                    dbDelta($wpdb->insert(DOPTG_Settings_table, array('name' => DOPTG_DEFAULT_SETTINGS,
                                                                      'gallery_id' => 0)));
                    
                    dbDelta($wpdb->insert(DOPTG_Settings_table, array('name' => 'Example 1',
                                                                      'gallery_id' => 0)));
                    
                    dbDelta($wpdb->insert(DOPTG_Settings_table, array('name' => 'Example 2',
                                                                      'gallery_id' => 0,
                                                                      'width' => 900,
                                                                      'height' => 600,
                                                                      'bg_color' => 'ffffff',
                                                                      'bg_alpha' => 100,
                                                                      'images_order' => 'normal',
                                                                      'responsive_enabled' => 'true',
                                                                      'thumbnails_position' => 'left',
                                                                      'thumbnails_over_image' => 'false',
                                                                      'thumbnails_bg_color' => 'f1f1f1',
                                                                      'thumbnails_bg_alpha' => 100,
                                                                      'thumbnails_spacing' => 10,
                                                                      'thumbnails_padding_top' => 10,
                                                                      'thumbnails_padding_right' => 10,
                                                                      'thumbnails_padding_bottom' => 10,
                                                                      'thumbnails_padding_left' => 10,
                                                                      'thumbnails_navigation' => 'mouse',
                                                                      'thumbnails_navigation_prev' => 'assets/gui/images/ThumbnailsPrev.png',
                                                                      'thumbnails_navigation_prev_hover' => 'assets/gui/images/ThumbnailsPrevHover.png',
                                                                      'thumbnails_navigation_next' => 'assets/gui/images/ThumbnailsNext.png',
                                                                      'thumbnails_navigation_next_hover' => 'assets/gui/images/ThumbnailsNextHover.png',
                                                                      'thumbnail_loader' => 'assets/gui/images/ThumbnailLoader.gif',
                                                                      'thumbnail_width' => 150,
                                                                      'thumbnail_height' => 100,
                                                                      'thumbnail_width_mobile' => 75,
                                                                      'thumbnail_height_mobile' => 50,
                                                                      'thumbnail_alpha' => 100,
                                                                      'thumbnail_alpha_hover' => 100,
                                                                      'thumbnail_alpha_selected' => 100,
                                                                      'thumbnail_bg_color' => 'f1f1f1',
                                                                      'thumbnail_bg_color_hover' => 'f1f1f1',
                                                                      'thumbnail_bg_color_selected' => 'f1f1f1',
                                                                      'thumbnail_border_size' => 2,
                                                                      'thumbnail_border_color' => 'f1f1f1',
                                                                      'thumbnail_border_color_hover' => '000000',
                                                                      'thumbnail_border_color_selected' => '000000',
                                                                      'thumbnail_padding_top' => 0,
                                                                      'thumbnail_padding_right' => 0,
                                                                      'thumbnail_padding_bottom' => 0,
                                                                      'thumbnail_padding_left' => 0,
                                                                      'image_loader' => 'assets/gui/images/ImageLoader.gif',
                                                                      'image_bg_color' => 'f1f1f1',
                                                                      'image_bg_alpha' => 100,
                                                                      'image_display_type' => 'fit',
                                                                      'image_display_time' => 1000,
                                                                      'image_margin_top' => 0,
                                                                      'image_margin_right' => 0,
                                                                      'image_margin_bottom' => 0,
                                                                      'image_margin_left' => 50,
                                                                      'image_padding_top' => 5,
                                                                      'image_padding_right' => 5,
                                                                      'image_padding_bottom' => 5,
                                                                      'image_padding_left' => 5,
                                                                      'navigation_enabled' => 'true',
                                                                      'navigation_over_image' => 'true',
                                                                      'navigation_prev' => 'assets/gui/images/Prev.png',
                                                                      'navigation_prev_hover' => 'assets/gui/images/PrevHover.png',
                                                                      'navigation_next' => 'assets/gui/images/Next.png',
                                                                      'navigation_next_hover' => 'assets/gui/images/NextHover.png',
                                                                      'navigation_lightbox' => 'assets/gui/images/Lightbox.png',
                                                                      'navigation_lightbox_hover' => 'assets/gui/images/LightboxHover.png',
                                                                      'navigation_touch_device_swipe_enabled' => 'true',
                                                                      'caption_width' => 300,
                                                                      'caption_height' => 150,
                                                                      'caption_title_color' => '000000',
                                                                      'caption_text_color' => '000000',
                                                                      'caption_bg_color' => 'ffffff',
                                                                      'caption_bg_alpha' => 50,
                                                                      'caption_position' => 'top-left',
                                                                      'caption_over_image' => 'true',
                                                                      'caption_scroll_scrub_color' => '777777',
                                                                      'caption_scroll_bg_color' => 'e0e0e0',
                                                                      'caption_margin_top' => 10,
                                                                      'caption_margin_right' => 10,
                                                                      'caption_margin_bottom' => 10,
                                                                      'caption_margin_left' => 10,
                                                                      'caption_padding_top' => 10,
                                                                      'caption_padding_right' => 10,
                                                                      'caption_padding_bottom' => 10,
                                                                      'caption_padding_left' => 10,
                                                                      'lightbox_enabled' => 'true',
                                                                      'lightbox_window_color' => '000000',
                                                                      'lightbox_window_alpha' => 80,
                                                                      'lightbox_loader' => 'assets/gui/images/LightboxLoader.gif',
                                                                      'lightbox_bg_color' => '000000',
                                                                      'lightbox_bg_alpha' => 100,
                                                                      'lightbox_margin_top' => 70,
                                                                      'lightbox_margin_right' => 70,
                                                                      'lightbox_margin_bottom' => 70,
                                                                      'lightbox_margin_left' => 70,
                                                                      'lightbox_padding_top' => 10,
                                                                      'lightbox_padding_right' => 10,
                                                                      'lightbox_padding_bottom' => 10,
                                                                      'lightbox_padding_left' => 10,
                                                                      'lightbox_navigation_prev' => 'assets/gui/images/LightboxPrev.png',
                                                                      'lightbox_navigation_prev_hover' => 'assets/gui/images/LightboxPrevHover.png',
                                                                      'lightbox_navigation_next' => 'assets/gui/images/LightboxNext.png',
                                                                      'lightbox_navigation_next_hover' => 'assets/gui/images/LightboxNextHover.png',
                                                                      'lightbox_navigation_close' => 'assets/gui/images/LightboxClose.png',
                                                                      'lightbox_navigation_close_hover' => 'assets/gui/images/LightboxCloseHover.png',
                                                                      'lightbox_navigation_info_bg_color' => '000000',
                                                                      'lightbox_navigation_info_text_color' => 'dddddd',
                                                                      'lightbox_navigation_touch_device_swipe_enabled' => 'true',
                                                                      'social_share_enabled' => 'true',
                                                                      'social_share' => 'assets/gui/images/SocialShare.png',
                                                                      'social_share_lightbox' => 'assets/gui/images/SocialShareLightbox.png',
                                                                      'tooltip_enabled' => 'true',
                                                                      'tooltip_bg_color' => 'ffffff',
                                                                      'tooltip_stroke_color' => '000000',
                                                                      'tooltip_text_color' => '000000',
                                                                      'slideshow' => 'false',
                                                                      'slideshow_time' => 5000,
                                                                      'slideshow_autostart' => 'true',
                                                                      'slideshow_loop' => 'true',
                                                                      'slideshow_play' => 'assets/gui/images/Play.png',
                                                                      'slideshow_play_hover' => 'assets/gui/images/PlayHover.png',
                                                                      'slideshow_pause' => 'assets/gui/images/Pause.png',
                                                                      'slideshow_pause_hover' => 'assets/gui/images/PauseHover.png',
                                                                      'auto_hide' => 'false',
                                                                      'auto_hide_time' => 2000)));
                    
                    dbDelta($wpdb->insert(DOPTG_Settings_table, array('name' => 'Example 3',
                                                                      'gallery_id' => 0,
                                                                      'width' => 900,
                                                                      'height' => 600,
                                                                      'bg_color' => 'ffffff',
                                                                      'bg_alpha' => 100,
                                                                      'images_order' => 'normal',
                                                                      'responsive_enabled' => 'true',
                                                                      'thumbnails_position' => 'top',
                                                                      'thumbnails_over_image' => 'true',
                                                                      'thumbnails_bg_color' => '000000',
                                                                      'thumbnails_bg_alpha' => 50,
                                                                      'thumbnails_spacing' => 10,
                                                                      'thumbnails_padding_top' => 10,
                                                                      'thumbnails_padding_right' => 10,
                                                                      'thumbnails_padding_bottom' => 10,
                                                                      'thumbnails_padding_left' => 10,
                                                                      'thumbnails_navigation' => 'mouse',
                                                                      'thumbnails_navigation_prev' => 'assets/gui/images/ThumbnailsPrev.png',
                                                                      'thumbnails_navigation_prev_hover' => 'assets/gui/images/ThumbnailsPrevHover.png',
                                                                      'thumbnails_navigation_next' => 'assets/gui/images/ThumbnailsNext.png',
                                                                      'thumbnails_navigation_next_hover' => 'assets/gui/images/ThumbnailsNextHover.png',
                                                                      'thumbnail_loader' => 'assets/gui/images/ThumbnailLoader.gif',
                                                                      'thumbnail_width' => 100,
                                                                      'thumbnail_height' => 100,
                                                                      'thumbnail_width_mobile' => 50,
                                                                      'thumbnail_height_mobile' => 50,
                                                                      'thumbnail_alpha' => 50,
                                                                      'thumbnail_alpha_hover' => 100,
                                                                      'thumbnail_alpha_selected' => 100,
                                                                      'thumbnail_bg_color' => '000000',
                                                                      'thumbnail_bg_color_hover' => 'ffffff',
                                                                      'thumbnail_bg_color_selected' => 'ffffff',
                                                                      'thumbnail_border_size' => 2,
                                                                      'thumbnail_border_color' => '000000',
                                                                      'thumbnail_border_color_hover' => 'ffffff',
                                                                      'thumbnail_border_color_selected' => 'ffffff',
                                                                      'thumbnail_padding_top' => 0,
                                                                      'thumbnail_padding_right' => 0,
                                                                      'thumbnail_padding_bottom' => 0,
                                                                      'thumbnail_padding_left' => 0,
                                                                      'image_loader' => 'assets/gui/images/ImageLoader.gif',
                                                                      'image_bg_color' => 'ffffff',
                                                                      'image_bg_alpha' => 100,
                                                                      'image_display_type' => 'full',
                                                                      'image_display_time' => 1000,
                                                                      'image_margin_top' => 0,
                                                                      'image_margin_right' => 0,
                                                                      'image_margin_bottom' => 0,
                                                                      'image_margin_left' => 0,
                                                                      'image_padding_top' => 5,
                                                                      'image_padding_right' => 5,
                                                                      'image_padding_bottom' => 5,
                                                                      'image_padding_left' => 5,
                                                                      'navigation_enabled' => 'true',
                                                                      'navigation_over_image' => 'true',
                                                                      'navigation_prev' => 'assets/gui/images/Prev.png',
                                                                      'navigation_prev_hover' => 'assets/gui/images/PrevHover.png',
                                                                      'navigation_next' => 'assets/gui/images/Next.png',
                                                                      'navigation_next_hover' => 'assets/gui/images/NextHover.png',
                                                                      'navigation_lightbox' => 'assets/gui/images/Lightbox.png',
                                                                      'navigation_lightbox_hover' => 'assets/gui/images/LightboxHover.png',
                                                                      'navigation_touch_device_swipe_enabled' => 'true',
                                                                      'caption_width' => 450,
                                                                      'caption_height' => 200,
                                                                      'caption_title_color' => '000000',
                                                                      'caption_text_color' => '000000',
                                                                      'caption_bg_color' => 'ffffff',
                                                                      'caption_bg_alpha' => 50,
                                                                      'caption_position' => 'bottom-left',
                                                                      'caption_over_image' => 'true',
                                                                      'caption_scroll_scrub_color' => '777777',
                                                                      'caption_scroll_bg_color' => 'e0e0e0',
                                                                      'caption_margin_top' => 0,
                                                                      'caption_margin_right' => 10,
                                                                      'caption_margin_bottom' => 10,
                                                                      'caption_margin_left' => 10,
                                                                      'caption_padding_top' => 10,
                                                                      'caption_padding_right' => 10,
                                                                      'caption_padding_bottom' => 10,
                                                                      'caption_padding_left' => 10,
                                                                      'lightbox_enabled' => 'false',
                                                                      'lightbox_window_color' => '000000',
                                                                      'lightbox_window_alpha' => 80,
                                                                      'lightbox_loader' => 'assets/gui/images/LightboxLoader.gif',
                                                                      'lightbox_bg_color' => '000000',
                                                                      'lightbox_bg_alpha' => 100,
                                                                      'lightbox_margin_top' => 70,
                                                                      'lightbox_margin_right' => 70,
                                                                      'lightbox_margin_bottom' => 70,
                                                                      'lightbox_margin_left' => 70,
                                                                      'lightbox_padding_top' => 10,
                                                                      'lightbox_padding_right' => 10,
                                                                      'lightbox_padding_bottom' => 10,
                                                                      'lightbox_padding_left' => 10,
                                                                      'lightbox_navigation_prev' => 'assets/gui/images/LightboxPrev.png',
                                                                      'lightbox_navigation_prev_hover' => 'assets/gui/images/LightboxPrevHover.png',
                                                                      'lightbox_navigation_next' => 'assets/gui/images/LightboxNext.png',
                                                                      'lightbox_navigation_next_hover' => 'assets/gui/images/LightboxNextHover.png',
                                                                      'lightbox_navigation_close' => 'assets/gui/images/LightboxClose.png',
                                                                      'lightbox_navigation_close_hover' => 'assets/gui/images/LightboxCloseHover.png',
                                                                      'lightbox_navigation_info_bg_color' => '000000',
                                                                      'lightbox_navigation_info_text_color' => 'dddddd',
                                                                      'lightbox_navigation_touch_device_swipe_enabled' => 'true',
                                                                      'social_share_enabled' => 'false',
                                                                      'social_share' => 'assets/gui/images/SocialShare.png',
                                                                      'social_share_lightbox' => 'assets/gui/images/SocialShareLightbox.png',
                                                                      'tooltip_enabled' => 'false',
                                                                      'tooltip_bg_color' => 'ffffff',
                                                                      'tooltip_stroke_color' => '000000',
                                                                      'tooltip_text_color' => '000000',
                                                                      'slideshow' => 'true',
                                                                      'slideshow_time' => 5000,
                                                                      'slideshow_autostart' => 'true',
                                                                      'slideshow_loop' => 'true',
                                                                      'slideshow_play' => 'assets/gui/images/Play.png',
                                                                      'slideshow_play_hover' => 'assets/gui/images/PlayHover.png',
                                                                      'slideshow_pause' => 'assets/gui/images/Pause.png',
                                                                      'slideshow_pause_hover' => 'assets/gui/images/PauseHover.png',
                                                                      'auto_hide' => 'true',
                                                                      'auto_hide_time' => 2000)));
                    
                    dbDelta($wpdb->insert(DOPTG_Settings_table, array('name' => 'Example 4',
                                                                      'gallery_id' => 0,
                                                                      'width' => 900,
                                                                      'height' => 600,
                                                                      'bg_color' => 'f1f1f1',
                                                                      'bg_alpha' => 100,
                                                                      'images_order' => 'random',
                                                                      'responsive_enabled' => 'true',
                                                                      'thumbnails_position' => 'bottom',
                                                                      'thumbnails_over_image' => 'false',
                                                                      'thumbnails_bg_color' => 'f1f1f1',
                                                                      'thumbnails_bg_alpha' => 100,
                                                                      'thumbnails_spacing' => 5,
                                                                      'thumbnails_padding_top' => 0,
                                                                      'thumbnails_padding_right' => 10,
                                                                      'thumbnails_padding_bottom' => 10,
                                                                      'thumbnails_padding_left' => 10,
                                                                      'thumbnails_navigation' => 'arrows',
                                                                      'thumbnails_navigation_prev' => 'assets/gui/images/ThumbnailsPrev.png',
                                                                      'thumbnails_navigation_prev_hover' => 'assets/gui/images/ThumbnailsPrevHover.png',
                                                                      'thumbnails_navigation_next' => 'assets/gui/images/ThumbnailsNext.png',
                                                                      'thumbnails_navigation_next_hover' => 'assets/gui/images/ThumbnailsNextHover.png',
                                                                      'thumbnail_loader' => 'assets/gui/images/ThumbnailLoader.gif',
                                                                      'thumbnail_width' => 60,
                                                                      'thumbnail_height' => 60,
                                                                      'thumbnail_width_mobile' => 60,
                                                                      'thumbnail_height_mobile' => 60,
                                                                      'thumbnail_alpha' => 50,
                                                                      'thumbnail_alpha_hover' => 100,
                                                                      'thumbnail_alpha_selected' => 100,
                                                                      'thumbnail_bg_color' => 'f1f1f1',
                                                                      'thumbnail_bg_color_hover' => '000000',
                                                                      'thumbnail_bg_color_selected' => '000000',
                                                                      'thumbnail_border_size' => 2,
                                                                      'thumbnail_border_color' => 'f1f1f1',
                                                                      'thumbnail_border_color_hover' => '000000',
                                                                      'thumbnail_border_color_selected' => '000000',
                                                                      'thumbnail_padding_top' => 0,
                                                                      'thumbnail_padding_right' => 0,
                                                                      'thumbnail_padding_bottom' => 0,
                                                                      'thumbnail_padding_left' => 0,
                                                                      'image_loader' => 'assets/gui/images/ImageLoader.gif',
                                                                      'image_bg_color' => 'afafaf',
                                                                      'image_bg_alpha' => 100,
                                                                      'image_display_type' => 'fit',
                                                                      'image_display_time' => 1000,
                                                                      'image_margin_top' => 20,
                                                                      'image_margin_right' => 20,
                                                                      'image_margin_bottom' => 20,
                                                                      'image_margin_left' => 20,
                                                                      'image_padding_top' => 5,
                                                                      'image_padding_right' => 5,
                                                                      'image_padding_bottom' => 5,
                                                                      'image_padding_left' => 5,
                                                                      'navigation_enabled' => 'true',
                                                                      'navigation_over_image' => 'true',
                                                                      'navigation_prev' => 'assets/gui/images/Prev.png',
                                                                      'navigation_prev_hover' => 'assets/gui/images/PrevHover.png',
                                                                      'navigation_next' => 'assets/gui/images/Next.png',
                                                                      'navigation_next_hover' => 'assets/gui/images/NextHover.png',
                                                                      'navigation_lightbox' => 'assets/gui/images/Lightbox.png',
                                                                      'navigation_lightbox_hover' => 'assets/gui/images/LightboxHover.png',
                                                                      'navigation_touch_device_swipe_enabled' => 'true',
                                                                      'caption_width' => 900,
                                                                      'caption_height' => 75,
                                                                      'caption_title_color' => '000000',
                                                                      'caption_text_color' => '000000',
                                                                      'caption_bg_color' => 'ffffff',
                                                                      'caption_bg_alpha' => 50,
                                                                      'caption_position' => 'bottom',
                                                                      'caption_over_image' => 'true',
                                                                      'caption_scroll_scrub_color' => '777777',
                                                                      'caption_scroll_bg_color' => 'e0e0e0',
                                                                      'caption_margin_top' => 0,
                                                                      'caption_margin_right' => 0,
                                                                      'caption_margin_bottom' => 0,
                                                                      'caption_margin_left' => 0,
                                                                      'caption_padding_top' => 10,
                                                                      'caption_padding_right' => 10,
                                                                      'caption_padding_bottom' => 10,
                                                                      'caption_padding_left' => 10,
                                                                      'lightbox_enabled' => 'true',
                                                                      'lightbox_window_color' => '000000',
                                                                      'lightbox_window_alpha' => 80,
                                                                      'lightbox_loader' => 'assets/gui/images/LightboxLoader.gif',
                                                                      'lightbox_bg_color' => '000000',
                                                                      'lightbox_bg_alpha' => 100,
                                                                      'lightbox_margin_top' => 70,
                                                                      'lightbox_margin_right' => 70,
                                                                      'lightbox_margin_bottom' => 70,
                                                                      'lightbox_margin_left' => 70,
                                                                      'lightbox_padding_top' => 10,
                                                                      'lightbox_padding_right' => 10,
                                                                      'lightbox_padding_bottom' => 10,
                                                                      'lightbox_padding_left' => 10,
                                                                      'lightbox_navigation_prev' => 'assets/gui/images/LightboxPrev.png',
                                                                      'lightbox_navigation_prev_hover' => 'assets/gui/images/LightboxPrevHover.png',
                                                                      'lightbox_navigation_next' => 'assets/gui/images/LightboxNext.png',
                                                                      'lightbox_navigation_next_hover' => 'assets/gui/images/LightboxNextHover.png',
                                                                      'lightbox_navigation_close' => 'assets/gui/images/LightboxClose.png',
                                                                      'lightbox_navigation_close_hover' => 'assets/gui/images/LightboxCloseHover.png',
                                                                      'lightbox_navigation_info_bg_color' => '000000',
                                                                      'lightbox_navigation_info_text_color' => 'dddddd',
                                                                      'lightbox_navigation_touch_device_swipe_enabled' => 'true',
                                                                      'social_share_enabled' => 'true',
                                                                      'social_share' => 'assets/gui/images/SocialShare.png',
                                                                      'social_share_lightbox' => 'assets/gui/images/SocialShareLightbox.png',
                                                                      'tooltip_enabled' => 'false',
                                                                      'tooltip_bg_color' => 'ffffff',
                                                                      'tooltip_stroke_color' => '000000',
                                                                      'tooltip_text_color' => '000000',
                                                                      'slideshow' => 'false',
                                                                      'slideshow_time' => 5000,
                                                                      'slideshow_autostart' => 'true',
                                                                      'slideshow_loop' => 'true',
                                                                      'slideshow_play' => 'assets/gui/images/Play.png',
                                                                      'slideshow_play_hover' => 'assets/gui/images/PlayHover.png',
                                                                      'slideshow_pause' => 'assets/gui/images/Pause.png',
                                                                      'slideshow_pause_hover' => 'assets/gui/images/PauseHover.png',
                                                                      'auto_hide' => 'false',
                                                                      'auto_hide_time' => 2000)));
                }
            }

            function initUploadFolders(){
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/image-loader');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-loader');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-close');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-close-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-next');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-next-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-prev');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/lightbox-navigation-prev-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-lightbox');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-lightbox-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-next');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-next-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-prev');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/navigation-prev-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/slideshow-pause');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/slideshow-pause-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/slideshow-play');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/slideshow-play-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/social-share');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/social-share-lightbox');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/thumb-loader');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/thumbnails-navigation-next');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/thumbnails-navigation-next-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/thumbnails-navigation-prev');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/settings/thumbnails-navigation-prev-hover');
                $this->verifyUploadFolder('../wp-content/plugins/doptg/uploads/thumbs');
            }
            
            function verifyUploadFolder($folder){
                if (!file_exists($folder)){
                    mkdir($folder, 0777);                
                }
                else{
                    if (substr(decoct(fileperms($folder)), 1) != '0777'){
                        if (@chmod($folder, 0777)){
                            // File permissions changed.
                        }
                        else{
                            // File permissions didn't changed.
                        }
                    }
                }
            }
            
            function printAdminPage(){// Prints out the admin page.
                $this->DOPTG_AddEditGalleries->galleriesList();
            }
            
// Galleries
            function showGalleries(){// Show Galleries List.
                global $wpdb;
                
                $galleriesHTML = array();
                array_push($galleriesHTML, '<ul>');

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPTG_Galleries_table.' ORDER BY id DESC');
                
                if ($wpdb->num_rows != 0){
                    foreach ($galleries as $gallery){
                        array_push($galleriesHTML, '<li class="item" id="DOPTG-ID-'.$gallery->id.'"><span class="id">ID '.$gallery->id.':</span> <span class="name">'.$this->shortGalleryName($gallery->name, 25).'</span></li>');
                    }
                }
                else{
                    array_push($galleriesHTML, '<li class="no-data">'.DOPTG_NO_GALLERIES.'</li>');
                }
                array_push($galleriesHTML, '</ul>');
                echo implode('', $galleriesHTML);
                
            	die();                
            }
        
            function addGallery(){// Add Gallery.
                global $wpdb;

                $wpdb->insert(DOPTG_Galleries_table, array('name' => DOPTG_ADD_GALLERY_NAME));
                $wpdb->insert(DOPTG_Settings_table, array('gallery_id' => $wpdb->insert_id));
                $this->showGalleries();

            	die();
            }

            function deleteGallery(){// Delete Gallery.
                global $wpdb;

                $wpdb->query('DELETE FROM '.DOPTG_Galleries_table.' WHERE id="'.$_POST['id'].'"');
                $wpdb->query('DELETE FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$_POST['id'].'"');
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$_POST['id'].'" ORDER BY position');
                foreach ($images as $image) {
                    $wpdb->query('DELETE FROM '.DOPTG_Images_table.' WHERE id="'.$image->id.'"');
                    unlink(DOPTG_Plugin_AbsPath.'uploads/'.$image->name);
                    unlink(DOPTG_Plugin_AbsPath.'uploads/thumbs/'.$image->name);
                }

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPTG_Galleries_table.' ORDER BY id');
                echo $wpdb->num_rows;

            	die();
            }            

            function shortGalleryName($name, $size){// Return a short name for the gallery.
                $new_name = '';
                $pieces = str_split($name);
               
                if (count($pieces) <= $size){
                    $new_name = $name;
                }
                else{
                    for ($i=0; $i<$size-3; $i++){
                        $new_name .= $pieces[$i];
                    }
                    $new_name .= '...';
                }

                return $new_name;
            }

// Settings
            function showGallerySettings(){// Show Gallery Settings.
                global $wpdb;
                $result = array();
                $predefined_settings_list = array();
                
                $predefined_settings = $wpdb->get_results('SELECT * FROM '.DOPTG_Settings_table.' ORDER BY id');
                
                foreach ($predefined_settings as $ps){
                    array_push($predefined_settings_list, '<option value="'.$ps->id.'">'.($ps->gallery_id != 0 ? $ps->gallery_id.'. ':'').$ps->name.'</option>');
                }
                
                $result['predefined_settings'] = implode('', $predefined_settings_list);
                
                $gallery = $wpdb->get_row('SELECT * FROM '.DOPTG_Galleries_table.' WHERE id="'.$_POST['gallery_id'].'"');
    
                if ($_POST['settings_id'] != 0){
                    $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE id="'.$_POST['settings_id'].'"');
                    
                }
                else{
                    $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$_POST['gallery_id'].'"');
                }
                
                if ($_POST['gallery_id'] != 0){
                    $result['name'] = $gallery->name;
                }
                else{
                    $result['name'] = $settings->name;
                }
                
                $result['data_parse_method'] = $settings->data_parse_method;
                $result['width'] = $settings->width;
                $result['height'] = $settings->height;
                $result['bg_color'] = $settings->bg_color;
                $result['bg_alpha'] = $settings->bg_alpha;
                $result['images_order'] = $settings->images_order;
                $result['responsive_enabled'] = $settings->responsive_enabled;
                $result['thumbnails_position'] = $settings->thumbnails_position;
                $result['thumbnails_over_image'] = $settings->thumbnails_over_image;
                $result['thumbnails_bg_color'] = $settings->thumbnails_bg_color;
                $result['thumbnails_bg_alpha'] = $settings->thumbnails_bg_alpha;
                $result['thumbnails_spacing'] = $settings->thumbnails_spacing;
                $result['thumbnails_padding_top'] = $settings->thumbnails_padding_top;
                $result['thumbnails_padding_right'] = $settings->thumbnails_padding_right;
                $result['thumbnails_padding_bottom'] = $settings->thumbnails_padding_bottom;
                $result['thumbnails_padding_left'] = $settings->thumbnails_padding_left;
                $result['thumbnails_navigation'] = $settings->thumbnails_navigation;
                $result['thumbnails_navigation_prev'] = $settings->thumbnails_navigation_prev;
                $result['thumbnails_navigation_prev_hover'] = $settings->thumbnails_navigation_prev_hover;
                $result['thumbnails_navigation_next'] = $settings->thumbnails_navigation_next;
                $result['thumbnails_navigation_next_hover'] = $settings->thumbnails_navigation_next_hover;
                $result['thumbnail_loader'] = $settings->thumbnail_loader;
                $result['thumbnail_width'] = $settings->thumbnail_width;
                $result['thumbnail_height'] = $settings->thumbnail_height;
                $result['thumbnail_width_mobile'] = $settings->thumbnail_width_mobile;
                $result['thumbnail_height_mobile'] = $settings->thumbnail_height_mobile;
                $result['thumbnail_alpha'] = $settings->thumbnail_alpha;
                $result['thumbnail_alpha_hover'] = $settings->thumbnail_alpha_hover;
                $result['thumbnail_alpha_selected'] = $settings->thumbnail_alpha_selected;
                $result['thumbnail_bg_color'] = $settings->thumbnail_bg_color;
                $result['thumbnail_bg_color_hover'] = $settings->thumbnail_bg_color_hover;
                $result['thumbnail_bg_color_selected'] = $settings->thumbnail_bg_color_selected;
                $result['thumbnail_border_size'] = $settings->thumbnail_border_size;
                $result['thumbnail_border_color'] = $settings->thumbnail_border_color;
                $result['thumbnail_border_color_hover'] = $settings->thumbnail_border_color_hover;
                $result['thumbnail_border_color_selected'] = $settings->thumbnail_border_color_selected;
                $result['thumbnail_padding_top'] = $settings->thumbnail_padding_top;
                $result['thumbnail_padding_right'] = $settings->thumbnail_padding_right;
                $result['thumbnail_padding_bottom'] = $settings->thumbnail_padding_bottom;
                $result['thumbnail_padding_left'] = $settings->thumbnail_padding_left;
                $result['image_loader'] = $settings->image_loader;
                $result['image_bg_color'] = $settings->image_bg_color;
                $result['image_bg_alpha'] = $settings->image_bg_alpha;
                $result['image_display_type'] = $settings->image_display_type;
                $result['image_display_time'] = $settings->image_display_time;
                $result['image_margin_top'] = $settings->image_margin_top;
                $result['image_margin_right'] = $settings->image_margin_right;
                $result['image_margin_bottom'] = $settings->image_margin_bottom;
                $result['image_margin_left'] = $settings->image_margin_left;
                $result['image_padding_top'] = $settings->image_padding_top;
                $result['image_padding_right'] = $settings->image_padding_right;
                $result['image_padding_bottom'] = $settings->image_padding_bottom;
                $result['image_padding_left'] = $settings->image_padding_left;
                $result['navigation_enabled'] = $settings->navigation_enabled;
                $result['navigation_over_image'] = $settings->navigation_over_image;
                $result['navigation_prev'] = $settings->navigation_prev;
                $result['navigation_prev_hover'] = $settings->navigation_prev_hover;
                $result['navigation_next'] = $settings->navigation_next;
                $result['navigation_next_hover'] = $settings->navigation_next_hover;
                $result['navigation_lightbox'] = $settings->navigation_lightbox;
                $result['navigation_lightbox_hover'] = $settings->navigation_lightbox_hover;
                $result['navigation_touch_device_swipe_enabled'] = $settings->navigation_touch_device_swipe_enabled;
                $result['caption_width'] = $settings->caption_width;
                $result['caption_height'] = $settings->caption_height;
                $result['caption_title_color'] = $settings->caption_title_color;
                $result['caption_text_color'] = $settings->caption_text_color;
                $result['caption_bg_color'] = $settings->caption_bg_color;
                $result['caption_bg_alpha'] = $settings->caption_bg_alpha;
                $result['caption_position'] = $settings->caption_position;
                $result['caption_over_image'] = $settings->caption_over_image;
                $result['caption_scroll_scrub_color'] = $settings->caption_scroll_scrub_color;
                $result['caption_scroll_bg_color'] = $settings->caption_scroll_bg_color;
                $result['caption_margin_top'] = $settings->caption_margin_top;
                $result['caption_margin_right'] = $settings->caption_margin_right;
                $result['caption_margin_bottom'] = $settings->caption_margin_bottom;
                $result['caption_margin_left'] = $settings->caption_margin_left;
                $result['caption_padding_top'] = $settings->caption_padding_top;
                $result['caption_padding_right'] = $settings->caption_padding_right;
                $result['caption_padding_bottom'] = $settings->caption_padding_bottom;
                $result['caption_padding_left'] = $settings->caption_padding_left;
                $result['lightbox_enabled'] = $settings->lightbox_enabled;
                $result['lightbox_window_color'] = $settings->lightbox_window_color;
                $result['lightbox_window_alpha'] = $settings->lightbox_window_alpha;
                $result['lightbox_loader'] = $settings->lightbox_loader;
                $result['lightbox_bg_color'] = $settings->lightbox_bg_color;
                $result['lightbox_bg_alpha'] = $settings->lightbox_bg_alpha;
                $result['lightbox_margin_top'] = $settings->lightbox_margin_top;
                $result['lightbox_margin_right'] = $settings->lightbox_margin_right;
                $result['lightbox_margin_bottom'] = $settings->lightbox_margin_bottom;
                $result['lightbox_margin_left'] = $settings->lightbox_margin_left;
                $result['lightbox_padding_top'] = $settings->lightbox_padding_top;
                $result['lightbox_padding_right'] = $settings->lightbox_padding_right;
                $result['lightbox_padding_bottom'] = $settings->lightbox_padding_bottom;
                $result['lightbox_padding_left'] = $settings->lightbox_padding_left;
                $result['lightbox_navigation_prev'] = $settings->lightbox_navigation_prev;
                $result['lightbox_navigation_prev_hover'] = $settings->lightbox_navigation_prev_hover;
                $result['lightbox_navigation_next'] = $settings->lightbox_navigation_next;
                $result['lightbox_navigation_next_hover'] = $settings->lightbox_navigation_next_hover;
                $result['lightbox_navigation_close'] = $settings->lightbox_navigation_close;
                $result['lightbox_navigation_close_hover'] = $settings->lightbox_navigation_close_hover;
                $result['lightbox_navigation_info_bg_color'] = $settings->lightbox_navigation_info_bg_color;
                $result['lightbox_navigation_info_text_color'] = $settings->lightbox_navigation_info_text_color;    
                $result['lightbox_navigation_touch_device_swipe_enabled'] = $settings->lightbox_navigation_touch_device_swipe_enabled;                
                $result['social_share_enabled'] = $settings->social_share_enabled;
                $result['social_share'] = $settings->social_share;
                $result['social_share_lightbox'] = $settings->social_share_lightbox;
                $result['tooltip_enabled'] = $settings->tooltip_enabled;
                $result['tooltip_bg_color'] = $settings->tooltip_bg_color;
                $result['tooltip_stroke_color'] = $settings->tooltip_stroke_color;
                $result['tooltip_text_color'] = $settings->tooltip_text_color;
                $result['slideshow'] = $settings->slideshow;
                $result['slideshow_time'] = $settings->slideshow_time;
                $result['slideshow_autostart'] = $settings->slideshow_autostart;
                $result['slideshow_loop'] = $settings->slideshow_loop;
                $result['slideshow_play'] = $settings->slideshow_play;
                $result['slideshow_play_hover'] = $settings->slideshow_play_hover;
                $result['slideshow_pause'] = $settings->slideshow_pause;
                $result['slideshow_pause_hover'] = $settings->slideshow_pause_hover;
                $result['auto_hide'] = $settings->auto_hide;
                $result['auto_hide_time'] = $settings->auto_hide_time;

                echo json_encode($result);
            	die();
            }

            function editGallerySettings(){// Edit Gallery Settings.
                global $wpdb;
                
                $settings = array('name' => $_POST['name'],
                                  'data_parse_method' => $_POST['data_parse_method'],
                                  'width' => $_POST['width'],
                                  'height' => $_POST['height'],
                                  'bg_color' => $_POST['bg_color'],
                                  'bg_alpha' => $_POST['bg_alpha'],
                                  'images_order' => $_POST['images_order'],
                                  'responsive_enabled' => $_POST['responsive_enabled'],
                                  'thumbnails_position' => $_POST['thumbnails_position'],
                                  'thumbnails_over_image' => $_POST['thumbnails_over_image'],
                                  'thumbnails_bg_color' => $_POST['thumbnails_bg_color'],
                                  'thumbnails_bg_alpha' => $_POST['thumbnails_bg_alpha'],
                                  'thumbnails_spacing' => $_POST['thumbnails_spacing'],
                                  'thumbnails_padding_top' => $_POST['thumbnails_padding_top'],
                                  'thumbnails_padding_right' => $_POST['thumbnails_padding_right'],
                                  'thumbnails_padding_bottom' => $_POST['thumbnails_padding_bottom'],
                                  'thumbnails_padding_left' => $_POST['thumbnails_padding_left'],
                                  'thumbnails_navigation' => $_POST['thumbnails_navigation'],
                                  'thumbnail_width' => $_POST['thumbnail_width'],
                                  'thumbnail_height' => $_POST['thumbnail_height'],
                                  'thumbnail_width_mobile' => $_POST['thumbnail_width_mobile'],
                                  'thumbnail_height_mobile' => $_POST['thumbnail_height_mobile'],
                                  'thumbnail_alpha' => $_POST['thumbnail_alpha'],
                                  'thumbnail_alpha_hover' => $_POST['thumbnail_alpha_hover'],
                                  'thumbnail_alpha_selected' => $_POST['thumbnail_alpha_selected'],
                                  'thumbnail_bg_color' => $_POST['thumbnail_bg_color'],
                                  'thumbnail_bg_color_hover' => $_POST['thumbnail_bg_color_hover'],
                                  'thumbnail_bg_color_selected' => $_POST['thumbnail_bg_color_selected'],
                                  'thumbnail_border_size' => $_POST['thumbnail_border_size'],
                                  'thumbnail_border_color' => $_POST['thumbnail_border_color'],
                                  'thumbnail_border_color_hover' => $_POST['thumbnail_border_color_hover'],
                                  'thumbnail_border_color_selected' => $_POST['thumbnail_border_color_selected'],
                                  'thumbnail_padding_top' => $_POST['thumbnail_padding_top'],
                                  'thumbnail_padding_right' => $_POST['thumbnail_padding_right'],
                                  'thumbnail_padding_bottom' => $_POST['thumbnail_padding_bottom'],
                                  'thumbnail_padding_left' => $_POST['thumbnail_padding_left'],
                                  'image_bg_color' => $_POST['image_bg_color'],
                                  'image_bg_alpha' => $_POST['image_bg_alpha'],
                                  'image_display_type' => $_POST['image_display_type'],
                                  'image_display_time' => $_POST['image_display_time'],
                                  'image_margin_top' => $_POST['image_margin_top'],
                                  'image_margin_right' => $_POST['image_margin_right'],
                                  'image_margin_bottom' => $_POST['image_margin_bottom'],
                                  'image_margin_left' => $_POST['image_margin_left'],
                                  'image_padding_top' => $_POST['image_padding_top'],
                                  'image_padding_right' => $_POST['image_padding_right'],
                                  'image_padding_bottom' => $_POST['image_padding_bottom'],
                                  'image_padding_left' => $_POST['image_padding_left'],                                    
                                  'navigation_enabled' => $_POST['navigation_enabled'],                                  
                                  'navigation_over_image' => $_POST['navigation_over_image'],
                                  'navigation_touch_device_swipe_enabled' => $_POST['navigation_touch_device_swipe_enabled'],
                                  'caption_width' => $_POST['caption_width'],
                                  'caption_height' => $_POST['caption_height'],
                                  'caption_title_color' => $_POST['caption_title_color'],
                                  'caption_text_color' => $_POST['caption_text_color'],
                                  'caption_bg_color' => $_POST['caption_bg_color'],
                                  'caption_bg_alpha' => $_POST['caption_bg_alpha'],
                                  'caption_position' => $_POST['caption_position'],
                                  'caption_over_image' => $_POST['caption_over_image'],
                                  'caption_scroll_scrub_color' => $_POST['caption_scroll_scrub_color'],
                                  'caption_scroll_bg_color' => $_POST['caption_scroll_bg_color'],
                                  'caption_margin_top' => $_POST['caption_margin_top'],
                                  'caption_margin_right' => $_POST['caption_margin_right'],
                                  'caption_margin_bottom' => $_POST['caption_margin_bottom'],
                                  'caption_margin_left' => $_POST['caption_margin_left'],
                                  'caption_padding_top' => $_POST['caption_padding_top'],
                                  'caption_padding_right' => $_POST['caption_padding_right'],
                                  'caption_padding_bottom' => $_POST['caption_padding_bottom'],
                                  'caption_padding_left' => $_POST['caption_padding_left'],                                
                                  'lightbox_enabled' => $_POST['lightbox_enabled'],
                                  'lightbox_window_color' => $_POST['lightbox_window_color'],
                                  'lightbox_window_alpha' => $_POST['lightbox_window_alpha'],
                                  'lightbox_bg_color' => $_POST['lightbox_bg_color'],
                                  'lightbox_bg_alpha' => $_POST['lightbox_bg_alpha'],
                                  'lightbox_margin_top' => $_POST['lightbox_margin_top'],
                                  'lightbox_margin_right' => $_POST['lightbox_margin_right'],
                                  'lightbox_margin_bottom' => $_POST['lightbox_margin_bottom'],
                                  'lightbox_margin_left' => $_POST['lightbox_margin_left'],
                                  'lightbox_padding_top' => $_POST['lightbox_padding_top'],
                                  'lightbox_padding_right' => $_POST['lightbox_padding_right'],
                                  'lightbox_padding_bottom' => $_POST['lightbox_padding_bottom'],
                                  'lightbox_padding_left' => $_POST['lightbox_padding_left'],
                                  'lightbox_navigation_info_bg_color' => $_POST['lightbox_navigation_info_bg_color'],
                                  'lightbox_navigation_info_text_color' => $_POST['lightbox_navigation_info_text_color'],    
                                  'lightbox_navigation_touch_device_swipe_enabled' => $_POST['lightbox_navigation_touch_device_swipe_enabled'], 
                                  'social_share_enabled' => $_POST['social_share_enabled'], 
                                  'tooltip_enabled' => $_POST['tooltip_enabled'],
                                  'tooltip_bg_color' => $_POST['tooltip_bg_color'],
                                  'tooltip_stroke_color' => $_POST['tooltip_stroke_color'],
                                  'tooltip_text_color' => $_POST['tooltip_text_color'],
                                  'slideshow' => $_POST['slideshow'],
                                  'slideshow_time' => $_POST['slideshow_time'],
                                  'slideshow_autostart' => $_POST['slideshow_autostart'],
                                  'slideshow_loop' => $_POST['slideshow_loop'],
                                  'auto_hide' => $_POST['auto_hide'],
                                  'auto_hide_time' => $_POST['auto_hide_time']);
                
                if (isset($_POST['thumbnail_loader'])){
                    $settings['thumbnails_navigation_prev'] = $_POST['thumbnails_navigation_prev'];
                    $settings['thumbnails_navigation_prev_hover'] = $_POST['thumbnails_navigation_prev_hover'];
                    $settings['thumbnails_navigation_next'] = $_POST['thumbnails_navigation_next'];
                    $settings['thumbnails_navigation_next_hover'] = $_POST['thumbnails_navigation_next_hover'];
                    $settings['thumbnail_loader'] = $_POST['thumbnail_loader'];
                    $settings['image_loader'] = $_POST['image_loader'];
                    $settings['navigation_prev'] = $_POST['navigation_prev'];
                    $settings['navigation_prev_hover'] = $_POST['navigation_prev_hover'];
                    $settings['navigation_next'] = $_POST['navigation_next'];
                    $settings['navigation_next_hover'] = $_POST['navigation_next_hover'];
                    $settings['navigation_lightbox'] = $_POST['navigation_lightbox'];
                    $settings['navigation_lightbox_hover'] = $_POST['navigation_lightbox_hover'];
                    $settings['lightbox_loader'] = $_POST['lightbox_loader'];                    
                    $settings['lightbox_navigation_prev'] = $_POST['lightbox_navigation_prev'];
                    $settings['lightbox_navigation_prev_hover'] = $_POST['lightbox_navigation_prev_hover'];
                    $settings['lightbox_navigation_next'] = $_POST['lightbox_navigation_next'];
                    $settings['lightbox_navigation_next_hover'] = $_POST['lightbox_navigation_next_hover'];
                    $settings['lightbox_navigation_close'] = $_POST['lightbox_navigation_close'];
                    $settings['lightbox_navigation_close_hover'] = $_POST['lightbox_navigation_close_hover'];
                    $settings['social_share'] = $_POST['social_share'];
                    $settings['social_share_lightbox'] = $_POST['social_share_lightbox'];
                    $settings['slideshow_play'] = $_POST['slideshow_play'];
                    $settings['slideshow_play_hover'] = $_POST['slideshow_play_hover'];
                    $settings['slideshow_pause'] = $_POST['slideshow_pause'];
                    $settings['slideshow_pause_hover'] = $_POST['slideshow_pause_hover'];
                }
                
                $wpdb->update(DOPTG_Galleries_table, array('name' => $_POST['name']), array(id => $_POST['gallery_id']));
                
                if ($_POST['gallery_id'] == 0){
                    $wpdb->update(DOPTG_Settings_table, $settings, array(id => 1));
                }
                else{
                    $wpdb->update(DOPTG_Settings_table, $settings, array(gallery_id => $_POST['gallery_id']));
                }
                
                echo '';
                
            	die();
            }
            
            function updateSettingsImage(){// Update Settings Images via AJAX.
                if (isset($_POST['gallery_id'])){
                    global $wpdb;
                    
                    switch ($_POST['item']){
                        case 'thumbnails_navigation_prev':
                            $wpdb->update(DOPTG_Settings_table, array('thumbnails_navigation_prev' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'thumbnails_navigation_prev_hover':
                            $wpdb->update(DOPTG_Settings_table, array('thumbnails_navigation_prev_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'thumbnails_navigation_next':
                            $wpdb->update(DOPTG_Settings_table, array('thumbnails_navigation_next' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'thumbnails_navigation_next_hover':
                            $wpdb->update(DOPTG_Settings_table, array('thumbnails_navigation_next_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'thumbnail_loader':
                            $wpdb->update(DOPTG_Settings_table, array('thumbnail_loader' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'image_loader':
                            $wpdb->update(DOPTG_Settings_table, array('image_loader' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_prev':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_prev' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_prev_hover':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_prev_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_next':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_next' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_next_hover':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_next_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_lightbox':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_lightbox' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'navigation_lightbox_hover':
                            $wpdb->update(DOPTG_Settings_table, array('navigation_lightbox_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_loader':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_loader' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_prev':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_prev' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_prev_hover':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_prev_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_next':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_next' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_next_hover':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_next_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_close':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_close' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_close_hover':
                            $wpdb->update(DOPTG_Settings_table, array('lightbox_navigation_close_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;    
                        case 'social_share':
                            $wpdb->update(DOPTG_Settings_table, array('social_share' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;    
                        case 'social_share_lightbox':
                            $wpdb->update(DOPTG_Settings_table, array('social_share_lightbox' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;         
                        case 'slideshow_play':
                            $wpdb->update(DOPTG_Settings_table, array('slideshow_play' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'slideshow_play_hover':
                            $wpdb->update(DOPTG_Settings_table, array('slideshow_play_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'slideshow_pause':
                            $wpdb->update(DOPTG_Settings_table, array('slideshow_pause' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'slideshow_pause_hover':
                            $wpdb->update(DOPTG_Settings_table, array('slideshow_pause_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                    }
                    
                    echo '';
                }
            }
            
// Images            
            function showImages(){// Show Images List.
                if (isset($_POST['gallery_id'])){
                    global $wpdb;
                    $imagesHTML = array();
                    $gallery_id = $_POST['gallery_id'];
                    array_push($imagesHTML, '<ul>');

                    $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                    if ($wpdb->num_rows != 0){
                        foreach ($images as $image) {
                            if ($image->enabled == 'true'){
                                array_push($imagesHTML, '<li class="item-image" id="DOPTG-image-ID-'.$image->id.'"><img src="'.DOPTG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="" /></li>');
                            }
                            else{
                                array_push($imagesHTML, '<li class="item-image item-image-disabled" id="DOPTG-image-ID-'.$image->id.'"><img src="'.DOPTG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="" /></li>');
                            }
                        }
                    }
                    else{
                        array_push($imagesHTML, '<li class="no-data">'.DOPTG_NO_IMAGES.'</li>');
                    }

                    array_push($imagesHTML, '</ul>');
                    echo implode('', $imagesHTML);

                    die();
                }
            }

            function addImageWP(){// Add Images from WP Media.
                global $wpdb;
                
                $urlPieces = explode('wp-content/', $_POST['image_url']);
                $imagePieces = explode('/', $urlPieces[1]);
                
                $targetPath = DOPTG_Plugin_AbsPath.'uploads';
                $ext = substr($imagePieces[count($imagePieces)-1], strrpos($imagePieces[count($imagePieces)-1], '.') + 1);

                $newName = $this->generateName();
                
                // File and new size
                $filename = str_replace('//','/',$targetPath).'/'.$newName.'.'.$ext;
                copy(str_replace('\\', '/', ABSPATH).'wp-content/'.$urlPieces[1], $filename);
                
                // CREATE THUMBNAIL
               
                // Get new sizes
                list($width, $height) = getimagesize($filename);
                $newheight = 300;
                $newwidth = $width*$newheight/$height;

                if ($newwidth < 300){
                    $newwidth = 300;
                    $newheight = $height*$newwidth/$width;
                }

                // Load
                $thumb = ImageCreateTrueColor($newwidth, $newheight);
                
                if ($ext == 'png'){
                    imagealphablending($thumb, false);
                    imagesavealpha($thumb, true);  
                }
                
                if ($ext == 'png'){
                    $source = imagecreatefrompng($filename);
                    imagealphablending($source, true);
                }
                else{
                    $source = imagecreatefromjpeg($filename);
                }

                // Resize
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                // Output
                if ($ext == 'png'){
                    $source = imagepng($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext);
                }
                else{
                    $source = imagejpeg($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext, 100);
                }
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                $wpdb->insert(DOPTG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                        'name' => $newName.'.'.$ext,
                                                        'caption' => '',
                                                        'media' => '',
                                                        'lightbox_media' => '',
                                                        'position' => $wpdb->num_rows+1));
                
                echo $wpdb->insert_id.';;;'.$newName.'.'.$ext;
                
            	die();
            }
            
            function addImageFTP(){// Add Images from FTP.
                global $wpdb;
                
                $folder = DOPTG_Plugin_AbsPath.'ftp-uploads';
                $images = array();
                $folderData = opendir($folder);
   
                while (($file = readdir($folderData)) !== false){
                    if ($file != '.' && $file != '..'){
                        array_push($images, "$file");
                    }
                }
                
                closedir($folderData);

                $result = array();
                $targetPath = DOPTG_Plugin_AbsPath.'uploads';
                sort($images);
                
                foreach ($images as $image):
                    $ext = substr($image, strrpos($image, '.')+1);
                    $newName = $this->generateName();

                    // File and new size
                    $filename = str_replace('//','/',$targetPath).'/'.$newName.'.'.$ext;

                    // Get new sizes
                    copy(DOPTG_Plugin_AbsPath.'ftp-uploads/'.$image, $filename);

                    // CREATE THUMBNAIL

                    // Get new sizes
                    list($width, $height) = getimagesize($filename);
                    $newheight = 300;
                    $newwidth = $width*$newheight/$height;

                    if ($newwidth < 300){
                        $newwidth = 300;
                        $newheight = $height*$newwidth/$width;
                    }

                    // Load
                    $thumb = ImageCreateTrueColor($newwidth, $newheight);
                    
                    if ($ext == 'png'){
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);  
                    }
                    
                    if ($ext == 'png'){
                        $source = imagecreatefrompng($filename);
                        imagealphablending($source, true);
                    }
                    else{
                        $source = imagecreatefromjpeg($filename);
                    }

                    // Resize
                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    // Output
                    if ($ext == 'png'){
                        $source = imagepng($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext);
                    }
                    else{
                        $source = imagejpeg($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext, 100);
                    }

                    $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                    $wpdb->insert(DOPTG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                            'name' => $newName.'.'.$ext,
                                                            'caption' => '',
                                                            'media' => '',
                                                            'lightbox_media' => '',
                                                            'position' => $wpdb->num_rows+1));

                    array_push($result, $wpdb->insert_id.';;;'.$newName.'.'.$ext);
                endforeach;
                
                echo implode(';;;;;', $result);
                
            	die();
            }
            
            function addImage(){// Add Image via AJAX.
                global $wpdb;                
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                $wpdb->insert(DOPTG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                        'name' => $_POST['name'],
                                                        'caption' => '',
                                                        'media' => '',
                                                        'lightbox_media' => '',
                                                        'position' => $wpdb->num_rows+1));
                echo $wpdb->insert_id;
                
            	die();
            }

            function editImages(){// Edit Images.
                global $wpdb;
                
                $images_action = $_POST['images_action'];
                $images_list = $_POST['images'];
                
                switch ($images_action){
                    case 'delete':
                        for ($i=0; $i<count($images_list); $i++){
                            $image = $wpdb->get_row('SELECT * FROM '.DOPTG_Images_table.' WHERE id="'.$images_list[$i].'"');
                            $position = $image->position;

                            $wpdb->query('DELETE FROM '.DOPTG_Images_table.' WHERE id="'.$images_list[$i].'"');
                            unlink(DOPTG_Plugin_AbsPath.'uploads/'.$image->name);
                            unlink(DOPTG_Plugin_AbsPath.'uploads/thumbs/'.$image->name);

                            $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$image->gallery_id.'" ORDER BY position');
                            $num_rows = $wpdb->num_rows;
                            
                            foreach ($images as $image) {
                                if($image->position > $position){
                                    $newPosition = $image->position-1;
                                    $wpdb->update(DOPTG_Images_table, array('position' => $newPosition), array(id => $image->id));
                                }
                            }
                        }
                        break;
                    case 'enable':
                        for ($i=0; $i<count($images_list); $i++){
                            $wpdb->update(DOPTG_Images_table, array('enabled' => 'true'), array('id' => $images_list[$i]));
                        }
                        break;
                    case 'disable':
                        for ($i=0; $i<count($images_list); $i++){
                            $wpdb->update(DOPTG_Images_table, array('enabled' => 'false'), array('id' => $images_list[$i]));
                        }
                        break;
                }
                
                die();
            }

            function sortImages(){// Sort Images via AJAX.
                global $wpdb;

                $order = array();
                $order = explode(',', $_POST['data']);

                for ($i=0; $i<count($order)-1; $i++){
                    $newPos = $i+1;
                    $wpdb->update(DOPTG_Images_table, array('position' => $newPos), array(id => $order[$i]));
                }

                echo $_POST['data'];

            	die();
            }
            
            function showImage(){// Show Image details.
                global $wpdb;
                $result = array();

                $image = $wpdb->get_row('SELECT * FROM '.DOPTG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$image->gallery_id.'"');
                
                $result['id'] = $image->id;
                $result['name'] = $image->name;
                $result['thumbnail_width'] = $settings->thumbnail_width;
                $result['thumbnail_height'] = $settings->thumbnail_height;
                $result['title'] = stripslashes($image->title);
                $result['caption'] = preg_replace("/<br>/", "\n", stripslashes($image->caption));
                $result['media'] = stripslashes($image->media);
                $result['lightbox_media'] = stripslashes($image->lightbox_media);
                $result['enabled'] = $image->enabled;

                echo json_encode($result);
            	die();
            }

            function editImage(){// Edit Image.
                global $wpdb;

                $wpdb->update(DOPTG_Images_table, array('title' => $_POST['image_title'], 'caption' => preg_replace('`[\r\n]`', "<br>", $_POST['image_caption']), 'media' => $_POST['image_media'], 'lightbox_media' => $_POST['image_lightbox_media'], 'enabled' => $_POST['image_enabled']), array('id' => $_POST['image_id']));

                if ($_POST['crop_width'] > 0){
                    list($width, $height) = getimagesize(DOPTG_Plugin_AbsPath.'uploads/'.$_POST['image_name']);
                    $pr = $width/$_POST['image_width'];
                    $ext = substr($_POST['image_name'], strrpos($_POST['image_name'], '.') + 1);

                    $src = DOPTG_Plugin_AbsPath.'uploads/'.$_POST['image_name'];

                    if ($ext == 'png'){
                        $img_r = imagecreatefrompng($src);
                        imagealphablending($img_r, true);
                    }
                    else $img_r = imagecreatefromjpeg($src);

                    $thumb = ImageCreateTrueColor($_POST['thumb_width'], $_POST['thumb_height']);
                    if ($ext == 'png'){
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);  
                    }

                    imagecopyresampled($thumb, $img_r , 0, 0, $_POST['crop_x']*$pr, $_POST['crop_y']*$pr, $_POST['thumb_width'], $_POST['thumb_height'], $_POST['crop_width']*$pr, $_POST['crop_height']*$pr);

                    if ($ext == 'png') $source = imagepng($thumb, DOPTG_Plugin_AbsPath.'uploads/thumbs/'.$_POST['image_name']);
                    else $source = imagejpeg($thumb, DOPTG_Plugin_AbsPath.'uploads/thumbs/'.$_POST['image_name'], 100);

                    echo DOPTG_Plugin_URL.'uploads/thumbs/'.$_POST['image_name'];
                }
                else{
                    echo '';
                }

            	die();
            }
            
            function deleteImage(){// Delete Image.
                global $wpdb;

                $image = $wpdb->get_row('SELECT * FROM '.DOPTG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                $position = $image->position;

                $wpdb->query('DELETE FROM '.DOPTG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                unlink(DOPTG_Plugin_AbsPath.'uploads/'.$image->name);
                unlink(DOPTG_Plugin_AbsPath.'uploads/thumbs/'.$image->name);

                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$image->gallery_id.'" ORDER BY position');
                $num_rows = $wpdb->num_rows;
                foreach ($images as $image) {
                    if($image->position > $position){
                        $newPosition = $image->position-1;
                        $wpdb->update(DOPTG_Images_table, array('position' => $newPosition), array(id => $image->id));
                    }
                }
                
                echo $num_rows;

            	die();
            }

// Functions            
            private function generateName(){
                $len = 64;
                $base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
                $max = strlen($base)-1;
                $newName = '';
                mt_srand((double)microtime()*1000000);
                
                while (strlen($newName)<$len+1){
                    $newName .= $base{mt_rand(0,$max)};
                }
                
                return $newName;
            }  
            
// Editor Changes
            function addDOPTGtoTinyMCE(){// Add gallery button to TinyMCE Editor.
                add_filter('tiny_mce_version', array (&$this, 'changeTinyMCEVersion'));
                add_action('init', array (&$this, 'addDOPTGButtons'));
            }

            function tinyMCEGalleries(){// Send data to editor button.
                global $wpdb;
                $tinyMCE_data = '';
                $galleriesList = array();

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPTG_Galleries_table.' ORDER BY id');
                foreach ($galleries as $gallery) {
                    array_push($galleriesList, $gallery->id.';;'.$gallery->name);
                }
                $tinyMCE_data = DOPTG_TINYMCE_ADD.';;;;;'.implode(';;;', $galleriesList);
                echo '<script type="text/JavaScript">'.
                     '    var DOPTG_tinyMCE_data = "'.$tinyMCE_data.'",'.
                     '        WP_version          = "'.get_bloginfo("version").'";'.  
                     '</script>';
            }

            function addDOPTGButtons(){// Add Button.
                if (!current_user_can('edit_posts') && !current_user_can('edit_pages')){
                    return;
                }

                if ( get_user_option('rich_editing') == 'true'){
                    add_action('admin_head', array (&$this, 'tinyMCEGalleries'));
                    add_filter('mce_external_plugins', array (&$this, 'addDOPTGTinyMCEPlugin'), 5);
                    add_filter('mce_buttons', array (&$this, 'registerDOPTGTinyMCEPlugin'), 5);
                }
            }

            function registerDOPTGTinyMCEPlugin($buttons){// Register editor buttons.
                array_push($buttons, '', 'DOPTG');
                return $buttons;
            }

            function addDOPTGTinyMCEPlugin($plugin_array){// Add plugin to TinyMCE editor.
                $plugin_array['DOPTG'] =  DOPTG_Plugin_URL.'assets/js/tinymce-plugin.js';
                return $plugin_array;
            }

            function changeTinyMCEVersion($version){// TinyMCE version.
                $version = $version+100;
                return $version;
            }
        }
    }
?>