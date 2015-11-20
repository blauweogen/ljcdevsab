/*
	Javascript code for csv import
*/
jQuery(document).ready(function($){

$('#eventon_csv_data_list').delegate('.csv_list_row','click',function(){
	var inChecked = $(this).find('.col1 p').hasClass('outter_check') ;
	
	if(inChecked){
		$(this).find('.col1 p').removeClass('outter_check').addClass('outter_check_no');
		$(this).find('.csv_row_status').val('no');
	}else{
		$(this).find('.col1 p').addClass('outter_check').removeClass('outter_check_no');
		$(this).find('.csv_row_status').val('yes');
	}
});



});