<?php
/**
 * RSVP frontend supporting functions
 */
class evorsvp_functions{
	private $UMV = 'eventon_rsvp_user';

	// loggedin  user
		function add_user_meta($uid, $eid, $ri, $rsvp){
			$user_data = get_option($this->UMV);
			if(!empty($user_data) && !is_array($rsvp)){
				$user_data[$uid][$eid][$ri] = $rsvp;
				update_option($this->UMV, $user_data);
			}else{
				$user_data[$uid][$eid][$ri] = $rsvp;
				add_option($this->UMV, $user_data);
			}			
		}
		// GET user rsvp status by user id
			function get_user_rsvp_status($uid, $eid, $ri='0'){
				$user_data = get_option($this->UMV);
				//print_r($user_data);
				if(!empty($user_data)){
					return !empty($user_data[$uid][$eid][$ri])? $user_data[$uid][$eid][$ri]: false;
				}else{
					return false;
				}
			}
			function current_user_id(){
				return get_current_user_id();
			}
			function get_userloggedin_user_rsvp_status($eid, $ri=0){
				if(is_user_logged_in()){					
					return $this->get_user_rsvp_status($this->current_user_id(),$eid, $ri );
				}else{return false;}
			}
			function trash_user_rsvp($uid, $eid, $ri='0'){
				if($uid){
					$user_data = get_option($this->UMV);
					if(empty($user_data)) return;

					if(empty($user_data[$uid][$eid][$ri])) return;

					unset($user_data[$uid][$eid][$ri]);
					update_option($this->UMV, $user_data);
				}
			}
		// CHECK if user rsvped already
			function has_user_rsvped($post){
				$rsvped = new WP_Query( array(
					'posts_per_page'=>-1,
					'post_type' => 'evo-rsvp',
					'meta_query' => array(
						array('key' => 'email','value' => $post['email']),
						array('key' => 'e_id','value' => $post['e_id']),
						array('key' => 'repeat_interval','value' => $post['repeat_interval']),
					),
				));
				return ($rsvped->have_posts())? true: false;
			}
			function has_loggedin_user_rsvped(){
				$rsvped = new WP_Query( array(
					'posts_per_page'=>-1,
					'post_type' => 'evo-rsvp',
					'meta_query' => array(
						array('key' => 'e_id','value' => $post['e_id']),
						array('key' => 'repeat_interval','value' => $post['repeat_interval']),
						array('key' => 'userid','value' => $post['uid']),
					),
				));

				$rsvp = false;
				if($rsvped->have_posts() && $rsvped->found_posts==1){
					while($rsvped->have_posts()): $rsvped->the_post();
						$rsvp = get_post_meta($rsvped->post->ID, 'rsvp',true);
					endwhile;
				}
				wp_reset_postdata();

				return $rsvp;
			}

	// related to RSVP manager
		function get_user_events($userid){
			global $eventon_rs;

			$rsvps = new WP_Query(array(
				'posts_per_page'=>-1,
				'post_type' => 'evo-rsvp',
				'meta_query' => array(
					array('key' => 'userid','value' => $userid)
				),
				'meta_key'=>'last_name',
				'orderby'=>'post_date'
			));
			$userRSVP = array();
			ob_start();
			if($rsvps->have_posts()):					

				$datetime = new evo_datetime();
				$format = get_option('date_format');
				$currentTime = current_time('timestamp');

				while( $rsvps->have_posts() ): $rsvps->the_post();
					$_id = get_the_ID();
					$pmv = get_post_meta($_id);
					$checkin_status = (!empty($pmv['status']))? $pmv['status'][0]:'check-in'; // checkin status
					$e_id = (!empty($pmv['e_id']))? $pmv['e_id'][0]:false;

					if(!$e_id) continue;

					$epmv = get_post_custom($e_id);

					$rsvp = (!empty($pmv['rsvp'])?  $eventon_rs->frontend->get_rsvp_status($pmv['rsvp'][0]):'');
					$RI = (!empty($pmv['repeat_interval'])?$pmv['repeat_interval'][0]:'');

					$time = $datetime->get_correct_event_repeat_time($epmv, $RI, $format);
					$link = get_permalink($e_id);
					$link = $link.( strpos($link, '?')?'&ri='.$RI:'?ri='.$RI);

					$remaining_rsvp = $this->remaining_rsvp($epmv, $RI, $e_id);

					$p_classes = array();
					$p_classes[] = $time['start']>=$currentTime?'':'pastevent';

					echo "<p class='".(count($p_classes)>0? implode(' ', $p_classes):'')."'>RSVP ID: <b>#".$_id."</b> <span class='rsvpstatus'>{$rsvp}</span>
						<em class='checkin_status'>".$checkin_status."</em><br/>
						<em class='count'>".(!empty($pmv['count'])? $pmv['count'][0]:'-')."</em>
						<em class='event_data'>EVENT: <a href='".$link."'>".get_the_title($e_id)."</a> <br/>TIME: ".date($format.' h:i:a',$time['start'])." - ".date($format.' h:i:a',$time['end'])."</em>
						";
					echo ($time['start']>=$currentTime)? 
						"<span class='action' data-cap='".(is_int($remaining_rsvp)? $remaining_rsvp:'na')."' data-etitle='".get_the_title($e_id)."' data-precap='".$this->is_per_rsvp_max_set($epmv)."' data-uid='{$userid}' data-rsvpid='{$_id}' data-eid='{$e_id}' data-ri='{$RI}' ><a class='update_rsvp' data-val='chu'>UPDATE</a></span>":'';
					echo "</p>";
					
				endwhile;
			endif;
			wp_reset_postdata();
			return ob_get_clean();
		}

