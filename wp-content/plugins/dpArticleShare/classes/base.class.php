<?php
/*
 * DP Article Share
 *
 * Copyright 2013, Diego Pereyra
 *
 * @Web: http://www.dpereyra.com
 * @Email: info@dpereyra.com
 *
 * Base Class
 */

class DpArticleShare {
	
	var $nonce;
	var $is_admin = false;
	var $post_share_id = '';
	var $wpdb = null;
	var $translation = array( 
					   );
	
	function DpArticleShare( $is_admin = false, $widget = '', $post_share_id = '' ) 
	{
		global $table_prefix;

		$this->widget = $widget;
		if($is_admin) { $this->is_admin = true; }
		if($post_share_id != "") { $this->post_share_id = $post_share_id; }
		if(isset($translation)) { $this->translation = $translation; }
		
		$this->nonce = rand();
		
    }
		
	function getNonce() {
		
		return $this->nonce;
	}
	
	function addScripts() {
		global $dpArticleShare;
		$return = '';
		
		return $return;
	}
	
	function output($force_more = false) {
		global $dpArticleShare, $post;

		//wp_reset_query();
		if ( is_feed() ) {
			return;
		}
		
		if($this->post_share_id != "") {
			$post = get_post($this->post_share_id); 
		}
		
		$url = get_permalink($post->ID);
		if(empty($url)) { $url = home_url(); }
		$short_url = dpArticleShare_url_shortener($url, $post->ID);

		$text = get_the_title($post->ID);
		if(empty($text)) { $text = get_bloginfo('name'); }
		$text = strip_tags($text);
		
		$share_text = $dpArticleShare['i18n_share_on']." ";
		
		$dp_article_share_position = get_post_meta($post->ID, 'dp_article_share_position', true);
		$dp_article_share_counter = get_post_meta($post->ID, 'dp_article_share_counter', true);
		$dp_article_share_skin = get_post_meta($post->ID, 'dp_article_share_skin', true);
		$dp_article_share_tooltip = get_post_meta($post->ID, 'dp_article_share_tooltip', true);
		$dp_article_share_counter_position = get_post_meta($post->ID, 'dp_article_share_counter_position', true);

		$position = $dpArticleShare['position'];
		$vertical_offset = (is_numeric($dpArticleShare['vertical_offset']) ? $dpArticleShare['vertical_offset'] : '0');
	
		if($dp_article_share_position != "") {
			$position = $dp_article_share_position;
		}
		
		if($position == "horizontal-top-bottom") {
			$position = "horizontal-top";
		}
		
		$html .= '
			<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';
		if($position != 'vertical' && $position != 'vertical-inside') {
			$html .= '<div class="clear-article-share"></div>';
		}
		
		$count_loop = 0;
		$update_required = 0;
		$last_update = get_post_meta($post->ID, 'share_last_update', true);
		if(!is_numeric($last_update) || $last_update <= (time() - (15 * 60))) {
			$update_required = 1;
		}
		
		$html = '<ul class="dpArticleShare '.$position.' '.($dp_article_share_skin != "" ? $dp_article_share_skin : $dpArticleShare['skin']).' '.($dp_article_share_counter_position != "" ? 'counter-'.$dp_article_share_counter_position : 'counter-'.$dpArticleShare['counter_position']).'" data-article-id="'.$post->ID.'" data-vertical-offset="'.$vertical_offset.'" data-update-required="'.$update_required.'" style="display:none;">';
		foreach($dpArticleShare['social_icons_arr'] as $key=>$value) {
			if(!$value['active']) { continue; }
			$count_loop++;
			$no_count = false;
			if((!$dpArticleShare['show_counter'] && $dp_article_share_counter != "yes") || $dp_article_share_counter == "no") {
				$no_count = true;	
			}
			$href = "javascript:void(0);";
			$count = dpArticleShare_get_post_shares($post->ID);
			$counter = 0;
			$tooltip = '';
			$icon = "";

			switch($key) {
				case 'facebook':
					$icon = 'fb';
					$counter = $count['facebook'];
					$tooltip = $share_text.'Facebook';
					break;
				case 'twitter':
					$icon = 'tw';
					$counter = $count['twitter'];
					$tooltip = $share_text.'Twitter';
					break;
				case 'google':
					$icon = 'g';
					$counter = $count['gplus'];
					$tooltip = $share_text.'Google Plus';
					break;
				case 'linkedin':
					$icon = 'linkedin';
					$counter = $count['linkedin'];
					$tooltip = $share_text.'LinkedIn';
					break;
				case 'pinterest':
					$icon = 'pinterest';
					$counter = $count['pinterest'];
					$tooltip = $share_text.'Pinterest';
					break;
				case 'stumbleupon':
					$icon = 'stumbleupon';
					$counter = $count['stumbleupon'];
					$tooltip = $share_text.'Stumbleupon';
					break;
				case 'delicious':
					$icon = 'delicious';
					$counter = $count['delicious'];
					$tooltip = $share_text.'Delicious';
					break;
				case 'digg':
					$icon = 'digg';
					$counter = $count['digg'];
					$no_count = true;
					$tooltip = $share_text.'Digg';
					break;
				case 'tumblr':
					$icon = 'tumblr';
					$counter = $count['tumblr'];
					$no_count = true;
					$tooltip = $share_text.'Tumblr';
					break;
				case 'reddit':
					$icon = 'reddit';
					$counter = $count['reddit'];
					$tooltip = $share_text.'Reddit';
					break;
				case 'blogger':
					$icon = 'blogger';
					$counter = $count['blogger'];
					$tooltip = $share_text.'Blogger';
					$no_count = true;
					break;
				case 'buffer':
					$icon = 'buffer';
					$counter = $count['buffer'];
					$tooltip = $share_text.'Buffer';
					break;
				case 'vk':
					$icon = 'vk';
					$counter = $count['vk'];
					$tooltip = $share_text.'Vkontakte';
					break;
				case 'email':
					$icon = 'email';
					$href = 'mailto:?Subject='.$text.'&body='.$dpArticleShare['i18n_email_body'].' '.$url;
					$no_count = true;
					$tooltip = $dpArticleShare['i18n_email'];
					break;
				case 'comments':
					/*
					if(!is_singular()) {
						break;	
					}*/
					$icon = 'comment';
					
					if($dpArticleShare['disqus_enabled']) {
						$counter = dpArticleShare_get_disqus_counter($url);		
						if(is_singular()) {
							$href = '#dsq-2';
						} else {
							$href = get_permalink($post->ID).'#dsq-2';
						}
										
					} else {
						$comments_count = wp_count_comments($post->ID);
						$counter = $comments_count->approved;
						if(is_singular()) {
							$href = '#comments';
						} else {
							$href = get_permalink($post->ID).'#comments';
						}
					}
					$tooltip = $dpArticleShare['i18n_comments'];
					
					break;
				case 'print':
					$icon = 'print';
					$no_count = true;
					$href = 'javascript:window.print();';
					$tooltip = $dpArticleShare['i18n_print'];
					break;
			}
			
			$counter = ($counter > 999999 ? substr($counter, 0, -6).'M' : $counter);
			$counter = ($counter > 9999 ? substr($counter, 0, -3).'K' : $counter);

			if($icon != "") {
				$html .= '<li class="li-dpShareIcon-'.$icon.'" '.($dpArticleShare['limit_icons'] && is_numeric($dpArticleShare['limit_icons_number']) && $count_loop > $dpArticleShare['limit_icons_number'] ? 'style="display:none;"': '').'>';
					if(($dpArticleShare['show_tooltips'] || $dp_article_share_tooltip == "yes") && $dp_article_share_tooltip != "no" && !is_admin()) {
						$html .= '<div class="dpArticleSocialShareTooltip" data-text="'.addslashes($tooltip).'"></div>';
					}
					$html .= '<div class="icon icon-dpShareIcon-'.$icon.' '.($no_count ? 'icon-nocount' : '').'"></div>';
					$html .= '<a class="dpShareButton '.$key.'" href="'.$href.'" '.($key == 'twitter' ? 'data-via="'.$dpArticleShare['twitter_handle'].'"' : '').' data-url="'.($key == 'twitter' || $dpArticleShare['bitly_enabled'] ? $short_url : $url).'" data-text="'.$text.'" '.($key == 'email' ? 'data-share-text="'.$share_text.'" data-email-body="'.addslashes($dpArticleShare['i18n_email_body'].' '.$url).'" data-send-email="'.addslashes($dpArticleShare['i18n_email']).'"' : '').'>';
						$html .= (!$no_count ? '<i class="dpShareArticleCounter"><span data-counter="'.$counter.'"></span></i>': '');
					$html .= '</a>';
					$html .= '<div class="clear-article-share"></div>';
				$html .= '</li>';
			}
		}
				
		if($dpArticleShare['limit_icons'] || $force_more) {
			$html .= '<li class="li-dpShareIcon-more">';
				if(($dpArticleShare['show_tooltips'] || $dp_article_share_tooltip == "yes") && $dp_article_share_tooltip != "no" && !is_admin()) {
					$html .= '<div class="dpArticleSocialShareTooltip" data-text="'.addslashes($dpArticleShare['i18n_more']).'"></div>';
				}
				//'.$dpArticleShare['i18n_share_on'].'
				$html .= '<div class="icon icon-dpShareIcon-more"></div>';

				$html .= '<a class="dpShareButton more" href="javascript:void(0);" data-share-text="'.$share_text.'" data-url="'.($dpArticleShare['bitly_enabled'] ? $short_url : $url).'" data-text="'.$text.'" data-send-email="'.addslashes($dpArticleShare['i18n_email']).'" data-email-body="'.addslashes($dpArticleShare['i18n_email_body'].' '.$url).'">';
				$html .= '</a>';
				$html .= '<div class="clear-article-share"></div>';
			$html .= '</li>';
		}
		
		$html .= '</ul>';
		
		if($position != 'vertical' && $position != 'vertical-inside') {
			$html .= '
			<div class="clear-article-share"></div>';
		}
		
		$html .= '<script type="text/javascript">jQuery(document).ready(function() { dpArticleShare_init(); });</script>';
		
		if($this->post_share_id != "") {
			wp_reset_query();
		}
		
		return $html;
	}
	
}
?>