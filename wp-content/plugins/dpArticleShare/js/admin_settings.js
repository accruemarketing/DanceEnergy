jQuery(function($) {
	
	if($('#share_update_counter').length) {
		$('#share_update_counter').click(function() {
			var element = this;
			$(this).prop('disabled', true);
			$(this).next('.dpArticleShare_Loader').show();
			
			jQuery.post(ArticleShareAjax.ajaxurl, { action: 'updateAllPostShares', postEventsNonce : ArticleShareAjax.postEventsNonce },
				function(data) {
					$(element).prop('disabled', false);
					$(element).next('.dpArticleShare_Loader').hide();
					
					location.reload(true);
				}
			);	
		});
	}
	
});  