	// RSVP post related
		// RETURN: remaining RSVP
		function remaining_rsvp($event_pmv, $ri = 0, $event_id=''){
			// get already RSVP-ed count
			$yes = (!empty($event_pmv['_rsvp_yes']))? $event_pmv['_rsvp_yes'][0]:0;
			$maybe = (!empty($event_pmv['_rsvp_maybe']))? $event_pmv['_rsvp_maybe'][0]:0;

			// if capacity limit set for rsvp 
			if(!empty($event_pmv['evors_capacity']) && $event_pmv['evors_capacity'][0]=='yes'){
				// if capacity calculated per each repeat instance
				if($this->is_ri_count_active($event_pmv)){		
					$ri_capacity = unserialize($event_pmv['ri_capacity_rs'][0]);			
					$ri_count = !empty($event_pmv['ri_count_rs'])? unserialize($event_pmv['ri_count_rs'][0]):null;	

					// if count not saved
					if(empty($ri_count)){
						$this->update_ri_count($event_id, $ri, 'y', $yes);
						$this->update_ri_count($event_id, $ri, 'm', $maybe);
					}	
					$count = (!empty($ri_count))? (!empty($ri_count[$ri]['y'])? $ri_count[$ri]['y']:0)+
						(!empty($ri_count[$ri]['m'])? $ri_count[$ri]['m']:0)
						:($yes+$maybe);

					return $ri_capacity[$ri] - $count;
				}elseif(
					// not 
					!empty($event_pmv['evors_capacity_count'])
				){
					$capacity = (int)$event_pmv['evors_capacity_count'][0];
					$remaining =  $capacity - ( $yes + $maybe);
					return ($remaining>0)? $remaining: false;
				}elseif($event_pmv['evors_capacity'][0]=='no'){
					return true;
				}
			}else{
			// set capacity limit is NOT set
				return true;
			}
		}
		// CHECK FUNCTIONs remaining RSVP
			function show_spots_remaining($event_pmv){
				return (!empty($event_pmv['evors_capacity_count'])
					&& !empty($event_pmv['evors_capacity_show'])
					&& $event_pmv['evors_capacity_show'][0] == 'yes'
					&& !empty($event_pmv['evors_capacity']) && $event_pmv['evors_capacity'][0]=='yes'
				)? true:false;
			}
			function show_whoscoming($event_pmv){
				return (!empty($event_pmv['evors_show_whos_coming'])
					&& $event_pmv['evors_show_whos_coming'][0] == 'yes')? true:false;
			}
			// check if repeat interval rsvp is activate
			function is_ri_count_active($event_pmv){
				 return (
					!empty($event_pmv['evors_capacity']) && $event_pmv['evors_capacity'][0]=='yes'
					&& !empty($event_pmv['_manage_repeat_cap_rs']) && $event_pmv['_manage_repeat_cap_rs'][0]=='yes'
					&& !empty($event_pmv['evcal_repeat']) && $event_pmv['evcal_repeat'][0] == 'yes' 
					&& !empty($event_pmv['ri_capacity_rs']) 
				)? true:false;
			}

