<?php
/**
 * 
 * eventon tickets front and admin class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon-tickets/Classes
 * @version     0.1
 */

class evotx_ticket{
	
	function __construct(){
		//add_action('woocommerce_payment_complete', array($this, 'custom_process'), 10, 1);
		//add_action('woocommerce_order_status_completed', array($this, 'custom_process'), 10, 1);
		// /add_action('woocommerce_checkout_order_processed', array($this, 'custom_process'), 10, 1);
		//add_action('woocommerce_order_status_changed', array($this, 'order_status_change'), 10, 3);

		$sendTixEmails = get_evoOPT('tx', 'evotx_tix_email');

		// send email when order is complete w/ tix
		if($sendTixEmails != 'yes'){
			add_action('woocommerce_order_status_completed', array($this, 'send_ticket_email'), 10, 1);	
		}
	}

	// connect function for sending emails
		function send_ticket_email($order_id){
			global $evotx;
			$evotx->send_ticket_email($order_id);
		}	
	
	// when order payment is completed 
	// Create matching evo-tix for each order
	// connected via class-frontend.php
		public function custom_process($order_id){	
			
			$order = new WC_Order( $order_id );	
		    $items = $order->get_items();

		    $index = 1;
		    
		    // for each order item
		    foreach ($items as $item) {	

		    	$tixids = array();
		    	$eid = get_post_meta( $item['product_id'], '_eventid', true);  	

		    	// Make sure these are indeed ticket sales
		    	//$terms = wp_get_post_terms($item['product_id'], 'product_cat', array('fields'=>'names'));  			    	

		    	// Check if these order items are event ticket items
		    	if(!empty($eid) ){

		    		// get order post meta array
				    $order_meta = get_post_custom($order_id, true);	    	    
				    $user_id_ = $order_meta['_customer_user'][0];			    

		    		// Specify order type only for ticket sale
			    	if($index==1)
			    		update_post_meta($order_id, '_order_type','evotix');	    		

		    		// get repeat interval for order item
				    	$item_meta = (!empty($item['Event-Time'])? $item['Event-Time']: false);
				    	if($item_meta){
				    		$ri__ = explode('[RI', $item_meta);
					    	$ri_ = explode(']', $ri__[1]);
					    	$ri = $ri_[0];
				    	}else{
				    		$ri = 0;
				    	}				    	

				    // Get customer information
				    	if($user_id_ == 0){	// checkout without creating account
				    		$_user = array(
		    					'name'=>$order_meta['_billing_first_name'][0].' '.$order_meta['_billing_last_name'][0],
		    					'email'=>$order_meta['_billing_email'][0]
		    				);
				    	}else{
				    		//$myuser_id = $order->user_id;
		    				$usermeta = get_user_meta( $user_id_ );
		    				$_user = array(
		    					'name'=>$usermeta['first_name'][0].' '.$usermeta['last_name'][0],
		    					'email'=>$usermeta['billing_email'][0]
		    				);
				    	}
		    		
		        	// create new event ticket post
					if($created_tix_id = $this->create_post()){

						$ticket_ids = $ticket_ids_ = array();
						
						// variation product
							if(!empty($item['variation_id'])){
								$_product = new WC_Product_Variation($item['variation_id'] );
			        			$hh= $_product->get_variation_attributes( );

			        			foreach($hh as $f=>$v){
			        				$type = $v;
			        			}
			        		}else{ $type = 'Normal'; }

			        	// ticket ID(s)
				        	$tid = $created_tix_id.'-'.$order_id.'-'.( !empty($item['variation_id'])? $item['variation_id']: $item['product_id']);
							if($item['qty']>1){
								$_tid='';
								$str = 'A';
								for($x=0; $x<$item['qty']; $x++){ // each ticket in item
									$strng = ($x==0)? $str: ++$str;
									$ticket_ids[$tid.$strng] = 'check-in';
									$ticket_ids_[] = $tid.$strng;
								}
							}else{ // just one ticket
								$ticket_ids[$tid] = 'check-in';
								$ticket_ids_[] = $tid;
							}
	        	
						// save ticket data	
							$this->create_custom_fields($created_tix_id, 'name', $_user['name']);
							$this->create_custom_fields($created_tix_id, 'email', $_user['email']);
							$this->create_custom_fields($created_tix_id, 'qty', $item['qty']);					
							$this->create_custom_fields($created_tix_id, 'cost', $order->get_line_subtotal($item) );					
							$this->create_custom_fields($created_tix_id, 'type', $type);
							$this->create_custom_fields($created_tix_id, 'ticket_ids', $ticket_ids);
							$this->create_custom_fields($created_tix_id, 'wcid', $item['product_id']);
							$this->create_custom_fields($created_tix_id, 'tix_status', 'none');
							$this->create_custom_fields($created_tix_id, 'status', 'check-in');
							$this->create_custom_fields($created_tix_id, '_eventid', $eid);
							$this->create_custom_fields($created_tix_id, '_orderid', $order_id);
							$this->create_custom_fields($created_tix_id, '_customerid', $user_id_);
							$this->create_custom_fields($created_tix_id, 'repeat_interval', $ri);

							// save event ticket id to order id
								$tixids = get_post_meta($order_id, '_tixids', true);

								if(is_array($tixids)){ // if previously saved tixid array
									$tixids_ = array_merge($tixids, $ticket_ids_);
								}else{ // empty of saved as string
									$tixids_ = $ticket_ids_;
								}
								// save ticket ids as array
								update_post_meta($order_id, '_tixids', $tixids_);
							
							// update product capacity if repeat interval capacity is set 
							// seperately per individual repeat interval
								$emeta = get_post_meta($eid);

								if(	evo_check_yn($emeta,'_manage_repeat_cap') &&
									evo_check_yn($emeta,'evcal_repeat') &&
									!empty($emeta['repeat_intervals']) && 
									!empty($emeta['ri_capacity'])
								){
									
									// repeat capacity values for this event
									$ri_capacity = unserialize($emeta['ri_capacity'][0]);

									// repeat capacity for this repeat  interval
									$capacity_for_this_event = $ri_capacity[$ri];
									$new_capacity = $capacity_for_this_event-$item['qty'];

									$ri_capacity[$ri] = ($new_capacity>=0)? $new_capacity:0;

									// save the adjusted repeat capacity
									update_post_meta($eid, 'ri_capacity',$ri_capacity);

								}

					}
				}
				$index++;
		    } // endforeach
			
			
		}

