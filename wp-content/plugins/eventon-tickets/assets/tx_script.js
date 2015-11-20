/**
 * Event Ticket script 
 * @version 2.0
 */
jQuery(document).ready(function($){
	
// click add to cart for variable product
    $('body').on('click','.evoAddToCart', function(e){

        e.preventDefault();
        thisButton = $(this);

        // loading animation
        thisButton.closest('.evoTX_wc').addClass('loading');

        // set cart item additional data
            var ticket_row = thisButton.closest('.evo_metarow_tix');
            var event_id = ticket_row.attr('data-event_id');
            var ri = ticket_row.attr('data-ri');
            
            // variable item
            if(thisButton.hasClass('variable_add_to_cart_button')){
                var variation_form = thisButton.closest('form.variations_form');
                var variations_table = variation_form.find('table.variations');

                var product_id = parseInt(variation_form.attr('data-product_id'));
                var variation_id = parseInt(variation_form.find('input[name=variation_id]').val());
                var quantity = parseInt(variation_form.find('input[name=quantity]').val());

                values = variation_form.serialize();

                var attributes ='';
                variations_table.find('select').each(function(index){
                    attributes += '&'+ $(this).attr('name') +'='+ $(this).val();
                });

                $.ajax({
                    type: 'POST',
                    url: '?add-to-cart='+product_id+'&variation_id='+variation_id+attributes+'&quantity='+quantity +'&ri='+ri+'&eid='+event_id,
                    beforeSend: function(){ },
                    success: function(response, textStatus, jqXHR){
                        // Show success message
                        thisButton.closest('.evo_metarow_tix').find('.tx_wc_notic').slideDown();
                    }, complete: function(){
                        var delay = setTimeout(function(){
                            thisButton.closest('.evoTX_wc').removeClass('loading');
                        }, 1000);
                        // if need to be redirected to cart after adding
                            if(evotx_object.redirect_to_cart=='yes'){
                                window.location.href = evotx_object.cart_url;
                            }                        
                    }
                }); 
            }

            // simple item
            if(thisButton.hasClass('single_add_to_cart_button')){
                // /console.log('66');
                var sold_individually = thisButton.closest('.evoTX_wc').data('si');
                var qty = (sold_individually=='yes')? 1: thisButton.parent().find('input[name=quantity]').val();
                var product_id = thisButton.attr('data-product_id');

                $.ajax({
                    type: 'POST',
                    url: '?add-to-cart='+product_id+'&quantity='+qty +'&ri='+ri+'&eid='+event_id,
                    beforeSend: function(){ },
                    success: function(response, textStatus, jqXHR){
                        // Show success message
                        thisButton.closest('.evo_metarow_tix').find('.tx_wc_notic').slideDown();
                    }, complete: function(){
                        var delay = setTimeout(function(){
                            thisButton.closest('.evoTX_wc').removeClass('loading');
                        }, 1000);

                        // reduce remaining qty
                            var remainingEL = thisButton.closest('.evcal_evdata_cell').find('.evotx_remaining');
                            var remaining_count = parseInt(remainingEL.attr('data-count'));
                            if(remaining_count){
                                if(thisButton.hasClass('variable_add_to_cart_button')){
                                    var qty = thisButton.siblings('.quantity').find('input.qty').val();
                                    var new_count = remaining_count-qty;
                                }else{
                                     // simple items
                                    var new_count = remaining_count-1;            
                                }
                               
                                // update
                                    remainingEL.attr({'data-count':new_count}).find('span').html(new_count);

                                    // hide if no tickets left
                                    if(new_count==0)    $(this).fadeOut();
                            }
                        // if need to be redirected to cart after adding
                            if(evotx_object.redirect_to_cart=='yes'){
                                window.location.href = evotx_object.cart_url;
                            }
                    }   
                });
            }
        
        return false;
    });

// 	inquiry submissions
    $('body').on('click','.evotx_INQ_btn', function(){
        $(this).siblings('.evotxINQ_box').slideToggle();
    });
    $('body').on('click','.evotx_INQ_submit', function(event){
        event.preventDefault;
        var form = $(this).closest('.evotxINQ_form');
        var notif = form.find('.notif');

        //reset notification
        notif.html( notif.attr('data-notif') );

        var data = {
            action: 'evoTX_ajax_06',
            event_id: form.attr('data-event_id'),
            ri: form.attr('data-ri'),
        };

        error = 'none';
        form.find('.evotxinq_field').each(function(index){
            if( $(this).val()=='') error='yes';
            data[$(this).attr('name')] = $(this).val();
        });

        if(error=='none'){
            $.ajax({
                type:'POST',url:evotx_object.ajaxurl,
                data:data,
                beforeSend: function(){
                    form.addClass('loading');
                },success:function(data){
                    form.slideUp();
                    form.siblings('.evotxINQ_msg').fadeIn(function(){
                        form.removeClass('loading');
                    });
                }
            });
        }else{
            notif.html( form.attr('data-err') );
        }
    });

// add to cart button from eventtop
    $('body').on('click','.evotx_add_to_cart em', function(){

    });
	
});