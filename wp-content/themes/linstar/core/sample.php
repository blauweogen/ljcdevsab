<?php

/**
*
* (c) king-theme.com /Init widgets
*
*/

?>
<div class="style-1">

	<section class="wrap col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
			
			<div class="row" style="padding: 20px">
				
				<section class="content col-md-12">
					
					<?php 
						
						if( !empty( $_POST['importSampleData'] ) ){
					
					?>
						<img src="<?php echo THEME_URI; ?>/core/assets/images/king-gray.png" height="50" class="pull-right" />
						<div id="errorImportMsg" class="p" style="width:100%;"></div>
						<div id="importWorking">
							<h2 style="color: #30bfbf;">The importer is working</h2>
							<p>Please don't navigate away while importing. Import speed depends on internet connection.</p>
							<i>Status: <span id="import-status" style="font-size: 12px;color: maroon;">Preparing to connect to server...</span></i>
							<div class="progress" style="height:35px;">
						      <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" id="importStatus" style="width: 0%;height:35px;line-height: 35px;">0% Complete</div>
						    </div>
						    <center>
							    &copy; king-theme.com
						    </center>
						</div>
					    <script type="text/javascript">
					    	
					    	var docTitle = document.title;
					    	var el = document.getElementById('importStatus');
					    	
					    	function istaus( is ){
					    		
					    		var perc = parseInt( is*100 )+'%';
					    		el.style.width = perc;
					    		
					    		if( perc != '100%' ){
					    			el.innerHTML = perc+' Complete';
					    		}	
					    		else{
						    		el.innerHTML = 'Download Completed!  &nbsp;  Initializing Data...';	
					    		}
					    		document.title = el.innerHTML+'  - '+docTitle;
					    	}
					    	
					    	function tstatus( t ){
					    		document.getElementById('import-status').innerHTML = t;
					    	}
					    	
					    	function iserror( msg ){
						    	document.getElementById('errorImportMsg').innerHTML += '<div class="alert alert-danger">'+msg+'</div>';
						    	document.getElementById('errorImportMsg').style.display = 'inline-block';
					    	}
					    </script>
					    						
					<?php	
					
						include THEME_PATH.DS.'core'.DS.'sample'.DS.'king.importer.php';						
						
					?>		
						<script type="text/javascript">document.getElementById('importWorking').style.display = 'none';</script>
						
						<h2 style="color: #30bfbf;">Import has completed</h2>
						<div class="h4">
							<p>We will redirect you to homepage after <span id="countdown">10</span> seconds. 
								You can 
								<a href="#" onclick="clearTimeout(countdownTimer)">
									Stop Now
								</a>
								 or go to 
								<a href="<?php echo admin_url('admin.php?page='.strtolower(THEME_NAME).'-panel'); ?>" onclick="clearTimeout(countdownTimer)">
									Theme Panel
								</a>
							</p>
						</div>		
						<div class="p">
							<div class="updated settings-error below-h2">
								<p></p>
								<h3>Import Successful</h3>
								<p>All done. Have fun!</p>
								<p></p>
								<p></p>
							</div>
						</div>		
						
					<?php	
						
						}else{
						
					?>
					
					<form action="" method="post" onsubmit="doSubmit(this)">
						<img src="<?php echo THEME_URI; ?>/core/assets/images/king-gray.png" height="50" class="pull-right" />
						<h2 style="color: #30bfbf;">Welcome to <?php echo THEME_NAME; ?> </h2>
						
						<div class="h4"><p>Thank you for using the <?php echo THEME_NAME; ?> Theme.</p></div>	
						
						<div class="bs-callout bs-callout-info">
							<h4><?php _e('Sample Data','king'); ?></h4>			
							<div class="p">
								<p>
								Let our custom demo content importer do the heavy lifting. Painlessly import settings, layouts, menus, colors, fonts, content, slider and plugins. Then get customising</p>
								Notice: Before import, Make sure your website data is empty (posts, pages, menus...etc...) 
								<br />
								We suggest you use the plugin <a href="<?php echo esc_url(SITE_URI); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-reset&from=<?php echo strtolower(THEME_NAME); ?>-theme&TB_iframe=true&width=800&height=550" class="thickbox" title="Install Wordpress Reset">"Wordpress Reset"</a> to reset your website before import. <br />
								<i>( After install plugin <a href="<?php echo esc_url(SITE_URI); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-reset&from=<?php echo strtolower(THEME_NAME); ?>-theme&TB_iframe=true&width=800&height=550" class="thickbox" title="Install Wordpress Reset">"Wordpress Reset"</a> go to: Tool -> reset )</i>
							</div>		
						</div>	
						
						<div class="p">
							<p>
								<label class="label-form-sel">
									We required using 4 plugins ( Linstar Helper, Visual Composer, Layer Slider & Contact Form 7  )
								</label>
								<br />
								<button id="submitbtn2" onclick="doSubmit2()" class="btn submit-btn">Install Plugins Only</button>
								<input type="hidden" value="" name="pluginsOnly" id="pluginsOnly" />
								<br />
								<br />
								<i class="sub-label-form-sel">Plugins will be installed automatically during Import Sample Data.<br /> You also able to find the installation files in the directory: wp-content/themes/<?php echo strtolower(THEME_NAME); ?>/core/sample/plugins</i>
								
							</p>
						</div>
											
						<div class="p">
							<p>
								<input type="submit" id="submitbtn" value="Import Data Sample" class="btn submit-btn" />
								<h3 id="imp-notice">
									<img src="<?php echo THEME_URI; ?>/core/assets/images/loading.gif" /> 
									Please don't navigate away while importing
									<br />
									<span style="font-size: 10px;float: right;margin: 5px 7px 0 0;">It may take up to 10 minutes</span>
								</h3>
								
								<input type="hidden" value="1" name="importSampleData" />
							</p>
						</div>
					</form>		
					<?php } ?>
				</section><!-- /content -->
				

			</div><!-- /row -->
	
			<div class="row">
	
			<section class="col-md-12">
				
				<div class="footer">

					<?php echo THEME_NAME; ?> version <?php global $king_options; echo KING_VERSION; ?> &copy; by King-Theme
					|  Question? <a href="<?php echo esc_url( 'http://help.king-theme.com' ); ?>">help.king-theme.com</a>
					
					<a onclick="clearTimeout(countdownTimer)" class="pull-right link btn btn-default" class="btn btn-default" href="<?php echo admin_url('admin.php?page='.strtolower(THEME_NAME).'-panel'); ?>">
						No, Thanks! &nbsp; <i class="fa fa-sign-out"></i>
					</a>
					
				</div>

			</section><!-- /subscribe -->
			
			</div><!-- /row -->

	  </section>
</div>		
<script type="text/javascript">


	function doSubmit( form ){
		var btn = document.getElementById('submitbtn');
		btn.className+=' disable';
		btn.disabled=true;
		btn.value='Importing.....';
		document.getElementById('imp-notice').style.display = 'block';
	}
	function doSubmit2(){
		document.getElementById('pluginsOnly').value = 'ON';
		document.getElementById('submitbtn').click();
	}
	var countdown = document.getElementById('countdown');
	var countdownTimer = null;
	if( countdown ){
		
		function count_down( second ){
			
			second--;
			countdown.innerHTML = second;
			if(second>0){
				countdownTimer = setTimeout('count_down('+second+')', 1000);
			}else{
				window.location = '<?php echo SITE_URL; ?>';
			}	
		}

		count_down( 10 );
		
	}
	
	
	
</script>  