	// Support functions
		function create_post() {
			
			// tix post status
			$opt_draft = 'publish'; 
				
	        $type = 'evo-tix';
	        $valid_type = (function_exists('post_type_exists') &&  post_type_exists($type));

	        if (!$valid_type) {
	            $this->log['error']["type-{$type}"] = sprintf(
	                'Unknown post type "%s".', $type);
	        }
	       
	        $title = 'TICKET '.date('M d Y @ h:i:sa', time());

	        $new_post = array(
	            'post_title'   => $title,
	            'post_status'  => $opt_draft,
	            'post_type'    => $type,
	            'post_name'    => sanitize_title($title),
	            'post_author'  => $this->get_author_id(),
	        );
	       
	        // create!
	        $id = wp_insert_post($new_post);
	       
	        return $id;
	    }
		function create_custom_fields($post_id, $field, $value) {       
	        add_post_meta($post_id, $field, $value);
	    }
	    function update_custom_fields($post_id, $field, $value) {       
	        update_post_meta($post_id, $field, $value);
	    }
    	function get_author_id() {
			$current_user = wp_get_current_user();
	        return (($current_user instanceof WP_User)) ? $current_user->ID : 0;
	    }	
	    function get_event_post_date() {
	        return date('Y-m-d H:i:s', time());        
	    }

	// additions
	    // tickets body for emails
			function get_ticket_email_body($args){
				global $eventon;

				ob_start();
				echo $eventon->get_email_part('header');

				echo $this->get_ticket_email_body_only($args);
				
				echo $eventon->get_email_part('footer');

				return ob_get_clean();
			}
			
