<?php

/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : doptg-widget.php
* File Version            : 1.4
* Created / Last Modified : 05 May 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Widget Class.
*/
  
    class DOPThumbnailGalleryWidget extends WP_Widget{
        
        function DOPThumbnailGalleryWidget(){
            $widget_ops = array('classname' => 'DOPThumbnailGalleryWidget', 'description' => !is_admin() ? '':DOPTG_WIDGET_DESCRIPTION);
            $this->WP_Widget('DOPThumbnailGalleryWidget', !is_admin() ? '':DOPTG_WIDGET_TITLE, $widget_ops);
        }
 
        function form($instance){
            global $wpdb;
            
            $instance = wp_parse_args((array)$instance, array('title' => '', 'id' => '0'));
            $title = $instance['title'];
            $id = $instance['id'];
                            
            $galleryHTML = array();
            
            array_push($galleryHTML, '<p>');
            array_push($galleryHTML, '    <label for="'.$this->get_field_id('title').'">'.DOPTG_WIDGET_LABEL_TITLE.' </label>');
            array_push($galleryHTML, '    <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" />');
            
            array_push($galleryHTML, '    <label for="'.$this->get_field_id('id').'" style=" display: block; padding-top: 10px;">'.DOPTG_WIDGET_LABEL_ID.' </label>');
            array_push($galleryHTML, '    <select class="widefat" id="'.$this->get_field_id('id').'" name="'.$this->get_field_name('id').'">');

            $galleries = $wpdb->get_results('SELECT * FROM '.DOPTG_Galleries_table.' ORDER BY id DESC');

            if ($wpdb->num_rows != 0){
                foreach ($galleries as $gallery) {
                    if (esc_attr($id) == $gallery->id){
                        array_push($galleryHTML, '<option value="'.$gallery->id.'" selected="selected">'.$gallery->id.' - '.$gallery->name.'</option>');
                        
                    }
                    else{
                        array_push($galleryHTML, '<option value="'.$gallery->id.'">'.$gallery->id.' - '.$gallery->name.'</option>');
                    }
                }
            }
            else{
                array_push($galleryHTML, '<option value="0">'.DOPTG_WIDGET_NO_SCROLLERS.'</option>');
            }
            
            array_push($galleryHTML, '    </select>');
            array_push($galleryHTML, '</p>');

            echo implode('', $galleryHTML);
        }
 
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['id'] = $new_instance['id'];
            
            return $instance;
        }

        function widget($args, $instance){
            global $wpdb;
            $data = array();
            $imagesList = array();
            extract($args, EXTR_SKIP);

            echo $before_widget;
            $title = empty($instance['title']) ? ' ':apply_filters('widget_title', $instance['title']);
            $id = empty($instance['id']) ? '0':$instance['id'];
 
            if (!empty($title)){
                echo $before_title.$title.$after_title;        
            }
                
            $default_settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="0"');
            $settings = $wpdb->get_row('SELECT * FROM '.DOPTG_Settings_table.' WHERE gallery_id="'.$id.'"');

            if ($default_settings->data_parse_method == 'ajax'){
                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$id.'" AND enabled="true" ORDER BY position');

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
                
                echo '<div class="DOPThumbnailGalleryContainer" id="DOPThumbnailGallery'.$id.'">
                            <a href="'.DOPTG_Plugin_URL.'frontend-ajax.php" class="Settings"></a>
                            <ul class="Content" style="display:none;">'.implode('', $imagesList).'</ul>
                        </div>
                        <script type="text/JavaScript">
                            jQuery(document).ready(function(){
                                jQuery(\'#DOPThumbnailGallery'.$id.'\').DOPThumbnailGallery();
                            });
                        </script>';
            }
            else{
                $images = $wpdb->get_results('SELECT * FROM '.DOPTG_Images_table.' WHERE gallery_id="'.$id.'" AND enabled="true" ORDER BY position');

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
            
                echo '<div class="DOPThumbnailGalleryContainer" id="DOPThumbnailGallery'.$id.'">
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
                            jQuery(\'#DOPThumbnailGallery'.$id.'\').DOPThumbnailGallery({\'ParseMethod\': \'HTML\'});
                        });
                    </script>';
            }

            echo $after_widget;
        }

    }

?>