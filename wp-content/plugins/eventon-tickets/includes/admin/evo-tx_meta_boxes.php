<?php
/**
 * Ticket meta boxes for event page
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin/evo-tix
 * @version     0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Init the meta boxes. */
	function evotx_meta_boxes(){
		global $post;
		add_meta_box('evotx_mb1', __('Event Tickets','eventon'), 'evotx_metabox_content','ajde_events', 'normal', 'high');			
		add_meta_box('evo_mb1',__('Event Ticket','eventon'), 'evotx_metabox_002','evo-tix', 'normal', 'high');
		
		if($post->post_type=='shop_order'){
			$order_type = get_post_meta($post->ID, '_order_type', true);
			if($order_type)
				add_meta_box('evotx_mb1','Event Tickets', 'evotx_metabox_003','shop_order', 'side', 'default');
		}
		
		add_meta_box('evotx_mb2',__('Event Ticket Confirmation','eventon'), 'evoTX_notifications_box','evo-tix', 'side', 'default');

		do_action('evotx_add_meta_boxes');	
	}
	add_action( 'add_meta_boxes', 'evotx_meta_boxes' );
	add_action('eventon_save_meta',  'evotx_save_ticket_info', 10, 2);

// META box for WC order post shop_order
	function evotx_metabox_003(){
		global $post;	
		
		if(!empty($_GET['tixemail']) && $_GET['tixemail']=='resend'){
			global $evotx;
			$send_email = $evotx->send_ticket_email($post->ID);

			echo ($send_email)?'Email Sent Successfully!':'Email Sent Failed!, try again later.';
			echo "<br/><br/>";
		}
				
		?>
		<a class='button' href="<?php echo admin_url();?>post.php?post=<?php echo $post->ID;?>&action=edit&tixemail=resend"><?php _e('Resend Ticket Email','eventon');?></a>
		<?php
	}

