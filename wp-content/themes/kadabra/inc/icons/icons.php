<?php

	/*	
	*	---------------------------------------------------------------------
	*	Compatibility mode
	*	Set to TRUE to enable compatibility mode - [v_icon]
	*	--------------------------------------------------------------------- 
	*/

	define( 'VI_SAFE_MODE', apply_filters( 'vi_safe_mode', FALSE ) );
	
	
	/* Setup perfix */
	function crum_i_compatibility_mode() {
		$prefix = ( VI_SAFE_MODE == true ) ? 'v_' : '';
		return $prefix;
	}

	

	/*	
	*	---------------------------------------------------------------------
	*	Setup plugin
	*	--------------------------------------------------------------------- 
	*/
	add_action('after_setup_theme', 'dfd_kadabra_i_plugin_init');
	
	if (!function_exists('dfd_kadabra_i_plugin_init')) {
		function dfd_kadabra_i_plugin_init() {
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 'dfd_kadabra_i_plugin_scripts', 1);
			// Enqueue admin scripts and styles
			add_action('admin_enqueue_scripts', 'dfd_kadabra_i_plugin_admin_scripts');
		}
	}
	
	if (!function_exists('dfd_kadabra_i_plugin_scripts')) {
		function dfd_kadabra_i_plugin_scripts() {
			wp_register_style('icon-font-style', get_template_directory_uri() . '/inc/icons/css/icon-font-style.css', false, '', 'all' );
			wp_enqueue_style('icon-font-style');
		}
	}
	
	if (!function_exists('dfd_kadabra_i_plugin_admin_scripts')) {
		function dfd_kadabra_i_plugin_admin_scripts() {
			wp_register_style( 'icon-font-style', get_template_directory_uri() . '/inc/icons/css/icon-font-style.css', false, '', 'all' );
			wp_register_style( 'mnky-icon-generator', get_template_directory_uri() . '/inc/icons/css/generator.css', false, '', 'all' );
			wp_register_script( 'mnky-icon-generator', get_template_directory_uri() . '/inc/icons/js/generator.js', array( 'jquery' ), '', false );
			
			wp_enqueue_style( 'icon-font-style' );
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_style( 'mnky-icon-generator' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'farbtastic' );		
			wp_enqueue_script( 'mnky-icon-generator' );
		}
	}
	
	/*	
	*	---------------------------------------------------------------------
	*	Plugin URL
	*	--------------------------------------------------------------------- 
	*/
	
	function crum_i_plugin_url() {
		return locate_template('/inc/icons/icons.php');
    }

	/*
	*	---------------------------------------------------------------------
	*	Icon generator box
	*	---------------------------------------------------------------------
	*/

	function crum_i_generator() {

		include_once 'inc/list.php'; ?>
		<div id="mnky-generator-overlay" class="mnky-overlay-bg" style="display:none"></div>
		<div id="mnky-generator-wrap" style="display:none">
			<div id="mnky-generator">
				<a href="#" id="mnky-generator-close"><span class="mnky-close-icon"></span></a>
				<div id="mnky-generator-shell">

					<table border="0">
						<tr>
							<td class="generator-title">
								<span>Icon pack:</span>
							</td>
							<td>
								<select name="icon-pack" id="mnky-generator-select-pack">
									<?php foreach($dfd_i_icon_list as $pack_name=>$icons_list): ?>
									<option value="<?php echo $pack_name; ?>-icon-list"><?php echo ucfirst($pack_name);?> icons</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					</table>
					
					<div class="mnky-generator-icon-select">
						<?php $i=0; foreach($dfd_i_icon_list as $pack_name=>$icons_list): ?>
						<ul class="<?php echo $pack_name; ?>-icon-list ul-icon-list" <?php if ($i>0): ?>style="display:none"<?php endif; ?>>
							<?php
                            foreach ( $icons_list as $icon_class ) {
                                $selected_icon = ( 'linecons-adjust' == $icon_class ) ? ' checked' : '';
                                echo '<li><input name="name" type="radio" value="' . $icon_class . '" id="' . $icon_class . '" '. $selected_icon .' ><label for="' . $icon_class . '"><i class="' . $icon_class . '"></i></label></li>';
                            }
                            ?>
						</ul>
						<?php $i++; endforeach; /* ?>
                        <ul class="linecons-icon-list">
                            <?php
                            foreach ( $dfd_i_icon_list['linecons'] as $linecons_icon ) {
                                $selected_icon = ( 'linecons-adjust' == $linecons_icon ) ? ' checked' : '';
                                echo '<li><input name="name" type="radio" value="' . $linecons_icon . '" id="' . $linecons_icon . '" '. $selected_icon .' ><label for="' . $linecons_icon . '"><i class="' . $linecons_icon . '"></i></label></li>';
                            }
                            ?>
                        </ul>
						<ul class="moon-icon-list" style="display:none">
						<?php 
						foreach ( $dfd_i_icon_list['moon'] as $moon_icon ) {
							echo '<li><input name="name" type="radio" value="' . $moon_icon . '" id="' . $moon_icon . '"><label for="' . $moon_icon . '"><i class="' . $moon_icon . '"></i></label></li>';
						} 
						?>
						</ul>
						<?php */ ?>
					</div>

					<input name="mnky-generator-insert" type="submit" class="button button-primary button-large" id="mnky-generator-insert" value="Insert Icon">
				</div>
			</div>
		</div>
		
	<?php
	}

	add_action( 'admin_footer', 'crum_i_generator' );

?>