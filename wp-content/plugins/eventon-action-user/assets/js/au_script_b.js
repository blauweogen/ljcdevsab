/*
	Javascript: Eventon Active User
*/
jQuery(document).ready(function($){

	// add new users to permissions list
	$('.ajde_popup_text').on('click','.evoau_user_list_item',function(){
		var uid = parseInt($(this).val());
		var uname = $(this).attr('uname');		
			
		if( $(this).is(':checked') ){		
			$('.evoau_users_data').find('#evoau_'+uid).attr('checked','checked');
		}else{
			$('.evoau_users_data').find('#evoau_'+uid).removeAttr('checked');
			
		}
	});

	
});