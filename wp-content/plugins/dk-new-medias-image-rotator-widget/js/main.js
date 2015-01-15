var iframeJQuery;
var fromUrl = false;

jQuery(function() {

 	var tb_show_temp = window.tb_show;
	window.tb_show = function() {
		tb_show_temp.apply(null, arguments);

		var iframe = jQuery('#TB_iframeContent');
		iframe.load(function(){
			var iframeDoc = iframe[0].contentWindow.document;
			iframeJQuery = iframe[0].contentWindow.jQuery;
			apply_insert_button_filter(iframeJQuery);
		});
	}
			
});


function irw_load() {
	// IE Fixes
	if(jQuery.browser.msie && jQuery.browser.version == "8.0") {
		jQuery('.irw_header').prev().prepend('<h4 class="irw-old-browser">The browser you are using is old and not supported.</h4>'); 
	} else if(jQuery.browser.msie && jQuery.browser.version == "7.0") {
		jQuery('body').addClass('ie7');
		jQuery('.irw_header').prev().prepend('<h4 class="irw-old-browser">The browser you are using is old and not supported.</h4>'); 
	} else if(jQuery.browser.msie && jQuery.browser.version == "6.0") {
		jQuery('.irw_header').prev().prepend('<h4 class="irw-old-browser">The browser you are using is old and not supported.</h4>'); 
	}

	// Keep track of active widget
	jQuery("body").delegate(".widget-content", "hover", function(){
		if(!jQuery(this).is(".active-widget")) {
			jQuery(".active-widget").removeClass("active-widget");
			jQuery(this).addClass("active-widget");
		}
	});

	irw_init();

	jQuery(".widget-control-save").on("click", function(){
		setTimeout(function(){
			irw_init();
		}, 2000);
	});

	if(jQuery(".irw-h5").length < 1) {
		jQuery(".irw_images").parent().parent().append('<center><h5 class="irw-h5">Made by <a href="http://dknewmedia.com">DK New Media</a></h5></center>');
	}
}

function irw_init() {

	// jQuery UI Sort
	jQuery(".irw_images").sortable({
		stop: function(){
			var str = "";
			var i = 1;
			var parent = jQuery(this).parent().find('.image_list');
			jQuery(this).children('li').each(function(){
				var img_link = (jQuery(this).attr('data-link').length > 0) ? '|' + jQuery(this).attr('data-link') : '';
				if(i == 1) {
					str += jQuery(this).attr('data-url') + img_link;
				} else {
					str += ", " + jQuery(this).attr('data-url') + img_link;
				}
				i++;
			});
			parent.val(str);
		},
		placeholder: 'irw-placeholder',
		forcePlaceholderSize: true
	});

	// Disable Selection of Images
	jQuery(".irw_images").disableSelection();

	rebinder();
	qtip_init();

	jQuery('.irw_images li').mouseover();
}

function rebinder() {
	// Removes an Image
	jQuery(".irw_images li button").off("click").on("click", function(){
		var thiss = jQuery(this);
		thiss.parent().parent().parent().addClass('active-widget');
		thiss.parent().remove();
		var n = 0;
		var str = "";
		jQuery(".active-widget .irw_images li").each(function(){
			var img_link = (jQuery(this).attr('data-link').length > 0) ? '|' + jQuery(this).attr('data-link') : '';
			if(n < 1) {
				str += jQuery(this).attr('data-url') + img_link;
			} else {
				str += ", " + jQuery(this).attr('data-url') + img_link;
			}
			n++;
		});
		jQuery(".active-widget .image_list").val(str);
		if(jQuery(".active-widget .irw_images li").length < 1) {
			jQuery(".active-widget .add-image-button").parent().addClass('alert');
		}
	});
}