		// GET RSVP attendee list as ARRAY
			function GET_rsvp_list($eventID, $ri=''){
				
				$event_pmv = get_post_custom($eventID);
				$ri_count_active = $this->is_ri_count_active($event_pmv);
				$guestsAR = array('y'=>array(),'m'=>array(),'n'=>array());

				$guests = new WP_Query(array(
					'posts_per_page'=>-1,
					'post_type' => 'evo-rsvp',
					'meta_query' => array(
						array('key' => 'e_id','value' => $eventID)
					),
					'meta_key'=>'last_name',
					'orderby'=>'meta_value'
				));
				if($guests->have_posts()):
					while( $guests->have_posts() ): $guests->the_post();
						$_id = get_the_ID();
						$pmv = get_post_meta($_id);
						$_status = (!empty($pmv['status']))? $pmv['status'][0]:'check-in';
						$rsvp = (!empty($pmv['rsvp']))? $pmv['rsvp'][0]:false;
						$e_id = (!empty($pmv['e_id']))? $pmv['e_id'][0]:false;

						if(!$rsvp) continue;
						if(!$e_id || $e_id!=$eventID) continue;

						if(
							(
								$ri_count_active && 
								((!empty($pmv['repeat_interval']) && $pmv['repeat_interval'][0]==$ri)
									|| ( empty($pmv['repeat_interval']) && $ri==0)
								)
							)
							|| !$ri_count_active 
							|| $ri=='all'
						){
							$guestsAR[$rsvp][$_id] = array(
								'fname'=> $pmv['first_name'][0],
								'lname'=> $pmv['last_name'][0],
								'name'=> $pmv['last_name'][0].', '.$pmv['first_name'][0],
								'email'=> $pmv['email'][0],
								'phone'=> (!empty($pmv['phone'])?$pmv['phone'][0]:''),
								'status'=>$_status,
								'count'=>$pmv['count'][0],						
							);
						}

					endwhile;
				endif;
				wp_reset_postdata();
				return array('y'=>$guestsAR['y'], 'm'=>$guestsAR['m'], 'n'=>$guestsAR['n']);
			}

		// GET repeat interval RSVP count
			function get_ri_count($rsvp, $ri=0, $event_pmv=''){
				$ri_count = (!empty($event_pmv) && !empty($event_pmv['ri_count_rs']))? 
					unserialize($event_pmv['ri_count_rs'][0]):false;
				if(!$ri_count) return 0;
				return !empty($ri_count[$ri][$rsvp])? $ri_count[$ri][$rsvp]:0;
			}
		// GET rsvp (remaining) count RI or not
			function get_rsvp_count($event_pmv, $rsvp, $ri=0){
				if($this->is_ri_count_active($event_pmv)){
					return $this->get_ri_count($rsvp, $ri, $event_pmv);
				}else{
					global $eventon_rs;
					return !empty($event_pmv['_rsvp_'.$eventon_rs->rsvp_array[$rsvp]])? 
						$event_pmv['_rsvp_'.$eventon_rs->rsvp_array[$rsvp]][0]:0;
				}				
			}
			function get_ri_remaining_count($rsvp, $ri=0, $ricount, $eventpmv){
				$openCount = (int)$this->get_ri_count($rsvp, $ri, $eventpmv);
				return $ricount - $openCount;
			}
			// GET rsvp count for given rsvp type
			function get_event_rsvp_count($event_id, $rsvp_type, $event_pmv=''){
				$event_pmv = (!empty($event_pmv))? $event_pmv: get_post_meta($event_id);
				return (!empty($event_pmv['_rsvp_'.$rsvp_type]))? $event_pmv['_rsvp_'.$rsvp_type][0]:'0';
			}
		
		// UPDATE repeat interval RSVP count
		// val = y,n
			function update_ri_count($event_id, $ri, $val, $count){
				$ri_count = get_post_meta($event_id, 'ri_count_rs', true);
				$ri_count = !empty($ri_count)? $ri_count: false;
				$ri_count[$ri][$val] = $count;
				update_post_meta($event_id, 'ri_count_rs', $ri_count);
			}
			public function _form_update_rsvp($post){
				// update each fields
				foreach($post as $field=>$value){
					update_post_meta($post['rsvpid'], $field, $value);
				}
				// update usermeta
				if(isset($post['userid']) && isset($post['e_id'])){
					$this->add_user_meta($post['userid'], $post['e_id'], $post['repeat_interval'], $post['rsvp']);
				}
				// sync rsvp count after update
				$this->sync_rsvp_count($post['e_id']);
				return true;
			}

		// find a RSVP
			public function find_rsvp($rsvpid, $fname, $eid){
				$rsvp = get_post($rsvpid);
				if($rsvp){
					$rsvp_meta = get_post_custom($rsvpid);

					// check if first name and event id
					return ($fname == $rsvp_meta['first_name'][0] && $eid == $rsvp_meta['e_id'][0])? array('rsvp'=>$rsvp_meta['rsvp'][0], 'count'=>$rsvp_meta['count'][0]): false;
				}else{ return false;}
			}
		