// META BOX for ticket item post type CPT = evo-tix
	function evotx_metabox_002(){
		global $post, $evotx;
		$ticketItem_meta = get_post_meta($post->ID);	

		$ticket_item = new evotx_TicketItem($post->ID, $ticketItem_meta);
		$tix = new evotx_ticket();

		// Debug email templates
			$show_debug_email = false;
			if($show_debug_email):
				
				$order_id = $ticketItem_meta['_orderid'][0];
				$order = new WC_Order( $order_id);
				$tickets = $order->get_items();

				$email_body_arguments = array(
					'orderid'=>$order_id,
					'tickets'=>$tickets, 
					'customer'=>'Ashan Jay',
					'email'=>'yes'
				);
				$tt = $tix->get_ticket_email_body($email_body_arguments);
				print_r($tt);
			endif;

		// get event times
			$event_id = !empty($ticketItem_meta['_eventid'])? $ticketItem_meta['_eventid'][0]:'';	
			$event_time = $ticket_item->get_event_time($event_id);

			// get corrected event ticket ids
				if(empty($ticketItem_meta['ticket_ids'][0])){
					$tix->correct_tix_ids($ticketItem_meta, $post->ID);					
				}
		?>	
		<div class='eventon_mb' style='margin:-6px -12px -12px'>
		<div style='background-color:#ECECEC; padding:15px;'>
			<div style='background-color:#fff; border-radius:8px;'>
			<table width='100%' class='evo_metatable' cellspacing="" style='vertical-align:top' valign='top'>
				<tr><td><?php _e('Woocommerce Order ID','eventon');?> #: </td><td><?php echo '<a class="button" href="'.get_edit_post_link($ticketItem_meta['_orderid'][0]).'">'.$ticketItem_meta['_orderid'][0].'</a>';?></td></tr>				
				<tr><td><?php _e('Ticket Type','eventon');?>: </td><td><?php echo (!($ticketItem_meta['type'])? $ticketItem_meta['type'][0]:'--');?></td></tr>

				<?php
					$ticket_holder = (!empty($ticketItem_meta['_customerid'][0]) && $ticketItem_meta['_customerid'][0] != '0')? '<a href="'.get_edit_user_link($ticketItem_meta['_customerid'][0]).'">'.$ticketItem_meta['name'][0].'</a>': $ticketItem_meta['name'][0];
				?>
				<tr><td><?php _e('Ticket Holder','eventon');?>: </td><td><?php echo $ticket_holder;?></td></tr>
				<tr><td><?php _e('Email Address','eventon');?>: </td><td><?php echo $ticketItem_meta['email'][0];?></td></tr>
				<tr><td><?php _e('Quantity','eventon');?>: </td><td><?php echo $ticketItem_meta['qty'][0];?></td></tr>
				<tr><td><?php _e('Cost for ticket(s)','eventon');?>: </td><td><?php echo get_woocommerce_currency_symbol().$ticketItem_meta['cost'][0];?></td></tr>
				<tr><td><?php _e('Event','eventon');?>: </td>
				<td><?php echo '<a href="'.get_edit_post_link($event_id).'">'.get_the_title($ticketItem_meta['_eventid'][0]).'</a>';?>

					<?php
						// if this is a repeat event show repeat information
						$event_meta = get_post_meta($event_id);
						if(!empty($event_meta['evcal_repeat']) && $event_meta['evcal_repeat'][0]=='yes'){
							echo "<p>This is a repeating event.</p>";
						}

					?>
				</td></tr>
				<?php
				// get translated checkin status
					$st_count = $ticket_item->checked_count();
					$status = $ticket_item->get_checkin_status('checked');

					$__count = ': '.(!empty($st_count['checked'])? $st_count['checked']:'0').' out of '.$ticketItem_meta['qty'][0];
				?>
				<tr><td><?php _e('Ticket Status','eventon');?>: </td><td><?php echo $status.$__count; ?></td></tr>
				<tr><td><?php _e('Ticket Time','eventon');?>: </td><td><?php echo $event_time;?></td></tr>
				<tr><td ><?php _e('Ticket(s)','eventon');?> #: </td><td>
					<?php 
						// get ticket IDs for this ticket item
						$ticketids = $ticket_item->ticket_ids();
					?>
					<table id='evotx_ticketItem_tickets'>
						<?php 
							if(is_array($ticketids)):
								foreach($ticketids as $ff=>$vv):?>
								<tr><td><?php echo  apply_filters('evotx_tixPost_tixid', $ff);?><br/><span class='tix_status <?php echo $vv;?>' data-tiid='<?php echo $post->ID;?>' data-tid='<?php echo $ff;?>' data-status='<?php echo $vv;?>'><?php echo $ticket_item->get_checkin_status($vv);?></span></td></tr>
						<?php endforeach;
							else:
								echo "<tr><td>{$ticketids}</td></tr>";
							endif;
						?>
					</table>

				</td></tr>
				<?php
					do_action('eventontx_tix_post_table',$post->ID, $ticketItem_meta);
				?>
			</table>
			</div>
		</div>
		</div>
		<?php
	}

// notification email box
	function evoTX_notifications_box(){
		global $post;
		?>
		<div class='evoTX_resend_conf'>
			<div class='evoTX_rc_in'>
				<p><i><?php _e('You can re-send the Event Ticket confirmation email to customer if they have not received it. Make sure to check spam folder.','eventon');?></i></p>
				<a id='evoTX_resend_email' class='button' data-tiid='<?php echo $post->ID;?>'><?php _e('Re-send Confirmation Email','eventon');?></a>
				<p class='message' style='display:none'><?php _e('Email resend action performed!','eventon');?></p>
			</div>
		</div>

		<div class='evotix_resend_ticket'>
			<?php global $post;	
			
			if(!empty($_GET['tixemail']) && $_GET['tixemail']=='resend'){
				global $evotx;

				$order_id = get_post_meta($post->ID, '_orderid', true);
				$send_email = $evotx->send_ticket_email($order_id);

				echo "<br/>";
				echo ($send_email)?'Ticket Email Sent Successfully!':'Ticket Email Failed!, try again later.';
				echo "<br/>";
			}					
			?>
			<br/><a class='button' href="<?php echo admin_url();?>post.php?post=<?php echo $post->ID;?>&action=edit&tixemail=resend"><?php _e('Resend Ticket Email','eventon');?></a>
		</div>

		<?php
	}