			function get_ticket_email_body_only($args){
				global $eventon, $evotx;

				ob_start();
				
				// email body message
					/**
					 * path like: ../plugins/eventon-tickets/templates/email/ticket_confirmation_email.php
					 */
					$file = 'ticket_confirmation_email';
					$path = $evotx->addon_data['plugin_path']."/templates/email/";	

					$args = array($args, true);

					$paths = array(
						0=> TEMPLATEPATH.'/'.$eventon->template_url.'templates/email/tickets/',
						1=> $path,
					);

					$file_name = $file.'.php';
					foreach($paths as $path_){	
						// /echo $path.$file_name.'<br/>';			
						if(file_exists($path_.$file_name) ){	
							$template = $path_.$file_name;	
							break;
						}
					}

					include($template);

				return ob_get_clean();
			}
		
		// reusable tickets HTML for an order
			function get_tickets($tix, $email=false){

				global $eventon, $evotx;

				/**
				 * path like: ../plugins/eventon-tickets/templates/email/ticket_confirmation_email.php
				 */
				$file = 'ticket_confirmation_email';
				$path = $evotx->addon_data['plugin_path']."/templates/email/";

				$args = array($tix, $email);

				// GET email 
				$message = $eventon->get_email_body($file,$path, $args);

				return $message;

			}

		// get ticket item id from ticket id
			function get_tiid($ticket_id){
				$tix = explode('-', $ticket_id);
				return $tix[0];
			}

		// Get neat formatted event time
			function get_event_time($event_id, $start='', $end=''){

				if(empty($start) && empty($end)){
					$times = $this->get_correct_times($event_id);
					$start = $times['start'];
					$end = $times['end'];
				}

				$ST = eventon_get_formatted_time($start);
				$EN = eventon_get_formatted_time($end);
				$date_format = get_option('date_format');
				$time_format = get_option('time_format');

				$output='';
				// if same start year
				if($ST['Y'] == $EN['Y']){
					if($ST['n']== $EN['n']){// same month
						if($ST['j']==$EN['j']){ // same date
							$output = date($date_format, $start). ' '.date($time_format, $start).'-'.date($time_format, $end);
						}else{
							$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $end).' '.date($time_format, $end);
						}					
					}else{
						$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $end).' '.date($time_format, $end);
					}
				}else{
					$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $end).' '.date($time_format, $end);
				}
				return $output;
			}
		// return repeat interval corrected unix time values
			function get_correct_times($event_id, $event_pmv=''){

				$event_post_meta= !empty($event_pmv)? $event_pmv: get_post_custom($event_id);

				$repeat_interval = $this->repeat_interval();

				if(!empty($repeat_interval) && !empty($event_post_meta['repeat_intervals'])){
					$repeat_interval = (int)$repeat_interval;	// convert to interval

					$intervals = unserialize($event_post_meta['repeat_intervals'][0]);
					$start_row = $intervals[$repeat_interval][0];
					$end_row = $intervals[$repeat_interval][1];
					
					$end_row = (!empty($event_post_meta['evcal_erow'])? $event_post_meta['evcal_erow'][0]:$start_row);	
				}else{  
					$start_row = $event_post_meta['evcal_srow'][0];
					$end_row = $event_post_meta['evcal_erow'][0];
				}
				return array( 'start'=>$start_row, 'end'=>$end_row);
			}
			function repeat_interval(){
				return !empty($this->ticketItem_meta['repeat_interval'])? $this->ticketItem_meta['repeat_interval'][0]: 0;
			}
		// corrected ticket IDs
			public function correct_tix_ids($t_pmv, $ticket_item_id){
				$tix = explode(',', $t_pmv['tid'][0]);
				foreach($tix as $tt){
					$ticket_ids[$tt] = 'check-in';
				}
				
				update_post_meta($ticket_item_id, 'ticket_ids',$ticket_ids);
			}

	// CHECKING & Status
		// get proper ticket status name I18N
			function get_checkin_status($status, $lang=''){
				global $evotx;
				$evopt = $evotx->opt2;
				$lang = (!empty($lang))? $lang : 'L1';

				if($status=='check-in'){
					return (!empty($evopt[$lang]['evoTX_003x']))? $evopt[$lang]['evoTX_003x']: 'check-in';
				}else{
					return (!empty($evopt[$lang]['evoTX_003y']))? $evopt[$lang]['evoTX_003y']: 'checked';
				}
			}

		// check if an order have event tickets
			public function does_order_have_tickets($order_id){
				$meta = get_post_meta($order_id, '_tixids', true);
				return (!empty($meta))? true: false;
			}

}
new evotx_ticket();