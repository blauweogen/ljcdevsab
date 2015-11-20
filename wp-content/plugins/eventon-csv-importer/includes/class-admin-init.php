<?php
/**
 * Admin class for csv importer plugin
 *
 * @version 	0.1
 * @author  	AJDE
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evocsv_admin{
	var $log= array();

	function __construct(){
		global $eventon_csv;
		add_filter('eventon_settings_tabs',array($this, 'tab_array') ,10, 1);
		add_action('eventon_settings_tabs_evcal_csv',array($this, 'tab_content') );
		//add_filter('eventon_event_frequency_daily',array($this, 'daily_switch'),10,1);
		add_action('admin_init', array($this, 'admin_scripts'));
		
		// settings link in plugins page
		add_filter("plugin_action_links_".$eventon_csv->plugin_slug, array($this,'eventon_plugin_links' ));
	}
	/**	Add the tab to settings page on myeventon	 */
		function tab_array($evcal_tabs){
			$evcal_tabs['evcal_csv']='CSV Import';
			return $evcal_tabs;
		}

	/**	CSV tab content	 */
		function tab_content(){
		?>
		<div id="evcal_csv" class="postbox evcal_admin_meta">	
			<div class="inside">
				<?php
					$steps = (!isset($_GET['steps']))?null:$_GET['steps'];					
					$this->step_content($steps);	
				?>
			</div>
		</div>
		<?php
		}

		/**	Styles for the tab page	 */
		public function admin_scripts(){
			global $eventon_csv, $pagenow;

			if( (!empty($pagenow) && $pagenow=='admin.php')
			 && (!empty($_GET['page']) && $_GET['page']=='eventon') 
			){
				wp_enqueue_style( 'csv_import_styles',$eventon_csv->plugin_url.'/assets/csv_import_styles.css');
				wp_enqueue_script('csv_import_script',$eventon_csv->plugin_url.'/assets/script.js', array('jquery'), 1.0, true );
			}
		}
	
	
	
	/**
	 * Content for each step of the CSV import stages
	 * Via switch statement
	 */
		function step_content($steps){
			global $eventon_csv;

			$evo_opt = get_option('evcal_options_evcal_1');
			$event_type_count = evo_get_ett_count($evo_opt);
			$cmd_count = evo_calculate_cmd_count($evo_opt);


			switch($steps){
			
			// Step nuber 1
			case 'uno':?>
					
			<h2><?php _e('Step 1: Upload CSV file','eventon')?></h2>
			<?php	
				$this->form();			
				$this->print_guidelines();
			
			break;
			
			// Step number 2
			case 'dos':
				
				if( $this->csv_verify_nonce_post( 'eventon_csvi_noncename')){

					?>
				<h2><?php _e('Step 2: Verify uploaded events','eventon')?></h2>
				<p><?php _e('In this step you can see below the events we found from your uploaded CSV file. Please making sure the data is present correctly. You can also click on each event to deselect them from being imported to EventON - in the next step.','eventon')?></p>
				<?php
				
					// verified nonce
					if (empty($_FILES['csv_import']['tmp_name'])) {
						$this->log['error'][] = 'No file uploaded, Please try again!.';
						$this->print_messages();
						
						$this->step_content('uno');
						return;
					}
					
					
					// get csv helper file
					require_once $eventon_csv->plugin_path.'/assets/DataSource.php';

					$time_start = microtime(true);
					$csv = new File_CSV_DataSource;
					$file = $_FILES['csv_import']['tmp_name'];
					$this->stripBOM($file);

					if (!$csv->load($file)) {
						$this->log['error'][] = 'Failed to load file, Please try again!.';
						$this->print_messages();						
						$this->step_content('uno');
						return;
					}
					
					 // pad shorter rows with empty values
					$csv->symmetrize();
					
					// correct wordpress time zone for event posts
					$tz = get_option('timezone_string');
					if ($tz && function_exists('date_default_timezone_set')) {
						date_default_timezone_set($tz);
					}
					
										
					?>
					<form class="" action='<?php echo admin_url();?>admin.php?page=eventon&tab=evcal_csv&steps=thres' method="post" enctype="multipart/form-data">
					<?php 
						settings_fields('eventon_csvi_field_grp'); 
						wp_nonce_field( $eventon_csv->plugin_path, 'eventon_csvi_dos_noncename' );
					
					echo "<table id='eventon_csv_data_list' class='wp-list-table widefat'>
							<thead>
							<th></th>
							<th title='Publish status for event'>Status</th>
							<th>Event Name</th>
							<th>Description</th>
							<th>Start Date & Time</th>
							<th>End Date & Time</th>
							<th>Location</th>
							<th>Organizer</th>
							</thead>";
					
					// for each record		
					$x=0;

					foreach ($csv->connect() as $csv_data) {
							$ev_desc_class = (!empty($csv_data['event_description']))? 'inner_check':'inner_check_no';
							$ev_location_class = (!empty($csv_data['event_location']))? 'inner_check':'inner_check_no';
							$ev_orga_class = (!empty($csv_data['event_organizer']))? 'inner_check':'inner_check_no';
							
							// event date validation
								if(!empty($csv_data['event_start_date'])){
									if(preg_match('/^(\d{1,2})\/(\d{1,2})\/((?:\d{2}){1,2})$/', $csv_data['event_start_date']) ){
										$event_start_date = $event_start_date_val =$csv_data['event_start_date'];
									}else{	
										$event_start_date ="<p class='inner_check_no eventon_csv_icons'></p>";	
										$event_start_date_val =null;
									}
								}else{ $event_start_date ="<p class='inner_check_no eventon_csv_icons'></p>"; $event_start_date_val =null;	}
							
							// event start time validation
								if(!empty($csv_data['event_start_time'])){
									if(preg_match('/(1[0-2]|0?[0-9]):[0-5]?[0-9]?:(AM|PM)/', $csv_data['event_start_time']) ){
										$event_start_time = $event_start_time_val =$csv_data['event_start_time'];
									}else{	
										$event_start_time ="<p class='inner_check_no eventon_csv_icons'></p>";	
										$event_start_time_val =null;
									}
								}else{ $event_start_time ="<p class='inner_check_no eventon_csv_icons'></p>"; $event_start_time_val =null;	}
							// end time
								if(!empty($csv_data['event_end_time'])){
									if(preg_match('/(1[0-2]|0?[0-9]):[0-5]?[0-9]?:(AM|PM)/', $csv_data['event_end_time']) ){
										$event_end_time = $event_end_time_val =$csv_data['event_end_time'];
									}else{	
										$event_end_time ="<p class='inner_check_no eventon_csv_icons'></p>";	
										$event_end_time_val =$event_start_time_val;
									}
								}else{ $event_end_time ="<p class='inner_check_no eventon_csv_icons'></p>"; 
									$event_end_time_val =$event_start_time_val;	}								
							// event end date
								if(!empty($csv_data['event_end_date'])){
									if(preg_match('/^(\d{1,2})\/(\d{1,2})\/((?:\d{2}){1,2})$/', $csv_data['event_end_date']) ){
										$event_end_date = $event_end_date_val =$csv_data['event_end_date'];
									}else{	
										$event_end_date ="<p class='inner_check_no eventon_csv_icons'></p>";
										$event_end_date_val = $event_start_date_val;
									}
								}else{ // no end date present
									$event_end_date ="<p class='inner_check_no eventon_csv_icons'></p>";	
									$event_end_date_val = $event_start_date_val;
								}

							// description
								$event_description = (!empty($csv_data['event_description']))? convert_chars(addslashes($csv_data['event_description'] )): null;
							
							// /print_r($csv_data);

							$updated_csv_data = $csv_data;
							$updated_csv_data['event_start_date'] = $event_start_date_val;
							$updated_csv_data['event_start_time'] = $event_start_time_val;
							$updated_csv_data['event_end_date'] = $event_end_date_val;
							$updated_csv_data['event_end_time'] = $event_end_time_val;
							$updated_csv_data['event_description'] = $event_description;
						?>	
						<tr class='csv_list_row'>
							<?php echo $this->_html_get_hidden_input_fields($updated_csv_data, $x, $event_type_count, $cmd_count);?>
							<td class='col1'><p class='outter_check eventon_csv_icons'></p></td>
							<td><i><?php echo $csv_data['publish_status'];?></i></td>
							<td><?php echo $csv_data['event_name'];?></td>
							<td><p class='<?php echo $ev_desc_class;?> eventon_csv_icons'></p></td>
							<td><?php echo $event_start_date.'<br/>'.$event_start_time;?></td>
							<td><?php echo $event_end_date.'<br/>'.$event_end_time;?></td>
							<td title='<?php echo $csv_data['event_location'];?>'><p class='<?php echo $ev_location_class;?> eventon_csv_icons'></p></td>
							<td title='<?php echo $csv_data['event_organizer'];?>'><p class='<?php echo $ev_orga_class;?> eventon_csv_icons'></p></td>
						</tr>
						
						<?php						
						$x++;
					}
					
					echo "</table><p class='submit'><input type='submit' class='evo_admin_btn btn_tritiary' name='submit' value='Next: Import selected events' /></p></form>";
					
					if (file_exists($file)) {
						@unlink($file);
					}
					
					$exec_time = microtime(true) - $time_start;
					
					$this->log['notice'][] = sprintf("<b>Found {$x} events in %.2f seconds from the csv file.</b>", $exec_time);
					$this->print_messages();									
				}				
					
			break;
			
			// Step three
			case 'thres':
				if($this->csv_verify_nonce_post('eventon_csvi_dos_noncename')){		
					
					if(isset($_POST['csvi'])){
						
						$skipped = 0;
						$imported = 0;
						
						$event_names_imp = array();
						$event_names_skip = array();
						
						$time_start = microtime(true);
						//$date_format = eventon_get_timeNdate_format();
						
						
						// Run through each event row and add them
						foreach($_POST['csvi'] as $event){
							// check is the event is selected to be imported
							if($event['status']=='yes'){ 
								if($post_id = $this->create_post($event) ){
									$imported++;
									$event_names_imp[]=$event['event_name'];
									
									/*	Event Start and end time and dates	*/	
										if(isset($event['event_start_date'])&& isset($event['event_end_date']) &&isset($event['event_start_time']) && isset($event['event_end_time']) )
										{
											$start_time = explode(":",$event['event_start_time']);
											$end_time = explode(":",$event['event_end_time']);
											
											$date_array = array(
												'evcal_start_date'=>$event['event_start_date'],
												'evcal_start_time_hour'=>$start_time[0],
												'evcal_start_time_min'=>$start_time[1],
												'evcal_st_ampm'=>$start_time[2],
												'evcal_end_date'=>$event['event_end_date'], 										
												'evcal_end_time_hour'=>$end_time[0],
												'evcal_end_time_min'=>$end_time[1],
												'evcal_et_ampm'=>$end_time[2],

												'evcal_allday'=>( !empty($event['all_day'])? $event['all_day']:'no'),
											);
											
											$proper_time = eventon_get_unix_time($date_array, 'm/d/Y');
											
											// save required start time variables
											$this->create_custom_fields($post_id, 'evcal_srow', $proper_time['unix_start']);
											$this->create_custom_fields($post_id, 'evcal_erow', $proper_time['unix_end']);									
											//$this->create_custom_fields($post_id, 'data', $date_format[1]);									
											
										}
									
									// rest of the custom meta fields
									$this->save_custom_meta_fields($event, $post_id,$event_type_count, $cmd_count);
								}else{
									$skipped++;
									$event_names_skip[]=$event['event_name'];
								}
							}
						}
						
						// create notices
						if ($skipped>0) {
							$this->log['notice'][] = "<b>Skipped {$skipped} events (most likely due to empty title or description).</b>";
						}
						
						$exec_time = microtime(true) - $time_start;
						$this->log['notice'][] = sprintf("<b>Imported {$imported} events in %.2f seconds.</b>", $exec_time);
						$this->print_messages();
						
						// =====
						// Show the report on import process
						_e('<h2>Done!</h2>','eventon');
						echo "<p>Please go to <a href='".admin_url()."edit.php?post_type=ajde_events'>All Events</a> to further customize the events you just imported.</p>";
						
						// Success report					
							if(count($event_names_imp)>0){
								echo "<table id='eventon_csv_data_list' class='wp-list-table widefat'>
									<thead>
									<th>Event Name</th>
									<th>Status</th>
									</thead>";
								
								foreach($event_names_imp as $event_name){
									echo "<tr><td>{$event_name}</td><td>Imported</td></tr>";
								}
								
								// didnt import
								if(count($event_names_skip)>0){
									foreach($event_names_skip as $event_name){
										echo "<tr><td>{$event_name}</td><td>Not Imported</td></tr>";
									}
								}
								echo "</table>";
							}
					}	
				}
			break;
			
			// default step
			default:?>
				<h3><?php _e('Import Events from a CSV file','eventon')?></h3>
				<p>CSV Importer addon for EventON will allow you to export events from another event plugin or another event calendar, into CSV file format, and import those events into EventON Calendar.</p>
				
				<hr/>
				
				<a href='<?php echo admin_url();?>admin.php?page=eventon&tab=evcal_csv&steps=uno' class='csv_import_get_start_btn evo_admin_btn btn_prime'><?php _e('Get Started Now','eventon');?></a>		
						
				<?php 
				$this->print_guidelines();
			break;
			
			}// end switch statement
		}

		// return hidden input fields
			function _html_get_hidden_input_fields($csv_data, $x, $event_type_count, $cmd_count){
				
				ob_start();
				?>
				<input class='csv_row_status' type='hidden' name='csvi[<?php echo $x;?>][status]' value='yes'/>
				<?php
					foreach(array('publish_status','featured','color','event_name','location_name','event_location','event_gmap','event_organizer','all_day','hide_end_time','event_start_date','event_start_time','event_end_date','event_end_time','event_description') as $field){

						if(!empty($csv_data[$field])):?>
							<input type='hidden' name='csvi[<?php echo $x;?>][<?php echo $field;?>]' value="<?php echo $csv_data[$field];?>"/>
						<?php endif;
					}

					// for event taxonomies
					for($y=1; $y<=$event_type_count;  $y++){
						$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
						if(!empty($csv_data[$_ett_name])){
							echo "<input type='hidden' name='csvi[{$x}][{$_ett_name}]' value='{$csv_data[$_ett_name]}'/>";
						}
					}

					// for event custom meta data
					for($z=1; $z<=$cmd_count;  $z++){
						$_cmd_name = 'cmd_'.$z;
						if(!empty($csv_data[$_cmd_name])){
							echo "<input type='hidden' name='csvi[{$x}][{$_cmd_name}]' value='{$csv_data[$_cmd_name]}'/>";
						}
					}

				return ob_get_clean();
			}
		
		// save custom meta fields
			 function save_custom_meta_fields($post_data, $post_id,$event_type_count, $cmd_count){
			 	// yes no value fields
			 	foreach(array('_featured'=>'featured','evcal_gmap_gen'=>'event_gmap','evcal_allday'=>'all_day','evo_hide_endtime'=>'hide_end_time') as $field=>$var){
			 		if(isset($post_data[$var]) && strtolower($post_data[$var])=='yes'){
			 			$this->create_custom_fields($post_id, $field, 'yes');
					}
			 	}

			 	// color
				 	if( isset($post_data['color']) )
						$this->create_custom_fields($post_id, 'evcal_event_color', $post_data['color']);

			 	// event location
					if(isset($post_data['event_location'])){
						$this->create_custom_fields($post_id, 'evcal_location', $post_data['event_location']);
					}if(isset($post_data['location_name'])){
						$this->create_custom_fields($post_id, 'evcal_location_name', $post_data['location_name']);
					}

				// organizer
					if(isset($post_data['event_organizer']))
						$this->create_custom_fields($post_id, 'evcal_organizer', $post_data['event_organizer']);

				// for event taxonomies
					for($y=1; $y<=$event_type_count;  $y++){
						$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
						if(!empty($post_data[$_ett_name])){
							$ett = explode(',', $post_data[$_ett_name]);
							wp_set_post_terms($post_id, $ett, $_ett_name);
						}
					}
				// for event custom meta data
					for($z=1; $z<=$cmd_count;  $z++){
						$_cmd_name = 'cmd_'.$z;
						if(!empty($post_data[$_cmd_name])){
							$this->create_custom_fields($post_id, '_evcal_ec_f'.$z.'a1_cus', $post_data[$_cmd_name]);
						}
					}
			 }

	/** function to verify wp nonce and the $_POST array submit values	 */
		function csv_verify_nonce_post($post_field){
			global $_POST, $eventon_csv;
			if(isset( $_POST ) && $_POST[$post_field]  ){
				if ( wp_verify_nonce( $_POST[$post_field],  $eventon_csv->plugin_path )){
					return true;
				}else{	
					$this->log['error'][] ="Could not verify the form submit. Please try again.";
					$this->print_messages();
					return false;	}
			}else{	
				$this->log['error'][] ="Could not verify the form submit. Please try again.";
				$this->print_messages();
				return false;	}
		}
	
	/** Print the messages for the csv settings	 */
		function print_messages(){
			if (!empty($this->log)) {
				
				if (!empty($this->log['error'])): ?>
				
				<div class="error">
					<?php foreach ($this->log['error'] as $error): ?>
						<p class=''><?php echo $error; ?></p>
					<?php endforeach; ?>
				</div>			
				<?php endif; ?>
				
				
				<?php if (!empty($this->log['notice'])): ?>
				<div class="updated fade">
					<?php foreach ($this->log['notice'] as $notice): ?>
						<p><?php echo $notice; ?></p>
					<?php endforeach; ?>
				</div>
				<?php endif; 
							
				$this->log = array();
			}
		}
	
	// the form
		function form(){	
			global $eventon_csv;	
	        // form HTML {{{
			?>
			<div class="wrap">
				<form class="" action='<?php echo admin_url();?>admin.php?page=eventon&tab=evcal_csv&steps=dos' method="post" enctype="multipart/form-data">
				<?php 
				settings_fields('eventon_csvi_field_grp'); 
				wp_nonce_field( $eventon_csv->plugin_path, 'eventon_csvi_noncename' );
				?>
			        <!-- File input -->
			        <p><label for="csv_import">Select a .CSV file:</label><br/>
			            <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
			        <p class="submit"><input type="submit" class="evo_admin_btn btn_tritiary" name="submit" value="<?php _e('Next','eventon')?>" /></p>
			    </form>
			</div><!-- end wrap -->

			<?php
	        // end form HTML }}}
		}

	/** Print guidelines messages	 */
		function print_guidelines(){
			global $eventon, $eventon_csv;
			
			ob_start();			
			require_once($eventon_csv->plugin_path.'/guide.php');			
			$content = ob_get_clean();
			
			echo $eventon->output_eventon_pop_window( 
				array('content'=>$content, 'title'=>'How to use CSV Importer', 'type'=>'padded')
			);
			?>				
				<hr/>
				<h3><?php _e('**CSV file guidelines','eventon')?></h3>
				<p><?php _e('Please read the guidelines below for correct CSV format to import events from a CSV file successfully.','eventon');?></p>
				<p><img id='eventon_csv_guide_trig' class='evcsv_img ajde_popup_trig' src='<?php echo $eventon_csv->plugin_url.'/assets/images/csv_import_01.jpg'?>'/></p>
			<?php
		}	
	
	/** Create the event post */
		function create_post($data) {
	       
			$opt_draft = (!empty($data['publish_status']))?$data['publish_status']:'draft';        
	        $type = 'ajde_events';
	        $valid_type = (function_exists('post_type_exists') &&  post_type_exists($type));

	        if (!$valid_type) {
	            $this->log['error']["type-{$type}"] = sprintf(
	                'Unknown post type "%s".', $type);
	        }

	        $new_post = array(
	            'post_title'   => convert_chars(addslashes($data['event_name'])),
	            'post_content' => (!empty($data['event_description'])? wpautop(convert_chars(stripslashes($data['event_description']))): ''),
	            'post_status'  => $opt_draft,
	            'post_type'    => $type,
	            'post_name'    => sanitize_title($data['event_name']),
	            'post_author'  => $this->get_author_id(),
	        );
	       
	        // create!
	        $id = wp_insert_post($new_post);
	       
	        return $id;
	    }
	
		function create_custom_fields($post_id, $field, $value) {       
	        add_post_meta($post_id, $field, $value);
	    }
		
		function get_author_id() {
			$current_user = wp_get_current_user();
	        return (($current_user instanceof WP_User)) ? $current_user->ID : 0;
	    }
	
	/** Convert date in CSV file to 1999-12-31 23:52:00 format    */
	    function get_event_post_date() {
	        return date('Y-m-d H:i:s', time());        
	    }	
		
    /**
     * Delete BOM from UTF-8 file.
     *
     * @param string $fname
     * @return void
     */
	    function stripBOM($fname) {
	        $res = fopen($fname, 'rb');
	        if (false !== $res) {
	            $bytes = fread($res, 3);
	            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
	                $this->log['notice'][] = 'Getting rid of byte order mark...';
	                fclose($res);

	                $contents = file_get_contents($fname);
	                if (false === $contents) {
	                    trigger_error('Failed to get file contents.', E_USER_WARNING);
	                }
	                $contents = substr($contents, 3);
	                $success = file_put_contents($fname, $contents);
	                if (false === $success) {
	                    trigger_error('Failed to put file contents.', E_USER_WARNING);
	                }
	            } else {
	                fclose($res);
	            }
	        } else {
	            $this->log['error'][] = 'Failed to open file, aborting.';
	        }
	    }
	
	// SECONDARY FUNCTIONS
    	function eventon_plugin_links($links){
			$settings_link = '<a href="admin.php?page=eventon&tab=evcal_csv">Settings</a>'; 
			array_unshift($links, $settings_link); 
	 		return $links; 	
		}
}
new evocsv_admin();