/**  Event META BOX for ajde_events CPT */	
	function evotx_metabox_content(){
		global $post, $evotx, $eventon, $evotx_admin;
		$woometa='';

		$fmeta = get_post_meta($post->ID);
		$woo_product = (!empty($fmeta['tx_woocommerce_product_id']))? $fmeta['tx_woocommerce_product_id'][0]:null;

		// get options
		$evoOpt = get_evoOPT_array(1);

		// if woocommerce ticket has been created
		if($woo_product){
			$woometa =  get_post_custom($woo_product);
		}
		$__woo_currencySYM = get_woocommerce_currency_symbol();

		//print_r($woometa);

		ob_start();

		$evotx_tix = (!empty($fmeta['evotx_tix']))? $fmeta['evotx_tix'][0]:null;
		
		?>
		<div class='eventon_mb'>
		<div class="evotx">
			<input type='hidden' name='tx_woocommerce_product_id' value="<?php echo evo_meta($fmeta, 'tx_woocommerce_product_id');?>"/>

			<p class='yesno_leg_line ' style='padding:10px'>
				<?php echo eventon_html_yesnobtn(array('id'=>'evotx_activate','var'=>$evotx_tix, 
					'attr'=>array('afterstatement'=>'evotx_details'))); ?>				
				<input type='hidden' name='evotx_tix' value="<?php echo ($evotx_tix=='yes')?'yes':'no';?>"/>
				<label for='evotx_tix'><?php _e('Activate tickets for this Event','eventon'); echo $eventon->throw_guide('You can allow ticket selling via Woocommerce for this event in here.','',false); ?></label>
			</p>
			<div id='evotx_details' class='evotx_details evomb_body ' <?php echo ( $evotx_tix=='yes')? null:'style="display:none"'; ?>>
				
				
				<div class="evotx_tickets" >
				
					<h4><?php _e('Ticket Info for this event','eventon');?></h4>
					<table width='100%' border='0' cellspacing='0'>
						<?php
							$product_type = 'simple';

							// product type
							if($woo_product && function_exists('get_product')):
								$product = new WC_Product( $woo_product );

								$product_type = $evotx_admin->get_product_type($woo_product);
								$product_type = (!empty($product_type))? $product_type: 'simple';
						?>
							<tr><td><?php _e('Ticket Pricing Type','eventon');?></td><td><?php echo  $product_type;?></td></tr>
						<?php endif;?>

						<input type='hidden' name='tx_product_type' value='<?php echo $product_type;?>'/>

						<!-- Price-->
						<?php if(!empty($product_type) && $product_type=='variable'):?>
							<tr><td><?php printf( __('Ticket price (%s)','eventon'), $__woo_currencySYM);?></td><td><p><?php echo $__woo_currencySYM.' '.evo_meta($woometa, '_min_variation_price').' - '.evo_meta($woometa, '_max_variation_price');?></p>
							<p class='marb20'><a href='<?php echo get_edit_post_link($woo_product);?>' style='color:#fff'><?php _e('Edit Price Variations')?></a></p></td></tr>				
							
						<?php else:?>
							<!-- Regular Price-->
							<tr><td><?php printf( __('Ticket price (%s)','eventon'), $__woo_currencySYM);?></td><td><input type='text' id='_regular_price' name='_regular_price' value="<?php echo evo_meta($woometa, '_regular_price');?>"/></td></tr>

							<!-- Sale Price-->
							<tr><td><?php printf( __('Sale price (%s)','eventon'), $__woo_currencySYM);?></td><td><input type='text' id='_sale_price' name='_sale_price' value="<?php echo evo_meta($woometa, '_sale_price');?>"/></td></tr>
						<?php endif;?>

						<?php do_action('evotx_edit_event_ticket_tablerow', $post->ID, $woo_product);?>								


						<!-- SKU-->
						<tr><td><?php _e('Ticket SKU', 'eventon'); echo $eventon->throw_guide('SKU refers to a Stock-keeping unit, a unique identifier for each distinct menu item that can be ordered.','',false);?></td><td><input type='text' name='_sku' value='<?php echo evo_meta($woometa, '_sku');?>'/></td></tr>

						<!-- Desc-->
						<tr><td><?php _e('Short Ticket Detail', 'eventon'); ?></td><td><input type='text' name='_tx_desc' value='<?php echo evo_meta($woometa, '_tx_desc');?>'/></td></tr>
						
						<!-- manage capacity -->
							<?php
								$_manage_cap = evo_meta_yesno($woometa,'_manage_stock','yes','yes','no' );
							?>
							<tr><td colspan='2'>
								<p class='yesno_leg_line ' >
									<?php echo eventon_html_yesnobtn(array('id'=>'evotx_mcap',
									'var'=>$_manage_cap, 'attr'=>array('afterstatement'=>'exotc_cap'))); ?>
									<input type='hidden' name='_manage_stock' value="<?php echo $_manage_cap;?>"/>
									<label for='_manage_stock'><?php _e('Manage Capacity')?></label>
								</p>
							</td></tr>
							
							<tbody id='exotc_cap' style='display:<?php echo evo_meta_yesno($woometa,'_manage_stock','yes','','none' );?>'>
							<tr ><td><?php _e('CAPACITY','eventon');?></td><td><input type='text' id="_stock" name="_stock" value="<?php echo evo_meta($woometa, '_stock');?>"/></td></tr>
						
						<!-- stock status -->
							<?php
								$_stock_status = ( !empty($woometa['_stock_status']) && $woometa['_stock_status'][0]=='outofstock')? 'outofstock':'instock';
								$_stock_status_yesno = ( !empty($woometa['_stock_status']) && $woometa['_stock_status'][0]=='outofstock')? 'yes':'no';
							?>
							<tr><td colspan='2'>
								<p class='yesno_leg_line '>
									<?php echo eventon_html_yesnobtn(array('id'=>'evotx_mcap',
									'var'=>$_stock_status_yesno, 'no'=>'no')); ?>
									<input type='hidden' name='_stock_status' value="<?php echo $_stock_status;?>"/>
									<label for='_stock_status'><?php _e('Make ticket out of stock', 'eventon'); echo $eventon->throw_guide('Set stock status of tickets. Setting this to yes would make tickets not available for sale anymore','',false)?></label>
								</p>
							</td></tr>
			
						<!-- Manage Capcity seperate for repeating events -->
							<?php

								if(!empty($fmeta['evcal_repeat']) && $fmeta['evcal_repeat'][0]=='yes' && $product_type=='simple'):
								$manage_repeat_cap = evo_meta_yesno($fmeta,'_manage_repeat_cap','yes','yes','no' );

							?>
							<tr><td colspan='2'>
								<p class='yesno_leg_line ' >
									<?php echo eventon_html_yesnobtn(array('id'=>'evotx_mcap',
									'var'=>$manage_repeat_cap, 'attr'=>array('afterstatement'=>'evotx_ri_cap'))); ?>
									<input type='hidden' name='_manage_repeat_cap' value="<?php echo $manage_repeat_cap;?>"/>

									<label for='_manage_repeat_cap'><?php _e('Manage capacity seperate for each repeating event'); echo $eventon->throw_guide('This will show remaining tickets for this event on front-end','',false)?></label>
								</p>
							<?php
								$repeat_intervals = !empty($fmeta['repeat_intervals'])? unserialize($fmeta['repeat_intervals'][0]): false;
							?>
								<div id='evotx_ri_cap' class='evotx_repeat_capacity' style='padding-top:15px; padding-bottom:20px;display:<?php echo evo_meta_yesno($fmeta,'_manage_repeat_cap','yes','','none' );?>'>
									<p><em style='opacity:0.6'><?php _e('NOTE: The capacity above should match the total number of capacity for each repeat occurance below for this event.','eventon');?></em></p>
									<?php
										// if repeat intervals set 
										if($repeat_intervals && count($repeat_intervals)>0){
											$count =0;

											// get saved capacities for repeats
											$ri_capacity = !empty($fmeta['ri_capacity'])?
												unserialize($fmeta['ri_capacity'][0]): false;

											//print_r($ri_capacity);
											//print_r($repeat_intervals);

											echo "<div class='evotx_ri_cap_inputs'>";
											// for each repeat interval
											foreach($repeat_intervals as $interval){
												$TIME  = $evotx_admin->get_format_time($interval[0]);

												echo "<p style='display:" . ( ($count>4)?'none':'block') . "'><input type='text' name='ri_capacity[]' value='". (($ri_capacity && !empty($ri_capacity[$count]))? $ri_capacity[$count]:'0') . "'/> " . $TIME[0] . "</p>";
												$count++;
											}

											echo "</div>";

											echo (count($repeat_intervals)>5)? 
												"<p class='evotx_ri_view_more'><a class='button_evo'>Click here</a> to view the rest of repeat occurances.</p>":null;
										}
									?>
								</div>
							</td></tr>
							<?php endif;?>

						<!-- show remaining -->
							<?php
								$remain_tix = evo_meta_yesno($fmeta,'_show_remain_tix','yes','yes','no' );
							?>
							<tr><td colspan='2'>
								<p class='yesno_leg_line ' >
									<?php echo eventon_html_yesnobtn(array('id'=>'evotx_mcap',
									'var'=>$remain_tix, 'attr'=>array('afterstatement'=>'evotx_showre_count'))); ?>
									<input type='hidden' name='_show_remain_tix' value="<?php echo $remain_tix;?>"/>
									<label for='_show_remain_tix'><?php _e('Show remaining tickets'); echo $eventon->throw_guide('This will show remaining tickets for this event on front-end','',false)?></label>
								</p>
							</td></tr>
							<tr id='evotx_showre_count' style='display:<?php echo evo_meta_yesno($fmeta,'_show_remain_tix','yes','','none' );?>'><td><?php _e('Show remaining count at','eventon'); echo $eventon->throw_guide('Show remaining count when remaining count go below this number.','',false);?></td><td><input type='text' id="remaining_count" name="remaining_count" placeholder='20' value="<?php echo evo_meta($fmeta, 'remaining_count');?>"/></td></tr>	
							</tbody>
							
						<!-- sold individually -->
							<?php
								$_sold_ind = evo_meta_yesno($woometa,'_sold_individually','yes','yes','no' );
							?>
							<tr><td colspan='2'>
								<p class='yesno_leg_line ' >
									<?php echo eventon_html_yesnobtn(array('id'=>'evotx_mcap','var'=>$_sold_ind,)); ?>				
									<input type='hidden' name="_sold_individually" value="<?php echo $_sold_ind;?>"/>
									<label for='_sold_individually'><?php _e('Sold Individually', 'eventon'); echo $eventon->throw_guide('Enable this to only allow one ticket per person','',false)?></label>
								</p>
							</td></tr>	

						<!-- Field details-->
							<tr><td style='padding:5px 25px;' colspan='2'><?php _e('Ticket Field description', 'eventon'); echo $eventon->throw_guide('Use this to type instruction text that will appear above add to cart section on calendar.','',false);?><br/><input style='width:100%; margin-top:5px'type='text' name='_tx_text' value='<?php echo evo_meta($woometa, '_tx_text');?>'/></td></tr>

						<!-- ticket image -->
							<?php
								// tix_image_id
								$_tix_image_id = (!empty($fmeta['_tix_image_id'])? 
									$fmeta['_tix_image_id'][0]:false);
								// image soruce array
								$img_src = ($_tix_image_id)? 
									wp_get_attachment_image_src($_tix_image_id,'medium'): null;
								$tix_img_src = (!empty($img_src))? $img_src[0]: null;

								// button texts & Class names
									$__button_text = (!empty($_tix_image_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
									$__button_text_not = (empty($_tix_image_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
									$__button_class = (!empty($_tix_image_id))? 'removeimg':'chooseimg';
							?>
							<tr><td style='padding:5px 25px;' colspan='2'>
								<div class='evo_metafield_image' style='padding-top:10px'>
									<p >
										<label style='padding-bottom:5px; display:inline-block'><?php _e('Ticket Image','eventon');?></label><br/>
										<span style=''></span>
										<input id='_tix_image_id' class='custom_upload_image evo_meta_img' name="_tix_image_id" type="hidden" value="<?php echo ($_tix_image_id)? $_tix_image_id: null;?>" /> 
			                    		<input class="custom_upload_image_button button <?php echo $__button_class;?>" data-txt='<?php echo $__button_text_not;?>' type="button" value="<?php echo $__button_text;?>" /><br/>
			                    		<span class='evo_loc_image_src image_src'>
			                    			<img src='<?php echo $tix_img_src;?>' style='<?php echo !empty($_tix_image_id)?'':'display:none';?>'/>
			                    		</span>		                    		
			                    	</p>
			                    	<?php _e('Ticket Image Caption', 'eventon'); echo $eventon->throw_guide('Caption text that will appear under ticket image.','',false);?><br/><input style='width:100%; margin-top:5px'type='text' name='_tx_img_text' value='<?php echo evo_meta($fmeta, '_tx_img_text');?>'/>
			                    </div>
							</td></tr>

						<?php
						// inquire before buying
							$_allow_inquire = evo_meta_yesno($fmeta,'_allow_inquire','yes','yes','no' );
							$_tx_inq_subject = (!empty($fmeta['_tx_inq_subject']))? $fmeta['_tx_inq_subject'][0]: 
								( !empty($evoOpt['evotx_tix_inquiries_def_subject'])? $evoOpt['evotx_tix_inquiries_def_subject']: 'New Ticket Sale Inquery');
							$_tx_inq_email = (!empty($fmeta['_tx_inq_email']))? $fmeta['_tx_inq_email'][0]: 
								( !empty($evoOpt['evotx_tix_inquiries_def_email'])? $evoOpt['evotx_tix_inquiries_def_email']: get_option('admin_email') );
						?>
						<tr><td colspan='2'>
							<p class='yesno_leg_line ' >
								<?php echo eventon_html_yesnobtn(array('id'=>'evotx_showinq',
								'var'=>$_allow_inquire, 'attr'=>array('afterstatement'=>'evotx_show_inq'))); ?>
								<input type='hidden' name='_allow_inquire' value="<?php echo $_allow_inquire;?>"/>
								<label for='_allow_inquire'><?php _e('Allow customers to submit inquiries.'); echo $eventon->throw_guide('With this customers can submit inquiries via this form before buying tickets on front-end.','',false)?></label>
							</p>
						</td></tr>
						<tr id='evotx_show_inq' style='display:<?php echo evo_meta_yesno($fmeta,'_allow_inquire','yes','','none' );?>'><td colspan='2'><?php _e('Override Default Email Address to receive Inquiries', 'eventon'); ?><br/>
							<input style='width:100%; margin-top:5px'type='text' name='_tx_inq_email' placeholder='<?php echo $_tx_inq_email;?>' value='<?php echo $_tx_inq_email;?>'/>
							<?php _e('Override Default Subject for Inquiries Email', 'eventon'); ?><br/>
							<input style='width:100%; margin-top:5px'type='text' name='_tx_inq_subject' placeholder='<?php echo $_tx_inq_subject;?>' value='<?php echo evo_meta($fmeta, '_tx_inq_subject');?>'/>
							<p style='padding-top:5px;opacity:0.6'><i><?php _e('NOTE: Front-end fields for Inquiries form can be customized from','eventon');?> <a style='color:#B3DDEC' href='<?php echo admin_url();?>admin.php?page=eventon&tab=evcal_2'>EventON Languages</a></i></p>
						</td></tr>	
					</table>
					<?php
						// DOWNLOAD CSV link 
						$exportURL = add_query_arg(array(
						    'action' => 'the_ajax_evotx_a3',
						    'e_id' => $post->ID,
						    'pid'=> $woo_product
						), admin_url('admin-ajax.php'));
					?>

					<?php if($woo_product):?>
						<p class='actions'><a class='button_evo edit' href='<?php echo get_edit_post_link($woo_product);?>'  title='<?php _e('Further Edit','eventon');?>'></a></p>
					<?php endif;?>
					<div class='clear'></div>		
				</div>	

				<?php echo $eventon->output_eventon_pop_window(array('class'=>'evotx_lightbox', 'content'=>'Loading...', 'title'=>'Event Attendees', 'type'=>'padded', 'max_height'=>'500' ));?>

				<!-- Attendee section -->
					<?php if(!empty($woometa['total_sales']) && $woometa['total_sales']>0):?>
					<div class='evoTX_metabox_attendee_other'>
						<p><?php _e('Attendees for event','eventon');?></p>
						<p class="actions">
							<a id='evotx_attendees' data-eid='<?php echo $post->ID;?>' data-wcid='<?php echo evo_meta($fmeta, 'tx_woocommerce_product_id');?>' data-popc='evotx_lightbox' class='button_evo attendees ajde_popup_trig' title='<?php _e('View Attendees','eventon');?>'><?php _e('View Attendees','eventon');?></a><a class='button_evo download' href="<?php echo $exportURL;?>"><?php _e('Download (CSV)','eventon');?></a>
						</p>

					</div>
					<?php endif;?>
			</div>			
		</div>
		</div>

		<?php
		echo ob_get_clean();
	}


// save new ticket and create matching WC product
	function evotx_save_ticket_info($arr, $post_id){			

		global $evotx_admin;

		// if allowing woocommerce ticketing
		if(!empty($_POST['evotx_tix']) && $_POST['evotx_tix']=='yes'){
			// check if woocommerce product id exist
			if(!empty($_POST['tx_woocommerce_product_id'])){

				$post_exists = $evotx_admin->post_exist($_POST['tx_woocommerce_product_id']);					
				// add new
				if(!$post_exists){
					$evotx_admin->add_new_woocommerce_product($post_id);
				}else{
					$evotx_admin->update_woocommerce_product($_POST['tx_woocommerce_product_id'], $post_id);
				}	
			// if there isnt a woo product associated to this - add new one
			}else{
				$evotx_admin->add_new_woocommerce_product($post_id);
			}
		}

		foreach(array(
			'_tx_img_text',
			'evotx_tix', 
			'_show_remain_tix', 
			'remaining_count', 
			'_manage_repeat_cap', 
			'_tix_image_id', 
			'_allow_inquire',
			'_tx_inq_email',
			'_tx_inq_subject',
		) as $variable){
			if(!empty($_POST[$variable])){
				update_post_meta( $post_id, $variable,$_POST[$variable]);
			}elseif(empty($_POST[$variable])){
				delete_post_meta($post_id, $variable);
			}
		}

		// repeat interval capacities
		if(!empty($_POST['ri_capacity']) && $_POST['_manage_repeat_cap']=='yes'){

			// get total
			$count = 0; 
			foreach($_POST['ri_capacity'] as $cap){
				$count = $count + $cap;
			}
			// update product capacity
			update_post_meta( $_POST['tx_woocommerce_product_id'], '_stock',$count);
			update_post_meta( $post_id, 'ri_capacity',$_POST['ri_capacity']);
		}
	}