function qtip_init() {
	jQuery(".irw_images li span:not(.button)").each(function(e,n){
		jQuery(this).text(get_truncated_filename(jQuery(this).text(), true));
		var src = jQuery(this).parent().attr("data-url");
		var alt = jQuery(this).parent().attr("data-alt");

		jQuery(this).qtip({
			content: {
				text: '<center><img src="' + src + '" alt="' + alt + '" /></center>',
				prerender: true
			},
			show: 'mouseover',
			hide: 'mouseout',
			position: {
				corner: {
					target: 'leftMiddle',
					tooltip: 'rightMiddle'
				}
			},
			style: {
				//width: 300,
				tip: 'rightMiddle'
			},
			api: {
				beforeShow: function(){
					this.updateWidth();
				}
			}
		});
	});
}

function apply_insert_button_filter(iframejq) {
	
	setInterval(function(){

		if(iframejq("#src").length > 0) {
			iframejq('body').addClass('fromUrl');
		}

		iframejq('.open .savesend input[type=submit], #go_button').each(function(i, e){
			iframejq(e).attr("value", "Send to Image Rotator Widget");
			iframejq(e).off("click").on("click", function(){
				if(iframejq('body').is(".fromUrl")) {
					var imgurl = iframejq('#src').val();
					var imglink = iframejq('#url').val();
				} else {
					var imgurl = iframejq('.open .urlfile').data('link-url');
					var imglink = iframejq('.open .urlfield').val();
				}
				var n = jQuery('.active-widget .irw_images li').length;
				var parent = jQuery(".active-widget .irw_images").parent().find('.image_list');
				jQuery('.active-widget .add-image-button').parent().removeClass('alert');
				jQuery('.active-widget .irw_images').append("<li data-url='" + imgurl + "' data-link='" + imglink + "'><span>" + get_truncated_filename(imgurl, true) + "</span> <button class='button irw_button'> - </button></li>");
				if(n > 0) {
					var list = jQuery('.active-widget .image_list').val();
					var img = imgurl + "|" + imglink;
					parent.val(list + ", " + img);
				} else {
					var img = imgurl + "|" + imglink;
					parent.val(img);
				}
				tb_remove();
				irw_init();
			});
		});
	}, 1000);
}

function upload_handler(imgurl, imglink, imgalt) {
	
	if(imglink == false) {
		imglink = "";
	}
	var n = jQuery('.active-widget .irw_images li').length;
	var parent = jQuery(".active-widget .irw_images").parent().find('.image_list');
	jQuery('.active-widget .add-image-button').parent().removeClass('alert');
	jQuery('.active-widget .irw_images').append("<li data-url='" + imgurl + "' data-link='" + imglink + "' data-alt='" + imgalt + "'><span>" + get_truncated_filename(imgurl, true) + "</span> <button class='button irw_button'> - </button></li>");
	if(n > 0) {
		var list = jQuery('.active-widget .image_list').val();
		var img = imgurl + "|" + imglink + "|" + imgalt;
		parent.val(list + ", " + img);
	} else {
		var img = imgurl + "|" + imglink + "|" + imgalt;
		parent.val(img);
	}
	irw_init();
}

function media_dialog(e) {

	var send_attachment_bkp = wp.media.editor.send.attachment;

	wp.media.editor.send.attachment = function(props, attachment) {
		var imgUrl = attachment.url;
		var imgAlt = attachment.alt;
		if(props.link == "file") {
			var imgLink = attachment.url;
		} else if(props.link == "custom") {
			var imgLink = props.linkUrl;
		} else if(props.link == "post") {
			var imgLink = attachment.link;
		} else {
			var imgLink = false;
		}
		upload_handler(imgUrl, imgLink, imgAlt);
		wp.media.editor.send.attachment = send_attachment_bkp;
	}
	wp.media.editor.open(e/*'#' + button.attr('id')*/);
	return false;
}

function media_dialog_dep(e) {
	formfield = jQuery('#upload_image').attr('name');
	tb_show('Add Image to the Image Rotator Widget', 'media-upload.php?type=image&amp;TB_iframe=true');
	return false;
}

function get_truncated_filename(str, is_url) {
	if(is_url) {
		var filename = str.split("/").pop().split(".");
	} else {
		var filename = str.split(".");
	}
	if(filename[0].length > 20) {
		return filename[0].substr(0, 20) + "..." + filename.pop();
	} else {
		return filename[0] + "." + filename[1];
	}
	
}
