<?php
/**
 * 
 * eventon tickets front end class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon-tickets/Classes
 * @version     2.0
 */

class evotx_front{
	
	function __construct(){
		// event top inclusion
		// /add_filter('eventon_eventtop_one', array($this, 'eventop'), 10, 3);
		// /add_filter('evo_eventtop_adds', array($this, 'eventtop_adds'), 10, 1);
		// /add_filter('eventon_eventtop_evotx', array($this, 'eventtop_content'), 10, 2);
		
		$this->opt2 = get_evoOPT_array(2);
		
		// event card inclusion
		add_filter('eventon_eventCard_evotx', array($this, 'frontend_box'), 10, 2);
		add_filter('eventon_eventcard_array', array($this, 'eventcard_array'), 10, 4);
		add_filter('evo_eventcard_adds', array($this, 'eventcard_adds'), 10, 1);
		
		// scripts and styles 
		add_action( 'init', array( $this, 'register_styles_scripts' ) ,15);	
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 10 );


		// thank you page tickets
		add_action('woocommerce_thankyou', array( $this, 'wc_order_tix' ), 10 ,1);
		add_action('woocommerce_view_order', array( $this, 'wc_order_tix' ), 10 ,1);
		
		// order item name in emails
		add_filter('woocommerce_order_item_name', array($this, 'order_item_name'), 10, 2);

		// Passing Repeat interval related actions		
		add_filter('woocommerce_add_cart_item_data',array($this,'wdm_add_item_data'),1,2);
		add_filter('woocommerce_get_cart_item_from_session', array($this,'wdm_get_cart_items_from_session'), 1, 3 );

		// display custom date in cart
		add_filter('woocommerce_checkout_cart_item_quantity',array($this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);  
		add_filter('woocommerce_cart_item_price',array($this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);

		// saving meta data
		add_action('woocommerce_add_order_item_meta',array($this,'wdm_add_values_to_order_item_meta'),1,2);
		add_action('woocommerce_before_cart_item_quantity_zero',array($this,'wdm_remove_user_custom_data_options_from_cart'),1,1);

		add_action('woocommerce_checkout_order_processed', array($this, 'create_evo_tickets'), 10, 1);


		// adding extra fields for ticket names
		

	}


	// event top inclusion
		public function eventop($array, $pmv, $vals){
			$array['evotx'] = array(
				'vals'=>$vals,
			);
			return $array;
		}
		public function eventtop_content($object, $helpers){
			$output = '';
			$emeta = get_post_custom($object->vals['eventid']);


			// if tickets and enabled for the event
			if( !empty($emeta['evotx_tix']) && $emeta['evotx_tix'][0]=='yes'
				&& $object->vals['fields_'] && in_array('organizer',$object->vals['fields'])
			){

				$woo_product_id = $emeta['tx_woocommerce_product_id'][0];
				$woometa = get_post_custom($woo_product_id);
						
				$output .= "<span class='evotx_add_to_cart'><em>Add to cart</em></span>";
			}	

			return $output;
		}

		// event card inclusion functions		
			function eventtop_adds($array){
				$array[] = 'evotx';
				return $array;
			}

	// passing on Repeat interval for event to order		
		// pass RI session to WC session
			function wdm_add_item_data($cart_item_data,$product_id){
		        /*Here, We are adding item in WooCommerce session with, evotx_repeat_interval_wc name*/
		        global $woocommerce;
		       	//session_start();   
		       	//update_option('aaa',$_SESSION);
		        
		        //print_r($_REQUEST);
		        

		        //echo $_REQUEST['add-to-cart'].' '.$_REQUEST['ri'].' '.$_REQUEST['eid'].' '.$product_id;


		        if( !empty($_REQUEST['add-to-cart']) &&
		        	$_REQUEST['add-to-cart']==$product_id && 
		        	isset($_REQUEST['ri']) &&
		        	!empty($_REQUEST['eid'])
		        ){
		        	$new_value['evotx_repeat_interval_wc'] = (!empty($_REQUEST['ri'])? $_REQUEST['ri']:0);
		        	$new_value['evotx_event_id_wc'] = $_REQUEST['eid'];

		        	if(empty($cart_item_data))
		                return $new_value;
		            else
		                return array_merge($cart_item_data,$new_value);

		        }else{
		        	return $cart_item_data;
		        }
		    }
		// insert into cart object
		    function wdm_get_cart_items_from_session($item, $values, $key){
		    	    	
		        // updates values
		        if (array_key_exists( 'evotx_repeat_interval_wc', $values ) ){
		       		$item['evotx_repeat_interval_wc'] = $values['evotx_repeat_interval_wc'];
		        } 
		        if (array_key_exists( 'evotx_event_id_wc', $values ) ){
		       		$item['evotx_event_id_wc'] = $values['evotx_event_id_wc'];		       		
		        }  

		        return $item;
		    }
		// display custom data in the cart
		    function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key ) {

		    	//print_r($values);
		    	/*code to add custom data on Cart & checkout Page*/    
		        if(isset($values['evotx_repeat_interval_wc']) 	&& count($values['evotx_repeat_interval_wc']) > 0
		        ){
		        	$ri = (!empty($values['evotx_repeat_interval_wc']))? $values['evotx_repeat_interval_wc']: 0;

		        	$ticket_time = $this->get_proper_time($values['evotx_event_id_wc'], $ri);
		            $return_string = $product_name . "</a><dl class='variation'>";
		            $return_string .= "<table class='wdm_options_table' id='" . $values['product_id'] . "'>";
		            $return_string .= "<tr><td>".eventon_get_custom_language($this->opt2, 'evoTX_005a','Event Time').": <br/><i>" . $ticket_time . "</i></td></tr>";
		            $return_string .= "</table></dl>"; 
		            return $return_string;
		        }else if( !empty($values['evotx_event_id_wc']) ){
		        	return $product_name; 
		        }else{    
		        	return $product_name;    
		        }
		    }
		// add custom data as meta data to order item
		    function wdm_add_values_to_order_item_meta($item_id, $values){
		        global $woocommerce,$wpdb;

		        // if event id and repeat interval saved in session
		        if(isset($values['evotx_repeat_interval_wc']) && !empty($values['evotx_event_id_wc'])){
			        
			        $ri = (!empty($values['evotx_repeat_interval_wc']))? $values['evotx_repeat_interval_wc']: 0;
			        
			        $ticket_time = $this->get_proper_time($values['evotx_event_id_wc'], $ri);
			        $ticket_time_add = ' [RI'.$ri.']';

		        	wc_add_order_item_meta($item_id,'Event-Time',$ticket_time.$ticket_time_add); 
		        }			      
			}
		// remove custom data if item removed from cart
			function wdm_remove_user_custom_data_options_from_cart($cart_item_key){
		        global $woocommerce;
		        // Get cart
		        $cart = $woocommerce->cart->get_cart();
		        // For each item in cart, if item is upsell of deleted product, delete it
		        if(!empty($values['evotx_repeat_interval_wc'])){
			        foreach( $cart as $key => $values){
				        if ( $values['evotx_repeat_interval_wc'] == $cart_item_key ){
				            unset( $woocommerce->cart->cart_contents[ $key ] );
				        }
			        }
			    }
		    }

