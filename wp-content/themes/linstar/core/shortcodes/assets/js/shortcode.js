!function($) {
	
}(window.jQuery);

function king_shortcode_setIcon( id, st ){
	
	jQuery( event.target ).closest('.vc_panel-body').find('input.icon-fields').val('');
	jQuery( event.target ).closest('.vc_panel-body').find('i.icon-preview').attr({'class':'icon-preview'});
	
	var icon = event.target.title;
	jQuery( '#'+id ).val( icon ).focus();
	var preview = jQuery( '#'+id.replace( 'color-', 'icon-preview-' ) );
	switch( st ){
		case 1:preview.attr({'class':'icon-preview fa fa-'+icon});break;
		case 2:preview.attr({'class':'icon-preview icon-'+icon});break;
		case 3:preview.attr({'class':'icon-preview et-'+icon});break;
	}
}
function king_shortcode_hideIcon( id ){
	jQuery( '#'+id ).get(0).hideTimer = setTimeout(function(){
		jQuery( '#'+id ).css({
			height: '0px',
			padding: '0px',
			border: 'none'
		});
	}, 300 );	
}
function king_shortcode_showIcon( id ){
	
	clearTimeout( jQuery( '#'+id ).get(0).hideTimer );
	
	jQuery( '#'+id ).css({
		height: '100px',
		padding: '15px 9px',
		border: '1px solid #ccc'
	});
}
function king_filter_terms( btn ){
	jQuery(btn.parentNode).find('.vc_btn-grace').removeClass('vc_btn-grace').addClass('vc_btn-gray');
	jQuery(btn).addClass('vc_btn-grace');
	jQuery(btn.parentNode).find('select option').hide();
	jQuery(btn.parentNode).find('select option.'+btn.innerHTML).show();
	jQuery(btn.parentNode).find('select option:selected').removeAttr('selected');
	jQuery(btn.parentNode).find('select option.'+btn.innerHTML+'-st').attr({ 'selected' : true });
}

function king_shortcode_radioChoose( inp ){
	jQuery( inp.parentNode ).find('.king-radio-val').val( inp.value );
}