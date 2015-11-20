/*
	Javascript: Event Tickets Calendar
	version: 0.1
*/
jQuery(document).ready(function($){

	// GET attendee list
		$('#evotx_attendees').on('click',function(){

			var data_arg = {
				action: 		'the_ajax_evotx_a1',
				eid:			$(this).data('eid'),
				wcid:			$(this).data('wcid'),
				postnonce: evotx_admin_ajax_script.postnonce, 
			};
			//console.log(data_arg);
			
			$.ajax({
				beforeSend: function(){},
				type: 'POST',
				url:evotx_admin_ajax_script.ajaxurl,
				data: data_arg,
				dataType:'json',
				success:function(data){
					console.log(data.status);
					if(data.status=='0'){
						$('.evotx_lightbox').find('.ajde_popup_text').html(data.content);
						$('.ajde_popup_text').find('.evotx_ticket span.evotx_status').click(function(){
							var obj = $(this);
							checkin_attendee(obj);
						});
					}else{
						$('.evotx_lightbox').find('.ajde_popup_text').html('Could not load attendee list');
					}

				},complete:function(){
					
				}
			});

		});	

	// CHECK in attendees
		function checkin_attendee(obj){

			var status = obj.attr('data-status');
			var data_arg = {
				action: 'the_ajax_evotx_a5',
				tid: obj.attr('data-tid'),
				tiid: obj.attr('data-tiid'),
				status:  status
			};
			$.ajax({
				beforeSend: function(){
					obj.html( obj.html()+'...' );
				},
				type: 'POST',
				url:evotx_admin_ajax_script.ajaxurl,
				data: data_arg,
				dataType:'json',
				success:function(data){
					obj.attr({'data-status':data.new_status}).html(data.new_status_lang).removeAttr('class').addClass('evotx_status '+ data.new_status);
				}
			});
		}

	// check in attendees via evo-tix post page
		$('#evotx_ticketItem_tickets').on('click','.tix_status', function(){
			var obj = $(this);
			var data_arg = {
				action: 'the_ajax_evotx_a5',
				tid: obj.attr('data-tid'),
				tiid: obj.attr('data-tiid'),
				status: obj.attr('data-status'),
			};
			$.ajax({
				beforeSend: function(){
					obj.html( obj.html()+'...' );
				},
				type: 'POST',
				url:evotx_admin_ajax_script.ajaxurl,
				data: data_arg,
				dataType:'json',
				success:function(data){
					//alert(data);
					obj.attr({'data-status':data.new_status}).html(data.new_status_lang).removeAttr('class').addClass('tix_status '+ data.new_status);

				}
			});
		});
	// resend confirmation email
		$('#evoTX_resend_email').on('click',function(){
			var obj = $(this);
			
			var data_arg = {
				action: 'the_ajax_evotx_a55',
				tiid: obj.data('tiid'),
			};
			$.ajax({
				beforeSend: function(){
					obj.closest('.evoTX_resend_conf').addClass('loading');
				},
				type: 'POST',
				url:evotx_admin_ajax_script.ajaxurl,
				data: data_arg,
				dataType:'json',
				success:function(data){
					//alert(data);
					if(data.status=='0'){
						obj.siblings('.message').fadeIn().delay(5000).fadeOut();
					}

				},complete:function(){
					obj.closest('.evoTX_resend_conf').removeClass('loading');
				}
			});
		});


	// view rest repeat occurance 
		$('body').on('click', '.evotx_ri_view_more a', function(){
			$(this).parent().siblings('.evotx_ri_cap_inputs').find('p').fadeIn();
			$(this).parent().fadeOut();
		});
	
});