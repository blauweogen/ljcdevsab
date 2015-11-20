<?php
/**
 * Ticket Confirmation email Template
 * @version 2.0
 *
 * To customize: copy this file to your theme folder as below path
 * path: your-theme-dir/eventon/templates/email/tickets/
 */

	global $eventon, $evotx;

	// $args are passed to this page
	// These are required on this template page to get correct ticket values
		
		$email = $args[1];
		$args = $args[0];
		
		$evo_options = get_option('evcal_options_evcal_1');
		$evo_options_2 = get_option('evcal_options_evcal_2');

		$tix__ = new evotx_ticket();

	// inline styles
		$__styles_01 = "font-size:30px; color:#303030; font-weight:bold; text-transform:uppercase; margin-bottom:0px;  margin-top:0;";
		$__styles_02 = "font-size:18px; color:#303030; font-weight:normal; text-transform:uppercase; display:block; font-style:italic; margin: 4px 0; line-height:110%";
		$__sty_lh = "line-height:110%;";
		$__styles_02a = "color:#afafaf; text-transform:none";
		$__styles_03 = "color:#afafaf; font-style:italic;font-size:14px; margin:0 0 10px 0";
		$__styles_04 = "color:#303030; text-transform:uppercase; font-size:18px; font-style:italic; padding-bottom:0px; margin-bottom:0px; line-height:110%;";
		$__styles_05 = "padding-bottom:40px; ";
		$__styles_06 = "border-bottom:1px dashed #d1d1d1; padding:5px 20px";
		$__sty_td ="padding:0px;border:none";
		$__sty_pt20 ="padding-top:20px;";
		$__sty_m0 ="margin:0px;";

		$__styles_button = "font-size:14px; background-color:#".( !empty($evo_options['evcal_gen_btn_bgc'])? $evo_options['evcal_gen_btn_bgc']: "237ebd")."; color:#".( !empty($evo_options['evcal_gen_btn_fc'])? $evo_options['evcal_gen_btn_fc']: "ffffff")."; padding: 5px 10px; text-decoration:none; border-radius:4px;";

?>

<table width='100%' style='width:100%; margin:0'>
<?php 
$count = 1;
	
if(empty($args['tickets'])) return;

// order items as ticket items - run through each
foreach($args['tickets'] as $ticket_item):

	// initiate ticket order item class
		$torderItem = new evotx_ticket_orderitem($ticket_item, $args['orderid']);

		$event_id = $torderItem->event_id();
		$e_pmv = get_post_custom($event_id);

	// verify only ticket items are in this email
		if(empty($event_id)) continue;
	
	// ticket item ID		
		$ticket_item_id = $torderItem->the_ticket_item_id;

	// location data
		$location = (!empty($e_pmv['evcal_location_name'])? $e_pmv['evcal_location_name'][0].' ': null).(!empty($e_pmv['evcal_location'])? $e_pmv['evcal_location'][0]:null);

	// event time
		$ticketItem = new evotx_TicketItem($ticket_item_id);
		$__date = $ticketItem->_event_date($event_id , $e_pmv);
		$time = $ticketItem->get_correct_times($event_id , $e_pmv);
		$event_time = $ticketItem->get_event_time($event_id, $time['start'],$time['end']);

