<?php
/*
  Plugin Name: Show Theme File
  Description: Displays the theme file used to render the current page.
  Author: Dan LaManna
  Author URI: http://danlamanna.com
  Version: 0.0.1
 */

function getWpTemplate() {
    if (defined('WP_USE_THEMES') && WP_USE_THEMES) {
       $template = false;
       
       if     (is_404()		   && $template = get_404_template()):
       elseif (is_search() 	   && $template = get_search_template()):
       elseif (is_tax()            && $template = get_taxonomy_template()):
       elseif (is_front_page() 	   && $template = get_front_page_template()):
       elseif (is_home()           && $template = get_home_template()):
       elseif (is_attachment() 	   && $template = get_attachment_template()):
       elseif (is_single()     	   && $template = get_single_template()):
       elseif (is_page()           && $template = get_page_template()):
       elseif (is_category() 	   && $template = get_category_template()):
       elseif (is_tag()      	   && $template = get_tag_template()):
       elseif (is_author()         && $template = get_author_template()):
       elseif (is_date()           && $template = get_date_template()):
       elseif (is_archive()        && $template = get_archive_template()):
       elseif (is_comments_popup() && $template = get_comments_popup_template()):
       elseif (is_paged()          && $template = get_paged_template()):       
       else:
	$template = get_index_template();
       endif;

       return str_replace(ABSPATH, '', $template);
    } else {
       return null;
    }
}

function outputThemeFilename() {
    global $stfOptions;

    echo '<div id="showtheme" style="position: absolute; z-index: 9999; padding: 1em; background: white; color: black; opacity: 0.8; border: dotted 2px #999;">';
    echo (getWpTemplate() !== null) ? getWpTemplate() : 'Unknown theme file';
    echo '</div>';
    return;
}

add_action('wp_head', 'outputThemeFilename');