<?php
/**
 * Event Tickets Ajax Handletx
 *
 * Handles AJAX requests via wp_ajax hook (both admin and front-end events)
 *
 * @author 		AJDE
 * @category 	Core
 * @package 	EventON-TX/classes/AJAX
 * @vetxion     2.0
 */

class evo_tix_ajax{
	/**
	 * Hook into ajax events
	 */
	public function __construct(){
		$ajax_events = array(
			'the_ajax_evotx_a1'=>'evotx_get_attendees',
			'the_ajax_evotx_a5'=>'evoTX_checkin_',
			'the_ajax_evotx_a3'=>'generate_csv',
			'evotx_woocommerce_add_to_cart'=>'evotx_woocommerce_ajax_add_to_cart',
			'the_ajax_evotx_a55'=>'admin_resend_confirmation',
			'evoTX_ajax_06'=>'evoTX_ajax_06',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_'.  $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_'.  $ajax_event, array( $this, $class ) );
		}
	}

	// submit inqurry form
		function evoTX_ajax_06(){

			$evoOpt = get_evoOPT_array(1);

			$event_id = $_POST['event_id'];
			$ri = $_POST['ri'];

			add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));	
			$_event_pmv = get_post_custom($event_id);
			
			// get email address
			$_to_mail = (!empty($_event_pmv['_tx_inq_email']))? $_event_pmv['_tx_inq_email'][0]:
				( !empty($evoOpt['evotx_tix_inquiries_def_email'])? $evoOpt['evotx_tix_inquiries_def_email']:
					get_option('admin_email'));
			// get subject
			$subject = (!empty($_event_pmv['_tx_inq_subject']))? $_event_pmv['_tx_inq_subject'][0]:
				( !empty($evoOpt['evotx_tix_inquiries_def_subject'])? $evoOpt['evotx_tix_inquiries_def_subject']:'New Ticket Sale Inquery');

			$headers = 'From: '.$_POST['email'];	

			ob_start();?>
				<p>Event: <br/><?php echo get_the_title( $event_id ); ?></p>
				<p>From: <br/><?php echo $_POST['name'];?></p>
				<p>Message: <br/><?php echo $_POST['message'];?></p>
			<?php
			$body = ob_get_clean();

			$send_wp_mail = wp_mail($_to_mail, $subject, $body, $headers);

		}

	// get attendeed count
		function evotx_get_attendees(){		
			$nonce = $_POST['postnonce'];
			$status = 0;
			$message = $content = '';

			if(! wp_verify_nonce( $nonce, 'evotx_nonce' ) ){
				$status = 1;	$message ='Invalid Nonce';
			}else{

				global $evotx_admin;
				ob_start();

				$customer_ = $evotx_admin->get_customer_ticket_list($_POST['eid'], $_POST['wcid']);

				// customers with completed orders
				if($customer_){
					echo "<div class='evotx'>";
					echo "<p class='header'>Attendee Name <span class='txcount'>Ticket Count</span></p>";	

					$tix = new evotx_ticket();
					// each customer
					foreach($customer_ as $customer=>$cus){
						echo "<p class='attendee'>";					
						$index = 1;
						// each ticket item
						foreach($cus as $ticketItem_){
							echo ($index==1)? "<span class='evotx_ticketitem_header'>".$customer." ({$ticketItem_['email']}) - <b>{$ticketItem_['type']}</b></span>":'';
							echo "<span class='evotx_ticketItem'><span class='txcount'>{$ticketItem_['qty']}</span>";

							$tid = $ticketItem_['tids']; // ticket ID array with status

							echo "<span class='tixid'>";
							
							// for each ticket ID
							foreach($tid as $id=>$_status){
								$langStatus = $tix->get_checkin_status($_status);
								echo "<span class='evotx_ticket'><span class='evotx_status {$_status}' data-tid='{$id}' data-status='{$_status}' data-tiid='{$ticketItem_['tiid']}'>".$langStatus."</span> ".$id."</span>";
							}
							echo "</span></span>";
							$index++;
						}
						echo "</p>";					
					}
					echo "</div>";
				}else{
					echo "<div class='evotx'>";
					echo "<p class='header nada'>Could not find attendees with completed orders.</p>";	
					echo "</div>";
				}
				
				$content = ob_get_clean();
			}
					
			$return_content = array(
				'message'=> $message,
				'status'=>$status,
				'content'=>$content,
			);
			
			echo json_encode($return_content);		
			exit;
		}

	// for evo-tix post page and from event edit page
		function evoTX_checkin_(){
			$ticket_item_id = $_POST['tiid'];
			$ticket_id = $_POST['tid'];
			$current_status = $_POST['status'];

			$ticketItem = new evotx_TicketItem($ticket_item_id);

			$other_status = $ticketItem->get_other_status($current_status);
			$ticketItem->change_ticket_status($other_status[0], $ticket_id, $ticket_item_id);


			$return_content = array(
				'new_status'=>$other_status[0],
				'new_status_lang'=>$other_status[1],
			);
			
			echo json_encode($return_content);		
			exit;
		}

	// Download csv list of attendees
		function generate_csv(){

			$e_id = $_REQUEST['e_id'];
			$event = get_post($e_id, ARRAY_A);

			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=".$event['post_name']."_".date("d-m-y").".csv");
			header("Pragma: no-cache");
			header("Expires: 0");


			global $evotx_admin;
			$customers = $evotx_admin->get_customer_ticket_list($e_id, $_REQUEST['pid']);

			if($customers){
				//$fp = fopen('file.csv', 'w');

				echo "Name, Email Address, Ticket IDs, Quantity, Ticket Type\n";
				

				$tix = new evotx_ticket();

				// each customer
				foreach($customers as $customer=>$cus){
					// each ticket item
					foreach($cus as $ticketItem_){
						
						$tid = $ticketItem_['tids']; // ticket ID array with status
						
						// for each ticket ID
						foreach($tid as $id=>$_status){						
							$langStatus = $tix->get_checkin_status($_status);

							echo $customer.",".$ticketItem_['email'].",".$id.",1,".$ticketItem_['type']."\n";
						}			
					}
					
				}
			}

		}

	// ADD to cart for variable items
		function evotx_woocommerce_ajax_add_to_cart() {
			global $woocommerce;
			 
			// Initial values
				$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
				$variation_id     = apply_filters( 'woocommerce_add_to_cart_variation_id', absint( $_POST['variation_id'] ) );
				$quantity  = empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				
			// if variations are sent
				if(isset($_POST['variations'])){
					$att=array();
					foreach($_POST['variations'] as $varF=>$varV){
						$att[$varF]=$varV;
					}
				}
			

			if($passed_validation && !empty($variation_id)){
				$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ,$att);
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				$frags = new WC_AJAX( );
	        	$frags->get_refreshed_fragments( );
			}

			/*
				// if variation ID is given
				if(!empty($variation_id) && $variation_id > 0){
					
					$cart_item_key = $woocommerce->cart->add_to_cart( $product_id, $quantity, $variation_id ,$att);
					 
					do_action( 'woocommerce_ajax_added_to_cart', $product_id ,$quantity, $variation_id ,$variation);

					// Return fragments
					//$frags = new WC_AJAX( );
		        	//$frags->get_refreshed_fragments( );


					// if WC settings set to redirect after adding to cart
					if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
						// show cart notification
					 	wc_add_to_cart_message( $product_id );
					 	$woocommerce->set_messages();
					}
				}else{
				 
					if ( $passed_validation && $woocommerce->cart->add_to_cart( $product_id, $quantity) ) {
						do_action( 'woocommerce_ajax_added_to_cart', $product_id );
						 
						if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
						 	woocommerce_add_to_cart_message( $product_id );
						 	$woocommerce->set_messages();
						}
						 
						// Return fragments
						// $frags = new WC_AJAX( );
		        		// $frags->get_refreshed_fragments( );
					 
					} else {
					 
						header( 'Content-Type: application/json; charset=utf-8' );
						 
						// If there was an error adding to the cart, redirect to the product page to show any errors
						$data = array(
						 	'error' => true,
						 	'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
						);
						 
						$woocommerce->set_messages();
						 
						echo json_encode( $data );
					 
					}
					die();
				} // endif
			
			*/
		
			$output = array(
				'key'=>$cart_item_key,
				'variation'=>WC()->cart->cart_contents_total
			);
			echo json_encode( $output );
		 }

	// resend confirmation
		function admin_resend_confirmation(){
			global $evotx;

			$tiid = $_POST['tiid'];			
			$order_id = get_post_meta($tiid, '_orderid', true);
			
			$send_mail = $evotx->send_ticket_email($order_id);

			$return_content = array(
				'status'=>'0'
			);
			
			echo json_encode($return_content);		
			exit;
		}
	


}
new evo_tix_ajax();


?>