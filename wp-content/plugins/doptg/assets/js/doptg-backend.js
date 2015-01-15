/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : doptg-backend.js
* File Version            : 2.1
* Created / Last Modified : 01 October 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Admin Scripts.
*/

//Declare global variables.
var currGallery = 0,
currImage = 0,
clearClick = true,
imageDisplay = false,
imageWidth = 0,
imageHeight = 0,
$jDOPTG = jQuery.noConflict();

$jDOPTG(document).ready(function(){
    if (DOPTG_curr_page == undefined){
        DOPTG_curr_page = 'Galleries List';
    }
    
    doptgResize();

    $jDOPTG(window).resize(function(){
        doptgResize();
    });
    
    $jDOPTG(document).scroll(function(){
        doptgResize();
    });

    switch (DOPTG_curr_page){
        case 'Galleries List':
            doptgShowGalleries();
            break;
    }
});

function doptgResize(){// ResiE admin panel.
    $jDOPTG('.column2', '.DOPTG-admin').width(($jDOPTG('.DOPTG-admin').width()-$jDOPTG('.column1', '.DOPTG-admin').width()-2)/2);
    $jDOPTG('.column3', '.DOPTG-admin').width(($jDOPTG('.DOPTG-admin').width()-$jDOPTG('.column1', '.DOPTG-admin').width()-2)/2);
    $jDOPTG('.column-separator', '.DOPTG-admin').height(0);
    $jDOPTG('.column-separator', '.DOPTG-admin').height($jDOPTG('.DOPTG-admin').height()-$jDOPTG('h2', '.DOPTG-admin').height()-parseInt($jDOPTG('h2', '.DOPTG-admin').css('padding-top'))-parseInt($jDOPTG('h2', '.DOPTG-admin').css('padding-bottom')));
    $jDOPTG('.main', '.DOPTG-admin').css('display', 'block');

    $jDOPTG('.column-input', '.DOPTG-admin').width($jDOPTG('.column-content', '.column3', '.DOPTG-admin').width()-32);
    $jDOPTG('.column-image', '.DOPTG-admin').width($jDOPTG('.column-input', '.DOPTG-admin').width()+10);
    
    if (imageDisplay){
        $jDOPTG('span', '.column-image', '.DOPTG-admin').width($jDOPTG('.column-image', '.DOPTG-admin').width());
        $jDOPTG('span', '.column-image', '.DOPTG-admin').height($jDOPTG('.column-image', '.DOPTG-admin').width()*imageHeight/imageWidth);
        $jDOPTG('img', '.column-image', '.DOPTG-admin').width($jDOPTG('span', '.column-image', '.DOPTG-admin').width());
        $jDOPTG('img', '.column-image', '.DOPTG-admin').height($jDOPTG('span', '.column-image', '.DOPTG-admin').height());
        $jDOPTG('img', '.column-image', '.DOPTG-admin').css('margin-top', 0);
        $jDOPTG('img', '.column-image', '.DOPTG-admin').css('margin-left', 0);
    }
}

// Galleries

function doptgShowGalleries(){// Show all galleries.
    doptgRemoveColumns(2);
    doptgToggleMessage('show', DOPTG_LOAD);
    
    $jDOPTG.post(ajaxurl, {action: 'doptg_show_galleries'}, function(data){
        $jDOPTG('.column-content', '.column1', '.DOPTG-admin').html(data);
        doptgGalleriesEvents();
        doptgToggleMessage('hide', DOPTG_GALLERIES_LOADED);
    });
}

function doptgAddGallery(){// Add gallery via AJAX.
    if (clearClick){
        doptgRemoveColumns(2);
        doptgToggleMessage('show', DOPTG_ADD_GALLERY_SUBMITED);
        
        $jDOPTG.post(ajaxurl, {action: 'doptg_add_gallery'}, function(data){
            $jDOPTG('.column-content', '.column1', '.DOPTG-admin').html(data);
            doptgGalleriesEvents();
            doptgToggleMessage('hide', DOPTG_ADD_GALERRY_SUCCESS);
        });
    }
}

