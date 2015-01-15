jQuery(function($) {

	$overlay = $('<div>').addClass('dpProEventCalendar_Overlay').click(function(){
		hideOverlay();
	});

	if($("#dpProEventCalendar_SpecialDates").length) {
		var $specialDates = $("#dpProEventCalendar_SpecialDates");
		$specialDates.dialog({                   
			'dialogClass'   : 'wp-dialog',           
			'modal'         : true,
			'height'		: 220,
			'width'			: 400,
			'autoOpen'      : false, 
			'closeOnEscape' : true
		});
		
		jQuery('.btn_add_special_date').live('click', function(event) {
			$specialDates.dialog('open');
		});
	}
	
	if($("#dpProEventCalendar_SpecialDatesEdit").length) {
		var $specialDatesEdit = $("#dpProEventCalendar_SpecialDatesEdit");
		$specialDatesEdit.dialog({                   
			'dialogClass'   : 'wp-dialog',           
			'modal'         : true,
			'height'		: 220,
			'width'			: 400,
			'autoOpen'      : false, 
			'closeOnEscape' : true
		});
		
		jQuery('.btn_edit_special_date').live('click', function(event) {
			$('#dpPEC_special_id').val($(this).data('special-date-id'));
			$('#dpPEC_special_title').val($(this).data('special-date-title'));
			$('#dpPEC_special_color').val($(this).data('special-date-color'));
			$('#specialDate_colorSelector_Edit div').css('backgroundColor', $(this).data('special-date-color'));

			$specialDatesEdit.dialog('open');
		});
	}
	
	if($(".dpProEventCalendar_ModalCalendar").length) {	
		$('.dpProEventCalendar_btn_getDate').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				
				hideOverlay();
				$('#dpProEventCalendar_default_date').val($(this).data('dppec-date'));
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');
				
			});
		});
		
		$('.dpProEventCalendar_btn_getEventDate').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				
				hideOverlay();
				$('#pec_date').val($(this).data('dppec-date'));
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');
				
			});
		});
		
		$('.dpProEventCalendar_btn_getEventEndDate').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				
				hideOverlay();
				$('#pec_end_date').val($(this).data('dppec-date'));
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');
				
			});
		});
		
		$('.dpProEventCalendar_btn_getDateRangeStart').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				
				hideOverlay();
				$('#dpProEventCalendar_date_range_start').val($(this).data('dppec-date'));
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');
				
			});
		});
		
		$('.dpProEventCalendar_btn_getDateRangeEnd').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				
				hideOverlay();
				$('#dpProEventCalendar_date_range_end').val($(this).data('dppec-date'));
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');
				
			});
		});
		
		$('.btn_manage_special_dates').live('click', function(event) {
			var nonce = $(this).data('calendar-nonce');
			var calendar = $(this).data('calendar-id');
			$('.dp_pec_wrapper', '.dpProEventCalendar_ModalCalendar').hide();
			$('#dp_pec_id'+nonce, '.dpProEventCalendar_ModalCalendar').show();
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				if($(this).data('sp_date_active')) { return false; }
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').data('sp_date_active', false);
				$(this).data('sp_date_active', true);
				
				$('.dp_pec_content', '.dpProEventCalendar_ModalCalendar').css({'overflow': 'visible'});
				
				$('.dp_manage_special_dates', '.dpProEventCalendar_ModalCalendar').slideUp('fast').parent().css('z-index', 2);
				$('.dp_manage_special_dates', this).slideDown('fast').parent().css('z-index', 3);
				
			});
			
			$('.dp_pec_date:not(.disabled) select', '.dpProEventCalendar_ModalCalendar').live('change', function() 
			{
			   changeSpecialDate($(this), calendar);
			});
		});
		
		$('#btn_manage_special_dates').live('click', function(event) {
			
			showOverlay();
			
			$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').live('click', function(event) {
				if($(this).data('sp_date_active')) { return false; }
				$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').data('sp_date_active', false);
				$(this).data('sp_date_active', true);
				
				$('.dp_pec_content', '.dpProEventCalendar_ModalCalendar').css({'overflow': 'visible'});
				
				$('.dp_manage_special_dates', '.dpProEventCalendar_ModalCalendar').slideUp('fast').parent().css('z-index', 2);
				$('.dp_manage_special_dates', this).slideDown('fast').parent().css('z-index', 3);
				
			});
			
			$('.dp_pec_date:not(.disabled) select', '.dpProEventCalendar_ModalCalendar').live('change', function() 
			{
			   changeSpecialDate($(this));
			});
		});
		
	}
	
	function changeSpecialDate(obj, calendar) {
		if($(obj).val() == '') { 
			$(obj).parent().parent().css('background-color', '#fff');
		} else {
			obj_arr = $(obj).val().split(',');
			var color = obj_arr[1];
			var sp = obj_arr[0];
			$(obj).parent().parent().css('background-color', color);
		}
		
		$.post(ProEventCalendarAjax.ajaxurl, { date: $(obj).parent().parent().data('dppec-date'), sp : sp, calendar: calendar, action: 'setSpecialDates', postEventsNonce : ProEventCalendarAjax.postEventsNonce },
			function(data) {

			}
		);	
	}
	
	function showOverlay() {
		if($(".dpProEventCalendar_Overlay").length) {
			$($overlay).fadeIn('fast');
		} else {
			$('body').append($overlay);
		}
		$(".dpProEventCalendar_ModalCalendar").css({ display: 'none', visibility: 'visible' }).fadeIn('fast');	
	}
	
	function hideOverlay() {
		$(".dpProEventCalendar_ModalCalendar").fadeOut('fast', function() { $(this).css({ display: 'block', visibility: 'hidden' }) } );	
		$(".dpProEventCalendar_Overlay").fadeOut('fast');
		
		$('.dp_manage_special_dates', '.dpProEventCalendar_ModalCalendar').slideUp('fast').parent().css('z-index', 2);
		$('.dp_pec_date:not(.disabled)', '.dpProEventCalendar_ModalCalendar').die('click');	
		$('.dp_pec_date:not(.disabled) select', '.dpProEventCalendar_ModalCalendar').die('change');	
	}
			
}); 

function pec_removeBooking(booking_id, parent_el) {
	jQuery(parent_el).closest('tr').css('opacity', .6);
	jQuery.post(ProEventCalendarAjax.ajaxurl, { booking_id: booking_id, action: 'removeBooking', postEventsNonce : ProEventCalendarAjax.postEventsNonce },
		function(data) {
			jQuery(parent_el).closest('tr').remove();
		}
	);	
}
 