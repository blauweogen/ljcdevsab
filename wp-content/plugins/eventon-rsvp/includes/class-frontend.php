<?php
/**
 * 
 * eventon rsvp front end class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon-rsvp/classes
 * @version     0.1
 */
class evors_front{
	public $rsvp_array = array('y'=>'yes','m'=>'maybe','n'=>'no');
	public $rsvp_array_ = array('y'=>'Yes','m'=>'Maybe','n'=>'No');
	public $evors_args;
	public $optRS;

	private $currentlang;

	function __construct(){
		global $eventon_rs;

		include_once('class-functions.php');
		$this->functions = new evorsvp_functions();

		add_filter('eventon_eventCard_evorsvp', array($this, 'frontend_box'), 10, 2);
		add_filter('eventon_eventcard_array', array($this, 'eventcard_array'), 10, 4);
		add_filter('evo_eventcard_adds', array($this, 'eventcard_adds'), 10, 1);

		// event top inclusion
		add_filter('eventon_eventtop_one', array($this, 'eventop'), 10, 3);
		add_filter('evo_eventtop_adds', array($this, 'eventtop_adds'), 10, 1);
		add_filter('eventon_eventtop_evors', array($this, 'eventtop_content'), 10, 2);			
		//add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 10 );	
		// scripts and styles 
		add_action( 'init', array( $this, 'register_styles_scripts' ) ,15);	

		$this->optRS = $eventon_rs->evors_opt;
		$this->opt2 = $eventon_rs->opt2;

		// add rsvp form HTML to footer
		add_action('wp_footer', array($this, 'footer_content'));
	}

	//	STYLES: for the tab page 
		public function register_styles_scripts(){
			global $eventon_rs;
			
			wp_register_style( 'evo_RS_styles',$eventon_rs->plugin_url.'/assets/RS_styles.css');
			wp_register_script('evo_RS_script',$eventon_rs->plugin_url.'/assets/RS_script.js', array('jquery'), $eventon_rs->version, true );
			
			$this->print_scripts();
			add_action( 'wp_enqueue_scripts', array($this,'print_styles' ));				
		}
		public function print_scripts(){
			wp_enqueue_script('evo_RS_ease');	
			//wp_enqueue_script('evo_RS_mobile');	
			wp_enqueue_script('evo_RS_script');	
		}
		function print_styles(){
			wp_enqueue_style( 'evo_RS_styles');	
		}

	// EVENTTOP inclusion
		public function eventop($array, $pmv, $vals){
			$array['evors'] = array(	'vals'=>$vals,	);
			return $array;
		}		
		function eventtop_adds($array){
			$array[] = 'evors';
			return $array;
		}
		public function eventtop_content($object, $helpers){
			$output = '';
			
			if($object->vals['fields_'] && in_array('rsvp_options',$object->vals['fields']) ){

				$emeta = get_post_custom($object->vals['eventid']);

				// if tickets and enabled for the event
				if( !empty($emeta['evors_rsvp']) && $emeta['evors_rsvp'][0]=='yes'
					&& !empty($emeta['evors_show_rsvp']) && $emeta['evors_show_rsvp'][0]=='yes'				
				){
					global $eventon;
					$lang = (!empty($eventon->evo_generator->shortcode_args['lang'])? $eventon->evo_generator->shortcode_args['lang']:'L1');

					// correct language text for based on count coming to event
					$lang_str = array(
						'0'=>'Be the first to RSVP',
						'1'=>'One guest is going',
						'2'=>'guests are going',
					);

					$yes_count = $this->functions->get_rsvp_count($emeta, 'y', $object->vals['ri']);
					$__count_lang = ($yes_count==1)?
						evo_lang($lang_str['1'], $lang):
						($yes_count>1? evo_lang($lang_str['2'], $lang): 
							evo_lang($lang_str['0'], $lang));

					// logged-in user RSVPing with one click
						$existing_rsvp_status = false;
						if(!empty($this->optRS['evors_eventop_rsvp']) && $this->optRS['evors_eventop_rsvp']=='yes'){				
							if(is_user_logged_in()){
								global $current_user;
								get_currentuserinfo();
								$existing_rsvp_status = $this->functions->get_user_rsvp_status($current_user->ID, $object->vals['eventid'], $object->vals['ri']);
								// if loggedin user have not rsvp-ed yet
								if(!$existing_rsvp_status){
									$TEXT = eventon_get_custom_language($this->opt2, 'evoRSL_001','RSVP to event', $lang);
									$output .=  "<span class='evors_eventtop_rsvp' data-eid='{$object->vals['eventid']}' data-ri='{$object->vals['ri']}'data-uid='{$current_user->ID}' data-lang='{$lang}'>".$TEXT. $this->get_rsvp_choices($this->opt2, $this->optRS)."</span>";
								}else{
								// user has rsvp-ed already
									$TEXT = evo_lang('You have already RSVP-ed', $lang);
									$output .="<span class='evors_eventtop_rsvp'>{$TEXT}: <em class='evors_rsvped_status_user'>".$this->rsvp_array_[$existing_rsvp_status]."</em></span>";
								}
							}							
						}					
					$output .= "<span class='evors_eventtop_data'><em>".($yes_count>1?$yes_count.' ':'').$__count_lang."</em></span>";
				}	
				return $output;
			}else{
				return false;
			}
		}

