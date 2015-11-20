<?php
/**
 * Event Ticket Custom Post class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventON/Admin/evo-tix
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evotx_tix{	
	// Constructor
		function __construct(){
			add_filter( 'request', array($this,'ticket_order') );

			add_filter( 'manage_edit-evo-tix_sortable_columns', array($this,'ticket_sort') );
			add_action('manage_evo-tix_posts_custom_column', array($this,'evo_tx_custom_event_columns'), 2 );
			add_filter( 'manage_edit-evo-tix_columns', array($this,'evo_tx_edit_event_columns') );
			add_action("admin_init", array($this,"_evo_tx_remove_box"));


			// Just to make clear how the filters work
			    $posttype = "shop_order";

			    // Priority 20, with 1 parameter (the 1 here is optional)
			    add_filter( "manage_edit-{$posttype}_columns", array($this, 'column_set_so_22237380'), 20, 1 ); 

			    // Priority 20, with 2 parameters
			    add_action( "manage_{$posttype}_posts_custom_column", array($this, 'column_display_so_22237380'), 20, 2 ); 

			    // Default priority, default parameters (zero or one)
			    add_filter( "manage_edit-{$posttype}_sortable_columns", array($this, 'column_sort_so_22237380') ); 

		}

	// add order type columns
		function column_set_so_22237380( $columns ){
		    $columns['order_type'] = "Type";
		    return $columns;
		}
		function column_display_so_22237380( $column_name, $post_id ) {
		    if ( 'order_type' != $column_name )
		        return;

		    $order_type_ = get_post_meta($post_id, '_order_type', true);

		    $order_type = (!empty($order_type_) && $order_type_== 'evotix')? 'Ticket Order':'None Ticket Order';

		    if ( $order_type ){
		    	//print_r($order_type_);
		        echo $order_type;
		    }
		}
		function column_sort_so_22237380( $columns ) {
		    $columns['order_type'] = 'order_type';
		    return $columns;
		}

	function _evo_tx_remove_box(){
		remove_post_type_support('evo-tix', 'title');
		remove_post_type_support('evo-tix', 'editor');
	}

	/**
	 * Define custom columns for evo-tix
	 * @param  array $existing_columns
	 * @return array
	 */
		function evo_tx_edit_event_columns( $existing_columns ) {
			global $eventon;
			
			// GET event type custom names
			
			if ( empty( $existing_columns ) && ! is_array( $existing_columns ) )
				$existing_columns = array();
			if($_GET['post_type']!='evo-tix')
				return;

			unset( $existing_columns['title'], $existing_columns['comments'], $existing_columns['date'] );

			$columns = array();
			$columns["cb"] = "<input type=\"checkbox\" />";	

			$columns['tix'] = __( 'Ticket', 'eventon' );
			$columns['tix_status'] = __( 'Status', 'eventon' );
			//$columns["qty"] = __( 'Quantity', 'eventon' );
			$columns["date"] = __( 'Date', 'eventon' );
			$columns["tix_event"] = __( 'Event', 'eventon' );
			$columns["tix_type"] = __( 'Ticket Type', 'eventon' );				
			

			return array_merge( $columns, $existing_columns );
		}
		

	// field values
		function evo_tx_custom_event_columns( $column ) {
			global $post, $eventon, $evotx;

			$meta = get_post_meta($post->ID); // ticket item meta
			$ticket_item = new evotx_TicketItem($post->ID, $meta);

			switch ($column) {		
				case "tix":
					$tix_pmv = get_post_custom($post->ID);

					$edit_link = get_edit_post_link( $post->ID );
					$tid = (!empty($meta['tid']))? $meta['tid'][0]: null;
					$qty = get_post_meta($post->ID, 'qty', true);
					$cost = get_post_meta($post->ID, 'cost', true);			

					echo "<strong><a class='row-title' href='".$edit_link."'>#{$post->ID}</a></strong> by ".$meta['name'][0]." ".$meta['email'][0];

					// get ticket ids
					$tix_id_ar = $ticket_item->ticket_ids('string');

					echo (!empty($tid))? '<br/><em class="lite">Ticket ID(s):</em> <i>'.$tix_id_ar.'</i>':null;

					//$ticket_item->fix_wrong_qty();

					echo '<br/><span class="evotx_intrim">'.$qty.' <em class="lite">(Qty)</em> - '. ((!empty($cost))? get_woocommerce_currency_symbol().apply_filters('woocommerce_get_price', $cost): '-').'<em class="lite"> (Total)</em></span>';
					//echo get_post_meta($post->ID, 'tix', true);
				break;
				case "tix_event":
					$e_id = (!empty($meta['_eventid']))? $meta['_eventid'][0]: null;

					if($e_id){
						echo '<strong><a class="row-title" href="'.get_edit_post_link( $e_id ).'">' . get_the_title($e_id).'</a></strong>';
					}else{ echo '--';}

				break;
				case "tix_type":
					$type = get_post_meta($post->ID, 'type', true);						
					echo (!empty($type))? $type: '-';

				break;
				
				case "tix_status":

					$checked_count = $ticket_item->checked_count();
					$status = 'checked';

					$checked_count_ = !empty($checked_count['checked'])? $checked_count['checked']:'0';
					
					// if all checked 
						$_checked_class = ($checked_count_ == $checked_count['qty'])? 'checked':'check-in';

					// different state on checked tickets
						if($checked_count['qty'] == '1' && $checked_count_=='0' ){
							$display = $ticket_item->get_checkin_status('check-in');
						}elseif(($checked_count['qty'] == '1' && $checked_count_=='1')|| ($checked_count['qty']>1 && $checked_count['qty'] == $checked_count_)){
							$display = $ticket_item->get_checkin_status('checked');
						}else{
							$display = $ticket_item->get_checkin_status($status).' '.$checked_count_.'/'.$checked_count['qty'];
						}


					// order
						$_orderid = get_post_meta($post->ID, '_orderid', true);	
						if(!empty($_orderid)){	
							$order = new WC_Order( $_orderid );
							$order_status = $order->status;
						}else{ $order_status = 'n/a';}

					echo "<p class='evotx_status_list {$order_status}'><em class='lite'>Order:</em> ".$order_status ."</p>";

					echo "<p class='evotx_status_list {$_checked_class}'><em class='lite'>Ticket:</em> <span class='tixstatus'>".$display."</span></p>";		

				break;
			}
		}
		


	// make ticket columns sortable
		function ticket_sort($columns) {
			$custom = array(
				'event'		=> 'event',
			);
			return wp_parse_args( $custom, $columns );
		}
		function ticket_order( $vars ) {
			if (isset( $vars['orderby'] )) :
				if ( 'event' == $vars['orderby'] ) :
					$vars = array_merge( $vars, array(
						'meta_key' 	=> '_eventid',
						'orderby' 	=> 'meta_value'
					) );
				endif;
				
			endif;

			return $vars;
		}
}
new evotx_tix();
