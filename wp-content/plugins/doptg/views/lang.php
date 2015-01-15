<?php

/*
* Title                   : Thumbnail Gallery (WordPress Plugin)
* Version                 : 2.4
* File                    : lang.php
* File Version            : 2.0
* Created / Last Modified : 01 October 2013
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Thumbnail Gallery Translation.
*/

    $DOPTG_lang = array();
                
    array_push($DOPTG_lang, array('key' => 'DOPTG_TITLE', 'text' => 'Thumbnail Gallery'));

    // Loading ...
    array_push($DOPTG_lang, array('key' => 'DOPTG_LOAD', 'text' => 'Load data ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERIES_LOADED', 'text' => 'Galleries list loaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGES_LOADED', 'text' => 'Images list loaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NO_GALLERIES', 'text' => 'No galleries.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NO_IMAGES', 'text' => 'No images.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERY_LOADED', 'text' => 'Gallery data loaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_LOADED', 'text' => 'Image loaded.'));

    // Save ...
    array_push($DOPTG_lang, array('key' => 'DOPTG_SAVE', 'text' => 'Save data ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SELECT_FILE', 'text' => 'Select File'));

    // Help
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERIES_HELP', 'text' => 'Click on the "Plus" icon to add a gallery. Click on a gallery item to open the editing area. Click on the "Pencil" icon to edit galleries default settings.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERIES_EDIT_INFO_HELP', 'text' => 'Click "Submit Button" to save changes.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERY_EDIT_HELP', 'text' => 'Click on the "Plus" icon to add images. Click on an image to open the editing area. You can drag images to sort them. Click on the "Pencil" icon to edit gallery settings. Check images, select action and click "Apply" to bulk edit images.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERY_EDIT_INFO_HELP', 'text' => 'Click "Submit Button" to save changes. Images are saved automaticaly. Click "Delete Button" to delete the gallery. Click "Use Settings" to use the predefined settings; the current settings will be deleted.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGES_HELP', 'text' => 'You have 4 upload types (WordPress, AJAX, Uploadify, FTP). At least one should work.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGES_HELP_WP', 'text' => 'You can use the default WordPress Uploader. To add an image to the gallery select it from WordPress and press Insert into Post.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGES_HELP_AJAX', 'text' => 'Just a simple AJAX upload. Just select an image and the upload will start automatically.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGES_HELP_UPLOADIFY', 'text' => 'You can use this option if you want to upload a single or multiple images to your gallery. Just select the images and the upload will start automatically. Uploadify will not display the progress bar and image processing will go slower if you have a firewall enabled.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGES_HELP_FTP', 'text' => 'Copy all the images in ftp-uploads in Thumbnail Gallery plugin folder. Press Add Images to add the content of the folder to your gallery. This will take some time depending on the number and size of the images. On some servers the images names that contain other characters different from alphanumeric ones will not be uploaded. Change the names for them to work.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_EDIT_HELP', 'text' => 'Drag the mouse over the big image to select a new thumbnail. Click "Submit Button" to save the thumbnail, title, caption, media, lightbox media or enable/disable the image. Click "Delete Button" to delete the image.'));

    // Form
    array_push($DOPTG_lang, array('key' => 'DOPTG_SELECT_ACTION', 'text' => 'Select Action'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_APPLY', 'text' => 'Apply'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SUBMIT', 'text' => 'Submit'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE', 'text' => 'Delete'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ENABLE', 'text' => 'Enable'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DISABLE', 'text' => 'Disable'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DEFAULT', 'text' => 'Use Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGES_CONFIRMATION', 'text' => 'Are you sure you want to delete images?'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGES_SUBMITED', 'text' => 'Deleting images ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGES_SUCCESS', 'text' => 'You have succesfully deleted the images.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ENABLE_IMAGES_CONFIRMATION', 'text' => 'Are you sure you want to enable images?'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ENABLE_IMAGES_SUBMITED', 'text' => 'Enabling images ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ENABLE_IMAGES_SUCCESS', 'text' => 'You have succesfully enabled the images.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DISABLE_IMAGES_CONFIRMATION', 'text' => 'Are you sure you want to disable images?'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DISABLE_IMAGES_SUBMITED', 'text' => 'Disabling images ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DISABLE_IMAGES_SUCCESS', 'text' => 'You have succesfully disabled the images.'));
    
    //Form Text
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_ENABLED_TEXT', 'text' => 'Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_DISABLED_TEXT', 'text' => 'Disabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_NORMAL_TEXT', 'text' => 'Normal'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_RANDOM_TEXT', 'text' => 'Random'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_TOP_TEXT', 'text' => 'Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_TOP_RIGHT_TEXT', 'text' => 'Top-Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_RIGHT_TEXT', 'text' => 'Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_BOTTOM_RIGHT_TEXT', 'text' => 'Bottom-Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_BOTTOM_TEXT', 'text' => 'Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_BOTTOM_LEFT_TEXT', 'text' => 'Bottom-Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_LEFT_TEXT', 'text' => 'Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_TOP_LEFT_TEXT', 'text' => 'Top-Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_MOUSE_TEXT', 'text' => 'Mouse'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_ARROWS_TEXT', 'text' => 'Arrows'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_FIT_TEXT', 'text' => 'Fit'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_FORM_FULL_TEXT', 'text' => 'Full'));

    // Add Gallery
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_GALLERY_NAME', 'text' => 'New Gallery'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_GALLERY_SUBMIT', 'text' => 'Add Gallery'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_GALLERY_SUBMITED', 'text' => 'Adding gallery ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_GALERRY_SUCCESS', 'text' => 'You have succesfully added a new gallery.'));

    // Edit Galleries
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_GALLERIES_SUBMIT', 'text' => 'Edit Galleries Default Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_GALLERIES_SUCCESS', 'text' => 'You have succesfully edited the default settings.'));

    // Edit Gallery
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_GALLERY_SUBMIT', 'text' => 'Edit Gallery'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_GALLERY_SUCCESS', 'text' => 'You have succesfully edited the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_GALLERY_USE_DEFAULT_CONFIRMATION', 'text' => 'Are you sure you want to use this predefined settings. Current settings are going to be deleted?'));

    // Delete Gallery
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_GALLERY_CONFIRMATION', 'text' => 'Are you sure you want to delete this gallery?'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_GALLERY_SUBMIT', 'text' => 'Delete Gallery'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_GALLERY_SUBMITED', 'text' => 'Deleting gallery ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_GALLERY_SUCCESS', 'text' => 'You have succesfully deleted the gallery.'));

    // Add Image
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_SUBMIT', 'text' => 'Add Images'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_WP_UPLOAD', 'text' => 'Default WordPress file upload'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_SIMPLE_UPLOAD', 'text' => 'Simple AJAX file upload'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_MULTIPLE_UPLOAD', 'text' => 'Multiple files upload (Uploadify jQuery Plugin)'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_FTP_UPLOAD', 'text' => 'FTP file upload'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_SUBMITED', 'text' => 'Adding images ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_SUCCESS', 'text' => 'You have succesfully added a new image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SELECT_IMAGES', 'text' => 'Select Images'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SELECT_FTP_IMAGES', 'text' => 'Add Images'));

    // Sort Image
    array_push($DOPTG_lang, array('key' => 'DOPTG_SORT_IMAGES_SUBMITED', 'text' => 'Sorting images ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SORT_IMAGES_SUCCESS', 'text' => 'You have succesfully sorted the images.'));

    // Edit Image
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_SUBMIT', 'text' => 'Edit Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_SUCCESS', 'text' => 'You have succesfully edited the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_CROP_THUMBNAIL', 'text' => 'Crop Thumbnail'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_CURRENT_THUMBNAIL', 'text' => 'Current Thumbnail'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_TITLE', 'text' => 'Title'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_CAPTION', 'text' => 'Caption'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_MEDIA', 'text' => 'Media: Add videos (YouTube, Vimeo, ...), HTML, Flash, ...<br />IMPORTANT: Make sure that all the code is in one html tag. Iframe embedding code will work :).'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_LIGHTBOX_MEDIA', 'text' => 'Lightbox Media: Add Media that will appear in lightbox. It will appear instead of Image or Default Media.<br />IMPORTANT: Make sure that all the code is in one html tag.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_EDIT_IMAGE_ENABLED', 'text' => 'Enabled'));

    // Delete Image
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGE_CONFIRMATION', 'text' => 'Are you sure you want to delete this image?'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGE_SUBMIT', 'text' => 'Delete Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGE_SUBMITED', 'text' => 'Deleting image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DELETE_IMAGE_SUCCESS', 'text' => 'You have succesfully deleted the image.'));

    // TinyMCE
    array_push($DOPTG_lang, array('key' => 'DOPTG_TINYMCE_ADD', 'text' => 'Add Thumbnail Gallery'));

    // Settings
    array_push($DOPTG_lang, array('key' => 'DOPTG_DEFAULT_SETTINGS', 'text' => 'Default Settings'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_GENERAL_STYLES_SETTINGS', 'text' => 'General Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERY_NAME', 'text' => 'Name'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DATA_PARSE_METHOD', 'text' => 'Gallery Data Parse Method'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDTH', 'text' => 'Width'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_HEIGHT', 'text' => 'Height'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_BG_COLOR', 'text' => 'Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_BG_ALPHA', 'text' => 'Background Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGES_ORDER', 'text' => 'Images Order'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_RESPONSIVE_ENABLED', 'text' => 'Responsive Enabled'));    

    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_STYLES_SETTINGS', 'text' => 'Thumbnails Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_POSITION', 'text' => 'Thumbnails Position'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_OVER_IMAGE', 'text' => 'Thumbnails Over Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_BG_COLOR', 'text' => 'Thumbnails Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_BG_ALPHA', 'text' => 'Thumbnails Background Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_SPACING', 'text' => 'Thumbnails Spacing'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_TOP', 'text' => 'Thumbnails Padding Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_RIGHT', 'text' => 'Thumbnails Padding Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_BOTTOM', 'text' => 'Thumbnails Padding Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_LEFT', 'text' => 'Thumbnails Padding Left'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_STYLES_SETTINGS', 'text' => 'Thumbnails Navigation Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION', 'text' => 'Thumbnails Navigation Type'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_PREV', 'text' => 'Thumbnails Navigation Previous Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_SUBMITED', 'text' => 'Uploading previous button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_SUCCESS', 'text' => 'Previous button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_PREV_HOVER', 'text' => 'Thumbnails Navigation Previous Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_HOVER_SUBMITED', 'text' => 'Uploading previous button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_PREV_HOVER_SUCCESS', 'text' => 'Previous button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_NEXT', 'text' => 'Thumbnails Navigation Next Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_SUBMITED', 'text' => 'Uploading next button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_SUCCESS', 'text' => 'Next button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_NEXT_HOVER', 'text' => 'Thumbnails Navigation Next Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_HOVER_SUBMITED', 'text' => 'Uploading next button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAILS_NAVIGATION_NEXT_HOVER_SUCCESS', 'text' => 'Next button hover image uploaded.'));
        
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_STYLES_SETTINGS', 'text' => 'Styles & Settings for a Thumbnail'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_LOADER', 'text' => 'Thumbnail Loader'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAIL_LOADER_SUBMITED', 'text' => 'Adding thumbnail loader...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_THUMBNAIL_LOADER_SUCCESS', 'text' => 'Thumbnail loader added.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_WIDTH', 'text' => 'Thumbnail Width'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_HEIGHT', 'text' => 'Thumbnail Height'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_WIDTH_MOBILE', 'text' => 'Mobile Thumbnail Width'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_HEIGHT_MOBILE', 'text' => 'Mobile Thumbnail Height'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA', 'text' => 'Thumbnail Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA_HOVER', 'text' => 'Thumbnail Alpha Hover'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA_SELECTED', 'text' => 'Thumbnail Alpha Selected'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR', 'text' => 'Thumbnail Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR_HOVER', 'text' => 'Thumbnail Background Color Hover'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR_SELECTED', 'text' => 'Thumbnail Background Color Selected'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_SIZE', 'text' => 'Thumbnail Border Size'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR', 'text' => 'Thumbnail Border Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR_HOVER', 'text' => 'Thumbnail Border Color Hover'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR_SELECTED', 'text' => 'Thumbnail Border Color Selected'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_TOP', 'text' => 'Thumbnail Padding Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_RIGHT', 'text' => 'Thumbnail Padding Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_BOTTOM', 'text' => 'Thumbnail Padding Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_LEFT', 'text' => 'Thumbnail Padding Left'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_STYLES_SETTINGS', 'text' => 'Image Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_LOADER', 'text' => 'Image Loader'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_LOADER_SUBMITED', 'text' => 'Uploading image loader...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_IMAGE_LOADER_SUCCESS', 'text' => 'Image loader uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_BG_COLOR', 'text' => 'Image Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_BG_ALPHA', 'text' => 'Image Background Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_DISPLAY_TYPE', 'text' => 'Image Display Type'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_DISPLAY_TIME', 'text' => 'Image Display Time'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_TOP', 'text' => 'Image Margin Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_RIGHT', 'text' => 'Image Margin Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_BOTTOM', 'text' => 'Image Margin Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_LEFT', 'text' => 'Image Margin Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_TOP', 'text' => 'Image Padding Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_RIGHT', 'text' => 'Image Padding Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_BOTTOM', 'text' => 'Image Padding Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_LEFT', 'text' => 'Image Padding Left'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_STYLES_SETTINGS', 'text' => 'Navigation Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_ENABLED', 'text' => 'Navigation Buttons Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_OVER_IMAGE', 'text' => 'Navigation Over Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_PREV', 'text' => 'Navigation Previous Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_PREV_SUBMITED', 'text' => 'Uploading previous button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_PREV_SUCCESS', 'text' => 'Previous button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_PREV_HOVER', 'text' => 'Navigation Previous Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_PREV_HOVER_SUBMITED', 'text' => 'Uploading previous button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_PREV_HOVER_SUCCESS', 'text' => 'Previous button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_NEXT', 'text' => 'Navigation Next Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_NEXT_SUBMITED', 'text' => 'Uploading next button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_NEXT_SUCCESS', 'text' => 'Next button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_NEXT_HOVER', 'text' => 'Navigation Next Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_NEXT_HOVER_SUBMITED', 'text' => 'Uploading next button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_NEXT_HOVER_SUCCESS', 'text' => 'Next button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_LIGHTBOX', 'text' => 'Navigation Lightbox Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_LIGHTBOX_SUBMITED', 'text' => 'Uploading lightbox button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_LIGHTBOX_SUCCESS', 'text' => 'Lightbox button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_LIGHTBOX_HOVER', 'text' => 'Navigation Lightbox Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_LIGHTBOX_HOVER_SUBMITED', 'text' => 'Uploading lightbox button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_NAVIGATION_LIGHTBOX_HOVER_SUCCESS', 'text' => 'Lightbox button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED', 'text' => 'Swipe Navigation Enabled'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_STYLES_SETTINGS', 'text' => 'Image Caption Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_WIDTH', 'text' => 'Caption Width'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_HEIGHT', 'text' => 'Caption Height'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_TITLE_COLOR', 'text' => 'Caption Title Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_TEXT_COLOR', 'text' => 'Caption Text Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_BG_COLOR', 'text' => 'Caption Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_BG_ALPHA', 'text' => 'Caption Background Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_POSITION', 'text' => 'Caption Position'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_OVER_IMAGE', 'text' => 'Caption Over Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_SCROLL_SCRUB_COLOR', 'text' => 'Caption Scroll Scrub Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_SCROLL_BG_COLOR', 'text' => 'Caption Scroll Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_TOP', 'text' => 'Caption Margin Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_RIGHT', 'text' => 'Caption Margin Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_BOTTOM', 'text' => 'Caption Margin Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_LEFT', 'text' => 'Caption Margin Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_TOP', 'text' => 'Caption Padding Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_RIGHT', 'text' => 'Caption Padding Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_BOTTOM', 'text' => 'Caption Padding Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_LEFT', 'text' => 'Caption Padding Left'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_STYLES_SETTINGS', 'text' => 'Lightbox Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_ENABLED', 'text' => 'Lightbox Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_WINDOW_COLOR', 'text' => 'Lightbox Window Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_WINDOW_ALPHA', 'text' => 'Lightbox Window Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_LOADER', 'text' => 'Lightbox Loader'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_LOADER_SUBMITED', 'text' => 'Adding lightbox loader...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_LOADER_SUCCESS', 'text' => 'Lightbox loader added.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_BACKGROUND_COLOR', 'text' => 'Lightbox Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_BACKGROUND_ALPHA', 'text' => 'Lightbox Background Alpha'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_TOP', 'text' => 'Lightbox Margin Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_RIGHT', 'text' => 'Lightbox Margin Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_BOTTOM', 'text' => 'Lightbox Margin Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_LEFT', 'text' => 'Lightbox Margin Left'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_TOP', 'text' => 'Lightbox Padding Top'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_RIGHT', 'text' => 'Lightbox Padding Right'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_BOTTOM', 'text' => 'Lightbox Padding Bottom'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_LEFT', 'text' => 'Lightbox Padding Left'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_STYLES_SETTINGS', 'text' => 'Lightbox Navigation Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_PREV', 'text' => 'Lightbox Navigation Previous Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_SUBMITED', 'text' => 'Uploading previous button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_SUCCESS', 'text' => 'Previous button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_PREV_HOVER', 'text' => 'Lightbox Navigation Previous Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUBMITED', 'text' => 'Uploading previous button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUCCESS', 'text' => 'Previous button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_NEXT', 'text' => 'Lightbox Navigation Next Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUBMITED', 'text' => 'Uploading next button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUCCESS', 'text' => 'Next button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_NEXT_HOVER', 'text' => 'Lightbox Navigation Next Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUBMITED', 'text' => 'Uploading next button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUCCESS', 'text' => 'Next button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_CLOSE', 'text' => 'Lightbox Navigation Close Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUBMITED', 'text' => 'Uploading close button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUCCESS', 'text' => 'Close button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_CLOSE_HOVER', 'text' => 'Lightbox Navigation Close Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUBMITED', 'text' => 'Uploading close button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUCCESS', 'text' => 'Close button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_INFO_BG_COLOR', 'text' => 'Lightbox Navigation Info Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_INFO_TEXT_COLOR', 'text' => 'Lightbox Navigation Info Text Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED', 'text' => 'Swipe Lightbox Navigation Enabled'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_STYLES_SETTINGS', 'text' => 'Social Share Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_ENABLED', 'text' => 'Social Share Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE', 'text' => 'Social Share Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_SUBMITED', 'text' => 'Uploading social share button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_SUCCESS', 'text' => 'Social share button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_LIGHTBOX', 'text' => 'Lightbox Social Share Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_LIGHTBOX_SUBMITED', 'text' => 'Uploading lightbox social share button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_LIGHTBOX_SUCCESS', 'text' => 'Lightbox social share button image uploaded.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_STYLES_SETTINGS', 'text' => 'Tooltip Styles & Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_ENABLED', 'text' => 'Tooltip Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_BG_COLOR', 'text' => 'Tooltip Background Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_STROKE_COLOR', 'text' => 'Tooltip Stroke Color'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_TEXT_COLOR', 'text' => 'Tooltip Text Color'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_SETTINGS', 'text' => 'Slideshow Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW', 'text' => 'Slideshow Enabled'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_TIME', 'text' => 'Slideshow Time'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_AUTOSTART', 'text' => 'Slideshow Autostart'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_LOOP', 'text' => 'Slideshow Loop'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY', 'text' => 'Slideshow Play Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_SUBMITED', 'text' => 'Uploading slideshow play button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_SUCCESS', 'text' => 'Slideshow play button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_HOVER', 'text' => 'Slideshow Play Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_HOVER_SUBMITED', 'text' => 'Uploading slideshow play button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_HOVER_SUCCESS', 'text' => 'Slideshow play button hover image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE', 'text' => 'Slideshow Pause Button Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_SUBMITED', 'text' => 'Uploading slideshow pause button image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_SUCCESS', 'text' => 'Slideshow pause button image uploaded.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_HOVER', 'text' => 'Slideshow Pause Button Hover Image'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_HOVER_SUBMITED', 'text' => 'Uploading slideshow pause button hover image ...'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_HOVER_SUCCESS', 'text' => 'Slideshow pause button hover image uploaded.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_AUTO_HIDE_SETTINGS', 'text' => 'Auto Hide Settings'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_AUTO_HIDE', 'text' => 'Auto Hide Thumbnails and Buttons'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_AUTO_HIDE_TIME', 'text' => 'Auto Hide Time'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_GO_TOP', 'text' => 'go top'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_GALLERY_NAME_INFO', 'text' => 'Change gallery name.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_DATA_PARSE_METHOD_INFO', 'text' => 'Gallery Data Parse Method (AJAX, HTML). Default value: AJAX. Set the method by which the data will be parsed to the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDTH_INFO', 'text' => 'Width (value in pixels). Default value: 900. Set the width of the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_HEIGHT_INFO', 'text' => 'Height (value in pixels). Default value: 600. Set the height of the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_BG_COLOR_INFO', 'text' => 'Background Color (color hex code). Default value: f1f1f1. Set gallery backgrund color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_BG_ALPHA_INFO', 'text' => 'Background Alpha (value from 0 to 100). Default value: 100. Set gallery alpha.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGES_ORDER_INFO', 'text' => 'Images Order (Normal, Random). Default value: Normal. Set images order.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_RESPONSIVE_ENABLED_INFO', 'text' => 'Responsive Enabled (Enabled, Disabled). Default value: Enabled. Enable responsive layout.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_POSITION_INFO', 'text' => 'Thumbnails Position (Top, Right, Bottom, Left). Default value: Bottom. Set the position of the thumbnails in the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_OVER_IMAGE_INFO', 'text' => 'Thumbnails Over Image (Enabled, Disabled). Default value: Disabled. If the value is true the thumbnails will be displayed over the big image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_BG_COLOR_INFO', 'text' => 'Thumbnails Background Color (color hex code). Default value: f1f1f1. Set the color for the thumbnails background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_BG_ALPHA_INFO', 'text' => 'Thumbnails Background Alpha (value from 0 to 100). Default value: 100. Set the transparancy for the thumbnails background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_SPACING_INFO', 'text' => 'Thumbnails Spacing (value in pixels). Default value: 5. Set the space between thumbnails.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_TOP_INFO', 'text' => 'Thumbnails Padding Top (value in pixels). Default value: 0. Set the top padding for the thumbnails.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_RIGHT_INFO', 'text' => 'Thumbnails Padding Right (value in pixels). Default value: 5. Set the right padding for the thumbnails.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_BOTTOM_INFO', 'text' => 'Thumbnails Padding Bottom (value in pixels). Default value: 5. Set the bottom padding for the thumbnails.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_PADDING_LEFT_INFO', 'text' => 'Thumbnails Padding Left (value in pixels). Default value: 5. Set the left padding for the thumbnails.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_INFO', 'text' => 'Thumbnails Navigation Type (Mouse, Arrows). Default value: Mouse. Set the thumbnails navigation type.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_PREV_INFO', 'text' => 'Thumbnails Navigation Previous Button Image (path to image). Upload the image for thumbnails navigation previous button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_PREV_HOVER_INFO', 'text' => 'Thumbnails Navigation Previous Button Hover Image (path to image). Upload the image for thumbnails navigation previous hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_NEXT_INFO', 'text' => 'Thumbnails Navigation Next Button Image (path to image). Upload the image for thumbnails navigation next button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAILS_NAVIGATION_NEXT_HOVER_INFO', 'text' => 'Thumbnails Navigation Next Button Hover Image (path to image). Upload the image for thumbnails navigation next hover button.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_LOADER_INFO', 'text' => 'Thumbnail Loader (path to image). Set the loader for the thumbnails.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_WIDTH_INFO', 'text' => 'Thumbnail Width (the size in pixels). Default value: 60. Set the width of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_HEIGHT_INFO', 'text' => 'Thumbnail Height (the size in pixels). Default value: 60. Set the height of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_WIDTH_MOBILE_INFO', 'text' => 'Mobile Thumbnail Width (the size in pixels). Default value: 60. Set the width of a thumbnail on mobile devices.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_HEIGHT_MOBILE_INFO', 'text' => 'Mobile Thumbnail Height (the size in pixels). Default value: 60. Set the height of a thumbnail on mobile devices.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA_INFO', 'text' => 'Thumbnail Alpha (value from 0 to 100). Default value: 50. Set the transparancy of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA_HOVER_INFO', 'text' => 'Thumbnail Alpha Hover (value from 0 to 100). Default value: 100. Set the transparancy of a thumbnail when hover.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_ALPHA_SELECTED_INFO', 'text' => 'Thumbnail Alpha Selected (value from 0 to 100). Default value: 100. Set the transparancy of a thumbnail when selected.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR_INFO', 'text' => 'Thumbnail Background Color (color hex code). Default value: f1f1f1. Set the color of a thumbnail background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR_HOVER_INFO', 'text' => 'Thumbnail Background Color Hover (color hex code). Default value: 000000. Set the color of a thumbnail background when hover.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BG_COLOR_SELECTED_INFO', 'text' => 'Thumbnail Background Color Selected (color hex code). Default value: 000000. Set the color of a thumbnail background when selected.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_SIZE_INFO', 'text' => 'Thumbnail Border Size (value in pixels). Default value: 2. Set the size of a thumbnail border.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR_INFO', 'text' => 'Thumbnail Border Color (color hex code). Default value: f1f1f1. Set the color of a thumbnail border.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR_HOVER_INFO', 'text' => 'Thumbnail Border Color Hover (color hex code). Default value: 000000. Set the color of a thumbnail border when hover.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_BORDER_COLOR_SELECTED_INFO', 'text' => 'Thumbnail Border Color Selected (color hex code). Default value: 000000. Set the color of a thumbnail border when selected.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_TOP_INFO', 'text' => 'Thumbnail Padding Top (value in pixels). Default value: 0. Set top padding value of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_RIGHT_INFO', 'text' => 'Thumbnail Padding Right (value in pixels). Default value: 0. Set right padding value of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_BOTTOM_INFO', 'text' => 'Thumbnail Padding Bottom (value in pixels). Default value: 0. Set bottom padding value of a thumbnail.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_THUMBNAIL_PADDING_LEFT_INFO', 'text' => 'Thumbnail Padding Left (value in pixels). Default value: 0. Set left padding value of a thumbnail.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_LOADER_INFO', 'text' => 'Image Loader (path to image). Set the loader for the big image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_BG_COLOR_INFO', 'text' => 'Image Background Color (color hex code). Default value: afafaf. Set the color for the image background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_BG_ALPHA_INFO', 'text' => 'Image Background Alpha (value from 0 to 100). Default value: 100. Set the transparancy for the image background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_DISPLAY_TYPE_INFO', 'text' => 'Image Display Type (Fit, Full). Default value: Fit. Set image display type. The fit value will display the all image. The full value will display the image on the all stage, padding and margin values will not be taken into consideration.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_DISPLAY_TIME_INFO', 'text' => 'Image Display Time (time in miliseconds). Default value: 1000. Set image display duration.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_TOP_INFO', 'text' => 'Image Margin Top (value in pixels). Default value: 20. Set top margin value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_RIGHT_INFO', 'text' => 'Image Margin Right (value in pixels). Default value: 20. Set right margin value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_BOTTOM_INFO', 'text' => 'Image Margin Bottom (value in pixels). Default value: 20. Set bottom margin value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_MARGIN_LEFT_INFO', 'text' => 'Image Margin Left (value in pixels). Default value: 20. Set top left value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_TOP_INFO', 'text' => 'Image Padding Top (value in pixels). Default value: 5. Set top padding value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_RIGHT_INFO', 'text' => 'Image Padding Right (value in pixels). Default value: 5. Set right padding value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_BOTTOM_INFO', 'text' => 'Image Padding Bottom (value in pixels). Default value: 5. Set bottom padding value for the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_IMAGE_PADDING_LEFT_INFO', 'text' => 'Image Padding Left (value in pixels). Default value: 5. Set left padding value for the image.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_ENABLED_INFO', 'text' => 'Enable Navigation (Enabled, Disabled). Default value: Enabled. Enable navigation buttons.'));    
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_OVER_IMAGE_INFO', 'text' => 'Navigation Over Image (Enabled, Disabled). Default value: Enabled. Show navigation buttons over or outside the image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_PREV_INFO', 'text' => 'Navigation Previous Button Image (path to image). Upload the image for navigation previous button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_PREV_HOVER_INFO', 'text' => 'Navigation Previous Button Hover Image (path to image). Upload the image for navigation previous hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_NEXT_INFO', 'text' => 'Navigation Next Button Image (path to image). Upload the image for navigation next button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_NEXT_HOVER_INFO', 'text' => 'Navigation Next Button Hover Image (path to image). Upload the image for navigation next hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_LIGHTBOX_INFO', 'text' => 'Navigation Lightbox Button Image (path to image). Upload the image for navigation lightbox button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_LIGHTBOX_HOVER_INFO', 'text' => 'Navigation Lightbox Button Hover Image (path to image). Upload the image for navigation lightbox hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED_INFO', 'text' => 'Swipe Navigation Enabled (Enabled, Disabled). Default value: Enabled. Enable swipe navigation on touch devices.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_WIDTH_INFO', 'text' => 'Caption Width (value in pixels). Default value: 900. Set caption width.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_HEIGHT_INFO', 'text' => 'Caption Height (value in pixels). Default value: 75. Set caption height.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_TITLE_COLOR_INFO', 'text' => 'Caption Title Color (color hex code). Default value: 000000. Set caption title color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_TEXT_COLOR_INFO', 'text' => 'Caption Text Color (color hex code). Default value: 000000. Set caption text color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_BG_COLOR_INFO', 'text' => 'Caption Background Color (color hex code). Default value: ffffff. Set caption background color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_BG_ALPHA_INFO', 'text' => 'Caption Background Alpha (value from 0 to 100). Default value: 50. Set caption alpha color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_POSITION_INFO', 'text' => 'Caption Position (Top, Right, Bottom, Left, Top-Left, Top-Right, Bottom-Left, Bottom-Right). Default value: Bottom. Set caption position.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_OVER_IMAGE_INFO', 'text' => 'Caption Over Image (Enabled, Disabled). Default value: Enabled. Display caption over image, or not.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_SCROLL_SCRUB_COLOR_INFO', 'text' => 'Caption Scroll Scrub Color (color hex code). Default value: 777777. Set scroll scrub color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_SCROLL_BG_COLOR_INFO', 'text' => 'Caption Scroll Background Color (color hex code). Default value: e0e0e0. Set scroll background color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_TOP_INFO', 'text' => 'Caption Margin Top (value in pixels). Default value: 0. Set caption top margin.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_RIGHT_INFO', 'text' => 'Caption Margin Right (value in pixels). Default value: 0. Set caption right margin.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_BOTTOM_INFO', 'text' => 'Caption Margin Bottom (value in pixels). Default value: 0. Set caption bottom margin.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_MARGIN_LEFT_INFO', 'text' => 'Caption Margin Left (value in pixels). Default value: 0. Set caption left margin.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_TOP_INFO', 'text' => 'Caption Padding Top (value in pixels). Default value: 10. Set caption top padding.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_RIGHT_INFO', 'text' => 'Caption Padding Right (value in pixels). Default value: 10. Set caption right padding.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_BOTTOM_INFO', 'text' => 'Caption Padding Bottom (value in pixels). Default value: 10. Set caption bottom padding.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_CAPTION_PADDING_LEFT_INFO', 'text' => 'Caption Padding Left (value in pixels). Default value: 10. Set caption left padding.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_ENABLED_INFO', 'text' => 'Enable Lightbox (Enabled, Disabled). Default value: Enabled. Enable the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_WINDOW_COLOR_INFO', 'text' => 'Lightbox Window Color (color hex code). Default value: 000000. Set the color for the lightbox window.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_WINDOW_ALPHA_INFO', 'text' => 'Lightbox Window Alpha (value from 0 to 100). Default value: 80. Set the transparancy for the lightbox window.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_LOADER_INFO', 'text' => 'Lightbox Loader (path to image). Set the loader for the lightbox image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_BACKGROUND_COLOR_INFO', 'text' => 'Lightbox Background Color (color hex code). Default value: 000000. Set the color for the lightbox background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_BACKGROUND_ALPHA_INFO', 'text' => 'Lightbox Background Alpha (value from 0 to 100). Default value: 100. Set the transparancy for the lightbox background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_TOP_INFO', 'text' => 'Lightbox Margin Top (value in pixels). Default value: 70. Set top margin value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_RIGHT_INFO', 'text' => 'Lightbox Margin Right (value in pixels). Default value: 70. Set right margin value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_BOTTOM_INFO', 'text' => 'Lightbox Margin Bottom (value in pixels). Default value: 70. Set bottom margin value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_MARGIN_LEFT_INFO', 'text' => 'Lightbox Margin Left (value in pixels). Default value: 70. Set top left value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_TOP_INFO', 'text' => 'Lightbox Padding Top (value in pixels). Default value: 10. Set top padding value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_RIGHT_INFO', 'text' => 'Lightbox Padding Right (value in pixels). Default value: 10. Set right padding value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_BOTTOM_INFO', 'text' => 'Lightbox Padding Bottom (value in pixels). Default value: 10. Set bottom padding value for the lightbox.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_PADDING_LEFT_INFO', 'text' => 'Lightbox Padding Left (value in pixels). Default value: 10. Set left padding value for the lightbox.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_PREV_INFO', 'text' => 'Lightbox Navigation Previous Button Image (path to image). Upload the image for lightbox navigation previous button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_PREV_HOVER_INFO', 'text' => 'Lightbox Navigation Previous Button Hover Image (path to image). Upload the image for lightbox navigation previous hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_NEXT_INFO', 'text' => 'Lightbox Navigation Next Button Image (path to image). Upload the image for lightbox navigation next button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_NEXT_HOVER_INFO', 'text' => 'Lightbox Navigation Next Button Hover Image (path to image). Upload the image for lightbox navigation next hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_CLOSE_INFO', 'text' => 'Lightbox Navigation Close Button Image (path to image). Upload the image for lightbox navigation close button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_CLOSE_HOVER_INFO', 'text' => 'Lightbox Navigation Close Button Hover Image (path to image). Upload the image for lightbox navigation close hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_INFO_BG_COLOR_INFO', 'text' => 'Lightbox Navigation Info Background Color (color hex code). Default value: 000000. Set the color for the lightbox info background.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_INFO_TEXT_COLOR_INFO', 'text' => 'Lightbox Navigation Info Text Color (color hex code). Default value: dddddd. Set the color for the lightbox info text.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_LIGHTBOX_NAVIGATION_TOUCH_DEVICE_SWIPE_ENABLED_INFO', 'text' => 'Swipe Lightbox Navigation Enabled (Enabled, Disabled). Default value: Enabled. Enable swipe lightbox navigation on touch devices.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_ENABLED_INFO', 'text' => 'Social Share Enabled (Enabled, Disabled). Default value: Enabled. Enable AddThis Social Share.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_INFO', 'text' => 'Social Share Button Image (path to image). Upload the image for social share button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SOCIAL_SHARE_LIGHTBOX_INFO', 'text' => 'Lightbox Social Share Button Image (path to image). Upload the image for lightbox social share button.'));
    
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_ENABLED_INFO', 'text' => 'Tooltip Enabled (Enabled, Disabled). Default value: Disabled. Enable the tooltip. The gallery item needs to have a title for tooltip to work.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_BG_COLOR_INFO', 'text' => 'Tooltip Background Color (color hex code). Default value: ffffff. Set tooltip background color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_STROKE_COLOR_INFO', 'text' => 'Tooltip Stroke Color (color hex code). Default value: 000000. Set tooltip stroke color.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_TOOLTIP_TEXT_COLOR_INFO', 'text' => 'Tooltip Text Color (color hex code). Default value: 000000. Set tooltip text color.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_INFO', 'text' => 'Slideshow (Enabled, Disabled). Default value: Disabled. Enable or disable the slideshow.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_TIME_INFO', 'text' => 'Slideshow Time (time in miliseconds). Default: 5000. How much time an image stays until it passes to the next one.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_AUTOSTART_INFO', 'text' => 'Slideshow Autostart (Enabled, Disabled). Default: true. Set it to true if you want the slideshow to start after imediatly after gallery is displayed.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_LOOP_INFO', 'text' => 'Slideshow Loop (Enabled, Disabled). Default: true. Set it to false if you want the slideshow to stop when it reaches the last image.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_INFO', 'text' => 'Slideshow Play Button Image (path to image). Upload the image for slideshow play button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PLAY_HOVER_INFO', 'text' => 'Slideshow Play Button Hover Image (path to image). Upload the image for slideshow play hover button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_INFO', 'text' => 'Slideshow Pause Button Image (path to image). Upload the image for slideshow pause button.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_SLIDESHOW_PAUSE_HOVER_INFO', 'text' => 'Slideshow Pause Button Hover Image (path to image). Upload the image for slideshow pause hover button.'));

    array_push($DOPTG_lang, array('key' => 'DOPTG_AUTO_HIDE_INFO', 'text' => 'Auto Hide Thumbnails and Buttons (Enabled, Disabled). Default: false. Hide the thumbnails and buttons and display them when you hover the gallery.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_AUTO_HIDE_TIME_INFO', 'text' => 'Auto Hide Time (time in miliseconds). Default: 2000. Set the time after which the thumbnails and buttons hide.'));

    // Widget    
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDGET_TITLE', 'text' => 'Thumbnail Gallery'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDGET_DESCRIPTION', 'text' => 'Select the ID of the Gallery you want in the widget.'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDGET_LABEL_TITLE', 'text' => 'Title:'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDGET_LABEL_ID', 'text' => 'Select Gallery ID:'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_WIDGET_NO_SCROLLERS', 'text' => 'No galleries.'));
    
    // Help
    array_push($DOPTG_lang, array('key' => 'DOPTG_HELP_DOCUMENTATION', 'text' => 'Documentation'));
    array_push($DOPTG_lang, array('key' => 'DOPTG_HELP_FAQ', 'text' => 'FAQ'));
    
    for ($i=0; $i<count($DOPTG_lang); $i++){
        define($DOPTG_lang[$i]['key'], $DOPTG_lang[$i]['text']);
    }

?>