	// RSVP EVENTCARD form HTML
		// add RSVP box to front end
			function frontend_box($object, $helpers){
				global $eventon_rs;
				$event_pmv = get_post_custom($object->event_id);

				// loggedin user
					$currentUserID = 	$this->functions->current_user_id();	
					$currentUserRSVP = $this->functions->get_userloggedin_user_rsvp_status($object->event_id, $object->__repeatInterval);	

				// RSVP enabled for this event
					if(empty($event_pmv['evors_rsvp']) || (!empty($event_pmv['evors_rsvp']) && $event_pmv['evors_rsvp'][0]=='no') ) return;

				$lang = (!empty($eventon->evo_generator->shortcode_args['lang'])? $eventon->evo_generator->shortcode_args['lang']:'L1');
				
				$optRS = $this->optRS;
				$is_user_logged_in = is_user_logged_in();

				// if only loggedin users can see rsvp form
					if( evo_settings_val('evors_onlylogu', $optRS) && !$is_user_logged_in){
						return $this->rsvp_for_none_loggedin($helpers, $object);
						return;			
					}

				// if close rsvp is set and check time
					$close_time = evo_meta($event_pmv, 'evors_close_time');
					if( !empty( $close_time) ){
						
						date_default_timezone_set('UTC');
						$time = time()+(get_option('gmt_offset', 0) * 3600);
						$close_t = (int)($close_time)*60;

						// adjust event end time for repeat intervals
						$row_endTime = $this->get_correct_event_end_time(
							$event_pmv, $object->__repeatInterval);
						$change_t = (int)$row_endTime - $close_t;

						if($change_t <= $time )	
							return;
					}				
				// show rsvp count
					if( evo_meta_yesno($event_pmv, 'evors_show_rsvp', 'yes', true, false) ){
						$countARR = array(
							'y' => (' ('.$this->functions->get_rsvp_count($event_pmv,'y',$object->__repeatInterval).')'),
							'n' => (' ('.$this->functions->get_rsvp_count($event_pmv,'n',$object->__repeatInterval).')'),
							'm' => (' ('.$this->functions->get_rsvp_count($event_pmv,'m',$object->__repeatInterval).')'),
						);
					}else{	$countARR = array();}
				// get options array
					$opt = $helpers['evoOPT2'];
					$fields_options = 	(!empty($optRS['evors_ffields']))?$optRS['evors_ffields']:false;
				// change rsvp button
					$_txt_changersvp = eventon_get_custom_language($opt, 'evoRSL_005a','Change my RSVP');
					$changeRSVP = (!empty($optRS['evors_hide_change']) && $optRS['evors_hide_change']=='yes')?'': "<span class='change' data-val='ch'>".$_txt_changersvp."</span>";

				ob_start();

				$remaining_rsvp = $this->functions->remaining_rsvp($event_pmv, $object->__repeatInterval, $object->event_id);

				echo  "<div class='evorow evcal_evdata_row bordb evcal_evrow_sm evo_metarow_rsvp".$helpers['end_row_class']."' data-rsvp='' data-event_id='".$object->event_id."'>
							<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__evors_001', 'fa-envelope',$helpers['evOPT'] )."'></i></span>
							<div class='evcal_evdata_cell'>							
								<h3 class='evo_h3'>".eventon_get_custom_language($opt, 'evoRSL_001','RSVP to event')."</h3>";
						
						// RSVPing allowed and spaces left
						$eventtop_rsvp = (!empty($this->optRS['evors_eventop_rsvp']) && $this->optRS['evors_eventop_rsvp']=='yes')? true:false;

						//echo "<div class='evorsvp_eventcard_column'>";
						
						if(($remaining_rsvp==true || $remaining_rsvp >0) || $currentUserRSVP){
							echo "<div class='evoRS_status_option_selection' data-etitle='". get_the_title($object->event_id)."' data-eid='".$object->event_id."' data-ri='{$object->__repeatInterval}' data-cap='".(is_int($remaining_rsvp)? $remaining_rsvp:'na')."' data-precap='".$this->functions->is_per_rsvp_max_set($event_pmv)."'>";

							// if already RSVPED
							if($currentUserRSVP){
								echo "<p class='nobrbr loggedinuser' data-uid='{$currentUserID}' data-eid='{$object->event_id}' data-ri='{$object->__repeatInterval}'>";
								echo (!$eventtop_rsvp)? evo_lang('You have already RSVP-ed').": <em class='evors_rsvped_status_user'>".$this->rsvp_array_[$currentUserRSVP]."</em> ":'';

								$changeRSVP_ = (!empty($optRS['evors_hide_change']) && $optRS['evors_hide_change']=='yes')?'': "<span class='change' data-val='chu'>".$_txt_changersvp."</span>";

								echo $changeRSVP_."</p>";
							}else{
								// if user loggedin OR eventtop RSVP is disabled
								if(!$eventtop_rsvp || !is_user_logged_in())
									echo "<p>". $this->get_rsvp_choices($opt, $optRS, $countARR).$changeRSVP."</p>";
							}								
							echo "</div>";
						}		
								
						// spots remaining
							if($this->functions->show_spots_remaining($event_pmv)){
								// no more spaces
								if(!$remaining_rsvp){
									echo "<p class='remaining_count no_spots_left'>".eventon_get_custom_language($opt, 'evoRSL_002a','No more spots left!')."</p>";
								}else{
									echo "<p class='remaining_count'>".$remaining_rsvp  ." ".eventon_get_custom_language($opt, 'evoRSL_002b','Spots remaining')."</p>";
								}
							}
						// whos coming section
							if($this->functions->show_whoscoming($event_pmv)){
								$attendee_icons = $this->GET_attendees_icons($object->event_id, $object->__repeatInterval);
								if($attendee_icons)
									echo "<p class='evors_whos_coming_title'>".eventon_get_custom_language($opt, 'evoRSL_002a','Who is coming to the event')."</p><p class='evors_whos_coming'>". $attendee_icons."</p>";
							}
						// subtitle for rsvp section
							echo "<p class='evo_data_val'>";
							if(!$remaining_rsvp){
								echo eventon_get_custom_language($opt, 'evoRSL_002d',"RSVPing is closed at this time.");
							}else{
								if(!$currentUserRSVP)
									echo eventon_get_custom_language($opt, 'evoRSL_002','Make sure to RSVP to this amazing event!');
							}
							echo "</p>";
						// additional information to rsvped logged in user
							if(!empty($event_pmv['evors_additional_data']) && $currentUserRSVP){
								echo "<h3 class='evo_h3 additional_info'>".evo_lang('Additional Information', $lang, $opt)."</h3>";
								echo "<p class='evo_data_val'>".$event_pmv['evors_additional_data'][0]."</p>";
							}

						//echo "</div><div class='evorsvp_eventcard_column'>";
						//echo "</div>";
								
						echo "</div>".$helpers['end'];
						echo "</div>";
							

				return ob_get_clean();
			}
			// for not loggedin users
				function rsvp_for_none_loggedin($helpers, $object){
					global $eventon;
					$lang = (!empty($eventon->evo_generator->shortcode_args['lang'])? $eventon->evo_generator->shortcode_args['lang']:'L1');
					ob_start();
					echo  "<div class='evorow evcal_evdata_row bordb evcal_evrow_sm evo_metarow_rsvp".$helpers['end_row_class']."' data-rsvp='' data-event_id='".$object->event_id."'>
								<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__evors_001', 'fa-envelope',$helpers['evOPT'] )."'></i></span>
								<div class='evcal_evdata_cell'>							
									<h3 class='evo_h3'>".eventon_get_custom_language($helpers['evoOPT2'], 'evoRSL_001','RSVP to event')."</h3>";
							$txt_1 = evo_lang('You must login to RSVP for this event',$lang, $helpers['evoOPT2']);
							$txt_2 = evo_lang('Login Now',$lang, $helpers['evoOPT2']);
							echo "<p>{$txt_1} <a href='".wp_login_url(get_permalink())."' class='evcal_btn'>{$txt_2}</a></p>";
					echo "</div></div>";
					return ob_get_clean();
				}
		// footer of rsvp form
			function footer_content(){
				$optRS = $this->optRS;
				$active_fields =(!empty($optRS['evors_ffields']))?$optRS['evors_ffields']:false;
				include_once('html_form.php');
			}
		// save a cookie for RSVP
			function set_user_cookie($args){
				//$ip =$this->get_client_ip();
				$cookie_name = 'evors_'.$args['email'].'_'.$args['e_id'].'_'.$args['repeat_interval'];
				$cookie_value = 'rsvped';
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
			}
			function check_user_cookie($userid, $eventid){
				$cookie_name = 'evors_'.$eventid.'_'.$userid;
				if(!empty($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name]=='rsvped'){
					return true;
				}else{
					return false;
				}
			}
		// get form messages html
			function get_form_message($code='', $lang=''){
				$opt = $this->opt2;
				$array =  array(
					'err'=>eventon_get_custom_language($opt, 'evoRSL_013','Required fields missing',$lang),
					'err2'=>eventon_get_custom_language($opt, 'evoRSL_014','Invalid email address',$lang),
					'err3'=>eventon_get_custom_language($opt, 'evoRSL_015','Please select RSVP option',$lang),
					'err4'=>eventon_get_custom_language($opt, 'evoRSL_016','Could not update RSVP, please contact us.',$lang),
					'err5'=>eventon_get_custom_language($opt, 'evoRSL_017','Could not find RSVP, please try again.',$lang),
					'err6'=>eventon_get_custom_language($opt, 'evoRSL_017x','Invalid Validation code.',$lang),
					'err7'=>eventon_get_custom_language($opt, 'evoRSL_017y','Could not create a RSVP please try later.',$lang),
					'err8'=>eventon_get_custom_language($opt, 'evoRSL_017z1','You can only RSVP once for this event.',$lang),
					'err9'=>eventon_get_custom_language($opt, 'evoRSL_017z2','Your party size exceed available space.',$lang),
					'err10'=>eventon_get_custom_language($opt, 'evoRSL_017z3','Your party size exceed allowed space per RSVP.',$lang),
					'succ'=>eventon_get_custom_language($opt, 'evoRSL_018','Thank you for submitting your rsvp',$lang),
					'succ_n'=>eventon_get_custom_language($opt, 'evoRSL_019','Sorry to hear you are not going to make it to our event.',$lang),
					'succ_m'=>eventon_get_custom_language($opt, 'evoRSL_020','Thank you for updating your rsvp',$lang),
					'succ_c'=>eventon_get_custom_language($opt, 'evoRSL_021','Great! we found your RSVP!',$lang),
				);				
				return (!empty($code))? $array[$code]: $array;
			}
			function get_form_msg($opt){
				$str='';
				$ar = array('codes'=> $this->get_form_message());
				return "<div class='evors_msg_' style='display:none'>". json_encode($ar)."</div>";
			}
		// GET attendees icons
			function GET_attendees_icons($eventID, $ri){
				$list = $this->functions->GET_rsvp_list($eventID, $ri);
				$output = false;

				if(!empty($list['y'])){
					foreach($list['y'] as $feild=>$value){
						//$gravatar_link = 'http://www.gravatar.com/avatar/' . md5($value['email']) . '?s=32';
						$initials = substr($value['fname'], 0, 1).substr($value['lname'], 0, 1);
						$output .= "<span title='{$value['fname']} {$value['lname']} (x{$value['count']})' >{$initials}</span>";
					}
				}
				return $output;
			}
		// GET rsvp status selection HTML
			function get_rsvp_choices($opt2, $optRS, $countARR=''){
				$selection = (!empty($optRS['evors_selection']))? $optRS['evors_selection']: true;
				$selOpt = array(
					'y'=>array('Yes', 'evoRSL_003'),
					'n'=>array('No', 'evoRSL_005'),
					'm'=>array('Maybe', 'evoRSL_004'),
				);

				$content ='';
				$lang = !empty($this->currentlang)? $this->currentlang: 'L1';
				foreach($selOpt as $field=>$value){
					if( is_array($selection) && in_array($field, $selection) || $field=='y'){
						$selCount = (!is_array($selection))? 'one': '';
						$count = (!empty($countARR))? $countARR[$field]: null;
						$content .= "<span data-val='{$field}' class='{$selCount}'>".eventon_get_custom_language($opt2, $value[1],$value[0], $lang).$count."</span>";
					}
				}
				return $content;
			}
		// add eventon rsvp event card field to filter
			function eventcard_array($array, $pmv, $eventid, $__repeatInterval){
				$array['evorsvp']= array(
					'event_id' => $eventid,
					'value'=>'tt',
					'__repeatInterval'=>(!empty($__repeatInterval)? $__repeatInterval:0)
				);
				return $array;
			}
			function eventcard_adds($array){
				$array[] = 'evorsvp';
				return $array;
			}

