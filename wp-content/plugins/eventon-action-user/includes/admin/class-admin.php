<?php
/*
	Action User admin functions
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evoau_admin{
	public function __construct(){
		add_action('init', array($this, 'admininit'));
	}

	// admin init
		function admininit(){
			global $eventon_au;
			add_action('eventon_add_meta_boxes', array($this,'evoAU_trigger_meta_box') );
			add_action('eventon_save_meta', array($this,'evoAU_save_meta_box_values'), 10, 2);
			add_filter('eventon_settings_lang_tab_content', array($this,'evoAU_language_additions'), 10, 1);
			add_filter('eventon_inline_styles_array', array($this,'evoAU_dynamicstyle_insertion'), 10, 1);
			
			// admin styles and scripts
			add_action( 'admin_enqueue_scripts', array($this,'eveoAU_admin_setting_styles') );
			add_action( 'eventon_admin_post_script', array( $this, 'backend_post_scripts' ) ,15);
			add_action( 'admin_enqueue_scripts', array($this,'evoau_admin_scripts' ));

			// other hooks
			add_filter('eventon_core_capabilities', array($this, 'add_new_capability_au'),10, 1);
			add_action('admin_menu', array( $this,'evoAU_add_menu_pages'));
			add_action( 'user_row_actions', array( $this,'evoAU_user_row'), 10, 2 );

			// column for events page
			add_filter('evo_event_columns', array($this, 'add_column_title'), 10, 1);
			add_filter('evo_column_type_evoau', array($this, 'column_content'), 10, 1);

			// capabilities
			add_filter( 'map_meta_cap', array($this, 'my_map_meta_cap'), 10, 4 );

			// settings link in plugins page
			add_filter("plugin_action_links_".$eventon_au->plugin_slug, array($this,'eventon_plugin_links' ));	
		}
	// MENUS
		function evoAU_add_menu_pages(){
			add_submenu_page( 'eventon', 'Action User', 'Action User', 'manage_eventon', 'action_user', array($this,'evoAU_action_user_fnct') );
		}
			function evoAU_action_user_fnct(){
				require_once('settings_page.php');
				$settings = new evoau_settings();
				$settings->content();
			}

	// Add settings to dynamic styles
		function evoAU_dynamicstyle_insertion($_existen){
			$new= array(
				array(
					'item'=>'#eventon_form .submit_row input',
					'multicss'=>array(
						array('css'=>'color:#$', 'var'=>'evcal_gen_btn_fc',	'default'=>'ffffff'),
						array('css'=>'background:#$', 'var'=>'evcal_gen_btn_bgc',	'default'=>'237ebd')
					)
				),array(
					'item'=>'#eventon_form .submit_row input:hover',
					'multicss'=>array(
						array('css'=>'color:#$', 'var'=>'evcal_gen_btn_fcx',	'default'=>'fff'),
						array('css'=>'background-color:#$', 'var'=>'evcal_gen_btn_bgcx',	'default'=>'237ebd')
					)
				)
			);

			return (is_array($_existen))? array_merge($_existen, $new): $_existen;
		}

	// language settings additinos
		function evoAU_language_additions($_existen){
			$evcal_opt = get_option('evcal_options_evcal_1');
			$new_ar = array(
					array('type'=>'togheader','name'=>'ADDON: Action User'),
					array('label'=>'Event Name','name'=>'evoAUL_evn','legend'=>''),
					array('label'=>'Event Sub Title','name'=>'evoAUL_est','legend'=>''),
					array('label'=>'Event Start Date/Time','name'=>'evoAUL_esdt','legend'=>''),
					array('label'=>'Event End Date/Time','name'=>'evoAUL_eedt','legend'=>''),
					array('label'=>'Event Details','name'=>'evcal_evcard_details_au','legend'=>''),
					array('label'=>'Event Color','name'=>'evoAUL_ec','legend'=>''),
					array('label'=>'Event Location Name','name'=>'evoAUL_lca'),
					array('label'=>'Event Location Address','name'=>'evoAUL_ln'),
					array('label'=>'Event Location Coordinates (lat,lon Seperated by comma)','name'=>'evoAUL_lcor'),
					array('label'=>'Event Organizer','name'=>'evoAUL_eo','legend'=>''),
					array('label'=>'Event Organizer Contact Information','name'=>'evoAUL_eoc','legend'=>''),
					array('label'=>'Learn More Link','name'=>'evoAUL_lml','legend'=>''),
				);

			// event taxnomies upto 5 all active ones only
			$ett_verify = evo_get_ett_count($evcal_opt );
			$_tax_names_array = evo_get_ettNames($evcal_opt);
			$new_ar_1 = '';
			for($x=1; $x< ($ett_verify+1); $x++){
				$ab = ($x==1)? '':'_'.$x;
				$__tax_name = $_tax_names_array[$x];
				$new_ar_1[]= array('label'=>'Select the '.$__tax_name.'','name'=>'evoAUL_stet'.$x,'legend'=>'');
			}

			$new_ar_2 = array(
				array('label'=>'Edit Submitted Event','name'=>'evoAUL_ese','legend'=>''),
				array('label'=>'Event Image','name'=>'evoAUL_ei','legend'=>''),				
				array('label'=>'All Day Event','name'=>'evoAUL_001','legend'=>''),
				array('label'=>'No End time','name'=>'evoAUL_002','legend'=>''),
				array('label'=>'Your Full Name','name'=>'evoAUL_fn','legend'=>''),
				array('label'=>'Your Email Address','name'=>'evoAUL_ea','legend'=>''),
				array('label'=>'Form Human Submission Validation','name'=>'evoAUL_cap','legend'=>''),
				array('label'=>'Select an Image','name'=>'evoAUL_img002','legend'=>''),
				array('label'=>'Image Chosen','name'=>'evoAUL_img001','legend'=>''),
				array('label'=>'Additional Field','name'=>'evoAU_add','legend'=>''),
				array('label'=>'(Text)','var'=>'1'),
				array('label'=>'(Link)','var'=>'1'),				
				array('label'=>'Submit Event','name'=>'evoAUL_se','legend'=>''),
				array('label'=>'Submit another event','var'=>'1'),

				array('label'=>'Form field placeholders','type'=>'subheader'),
					array('label'=>'Start Date','name'=>'evoAUL_phsd','legend'=>''),
					array('label'=>'Start Time','name'=>'evoAUL_phst','legend'=>''),
					array('label'=>'End Date','name'=>'evoAUL_phed','legend'=>''),
					array('label'=>'End Time','name'=>'evoAUL_phet','legend'=>''),
					
				array('type'=>'togend'),
				array('label'=>'User Interaction values','type'=>'subheader'),
					array('label'=>'Slide Down EventCard','name'=>'evoAUL_ux1','legend'=>''),
					array('label'=>'External Link','name'=>'evoAUL_ux2','legend'=>''),
					array('label'=>'Lightbox popup window','name'=>'evoAUL_ux3','legend'=>''),
				array('type'=>'togend'),

				array('label'=>'Form Notification Messages','type'=>'subheader'),
					array('label'=>'You must login to submit events.','name'=>'evoAUL_ymlse','legend'=>''),
					array('label'=>'Required Fields Missing','name'=>'evoAUL_nof1','legend'=>''),
					array('label'=>'Invalid validation code please try again','name'=>'evoAUL_nof2','legend'=>''),
					array('label'=>'Thank you for submitting your event!','name'=>'evoAUL_nof3','legend'=>''),
					array('label'=>'Could not create event post, try again later!','name'=>'evoAUL_nof4','legend'=>''),
					array('label'=>'Bad nonce form verification, try again!','name'=>'evoAUL_nof5','legend'=>''),
					array('label'=>'You can only submit one event!','name'=>'evoAUL_nof6','legend'=>''),
				array('type'=>'togend'),				

				array('type'=>'togend'),
			);

			$new_ar = array_merge($new_ar, $new_ar_1, $new_ar_2);

			return (is_array($_existen))? array_merge($_existen, $new_ar): $_existen;
		}
	
	// USERS page: Add capabilities edit button each users line
		function evoAU_user_row($actions, $user)  {
			global $pagenow;
			if ($pagenow == 'users.php') {				
				if (current_user_can( 'manage_eventon' )) {
				  $actions['evo_capabilities'] = '<a href="' . 
					wp_nonce_url("admin.php?page=action_user&tab=evoau_2&"."object=user&amp;user_id={$user->ID}", "evo_user_{$user->ID}") . 
					'">' . __('EventON Capabilities', 'eventon') . '</a>';
				}      
			}
			return $actions;
		}
		// UPDATE user/role capabilities
			function update_role_caps($ID, $type='role', $action=''){
				global $_POST;
				
				$caps = eventon_get_core_capabilities();
				
				if($type=='role'){
					global $wp_roles;
					
					$current_role_caps = $wp_roles->get_role($ID);		
					$cur_role_caps = ($current_role_caps->capabilities);
					
					foreach($caps as $capgroupf=>$capgroup){			
						foreach($capgroup as $cap){
							
							// add cap
							// If capability exist currently
							if(array_key_exists($cap, $cur_role_caps)){ 
								if($_POST[$cap]=='no'){
									$wp_roles->remove_cap( $ID, $cap );
								}
							}else{// if capability doesnt exists currently
								if($_POST[$cap]=='yes'){
									$wp_roles->add_cap( $ID, $cap );
								}
							}					
						}
					}		
				}else if($type=='user'){
					$currentuser = new WP_User( $ID );
					$cur_role_caps = $currentuser->allcaps;
					
					foreach($caps as $capgroupf=>$capgroup){			
						foreach($capgroup as $cap){					
							// add cap
							// If capability exist currently
							if(array_key_exists($cap, $cur_role_caps)){ 
								if($_POST[$cap]=='no'){
									$currentuser->remove_cap( $cap );
								}
							}else{// if capability doesnt exists currently
								if($_POST[$cap]=='yes'){
									$currentuser->add_cap( $cap );
								}
							}					
						}
					}
				}
			}
		// save user specific capabilities
			public function my_map_meta_cap($caps, $cap, $user_id, $args ) {

				if ( ('edit_eventon' == $cap || 'delete_eventon' == $cap || 'read_eventon' == $cap ) && !empty($args[0])) {
					$post = get_post( $args[0] );
					$post_type = get_post_type_object( $post->post_type );

					$caps = array();

					if ( 'edit_eventon' == $cap ) {
						if ( $user_id == $post->post_author )
							$caps[] = $post_type->cap->edit_posts;
						else
							$caps[] = $post_type->cap->edit_others_posts;
					}

					elseif ( 'delete_eventon' == $cap ) {
						if ( $user_id == $post->post_author)
							$caps[] = $post_type->cap->delete_posts;
						else
							$caps[] = (!empty($post_type->cap->delete_others_posts)? 
								$post_type->cap->delete_others_posts:null);
					}

					elseif ( 'read_eventon' == $cap ) {

						if ( 'private' != $post->post_status )
							$caps[] = 'read';
						elseif ( $user_id == $post->post_author )
							$caps[] = 'read';
						else
							$caps[] = $post_type->cap->read_private_posts;
					}
				}

				/* Return the capabilities required by the user. */
				return $caps;
			}
			
	// ADMIN stylesheet
		function eveoAU_admin_setting_styles(){
			global $eventon_au;
			wp_enqueue_style( 'au_backend_settings',$eventon_au->plugin_url.'/assets/au_styles_settings.css');
		}
		function backend_post_scripts(){
			global $eventon_au;
			wp_enqueue_script( 'evo_au_backend',$eventon_au->plugin_url.'/assets/js/au_script_b.js',array('jquery'),'1.0',true);
		}
		function evoau_admin_scripts(){
			global $pagenow, $eventon_au;
			
			if($pagenow=='admin.php' && $_GET['page']=='action_user'){			
				wp_register_script( 'evo_au_backend_admin',$eventon_au->plugin_url.'/assets/js/au_script_b_admin.js',array('jquery'),'1.0',true);
				wp_localize_script( 'evo_au_backend_admin', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
				
				wp_enqueue_script('evo_au_backend_admin');
			}
		}

	// ADD meta box on events page
		function evoAU_trigger_meta_box(){	
			// restrict access to user permission set box only to those can manage eventon
			add_meta_box('ajdeevcal_mb_au','Action User',  array($this,'evoAU_meta_box'),'ajde_events', 'side', 'low');	
		}

	/* Action User META BOX for events post page*/
		function evoAU_meta_box(){
			global $eventon, $post; 

			if(current_user_can('manage_eventon') || current_user_can('assign_users_to_events')):
			$all_users = get_users();
			
			echo $eventon->output_eventon_pop_window( 
				array('content'=>"<p>Loading</p>",
					'title'=>'Select the users you want to assign to this event',
					'type'=>'padded'
					) );
								
			// The actual fields for data entry
			$p_id = $post->ID;
			$pmv = get_post_custom($p_id);
			
			$saved_users = wp_get_object_terms($p_id, 'event_users', array('fields'=>'slugs'));
			$saved_users = (!empty($saved_users))? $saved_users:null;
			//print_r($terms);
			
			$assigned_users = array();			
		?>
			<div id='popup_content'>
				<div id='evoau_users' class='evoau_users_data' style='display:none'>
					<p><i><?php _e('NOTE: Assigning users to events will allow you to create calendars with events from those assigned users.','eventon');?></i></p>
					<div id='evoau_us_list'>
					<?php					
						foreach($all_users as $uu){
							$checkbox_state='';
							if(is_array($saved_users) && !empty($saved_users) && in_array($uu->ID, $saved_users)){
								$checkbox_state = 'checked="checked"';
								$assigned_users[] = array($uu->ID, $uu->display_name);
							}
							
							echo "<p><input name='evoau_users[]' id='evoau_".$uu->ID."' class='evoau_user_list_item' type='checkbox' value='".$uu->ID."' uname='".$uu->display_name."' ".$checkbox_state."> ".$uu->display_name." <i>(ID: {$uu->ID})</i></p>";
						}
					?>
					</div>
					<a id='evoau_assign_users_btn' class='evo_admin_btn btn_prime ajde_close_pop_trig'><?php _e('Save','eventon');?></a>
				</div>
			</div>
			
			<!-- disable front end editting -->
			<p class='yesno_leg_line' style='padding-top:0px'>
				<?php 	
					$evoau_disableEditing = (!empty($pmv['evoau_disableEditing']))?
						$pmv['evoau_disableEditing'][0]:null;
					echo eventon_html_yesnobtn(
					array(
						'id'=>'evoau_disableEditing', 
						'var'=>$evoau_disableEditing,
						'input'=>true,
						'label'=>__('Disable frontend editing','eventon_cd'),
						'guide'=>__('This will disable users from trying to edit this event on frontend event manager page.'),
						'guide_position'=>'L',
					));
				?>	
			</p>
			<div class="evoau_assign_users">
				<?php
					if(!empty($assigned_users)){
						echo "<h4>".__('Users Assigned to this Event','eventon')."</h4>";
						foreach($assigned_users as $user){
							echo "<p><i>{$user[1]} ({$user[0]})</i></p>";
						}
					}else{
						echo "<p>".__('You can assign users to this event and build calendars with events from only those users.','eventon')." <a href='http://www.myeventon.com/documentation/assign-users-events/' target='_blank'>".__('Learn More','eventon')."</a></p><br/>";
					}
				?>
				<?php if( !empty($post->post_author)):?>
				<p><b>Event Author:</b> <?php echo get_the_author_meta('display_name',$post->post_author);?></p>
				<?php endif;?>
			</div>
			<p><a class='button ajde_popup_trig' content_id='evoau_users' dynamic_c='yes'><?php _e('Manage Assigned Users','eventon');?></a></p>	
			<?php if(!empty($pmv['_submitter_name']) && !empty($pmv['_submitter_email'])):?>
				<p><i><?php _e('Event submitted by','eventon');?>: <b><?php echo $pmv['_submitter_name'][0]?> (<?php echo $pmv['_submitter_email'][0];?>)</b></i></p>
			<?php endif;
			else:
				echo "<p>".__('You do not have permission to edit this section!','eventon')."</p>";
			endif;

			// additional private notes to admin
				if( (current_user_can('manage_eventon') || current_user_can('view_private_event_submission_notes'))  && !empty($pmv['evcalau_notes']) ){
					$notes = trim($pmv['evcalau_notes'][0]);
					if(!empty($notes))
						echo "<p class='evoau_private_note'><span><em>".__('Private notes','eventon')."</em>{$pmv['evcalau_notes'][0]}</span><p>";
				}
		}
			
	// SAVE meta box values for user assignments
		function evoAU_save_meta_box_values($fields, $post_id){	
			$users = (!empty($_POST['evoau_users']))? $_POST['evoau_users']:null;	
			$tagged = wp_set_object_terms( $post_id, $users, 'event_users' );

			if(isset($_POST['evoau_disableEditing']))
				update_post_meta($post_id,'evoau_disableEditing',$_POST['evoau_disableEditing']);
			
		}

	// add a new capability to be able to manage eventon user capabilities
		function add_new_capability_au($caps){
			$new_caps = $caps;			
			$new_caps[] = 'manage_eventon_user_capabilities';			
			$new_caps[] = 'assign_users_to_events';			
			$new_caps[] = 'view_private_event_submission_notes';	
			return $new_caps;
		}	

	// return HTML content for eventON role editor admin settings
		// type = role, user
		function get_cap_list_admin($ID, $type='role'){
			
			$content = $content_l = $content_r ='';	
			$count=1;
			if($type =='role'){
				global $wp_roles;
				$wp_roles = new WP_Roles();
									
				$current_role_caps = $wp_roles->get_role($ID);	
				//print_r($current_role_caps);
				$cur_role_caps = ($current_role_caps->capabilities);			
				
			}else if($type=='user'){
				$currentuser = new WP_User( $ID );
				$cur_role_caps = $currentuser->allcaps;
			}
			
			
			$caps = eventon_get_core_capabilities();
			foreach($caps as $capgroupf=>$capgroup){
				
				foreach($capgroup as $cap){
					$rowcap = $cap;
					
					if($capgroupf=='core'){
						$cap = str_replace('eventon','eventon Settings', $cap);
					}else{
						$cap = str_replace('eventon','event', $cap);
					}
					
					$human_nam = ucwords(str_replace('_',' ',$cap));
					
					$yesno_val = ($ID=='administrator')? 'yes':((isset($cur_role_caps[$rowcap]))? 'yes':'no');
					$disabled = ($ID=='administrator')?'disable':null;
					
					$yesno_btn = eventon_html_yesnobtn(array('var'=>$yesno_val));

					$content= '<p class="yesno_row">'.$yesno_btn.'<input type="hidden" name="'.$rowcap.'" value="'.$yesno_val.'"><span class="field_name">'.$human_nam.'</span></p>';
					
					if($count >10){
						$content_r .=$content;
					}else{
						$content_l .=$content;
					}
					
					$count++;
				}
			}
			
			$content = "<table width='100%' ><tr><td valign='top'>".$content_l."</td><td valign='top'>".$content_r."</td></tr></table>";
			
			return $content;
		}

	// settings link on plugins page
		function eventon_plugin_links($links){
			$settings_link = '<a href="admin.php?page=action_user">Settings</a>'; 
			array_unshift($links, $settings_link); 
	 		return $links; 	
		}

	// Assigned users for column for events
	// @version 1.8
		function add_column_title($columns){
			$columns['evoau']= 'Assigned Users';
			return $columns;
		}
		function column_content($post_id){

			$output = __('None','eventon');

			$saved_users = wp_get_object_terms($post_id, 'event_users', array('fields'=>'slugs'));
			$saved_users = (!empty($saved_users))? $saved_users:null;

			if(!empty($saved_users) && is_array($saved_users)){
				$output='';
				foreach($saved_users as $user){
					$output[]= get_the_author_meta('display_name', $user);
				}
				$output = implode(', ', $output);
			}
			return $output;
		}


}