	// when order payment is complete create matching tickets
		function create_evo_tickets($order_id){
			$tix = new evotx_ticket();
			$tix->custom_process($order_id);
		}

	// get event neat times
		function get_proper_time($event_id, $ri){
			$tix = new evotx_ticket();

			$event_pmv = get_post_custom($event_id);
			if(!empty($event_pmv['repeat_intervals'])){
	    		$saved_ri = unserialize($event_pmv['repeat_intervals'][0]);
	    		$ticket_row_time = $saved_ri[$ri];

	    		$ticket_time = $tix->get_event_time($event_id, $ticket_row_time[0], $ticket_row_time[1]);
	    	}else{ // not repeating event
	    		$ticket_time = $tix->get_event_time($event_id, $event_pmv['evcal_srow'][0], $event_pmv['evcal_erow'][0]);
	    	}
	    	return $ticket_time;

		}
		
	// show tickets in front-end customer account pages
	// Only when order is completed
		public function wc_order_tix($oid){
			
			$order = new WC_Order( $oid );

			if ( in_array( $order->status, array( 'completed' ) ) ) {

				$tix = new evotx_ticket();

				if($tix->does_order_have_tickets($oid)){

					$tickets = $order->get_items();
					

					if($tickets && count($tickets)>0){

						$customer = get_post_meta($oid, '_customer_user');
						$userdata = get_userdata($customer[0]);

						$email_body_arguments = array(
							'orderid'=>$oid,
							'tickets'=>$tickets, 
							'customer'=>(isset($userdata->first_name)? $userdata->first_name:'').
								(isset($userdata->last_name)? ' '.$userdata->last_name:'').
								(isset($userdata->user_email)? ' '.$userdata->user_email:''),
							'email'=>''
						);

						$wrapper = "background-color: #e6e7e8;-webkit-text-size-adjust:none !important;margin:0;padding: 20px 20px 20px 20px;";

						$innner = "background-color: #ffffff; -webkit-text-size-adjust:none !important; margin:0;border-radius:5px;";
						
						ob_start();
						?>
						<h2><?php echo eventon_get_custom_language($this->opt2, 'evoTX_014','Your event Tickets');?></h2>
						<div style="<?php echo $wrapper; ?>">
						<div style='<?php echo $innner;?>'>
						<?php
							echo $tix->get_ticket_email_body_only($email_body_arguments);

						echo "</div></div>";

						echo ob_get_clean();
						
					}
				} // does order have tickets
			}

		}
	
