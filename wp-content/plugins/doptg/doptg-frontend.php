<?php

/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : doptg-frontend.php
* File Version            : 2.0
* Created / Last Modified : 01 October 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Front End Class.
*/

    if (!class_exists("DOPThumbnailGalleryFrontEnd")){
        class DOPThumbnailGalleryFrontEnd{
            function DOPThumbnailGalleryFrontEnd(){// Constructor.
                add_action('wp_enqueue_scripts', array(&$this, 'addStyles'));
                add_action('wp_enqueue_scripts', array(&$this, 'addScripts'));
                $this->init();
            }
            
            function addStyles(){
                // Register Styles.
                wp_register_style('DOPTG_JScrollPaneStyle', plugins_url('libraries/gui/css/jquery.jscrollpane.css', __FILE__));
                wp_register_style('DOPTG_ThumbnailGalleryStyle', plugins_url('assets/gui/css/jquery.dop.ThumbnailGallery.css', __FILE__));
                
                // Enqueue Styles.
                wp_enqueue_style('DOPTG_JScrollPaneStyle');
                wp_enqueue_style('DOPTG_ThumbnailGalleryStyle');
            }
            
            function addScripts(){
                // Register JavaScript.
                if (preg_match('/MSIE 7/i', $_SERVER['HTTP_USER_AGENT'])){
                    wp_register_script('DOPTG_json2', plugins_url('libraries/js/json2.js', __FILE__), array('jquery'), false, true);
                }
                wp_register_script('DOPTG_MouseWheelJS', plugins_url('libraries/js/jquery.mousewheel.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_JScrollPaneJS', plugins_url('libraries/js/jquery.jscrollpane.min.js', __FILE__), array('jquery'), false, true);
                wp_register_script('DOPTG_ThumbnailGalleryJS', plugins_url('assets/js/jquery.dop.ThumbnailGallery.js', __FILE__), array('jquery'), false, true);

                // Enqueue JavaScript.
                if (!wp_script_is('jquery', 'queue')){
                    wp_enqueue_script('jquery');
                }
                if (preg_match('/MSIE 7/i', $_SERVER['HTTP_USER_AGENT'])){
                    wp_enqueue_script('DOPTG_json2');
                }
                wp_enqueue_script('DOPTG_MouseWheelJS');
                wp_enqueue_script('DOPTG_JScrollPaneJS');
                wp_enqueue_script('DOPTG_ThumbnailGalleryJS');
            }

            function init(){// Init Gallery.
                $this->initConstants();
                add_shortcode('doptg', array(&$this, 'captionShortcode'));
            }

            function initConstants(){// Constants init.
                global $wpdb;

                // Tables
                define('DOPTG_Settings_table', $wpdb->prefix.'doptg_settings');
                define('DOPTG_Galleries_table', $wpdb->prefix.'doptg_galleries');
                define('DOPTG_Images_table', $wpdb->prefix.'doptg_images');
            }

            function captionShortcode($atts, $content = null){// Read Shortcodes.
                global $wpdb;
                $data = array();
                $imagesList = array();
                
                extract(shortcode_atts(array(
                    'class' => 'doptg',
                ), $atts));
                
                $default_settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="0"');
                $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$atts['id'].'"');
                
                if ($default_settings->data_parse_method == 'ajax'){
                    $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$atts['id'].'" AND enabled="true" ORDER BY position');

                    foreach ($images as $image){
                        array_push($imagesList, '<li>
                                                    <img class="Image" src="'.DOPTG_Plugin_URL.'uploads/'.$image->name.'" alt="'.stripslashes($image->title).'" title="'.stripslashes($image->title).'" />
                                                    <img class="Thumb" src="'.DOPTG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="'.stripslashes($image->title).'" title="'.stripslashes($image->title).'" />
                                                    <span class="CaptionTitle">'.stripslashes($image->title).'</span>
                                                    <span class="CaptionText">'.stripslashes($image->caption).'</span>
                                                    <span class="Media">'.stripslashes($image->media).'</span>
                                                    <span class="LightboxMedia">'.stripslashes($image->lightbox_media).'</span>
                                                 </li>');
                    }
                    
                    $data = '<div class="DOPThumbnailGalleryContainer" id="DOPThumbnailGallery'.$atts['id'].'">
                                 <a href="'.DOPTG_Plugin_URL.'frontend-ajax.php" class="Settings"></a>
                                 <ul class="Content" style="display:none;">'.implode('', $imagesList).'</ul>
                             </div>
                             <script type="text/JavaScript">
                                 jQuery(document).ready(function(){
                                     jQuery(\'#DOPThumbnailGallery'.$atts['id'].'\').DOPThumbnailGallery();
                                 });
                             </script>';
                }
                else{
                    $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$atts['id'].'" AND enabled="true" ORDER BY position');

                    foreach ($images as $image){
                        array_push($imagesList, '<li>
                                                    <img class="Image" src="'.DOPTG_Plugin_URL.'uploads/'.$image->name.'" alt="'.stripslashes($image->title).'" title="'.stripslashes($image->title).'" />
                                                    <img class="Thumb" src="'.DOPTG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="'.stripslashes($image->title).'" title="'.stripslashes($image->title).'" />
                                                    <span class="CaptionTitle">'.stripslashes($image->title).'</span>
                                                    <span class="CaptionText">'.stripslashes($image->caption).'</span>
                                                    <span class="Media">'.stripslashes($image->media).'</span>
                                                    <span class="LightboxMedia">'.stripslashes($image->lightbox_media).'</span>
                                                 </li>');
                    }
                
                    $data = '<div class="DOPThumbnailGalleryContainer" id="DOPThumbnailGallery'.$atts['id'].'">
                                <ul class="Settings" style="display:none;">
                                    <li class="Width">'.$settings->width.'</li>
                                    <li class="Height">'.$settings->height.'</li>
                                    <li class="BgColor">'.$settings->bg_color.'</li>
                                    <li class="BgAlpha">'.$settings->bg_alpha.'</li>
                                    <li class="ImagesOrder">'.$settings->images_order.'</li>
                                    <li class="ResponsiveEnabled">'.$settings->responsive_enabled.'</li>  
                                    <li class="ThumbnailsPosition">'.$settings->thumbnails_position.'</li>
                                    <li class="ThumbnailsOverImage">'.$settings->thumbnails_over_image.'</li>
                                    <li class="ThumbnailsBgColor">'.$settings->thumbnails_bg_color.'</li>
                                    <li class="ThumbnailsBgAlpha">'.$settings->thumbnails_bg_alpha.'</li>
                                    <li class="ThumbnailsSpacing">'.$settings->thumbnails_spacing.'</li>
                                    <li class="ThumbnailsPaddingTop">'.$settings->thumbnails_padding_top.'</li>
                                    <li class="ThumbnailsPaddingRight">'.$settings->thumbnails_padding_right.'</li>
                                    <li class="ThumbnailsPaddingBottom">'.$settings->thumbnails_padding_bottom.'</li>
                                    <li class="ThumbnailsPaddingLeft">'.$settings->thumbnails_padding_left.'</li>
                                    <li class="ThumbnailsNavigation">'.$settings->thumbnails_navigation.'</li>
                                    <li class="ThumbnailsNavigationPrev">'.DOPTG_Plugin_URL.$settings->thumbnails_navigation_prev.'</li>
                                    <li class="ThumbnailsNavigationPrevHover">'.DOPTG_Plugin_URL.$settings->thumbnails_navigation_prev_hover.'</li>
                                    <li class="ThumbnailsNavigationNext">'.DOPTG_Plugin_URL.$settings->thumbnails_navigation_next.'</li>
                                    <li class="ThumbnailsNavigationNextHover">'.DOPTG_Plugin_URL.$settings->thumbnails_navigation_next_hover.'</li>
                                    <li class="ThumbnailLoader">'.DOPTG_Plugin_URL.$settings->thumbnail_loader.'</li>
                                    <li class="ThumbnailWidth">'.$settings->thumbnail_width.'</li>
                                    <li class="ThumbnailHeight">'.$settings->thumbnail_height.'</li>
                                    <li class="ThumbnailWidthMobile">'.$settings->thumbnail_width_mobile.'</li>
                                    <li class="ThumbnailHeightMobile">'.$settings->thumbnail_height_mobile.'</li>
                                    <li class="ThumbnailAlpha">'.$settings->thumbnail_alpha.'</li>
                                    <li class="ThumbnailAlphaHover">'.$settings->thumbnail_alpha_hover.'</li>
                                    <li class="ThumbnailAlphaSelected">'.$settings->thumbnail_alpha_selected.'</li>
                                    <li class="ThumbnailBgColor">'.$settings->thumbnail_bg_color.'</li>
                                    <li class="ThumbnailBgColorHover">'.$settings->thumbnail_bg_color_hover.'</li>
                                    <li class="ThumbnailBgColorSelected">'.$settings->thumbnail_bg_color_selected.'</li>
                                    <li class="ThumbnailBorderSize">'.$settings->thumbnail_border_size.'</li>
                                    <li class="ThumbnailBorderColor">'.$settings->thumbnail_border_color.'</li>
                                    <li class="ThumbnailBorderColorHover">'.$settings->thumbnail_border_color_hover.'</li>
                                    <li class="ThumbnailBorderColorSelected">'.$settings->thumbnail_border_color_selected.'</li>
                                    <li class="ThumbnailPaddingTop">'.$settings->thumbnail_padding_top.'</li>
                                    <li class="ThumbnailPaddingRight">'.$settings->thumbnail_padding_right.'</li>
                                    <li class="ThumbnailPaddingBottom">'.$settings->thumbnail_padding_bottom.'</li>
                                    <li class="ThumbnailPaddingLeft">'.$settings->thumbnail_padding_left.'</li>
                                    <li class="ImageLoader">'.DOPTG_Plugin_URL.$settings->image_loader.'</li>
                                    <li class="ImageBgColor">'.$settings->image_bg_color.'</li>
                                    <li class="ImageBgAlpha">'.$settings->image_bg_alpha.'</li>
                                    <li class="ImageDisplayType">'.$settings->image_display_type.'</li>
                                    <li class="ImageDisplayTime">'.$settings->image_display_time.'</li>
                                    <li class="ImageMarginTop">'.$settings->image_margin_top.'</li>
                                    <li class="ImageMarginRight">'.$settings->image_margin_right.'</li>
                                    <li class="ImageMarginBottom">'.$settings->image_margin_bottom.'</li>
                                    <li class="ImageMarginLeft">'.$settings->image_margin_left.'</li>
                                    <li class="ImagePaddingTop">'.$settings->image_padding_top.'</li>
                                    <li class="ImagePaddingRight">'.$settings->image_padding_right.'</li>
                                    <li class="ImagePaddingBottom">'.$settings->image_padding_bottom.'</li>        
                                    <li class="ImagePaddingLeft">'.$settings->image_padding_left.'</li>
                                    <li class="NavigationEnabled">'.$settings->navigation_enabled.'</li>
                                    <li class="NavigationOverImage">'.$settings->navigation_over_image.'</li>
                                    <li class="NavigationPrev">'.DOPTG_Plugin_URL.$settings->navigation_prev.'</li>
                                    <li class="NavigationPrevHover">'.DOPTG_Plugin_URL.$settings->navigation_prev_hover.'</li>
                                    <li class="NavigationNext">'.DOPTG_Plugin_URL.$settings->navigation_next.'</li>
                                    <li class="NavigationNextHover">'.DOPTG_Plugin_URL.$settings->navigation_next_hover.'</li>
                                    <li class="NavigationLightbox">'.DOPTG_Plugin_URL.$settings->navigation_lightbox.'</li>
                                    <li class="NavigationLightboxHover">'.DOPTG_Plugin_URL.$settings->navigation_lightbox_hover.'</li>
                                    <li class="NavigationTouchDeviceSwipeEnabled">'.$settings->navigation_touch_device_swipe_enabled.'</li>
                                    <li class="CaptionWidth">'.$settings->caption_width.'</li>
                                    <li class="CaptionHeight">'.$settings->caption_height.'</li>
                                    <li class="CaptionTitleColor">'.$settings->caption_title_color.'</li>
                                    <li class="CaptionTextColor">'.$settings->caption_text_color.'</li>
                                    <li class="CaptionBgColor">'.$settings->caption_bg_color.'</li>
                                    <li class="CaptionBgAlpha">'.$settings->caption_bg_alpha.'</li>
                                    <li class="CaptionPosition">'.$settings->caption_position.'</li>
                                    <li class="CaptionOverImage">'.$settings->caption_over_image.'</li>
                                    <li class="CaptionScrollScrubColor">'.$settings->caption_scroll_scrub_color.'</li>
                                    <li class="CaptionScrollBgColor">'.$settings->caption_scroll_bg_color.'</li>    
                                    <li class="CaptionMarginTop">'.$settings->caption_margin_top.'</li>
                                    <li class="CaptionMarginRight">'.$settings->caption_margin_right.'</li>
                                    <li class="CaptionMarginBottom">'.$settings->caption_margin_bottom.'</li>
                                    <li class="CaptionMarginLeft">'.$settings->caption_margin_left.'</li>
                                    <li class="CaptionPaddingTop">'.$settings->caption_padding_top.'</li>
                                    <li class="CaptionPaddingRight">'.$settings->caption_padding_right.'</li>
                                    <li class="CaptionPaddingBottom">'.$settings->caption_padding_bottom.'</li>
                                    <li class="CaptionPaddingLeft">'.$settings->caption_padding_left.'</li>
                                    <li class="LightboxEnabled">'.$settings->lightbox_enabled.'</li>
                                    <li class="LightboxWindowColor">'.$settings->lightbox_window_color.'</li>
                                    <li class="LightboxWindowAlpha">'.$settings->lightbox_window_alpha.'</li>
                                    <li class="LightboxLoader">'.DOPTG_Plugin_URL.$settings->lightbox_loader.'</li>
                                    <li class="LightboxBgColor">'.$settings->lightbox_bg_color.'</li>
                                    <li class="LightboxBgAlpha">'.$settings->lightbox_bg_alpha.'</li>
                                    <li class="LightboxMarginTop">'.$settings->lightbox_margin_top.'</li>
                                    <li class="LightboxMarginRight">'.$settings->lightbox_margin_right.'</li>
                                    <li class="LightboxMarginBottom">'.$settings->lightbox_margin_bottom.'</li>
                                    <li class="LightboxMarginLeft">'.$settings->lightbox_margin_left.'</li>
                                    <li class="LightboxPaddingTop">'.$settings->lightbox_padding_top.'</li>
                                    <li class="LightboxPaddingRight">'.$settings->lightbox_padding_right.'</li>
                                    <li class="LightboxPaddingBottom">'.$settings->lightbox_padding_bottom.'</li>
                                    <li class="LightboxPaddingLeft">'.$settings->lightbox_padding_left.'</li>
                                    <li class="LightboxNavigationPrev">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_prev.'</li>
                                    <li class="LightboxNavigationPrevHover">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_prev_hover.'</li>
                                    <li class="LightboxNavigationNext">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_next.'</li>
                                    <li class="LightboxNavigationNextHover">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_next_hover.'</li>
                                    <li class="LightboxNavigationClose">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_close.'</li>
                                    <li class="LightboxNavigationCloseHover">'.DOPTG_Plugin_URL.$settings->lightbox_navigation_close_hover.'</li>
                                    <li class="LightboxNavigationInfoBgColor">'.$settings->lightbox_navigation_info_bg_color.'</li>
                                    <li class="LightboxNavigationInfoTextColor">'.$settings->lightbox_navigation_info_text_color.'</li>
                                    <li class="LightboxNavigationTouchDeviceSwipeEnabled">'.$settings->lightbox_navigation_touch_device_swipe_enabled.'</li>
                                    <li class="SocialShareEnabled">'.$settings->social_share_enabled.'</li>
                                    <li class="SocialShare">'.DOPTG_Plugin_URL.$settings->social_share.'</li>
                                    <li class="SocialShareLightbox">'.DOPTG_Plugin_URL.$settings->social_share_lightbox.'</li>
                                    <li class="TooltipEnabled">'.$settings->tooltip_enabled.'</li>
                                    <li class="TooltipBgColor">'.$settings->tooltip_bg_color.'</li>
                                    <li class="TooltipStrokeColor">'.$settings->tooltip_stroke_color.'</li>
                                    <li class="TooltipTextColor">'.$settings->tooltip_text_color.'</li>
                                    <li class="Slideshow">'.$settings->slideshow.'</li>
                                    <li class="SlideshowTime">'.$settings->slideshow_time.'</li>
                                    <li class="SlideshowAutostart">'.$settings->slideshow_autostart.'</li>
                                    <li class="SlideshowLoop">'.$settings->slideshow_loop.'</li>
                                    <li class="SlideshowPlay">'.DOPTG_Plugin_URL.$settings->slideshow_play.'</li>
                                    <li class="SlideshowPlayHover">'.DOPTG_Plugin_URL.$settings->slideshow_play_hover.'</li>
                                    <li class="SlideshowPause">'.DOPTG_Plugin_URL.$settings->slideshow_pause.'</li>
                                    <li class="SlideshowPauseHover">'.DOPTG_Plugin_URL.$settings->slideshow_pause_hover.'</li>
                                    <li class="AutoHide">'.$settings->auto_hide.'</li>
                                    <li class="AutoHideTime">'.$settings->auto_hide_time.'</li>
                                </ul>
                                <ul class="Content" style="display:none;">'.implode('', $imagesList).'</ul>
                            </div>
                            <script type="text/JavaScript">
                                jQuery(document).ready(function(){
                                    jQuery(\'#DOPThumbnailGallery'.$atts['id'].'\').DOPThumbnailGallery({\'ParseMethod\': \'HTML\'});
                                });
                            </script>';
                }
                
                return $data;
            }

            function getGalleryData(){// Get Gallery Info.
                global $wpdb;
                $data = array();

                $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$_POST['id'].'"');

                $data['Width'] = $settings->width;
                $data['Height'] = $settings->height;
                $data['BgColor'] = $settings->bg_color;
                $data['BgAlpha'] = $settings->bg_alpha;
                $data['ImagesOrder'] = $settings->images_order;
                $data['ResponsiveEnabled'] = $settings->responsive_enabled;
                $data['ThumbnailsPosition'] = $settings->thumbnails_position;
                $data['ThumbnailsOverImage'] = $settings->thumbnails_over_image;
                $data['ThumbnailsBgColor'] = $settings->thumbnails_bg_color;
                $data['ThumbnailsBgAlpha'] = $settings->thumbnails_bg_alpha;
                $data['ThumbnailsSpacing'] = $settings->thumbnails_spacing;
                $data['ThumbnailsPaddingTop'] = $settings->thumbnails_padding_top;
                $data['ThumbnailsPaddingRight'] = $settings->thumbnails_padding_right;
                $data['ThumbnailsPaddingBottom'] = $settings->thumbnails_padding_bottom;
                $data['ThumbnailsPaddingLeft'] = $settings->thumbnails_padding_left;          
                $data['ThumbnailsNavigation'] = $settings->thumbnails_navigation;             
                $data['ThumbnailsNavigationPrev'] = DOPTG_Plugin_URL.$settings->thumbnails_navigation_prev;
                $data['ThumbnailsNavigationPrevHover'] = DOPTG_Plugin_URL.$settings->thumbnails_navigation_prev_hover;
                $data['ThumbnailsNavigationNext'] = DOPTG_Plugin_URL.$settings->thumbnails_navigation_next;
                $data['ThumbnailsNavigationNextHover'] = DOPTG_Plugin_URL.$settings->thumbnails_navigation_next_hover;
                $data['ThumbnailLoader'] = DOPTG_Plugin_URL.$settings->thumbnail_loader;
                $data['ThumbnailWidth'] = $settings->thumbnail_width;
                $data['ThumbnailHeight'] = $settings->thumbnail_height;
                $data['ThumbnailWidthMobile'] = $settings->thumbnail_width_mobile;
                $data['ThumbnailHeightMobile'] = $settings->thumbnail_height_mobile;
                $data['ThumbnailAlpha'] = $settings->thumbnail_alpha;
                $data['ThumbnailAlphaHover'] = $settings->thumbnail_alpha_hover;
                $data['ThumbnailAlphaSelected'] = $settings->thumbnail_alpha_selected;
                $data['ThumbnailBgColor'] = $settings->thumbnail_bg_color;
                $data['ThumbnailBgColorHover'] = $settings->thumbnail_bg_color_hover;
                $data['ThumbnailBgColorSelected'] = $settings->thumbnail_bg_color_selected;
                $data['ThumbnailBorderSize'] = $settings->thumbnail_border_size;
                $data['ThumbnailBorderColor'] = $settings->thumbnail_border_color;
                $data['ThumbnailBorderColorHover'] = $settings->thumbnail_border_color_hover;
                $data['ThumbnailBorderColorSelected'] = $settings->thumbnail_border_color_selected;
                $data['ThumbnailPaddingTop'] = $settings->thumbnail_padding_top;
                $data['ThumbnailPaddingRight'] = $settings->thumbnail_padding_right;
                $data['ThumbnailPaddingBottom'] = $settings->thumbnail_padding_bottom;
                $data['ThumbnailPaddingLeft'] = $settings->thumbnail_padding_left;
                $data['ImageLoader'] = DOPTG_Plugin_URL.$settings->image_loader;
                $data['ImageBgColor'] = $settings->image_bg_color;
                $data['ImageBgAlpha'] = $settings->image_bg_alpha;
                $data['ImageDisplayType'] = $settings->image_display_type;
                $data['ImageDisplayTime'] = $settings->image_display_time;
                $data['ImageMarginTop'] = $settings->image_margin_top;
                $data['ImageMarginRight'] = $settings->image_margin_right;
                $data['ImageMarginBottom'] = $settings->image_margin_bottom;
                $data['ImageMarginLeft'] = $settings->image_margin_left;
                $data['ImagePaddingTop'] = $settings->image_padding_top;
                $data['ImagePaddingRight'] = $settings->image_padding_right;
                $data['ImagePaddingBottom'] = $settings->image_padding_bottom;
                $data['ImagePaddingLeft'] = $settings->image_padding_left;
                $data['NavigationEnabled'] = $settings->navigation_enabled;
                $data['NavigationOverImage'] = $settings->navigation_over_image;
                $data['NavigationPrev'] = DOPTG_Plugin_URL.$settings->navigation_prev;
                $data['NavigationPrevHover'] = DOPTG_Plugin_URL.$settings->navigation_prev_hover;
                $data['NavigationNext'] = DOPTG_Plugin_URL.$settings->navigation_next;
                $data['NavigationNextHover'] = DOPTG_Plugin_URL.$settings->navigation_next_hover;
                $data['NavigationLightbox'] = DOPTG_Plugin_URL.$settings->navigation_lightbox;
                $data['NavigationLightboxHover'] = DOPTG_Plugin_URL.$settings->navigation_lightbox_hover;
                $data['NavigationTouchDeviceSwipeEnabled'] = $settings->navigation_touch_device_swipe_enabled;
                $data['CaptionWidth'] = $settings->caption_width;
                $data['CaptionHeight'] = $settings->caption_height;
                $data['CaptionTitleColor'] = $settings->caption_title_color;
                $data['CaptionTextColor'] = $settings->caption_text_color;
                $data['CaptionBgColor'] = $settings->caption_bg_color;
                $data['CaptionBgAlpha'] = $settings->caption_bg_alpha;
                $data['CaptionPosition'] = $settings->caption_position;
                $data['CaptionOverImage'] = $settings->caption_over_image;
                $data['CaptionScrollScrubColor'] = $settings->caption_scroll_scrub_color;
                $data['CaptionScrollBgColor'] = $settings->caption_scroll_bg_color;
                $data['CaptionMarginTop'] = $settings->caption_margin_top;
                $data['CaptionMarginRight'] = $settings->caption_margin_right;
                $data['CaptionMarginBottom'] = $settings->caption_margin_bottom;
                $data['CaptionMarginLeft'] = $settings->caption_margin_left;
                $data['CaptionPaddingTop'] = $settings->caption_padding_top;
                $data['CaptionPaddingRight'] = $settings->caption_padding_right;
                $data['CaptionPaddingBottom'] = $settings->caption_padding_bottom;
                $data['CaptionPaddingLeft'] = $settings->caption_padding_left;                
                $data['LightboxEnabled'] = $settings->lightbox_enabled;                
                $data['LightboxWindowColor'] = $settings->lightbox_window_color;
                $data['LightboxWindowAlpha'] = $settings->lightbox_window_alpha;
                $data['LightboxLoader'] = DOPTG_Plugin_URL.$settings->lightbox_loader;
                $data['LightboxBgColor'] = $settings->lightbox_bg_color;
                $data['LightboxBgAlpha'] = $settings->lightbox_bg_alpha;
                $data['LightboxMarginTop'] = $settings->lightbox_margin_top;
                $data['LightboxMarginRight'] = $settings->lightbox_margin_right;
                $data['LightboxMarginBottom'] = $settings->lightbox_margin_bottom;
                $data['LightboxMarginLeft'] = $settings->lightbox_margin_left;
                $data['LightboxPaddingTop'] = $settings->lightbox_padding_top;
                $data['LightboxPaddingRight'] = $settings->lightbox_padding_right;
                $data['LightboxPaddingBottom'] = $settings->lightbox_padding_bottom;
                $data['LightboxPaddingLeft'] = $settings->lightbox_padding_left;
                $data['LightboxNavigationPrev'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_prev;
                $data['LightboxNavigationPrevHover'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_prev_hover;
                $data['LightboxNavigationNext'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_next;
                $data['LightboxNavigationNextHover'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_next_hover;
                $data['LightboxNavigationClose'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_close;
                $data['LightboxNavigationCloseHover'] = DOPTG_Plugin_URL.$settings->lightbox_navigation_close_hover;
                $data['LightboxNavigationInfoBgColor'] = $settings->lightbox_navigation_info_bg_color;
                $data['LightboxNavigationInfoTextColor'] = $settings->lightbox_navigation_info_text_color;      
                $data['LightboxNavigationTouchDeviceSwipeEnabled'] = $settings->lightbox_navigation_touch_device_swipe_enabled;
                $data['SocialShareEnabled'] = $settings->social_share_enabled;   
                $data['SocialShare'] = DOPTG_Plugin_URL.$settings->social_share;   
                $data['SocialShareLightbox'] = DOPTG_Plugin_URL.$settings->social_share_lightbox;       
                $data['TooltipEnabled'] = $settings->tooltip_enabled;
                $data['TooltipBgColor'] = $settings->tooltip_bg_color;
                $data['TooltipStrokeColor'] = $settings->tooltip_stroke_color;
                $data['TooltipTextColor'] = $settings->tooltip_text_color;
                $data['Slideshow'] = $settings->slideshow;
                $data['SlideshowTime'] = $settings->slideshow_time;
                $data['SlideshowAutostart'] = $settings->slideshow_autostart;
                $data['SlideshowLoop'] = $settings->slideshow_loop;
                $data['SlideshowPlay'] = DOPTG_Plugin_URL.$settings->slideshow_play;
                $data['SlideshowPlayHover'] = DOPTG_Plugin_URL.$settings->slideshow_play_hover;
                $data['SlideshowPause'] = DOPTG_Plugin_URL.$settings->slideshow_pause;
                $data['SlideshowPauseHover'] = DOPTG_Plugin_URL.$settings->slideshow_pause_hover;
                $data['AutoHide'] = $settings->auto_hide;
                $data['AutoHideTime'] = $settings->auto_hide_time;

                echo json_encode($data);
            }
        }
    }
?>