?>
	<tr>
		<td style='<?php echo $__sty_td;?>'>
			
			<div class='event_date' style='<?php echo $__styles_06;?>'>
				<p style='padding-top:10px;color:#555555; text-transform:uppercase; font-size:12px; margin:0px; line-height:100%'><?php echo date('D',$time['start']) ;?></p>
				<p style='margin:0px; text-transform:uppercase; font-size:20px;<?php echo $__sty_lh;?>'><?php echo $__date['html_date'];?></p>
				<p style='margin:0px; padding-bottom:10px; font-style:italic; color:#838383;<?php echo $__sty_lh;?>'><?php echo $__date['html_fromto'];?></p>
			</div>
			<div style="padding:20px; font-family:'open sans'">
				<p style='<?php echo $__styles_01.$__sty_lh;?>'><?php echo $ticket_item['name'];?></p>
				<p style='<?php echo $__styles_02;?>'><span style='<?php echo $__styles_02a;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_004', 'Primary ticket holder');?>:</span> <?php echo $args['customer'];?></p>
				<!-- quantity-->
				<p style='<?php echo $__styles_02;?>'><span style='<?php echo $__styles_02a;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_005', 'Quantity');?>:</span> <?php echo $ticket_item['qty'];?></p>
				<!-- Event Time-->
					<p style='<?php echo $__styles_04.$__sty_pt20;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_005a', 'Event Time');?></p>
					<p style='<?php echo $__styles_03;?>'><?php echo $event_time;?></p>
				
				
				<?php if(!empty($ticket_item['variation_id'])):
					$_product = new WC_Product_Variation($ticket_item['variation_id'] );
        			$hh= $_product->get_variation_attributes( );

        				foreach($hh as $f=>$v):
        					if(empty($v)) continue;
					?>
						<p style='<?php echo $__styles_03;?>'><span style='<?php echo $__styles_02a;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_006', 'Type');?>:</span> <?php echo $v;?></p>
					<?php endforeach; endif;?>

				<!-- location -->
					<?php if(!empty($location)):?>
						<p style='<?php echo $__styles_04;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evcal_lang_location', 'Location');?></p>
						<p style='<?php echo $__styles_03;?>'><?php echo $location;?></p>
					<?php endif;?>
				
				<!-- add to calendar -->
				<p style='margin:0px; <?php echo (empty($location))? $__sty_pt20:null;?><?php echo (count($args['tickets'])>1)? "margin-bottom:30px":null;?>'><a style='<?php echo $__styles_button;?>' href='<?php echo admin_url();?>admin-ajax.php?action=eventon_ics_download&event_id=<?php echo $event_id;?>&sunix=<?php echo $e_pmv['evcal_srow'][0];?>&eunix=<?php echo $e_pmv['evcal_erow'][0];?>' target='_blank'><?php echo eventon_get_custom_language( $evo_options_2,'evcal_evcard_addics', 'Add to calendar');?></a></p>

				<?php do_action('evotx_ticket_template_end', $event_id, $ticket_item);?>
			</div>
			<div style=''>
				<p style='<?php echo $__styles_02;?>; padding-left:20px'><span style='<?php echo $__styles_02a;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_003', 'Ticket(s) #');?></span></p>
				<?php 

					$ticketids = $torderItem->ticket_ids();
					if(is_array($ticketids)):
				?>
				<?php foreach($ticketids as $ff=>$vv):?>
					<p style='<?php echo $__styles_02;?>; border-top:1px dashed #d1d1d1; padding:8px 20px 5px;'><?php echo apply_filters('evotx_email_tixid_list', $ff);?></p>
				<?php endforeach;

					else:
						?> <p style='<?php echo $__styles_02;?>; border-top:1px dashed #d1d1d1; padding:8px 20px 5px;'><?php echo apply_filters('evotx_email_tixid_list', $ticketids);?></p><?php
					endif;
				?>

			</div>
		</td>
	</tr>
<?php endforeach;?>
<?php if($email):?>
	<tr>
		<td  style='padding:20px; text-align:left;border-top:1px dashed #d1d1d1; font-style:italic; color:#ADADAD'>
			<?php
				$__link = (!empty($evo_options['evors_contact_link']))? $evo_options['evors_contact_link']:site_url();
			?>
			<p style='<?php echo $__sty_lh.$__sty_m0;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_007', 'We look forward to seeing you!')?></p>
			<p style='<?php echo $__sty_lh.$__sty_m0;?>'><a style='' href='<?php echo $__link;?>'><?php echo eventon_get_custom_language( $evo_options_2,'evoTX_008', 'Contact Us for questions and concerns')?></a></p>
		</td>
	</tr>
<?php endif;?>
</table>