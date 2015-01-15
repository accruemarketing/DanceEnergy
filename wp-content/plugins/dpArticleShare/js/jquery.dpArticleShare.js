/*
 * jQuery DP Article Share v1.0
 *
 * Copyright 2013, Diego Pereyra
 *
 * @Web: http://www.dpereyra.com
 * @Email: info@dpereyra.com
 *
 * Depends:
 * jquery.js
 */
//jQuery(document).ready(function() {
function dpArticleShare_init() {
	var not_loaded_el = jQuery('.dpArticleShare:not(.dpArticleShareLoaded)'),
	not_loaded_el_vertical = jQuery('.dpArticleShare.vertical-inside:not(.dpArticleShareLoaded), .dpArticleShare.vertical:not(.dpArticleShareLoaded)');
	
	jQuery(not_loaded_el).each(function(i) {
  		var article_id = jQuery(this).data('article-id');
		jQuery(this).show();
		jQuery(this).addClass('dpArticleShareLoaded');
		var update_required = jQuery(this).data('update-required');
		
		jQuery(this).find('li').each(function(e) {
			if(jQuery('.dpArticleSocialShareTooltip', this).length) {
				jQuery('.dpArticleSocialShareTooltip', this).css({
					bottom: (jQuery(this).height() + 10) +"px",
					left: -((jQuery('.dpArticleSocialShareTooltip', this).outerWidth() / 2)) + (jQuery(this).outerWidth() / 2) + 'px'
				}).html(jQuery('.dpArticleSocialShareTooltip', this).data('text').replace(/\\/gi, ""));
			}
			jQuery(this).find('span').html(jQuery(this).find('span').data('counter'));
		});
		
		if(update_required) {
			jQuery.post(ArticleShareAjax.ajaxurl, { post_id: article_id, action: 'updatePostShares', postEventsNonce : ArticleShareAjax.postEventsNonce },
				function(data) {
					
				}
			);	
		}
	});
	
	jQuery(not_loaded_el_vertical).each(function(i) {
		var element = jQuery(this);
		var el_offset = element.offset();
		var parent = element.parent();
		var parent_h = parent.height();
		var offset = element.parent().offset();
		var lastWindowHeight = "";
		var lastWindowWidth = "";
		var origAttr = "";
		var resized = false;
		var inMobile = false;
		var origEl = "";
				
		origEl = element.clone();

		if(parent_h <= jQuery(this).height()) {
			jQuery(this)
				.removeClass('vertical-inside')
				.removeClass('vertical')
				.addClass('horizontal-top');
				
			jQuery(this).before(jQuery('<div>').addClass('clear-article-share'));
			jQuery(this).after(jQuery('<div>').addClass('clear-article-share'));
			
		} else {
			parent.addClass('dpArticleParent');
			
			if(element.hasClass('vertical-inside')) {
				parent.addClass('dpArticleParentInside');
				parent.addClass(element.attr('class').replace('dpArticleShare', ''));
			}
				
			jQuery(window).bind('resize', function() {
				//confirm window was actually resized
				if(jQuery(window).height()!= lastWindowHeight || jQuery(window).width()!= lastWindowWidth){
			
					//set this windows size
					lastWindowHeight = jQuery(window).height();
					lastWindowWidth = jQuery(window).width();
					
					//on window resize stuff
					
					dpArticleShare_resize();
				}
			});

			dpArticleShare_resize();
		
		}
				
		function dpArticleShare_resize() {
			var offset = element.parent().offset();
			
			if(element.hasClass('vertical')) {
				element.attr('style', 'left: ' + (offset.left - element.outerWidth() - element.data('vertical-offset')) +'px !important;').show();
			}

			if((offset.left - origEl.outerWidth() - origEl.data('vertical-offset')) < 30 && !resized) {
				//console.log("Resize Down");
				element.removeAttr('style').show();
				element
					.removeClass('vertical-inside')
					.removeClass('vertical')
					.addClass('horizontal-top');
				
				parent.removeClass('dpArticleParentInside');

				if(!jQuery(parent).children().last().hasClass('clear-article-share')) {
					parent.append(jQuery('<div>').addClass('clear-article-share'));
					parent.append(jQuery(element.clone(true)).removeClass('scrolltop scrolltopend'));
					parent.append(jQuery('<div>').addClass('clear-article-share'));
				}
				
				if(!element.next().hasClass('clear-article-share')) {
					element.before(jQuery('<div>').addClass('clear-article-share'));
					element.after(jQuery('<div>').addClass('clear-article-share'));
				}
				
				inMobile = true;
				resized = true;
			} else if((offset.left - origEl.outerWidth() - origEl.data('vertical-offset')) >= 30 && resized) {
				//console.log("Resize Up");
				if(jQuery(origEl).hasClass('vertical-inside') || jQuery(origEl).hasClass('vertical')) {

					element
						.addClass(jQuery(origEl).hasClass('vertical-inside') ? 'vertical-inside' : 'vertical')
						.removeClass('horizontal-top');
					
					if(jQuery(origEl).hasClass('vertical-inside')) {
						parent.addClass('dpArticleParentInside');
					}
					
					if(jQuery(parent).children().last().hasClass('clear-article-share')) {
						jQuery(parent).children().last().remove();
						jQuery(parent).children().last().remove();
						jQuery(parent).children().last().remove();
					}
				
				}
				
				resized = false;
				inMobile = false;
				
			}
			origAttr = element.attr('style');	
		}
		
		function dpArticleShare_scroll() {
			var offset = element.parent().offset();
			var scroll_top = jQuery(window).scrollTop();
			var css_top = parseInt(jQuery(element).css('top'), 10);
			
			if(isNaN(css_top) || element.hasClass('scrolltopend')) { css_top = 0; }

			if(scroll_top > offset.top && !element.hasClass('scrolltop')) {
				element.addClass('scrolltop');
				if(scroll_top > (element.parent().height() + offset.top) - (element.height() + css_top)) {
					element.addClass('scrolltopend');
				}
			} else if(scroll_top <= offset.top) {
				element.removeClass('scrolltop');
			} else if(scroll_top > (element.parent().height() + offset.top) - (element.height() + css_top)) {
				element.addClass('scrolltopend');
				if(element.hasClass('vertical') && element.css('top') != ((element.parent().height() + offset.top) - element.height())+"px") {
					element.attr('style', origAttr + 'top: '+((element.parent().height() + offset.top) - element.height()) +'px !important;');
				}
			} else if(scroll_top <= (element.parent().height() + offset.top) - element.height()) {
				element.removeClass('scrolltopend');
				if(element.hasClass('vertical')) {
					element.attr('style', origAttr);
				}
			}	
			
			element.show();

		}
		
		jQuery(window).scroll(function() {
			
			if(element.css('position') == 'relative') { return; }
						
			dpArticleShare_scroll();
			
		})
		
		dpArticleShare_scroll();
	});
	
//});

	var tweetWindow = function(url, text, via) {
	  window.open( "http://twitter.com/share?url=" + 
		encodeURIComponent(url) + (via != "" ? "&via=" + 
		via : "") +"&text=" + 
		encodeURIComponent(text) + "&count=none/", 
		"tweet", "height=300,width=550,resizable=1" ) 
	}
	 
	var faceWindow = function(url, title) {
	  window.open( "http://www.facebook.com/sharer.php?u=" + 
		encodeURIComponent(url) + "&t=" + 
		encodeURIComponent(title), 
		"facebook", "height=300,width=550,resizable=1" ) 
	}
	
	var linkedinWindow = function(url, title) {
	  window.open( "http://www.linkedin.com/shareArticle?url=" + 
		encodeURIComponent(url) + "&mini=true&title="+encodeURIComponent(title), 
		"linkedin", "height=300,width=550,resizable=1" ) 
	}
	
	var plusWindow = function(url, title) {
	  window.open( "https://plus.google.com/share?url=" + 
		encodeURIComponent(url), 
		"plus", "height=300,width=550,resizable=1" ) 
	}
	
	var pinterestWindow = function(url, title, media) {
		var e=document.createElement("script"); 
		e.setAttribute("type","text/javascript"); 
		e.setAttribute("charset","UTF-8"); 
		e.setAttribute("src","http://assets.pinterest.com/js/pinmarklet.js?r=" + Math.random()*99999999); 
		document.body.appendChild(e);	
	}
	
	var stumbleuponWindow = function(url, title, media) {
	  window.open( "http://www.stumbleupon.com/submit?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"stumbleupon", "height=300,width=550,resizable=1" ) 
	}
	
	var deliciousWindow = function(url, title, media) {
	  window.open( "http://del.icio.us/post?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"delicious", "height=300,width=550,resizable=1" ) 
	}

	var tumblrWindow = function(url, title, media) {
	  window.open( "http://www.tumblr.com/share/link?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&name=" + 
		encodeURIComponent(title), 
		"tumblr", "height=300,width=550,resizable=1" ) 
	}

	var diggWindow = function(url, title, media) {
	  window.open( "http://digg.com/submit?phase=2&url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"digg", "height=300,width=550,resizable=1" ) 
	}
	
	var redditWindow = function(url, title, media) {
	  window.open( "http://reddit.com/submit?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"reddit", "height=300,width=550,resizable=1" ) 
	}
	
	var bufferWindow = function(url, title, media) {
	  window.open( "https://bufferapp.com/add?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&text=" + 
		encodeURIComponent(title), 
		"buffer", "height=550,width=830,resizable=1" ) 
	}
	
	var likeWindow = function(url, title, media) {
	  window.open( "http://reddit.com/submit?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"like", "height=300,width=550,resizable=1" ) 
	}
	
	var vkWindow = function(url, title, media) {
	  window.open( "http://vk.com/share.php?url=" + 
		encodeURIComponent(url) + "&media=" + 
		encodeURIComponent(media) + "&title=" + 
		encodeURIComponent(title), 
		"vk", "height=300,width=550,resizable=1" ) 
	}
	
	var bloggerWindow = function(url, title, media) {
	  window.open( "http://www.blogger.com/blog-this.g?height=370&width=580&b=" + 
		encodeURIComponent('<a href="'+url+'">'+title+'</a>') + "&n=" + 
		encodeURIComponent(title), 
		"blogger", "height=370,width=580,resizable=1" ) 
	}
	function dpShareLoadEvents() {
		jQuery('.icon', not_loaded_el).click( function() {
			if(!jQuery(this).hasClass('icon-dpShareIcon-comment')) {
				jQuery(this).closest('li').find('a.dpShareButton').trigger('click');
			} else {
				window.location = jQuery(this).closest('li').find('a.dpShareButton').attr('href');
			}
		});
		
		jQuery(".dpShareButton.twitter", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var via = jQuery(this).data('via');
			tweetWindow(href, text, via); 
		});
		
		jQuery(".dpShareButton.facebook", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			faceWindow(href, text); 
		});
		
		jQuery(".dpShareButton.linkedin", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			linkedinWindow(href, text); 
		});
		
		jQuery(".dpShareButton.google", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			plusWindow(href, text); 
		});
		
		jQuery(".dpShareButton.pinterest", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			pinterestWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.stumbleupon", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			stumbleuponWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.delicious", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			deliciousWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.tumblr", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			tumblrWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.digg", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			diggWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.reddit", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			redditWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.facebook_like", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			likeWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.buffer", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			bufferWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.vk", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			vkWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.blogger", not_loaded_el).click(function(e) {
			e.preventDefault();
			var href = jQuery(this).data('url');
			var text = jQuery(this).data('text');
			var media = jQuery(this).data('media');
			bloggerWindow(href, text, media); 
		});
		
		jQuery(".dpShareButton.print", not_loaded_el).click(function(e) {
			e.preventDefault();
			window.print();
		});
	
	}
	
	dpShareLoadEvents();
	
	jQuery(".dpShareButton.more, .dpShareButton.email", not_loaded_el).click(function(e) {
		e.preventDefault();
		var share_text = jQuery(this).data('share-text');
		var text = jQuery(this).data('text');
		var url = jQuery(this).data('url');
		var sendemail = jQuery(this).data('send-email');
		var emailbody = jQuery(this).data('email-body');
		
		var mailform = '<h3>'+sendemail+'</h3><form>';
		mailform += '<div class="dpArticleShare_success">'+ArticleShareAjax.i18n_email_sent+'</div>';
		mailform += '<div class="dpArticleShare_error">'+ArticleShareAjax.i18n_email_required+'</div>';
		mailform += '<div class="clear-article-share"></div>';
		mailform += '<input type="text" name="your_name" id="dpArticleShare_your_name" class="dpArticleShare_input dpArticleShare_from_name" placeholder="'+ArticleShareAjax.i18n_email_your_name+'" />';
		mailform += '<input type="text" name="your_email" id="dpArticleShare_your_email" class="dpArticleShare_input dpArticleShare_from_email" placeholder="'+ArticleShareAjax.i18n_email_your_email+'" />';
		mailform += '<input type="text" name="to" id="dpArticleShare_to" class="dpArticleShare_input" placeholder="'+ArticleShareAjax.i18n_email_to+'" />';
		mailform += '<input type="text" name="subject" id="dpArticleShare_subject" class="dpArticleShare_input" placeholder="'+ArticleShareAjax.i18n_email_subject+'" value="'+text+'" />';
		mailform += '<textarea name="body" class="dpArticleShare_textarea" id="dpArticleShare_message" placeholder="'+ArticleShareAjax.i18n_email_message+'">'+emailbody+'</textarea>';
		mailform += '<input type="button" class="dpArticleShare_send" name="" value="'+ArticleShareAjax.i18n_email_send+'" />';
		mailform += '<span class="dpArticleShare_sending_email"></span>';
		mailform += '</form>';
		
		if(!jQuery('.dpArticleShareModal').length) {
			jQuery('body').append(
				jQuery('<div>').addClass('dpArticleShareModal').append(
					jQuery(this).closest('.dpArticleShare').clone(true).addClass('color horizontal-top').removeClass('scrolltop').removeClass('vertical').removeClass('compact').removeClass('vertical-inside').removeAttr('style').show()
				).prepend(
					jQuery('<h2>').text(share_text).append(
						jQuery('<a>').addClass('dpArticleShareClose').attr({ 'href': 'javascript:void(0);' }).html('&times;').click(function() { 
							jQuery('.dpArticleShareModal, .dpArticleShareOverlay').fadeOut('fast')
						})
					)
				).append(
					jQuery('<div>').addClass('dpArticleShare_mailform').html(mailform)
				).append(
					jQuery('<div>').addClass('page_info').html('<span>'+text+'</span><p>'+url+'</p>')
				).show()
			).append(
				jQuery('<div>').addClass('dpArticleShareOverlay').click(function() { 
					jQuery('.dpArticleShareModal, .dpArticleShareOverlay').fadeOut('fast')
				}).show()
			);
			
			jQuery('.icon-dpShareIcon-email', '.dpArticleShareModal').click(function(e) {
				e.preventDefault();
				jQuery('.dpArticleShare', '.dpArticleShareModal').addClass('dpArticleShare_emailform');
				jQuery('.dpArticleShare_mailform').show();
			});
			
			jQuery('.dpArticleShare_send', '.dpArticleShareModal').click(function(e) {
				e.preventDefault();
				jQuery(this).prop('disabled', true);
				jQuery('.dpArticleShare_sending_email', '.dpArticleShareModal').css('display', 'inline-block');
				
				if( jQuery('#dpArticleShare_your_name', '.dpArticleShareModal').val() != ""
					&& jQuery('#dpArticleShare_your_email', '.dpArticleShareModal').val() != ""
					&& jQuery('#dpArticleShare_to', '.dpArticleShareModal').val() != "" 
					&& jQuery('#dpArticleShare_subject', '.dpArticleShareModal').val() != ""
					&& jQuery('#dpArticleShare_message', '.dpArticleShareModal').val() != "") {

					jQuery.post(ArticleShareAjax.ajaxurl, { 
							your_name: jQuery('#dpArticleShare_your_name', '.dpArticleShareModal').val(), 
							your_email: jQuery('#dpArticleShare_your_email', '.dpArticleShareModal').val(), 
							to: jQuery('#dpArticleShare_to', '.dpArticleShareModal').val(), 
							subject: jQuery('#dpArticleShare_subject', '.dpArticleShareModal').val(), 
							message: jQuery('#dpArticleShare_message', '.dpArticleShareModal').val(), 
							action: 'ArticleShare_SendMail', 
							postEventsNonce : ArticleShareAjax.postEventsNonce 
						},
						function(data) {
							jQuery('.dpArticleShare_send', '.dpArticleShareModal').prop('disabled', false);
							jQuery('.dpArticleShare_sending_email', '.dpArticleShareModal').hide();
							
							jQuery('.dpArticleShare_success, .dpArticleShare_error').hide();
							jQuery('.dpArticleShare_success').css('display', 'inline-block');
							jQuery('form', '.dpArticleShareModal')[0].reset();
						}
					);	
				} else {
					jQuery(this).prop('disabled', false);
					jQuery('.dpArticleShare_sending_email', '.dpArticleShareModal').hide();
					
					jQuery('.dpArticleShare_success, .dpArticleShare_error').hide();
					jQuery('.dpArticleShare_error').css('display', 'inline-block');
				}
			});
			
			jQuery("input, textarea", '.dpArticleShareModal').placeholder();
			
			dpShareLoadEvents();
		} else {
			if(jQuery('.page_info p', '.dpArticleShareModal').text() != url) {
				jQuery('.dpArticleShareModal, .dpArticleShareOverlay').remove();
				jQuery(this).trigger('click');
			} else {
				jQuery('.dpArticleShareModal, .dpArticleShareOverlay').show();
			}
		}
		
		if(jQuery(this).hasClass('email')) {
			jQuery('.dpArticleShare', '.dpArticleShareModal').addClass('dpArticleShare_emailform');
			jQuery('.dpArticleShare_mailform').show();
		} else {
			jQuery('.dpArticleShare', '.dpArticleShareModal').removeClass('dpArticleShare_emailform');
			jQuery('.dpArticleShare_mailform').hide();
		}
		
		jQuery('.dpArticleShare_success, .dpArticleShare_error').hide();

	});
}

dpArticleShare_init();