	// product name on WC order email
		function order_item_name($item_name, $item){

			$_product = get_product($item['variation_id'] ? $item['variation_id'] : $item['product_id']);
			$event_id = get_post_meta($_product->ID, '_eventid', true);
			$startDate = get_post_meta($event_id, 'evcal_srow', true);

			if(!empty($startDate)){
				$date_addition = date('F j(l)', $startDate);
				return $item_name.' - '.$date_addition;
			}else{
				return $item_name;
			}
		}

	// styles are scripts
		public function load_styles(){
			global $evotx;

			//wp_register_script('tx_wc_simple', $evotx->plugin_url.'/assets/tx_wc_simple.js', array('jquery'), 1.0, true);			
			wp_register_script('tx_wc_variable', $evotx->plugin_url.'/assets/tx_wc_variable.js', array('jquery'), 1.0, true);
			wp_register_script('tx_wc_tickets', $evotx->plugin_url.'/assets/tx_script.js', array('jquery'), 1.0, true);

			wp_enqueue_script('tx_wc_variable');
			//wp_enqueue_script('tx_wc_simple');
			wp_enqueue_script('tx_wc_tickets');
			
			// localize script data
			$script_data = array_merge(array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				), $this->get_script_data());

			wp_localize_script( 
				'tx_wc_tickets', 
				'evotx_object',$script_data	);
		}
		public function register_styles_scripts(){	
			global $evotx;	
				
			wp_register_style( 'evo_TX_styles',$evotx->plugin_url.'/assets/tx_styles.css');
			//wp_register_script('evo_TX_script',$this->plugin_url.'/assets/tx_script.js', array('jquery'), 1.0, true );

			$this->print_scripts();
			add_action( 'wp_enqueue_scripts', array($this,'print_styles' ));
				
		}
		public function print_scripts(){
			// /wp_enqueue_script('evo_TX_ease');
			//wp_enqueue_script('evo_RS_mobile');	
			//wp_enqueue_script('evo_TX_script');	
		}
		function print_styles(){
			wp_enqueue_style( 'evo_TX_styles');	
		}
		
		/**
		 * Return data for script handles
		 * @access public
		 * @return array|bool
		 */
		function get_script_data(){
			global $evotx;
			return array(
				'cart_url'=>get_permalink( wc_get_page_id( 'cart' ) ),
				'redirect_to_cart'=>get_option( 'woocommerce_cart_redirect_after_add' )
			);
		}

	// add Ticket box to front end
		function frontend_box($object, $helpers){

			global $evotx, $woocommerce;
			
			$txmeta = get_post_custom($object->event_id);

			// not show event tickets if tickets to show only for loggedin users
			if( !empty($evotx->evotx_opt['evotx_loggedinuser']) && $evotx->evotx_opt['evotx_loggedinuser']=='yes' &&  !is_user_logged_in() )
				return;	

			if( !empty($txmeta['evotx_tix']) && $txmeta['evotx_tix'][0]=='yes' 
			):

				// get options array
				$woo_product_id = $txmeta['tx_woocommerce_product_id'][0];
				$woometa = get_post_custom($woo_product_id);

				$opt = $helpers['evoOPT2'];				


			ob_start();?>

				<div class='evorow evcal_evdata_row bordb evcal_evrow_sm evo_metarow_tix <?php echo $helpers['end_row_class']?>' data-tx='' data-event_id='<?php echo $object->event_id ?>' data-ri='<?php echo $object->repeat_interval; ?>'>
					<span class='evcal_evdata_icons'><i class='fa <?php echo get_eventON_icon('evcal__evotx_001', 'fa-tags',$helpers['evOPT'] );?>'></i></span>
					<div class='evcal_evdata_cell'>							
						<h3 class='evo_h3'><?php echo eventon_get_custom_language($opt, 'evoTX_001','Ticket Section Title');?></h3>
						<p class='evo_data_val'><?php echo evo_meta($woometa,'_tx_text');?></p>

						<?php
							// ticket image id - if exists
							$_tix_image_id = !empty($txmeta['_tix_image_id'])? $txmeta['_tix_image_id'][0]:false;
						?>

						<div class='evoTX_wc <?php echo ($_tix_image_id)? 'tximg':'';?>' data-si='<?php echo !empty($woometa['_sold_individually'])? $woometa['_sold_individually'][0]: '-';?>' >
							<div class='evoTX_wc_section'>

							<?php
								$_repeat_in_stock = true;
								$capacity_of_this_repeat = 'none';

								// check if capacity managed by repeat intervals of event
								if( evo_check_yn($txmeta,'_manage_repeat_cap') &&
									evo_check_yn($txmeta,'evcal_repeat') &&
									!empty($txmeta['repeat_intervals']) && 
									!empty($txmeta['ri_capacity']) &&
									$product->product_type == 'simple'
								){
									$ri_capacity = unserialize($txmeta['ri_capacity'][0]);
									$capacity_of_this_repeat = 
										(isset($ri_capacity[ $object->repeat_interval ]) )? 
											$ri_capacity[ $object->repeat_interval ]
											:0;
									$_repeat_in_stock = ($capacity_of_this_repeat==0)?
										false : true;
								}


								// get the woocommerce product
								$_pf = new WC_Product_Factory();
								$product = $_pf->get_product( $woo_product_id );

								if ( !$_repeat_in_stock || !empty($woometa['_stock_status']) && $woometa['_stock_status'][0]=='outofstock') :

									echo "<p class='evotx_soldout'>". eventon_get_custom_language($opt, 'evoTX_012','Sold Out!')."</p>";
								else:
									// SIMPLE product
									if($product->product_type == 'simple'):

										$url = $evotx->addon_data['plugin_path'].'/templates/template-add-to-cart-single.php';
										include($url);
										
									endif; // end simple product

									// VARIABLE Product
									if($product->product_type=='variable'):

										include($evotx->addon_data['plugin_path'].'/templates/template-add-to-cart-variable.php');

									endif;
								endif; // is_in_stock()	
							?>
							
							<?php
								// show remaining tickets or not
								if(
									$_repeat_in_stock &&
									evo_check_yn($txmeta,'_show_remain_tix') &&
									evo_check_yn($woometa,'_manage_stock') 
									&& !empty($woometa['_stock']) 
									&& $woometa['_stock_status'][0]=='instock' 
									&& 
									( (!empty($txmeta['remaining_count']) 
										&& (int)$txmeta['remaining_count'][0] >= $woometa['_stock'][0]
										) ||
										empty($txmeta['remaining_count'])
									)
									&& 
									$product->product_type == 'simple'
								){

									// get the remaining ticket 
									// count for event
									// show this remaining total only for simple events
									$remaining_count = ($capacity_of_this_repeat != 'none' )? $capacity_of_this_repeat: $woometa['_stock'][0];
									$remaining_count = (int)$remaining_count;

									echo "<p class='evotx_remaining' data-count='{$remaining_count}'><span>" . $remaining_count . "</span> " . eventon_get_custom_language($opt, 'evoTX_013','Tickets remaining!')  . "</p>";
								}
							?>

							<?php
								// inquire before buy form
								include('html-ticket-inquery.php');
							?>

							<?php
							?>

							</div><!-- .evoTX_wc_section -->
							<?php 
								// content for ticket image seciton
								if($_tix_image_id):
								$img_src = ($_tix_image_id)? 
									wp_get_attachment_image_src($_tix_image_id,'medium'): null;
								$tix_img_src = (!empty($img_src))? $img_src[0]: null;
							?>
								<div class='evotx_image'>
									<img src='<?php echo $tix_img_src;?>'/>
									<?php if(!empty($txmeta['_tx_img_text'])):?>
										<p class='evotx_caption'><?php echo $txmeta['_tx_img_text'][0];?></p>
									<?php endif;?>
								</div><div class="clear"></div>
							<?php endif;?>
						</div>						
					</div>

					
					<div class='tx_wc_notic' style='display:none'>
						<p><b></b><span><?php echo eventon_get_custom_language($opt, 'evoTX_009','Successfully added to cart!');?></span> <a class='evcal_btn view_cart' href='<?php echo $woocommerce->cart->get_cart_url();?>'><?php echo eventon_get_custom_language($opt, 'evoTX_011','View cart');?></a> <a class='evcal_btn checkout' href='<?php echo $woocommerce->cart->get_checkout_url();?>'><?php echo eventon_get_custom_language($opt, 'evoTX_010','Checkout');?></a><em></em></p>
					</div>
				<?php echo $helpers['end'];?> 
				</div>


			<?php 
			$output = ob_get_clean();

			return $output;

			endif;
		}
		// event card inclusion functions
			function eventcard_array($array, $pmv, $eventid, $repeat_interval){
				$array['evotx']= array(
					'event_id' => $eventid,
					'repeat_interval'=>$repeat_interval,
					'value'=>'tt'
				);
				return $array;
			}
			function eventcard_adds($array){
				$array[] = 'evotx';
				return $array;
			}
}
new evotx_front();