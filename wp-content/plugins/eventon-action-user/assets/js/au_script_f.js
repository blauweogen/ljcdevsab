/**
 * Javascript: Eventon Active User - Front end script
 * @version  1.8
 */
jQuery(document).ready(function($){

// lightbox form trigger
	$('body').on('click','.evoAU_form_trigger_btn',function(){
		$('body').find('.eventon_au_form_section').show().addClass('showForm');
		$('body').addClass('evoOverflowHide');
		reset_form( $('.evoau_submission_form').find('form'), 'hardcore');
	});
	$('body').on('click','.closeForm',function(){
		$('body').find('.eventon_au_form_section').show().removeClass('showForm');
		$('body').removeClass('evoOverflowHide');
	});

// FIELDS of the event form
	// all day event
		$('#evoAU_all_day').on('click',function(){
			if ($('input#evoAU_all_day').is(':checked')) {
				$('.evoau_tpicker').hide();
			}else{
				$('.evoau_tpicker').show();
			}
		});

	// no time event
		$('#evoAU_hide_ee').on('click',function(){
			if ($('input#evoAU_hide_ee').is(':checked')) {
				$('#evoAU_endtime_row').hide();
			}else{
				$('#evoAU_endtime_row').show();
			}
		});

	// date picker fields
		var dateformat__ = $('#_evo_date_format').attr('jq');
		date_format = (typeof dateformat__ !== 'undefined' && dateformat__ !== false)?	
			dateformat__: 'dd/mm/yy';	
		
		$('#evoAU_start_date').datepicker({ 
			dateFormat: date_format,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
		        $( "#evoAU_end_date" ).datepicker( "option", "minDate", selectedDate );
		    }
		});
		$('#evoAU_end_date').datepicker({ 
			dateFormat: date_format,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
	        	$( "#evoAU_start_date" ).datepicker( "option", "maxDate", selectedDate );
	      	}
		});

	// time picker fields
		var time_format__ = $('#_evo_time_format').val();
		time_format__ = (time_format__=='24h')? 'H:i':'h:i:A';
		$('.evoau_tpicker').timepicker({ 'step': 15,'timeFormat': time_format__ });

	// color picker
		$('body').find('.color_circle').each(function(){
			OBJ = $(this);
			OBJ.ColorPicker({
				onBeforeShow: function(){
					$(this).ColorPickerSetColor( $(this).attr('data-hex'));
				},	
				onChange:function(hsb, hex, rgb,el){
					OBJ.attr({'backgroundColor': '#' + hex, 'data-hex':hex}).css('background-color','#' + hex);
					set_rgb_min_value(rgb,'rgb', OBJ);
					OBJ.next().find('.evcal_event_color').attr({'value':hex});
				},	
				onSubmit: function(hsb, hex, rgb, el) {					
					var sibb = OBJ.siblings('.evoau_color_picker');
					sibb.find('.evcal_event_color').attr({'value':hex});
					OBJ.css('backgroundColor', '#' + hex);				
					OBJ.ColorPickerHide();
					set_rgb_min_value(rgb,'rgb', OBJ);
				}
			});
		});
		
		/** convert the HEX color code to RGB and get color decimal value**/
			function set_rgb_min_value(color,type, OBJ){			
				if( type === 'hex' ) {			
					var rgba = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);	
					var rgb = new Array();
					 rgb['r']= parseInt(rgba[1], 16);			
					 rgb['g']= parseInt(rgba[2], 16);			
					 rgb['b']= parseInt(rgba[3], 16);	
				}else{
					var rgb = color;
				}			
				var val = parseInt((rgb['r'] + rgb['g'] + rgb['b'])/3);			
				OBJ.next().find('.evcal_event_color_n').attr({'value':val});
			}

	// click on image select field
		var SITE = SITE || {};
		var FILES;
 
		// bind click on image file to all image fields on the forms
		$('.evoau_file_field input[type=file]').bind('change focus click', function(event){
			var $this = $(this),
		      	$val = $this.val(),
		      	valArray = $val.split('\\'),
		      	newVal = valArray[valArray.length-1],
		      	$button = $this.siblings('.evoau_img_btn'),
		     	$fakeFile = $this.siblings('.file_holder');
		  	if(newVal !== '') {
		   		var btntext = $this.attr('data-text');
		    	$button.text( btntext);
		    	if($fakeFile.length === 0) {
		    	  	$button.after('<span class="file_holder">' + newVal + '</span>');
		    	} else {
		      		$fakeFile.text(newVal);
		    	}		    	
		  	}
		});
		// run actual input field image when click on span button
		$('.evoau_img_btn').on('click',function(){
			$(this).parent().find('input').click();
		});

		// remove existing images
		$('body').on('click','.evoau_img_preview span',function(){
			$(this).parent().fadeOut();
			$(this).parent().next().fadeIn();
			$(this).siblings('input').val('no');
		});

	// click on user interaction field
			$('.evoau_submission_form').on('change', '.evoau_ui select', function(){
				var value = $(this).val();

				if(value==2){
					$(this).parent().siblings('.evoau_exter').slideDown();
				}else{
					$(this).parent().siblings('.evoau_exter').slideUp();
				}
			});