function doptgShowDefaultSettings(){// Show default settings.
    if (clearClick){
        $jDOPTG('li', '.column1', '.DOPTG-admin').removeClass('item-selected');
        currGallery = 0;
        currImage = 0;
        doptgRemoveColumns(2);
        $jDOPTG('#gallery_id').val(0);
        doptgToggleMessage('show', DOPTG_LOAD);
        
        $jDOPTG.post(ajaxurl, {action: 'doptg_show_gallery_settings', 
                               gallery_id: $jDOPTG('#gallery_id').val(),
                               settings_id: 0}, function(data){
            var HeaderHTML = new Array(),
            json = $jDOPTG.parseJSON(data);

            HeaderHTML.push('<input type="button" name="DOPTG_gallery_submit" class="submit-style" onclick="doptgEditGallerySettings()" title="'+DOPTG_EDIT_GALLERIES_SUBMIT+'" value="'+DOPTG_SUBMIT+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help"><span>'+DOPTG_GALLERIES_EDIT_INFO_HELP+'</span></a>');

            $jDOPTG('.column-header', '.column2', '.DOPTG-admin').html(HeaderHTML.join(''));
            doptgSettingsForm(json, 2);

            doptgResize();
            doptgToggleMessage('hide', DOPTG_GALLERY_LOADED);
        });
    }
}

function doptgShowGallerySettings(){// Show gallery settings.
    if (clearClick){
        $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
        doptgRemoveColumns(3);
        doptgToggleMessage('show', DOPTG_LOAD);
        
        $jDOPTG.post(ajaxurl, {action: 'doptg_show_gallery_settings',
                               gallery_id: $jDOPTG('#gallery_id').val(),
                               settings_id: 0}, function(data){            
            var HeaderHTML = new Array(),
            json = $jDOPTG.parseJSON(data);
            
            HeaderHTML.push('<input type="button" name="DOPTG_gallery_submit" class="submit-style" onclick="doptgEditGallerySettings()" title="'+DOPTG_EDIT_GALLERY_SUBMIT+'" value="'+DOPTG_SUBMIT+'" />');
            HeaderHTML.push('<input type="button" name="DOPTG_gallery_delete" class="submit-style" onclick="doptgDeleteGallery('+$jDOPTG('#gallery_id').val()+')" title="'+DOPTG_DELETE_GALLERY_SUBMIT+'" value="'+DOPTG_DELETE+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_GALLERY_EDIT_INFO_HELP+'</span></a>');
            HeaderHTML.push('<input type="button" name="DOPTG_gallery_delete" class="submit-style right" onclick="doptgDefaultGallery()" title="'+DOPTG_DEFAULT+'" value="'+DOPTG_DEFAULT+'" />');
            HeaderHTML.push('<select name="DOPTG_gallery_predefined_settings" id="DOPTG_gallery_predefined_settings" class="select-style right">'+json['predefined_settings']+'</select>');
            
            $jDOPTG('.column-header', '.column3', '.DOPTG-admin').html(HeaderHTML.join(''));
            doptgSettingsForm(json, 3);
            
            doptgResize();
            doptgToggleMessage('hide', DOPTG_GALLERY_LOADED);
        });
    }
}

function doptgEditGallerySettings(){// Edit Gallery Settings.
    if (clearClick){
        doptgToggleMessage('show', DOPTG_SAVE);
        
        $jDOPTG.post(ajaxurl, {action:'doptg_edit_gallery_settings',
                               gallery_id: $jDOPTG('#gallery_id').val(),
                               name: $jDOPTG('#name').val(),
                               data_parse_method: $jDOPTG('#data_parse_method').val(),
                               width: $jDOPTG('#width').val(),
                               height: $jDOPTG('#height').val(),
                               bg_color: $jDOPTG('#bg_color').val(),
                               bg_alpha: $jDOPTG('#bg_alpha').val(),
                               images_order: $jDOPTG('#images_order').val(),
                               responsive_enabled: $jDOPTG('#responsive_enabled').is(':checked') ? 'true':'false',
                               thumbnails_position: $jDOPTG('#thumbnails_position').val(),
                               thumbnails_over_image: $jDOPTG('#thumbnails_over_image').is(':checked') ? 'true':'false',
                               thumbnails_bg_color: $jDOPTG('#thumbnails_bg_color').val(),
                               thumbnails_bg_alpha: $jDOPTG('#thumbnails_bg_alpha').val(),
                               thumbnails_spacing: $jDOPTG('#thumbnails_spacing').val(),
                               thumbnails_padding_top: $jDOPTG('#thumbnails_padding_top').val(),
                               thumbnails_padding_right: $jDOPTG('#thumbnails_padding_right').val(),
                               thumbnails_padding_bottom: $jDOPTG('#thumbnails_padding_bottom').val(),
                               thumbnails_padding_left: $jDOPTG('#thumbnails_padding_left').val(),
                               thumbnails_navigation: $jDOPTG('#thumbnails_navigation').val(),
                               thumbnail_width: $jDOPTG('#thumbnail_width').val(),
                               thumbnail_height: $jDOPTG('#thumbnail_height').val(),
                               thumbnail_width_mobile: $jDOPTG('#thumbnail_width_mobile').val(),
                               thumbnail_height_mobile: $jDOPTG('#thumbnail_height_mobile').val(),
                               thumbnail_alpha: $jDOPTG('#thumbnail_alpha').val(),
                               thumbnail_alpha_hover: $jDOPTG('#thumbnail_alpha_hover').val(),
                               thumbnail_alpha_selected: $jDOPTG('#thumbnail_alpha_selected').val(),
                               thumbnail_bg_color: $jDOPTG('#thumbnail_bg_color').val(),
                               thumbnail_bg_color_hover: $jDOPTG('#thumbnail_bg_color_hover').val(),
                               thumbnail_bg_color_selected: $jDOPTG('#thumbnail_bg_color_selected').val(),
                               thumbnail_border_size: $jDOPTG('#thumbnail_border_size').val(),
                               thumbnail_border_color: $jDOPTG('#thumbnail_border_color').val(),
                               thumbnail_border_color_hover: $jDOPTG('#thumbnail_border_color_hover').val(),
                               thumbnail_border_color_selected: $jDOPTG('#thumbnail_border_color_selected').val(),
                               thumbnail_padding_top: $jDOPTG('#thumbnail_padding_top').val(),
                               thumbnail_padding_right: $jDOPTG('#thumbnail_padding_right').val(),
                               thumbnail_padding_bottom: $jDOPTG('#thumbnail_padding_bottom').val(),
                               thumbnail_padding_left: $jDOPTG('#thumbnail_padding_left').val(),
                               image_bg_color: $jDOPTG('#image_bg_color').val(),
                               image_bg_alpha: $jDOPTG('#image_bg_alpha').val(),
                               image_display_type: $jDOPTG('#image_display_type').val(),
                               image_display_time: $jDOPTG('#image_display_time').val(),
                               image_margin_top: $jDOPTG('#image_margin_top').val(),
                               image_margin_right: $jDOPTG('#image_margin_right').val(),
                               image_margin_bottom: $jDOPTG('#image_margin_bottom').val(),
                               image_margin_left: $jDOPTG('#image_margin_left').val(),
                               image_padding_top: $jDOPTG('#image_padding_top').val(),
                               image_padding_right: $jDOPTG('#image_padding_right').val(),
                               image_padding_bottom: $jDOPTG('#image_padding_bottom').val(),
                               image_padding_left: $jDOPTG('#image_padding_left').val(),
                               navigation_enabled: $jDOPTG('#navigation_enabled').is(':checked') ? 'true':'false',
                               navigation_over_image: $jDOPTG('#navigation_over_image').is(':checked') ? 'true':'false',
                               navigation_touch_device_swipe_enabled: $jDOPTG('#navigation_touch_device_swipe_enabled').is(':checked') ? 'true':'false',
                               caption_width: $jDOPTG('#caption_width').val(),
                               caption_height: $jDOPTG('#caption_height').val(),
                               caption_title_color: $jDOPTG('#caption_title_color').val(),
                               caption_text_color: $jDOPTG('#caption_text_color').val(),
                               caption_bg_color: $jDOPTG('#caption_bg_color').val(),
                               caption_bg_alpha: $jDOPTG('#caption_bg_alpha').val(),
                               caption_position: $jDOPTG('#caption_position').val(),
                               caption_over_image: $jDOPTG('#caption_over_image').is(':checked') ? 'true':'false',
                               caption_scroll_scrub_color: $jDOPTG('#caption_scroll_scrub_color').val(),
                               caption_scroll_bg_color: $jDOPTG('#caption_scroll_bg_color').val(),
                               caption_margin_top: $jDOPTG('#caption_margin_top').val(),
                               caption_margin_right: $jDOPTG('#caption_margin_right').val(),
                               caption_margin_bottom: $jDOPTG('#caption_margin_bottom').val(),
                               caption_margin_left: $jDOPTG('#caption_margin_left').val(),
                               caption_padding_top: $jDOPTG('#caption_padding_top').val(),
                               caption_padding_right: $jDOPTG('#caption_padding_right').val(),
                               caption_padding_bottom: $jDOPTG('#caption_padding_bottom').val(),
                               caption_padding_left: $jDOPTG('#caption_padding_left').val(),
                               lightbox_enabled: $jDOPTG('#lightbox_enabled').is(':checked') ? 'true':'false',
                               lightbox_window_color: $jDOPTG('#lightbox_window_color').val(),
                               lightbox_window_alpha: $jDOPTG('#lightbox_window_alpha').val(),
                               lightbox_bg_color: $jDOPTG('#lightbox_bg_color').val(),
                               lightbox_bg_alpha: $jDOPTG('#lightbox_bg_alpha').val(),
                               lightbox_margin_top: $jDOPTG('#lightbox_margin_top').val(),
                               lightbox_margin_right: $jDOPTG('#lightbox_margin_right').val(),
                               lightbox_margin_bottom: $jDOPTG('#lightbox_margin_bottom').val(),
                               lightbox_margin_left: $jDOPTG('#lightbox_margin_left').val(),
                               lightbox_padding_top: $jDOPTG('#lightbox_padding_top').val(),
                               lightbox_padding_right: $jDOPTG('#lightbox_padding_right').val(),
                               lightbox_padding_bottom: $jDOPTG('#lightbox_padding_bottom').val(),
                               lightbox_padding_left: $jDOPTG('#lightbox_padding_left').val(),
                               lightbox_navigation_info_bg_color: $jDOPTG('#lightbox_navigation_info_bg_color').val(),
                               lightbox_navigation_info_text_color: $jDOPTG('#lightbox_navigation_info_text_color').val(),
                               lightbox_navigation_touch_device_swipe_enabled: $jDOPTG('#lightbox_navigation_touch_device_swipe_enabled').is(':checked') ? 'true':'false',
                               social_share_enabled: $jDOPTG('#social_share_enabled').is(':checked') ? 'true':'false',
                               tooltip_enabled: $jDOPTG('#tooltip_enabled').is(':checked') ? 'true':'false',
                               tooltip_bg_color: $jDOPTG('#tooltip_bg_color').val(),
                               tooltip_stroke_color: $jDOPTG('#tooltip_stroke_color').val(),
                               tooltip_text_color: $jDOPTG('#tooltip_text_color').val(),
                               slideshow: $jDOPTG('#slideshow').is(':checked') ? 'true':'false',
                               slideshow_autostart: $jDOPTG('#slideshow_autostart').is(':checked') ? 'true':'false',
                               slideshow_time: $jDOPTG('#slideshow_time').val(),
                               slideshow_loop: $jDOPTG('#slideshow_loop').is(':checked') ? 'true':'false',
                               auto_hide: $jDOPTG('#auto_hide').is(':checked') ? 'true':'false',
                               auto_hide_time: $jDOPTG('#auto_hide_time').val()}, function(data){
            if ($jDOPTG('#gallery_id').val() != '0'){
                $jDOPTG('.name', '#DOPTG-ID-'+$jDOPTG('#gallery_id').val()).html(doptgShortName($jDOPTG('#name').val(), 25));
                doptgToggleMessage('hide', DOPTG_EDIT_GALLERY_SUCCESS);
            }
            else{
                doptgToggleMessage('hide', DOPTG_EDIT_GALLERIES_SUCCESS);
            }
        });
    }
}

function doptgDefaultGallery(){// Add default settings to gallery.
    if (clearClick){
        if (confirm(DOPTG_EDIT_GALLERY_USE_DEFAULT_CONFIRMATION)){
            doptgToggleMessage('show', DOPTG_SAVE);
            
            $jDOPTG.post(ajaxurl, {action:'doptg_show_gallery_settings',
                                   gallery_id: 0,
                                   settings_id: $jDOPTG('#DOPTG_gallery_predefined_settings').val()}, function(data){
                data = $jDOPTG.parseJSON(data);
                
                $jDOPTG('#width').val(data['width']);
                $jDOPTG('#height').val(data['height']);
                $jDOPTG('#bg_color').val(data['bg_color']);
                $jDOPTG('#bg_alpha').val(data['bg_alpha']);
                $jDOPTG('#images_order').val(data['images_order']);
                data['responsive_enabled'] == 'true' ? $jDOPTG('#responsive_enabled').attr('checked', 'checked'):$jDOPTG('#responsive_enabled').removeAttr('checked');
                
                $jDOPTG('#thumbnails_position').val(data['thumbnails_position']);
                data['thumbnails_over_image'] == 'true' ? $jDOPTG('#thumbnails_over_image').attr('checked', 'checked'):$jDOPTG('#thumbnails_over_image').removeAttr('checked');
                $jDOPTG('#thumbnails_bg_color').val(data['thumbnails_bg_color']);
                $jDOPTG('#thumbnails_bg_alpha').val(data['thumbnails_bg_alpha']);
                $jDOPTG('#thumbnails_spacing').val(data['thumbnails_spacing']);
                $jDOPTG('#thumbnails_padding_top').val(data['thumbnails_padding_top']);
                $jDOPTG('#thumbnails_padding_right').val(data['thumbnails_padding_right']);
                $jDOPTG('#thumbnails_padding_bottom').val(data['thumbnails_padding_bottom']);
                $jDOPTG('#thumbnails_padding_left').val(data['thumbnails_padding_left']);     
                
                $jDOPTG('#thumbnails_navigation').val(data['thumbnails_navigation']); 
                $jDOPTG('#thumbnails_navigation_prev_image').html('<img src="'+DOPTG_plugin_url+data['thumbnails_navigation_prev']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#thumbnails_navigation_prev_hover_image').html('<img src="'+DOPTG_plugin_url+data['thumbnails_navigation_prev_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#thumbnails_navigation_next_image').html('<img src="'+DOPTG_plugin_url+data['thumbnails_navigation_next']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#thumbnails_navigation_next_hover_image').html('<img src="'+DOPTG_plugin_url+data['thumbnails_navigation_next_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');           
                
                $jDOPTG('#thumbnail_loader_image').html('<img src="'+DOPTG_plugin_url+data['thumbnail_loader']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#thumbnail_width').val(data['thumbnail_width']);
                $jDOPTG('#thumbnail_height').val(data['thumbnail_height']);
                $jDOPTG('#thumbnail_width_mobile').val(data['thumbnail_width_mobile']);
                $jDOPTG('#thumbnail_height_mobile').val(data['thumbnail_height_mobile']);
                $jDOPTG('#thumbnail_alpha').val(data['thumbnail_alpha']);
                $jDOPTG('#thumbnail_alpha_hover').val(data['thumbnail_alpha_hover']);
                $jDOPTG('#thumbnail_alpha_selected').val(data['thumbnail_alpha_selected']);
                $jDOPTG('#thumbnail_bg_color').val(data['thumbnail_bg_color']);
                $jDOPTG('#thumbnail_bg_color_hover').val(data['thumbnail_bg_color_hover']);
                $jDOPTG('#thumbnail_bg_color_selected').val(data['thumbnail_bg_color_selected']);
                $jDOPTG('#thumbnail_border_size').val(data['thumbnail_border_size']);
                $jDOPTG('#thumbnail_border_color').val(data['thumbnail_border_color']);
                $jDOPTG('#thumbnail_border_color_hover').val(data['thumbnail_border_color_hover']);
                $jDOPTG('#thumbnail_border_color_selected').val(data['thumbnail_border_color_selected']);
                $jDOPTG('#thumbnail_padding_top').val(data['thumbnail_padding_top']);
                $jDOPTG('#thumbnail_padding_right').val(data['thumbnail_padding_right']);
                $jDOPTG('#thumbnail_padding_bottom').val(data['thumbnail_padding_bottom']);
                $jDOPTG('#thumbnail_padding_left').val(data['thumbnail_padding_left']);
                
                $jDOPTG('#image_loader_image').html('<img src="'+DOPTG_plugin_url+data['image_loader']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#image_bg_color').val(data['image_bg_color']);
                $jDOPTG('#image_bg_alpha').val(data['image_bg_alpha']);
                $jDOPTG('#image_display_type').val(data['image_display_type']);
                $jDOPTG('#image_display_time').val(data['image_display_time']);
                $jDOPTG('#image_margin_top').val(data['image_margin_top']);
                $jDOPTG('#image_margin_right').val(data['image_margin_right']);
                $jDOPTG('#image_margin_bottom').val(data['image_margin_bottom']);
                $jDOPTG('#image_margin_left').val(data['image_margin_left']);
                $jDOPTG('#image_padding_top').val(data['image_padding_top']);
                $jDOPTG('#image_padding_right').val(data['image_padding_right']);
                $jDOPTG('#image_padding_bottom').val(data['image_padding_bottom']);
                $jDOPTG('#image_padding_left').val(data['image_padding_left']);
                
                data['navigation_enabled'] == 'true' ? $jDOPTG('#navigation_enabled').attr('checked', 'checked'):$jDOPTG('#navigation_enabled').removeAttr('checked');
                data['navigation_over_image'] == 'true' ? $jDOPTG('#navigation_over_image').attr('checked', 'checked'):$jDOPTG('#navigation_over_image').removeAttr('checked');
                $jDOPTG('#navigation_prev_image').html('<img src="'+DOPTG_plugin_url+data['navigation_prev']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#navigation_prev_hover_image').html('<img src="'+DOPTG_plugin_url+data['navigation_prev_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#navigation_next_image').html('<img src="'+DOPTG_plugin_url+data['navigation_next']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#navigation_next_hover_image').html('<img src="'+DOPTG_plugin_url+data['navigation_next_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#navigation_lightbox_image').html('<img src="'+DOPTG_plugin_url+data['navigation_lightbox']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#navigation_lightbox_hover_image').html('<img src="'+DOPTG_plugin_url+data['navigation_lightbox_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                data['navigation_touch_device_swipe_enabled'] == 'true' ? $jDOPTG('#navigation_touch_device_swipe_enabled').attr('checked', 'checked'):$jDOPTG('#navigation_touch_device_swipe_enabled').removeAttr('checked');
                   
                $jDOPTG('#caption_width').val(data['caption_width']);
                $jDOPTG('#caption_height').val(data['caption_height']);
                $jDOPTG('#caption_title_color').val(data['caption_title_color']);
                $jDOPTG('#caption_text_color').val(data['caption_text_color']);
                $jDOPTG('#caption_bg_color').val(data['caption_bg_color']);
                $jDOPTG('#caption_bg_alpha').val(data['caption_bg_alpha']);
                $jDOPTG('#caption_position').val(data['caption_position']);
                data['caption_over_image'] == 'true' ? $jDOPTG('#caption_over_image').attr('checked', 'checked'):$jDOPTG('#caption_over_image').removeAttr('checked');
                $jDOPTG('#caption_scroll_scrub_color').val(data['caption_scroll_scrub_color']);
                $jDOPTG('#caption_scroll_bg_color').val(data['caption_scroll_bg_color']);
                $jDOPTG('#caption_margin_top').val(data['caption_margin_top']);
                $jDOPTG('#caption_margin_right').val(data['caption_margin_right']);
                $jDOPTG('#caption_margin_bottom').val(data['caption_margin_bottom']);
                $jDOPTG('#caption_margin_left').val(data['caption_margin_left']);
                $jDOPTG('#caption_padding_top').val(data['caption_padding_top']);
                $jDOPTG('#caption_padding_right').val(data['caption_padding_right']);
                $jDOPTG('#caption_padding_bottom').val(data['caption_padding_bottom']);
                $jDOPTG('#caption_padding_left').val(data['caption_padding_left']);
                
                data['lightbox_enabled'] == 'true' ? $jDOPTG('#lightbox_enabled').attr('checked', 'checked'):$jDOPTG('#lightbox_enabled').removeAttr('checked');
                $jDOPTG('#lightbox_window_color').val(data['lightbox_window_color']);
                $jDOPTG('#lightbox_window_alpha').val(data['lightbox_window_alpha']);
                $jDOPTG('#lightbox_loader_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_loader']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_bg_color').val(data['lightbox_bg_color']);
                $jDOPTG('#lightbox_bg_alpha').val(data['lightbox_bg_alpha']);
                $jDOPTG('#lightbox_margin_top').val(data['lightbox_margin_top']);
                $jDOPTG('#lightbox_margin_right').val(data['lightbox_margin_right']);
                $jDOPTG('#lightbox_margin_bottom').val(data['lightbox_margin_bottom']);
                $jDOPTG('#lightbox_margin_left').val(data['lightbox_margin_left']);
                $jDOPTG('#lightbox_padding_top').val(data['lightbox_padding_top']);
                $jDOPTG('#lightbox_padding_right').val(data['lightbox_padding_right']);
                $jDOPTG('#lightbox_padding_bottom').val(data['lightbox_padding_bottom']);
                $jDOPTG('#lightbox_padding_left').val(data['lightbox_padding_left']);
                                
                $jDOPTG('#lightbox_navigation_prev_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_prev']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_prev_hover_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_prev_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_next_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_next']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_next_hover_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_next_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_close_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_close']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_close_hover_image').html('<img src="'+DOPTG_plugin_url+data['lightbox_navigation_close_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#lightbox_navigation_info_bg_color').val(data['lightbox_navigation_info_bg_color']);
                $jDOPTG('#lightbox_navigation_info_text_color').val(data['lightbox_navigation_info_text_color']);
                data['lightbox_navigation_touch_device_swipe_enabled'] == 'true' ? $jDOPTG('#lightbox_navigation_touch_device_swipe_enabled').attr('checked', 'checked'):$jDOPTG('#lightbox_navigation_touch_device_swipe_enabled').removeAttr('checked');
                
                data['social_share_enabled'] == 'true' ? $jDOPTG('#social_share_enabled').attr('checked', 'checked'):$jDOPTG('#social_share_enabled').removeAttr('checked');
                $jDOPTG('#social_share_image').html('<img src="'+DOPTG_plugin_url+data['social_share']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#social_share_lightbox_image').html('<img src="'+DOPTG_plugin_url+data['social_share_lightbox']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                
                data['tooltip_enabled'] == 'true' ? $jDOPTG('#tooltip_enabled').attr('checked', 'checked'):$jDOPTG('#tooltip_enabled').removeAttr('checked');
                $jDOPTG('#tooltip_bg_color').val(data['tooltip_bg_color']);
                $jDOPTG('#tooltip_stroke_color').val(data['tooltip_stroke_color']);
                $jDOPTG('#tooltip_text_color').val(data['tooltip_text_color']);
                
                data['slideshow'] == 'true' ? $jDOPTG('#slideshow').attr('checked', 'checked'):$jDOPTG('#slideshow').removeAttr('checked');
                $jDOPTG('#slideshow_time').val(data['slideshow_time']);
                data['slideshow_autostart'] == 'true' ? $jDOPTG('#slideshow_autostart').attr('checked', 'checked'):$jDOPTG('#slideshow_autostart').removeAttr('checked');
                data['slideshow_loop'] == 'true' ? $jDOPTG('#slideshow_loop').attr('checked', 'checked'):$jDOPTG('#slideshow_loop').removeAttr('checked');
                $jDOPTG('#slideshow_play_image').html('<img src="'+DOPTG_plugin_url+data['slideshow_play']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#slideshow_play_hover_image').html('<img src="'+DOPTG_plugin_url+data['slideshow_play_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#slideshow_pause_image').html('<img src="'+DOPTG_plugin_url+data['slideshow_pause']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                $jDOPTG('#slideshow_pause_hover_image').html('<img src="'+DOPTG_plugin_url+data['slideshow_pause_hover']+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                                
                data['auto_hide'] == 'true' ? $jDOPTG('#auto_hide').attr('checked', 'checked'):$jDOPTG('#auto_hide').removeAttr('checked');
                $jDOPTG('#auto_hide_time').val(data['auto_hide_time']);
    
                $jDOPTG('#bg_color').removeAttr('style').css({'background-color': '#'+data['bg_color'],
                                                              'color': doptgIdealTextColor(data['bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnails_bg_color').removeAttr('style').css({'background-color': '#'+data['thumbnails_bg_color'],
                                                                         'color': doptgIdealTextColor(data['thumbnails_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_bg_color').removeAttr('style').css({'background-color': '#'+data['thumbnail_bg_color'],
                                                                        'color': doptgIdealTextColor(data['thumbnail_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_bg_color_hover').removeAttr('style').css({'background-color': '#'+data['thumbnail_bg_color_hover'],
                                                                              'color': doptgIdealTextColor(data['thumbnail_bg_color_hover']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_bg_color_selected').removeAttr('style').css({'background-color': '#'+data['thumbnail_bg_color_selected'],
                                                                                 'color': doptgIdealTextColor(data['thumbnail_bg_color_selected']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_border_color').removeAttr('style').css({'background-color': '#'+data['thumbnail_border_color'],
                                                                            'color': doptgIdealTextColor(data['thumbnail_border_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_border_color_hover').removeAttr('style').css({'background-color': '#'+data['thumbnail_border_color_hover'],
                                                                                  'color': doptgIdealTextColor(data['thumbnail_border_color_hover']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#thumbnail_border_color_selected').removeAttr('style').css({'background-color': '#'+data['thumbnail_border_color_selected'],
                                                                                     'color': doptgIdealTextColor(data['thumbnail_border_color_selected']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#image_bg_color').removeAttr('style').css({'background-color': '#'+data['image_bg_color'],
                                                                    'color': doptgIdealTextColor(data['image_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#caption_title_color').removeAttr('style').css({'background-color': '#'+data['caption_title_color'],
                                                                         'color': doptgIdealTextColor(data['caption_title_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#caption_text_color').removeAttr('style').css({'background-color': '#'+data['caption_text_color'],
                                                                        'color': doptgIdealTextColor(data['caption_text_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#caption_bg_color').removeAttr('style').css({'background-color': '#'+data['caption_bg_color'],
                                                                      'color': doptgIdealTextColor(data['caption_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#caption_scroll_scrub_color').removeAttr('style').css({'background-color': '#'+data['caption_scroll_scrub_color'],
                                                                                'color': doptgIdealTextColor(data['caption_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#caption_scroll_bg_color').removeAttr('style').css({'background-color': '#'+data['caption_scroll_bg_color'],
                                                                             'color': doptgIdealTextColor(data['caption_scroll_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#lightbox_window_color').removeAttr('style').css({'background-color': '#'+data['lightbox_window_color'],
                                                                           'color': doptgIdealTextColor(data['lightbox_window_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#lightbox_bg_color').removeAttr('style').css({'background-color': '#'+data['lightbox_bg_color'],
                                                                       'color': doptgIdealTextColor(data['lightbox_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#lightbox_navigation_info_bg_color').removeAttr('style').css({'background-color': '#'+data['lightbox_navigation_info_bg_color'],
                                                                                       'color': doptgIdealTextColor(data['lightbox_navigation_info_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#lightbox_navigation_info_text_color').removeAttr('style').css({'background-color': '#'+data['lightbox_navigation_info_text_color'],
                                                                                         'color': doptgIdealTextColor(data['lightbox_navigation_info_text_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#tooltip_bg_color').removeAttr('style').css({'background-color': '#'+data['tooltip_bg_color'],
                                                                      'color': doptgIdealTextColor(data['tooltip_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#tooltip_stroke_color').removeAttr('style').css({'background-color': '#'+data['tooltip_stroke_color'],
                                                                          'color': doptgIdealTextColor(data['tooltip_stroke_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPTG('#tooltip_text_color').removeAttr('style').css({'background-color': '#'+data['tooltip_text_color'],
                                                                        'color': doptgIdealTextColor(data['tooltip_text_color']) == 'white' ? '#ffffff':'#0000000'});
                
                $jDOPTG.post(ajaxurl, {action:'doptg_edit_gallery_settings',
                                       gallery_id: $jDOPTG('#gallery_id').val(),
                                       name: $jDOPTG('#name').val(),
                                       data_parse_method: $jDOPTG('#data_parse_method').val(),
                                       width: $jDOPTG('#width').val(),
                                       height: $jDOPTG('#height').val(),
                                       bg_color: $jDOPTG('#bg_color').val(),
                                       bg_alpha: $jDOPTG('#bg_alpha').val(),
                                       images_order: $jDOPTG('#images_order').val(),
                                       responsive_enabled: $jDOPTG('#responsive_enabled').is(':checked') ? 'true':'false',
                                       thumbnails_position: $jDOPTG('#thumbnails_position').val(),
                                       thumbnails_over_image: $jDOPTG('#thumbnails_over_image').is(':checked') ? 'true':'false',
                                       thumbnails_bg_color: $jDOPTG('#thumbnails_bg_color').val(),
                                       thumbnails_bg_alpha: $jDOPTG('#thumbnails_bg_alpha').val(),
                                       thumbnails_spacing: $jDOPTG('#thumbnails_spacing').val(),
                                       thumbnails_padding_top: $jDOPTG('#thumbnails_padding_top').val(),
                                       thumbnails_padding_right: $jDOPTG('#thumbnails_padding_right').val(),
                                       thumbnails_padding_bottom: $jDOPTG('#thumbnails_padding_bottom').val(),
                                       thumbnails_padding_left: $jDOPTG('#thumbnails_padding_left').val(),
                                       thumbnails_navigation: $jDOPTG('#thumbnails_navigation').val(),
                                       thumbnails_navigation_prev: data['thumbnails_navigation_prev'],
                                       thumbnails_navigation_prev_hover: data['thumbnails_navigation_prev_hover'],
                                       thumbnails_navigation_next: data['thumbnails_navigation_next'],
                                       thumbnails_navigation_next_hover: data['thumbnails_navigation_next_hover'],
                                       thumbnail_loader: data['thumbnail_loader'],
                                       thumbnail_width: $jDOPTG('#thumbnail_width').val(),
                                       thumbnail_height: $jDOPTG('#thumbnail_height').val(),
                                       thumbnail_width_mobile: $jDOPTG('#thumbnail_width_mobile').val(),
                                       thumbnail_height_mobile: $jDOPTG('#thumbnail_height_mobile').val(),
                                       thumbnail_alpha: $jDOPTG('#thumbnail_alpha').val(),
                                       thumbnail_alpha_hover: $jDOPTG('#thumbnail_alpha_hover').val(),
                                       thumbnail_alpha_selected: $jDOPTG('#thumbnail_alpha_selected').val(),
                                       thumbnail_bg_color: $jDOPTG('#thumbnail _bg_color').val(),
                                       thumbnail_bg_color_hover: $jDOPTG('#thumbnail_bg_color_hover').val(),
                                       thumbnail_bg_color_selected: $jDOPTG('#thumbnail_bg_color_selected').val(),
                                       thumbnail_border_size: $jDOPTG('#thumbnail_border_size').val(),
                                       thumbnail_border_color: $jDOPTG('#thumbnail_border_color').val(),
                                       thumbnail_border_color_hover: $jDOPTG('#thumbnail_border_color_hover').val(),
                                       thumbnail_border_color_selected: $jDOPTG('#thumbnail_border_color_selected').val(),
                                       thumbnail_padding_top: $jDOPTG('#thumbnail_padding_top').val(),
                                       thumbnail_padding_right: $jDOPTG('#thumbnail_padding_right').val(),
                                       thumbnail_padding_bottom: $jDOPTG('#thumbnail_padding_bottom').val(),
                                       thumbnail_padding_left: $jDOPTG('#thumbnail_padding_left').val(),
                                       image_loader: data['image_loader'],
                                       image_bg_color: $jDOPTG('#image_bg_color').val(),
                                       image_bg_alpha: $jDOPTG('#image_bg_alpha').val(),
                                       image_display_type: $jDOPTG('#image_display_type').val(),
                                       image_display_time: $jDOPTG('#image_display_time').val(),
                                       image_margin_top: $jDOPTG('#image_margin_top').val(),
                                       image_margin_right: $jDOPTG('#image_margin_right').val(),
                                       image_margin_bottom: $jDOPTG('#image_margin_bottom').val(),
                                       image_margin_left: $jDOPTG('#image_margin_left').val(),
                                       image_padding_top: $jDOPTG('#image_padding_top').val(),
                                       image_padding_right: $jDOPTG('#image_padding_right').val(),
                                       image_padding_bottom: $jDOPTG('#image_padding_bottom').val(),
                                       image_padding_left: $jDOPTG('#image_padding_left').val(),
                                       navigation_enabled: $jDOPTG('#navigation_enabled').is(':checked') ? 'true':'false',
                                       navigation_over_image: $jDOPTG('#navigation_over_image').is(':checked') ? 'true':'false',
                                       navigation_prev: data['navigation_prev'],
                                       navigation_prev_hover: data['navigation_prev_hover'],
                                       navigation_next: data['navigation_next'],
                                       navigation_next_hover: data['navigation_next_hover'],
                                       navigation_lightbox: data['navigation_lightbox'],
                                       navigation_lightbox_hover: data['navigation_lightbox_hover'],
                                       navigation_touch_device_swipe_enabled: $jDOPTG('#navigation_touch_device_swipe_enabled').is(':checked') ? 'true':'false',
                                       caption_width: $jDOPTG('#caption_width').val(),
                                       caption_height: $jDOPTG('#caption_height').val(),
                                       caption_title_color: $jDOPTG('#caption_title_color').val(),
                                       caption_text_color: $jDOPTG('#caption_text_color').val(),
                                       caption_bg_color: $jDOPTG('#caption_bg_color').val(),
                                       caption_bg_alpha: $jDOPTG('#caption_bg_alpha').val(),
                                       caption_position: $jDOPTG('#caption_position').val(),
                                       caption_over_image: $jDOPTG('#caption_over_image').is(':checked') ? 'true':'false',
                                       caption_scroll_scrub_color: $jDOPTG('#caption_scroll_scrub_color').val(),
                                       caption_scroll_bg_color: $jDOPTG('#caption_scroll_bg_color').val(),
                                       caption_margin_top: $jDOPTG('#caption_margin_top').val(),
                                       caption_margin_right: $jDOPTG('#caption_margin_right').val(),
                                       caption_margin_bottom: $jDOPTG('#caption_margin_bottom').val(),
                                       caption_margin_left: $jDOPTG('#caption_margin_left').val(),
                                       caption_padding_top: $jDOPTG('#caption_padding_top').val(),
                                       caption_padding_right: $jDOPTG('#caption_padding_right').val(),
                                       caption_padding_bottom: $jDOPTG('#caption_padding_bottom').val(),
                                       caption_padding_left: $jDOPTG('#caption_padding_left').val(),
                                       lightbox_enabled: $jDOPTG('#lightbox_enabled').is(':checked') ? 'true':'false',
                                       lightbox_window_color: $jDOPTG('#lightbox_window_color').val(),
                                       lightbox_window_alpha: $jDOPTG('#lightbox_window_alpha').val(),
                                       lightbox_loader: data['lightbox_loader'],
                                       lightbox_bg_color: $jDOPTG('#lightbox_bg_color').val(),
                                       lightbox_bg_alpha: $jDOPTG('#lightbox_bg_alpha').val(),
                                       lightbox_margin_top: $jDOPTG('#lightbox_margin_top').val(),
                                       lightbox_margin_right: $jDOPTG('#lightbox_margin_right').val(),
                                       lightbox_margin_bottom: $jDOPTG('#lightbox_margin_bottom').val(),
                                       lightbox_margin_left: $jDOPTG('#lightbox_margin_left').val(),
                                       lightbox_padding_top: $jDOPTG('#lightbox_padding_top').val(),
                                       lightbox_padding_right: $jDOPTG('#lightbox_padding_right').val(),
                                       lightbox_padding_bottom: $jDOPTG('#lightbox_padding_bottom').val(),
                                       lightbox_padding_left: $jDOPTG('#lightbox_padding_left').val(),
                                       lightbox_navigation_prev: data['lightbox_navigation_prev'],
                                       lightbox_navigation_prev_hover: data['lightbox_navigation_prev_hover'],
                                       lightbox_navigation_next: data['lightbox_navigation_next'],
                                       lightbox_navigation_next_hover: data['lightbox_navigation_next_hover'],
                                       lightbox_navigation_close: data['lightbox_navigation_close'],
                                       lightbox_navigation_close_hover: data['lightbox_navigation_close_hover'],
                                       lightbox_navigation_info_bg_color: $jDOPTG('#lightbox_navigation_info_bg_color').val(),
                                       lightbox_navigation_info_text_color: $jDOPTG('#lightbox_navigation_info_text_color').val(),
                                       lightbox_navigation_touch_device_swipe_enabled: $jDOPTG('#lightbox_navigation_touch_device_swipe_enabled').is(':checked') ? 'true':'false',
                                       social_share_enabled: $jDOPTG('#social_share_enabled').is(':checked') ? 'true':'false',
                                       social_share: data['social_share'],
                                       social_share_lightbox: data['social_share_lightbox'],                                       
                                       tooltip_enabled: $jDOPTG('#tooltip_enabled').is(':checked') ? 'true':'false',
                                       tooltip_bg_color: $jDOPTG('#tooltip_bg_color').val(),
                                       tooltip_stroke_color: $jDOPTG('#tooltip_stroke_color').val(),
                                       tooltip_text_color: $jDOPTG('#tooltip_text_color').val(),
                                       slideshow: $jDOPTG('#slideshow').is(':checked') ? 'true':'false',
                                       slideshow_time: $jDOPTG('#slideshow_time').val(),
                                       slideshow_autostart: $jDOPTG('#slideshow_autostart').is(':checked') ? 'true':'false',
                                       slideshow_loop: $jDOPTG('#slideshow_loop').is(':checked') ? 'true':'false',
                                       slideshow_play: data['slideshow_play'],
                                       slideshow_play_hover: data['slideshow_play_hover'],
                                       slideshow_pause: data['slideshow_pause'],
                                       slideshow_pause_hover: data['slideshow_pause_hover'],
                                       auto_hide: $jDOPTG('#auto_hide').is(':checked') ? 'true':'false',
                                       auto_hide_time: $jDOPTG('#auto_hide_time').val()}, function(data){
                    doptgToggleMessage('hide', DOPTG_EDIT_GALLERY_SUCCESS);
                });
            });
        }
    }
}

function doptgDeleteGallery(id){// Delete gallery
    if (clearClick){
        if (confirm(DOPTG_DELETE_GALLERY_CONFIRMATION)){
            doptgToggleMessage('show', DOPTG_DELETE_GALLERY_SUBMITED);
            
            $jDOPTG.post(ajaxurl, {action:'doptg_delete_gallery', id:id}, function(data){
                doptgRemoveColumns(2);
                $jDOPTG('#DOPTG-ID-'+id).stop(true, true).animate({'opacity':0}, 600, function(){
                    $jDOPTG(this).remove();
                    if (data == '0'){
                        $jDOPTG('.column-content', '.column1', '.DOPTG-admin').html('<ul><li class="no-data">'+DOPTG_NO_GALLERIES+'</li></ul>');
                    }
                    doptgToggleMessage('hide', DOPTG_DELETE_GALLERY_SUCCESS);
                });
            });
        }
    }
}

function doptgGalleriesEvents(){// Init Gallery Events.
    $jDOPTG('li', '.column1', '.DOPTG-admin').click(function(){
        if (clearClick){
            var id = $jDOPTG(this).attr('id').split('-')[2];
            
            if (currGallery != id){
                currGallery = id;
                $jDOPTG('li', '.column1', '.DOPTG-admin').removeClass('item-selected');
                $jDOPTG(this).addClass('item-selected');
                doptgShowImages(id);
            }
        }
    });
}

// Images

function doptgShowImages(gallery_id){// Show Images List.
    if (clearClick){
        $jDOPTG('#gallery_id').val(gallery_id);
        doptgRemoveColumns(2);
        doptgToggleMessage('show', DOPTG_LOAD);
        
        $jDOPTG.post(ajaxurl, {action:'doptg_show_images', gallery_id:gallery_id}, function(data){
            var HeaderHTML = new Array();
            HeaderHTML.push('<div class="add-button">');
            HeaderHTML.push('    <a href="javascript:doptgAddImages()" title="'+DOPTG_ADD_IMAGE_SUBMIT+'"></a>');
            HeaderHTML.push('</div>');
            HeaderHTML.push('<div class="edit-button">');
            HeaderHTML.push('    <a href="javascript:doptgShowGallerySettings()" title="'+DOPTG_EDIT_GALLERY_SUBMIT+'"></a>');
            HeaderHTML.push('</div>');
            HeaderHTML.push('<div class="actions-container">');
            HeaderHTML.push('   <select name="DOPTG-gallery-actions" id="DOPTG-gallery-actions">');
            HeaderHTML.push('       <option value="">- '+DOPTG_SELECT_ACTION+' -</option>');
            HeaderHTML.push('       <option value="delete">'+DOPTG_DELETE+'</option>');
            HeaderHTML.push('       <option value="enable">'+DOPTG_ENABLE+'</option>');
            HeaderHTML.push('       <option value="disable">'+DOPTG_DISABLE+'</option>');
            HeaderHTML.push('   </select>');
            HeaderHTML.push('   <input type="button" name="DOPTG_image_delete" class="submit-style" onclick="doptgEditImages()" value="'+DOPTG_APPLY+'">');
            HeaderHTML.push('</div>');
            HeaderHTML.push('<a href="javascript:void()" class="header-help"><span>'+DOPTG_GALLERY_EDIT_HELP+'</span></a>');
            
            $jDOPTG('.column-header', '.column2', '.DOPTG-admin').html(HeaderHTML.join(''));
            $jDOPTG('.column-content', '.column2', '.DOPTG-admin').html(data);
            $jDOPTG('.column-content', '.column2', '.DOPTG-admin').DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
            doptgImagesEvents();
            doptgToggleMessage('hide', DOPTG_IMAGES_LOADED);
        });
    }
}

function doptgImagesEvents(){// Init Images Events.
    $jDOPTG('.item-image', '.column2', '.DOPTG-admin').each(function(){
        var id = $jDOPTG(this).attr('id').split('-')[3];
        
        $jDOPTG(this).prepend('<div class="checkbox-container"><input type="checkbox" name="DOPTG-image-ID-check-'+id+'" id="DOPTG-image-ID-check-'+id+'" /></div>');
    });
    
    $jDOPTG('.DOPTG-admin .column2 .item-image input').unbind('click');
    $jDOPTG('.DOPTG-admin .column2 .item-image input').bind('click', function(){
        clearClick = false;
        
        setTimeout(function(){
            clearClick = true;
        }, 10);
    });
    
    $jDOPTG('.item-image', '.column2', '.DOPTG-admin').unbind('click');
    $jDOPTG('.item-image', '.column2', '.DOPTG-admin').bind('click', function(){
        var id = $jDOPTG(this).attr('id').split('-')[3];
        
        if (currImage != id && clearClick){
            $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
            $jDOPTG(this).addClass('item-image-selected');
            doptgShowImage(id);
        }
    });

    $jDOPTG('ul', '.column2').sortable({opacity:0.6, cursor:'move', update:function(){
        if (clearClick){
            var data = '';
            
            doptgToggleMessage('show', DOPTG_SORT_IMAGES_SUBMITED);
            $jDOPTG('li', '.column2', '.DOPTG-admin').each(function(){
                data += $jDOPTG(this).attr('id').split('-')[3]+',';
            });
            $jDOPTG.post(ajaxurl, {action:'doptg_sort_images', gallery_id:$jDOPTG('#gallery_id').val(), data:data}, function(data){
                doptgToggleMessage('hide', DOPTG_SORT_IMAGES_SUCCESS);
            });
        }
    },
    stop:function(){
        $jDOPTG('li', '.column2').removeAttr('style');
    }});
}

function doptgAddImages(){// Add Image/Images.
    if (clearClick){
        $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
        doptgRemoveColumns(3);
        
        var uploadifyHTML = new Array(), HeaderHTML = new Array();
        HeaderHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_ADD_IMAGES_HELP+'</span></a>');

        uploadifyHTML.push('<h3 class="settings">'+DOPTG_ADD_IMAGE_WP_UPLOAD+'</h3>');
        uploadifyHTML.push('<input name="doptg_wp_image" id="doptg_wp_image" type="button" value="'+DOPTG_SELECT_IMAGES+'" class="select-images" />');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_ADD_IMAGES_HELP_WP+'</span></a><br class="DOPTG-clear" />');

        uploadifyHTML.push('<h3 class="settings">'+DOPTG_ADD_IMAGE_SIMPLE_UPLOAD+'</h3>');
        uploadifyHTML.push('<form action="'+DOPTG_plugin_url+'libraries/php/upload.php?path='+DOPTG_plugin_abs+'" method="post" enctype="multipart/form-data" id="doptg_ajax_upload_form" name="doptg_ajax_upload_form" target="doptg_upload_target" onsubmit="doptgUploadImage()" >');
        uploadifyHTML.push('    <input name="doptg_image" type="file" onchange="$jDOPTG(\'#doptg_ajax_upload_form\').submit(); return false;" style="margin:5px 0 0 10px"; />');
        uploadifyHTML.push('    <a href="javascript:void()" class="header-help last"><span>'+DOPTG_ADD_IMAGES_HELP_AJAX+'</span></a><br class="DOPTG-clear" />');
        uploadifyHTML.push('</form>');
        uploadifyHTML.push('<iframe id="doptg_upload_target" name="doptg_upload_target" src="javascript:void(0)" style="display: none;"></iframe>');
        
        uploadifyHTML.push('<h3 class="settings">'+DOPTG_ADD_IMAGE_MULTIPLE_UPLOAD+'</h3>');
        uploadifyHTML.push('<div class="uploadifyContainer" style="float:left; margin-top:5px;">');
        uploadifyHTML.push('    <div><input type="file" name="uploadify" id="uploadify" style="width:100px;" /></div>');
        uploadifyHTML.push('    <div id="fileQueue"></div>');
        uploadifyHTML.push('</div>');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_ADD_IMAGES_HELP_UPLOADIFY+'</span></a><br class="DOPTG-clear" />');  
        
        uploadifyHTML.push('<h3 class="settings">'+DOPTG_ADD_IMAGE_FTP_UPLOAD+'</h3>');
        uploadifyHTML.push('<input name="doptg_ftp_image" id="doptg_ftp_image" type="button" value="'+DOPTG_SELECT_FTP_IMAGES+'" class="select-images" />');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_ADD_IMAGES_HELP_FTP+'</span></a><br class="DOPTG-clear" />');

        $jDOPTG('.column-header', '.column3', '.DOPTG-admin').html(HeaderHTML.join(''));
        $jDOPTG('.column-content', '.column3', '.DOPTG-admin').html(uploadifyHTML.join(''));
        
        // Add Images from WP Media.
        
        $jDOPTG('#doptg_wp_image').click(function(){
            if (clearClick){
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            }
            return false;                
        });

        window.send_to_editor = function(html){
            doptgToggleMessage('show', DOPTG_ADD_IMAGE_SUBMITED);

            setTimeout(function(){
                doptgResize();
            }, 100);
            
            $jDOPTG.post(ajaxurl, {action:'doptg_add_image_wp', gallery_id:$jDOPTG('#gallery_id').val(), image_url:$jDOPTG('img', html).attr('src')}, function(data){
                var imageID = data.split(';;;')[0],
                imageName = data.split(';;;')[1];
                
                if ($jDOPTG('ul', '.column2', '.DOPTG-admin').html() == '<li class="no-data">'+DOPTG_NO_IMAGES+'</li>'){
                    $jDOPTG('ul', '.column2', '.DOPTG-admin').html('<li class="item-image" id="DOPTG-image-ID-'+imageID+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                    doptgImagesEvents();
                }
                else{
                    $jDOPTG('ul', '.column2', '.DOPTG-admin').append('<li class="item-image" id="DOPTG-image-ID-'+imageID+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                }

                doptgResize();
                $jDOPTG('#DOPTG-image-ID-'+imageID).DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                $jDOPTG('#DOPTG-image-ID-'+imageID).prepend('<div class="checkbox-container"><input type="checkbox" name="DOPTG-image-ID-check-'+imageID+'" id="DOPTG-image-ID-check-'+imageID+'" /></div>');
    
                $jDOPTG('#DOPTG-image-ID-'+imageID+' input').unbind('click');
                $jDOPTG('#DOPTG-image-ID-'+imageID+' input').bind('click', function(){
                    clearClick = false;

                    setTimeout(function(){
                        clearClick = true;
                    }, 10);
                });
    
                $jDOPTG('#DOPTG-image-ID-'+imageID).unbind('click');
                $jDOPTG('#DOPTG-image-ID-'+imageID).bind('click', function(){
                    var id = $jDOPTG(this).attr('id').split('-')[3];

                    if (currImage != id && clearClick){
                        $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
                        $jDOPTG(this).addClass('item-image-selected');
                        doptgShowImage(id);
                    }
                });
                
                doptgToggleMessage('hide', DOPTG_ADD_IMAGE_SUCCESS);
            });
            
            tb_remove();
        }

        // Add Images width Uploadify.
        
        $jDOPTG('#uploadify').uploadify({
            'uploader'       : DOPTG_plugin_url+'libraries/swf/uploadify.swf',
            'script'         : DOPTG_plugin_url+'libraries/php/uploadify.php?path='+DOPTG_plugin_abs,
            'cancelImg'      : DOPTG_plugin_url+'libraries/gui/images/uploadify/cancel.png',
            'folder'         : '',
            'queueID'        : 'fileQueue',
            'buttonText'     : DOPTG_SELECT_IMAGES,
            'auto'           : true,
            'multi'          : true,
            'onError'        : function (event,ID,fileObj,errorObj){
                                    alert(errorObj.type + ' Error: ' + errorObj.info);
                               },
            'onInit'         : function(){
                                   doptgResize();
                               },
            'onCancel'         : function(event,ID,fileObj,data){
                                   doptgResize();
                               },
            'onSelect'       : function(event, ID, fileObj){
                                   clearClick = false;
                                   doptgToggleMessage('show', DOPTG_ADD_IMAGE_SUBMITED);
                                   setTimeout(function(){
                                       doptgResize();
                                   }, 100);
                               },
            'onComplete'     : function(event, ID, fileObj, response, data){                                   
                                   if (response != '-1'){
                                       setTimeout(function(){
                                           doptgResize();
                                       }, 1000);

                                       $jDOPTG.post(ajaxurl, {action:'doptg_add_image', gallery_id:$jDOPTG('#gallery_id').val(), name:response}, function(data){
                                           if ($jDOPTG('ul', '.column2', '.DOPTG-admin').html() == '<li class="no-data">'+DOPTG_NO_IMAGES+'</li>'){
                                               $jDOPTG('ul', '.column2', '.DOPTG-admin').html('<li class="item-image" id="DOPTG-image-ID-'+data+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
                                               doptgImagesEvents();
                                           }
                                           else{
                                               $jDOPTG('ul', '.column2', '.DOPTG-admin').append('<li class="item-image" id="DOPTG-image-ID-'+data+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
                                           }
                                           doptgResize();
                                           $jDOPTG('#DOPTG-image-ID-'+data).DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                                           $jDOPTG('#DOPTG-image-ID-'+data).prepend('<div class="checkbox-container"><input type="checkbox" name="DOPTG-image-ID-check-'+data+'" id="DOPTG-image-ID-check-'+data+'" /></div>');

                                           $jDOPTG('#DOPTG-image-ID-'+data+' input').unbind('click');
                                           $jDOPTG('#DOPTG-image-ID-'+data+' input').bind('click', function(){
                                               clearClick = false;

                                               setTimeout(function(){
                                                   clearClick = true;
                                               }, 10);
                                           });

                                           $jDOPTG('#DOPTG-image-ID-'+data).unbind('click');
                                           $jDOPTG('#DOPTG-image-ID-'+data).bind('click', function(){
                                               var id = $jDOPTG(this).attr('id').split('-')[3];
                                          
                                               if (currImage != id && clearClick){
                                                   $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
                                                   $jDOPTG(this).addClass('item-image-selected');
                                                   doptgShowImage(id);
                                               }
                                           });
                                       });
                                   }
                               },
            'onAllComplete'  : function(event, data){
                                   doptgToggleMessage('hide', DOPTG_ADD_IMAGE_SUCCESS);
                               }
        });
        
        // Add Images from FTP.
                
        $jDOPTG('#doptg_ftp_image').click(function(){
            if (clearClick){
                doptgToggleMessage('show', DOPTG_ADD_IMAGE_SUBMITED);

                $jDOPTG.post(ajaxurl, {action:'doptg_add_image_ftp', gallery_id:$jDOPTG('#gallery_id').val()}, function(data){
                    var images = data.split(';;;;;'), 
                    i, imageName, imageID;

                    for (i=0; i<images.length; i++){
                        imageID = images[i].split(';;;')[0];
                        imageName = images[i].split(';;;')[1];

                        if ($jDOPTG('ul', '.column2', '.DOPTG-admin').html() == '<li class="no-data">'+DOPTG_NO_IMAGES+'</li>'){
                            $jDOPTG('ul', '.column2', '.DOPTG-admin').html('<li class="item-image" id="DOPTG-image-ID-'+imageID+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                            doptgImagesEvents();
                        }
                        else{
                            $jDOPTG('ul', '.column2', '.DOPTG-admin').append('<li class="item-image" id="DOPTG-image-ID-'+imageID+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                        }

                        doptgResize();
                        $jDOPTG('#DOPTG-image-ID-'+imageID).DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                        $jDOPTG('#DOPTG-image-ID-'+imageID).prepend('<div class="checkbox-container"><input type="checkbox" name="DOPTG-image-ID-check-'+imageID+'" id="DOPTG-image-ID-check-'+imageID+'" /></div>');

                        $jDOPTG('#DOPTG-image-ID-'+imageID+' input').unbind('click');
                        $jDOPTG('#DOPTG-image-ID-'+imageID+' input').bind('click', function(){
                            clearClick = false;

                            setTimeout(function(){
                                clearClick = true;
                            }, 10);
                        });

                        $jDOPTG('#DOPTG-image-ID-'+imageID).unbind('click');
                        $jDOPTG('#DOPTG-image-ID-'+imageID).bind('click', function(){
                            var id = $jDOPTG(this).attr('id').split('-')[3];

                            if (currImage != id && clearClick){
                                $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
                                $jDOPTG(this).addClass('item-image-selected');
                                doptgShowImage(id);
                            }
                        });
                    }

                    doptgToggleMessage('hide', DOPTG_ADD_IMAGE_SUCCESS);
                });            
            }
        });

        doptgResize();
    }
}

function doptgUploadImage(){
    doptgToggleMessage('show', DOPTG_ADD_IMAGE_SUBMITED);
}

function doptgUploadImageSuccess(response){    
    if (response != '-1'){
        setTimeout(function(){
            doptgResize();
        }, 1000);
        
        $jDOPTG.post(ajaxurl, {action:'doptg_add_image', gallery_id:$jDOPTG('#gallery_id').val(), name:response}, function(data){
            if ($jDOPTG('ul', '.column2', '.DOPTG-admin').html() == '<li class="no-data">'+DOPTG_NO_IMAGES+'</li>'){
                $jDOPTG('ul', '.column2', '.DOPTG-admin').html('<li class="item-image" id="DOPTG-image-ID-'+data+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
                doptgImagesEvents();
            }
            else{
                $jDOPTG('ul', '.column2', '.DOPTG-admin').append('<li class="item-image" id="DOPTG-image-ID-'+data+'"><img src="'+DOPTG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
            }
            
            doptgResize();
            $jDOPTG('#DOPTG-image-ID-'+data).DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
            $jDOPTG('#DOPTG-image-ID-'+data).prepend('<div class="checkbox-container"><input type="checkbox" name="DOPTG-image-ID-check-'+data+'" id="DOPTG-image-ID-check-'+data+'" /></div>');

            $jDOPTG('#DOPTG-image-ID-'+data+' input').unbind('click');
            $jDOPTG('#DOPTG-image-ID-'+data+' input').bind('click', function(){
                clearClick = false;

                setTimeout(function(){
                    clearClick = true;
                }, 10);
            });

            $jDOPTG('#DOPTG-image-ID-'+data).unbind('click');
            $jDOPTG('#DOPTG-image-ID-'+data).bind('click', function(){
                var id = $jDOPTG(this).attr('id').split('-')[3];
                
                if (currImage != id && clearClick){
                    $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
                    $jDOPTG(this).addClass('item-image-selected');
                    doptgShowImage(id);
                }
            });
            
            doptgToggleMessage('hide', DOPTG_ADD_IMAGE_SUCCESS);
        });
    }
    else{
        doptgToggleMessage('hide', DOPTG_ADD_IMAGE_SUCCESS);
    }
}

function doptgEditImages(){
    if (clearClick && $jDOPTG('#DOPTG-gallery-actions').val() != ''){
        var images = new Array(), i, noImages = 0,
        confirmationMessage, actionMessage;
        
        switch ($jDOPTG('#DOPTG-gallery-actions').val()){
            case 'delete':
                confirmationMessage = DOPTG_DELETE_IMAGES_CONFIRMATION;
                actionMessage = DOPTG_DELETE_IMAGES_SUBMITED;
                break;    
            case 'enable':
                confirmationMessage = DOPTG_ENABLE_IMAGES_CONFIRMATION;
                actionMessage = DOPTG_ENABLE_IMAGES_SUBMITED;
                break;
            case 'disable':
                confirmationMessage = DOPTG_DISABLE_IMAGES_CONFIRMATION;
                actionMessage = DOPTG_DISABLE_IMAGES_SUBMITED;
                break;
        }
        
        $jDOPTG('.DOPTG-admin .column2 li input').each(function(){
            var id = $jDOPTG(this).attr('id').split('-')[4];

            if ($jDOPTG('#DOPTG-image-ID-check-'+id).is(':checked')){
                images.push(id);
            }
            noImages++;
        });
        
        if (images.length > 0 && confirm(confirmationMessage)){
            doptgRemoveColumns(3);
            $jDOPTG('li', '.column2', '.DOPTG-admin').removeClass('item-image-selected');
            doptgToggleMessage('show', actionMessage);

            $jDOPTG.post(ajaxurl, {action: 'doptg_edit_images',
                                   images_action: $jDOPTG('#DOPTG-gallery-actions').val(),
                                   images: images}, function(data){
                switch ($jDOPTG('#DOPTG-gallery-actions').val()){
                    case 'delete':
                        for (i=0; i<images.length; i++){
                            $jDOPTG('#DOPTG-image-ID-check-'+images[i]).removeAttr('checked');
                            $jDOPTG('#DOPTG-image-ID-'+images[i]).animate({'opacity':0}, 600, function(){
                                $jDOPTG(this).remove();
                                doptgResize();
                            });
                        }

                        if (noImages <= images.length){
                            setTimeout(function(){
                                $jDOPTG('.column-content', '.column2', '.DOPTG-admin').html('<ul><li class="no-data">'+DOPTG_NO_IMAGES+'</li></ul>');
                                doptgResize();
                            }, 700);
                        }
                        doptgToggleMessage('hide', DOPTG_DELETE_IMAGES_SUCCESS);
                        break;    
                    case 'enable':
                        for (i=0; i<images.length; i++){
                            $jDOPTG('#DOPTG-image-ID-check-'+images[i]).removeAttr('checked');
                            $jDOPTG('#DOPTG-image-ID-'+images[i]).removeClass('item-image-disabled');
                        }
                        doptgToggleMessage('hide', DOPTG_ENABLE_IMAGES_SUCCESS);
                        break;
                    case 'disable':
                        for (i=0; i<images.length; i++){
                            $jDOPTG('#DOPTG-image-ID-check-'+images[i]).removeAttr('checked');
                            $jDOPTG('#DOPTG-image-ID-'+images[i]).addClass('item-image-disabled');
                        }
                        doptgToggleMessage('hide', DOPTG_DISABLE_IMAGES_SUCCESS);
                        break;
                }
        
                $jDOPTG('#DOPTG-gallery-actions option[value=""]').attr('selected', 'selected');
            });
        }
    }
}

function doptgShowImage(id){// Show Image Details.
    if (clearClick){
        doptgRemoveColumns(3);
        currImage = id;
        doptgToggleMessage('show', DOPTG_LOAD);
        
        $jDOPTG.post(ajaxurl, {action:'doptg_show_image', image_id:id}, function(data){         
            var json = $jDOPTG.parseJSON(data),
            HeaderHTML = new Array(), HTML = new Array();
            
            HeaderHTML.push('<input type="button" name="DOPTG_image_submit" class="submit-style" onclick="doptgEditImage('+json['id']+')" title="'+DOPTG_EDIT_IMAGE_SUBMIT+'" value="'+DOPTG_SUBMIT+'" />');
            HeaderHTML.push('<input type="button" name="DOPTG_image_delete" class="submit-style" onclick="doptgDeleteImage('+json['id']+')" title="'+DOPTG_DELETE_IMAGE_SUBMIT+'" value="'+DOPTG_DELETE+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help last"><span>'+DOPTG_IMAGE_EDIT_HELP+'</span></a>');

            HTML.push('<input type="hidden" name="crop_x" id="crop_x" value="0" />');
            HTML.push('<input type="hidden" name="crop_y" id="crop_y" value="0" />');
            HTML.push('<input type="hidden" name="crop_width" id="crop_width" value="0" />');
            HTML.push('<input type="hidden" name="crop_height" id="crop_height" value="0" />');
            HTML.push('<input type="hidden" name="image_width" id="image_width" value="0" />');
            HTML.push('<input type="hidden" name="image_height" id="image_height" value="0" />');
            HTML.push('<input type="hidden" name="image_name" id="image_name" value="'+json['name']+'" />');
            HTML.push('<input type="hidden" name="thumb_width" id="thumb_width" value="'+json['thumbnail_width']+'" />');
            HTML.push('<input type="hidden" name="thumb_height" id="thumb_height" value="'+json['thumbnail_height']+'" />');
            HTML.push('<div class="column-image">');
            HTML.push('    <img src="'+DOPTG_plugin_url+'uploads/'+json['name']+'" alt="" />');
            HTML.push('</div>');
            HTML.push('<div class="column-thumbnail-left">');
            HTML.push('    <label class="label">'+DOPTG_EDIT_IMAGE_CROP_THUMBNAIL+'</label>');
            HTML.push('    <div class="column-thumbnail" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;">');
            HTML.push('        <img src="'+DOPTG_plugin_url+'uploads/'+json['name']+'" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;" alt="" />');
            HTML.push('    </div>');
            HTML.push('</div>');
            HTML.push('<div class="column-thumbnail-right">');
            HTML.push('    <label class="label">'+DOPTG_EDIT_IMAGE_CURRENT_THUMBNAIL+'</label>');
            HTML.push('    <div class="column-thumbnail" id="DOPTG-curr-thumb" style="float: right; width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;">');
            HTML.push('        <img src="'+DOPTG_plugin_url+'uploads/thumbs/'+json['name']+'?cacheBuster='+doptgRandomString(64)+'" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;" alt="" />');
            HTML.push('    </div>');
            HTML.push('</div>');
            HTML.push('<br class="DOPTG-clear" />');
            HTML.push('<label class="label" for="image_title">'+DOPTG_EDIT_IMAGE_TITLE+'</label>');
            HTML.push('<input type="text" class="column-input" name="image_title" id="image_title" value="'+json['title']+'" />');
            HTML.push('<label class="label" for="image_caption">'+DOPTG_EDIT_IMAGE_CAPTION+'</label>');
            HTML.push('<textarea class="column-input" name="image_caption" id="image_caption" cols="" rows="6">'+json['caption']+'</textarea>');
            HTML.push('<label class="label" for="image_video">'+DOPTG_EDIT_IMAGE_MEDIA+'</label>');
            HTML.push('<textarea class="column-input" name="image_media" id="image_media" cols="" rows="6">'+json['media']+'</textarea>');
            HTML.push('<label class="label" for="image_video">'+DOPTG_EDIT_IMAGE_LIGHTBOX_MEDIA+'</label>');
            HTML.push('<textarea class="column-input" name="image_lightbox_media" id="image_lightbox_media" cols="" rows="6">'+json['lightbox_media']+'</textarea>');
            HTML.push('<label class="label" for="image_enabled">'+DOPTG_EDIT_IMAGE_ENABLED+'</label>');
            HTML.push('<select class="column-select" name="image_enabled" id="image_enabled">');
            if (json['enabled'] == 'true'){
                HTML.push('<option value="true" selected="selected">true</option>');
                HTML.push('<option value="false">false</option>');
            }
            else{
                HTML.push('<option value="true">true</option>');
                HTML.push('<option value="false" selected="selected">false</option>');
            }
            HTML.push('</select>');


            $jDOPTG('.column-header', '.column3', '.DOPTG-admin').html(HeaderHTML.join(''));
            $jDOPTG('.column-content', '.column3', '.DOPTG-admin').html(HTML.join(''));
            doptgResize();
            $jDOPTG('.column-image', '.DOPTG-admin').DOPImageLoader({'LoaderURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPTG_plugin_url+'libraries/gui/images/image-loader/no-image.png', 'SuccessCallback': 'doptgInitJcrop()'});
            
            doptgToggleMessage('hide', DOPTG_IMAGE_LOADED);
        });
    }
}

function doptgInitJcrop(){// Init Jcrop. (For croping thumbnails)
    imageDisplay = true;
    imageWidth = $jDOPTG('img', '.column-image', '.DOPTG-admin').width();
    imageHeight = $jDOPTG('img', '.column-image', '.DOPTG-admin').height();
    doptgResize();
    $jDOPTG('img', '.column-image', '.DOPTG-admin').Jcrop({onChange: doptgShowCropPreview, onSelect: doptgShowCropPreview, aspectRatio: $jDOPTG('.column-thumbnail', '.DOPTG-admin').width()/$jDOPTG('.column-thumbnail', '.DOPTG-admin').height(), minSize: [$jDOPTG('.column-thumbnail', '.DOPTG-admin').width(), $jDOPTG('.column-thumbnail', '.DOPTG-admin').height()]});
    setTimeout(function(){
        doptgResize();        
    }, 1000);
}

function doptgShowCropPreview(coords){// Select thumbnail with Jcrop.
    if (parseInt(coords.w) > 0){
        $jDOPTG('#crop_x').val(coords.x);
        $jDOPTG('#crop_y').val(coords.y);
        $jDOPTG('#crop_width').val(coords.w);
        $jDOPTG('#crop_height').val(coords.h);
        $jDOPTG('#image_width').val($jDOPTG('img', '.column-image', '.DOPTG-admin').width());
        $jDOPTG('#image_height').val($jDOPTG('img', '.column-image', '.DOPTG-admin').height());

        var rx = $jDOPTG('.column-thumbnail', '.DOPTG-admin').width()/coords.w;
        var ry = $jDOPTG('.column-thumbnail', '.DOPTG-admin').height()/coords.h;

        $jDOPTG('img', '.column-thumbnail-left', '.DOPTG-admin').css({
            width: Math.round(rx*$jDOPTG('img', '.column-image', '.DOPTG-admin').width()) + 'px',
            height: Math.round(ry*$jDOPTG('img', '.column-image', '.DOPTG-admin').height()) + 'px',
            marginLeft: '-'+Math.round(rx * coords.x)+'px',
            marginTop: '-'+Math.round(ry * coords.y)+'px'
        });
    }
}

function doptgEditImage(id){// Edit Image Details.
    if (clearClick){
        doptgToggleMessage('show', DOPTG_SAVE);
        
        $jDOPTG.post(ajaxurl, {action:'doptg_edit_image',
                               image_id:id,
                               crop_x: $jDOPTG('#crop_x').val(),
                               crop_y: $jDOPTG('#crop_y').val(),
                               crop_width: $jDOPTG('#crop_width').val(),
                               crop_height: $jDOPTG('#crop_height').val(),
                               image_width: $jDOPTG('#image_width').val(),
                               image_height: $jDOPTG('#image_height').val(),
                               image_name: $jDOPTG('#image_name').val(),
                               thumb_width: $jDOPTG('#thumb_width').val(),
                               thumb_height: $jDOPTG('#thumb_height').val(),
                               image_title: $jDOPTG('#image_title').val(),
                               image_caption: $jDOPTG('#image_caption').val(),
                               image_media: $jDOPTG('#image_media').val(),
                               image_lightbox_media: $jDOPTG('#image_lightbox_media').val(),
                               image_enabled: $jDOPTG('#image_enabled').val()}, function(data){
            doptgToggleMessage('hide', DOPTG_EDIT_IMAGE_SUCCESS);
            if ($jDOPTG('#image_enabled').val() == 'true'){
                $jDOPTG('#DOPTG-image-ID-'+id).removeClass('item-image-disabled');
            }
            else{
                $jDOPTG('#DOPTG-image-ID-'+id).addClass('item-image-disabled');
            }
            if (data != ''){
                $jDOPTG('#DOPTG-curr-thumb').html('<img src="'+data+'?cacheBuster='+doptgRandomString(64)+'" style="width:'+$jDOPTG('#thumb_width').val()+'px; height:'+$jDOPTG('#thumb_height').val()+'px;" alt="" />');
            }
        });
    }
}

function doptgDeleteImage(id){// Delete Image.
    if (clearClick){
        if (confirm(DOPTG_DELETE_IMAGE_CONFIRMATION)){
            doptgToggleMessage('show', DOPTG_DELETE_IMAGE_SUBMITED);
            
            $jDOPTG.post(ajaxurl, {action:'doptg_delete_image',
                                   image_id: id}, function(data){
                doptgRemoveColumns(3);
                
                $jDOPTG('#DOPTG-image-ID-'+id).stop(true, true).animate({'opacity':0}, 600, function(){
                    $jDOPTG(this).remove();
                    doptgToggleMessage('hide', DOPTG_DELETE_GALLERY_SUCCESS);
                
                    if (data == '0'){
                        $jDOPTG('.column-content', '.column2', '.DOPTG-admin').html('<ul><li class="no-data">'+DOPTG_NO_IMAGES+'</li></ul>');
                    }
                    doptgResize();
                });
            });
        }
    }
}

// Settings

function doptgSettingsForm(data, column){// Settings Form.
    var HTML = new Array();
    
    HTML.push('<form method="post" class="settings" action="" onsubmit="return false;">');

// General Styles & Settings
    HTML.push('    <h3 class="settings">'+DOPTG_GENERAL_STYLES_SETTINGS+'</h3>');
    
    if ($jDOPTG('#gallery_id').val() != '0'){
        HTML.push(doptgSettingsFormInput('name', data['name'], DOPTG_GALLERY_NAME, '', '', '', 'help', DOPTG_GALLERY_NAME_INFO));
    }
    else{
        HTML.push('<input type="hidden" name="name" id="name" value="'+data['name']+'" />');
        HTML.push(doptgSettingsFormSelect('data_parse_method', data['data_parse_method'], DOPTG_DATA_PARSE_METHOD, '', '', '', 'help', DOPTG_DATA_PARSE_METHOD_INFO, 'ajax;;html', 'AJAX;;HTML'));
    }
    
    HTML.push(doptgSettingsFormInput('width', data['width'], DOPTG_WIDTH, '', 'px', 'small', 'help-small', DOPTG_WIDTH_INFO));
    HTML.push(doptgSettingsFormInput('height', data['height'], DOPTG_HEIGHT, '', 'px', 'small', 'help-small', DOPTG_HEIGHT_INFO));
    HTML.push(doptgSettingsFormInput('bg_color', data['bg_color'], DOPTG_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('bg_alpha', data['bg_alpha'], DOPTG_BG_ALPHA, '', '', 'small', 'help-small', DOPTG_BG_ALPHA_INFO));
    HTML.push(doptgSettingsFormSelect('images_order', data['images_order'], DOPTG_IMAGES_ORDER, '', '', '', 'help', DOPTG_IMAGES_ORDER_INFO, 'normal;;random', DOPTG_FORM_NORMAL_TEXT+';;'+DOPTG_FORM_RANDOM_TEXT));
    HTML.push(doptgSettingFormSwitch('responsive_enabled', data['responsive_enabled'], DOPTG_RESPONSIVE_ENABLED, '', '', 'help', DOPTG_RESPONSIVE_ENABLED_INFO));
       
// Thumbnails Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_THUMBNAILS_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormSelect('thumbnails_position', data['thumbnails_position'], DOPTG_THUMBNAILS_POSITION, '', '', '', 'help', DOPTG_THUMBNAILS_POSITION_INFO, 'top;;right;;bottom;;left', DOPTG_FORM_TOP_TEXT+';;'+DOPTG_FORM_RIGHT_TEXT+';;'+DOPTG_FORM_BOTTOM_TEXT+';;'+DOPTG_FORM_LEFT_TEXT));
    HTML.push(doptgSettingFormSwitch('thumbnails_over_image', data['thumbnails_over_image'], DOPTG_THUMBNAILS_OVER_IMAGE, '', '', 'help', DOPTG_THUMBNAILS_OVER_IMAGE_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_bg_color', data['thumbnails_bg_color'], DOPTG_THUMBNAILS_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_THUMBNAILS_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_bg_alpha', data['thumbnails_bg_alpha'], DOPTG_THUMBNAILS_BG_ALPHA, '', '', 'small', 'help-small', DOPTG_THUMBNAILS_BG_ALPHA_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_spacing', data['thumbnails_spacing'], DOPTG_THUMBNAILS_SPACING, '', 'px', 'small', 'help-small', DOPTG_THUMBNAILS_SPACING_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_padding_top', data['thumbnails_padding_top'], DOPTG_THUMBNAILS_PADDING_TOP, '', 'px', 'small', 'help-small', DOPTG_THUMBNAILS_PADDING_TOP_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_padding_right', data['thumbnails_padding_right'], DOPTG_THUMBNAILS_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPTG_THUMBNAILS_PADDING_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_padding_bottom', data['thumbnails_padding_bottom'], DOPTG_THUMBNAILS_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_THUMBNAILS_PADDING_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('thumbnails_padding_left', data['thumbnails_padding_left'], DOPTG_THUMBNAILS_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPTG_THUMBNAILS_PADDING_LEFT_INFO));

// Thumbnails Navigation Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_THUMBNAILS_NAVIGATION_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormSelect('thumbnails_navigation', data['thumbnails_navigation'], DOPTG_THUMBNAILS_NAVIGATION, '', '', '', 'help', DOPTG_THUMBNAILS_NAVIGATION_INFO, 'mouse;;arrows', DOPTG_FORM_MOUSE_TEXT+';;'+DOPTG_FORM_ARROWS_TEXT));
    HTML.push(doptgSettingsFormImage('thumbnails_navigation_prev', data['thumbnails_navigation_prev'], DOPTG_THUMBNAILS_NAVIGATION_PREV, 'help-image', DOPTG_THUMBNAILS_NAVIGATION_PREV_INFO));
    HTML.push(doptgSettingsFormImage('thumbnails_navigation_prev_hover', data['thumbnails_navigation_prev_hover'], DOPTG_THUMBNAILS_NAVIGATION_PREV_HOVER, 'help-image', DOPTG_THUMBNAILS_NAVIGATION_PREV_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('thumbnails_navigation_next', data['thumbnails_navigation_next'], DOPTG_THUMBNAILS_NAVIGATION_NEXT, 'help-image', DOPTG_THUMBNAILS_NAVIGATION_NEXT_INFO));
    HTML.push(doptgSettingsFormImage('thumbnails_navigation_next_hover', data['thumbnails_navigation_next_hover'], DOPTG_THUMBNAILS_NAVIGATION_NEXT_HOVER, 'help-image', DOPTG_THUMBNAILS_NAVIGATION_NEXT_HOVER_INFO));
        
// Styles & Settings for a Thumbnail
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_THUMBNAIL_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormImage('thumbnail_loader', data['thumbnail_loader'], DOPTG_THUMBNAIL_LOADER, 'help-image', DOPTG_THUMBNAIL_LOADER_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_width', data['thumbnail_width'], DOPTG_THUMBNAIL_WIDTH, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_WIDTH_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_height', data['thumbnail_height'], DOPTG_THUMBNAIL_HEIGHT, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_HEIGHT_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_width_mobile', data['thumbnail_width_mobile'], DOPTG_THUMBNAIL_WIDTH_MOBILE, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_WIDTH_MOBILE_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_height_mobile', data['thumbnail_height_mobile'], DOPTG_THUMBNAIL_HEIGHT_MOBILE, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_HEIGHT_MOBILE_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_alpha', data['thumbnail_alpha'], DOPTG_THUMBNAIL_ALPHA, '', '', 'small', 'help-small', DOPTG_THUMBNAIL_ALPHA_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_alpha_hover', data['thumbnail_alpha_hover'], DOPTG_THUMBNAIL_ALPHA_HOVER, '', '', 'small', 'help-small', DOPTG_THUMBNAIL_ALPHA_HOVER_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_alpha_selected', data['thumbnail_alpha_selected'], DOPTG_THUMBNAIL_ALPHA_SELECTED, '', '', 'small', 'help-small', DOPTG_THUMBNAIL_ALPHA_SELECTED_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_bg_color', data['thumbnail_bg_color'], DOPTG_THUMBNAIL_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_bg_color_hover', data['thumbnail_bg_color_hover'], DOPTG_THUMBNAIL_BG_COLOR_HOVER, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BG_COLOR_HOVER_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_bg_color_selected', data['thumbnail_bg_color_selected'], DOPTG_THUMBNAIL_BG_COLOR_SELECTED, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BG_COLOR_SELECTED_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_border_size', data['thumbnail_border_size'], DOPTG_THUMBNAIL_BORDER_SIZE, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_BORDER_SIZE_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_border_color', data['thumbnail_border_color'], DOPTG_THUMBNAIL_BORDER_COLOR, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BORDER_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_border_color_hover', data['thumbnail_border_color_hover'], DOPTG_THUMBNAIL_BORDER_COLOR_HOVER, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BORDER_COLOR_HOVER_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_border_color_selected', data['thumbnail_border_color_selected'], DOPTG_THUMBNAIL_BORDER_COLOR_SELECTED, '#', '', 'small', 'help-small', DOPTG_THUMBNAIL_BORDER_COLOR_SELECTED_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_padding_top', data['thumbnail_padding_top'], DOPTG_THUMBNAIL_PADDING_TOP, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_PADDING_TOP_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_padding_right', data['thumbnail_padding_right'], DOPTG_THUMBNAIL_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_PADDING_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_padding_bottom', data['thumbnail_padding_bottom'], DOPTG_THUMBNAIL_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_PADDING_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('thumbnail_padding_left', data['thumbnail_padding_left'], DOPTG_THUMBNAIL_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPTG_THUMBNAIL_PADDING_LEFT_INFO));

// Image Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_IMAGE_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormImage('image_loader', data['image_loader'], DOPTG_IMAGE_LOADER, 'help-image', DOPTG_IMAGE_LOADER_INFO));
    HTML.push(doptgSettingsFormInput('image_bg_color', data['image_bg_color'], DOPTG_IMAGE_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_IMAGE_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('image_bg_alpha', data['image_bg_alpha'], DOPTG_IMAGE_BG_ALPHA, '', '', 'small', 'help-small', DOPTG_IMAGE_BG_ALPHA_INFO));
    HTML.push(doptgSettingsFormSelect('image_display_type', data['image_display_type'], DOPTG_IMAGE_DISPLAY_TYPE, '', '', '', 'help', DOPTG_IMAGE_DISPLAY_TYPE_INFO, 'fit;;full', DOPTG_FORM_FIT_TEXT+';;'+DOPTG_FORM_FULL_TEXT));
    HTML.push(doptgSettingsFormInput('image_display_time', data['image_display_time'], DOPTG_IMAGE_DISPLAY_TIME, '', '', 'small', 'help-small', DOPTG_IMAGE_DISPLAY_TIME_INFO));
    HTML.push(doptgSettingsFormInput('image_margin_top', data['image_margin_top'], DOPTG_IMAGE_MARGIN_TOP, '', 'px', 'small', 'help-small', DOPTG_IMAGE_MARGIN_TOP_INFO));
    HTML.push(doptgSettingsFormInput('image_margin_right', data['image_margin_right'], DOPTG_IMAGE_MARGIN_RIGHT, '', 'px', 'small', 'help-small', DOPTG_IMAGE_MARGIN_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('image_margin_bottom', data['image_margin_bottom'], DOPTG_IMAGE_MARGIN_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_IMAGE_MARGIN_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('image_margin_left', data['image_margin_left'], DOPTG_IMAGE_MARGIN_LEFT, '', 'px', 'small', 'help-small', DOPTG_IMAGE_MARGIN_LEFT_INFO));
    HTML.push(doptgSettingsFormInput('image_padding_top', data['image_padding_top'], DOPTG_IMAGE_PADDING_TOP, '', 'px', 'small', 'help-small', DOPTG_IMAGE_PADDING_TOP_INFO));
    HTML.push(doptgSettingsFormInput('image_padding_right', data['image_padding_right'], DOPTG_IMAGE_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPTG_IMAGE_PADDING_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('image_padding_bottom', data['image_padding_bottom'], DOPTG_IMAGE_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_IMAGE_PADDING_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('image_padding_left', data['image_padding_left'], DOPTG_IMAGE_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPTG_IMAGE_PADDING_LEFT_INFO));

// Navigation Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_NAVIGATION_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('navigation_enabled', data['navigation_enabled'], DOPTG_NAVIGATION_ENABLED, '', '', 'help', DOPTG_NAVIGATION_ENABLED_INFO));
    HTML.push(doptgSettingFormSwitch('navigation_over_image', data['navigation_over_image'], DOPTG_NAVIGATION_OVER_IMAGE, '', '', 'help', DOPTG_NAVIGATION_OVER_IMAGE_INFO));
    HTML.push(doptgSettingsFormImage('navigation_prev', data['navigation_prev'], DOPTG_NAVIGATION_PREV, 'help-image', DOPTG_NAVIGATION_PREV_INFO));
    HTML.push(doptgSettingsFormImage('navigation_prev_hover', data['navigation_prev_hover'], DOPTG_NAVIGATION_PREV_HOVER, 'help-image', DOPTG_NAVIGATION_PREV_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('navigation_next', data['navigation_next'], DOPTG_NAVIGATION_NEXT, 'help-image', DOPTG_NAVIGATION_NEXT_INFO));
    HTML.push(doptgSettingsFormImage('navigation_next_hover', data['navigation_next_hover'], DOPTG_NAVIGATION_NEXT_HOVER, 'help-image', DOPTG_NAVIGATION_NEXT_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('navigation_lightbox', data['navigation_lightbox'], DOPTG_NAVIGATION_LIGHTBOX, 'help-image', DOPTG_NAVIGATION_LIGHTBOX_INFO));
    HTML.push(doptgSettingsFormImage('navigation_lightbox_hover', data['navigation_lightbox_hover'], DOPTG_NAVIGATION_LIGHTBOX_HOVER, 'help-image', DOPTG_NAVIGATION_LIGHTBOX_HOVER_INFO));
    HTML.push(doptgSettingFormSwitch('navigation_touch_device_swipe_enabled', data['navigation_touch_device_swipe_enabled'], DOPTG_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED, '', '', 'help', DOPTG_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED_INFO));
    
// Image Caption Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_CAPTION_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormInput('caption_width', data['caption_width'], DOPTG_CAPTION_WIDTH, '', 'px', 'small', 'help-small', DOPTG_CAPTION_WIDTH_INFO));
    HTML.push(doptgSettingsFormInput('caption_height', data['caption_height'], DOPTG_CAPTION_HEIGHT, '', 'px', 'small', 'help-small', DOPTG_CAPTION_HEIGHT_INFO));
    HTML.push(doptgSettingsFormInput('caption_title_color', data['caption_title_color'], DOPTG_CAPTION_TITLE_COLOR, '#', '', 'small', 'help-small', DOPTG_CAPTION_TITLE_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('caption_text_color', data['caption_text_color'], DOPTG_CAPTION_TEXT_COLOR, '#', '', 'small', 'help-small', DOPTG_CAPTION_TEXT_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('caption_bg_color', data['caption_bg_color'], DOPTG_CAPTION_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_CAPTION_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('caption_bg_alpha', data['caption_bg_alpha'], DOPTG_CAPTION_BG_ALPHA, '', '', 'small', 'help-small', DOPTG_CAPTION_BG_ALPHA_INFO));
    HTML.push(doptgSettingsFormSelect('caption_position', data['caption_position'], DOPTG_CAPTION_POSITION, '', '', '', 'help', DOPTG_CAPTION_POSITION_INFO, 'top;;right;;bottom;;left;;top-left;;top-right;;bottom-left;;bottom-right', DOPTG_FORM_TOP_TEXT+';;'+DOPTG_FORM_RIGHT_TEXT+';;'+DOPTG_FORM_BOTTOM_TEXT+';;'+DOPTG_FORM_LEFT_TEXT+';;'+DOPTG_FORM_TOP_LEFT_TEXT+';;'+DOPTG_FORM_TOP_RIGHT_TEXT+';;'+DOPTG_FORM_BOTTOM_LEFT_TEXT+';;'+DOPTG_FORM_BOTTOM_RIGHT_TEXT));
    HTML.push(doptgSettingFormSwitch('caption_over_image', data['caption_over_image'], DOPTG_CAPTION_OVER_IMAGE, '', '', 'help', DOPTG_CAPTION_OVER_IMAGE_INFO));
    HTML.push(doptgSettingsFormInput('caption_scroll_scrub_color', data['caption_scroll_scrub_color'], DOPTG_CAPTION_SCROLL_SCRUB_COLOR, '#', '', 'small', 'help-small', DOPTG_CAPTION_SCROLL_SCRUB_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('caption_scroll_bg_color', data['caption_scroll_bg_color'], DOPTG_CAPTION_SCROLL_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_CAPTION_SCROLL_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('caption_margin_top', data['caption_margin_top'], DOPTG_CAPTION_MARGIN_TOP, '', 'px', 'small', 'help-small', DOPTG_CAPTION_MARGIN_TOP_INFO));
    HTML.push(doptgSettingsFormInput('caption_margin_right', data['caption_margin_right'], DOPTG_CAPTION_MARGIN_RIGHT, '', 'px', 'small', 'help-small', DOPTG_CAPTION_MARGIN_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('caption_margin_bottom', data['caption_margin_bottom'], DOPTG_CAPTION_MARGIN_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_CAPTION_MARGIN_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('caption_margin_left', data['caption_margin_left'], DOPTG_CAPTION_MARGIN_LEFT, '', 'px', 'small', 'help-small', DOPTG_CAPTION_MARGIN_LEFT_INFO));
    HTML.push(doptgSettingsFormInput('caption_padding_top', data['caption_padding_top'], DOPTG_CAPTION_PADDING_TOP, '', 'px', 'small', 'help-small', DOPTG_CAPTION_PADDING_TOP_INFO));
    HTML.push(doptgSettingsFormInput('caption_padding_right', data['caption_padding_right'], DOPTG_CAPTION_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPTG_CAPTION_PADDING_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('caption_padding_bottom', data['caption_padding_bottom'], DOPTG_CAPTION_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_CAPTION_PADDING_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('caption_padding_left', data['caption_padding_left'], DOPTG_CAPTION_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPTG_CAPTION_PADDING_LEFT_INFO));

// Lightbox Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_LIGHTBOX_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('lightbox_enabled', data['lightbox_enabled'], DOPTG_LIGHTBOX_ENABLED, '', '', 'help', DOPTG_LIGHTBOX_ENABLED_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_window_color', data['lightbox_window_color'], DOPTG_LIGHTBOX_WINDOW_COLOR, '#', '', 'small', 'help-small', DOPTG_LIGHTBOX_WINDOW_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_window_alpha', data['lightbox_window_alpha'], DOPTG_LIGHTBOX_WINDOW_ALPHA, '', '', 'small', 'help-small', DOPTG_LIGHTBOX_WINDOW_ALPHA_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_loader', data['lightbox_loader'], DOPTG_LIGHTBOX_LOADER, 'help-image', DOPTG_LIGHTBOX_LOADER_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_bg_color', data['lightbox_bg_color'], DOPTG_LIGHTBOX_BACKGROUND_COLOR, '#', '', 'small', 'help-small', DOPTG_LIGHTBOX_BACKGROUND_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_bg_alpha', data['lightbox_bg_alpha'], DOPTG_LIGHTBOX_BACKGROUND_ALPHA, '', '', 'small', 'help-small', DOPTG_LIGHTBOX_BACKGROUND_ALPHA_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_margin_top', data['lightbox_margin_top'], DOPTG_LIGHTBOX_MARGIN_TOP, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_MARGIN_TOP_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_margin_right', data['lightbox_margin_right'], DOPTG_LIGHTBOX_MARGIN_RIGHT, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_MARGIN_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_margin_bottom', data['lightbox_margin_bottom'], DOPTG_LIGHTBOX_MARGIN_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_MARGIN_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_margin_left', data['lightbox_margin_left'], DOPTG_LIGHTBOX_MARGIN_LEFT, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_MARGIN_LEFT_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_padding_top', data['lightbox_padding_top'], DOPTG_LIGHTBOX_PADDING_TOP, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_PADDING_TOP_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_padding_right', data['lightbox_padding_right'], DOPTG_LIGHTBOX_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_PADDING_RIGHT_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_padding_bottom', data['lightbox_padding_bottom'], DOPTG_LIGHTBOX_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_PADDING_BOTTOM_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_padding_left', data['lightbox_padding_left'], DOPTG_LIGHTBOX_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPTG_LIGHTBOX_PADDING_LEFT_INFO));
    
// Lightbox Navigation Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_LIGHTBOX_NAVIGATION_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingsFormImage('lightbox_navigation_prev', data['lightbox_navigation_prev'], DOPTG_LIGHTBOX_NAVIGATION_PREV, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_PREV_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_navigation_prev_hover', data['lightbox_navigation_prev_hover'], DOPTG_LIGHTBOX_NAVIGATION_PREV_HOVER, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_PREV_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_navigation_next', data['lightbox_navigation_next'], DOPTG_LIGHTBOX_NAVIGATION_NEXT, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_NEXT_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_navigation_next_hover', data['lightbox_navigation_next_hover'], DOPTG_LIGHTBOX_NAVIGATION_NEXT_HOVER, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_NEXT_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_navigation_close', data['lightbox_navigation_close'], DOPTG_LIGHTBOX_NAVIGATION_CLOSE, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_CLOSE_INFO));
    HTML.push(doptgSettingsFormImage('lightbox_navigation_close_hover', data['lightbox_navigation_close_hover'], DOPTG_LIGHTBOX_NAVIGATION_CLOSE_HOVER, 'help-image', DOPTG_LIGHTBOX_NAVIGATION_CLOSE_HOVER_INFO));    
    HTML.push(doptgSettingsFormInput('lightbox_navigation_info_bg_color', data['lightbox_navigation_info_bg_color'], DOPTG_LIGHTBOX_NAVIGATION_INFO_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_LIGHTBOX_NAVIGATION_INFO_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('lightbox_navigation_info_text_color', data['lightbox_navigation_info_text_color'], DOPTG_LIGHTBOX_NAVIGATION_INFO_TEXT_COLOR, '#', '', 'small', 'help-small', DOPTG_LIGHTBOX_NAVIGATION_INFO_TEXT_COLOR_INFO));
    HTML.push(doptgSettingFormSwitch('lightbox_navigation_touch_device_swipe_enabled', data['lightbox_navigation_touch_device_swipe_enabled'], DOPTG_LIGHTBOX_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED, '', '', 'help', DOPTG_LIGHTBOX_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED_INFO));
       
// Social Share Styles & Settings 
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_SOCIAL_SHARE_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('social_share_enabled', data['social_share_enabled'], DOPTG_SOCIAL_SHARE_ENABLED, '', '', 'help', DOPTG_SOCIAL_SHARE_ENABLED_INFO));
    HTML.push(doptgSettingsFormImage('social_share', data['social_share'], DOPTG_SOCIAL_SHARE, 'help-image', DOPTG_SOCIAL_SHARE_INFO));
    HTML.push(doptgSettingsFormImage('social_share_lightbox', data['social_share_lightbox'], DOPTG_SOCIAL_SHARE_LIGHTBOX, 'help-image', DOPTG_SOCIAL_SHARE_LIGHTBOX_INFO)); 
    
// Tooltip Styles & Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_TOOLTIP_STYLES_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('tooltip_enabled', data['tooltip_enabled'], DOPTG_TOOLTIP_ENABLED, '', '', 'help', DOPTG_TOOLTIP_ENABLED_INFO));
    HTML.push(doptgSettingsFormInput('tooltip_bg_color', data['tooltip_bg_color'], DOPTG_TOOLTIP_BG_COLOR, '#', '', 'small', 'help-small', DOPTG_TOOLTIP_BG_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('tooltip_stroke_color', data['tooltip_stroke_color'], DOPTG_TOOLTIP_STROKE_COLOR, '#', '', 'small', 'help-small', DOPTG_TOOLTIP_STROKE_COLOR_INFO));
    HTML.push(doptgSettingsFormInput('tooltip_text_color', data['tooltip_text_color'], DOPTG_TOOLTIP_TEXT_COLOR, '#', '', 'small', 'help-small', DOPTG_TOOLTIP_TEXT_COLOR_INFO));

// Slideshow Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_SLIDESHOW_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('slideshow', data['slideshow'], DOPTG_SLIDESHOW, '', '', 'help', DOPTG_SLIDESHOW_INFO));
    HTML.push(doptgSettingsFormInput('slideshow_time', data['slideshow_time'], DOPTG_SLIDESHOW_TIME, '', '', 'small', 'help-small', DOPTG_SLIDESHOW_TIME_INFO));
    HTML.push(doptgSettingFormSwitch('slideshow_autostart', data['slideshow_autostart'], DOPTG_SLIDESHOW_AUTOSTART, '', '', 'help', DOPTG_SLIDESHOW_AUTOSTART_INFO));
    HTML.push(doptgSettingFormSwitch('slideshow_loop', data['slideshow_loop'], DOPTG_SLIDESHOW_LOOP, '', '', 'help', DOPTG_SLIDESHOW_LOOP_INFO));
    HTML.push(doptgSettingsFormImage('slideshow_play', data['slideshow_play'], DOPTG_SLIDESHOW_PLAY, 'help-image', DOPTG_SLIDESHOW_PLAY_INFO));
    HTML.push(doptgSettingsFormImage('slideshow_play_hover', data['slideshow_play_hover'], DOPTG_SLIDESHOW_PLAY_HOVER, 'help-image', DOPTG_SLIDESHOW_PLAY_HOVER_INFO));
    HTML.push(doptgSettingsFormImage('slideshow_pause', data['slideshow_pause'], DOPTG_SLIDESHOW_PAUSE, 'help-image', DOPTG_SLIDESHOW_PAUSE_INFO));
    HTML.push(doptgSettingsFormImage('slideshow_pause_hover', data['slideshow_pause_hover'], DOPTG_SLIDESHOW_PAUSE_HOVER, 'help-image', DOPTG_SLIDESHOW_PAUSE_HOVER_INFO));
    
// Auto Hide Settings
    HTML.push('    <a href="javascript:doptgMoveTop()" class="go-top">'+DOPTG_GO_TOP+'</a><h3 class="settings">'+DOPTG_AUTO_HIDE_SETTINGS+'</h3>');
    HTML.push(doptgSettingFormSwitch('auto_hide', data['auto_hide'], DOPTG_AUTO_HIDE, '', '', 'help', DOPTG_AUTO_HIDE_INFO));
    HTML.push(doptgSettingsFormInput('auto_hide_time', data['auto_hide_time'], DOPTG_AUTO_HIDE_TIME, '', '', 'small', 'help-small', DOPTG_AUTO_HIDE_TIME_INFO));
    
    HTML.push('</form>');
    HTML.push('<style type="text/css">');
    HTML.push('    .DOPTG-admin .setting-box .switch-inner:before{content: "'+DOPTG_FORM_ENABLED_TEXT+'";}');
    HTML.push('    .DOPTG-admin .setting-box .switch-inner:after{content: "'+DOPTG_FORM_DISABLED_TEXT+'";}');
    HTML.push('</style>');

    $jDOPTG('.column-content', '.column'+column, '.DOPTG-admin').html(HTML.join(''));
    setTimeout(function(){
        doptgResize();
        setTimeout(function(){
           doptgResize();
        }, 10000);
    }, 5000);
    
    $jDOPTG('#bg_color,\n\
             #thumbnails_bg_color,\n\
             #thumbnail_bg_color,\n\
             #thumbnail_bg_color_hover,\n\
             #thumbnail_bg_color_selected,\n\
             #thumbnail_border_color,\n\
             #thumbnail_border_color_hover,\n\
             #thumbnail_border_color_selected,\n\
             #image_bg_color,\n\
             #caption_title_color,\n\
             #caption_text_color,\n\
             #caption_bg_color,\n\
             #caption_scroll_scrub_color,\n\
             #caption_scroll_bg_color,\n\
             #lightbox_window_color,\n\
             #lightbox_bg_color,\n\
             #lightbox_navigation_info_bg_color,\n\
             #lightbox_navigation_info_text_color,\n\
             #tooltip_bg_color,\n\
             #tooltip_stroke_color,\n\
             #tooltip_text_color').ColorPicker({
        onSubmit:function(hsb, hex, rgb, el){
            $jDOPTG(el).val(hex);
            $jDOPTG(el).ColorPickerHide();
            $jDOPTG(el).removeAttr('style');
            $jDOPTG(el).css({'background-color': '#'+hex,
                             'color': doptgIdealTextColor(hex) == 'white' ? '#ffffff':'#0000000'});
        },
        onBeforeShow:function(){
            $jDOPTG(this).ColorPickerSetColor(this.value);
        },
        onShow:function(colpkr){
            $jDOPTG(colpkr).fadeIn(500);
            return false;
        },
        onHide:function(colpkr){
            $jDOPTG(colpkr).fadeOut(500);
            return false;
        }
    })
    .bind('keyup', function(){
        $jDOPTG(this).ColorPickerSetColor(this.value);
        $jDOPTG(this).removeAttr('style');
        
        if (this.value.length != 6){
            $jDOPTG(this).css({'background-color': '#ffffff',
                               'color': '#0000000'});
        }
        else{
            $jDOPTG(this).css({'background-color': '#'+this.value,
                               'color': doptgIdealTextColor(this.value) == 'white' ? '#ffffff':'#0000000'});
        }
    });
    
    $jDOPTG('#bg_color').css({'background-color': '#'+data['bg_color'],
                              'color': doptgIdealTextColor(data['bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnails_bg_color').css({'background-color': '#'+data['thumbnails_bg_color'],
                                         'color': doptgIdealTextColor(data['thumbnails_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_bg_color').css({'background-color': '#'+data['thumbnail_bg_color'],
                                        'color': doptgIdealTextColor(data['thumbnail_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_bg_color_hover').css({'background-color': '#'+data['thumbnail_bg_color_hover'],
                                              'color': doptgIdealTextColor(data['thumbnail_bg_color_hover']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_bg_color_selected').css({'background-color': '#'+data['thumbnail_bg_color_selected'],
                                                 'color': doptgIdealTextColor(data['thumbnail_bg_color_selected']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_border_color').css({'background-color': '#'+data['thumbnail_border_color'],
                                            'color': doptgIdealTextColor(data['thumbnail_border_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_border_color_hover').css({'background-color': '#'+data['thumbnail_border_color_hover'],
                                                  'color': doptgIdealTextColor(data['thumbnail_border_color_hover']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#thumbnail_border_color_selected').css({'background-color': '#'+data['thumbnail_border_color_selected'],
                                                     'color': doptgIdealTextColor(data['thumbnail_border_color_selected']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#image_bg_color').css({'background-color': '#'+data['image_bg_color'],
                                    'color': doptgIdealTextColor(data['image_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#caption_title_color').css({'background-color': '#'+data['caption_title_color'],
                                         'color': doptgIdealTextColor(data['caption_title_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#caption_text_color').css({'background-color': '#'+data['caption_text_color'],
                                        'color': doptgIdealTextColor(data['caption_text_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#caption_bg_color').css({'background-color': '#'+data['caption_bg_color'],
                                      'color': doptgIdealTextColor(data['caption_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#caption_scroll_scrub_color').css({'background-color': '#'+data['caption_scroll_scrub_color'],
                                                'color': doptgIdealTextColor(data['caption_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#caption_scroll_bg_color').css({'background-color': '#'+data['caption_scroll_bg_color'],
                                             'color': doptgIdealTextColor(data['caption_scroll_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#lightbox_window_color').css({'background-color': '#'+data['lightbox_window_color'],
                                           'color': doptgIdealTextColor(data['lightbox_window_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#lightbox_bg_color').css({'background-color': '#'+data['lightbox_bg_color'],
                                       'color': doptgIdealTextColor(data['lightbox_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#lightbox_navigation_info_bg_color').css({'background-color': '#'+data['lightbox_navigation_info_bg_color'],
                                                       'color': doptgIdealTextColor(data['lightbox_navigation_info_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#lightbox_navigation_info_text_color').css({'background-color': '#'+data['lightbox_navigation_info_text_color'],
                                                         'color': doptgIdealTextColor(data['lightbox_navigation_info_text_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#tooltip_bg_color').css({'background-color': '#'+data['tooltip_bg_color'],
                                      'color': doptgIdealTextColor(data['tooltip_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#tooltip_stroke_color').css({'background-color': '#'+data['tooltip_stroke_color'],
                                          'color': doptgIdealTextColor(data['tooltip_stroke_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPTG('#tooltip_text_color').css({'background-color': '#'+data['tooltip_text_color'],
                                        'color': doptgIdealTextColor(data['tooltip_text_color']) == 'white' ? '#ffffff':'#0000000'});
    
    doptgSettingsImageUpload('thumbnails_navigation_prev', 'uploads/settings/thumbnails-navigation-prev/', DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_SUBMITED, DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_SUCCESS);
    doptgSettingsImageUpload('thumbnails_navigation_prev_hover', 'uploads/settings/thumbnails-navigation-prev-hover/', DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_HOVER_SUBMITED, DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_HOVER_SUCCESS);
    doptgSettingsImageUpload('thumbnails_navigation_next', 'uploads/settings/thumbnails-navigation-next/', DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_SUBMITED, DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_SUCCESS);
    doptgSettingsImageUpload('thumbnails_navigation_next_hover', 'uploads/settings/thumbnails-navigation-next-hover/', DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_HOVER_SUBMITED, DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_HOVER_SUCCESS);
    
    doptgSettingsImageUpload('thumbnail_loader', 'uploads/settings/thumb-loader/', DOPTG_ADD_THUMBNAIL_LOADER_SUBMITED, DOPTG_ADD_THUMBNAIL_LOADER_SUCCESS);
    doptgSettingsImageUpload('image_loader', 'uploads/settings/image-loader/', DOPTG_ADD_IMAGE_LOADER_SUBMITED, DOPTG_ADD_IMAGE_LOADER_SUCCESS);
    
    doptgSettingsImageUpload('navigation_prev', 'uploads/settings/navigation-prev/', DOPTG_ADD_NAVIGATION_PREV_SUBMITED, DOPTG_ADD_NAVIGATION_PREV_SUCCESS);
    doptgSettingsImageUpload('navigation_prev_hover', 'uploads/settings/navigation-prev-hover/', DOPTG_ADD_NAVIGATION_PREV_HOVER_SUBMITED, DOPTG_ADD_NAVIGATION_PREV_HOVER_SUCCESS);
    doptgSettingsImageUpload('navigation_next', 'uploads/settings/navigation-next/', DOPTG_ADD_NAVIGATION_NEXT_SUBMITED, DOPTG_ADD_NAVIGATION_NEXT_SUCCESS);
    doptgSettingsImageUpload('navigation_next_hover', 'uploads/settings/navigation-next-hover/', DOPTG_ADD_NAVIGATION_NEXT_HOVER_SUBMITED, DOPTG_ADD_NAVIGATION_NEXT_HOVER_SUCCESS);
    doptgSettingsImageUpload('navigation_lightbox', 'uploads/settings/navigation-lightbox/', DOPTG_ADD_NAVIGATION_LIGHTBOX_SUBMITED, DOPTG_ADD_NAVIGATION_LIGHTBOX_SUCCESS);
    doptgSettingsImageUpload('navigation_lightbox_hover', 'uploads/settings/navigation-lightbox-hover/', DOPTG_ADD_NAVIGATION_LIGHTBOX_HOVER_SUBMITED, DOPTG_ADD_NAVIGATION_LIGHTBOX_HOVER_SUCCESS);
        
    doptgSettingsImageUpload('lightbox_loader', 'uploads/settings/lightbox-loader/', DOPTG_ADD_LIGHTBOX_LOADER_SUBMITED, DOPTG_ADD_LIGHTBOX_LOADER_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_prev', 'uploads/settings/lightbox-navigation-prev/', DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_prev_hover', 'uploads/settings/lightbox-navigation-prev-hover/', DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_next', 'uploads/settings/lightbox-navigation-next/', DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_next_hover', 'uploads/settings/lightbox-navigation-next-hover/', DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_close', 'uploads/settings/lightbox-navigation-close/', DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUCCESS);
    doptgSettingsImageUpload('lightbox_navigation_close_hover', 'uploads/settings/lightbox-navigation-close-hover/', DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUBMITED, DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUCCESS);
    
    doptgSettingsImageUpload('social_share', 'uploads/settings/social-share/', DOPTG_SOCIAL_SHARE_SUBMITED, DOPTG_SOCIAL_SHARE_SUCCESS);
    doptgSettingsImageUpload('social_share_lightbox', 'uploads/settings/social-share-lightbox/', DOPTG_SOCIAL_SHARE_LIGHTBOX_SUBMITED, DOPTG_SOCIAL_SHARE_LIGHTBOX_SUCCESS);
        
    doptgSettingsImageUpload('slideshow_play', 'uploads/settings/slideshow-play/', DOPTG_SLIDESHOW_PLAY_SUBMITED, DOPTG_SLIDESHOW_PLAY_SUCCESS);
    doptgSettingsImageUpload('slideshow_play_hover', 'uploads/settings/slideshow-play-hover/', DOPTG_SLIDESHOW_PLAY_HOVER_SUBMITED, DOPTG_SLIDESHOW_PLAY_HOVER_SUCCESS);
    doptgSettingsImageUpload('slideshow_pause', 'uploads/settings/slideshow-pause/', DOPTG_SLIDESHOW_PAUSE_SUBMITED, DOPTG_SLIDESHOW_PAUSE_SUCCESS);
    doptgSettingsImageUpload('slideshow_pause_hover', 'uploads/settings/slideshow-pause-hover/', DOPTG_SLIDESHOW_PAUSE_HOVER_SUBMITED, DOPTG_SLIDESHOW_PAUSE_HOVER_SUCCESS);
}

function doptgSettingsFormInput(id, value, label, pre, suf, input_class, help_class, help){// Create an Input Field.
    var inputHTML = new Array();

    inputHTML.push('    <div class="setting-box">');
    inputHTML.push('        <label for="'+id+'">'+label+'</label>');
    inputHTML.push('        <span class="pre">'+pre+'</span><input type="text" class="'+input_class+'" name="'+id+'" id="'+id+'" value="'+value+'" /><span class="suf">'+suf+'</span>');
    inputHTML.push('        <a href="javascript:void()" class="'+help_class+'"><span>'+help+'</span></a>');
    inputHTML.push('        <br class="DOPTG-clear" />');
    inputHTML.push('    </div>');

    return inputHTML.join('');
}

function doptgSettingsFormSelect(id, value, label, pre, suf, input_class, help_class, help, values, valuesLabels){// Create a Combo Box.
    var selectHTML = new Array(), i,
    valuesList = values.split(';;'),
    valuesLabelsList = valuesLabels.split(';;');

    selectHTML.push('    <div class="setting-box">');
    selectHTML.push('        <label for="'+id+'">'+label+'</label>');
    selectHTML.push('        <span class="pre">'+pre+'</span>');
    selectHTML.push('            <select name="'+id+'" id="'+id+'">');
    
    for (i=0; i<valuesList.length; i++){
        if (valuesList[i] == value){
            selectHTML.push('        <option value="'+valuesList[i]+'" selected="selected">'+valuesLabelsList[i]+'</option>');
        }
        else{
            selectHTML.push('        <option value="'+valuesList[i]+'">'+valuesLabelsList[i]+'</option>');
        }
    }
    selectHTML.push('            </select>');
    selectHTML.push('        <span class="suf">'+suf+'</span>');
    selectHTML.push('        <a href="javascript:void()" class="'+help_class+'"><span>'+help+'</span></a>');
    selectHTML.push('        <br class="DOPTG-clear" />');
    selectHTML.push('    </div>');

    return selectHTML.join('');
}

function doptgSettingsFormImage(id, value, label, help_class, help){// Create an Image Field.
    var imageHTML = new Array();

    imageHTML.push('    <div class="setting-box">');
    imageHTML.push('        <label for="'+id+'">'+label+'</label>');
    imageHTML.push('        <span class="pre"></span>');
    imageHTML.push('        <div class="uploadifyContainer" style="float:left; margin:0; width:120px;">');
    imageHTML.push('            <div><input type="file" name="'+id+'" id="'+id+'" style="width:120px;" /></div>');
    imageHTML.push('            <div id="fileQueue_'+id+'"></div>');
    imageHTML.push('        </div>');
    imageHTML.push('        <a href="javascript:void()" class="'+help_class+'"><span>'+help+'</span></a>');
    imageHTML.push('        <br class="DOPTG-clear" />');
    imageHTML.push('        <label for=""></label>');
    imageHTML.push('        <span class="pre"></span>');
    imageHTML.push('        <div class="uploadifyContainer" id="'+id+'_image" style="float:left; margin:5px 0 0 0; padding:0 0 10px 0;">');
    imageHTML.push('            <img src="'+DOPTG_plugin_url+value+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
    imageHTML.push('        </div>');
    imageHTML.push('        <br class="DOPTG-clear" />');
    imageHTML.push('    </div>');

    return imageHTML.join('');
}

function doptgSettingsImageUpload(id, path, submitMessage, successMessage){
    $jDOPTG('#'+id).uploadify({
        'uploader'       : DOPTG_plugin_url+'libraries/swf/uploadify.swf',
        'script'         : DOPTG_plugin_url+'libraries/php/uploadify-settings.php?data='+DOPTG_plugin_abs+';;'+path+';;'+$jDOPTG('#blog_id').val()+'-'+$jDOPTG('#gallery_id').val(),
        'cancelImg'      : DOPTG_plugin_url+'libraries/gui/images/uploadify/cancel.png',
        'folder'         : '',
        'queueID'        : 'fileQueue_'+id,
        'buttonText'     : DOPTG_SELECT_FILE,
        'auto'           : true,
        'multi'          : false,
        'onInit'         : function(){
                               doptgResize();
                           },
        'onCancel'         : function(event,ID,fileObj,data){
                               doptgResize();
                           },
        'onSelect'       : function(event, ID, fileObj){
                               clearClick = false;
                               doptgToggleMessage('show', submitMessage);
                               setTimeout(function(){
                                   doptgResize();
                               }, 100);
                           },
        'onComplete'     : function(event, ID, fileObj, response, data){
                               if (response != -1){
                                   setTimeout(function(){
                                       doptgResize();
                                   }, 1000);
                                   $jDOPTG.post(ajaxurl, {action:'doptg_update_settings_image', item:id, gallery_id:$jDOPTG('#gallery_id').val(), path:response}, function(data){
                                       $jDOPTG('#'+id+'_image').html('<img src="'+DOPTG_plugin_url+response+'?cacheBuster='+doptgRandomString(64)+'" alt="" />');
                                       doptgToggleMessage('hide', successMessage);
                                   });
                               }
                           }
    });
}

function doptgSettingFormSwitch(id, value, label, pre, suf, help_class, help){ // Create a Switch Button
    var switchtHTML = new Array();

    switchtHTML.push('    <div class="setting-box">');
    switchtHTML.push('        <label for="">'+label+'</label>');
    switchtHTML.push('        <span class="pre">'+pre+'</span>');
    switchtHTML.push('        <div class="switch">');
    switchtHTML.push('             <input type="checkbox" name="'+id+'" id="'+id+'" class="switch-checkbox"'+(value == 'true' ? ' checked="checked"':'')+' />');
    switchtHTML.push('             <label class="switch-label" for="'+id+'">');
    switchtHTML.push('                  <div class="switch-inner"></div>');
    switchtHTML.push('                  <div class="switch-switch"></div>');
    switchtHTML.push('             </label>');
    switchtHTML.push('        </div>');
    switchtHTML.push('        <span class="suf">'+suf+'</span>');
    switchtHTML.push('        <a href="javascript:void()" class="'+help_class+'"><span>'+help+'</span></a>');
    switchtHTML.push('        <br class="DOPTG-clear" />');
    switchtHTML.push('    </div>');

    return switchtHTML.join('');
}

// Functions

function doptgRemoveColumns(no){// Clear columns content.
    if (no <= 2){
        $jDOPTG('.column-header', '.column2', '.DOPTG-admin').html('');
        $jDOPTG('.column-content', '.column2', '.DOPTG-admin').html('');
    }
    if (no <= 3){
        $jDOPTG('.column-header', '.column3', '.DOPTG-admin').html('');
        $jDOPTG('.column-content', '.column3', '.DOPTG-admin').html('');
        imageDisplay = false;
        currImage = 0;
        doptgResize();
    }
}

function doptgToggleMessage(action, message){// Display Info Messages.
    doptgResize();
    
    if (action == 'show'){
        clearClick = false;
        $jDOPTG('#DOPTG-admin-message').addClass('loader');
        $jDOPTG('#DOPTG-admin-message').html(message);
        $jDOPTG('#DOPTG-admin-message').stop(true, true).animate({'opacity':1}, 600);
    }
    else{
        clearClick = true;
        $jDOPTG('#DOPTG-admin-message').removeClass('loader');
        $jDOPTG('#DOPTG-admin-message').html(message);
        setTimeout(function(){
            $jDOPTG('#DOPTG-admin-message').stop(true, true).animate({'opacity':0}, 600, function(){
                $jDOPTG('#DOPTG-admin-message').html('');
            });
        }, 2000);
    }
}

function doptgMoveTop(){
    jQuery('html').stop(true, true).animate({'scrollTop':'0'}, 300);
    jQuery('body').stop(true, true).animate({'scrollTop':'0'}, 300);
}

function doptgRandomString(string_length){// Create a string with random elements
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz",
    random_string = '';

    for (var i=0; i<string_length; i++){
        var rnum = Math.floor(Math.random()*chars.length);
        random_string += chars.substring(rnum,rnum+1);
    }
    return random_string;
}

function doptgIdealTextColor(bgColor){
    var rgb = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(bgColor);
    
    if (rgb != null){
        return parseInt(rgb[1], 10)+parseInt(rgb[2], 10)+parseInt(rgb[3], 10) < 3*256/2 ? 'white' : 'black';
    }
    else{
        return parseInt(bgColor.substring(0, 2), 16)+parseInt(bgColor.substring(2, 4), 16)+parseInt(bgColor.substring(4, 6), 16) < 3*256/2 ? 'white' : 'black';
    }
}

function doptgShortName(name, size){// Return a short string.
    var newName = new Array(),
    pieces = name.split(''),
    i;

    if (pieces.length <= size){
        newName.push(name);
    }
    else{
        for (i=0; i<size-3; i++){
            newName.push(pieces[i]);
        }
        newName.push('...');
    }

    return newName.join('');
}