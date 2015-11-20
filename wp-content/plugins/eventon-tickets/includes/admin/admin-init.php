<?php
/*
	Event Tickets Admin init
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon-tickets/Classes
 * @version     0.2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evotx_admin{
	
	private $addon_data;
	private $urls;
	private $evotx_opt;

	public $evotx_email_vals;

	function __construct(){
		add_action('admin_init', array($this, 'evotx_admin_init'));
		include_once('evo-tx_meta_boxes.php');
		include_once('evo-tix.php');

		// HOOKs		
		$this->evotx_opt = get_option('evcal_options_evcal_tx');

		add_filter('evo_event_columns', array($this, 'add_column_title'), 10, 1);
		add_filter('evo_column_type_woo', array($this, 'column_content'), 10, 1);

		// appearance
		add_filter( 'eventon_appearance_add', array($this, 'evotx_appearance_settings' ), 10, 1);
		add_filter( 'eventon_inline_styles_array',array($this, 'evotx_dynamic_styles') , 10, 1);

		// actions when event moved to trash that have wc product
		add_action('wp_trash_post', array($this, 'move_to_trash'));

		add_action('eventon_duplicate_product', array($this, 'when_event_duplicate'), 10, 2);
		add_action( 'transition_post_status', array($this,'update_wc_status'), 10, 3 );

		// add edit event button to wc product ticket edit page
		add_filter( 'post_submitbox_misc_actions', array($this,'event_edit_button'),10,2 );

		add_action( 'admin_menu', array( $this, 'menu' ),9);
		add_action( 'admin_menu', array( $this, 'order_tix' ),99);
		
	}

	
	// Initiate admin for tickets addon
		function evotx_admin_init(){

			// language
			add_filter('eventon_settings_lang_tab_content', array($this,'evotx_language_additions'), 10, 1);

			// eventCard inclusion
			add_filter( 'eventon_eventcard_boxes',array($this,'evotx_add_toeventcard_order') , 10, 1);

			// icon in eventon settings
			add_filter( 'eventon_custom_icons',array($this,'evotx_custom_icons') , 10, 1);

			global $pagenow, $typenow, $wpdb, $post;	
			
			if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
				$typenow = $post->post_type;
			} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
		        $post = get_post( $_GET['post'] );
		        $typenow = $post->post_type;
		    } elseif (empty( $typenow ) && ! empty( $_GET['post-type'] ) ) {
		    	$typenow = $_GET['post-type'];
		    }

			if ( $typenow == '' || $typenow == "ajde_events" || $typenow =='evo-tix') {

				// Event Post Only
				$print_css_on = array( 'post-new.php', 'post.php' );

				foreach ( $print_css_on as $page ){
					add_action( 'admin_print_styles-'. $page, array($this,'evotx_event_post_styles') );		
				}
			}

			// include ticket id in the search
			if($typenow =='' || $typenow == 'evo-tix'){
				// Filter the search page
				add_filter('pre_get_posts', array($this, 'evotx_search_pre_get_posts'));		
			}

			if($pagenow == 'edit.php' && $typenow == 'evo-tix'){
				add_action( 'admin_print_styles-edit.php', array($this, 'evotx_event_post_styles' ));	
			}

			// settings
			add_filter('eventon_settings_tabs',array($this,'evotx_tab_array') ,10, 1);
			add_action('eventon_settings_tabs_evcal_tx',array($this,'evotx_tab_content') );

		}

	// EventON settings menu inclusion
		function menu(){
			add_submenu_page( 'eventon', 'Tickets', __('Tickets','eventon'), 'manage_eventon', 'admin.php?page=eventon&tab=evcal_tx', '' );
		}
		
	// return customer tickets array by event id and product id
		function get_customer_ticket_list($event_id, $wcid){
			// get all ticket items matching product id and event id
			$ticketItems = new WP_Query(array(
				'posts_per_page'=>-1,
				'post_type'=>'evo-tix',
				'meta_query' => array(
					'relation' => 'AND',
					array('key' => 'wcid','value' => $wcid,'compare' => '=',	),
					array('key' => '_eventid','value' => $event_id,'compare' => '=',	),
				)
			));
			$customer_ = array();

			if($ticketItems->have_posts()):
				while($ticketItems->have_posts()): $ticketItems->the_post();
					$tiid = get_the_ID();
					$tii_meta = get_post_custom($tiid);

					$order_id = !empty($tii_meta['_orderid'])? $tii_meta['_orderid']: false;
					$orderOK = false;
					if($order_id){
						$order = new WC_Order( $order_id[0] );
						$order_status = $order->status;
						$orderOK = ($order_status=='completed')? true:false;
					}

					if($orderOK){ // if the order was completed for these tickets
						$ticket_item = new evotx_TicketItem($tiid, $tii_meta);
						$ticketids = $ticket_item->ticket_ids();

						$customer_[$tii_meta['name'][0]][] = array(
							'tiid'=>$tiid,
							'tids'=>$ticketids,
							'email'=>$tii_meta['email'][0],						
							'type'=>$tii_meta['type'][0],					
							'qty'=>$tii_meta['qty'][0],					
						);
					}
				endwhile;
			endif;

			return (count($customer_)>0)? $customer_: false;
		}
	
	// duplication of events
		public function when_event_duplicate($new_event_id, $post){
			$pmv = get_post_meta($new_event_id);

			// if there is a ticket wc product attach to this
			if(!empty($pmv['tx_woocommerce_product_id'])){
				$wc_post = get_post($pmv['tx_woocommerce_product_id'][0]);

				// create a duplicate of associated wc tix product for new duplicated event
				$new_wc_id = eventon_create_duplicate_from_event($wc_post);
				update_post_meta( $new_event_id, 'tx_woocommerce_product_id',$new_wc_id);
				update_post_meta( $new_wc_id, '_eventid',$new_event_id);

			}
		}
	// update associated wc product status along side event post status
		public function update_wc_status( $new_status, $old_status, $post ) {
			if($post->post_type=='ajde_events'){
				$tx_wc_id = get_post_meta($post->ID, 'tx_woocommerce_product_id', true);
				// only events with wc tx product association
				if(!empty($tx_wc_id)){
					$product = get_post($tx_wc_id, 'ARRAY_A');
					$product['post_status']= $new_status;
					wp_update_post($product);
				}				
			}
		}

	// ADD NEW
		function add_new_woocommerce_product($post_id){
			$user_ID = get_current_user_id();
			$_date_addition = (!empty($_POST['evcal_start_date']))? ' - '.$_POST['evcal_start_date']:null;

			$__sku = !empty($_REQUEST['_sku'])? '('. $_REQUEST['_sku'].') ':'';
			$post = array(
				'post_author' => $user_ID,
				'post_content' => (!empty($_REQUEST['_tx_desc']))? $_REQUEST['_tx_desc']: "Event Ticket",
				'post_status' => "publish",
				'post_title' => 'Ticket: '. $__sku.$_REQUEST['post_title'].$_date_addition,
				'post_type' => "product"
			);

			// create woocommerce product
			$woo_post_id = wp_insert_post( $post );
			if($woo_post_id){
				
				//wp_set_object_terms( $woo_post_id, $product->model, 'product_cat' );
				wp_set_object_terms($woo_post_id, $_REQUEST['tx_product_type'], 'product_type');
				

				update_post_meta( $post_id, 'tx_woocommerce_product_id', $woo_post_id);
				$this->save_product_meta_values($woo_post_id, $post_id);

				// add category 
				$this->assign_woo_cat($woo_post_id);

				// copy featured event image
				$ft_img_id = get_post_thumbnail_id($post_id);
				if(!empty($ft_img_id)) set_post_thumbnail( $post_id, $ft_img_id );
			}
		}
		
	// UPDATE
		function update_woocommerce_product($woo_post_id, $post_id){
			$user_ID = get_current_user_id();

			$post = array(
				'ID'=>$woo_post_id,
				'post_author' => $user_ID,
				'post_content' => (!empty($_REQUEST['_tx_desc']))? $_REQUEST['_tx_desc']: "Event Ticket",
				'post_status' => "publish",
				'post_title' => $_REQUEST['post_title'],
				'post_type' => "product",				
			);

			// create woocommerce product
			$woo_post_id = wp_update_post( $post );
			
			//update_post_meta( $post_id, 'tx_woocommerce_product_id', $woo_post_id);
			//wp_set_object_terms( $woo_post_id, $product->model, 'product_cat' );

			wp_set_object_terms($woo_post_id, $_POST['tx_product_type'], 'product_type');		

			$this->save_product_meta_values($woo_post_id, $post_id);
			
		}
		// front save values
			function save_product_meta_values($woo_post_id, $post_id){

				$update_metas = array(	
					'_sku'=>'_sku',
					'_regular_price'=>'_regular_price',
					'_sale_price'=>'_sale_price',
					'_price'=>'_price',
					'_visibility'=>'hidden',
					'_virtual'=>'yes',
					'_stock_status'=>'_stock_status',
					'_sold_individually'=>'_sold_individually',
					'_manage_stock'=>'_manage_stock',
					'_stock'=>'_stock',
					'_backorders'=>'_backorders',
					'evotx_price'=>'_regular_price',
					'_tx_desc'=>'_tx_desc',
					'_tx_text'=>'_tx_text',
					'_eventid'=>$post_id,
				);

				foreach($update_metas as $umeta=>$umetav){
					if($umeta == '_regular_price' || $umeta == '_sale_price'|| $umeta == '_price'){

						if($umeta == '_sale_price'){
							if(isset($_POST[$umetav])){
								$price = str_replace("$","",$_POST[$umetav]);
								update_post_meta($woo_post_id, $umeta,  $price);
								update_post_meta($woo_post_id, '_price', $price );
							}							
						}else{
							if(isset($_POST[$umetav]))
								update_post_meta($woo_post_id, $umeta, str_replace("$","",$_POST[$umetav]) );
						}
					}else if($umeta == '_eventid'){
						update_post_meta($woo_post_id, $umeta, $post_id);
					}else if($umeta == '_visibility' || $umeta == '_virtual'){
						update_post_meta($woo_post_id, $umeta, $umetav);
					}else if($umeta == 'evotx_price'){
						$__price = (!empty($_POST[$umetav]))? $_POST[$umetav]: ' ';
						update_post_meta($post_id, $umeta, $__price);
					}else if($umeta == '_stock_status'){
						$_stock_status = (!empty($_POST[$umetav]) && $_POST[$umetav]=='yes')? 'outofstock': 'instock';
						update_post_meta($woo_post_id, $umeta, $_stock_status);
					}else{
						if(isset($_POST[$umetav]))
							update_post_meta($woo_post_id, $umeta, $_POST[$umetav]);
					}
				}
			}


		// create and assign woocommerce product category for foodpress items
			function assign_woo_cat($post_id){

				// check if term exist
				$terms = term_exists('Ticket', 'product_cat');
				if(!empty($terms) && $terms !== 0 && $terms !== null){
					wp_set_post_terms( $post_id, $terms, 'product_cat' );
				}else{
					// create term
					$new_termid = wp_insert_term(
					  	'Ticket', // the term 
					  	'product_cat',
					  	array(
					  		'slug'=>'ticket'
					 	)
					);

					// assign term to woo product
					wp_set_post_terms( $post_id, $new_termid, 'product_cat' );
				}
				
			}

	// SUPPORT
		// check if post exist for a ID
			function post_exist($ID){
				global $wpdb;

				$post_id = $ID;
				$post_exists = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $post_id . "'", 'ARRAY_A');
				return $post_exists;
			}
		// add new column to menu items
			function add_column_title($columns){
				$columns['woo']= '<i title="Connected to woocommerce">Woo</i>';
				return $columns;
			}
			function column_content($post_id){				
				$evotx_tix = get_post_meta($post_id, 'evotx_tix', true);

				if(!empty($evotx_tix) && $evotx_tix=='yes'){
					$__woo = get_post_meta($post_id, 'tx_woocommerce_product_id', true);
					$__wo_perma = (!empty($__woo))? get_edit_post_link($__woo):null;
					return (!empty($__woo))?'<a href="'.$__wo_perma.'">'.__('Yes','eventon').'</a>':__('No','eventon');
				}else{
					return __('No','eventon');
				}
			}
		
	    // move a menu items to trash
		    function move_to_trash($post_id){
		    	$post_type = get_post_type( $post_id );
		    	$post_status = get_post_status( $post_id );
		    	if($post_type == 'ajde_events' && in_array($post_status, array('publish','draft','future')) ){
		    		$woo_product_id = get_post_meta($post_id, 'tx_woocommerce_product_id', true);

		    		if(!empty($woo_product_id)){
		    			$__product = array(
		    				'ID'=>$woo_product_id,
		    				'post_status'=>'trash'
		    			);
		    			wp_update_post( $__product );
		    		}	
		    	}

		    }

	// TABS SETTINGS
		function evotx_tab_array($evcal_tabs){
			$evcal_tabs['evcal_tx']='Tickets';		
			return $evcal_tabs;
		}

		function evotx_tab_content(){
			global $eventon;

			$eventon->load_ajde_backender();
					
			?>
				<form method="post" action=""><?php settings_fields('evoau_field_group'); 
						wp_nonce_field( AJDE_EVCAL_BASENAME, 'evcal_noncename' );?>
				<div id="evcal_tx" class="evcal_admin_meta">	
					<div class="evo_inside">
					<?php

						$site_name = get_bloginfo('name');
						$site_email = get_bloginfo('admin_email');

						$cutomization_pg_array = array(
						
							array(
								'id'=>'evotx','display'=>'show',
								'name'=>'General Ticket Settings',
								'tab_name'=>'General',
								'fields'=>array(
									array('id'=>'evotx_loggedinuser','type'=>'yesno','name'=>'Show ticket purchase only for loggedin users',)
									,array('id'=>'evotx_tix_email','type'=>'yesno','name'=>'Stop sending ticket emails to buyers',),
									array('id'=>'evotx_tix_inquiries','type'=>'subheader','name'=>'Ticket Inquiries Settings',),
									array('id'=>'evotx_tix_inquiries_def_email','type'=>'text','name'=>'Default Email Address to <b>Receive</b> Ticket Inquiries. eg. YourName &#60;you@mail.com&#62;','default'=>get_option('admin_email'), ),
									array('id'=>'evotx_tix_inquiries_def_subject','type'=>'text','name'=>'Default Subject for Ticket Inquiries Email','default'=>'New Ticket Sale Inquery'),
									
							)),array(
								'id'=>'evotx2',
								'name'=>'Email Templates',
								'tab_name'=>'Emails','icon'=>'envelope',
								'fields'=>array(
									array('type'=>'subheader','name'=>'Event Ticket Email'),
									
									array('id'=>'evotx_notfiemailfromN','type'=>'text','name'=>'"From" Name','default'=>$site_name),
									array('id'=>'evotx_notfiemailfrom','type'=>'text','name'=>'"From" Email Address' ,'default'=>$site_email),
									
									array('id'=>'evotx_notfiesubjest','type'=>'text','name'=>'Email Subject line','default'=>'Event Ticket'),
									array('id'=>'evcal_fcx','type'=>'subheader','name'=>'HTML Template'),
									array('id'=>'evcal_fcx','type'=>'note','name'=>'To override and edit the email template copy "eventon-tickets/templates/ticket_confirmation_email.php" to  "yourtheme/eventon/templates/email/ticket_confirmation_email.php.'),
							)),
						);
						
						
									
						$eventon->load_ajde_backender();		
						
						$evcal_opt = get_option('evcal_options_evcal_tx');


						print_ajde_customization_form($cutomization_pg_array, $evcal_opt);
								
							
					?>
				</div>
				</div>
				<div class='evo_diag'>
					<input type="submit" class="evo_admin_btn btn_prime" value="<?php _e('Save Changes') ?>" /><br/><br/>
					<a target='_blank' href='http://www.myeventon.com/support/'><img src='<?php echo AJDE_EVCAL_URL;?>/assets/images/myeventon_resources.png'/></a>
				</div>
				
				</form>	
			<?php
		}

	// GET product type by product ID
		public function get_product_type($id){
			if ( $terms = wp_get_object_terms( $id, 'product_type' ) ) {
				$product_type = sanitize_title( current( $terms )->name );
			} else {
				$product_type = apply_filters( 'default_product_type', 'simple' );
			}
			return $product_type;
		}

	// other hooks
		function evotx_search_pre_get_posts($query){
		    // Verify that we are on the search page that that this came from the event search form
		    if($query->query_vars['s'] != '' && is_search())
		    {
		        // If "s" is a positive integer, assume post id search and change the search variables
		        if(absint($query->query_vars['s']))
		        {
		            // Set the post id value
		            $query->set('p', $query->query_vars['s']);

		            // Reset the search value
		            $query->set('s', '');
		        }
		    }
		}	
		function evotx_event_post_styles(){
			global $evotx;
			wp_enqueue_style( 'evotx_admin_post',$evotx->plugin_url.'/assets/admin_evotx_post.css');
			wp_enqueue_script( 'evotx_admin_post_script',$evotx->plugin_url.'/assets/tx_admin_post_script.js');
			wp_localize_script( 
				'evotx_admin_post_script', 
				'evotx_admin_ajax_script', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ) , 
					'postnonce' => wp_create_nonce( 'evotx_nonce' )
				)
			);
		}
		// add edit event button to WC product page
			function event_edit_button(){
				global $post;

				if ( function_exists( 'event_edit_button' ) ) return;

				if ( ! is_object( $post ) ) return;

				if ( $post->post_type != 'product' ) return;

				
				// if event ticket category set
					if(has_term('ticket','product_cat', $post)){
						$event_id = get_post_meta($post->ID,'_eventid', true );
						
						if(!empty($event_id)){
						?>
						<div class="misc-pub-section" >
							<div id="edit-event-action"><a class="button" href="<?php echo get_edit_post_link($event_id); ?>"><?php _e( 'Edit Event', 'eventon' ); ?></a></div>
							
						</div>
						<?php
						}	
					}
			}

	// event tickets to eventcard
		function evotx_add_toeventcard_order($array){
			$array['evotx']= array('evotx',__('Event Ticket Box','eventon')); 

			//print_r($array);
			return $array;
		}
	// even tticket eventcard icons
		function evotx_custom_icons($array){
			$array[] = array('id'=>'evcal__evotx_001','type'=>'icon','name'=>'Event Ticket Icon','default'=>'fa-tags');
			return $array;
		}	

	// Appearnace section
		function evotx_appearance_settings($array){
			
			$new[] = array('id'=>'evotx','type'=>'hiddensection_open','name'=>'Tickets Styles');
			$new[] = array('id'=>'evotx','type'=>'fontation','name'=>'Success Notification',
				'variations'=>array(
					array('id'=>'evotx_1', 'name'=>'Text Color','type'=>'color', 'default'=>'ffffff'),
					array('id'=>'evotx_2', 'name'=>'Background Color','type'=>'color', 'default'=>'abbba1'),
					array('id'=>'evotx_3', 'name'=>'Checkout button background color','type'=>'color', 'default'=>'237ebd'),
					array('id'=>'evotx_4', 'name'=>'Checkout button text Color','type'=>'color', 'default'=>'ffffff'),
					array('id'=>'evotx_5', 'name'=>'View Cart button background color','type'=>'color', 'default'=>'237ebd'),
					array('id'=>'evotx_6', 'name'=>'View Cart button text color','type'=>'color', 'default'=>'ffffff'),
				)
			);

			$new[] = array('id'=>'evotx','type'=>'hiddensection_close',);

			return array_merge($array, $new);
		}

		function evotx_dynamic_styles($_existen){
			$new= array(
				array(
					'item'=>'.evo_metarow_tix .tx_wc_notic',
					'css'=>'background-color:#$', 'var'=>'evotx_2',	'default'=>'abbba1'
				),array(
					'item'=>'#evcal_list .eventon_list_event .evo_metarow_tix .tx_wc_notic p',
					'css'=>'color:#$', 'var'=>'evotx_1',	'default'=>'ffffff'
				),array(
					'item'=>'#evcal_list .eventon_list_event .event_description .tx_wc_notic .evcal_btn.view_cart',
					'multicss'=>array(
						array('css'=>'background-color:#$', 'var'=>'evotx_5',	'default'=>'237ebd'),
						array('css'=>'color:#$', 'var'=>'evotx_6',	'default'=>'ffffff'),
					)
				),array(
					'item'=>'#evcal_list .eventon_list_event .event_description .tx_wc_notic .evcal_btn.checkout',
					'multicss'=>array(
						array('css'=>'background-color:#$', 'var'=>'evotx_3',	'default'=>'237ebd'),
						array('css'=>'color:#$', 'var'=>'evotx_4',	'default'=>'ffffff'),
					)
				)			
			);
			

			return (is_array($_existen))? array_merge($_existen, $new): $_existen;
		}

	// language settings additinos
		function evotx_language_additions($_existen){
			$new_ar = array(
				array('type'=>'togheader','name'=>'ADDON: Event Tickets'),
					array('label'=>'Ticket Status: Check-in','name'=>'evoTX_003x',),
					array('label'=>'Ticket Status: Checked','name'=>'evoTX_003y',),

					array('label'=>'Ticket section title', 'name'=>'evoTX_001', 'legend'=>''),	
					array('label'=>'Add to Cart', 'name'=>'evoTX_002', 'legend'=>''),
					array('label'=>'Order Now', 'name'=>'evoTX_002ee', 'legend'=>''),
					array('label'=>'Price', 'name'=>'evoTX_002ff', 'legend'=>''),

					array('label'=>'Successfully Added to Cart!', 'name'=>'evoTX_009', 'legend'=>''),
					array('label'=>'Checkout', 'name'=>'evoTX_010', ),
					array('label'=>'View Cart', 'name'=>'evoTX_011', ),
					array('label'=>'Sold Out!', 'name'=>'evoTX_012', 'legend'=>'Out of stock for tickets'),
					array('label'=>'Tickets remaining!', 'name'=>'evoTX_013',),
					array('label'=>'Your event Tickets', 'name'=>'evoTX_014',),


					array('label'=>'Ticket #', 'name'=>'evoTX_003', 'legend'=>''),
					array('label'=>'Primary Ticket Holder', 'name'=>'evoTX_004', 'legend'=>''),
					array('label'=>'Quantity', 'name'=>'evoTX_005', 'legend'=>''),
					array('label'=>'Event Time', 'name'=>'evoTX_005a', 'legend'=>''),
					array('label'=>'Ticket Type', 'name'=>'evoTX_006', 'legend'=>''),
					array('label'=>'We look forward to seeing you!', 'name'=>'evoTX_007', 'legend'=>''),
					array('label'=>'Contact us for questions and concerns', 'name'=>'evoTX_008', 'legend'=>''),


					array('label'=>'Ticket Inquiries Front-end Form','type'=>'subheader'),
						array('label'=>'Inquire before buy','name'=>'evoTX_inq_01','legend'=>''),
						array('label'=>'Your Name','name'=>'evoTX_inq_02','legend'=>''),
						array('label'=>'Email Address','name'=>'evoTX_inq_03','legend'=>''),
						array('label'=>'Question','name'=>'evoTX_inq_04','legend'=>''),
						array('label'=>'All Fields are Required','name'=>'evoTX_inq_05','legend'=>''),
						array('label'=>'Required Fields are Missing, Please Try Again!','name'=>'evoTX_inq_06','legend'=>''),
						array('label'=>'Submit','name'=>'evoTX_inq_07','legend'=>''),
						array('label'=>'GOT IT! -- We will get back to you as soon as we can.','name'=>'evoTX_inq_08','legend'=>''),
					
					array('type'=>'togend'),
					
				array('type'=>'togend'),
			);
			return (is_array($_existen))? array_merge($_existen, $new_ar): $_existen;
		}

	// add submenu for ticket orders only
		function order_tix(){
			// add submenu page
			add_submenu_page('woocommerce','Ticket Orders', 'Ticket Orders', 'manage_eventon','edit.php?post_type=shop_order&product_cat=ticket');
		}

		function get_format_time($unix){
			$evcal_opt1 = get_option('evcal_options_evcal_1');
			$date_format = eventon_get_timeNdate_format($evcal_opt1);

			$TIME = eventon_get_editevent_kaalaya($unix, $date_format[1], $date_format[2]);

			return $TIME;
		}
	
	
}

$GLOBALS['evotx_admin'] = new evotx_admin();



	