		// SYNC rsvp status for an event
			function sync_rsvp_count($event_id){
				global $wpdb, $eventon_rs;

				// check if repeat interval RSVP active
				$event_pmv = get_post_custom($event_id);
				$is_ri_count_active = $this->is_ri_count_active($event_pmv);

				$ri_count = array();
				$rsvp_count = array('y'=>0,'n'=>0,'m'=>0);

				$evoRSVP = new WP_Query( array(
					'posts_per_page'=>-1,
					'post_type' => 'evo-rsvp',
					'meta_query' => array(
						array('key' => 'e_id','value' => $event_id,)
					)
				));
				if($evoRSVP->found_posts>0){
					while($evoRSVP->have_posts()): $evoRSVP->the_post();
						$rsvpPMV = get_post_custom($evoRSVP->post->ID);

						$rsvp = !empty($rsvpPMV['rsvp'])? $rsvpPMV['rsvp'][0]:false;
						$count = !empty($rsvpPMV['count'])? (int)$rsvpPMV['count'][0]:0;
						$ri = !empty($rsvpPMV['repeat_interval'])? $rsvpPMV['repeat_interval'][0]:0;

						$rsvp_count[$rsvp] = !empty($rsvp_count[$rsvp])? $rsvp_count[$rsvp]+$count: $count;

						if($is_ri_count_active){
							$ri_count[$ri][$rsvp] = !empty($ri_count[$ri][$rsvp])? $ri_count[$ri][$rsvp]+$count: $count;
						}

					endwhile;

					if(!empty($rsvp_count['y'])) update_post_meta($event_id,'_rsvp_yes', $rsvp_count['y'] );
					update_post_meta($event_id,'_rsvp_no', $rsvp_count['n'] );
					update_post_meta($event_id,'_rsvp_maybe', $rsvp_count['m'] );

					if(!empty($ri_count))
						update_post_meta($event_id,'ri_count_rs', $ri_count );

				}else{// no rsvps found
					update_post_meta($event_id,'_rsvp_yes', $rsvp_count['y'] );
					update_post_meta($event_id,'_rsvp_no', $rsvp_count['n'] );
					update_post_meta($event_id,'_rsvp_maybe', $rsvp_count['m'] );
				}
				wp_reset_postdata();
				/*
					// run through each rsvp status value
					foreach( $eventon_rs->frontend->rsvp_array as $rsvp=>$rsvpf){
						$ids = array();
						
						$_status = new WP_Query( array(
							'posts_per_page'=>-1,
							'post_type' => 'evo-rsvp',
							'meta_query' => array(
								'relation' => 'AND',
								array('key' => 'rsvp','value' => $rsvp,),
								array('key' => 'e_id','value' => $event_id,)
							)
						));

						if($_status->found_posts>0):
							while($_status->have_posts()): $_status->the_post();
								$rsvpPMV = get_post_custom($_status->post->ID);

								$ids[]= get_the_ID();
							endwhile;
							$idList = implode(",", $ids);		
							$count = $wpdb->get_var($wpdb->prepare("
								SELECT sum(meta_value)
								FROM $wpdb->postmeta
								WHERE meta_key = %s
								AND post_id in (".$idList.")", 'count'
								));
							$count = (!empty($count))?$count :0;
						else:
							$count =  0;
						endif;					
						update_post_meta($event_id,'_rsvp_'.$rsvpf, $count );
						wp_reset_postdata();
					}
				*/				
			}

	// Supporting
	// get IP address of user
		function get_client_ip() {
		    $ipaddress = '';
		    if ($_SERVER['HTTP_CLIENT_IP'])
		        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    else if($_SERVER['HTTP_X_FORWARDED'])
		        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		    else if($_SERVER['HTTP_FORWARDED_FOR'])
		        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		    else if($_SERVER['HTTP_FORWARDED'])
		        $ipaddress = $_SERVER['HTTP_FORWARDED'];
		    else if($_SERVER['REMOTE_ADDR'])
		        $ipaddress = $_SERVER['REMOTE_ADDR'];
		    else
		        $ipaddress = false;
		    return $ipaddress;
		}
		function get_current_userid(){
			if(is_user_logged_in()){
				global $current_user;
				get_currentuserinfo();
				return $current_user->ID;
			}else{
				return false;
			}
		}
		// check if per rsvp count max set and return the max value
		function is_per_rsvp_max_set($event_pmv){
			return (!empty($event_pmv['evors_max_active']) && $event_pmv['evors_max_active'][0]=='yes' && !empty($event_pmv['evors_max_count'])) ? $event_pmv['evors_max_count'][0]: 'na';
		}
}