// form submission
	$('body').on('click','.evoau_submission_form',function(){
		$(this).removeClass('errorForm');
		$(this).find('.formeMSG').fadeOut();
	});
	
	$('#evoau_submit').on('click',function(e){
		e.preventDefault();

		var form = $(this).closest('form'),
			formp = form.parent(),
			errors = 0,
			msg_obj = form.find('.formeMSG');

		var data_arg = {};

		// form notification messages
			var nof = form.find('.evoau_json');
			nof = JSON.parse(nof.html());

		// save cookie if submission is limited
		if(form.data('limitsubmission')=='ow'){
			if($.cookie('evoau_event_submited')=='yes'){
				formp.addClass('errorForm limitSubmission');
				form.find('.inner').slideUp();
				form.find('.evoau_success_msg').html('<p><b></b>'+nof.nof6+'</p>').show();
				return false;
			}else{
				$.cookie('evoau_event_submited','yes',{expires:24});
			}			
		}

		reset_form(form);
		//data_arg = form.formParams();
					
		// check required fields missing
			form.find('.req').each(function(i, el){
				var el = $(this);
				var val = el.val();
				var name = el.attr('name');
				
				// Filter values
				if( $('#evoAU_hide_ee').is(':checked') && el.hasClass('end')){
					// no end time event
				}else if( $('#evoAU_all_day').is(':checked') && el.hasClass('time')){
					// all day event
				}else{
					if(val.length==0){
						errors++;
						el.closest('.row').addClass('err');				
					}
				}
			});

		// check for captcha validation
			if(form.hasClass('captcha')){
				var field = form.find('.au_captcha input'),
					cval = field.val();

				validation_fail = false;

				if(cval==undefined || cval.length==0){
					validation_fail = true;
				}else{
					var numbers = ['11', '3', '6', '3', '8'];
					if(numbers[field.attr('data-cal')] != cval )
						validation_fail = true;
				}
				if(validation_fail){
					errors = (errors == 0)? 20:errors+1;
					form.find('.au_captcha').addClass('err');
				}
			}
		
		if(errors==0){
			form.ajaxSubmit({
				beforeSubmit: function(){						
					formp.addClass('loading');
				},
				dataType:'json',
				url:evoau_ajax_script.ajaxurl,
				success:function(responseText, statusText, xhr, $form){
					if(responseText.status=='good'){
						form.find('.inner').slideUp();

						SUCMSG = form.find('.evoau_success_msg');

						SUCMSG.html('<p><b></b>'+nof.nof3+'</p>').slideDown(); // show success msg
						formp.addClass('successForm');

						// redirect page after success form submission
						if(form.attr('data-redirect')!='nehe'){
							RDUR = (form.attr('data-rdur') !='' && form.attr('data-rdur')!== undefined)? parseInt(form.attr('data-rdur')):0;
							setTimeout(function(){
								window.location = form.attr('data-redirect');
							}, RDUR);
						}

						// show add more events button
						if(form.attr('data-msub')=='ow'){
							formp.find('.msub_row').fadeIn();
						}
					}else{
						MSG = (responseText.msg=='bad_nonce')? nof.nof5: nof.nof4;
						msg_obj.html( MSG).fadeIn();
					}
					formp.removeClass('loading');													
				}
			});			
		}else{
			formp.addClass('errorForm');
			console.log(errors);
			e.preventDefault();
			var msg = (errors==20)? nof.nof2: nof.nof0;
			msg_obj.html(msg).slideDown('fast');
			return false;
		}
	});

	// submit another event
		$('body').on('click','.msub_row a',function(){
			FORM = $(this).closest('form');

			if(FORM.parent().hasClass('successForm')){
				reset_form(FORM,'hardcore');
				$(this).parent().fadeOut();
			}
		});

// complete form actions
	function reset_form(form, type){		
		form.find('.eventon_form_message').removeClass('error_message').fadeOut();
		form.find('.row').removeClass('err');
		form.parent().removeClass('successForm errorForm');

		form.find('.inner').show();
		form.find('.evoau_success_msg').hide();

		if(type=='hardcore'){
			form.find('input[type=text]').val('');
		}

	}
});