	// SAVE new RSVP
		function _form_save_rsvp($args){
			global $eventon_rs;
			$status = 0;
			
			// add new rsvp
			if($created_rsvp_id = $this->create_post() ){

				//$pmv = get_post_meta($args['e_id']);				
				$_count = (empty($args['count']))?1: $args['count'];
				$_count = (int)$_count;					

				// save rsvp data								
				$this->create_custom_fields($created_rsvp_id, 'first_name', $args['first_name']);
				if(!empty($args['last_name']))
					$this->create_custom_fields($created_rsvp_id, 'last_name', $args['last_name']);

				if(!empty($args['email']))
					$this->create_custom_fields($created_rsvp_id, 'email', $args['email']);

				if(!empty($args['phone']))		
					$this->create_custom_fields($created_rsvp_id, 'phone', $args['phone']);		

				$this->create_custom_fields($created_rsvp_id, 'rsvp', $args['rsvp']); // y n m	
				$this->create_custom_fields($created_rsvp_id, 'updates', $args['updates']);	
				$this->create_custom_fields($created_rsvp_id, 'count', $_count);	
				$this->create_custom_fields($created_rsvp_id, 'e_id', $args['e_id']);

				$__repeat_interval = (!empty($args['repeat_interval']))? $args['repeat_interval']: '0';
				$this->create_custom_fields($created_rsvp_id, 'repeat_interval', $__repeat_interval);

				// save additional form fields
					$optRS = $this->optRS;
					for($x=1; $x<4; $x++){
						if(evo_settings_val('evors_addf'.$x, $optRS) && !empty($optRS['evors_addf'.$x.'_1'])  ){
							$value = (!empty($args['evors_addf'.$x.'_1']))? $args['evors_addf'.$x.'_1']: '-';
							$this->create_custom_fields($created_rsvp_id, 'evors_addf'.$x.'_1', $value);
						}
					}

				// save loggedin user ID if prefill fields for loggedin enabled
					$prefill_enabled = (!empty($optRS['evors_prefil']) && $optRS['evors_prefil']=='yes')? true:false;
					if( ($this->functions->get_current_userid() && $prefill_enabled) || !empty($args['uid'])){
						// user ID if provided or find loggedin user id
						$CURRENT_user_id = !empty($args['uid'])? $args['uid']: $this->functions->get_current_userid();
						$this->create_custom_fields($created_rsvp_id, 'userid',$CURRENT_user_id);

						// add user meta
						$this->functions->add_user_meta($CURRENT_user_id, $args['e_id'], $__repeat_interval, $args['rsvp']);
					}

				$args['rsvp_id'] = $created_rsvp_id;

				// SYNC event's rsvp counts
				$this->functions->sync_rsvp_count($args['e_id']);

				
				// send out email confirmation
				if($args['rsvp']!='n'){	
					$this->send_email_conf($args);
				}
				
				$this->send_email_notif($args);

				$status = $created_rsvp_id;

			}else{	$status = 7; // new rsvp post was not created
			}
		
			return $status;
		}

