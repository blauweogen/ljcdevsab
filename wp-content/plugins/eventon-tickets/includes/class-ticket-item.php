<?php
/**
 * 
 *	EventON Ticket Item class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon-tickets/Classes
 * @version     0.1
 */

class evotx_TicketItem{

	private $ticketItem_meta;
	private $ticketItem_id;
	
	function __construct($ticket_item_id, $ticketItem_meta=''){
		$this->ticketItem_id = $ticket_item_id;
		$this->ticketItem_meta = !empty($ticketItem_meta)? $ticketItem_meta: get_post_custom($this->ticketItem_id);
	}

	// get ticket ids with check status
		function ticket_ids($type='array'){
			$output = '';
			if(!empty($this->ticketItem_meta['ticket_ids']) ){
				$ticket_ids_arry = unserialize($this->ticketItem_meta['ticket_ids'][0]);

				if($type=='array'){
					return $ticket_ids_arry;
				}else{ // comma string
					$index =1;
					foreach( $ticket_ids_arry as $ff=>$vv){
						if(count($ticket_ids_arry)>0){
							$output.= ($index == count($ticket_ids_arry) )? $ff: $ff.', ';
						}else{	$output.= $ff;	}
						$index++;
					}
					return $output;
				}
				
			}else{ // ticket id does not exist
				
				if($type=='array'){
					$tids =   explode(',',$this->ticketItem_meta['tid'][0]);
					$output = array();
					foreach($tids as $ids){
						$output[$ids] = 'check-in'; 
					}

					// save the newly created ticket ids array for new version
					update_post_meta($this->ticketItem_id, 'ticket_ids',$output);
					return $output;
				}else{
					return  $this->ticketItem_meta['tid'][0];
				}				
			}
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
						$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $start).' '.date($time_format, $end);
					}					
				}else{
					$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $start).' '.date($time_format, $end);
				}
			}else{
				$output = date($date_format, $start). ' '.date($time_format, $start).' - '.date($date_format, $start).' '.date($time_format, $end);
			}
			return $output;
		}
			// get event date
			public function _event_date($event_id, $event_pmv=''){

				global $eventon;		
				
				$time = $this->get_correct_times($event_id, $event_pmv='');
				$evcal_lang_allday = eventon_get_custom_language( '','evcal_lang_allday', 'All Day');
					$event_post_meta = !empty($event_pmv)? $event_pmv: get_post_custom($event_id);
				$date_array = $eventon->evo_generator->generate_time_('','', $event_post_meta, $evcal_lang_allday,'','',$time['start'],$time['end']);
				
				return $date_array;
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


	// CHECK status related
		function checked_count(){
			$tixitem_pmv = $this->ticketItem_meta;

			$tix_arr = $this->get_unserialized_tix_array();
			if($tix_arr){
				$count = array_count_values($tix_arr);
				$count['checked'] = ( !empty($count['checked'] )? $count['checked'] : 0);
				$count['qty'] = !empty($tixitem_pmv['qty'])? $tixitem_pmv['qty'][0]:1;
				return $count; // Array ( [check-in] => 2 )
			}else{
				$status =  (!empty($tixitem_pmv['status']))? $tixitem_pmv['status'][0]: 'check-in';
				return array($status=>'1', 'qty'=>(!empty($tixitem_pmv['qty'])? $tixitem_pmv['qty'][0]:1) );
			}
		}

	// fix the ticket quantity for incorrectly saved ticket quantity values
		function fix_wrong_qty(){
			$tix_arr = $this->get_unserialized_tix_array();
			if($tix_arr){
				$qty = count($tix_arr);
				update_post_meta($this->ticketItem_id, 'qty', $qty);
			}
		}


		function change_ticket_status($new_status, $ticket_id, $ticket_item_id=''){
			$tixitem_pmv = $this->ticketItem_meta;

			// get ticket id array
			$ticket_ar = $this->get_unserialized_tix_array();
			if($ticket_ar){
				$new_ticket_ar = $ticket_ar;
				unset($new_ticket_ar[$ticket_id]);
				
				$new_ticket_ar[$ticket_id]= $new_status;
				update_post_meta($ticket_item_id, 'ticket_ids',$new_ticket_ar);
						
			}else{
				update_post_meta($ticket_item_id, 'status',$new_status);						
			}
		}

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
		function get_other_status($status=''){
			$new_status = ($status=='check-in')? 'checked':'check-in';
			$new_status_lang = $this->get_checkin_status($new_status);

			return array($new_status, $new_status_lang);
		}

		function get_ticket_status($ticket_id){
			if(!empty($this->ticketItem_meta['ticket_ids']) ){
				$ticket_ids_arry = unserialize($this->ticketItem_meta['ticket_ids'][0]);
				if(array_key_exists($ticket_id, $ticket_ids_arry)){
					return $ticket_ids_arry[$ticket_id];
				}else{
					return 'check-in';
				}
			}else{
				$ticketItem_status = $this->ticketItem_meta['status'];
				return (!empty($ticketItem_status))? $ticketItem_status[0]: 'check-in';
			}
		}


	function get_unserialized_tix_array(){
		if(!empty($this->ticketItem_meta['ticket_ids']) ){
			return unserialize($this->ticketItem_meta['ticket_ids'][0]);
		}else{
			return false;
		}
	}

}