	// EMAIL function 
		public function _event_date($pmv, $start_unix, $end_unix){
			global $eventon;
			$evcal_lang_allday = eventon_get_custom_language( '','evcal_lang_allday', 'All Day');
			$date_array = $eventon->evo_generator->generate_time_('','', $pmv, $evcal_lang_allday,'','',$start_unix,$end_unix);	
			return $date_array;
		}

		// RETURN corected event end time for repeat interval
			function get_correct_event_end_time($e_pmv, $__repeatInterval){
				$datetime = new evo_datetime();
				return $datetime->get_int_correct_event_time($e_pmv, $__repeatInterval, 'end');	
		    }

		// send email confirmation of RSVP  to submitter
			function get_email_data($args, $type='confirmation'){
				$this->evors_args = $args;

				$email_data = array();

				$from_email = $this->get_from_email();

				if($type=='confirmation'){
					$email_data['to'] = $args['email'];
					$email_data['args'] = $args;
					$email_data['type'] = $type;
				}else{
					$__to_email = (!empty($this->evors_opt['evors_notfiemailto']) )?
						htmlspecialchars_decode ($this->evors_opt['evors_notfiemailto'])
						:get_bloginfo('admin_email');
						$_other_to = get_post_meta($args['e_id'],'evors_add_emails', true);
						$_other_to = (!empty($_other_to))? $_other_to: null;

					$email_data['to'] = $__to_email.','.$_other_to;
				}				

				if(!empty($email_data['to'])){
					if($type=='confirmation'){
						$email_data['subject'] = '[#'.$args['rsvp_id'].'] '.((!empty($this->evors_opt['evors_notfiesubjest_e']))? 
						$this->evors_opt['evors_notfiesubjest_e']: __('RSVP Confirmation','eventon'));
						$filename = 'confirmation_email';
						$headers = 'From: '.$from_email;
					}else{
						$email_data['subject'] ='[#'.$args['rsvp_id'].'] '.((!empty($this->evors_opt['evors_notfiesubjest']))? $this->evors_opt['evors_notfiesubjest']: __('New RSVP Notification','eventon'));
						$filename = 'notification_email';
						$headers = 'From: '.$from_email. "\r\n";
						$headers .= 'Reply-To: '.$args['email']. "\r\n";
					}
					
					$email_data['message'] = $this->_get_email_body($args, $filename);
					$email_data['header'] = $headers;	
					$email_data['from'] = $from_email;	
				}
				return $email_data;
			}

			function send_email_conf($args){
				global $eventon_rs;
				$args['html']= 'yes';
				return $eventon_rs->helper->send_email(
					$this->get_email_data($args)
				);
			}
			function send_email_notif($args){				
				if(!empty($this->evors_opt['evors_notif']) && $this->evors_opt['evors_notif']=='yes'){
					global $eventon_rs;
					$args['html']= 'yes';
					return $eventon_rs->helper->send_email(
						$this->get_email_data($args)
					);
				}
			}
			// return proper from email with name
			function get_from_email(){
				$__from_email = (!empty($this->evors_opt['evors_notfiemailfrom_e']) )?
					htmlspecialchars_decode ($this->evors_opt['evors_notfiemailfrom_e'])
					:get_bloginfo('admin_email');
				$__from_email_name = (!empty($this->evors_opt['evors_notfiemailfromN_e']) )?
					($this->evors_opt['evors_notfiemailfromN_e'])
					:get_bloginfo('name');
					$from_email = (!empty($__from_email_name))? 
						$__from_email_name.' <'.$__from_email.'>' : $__from_email;
				return $from_email;
			}

		// email body for confirmation
			function _get_email_body($evors_args, $file){
				global $eventon, $eventon_rs;

				$path = $eventon_rs->addon_data['plugin_path']."/templates/";
				$args = $evors_args;

				$paths = array(
					0=> TEMPLATEPATH.'/'.$eventon->template_url.'templates/email/rsvp/',
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

				ob_start();
				include($template);
				return ob_get_clean();
			}
			// this will return eventon email template driven email body
			// need to update this after evo 2.3.8 release
			function get_evo_email_body($message){
				global $eventon;
				// /echo $eventon->get_email_part('footer');
				ob_start();
				$wrapper = "
					background-color: #e6e7e8;
					-webkit-text-size-adjust:none !important;
					margin:0;
					padding: 25px 25px 25px 25px;
				";
				$innner = "
					background-color: #ffffff;
					-webkit-text-size-adjust:none !important;
					margin:0;
					border-radius:5px;
				";
				?>
				<!DOCTYPE html>
				<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>
				<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
					<div style="<?php echo $wrapper; ?>">
						<div style="<?php echo $innner;?>"><?php
				echo $message;
				echo $eventon->get_email_part('footer');
				return ob_get_clean();
			}

	// user RSVP manager
		function user_rsvp_manager(){
			global $eventon_rs, $eventon;

			$this->register_styles_scripts();
			add_action('wp_footer', array($this, 'footer_content'));	
			$this->footer_content();		

			// intial variables
			$current_user = get_user_by( 'id', get_current_user_id() );
			$USERID = is_user_logged_in()? get_current_user_id(): false;
			$current_page_link = get_page_link();

			// loading child templates
				$file_name = 'rsvp_user_manager.php';
				$paths = array(
					0=> TEMPLATEPATH.'/'.$eventon->template_url.'rsvp/',
					1=> $eventon_rs->plugin_path.'/templates/',
				);

				foreach($paths as $path){	
					if(file_exists($path.$file_name) ){	
						$template = $path.$file_name;	
						break;
					}
				}

			require_once($template);
		}
	
	// SUPPORT functions	
		// RETURN: language
			function lang($variable, $default_text){
				global $eventon_rs;
				return $eventon_rs->lang($variable, $default_text);
			}
		// function replace event name from string
			function replace_en($string){
				return str_replace('[event-name]', "<span class='eventName'>Event Name</span>", $string);
			}
		// get proper rsvp status name I18N
			public function get_checkin_status($status, $lang='', $evopt=''){
				$evopt = $this->opt2;
				$lang = (!empty($lang))? $lang : 'L1';

				if($status=='check-in'){
					return (!empty($evopt[$lang]['evoRSL_003x']))? $evopt[$lang]['evoRSL_003x']: 'check-in';
				}else{
					return (!empty($evopt[$lang]['evoRSL_003y']))? $evopt[$lang]['evoRSL_003y']: 'checked';
				}
			}
			public function get_trans_checkin_status($lang=''){
				$evopt = $this->opt2;
				$lang = (!empty($lang))? $lang : 'L1';

				return array(
					'check-in'=>(!empty($evopt[$lang]['evoRSL_003x'])? $evopt[$lang]['evoRSL_003x']: 'check-in'),
					'checked'=>(!empty($evopt[$lang]['evoRSL_003y'])? $evopt[$lang]['evoRSL_003y']: 'checked'),
				);
			}

		// Internationalization rsvp status yes, no, maybe
			public function get_rsvp_status($status, $lang=''){
				if(empty($status)) return;

				$opt2 = $this->opt2;
				$_sta = array(
					'y'=>array('Yes', 'evoRSL_003'),
					'n'=>array('No', 'evoRSL_005'),
					'm'=>array('Maybe', 'evoRSL_004'),
				);

				$lang = (!empty($lang))? $lang: 'L1';
				return $this->lang($_sta[$status][1], $_sta[$status][0], $lang);
			}
		function create_post() {
			
			$type = 'evo-rsvp';
	        $valid_type = (function_exists('post_type_exists') &&  post_type_exists($type));

	        if (!$valid_type) {
	            $this->log['error']["type-{$type}"] = sprintf(
	                'Unknown post type "%s".', $type);
	        }
	       
	        $title = 'RSVP '.date('M d Y @ h:i:sa', time());
	        $author = ($this->get_author_id())? $this->get_author_id(): 1;

	        $new_post = array(
	            'post_title'   => $title,
	            'post_status'  => 'publish',
	            'post_type'    => $type,
	            'post_name'    => sanitize_title($title),
	            'post_author'  => $author,
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
	    // return sanitized additional rsvp field option values
	    function get_additional_field_options($val){
	    	$OPTIONS = stripslashes($val);
			$OPTIONS = str_replace(', ', ',', $OPTIONS);
			$OPTIONS = explode(',', $OPTIONS);
			$output = false;
			foreach($OPTIONS as $option){
				$slug = str_replace(' ', '-', $option);
				$output[$slug]= $option;
			}
			return $output;
	    }
}
