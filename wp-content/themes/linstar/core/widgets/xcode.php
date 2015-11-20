<?php

/*
*	(c) king-theme.com
*/

class PHP_Code_Widget extends WP_Widget {

	function PHP_Code_Widget() {
		$widget_ops = array(	
						'classname'		=> 'widget_execphp', 
						'description'	=> __('Arbitrary text, HTML, Javascript, CSS and PHP Code',KING_DOMAIN)
					);
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('execphp', __('Code',KING_DOMAIN), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
	
		global $king;
		extract($args);
	
		$title		= apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$showTitle	= empty($instance['showTitle']) ? 'true' : $instance['showTitle'];
		$class		= empty($instance['class']) ? '' : $instance['class'];
		$text		= empty($instance['data']) ? '' : $instance['data'];
		
		if( empty( $instance['filter'] ) ){
			$instance['filter'] = '';
		}
		
		if( !empty( $instance['class'] ) ){
			$before_widget = str_replace( 'class="', 'class="'.$class.' ', $before_widget );
		}
		
		print( $before_widget );

		if( $showTitle == 'true' ){
			print( $before_title.$title.$after_title );
		}
		
		ob_start();
		
			$testExe = $king->ext['ev']( '?>'.stripslashes( $text ) );
			
			if( $testExe === false ){
				echo '<font color="red">PHP Parse Error</font>';
			}	
			$text =  do_shortcode( ob_get_contents() );

		ob_end_clean();
		?>			
			<div class="execphpwidget">
				<?php print( !empty($text) ? $text : '' ) ?>
			</div>
		<?php
		
		print( $after_widget );
		
	}

	function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['showTitle']	= strip_tags(!empty($new_instance['showTitle'])?$new_instance['showTitle']:'');
		$instance['class']		= strip_tags(!empty($new_instance['class'])?$new_instance['class']:'');
		$instance['data']		= !empty($new_instance['data'])?$new_instance['data']:'';

		return $instance;
		
	}

	function form( $instance ) {
				
		$instance	= wp_parse_args( (array) $instance, array('title'=>'','id'=>'','showTitle'=>'true','class' =>'','data'=>''));
		$title		= strip_tags($instance['title']);
		$showTitle	= strip_tags($instance['showTitle']);
		$class		= strip_tags($instance['class']);
		$data		= $instance['data'];

	?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
				<?php _e( 'Title:', KING_DOMAIN ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('showTitle') ); ?>">
				<?php _e( 'Show Title:', KING_DOMAIN ); ?>
			</label>
			Yes <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('showTitle') ); ?>" name="<?php echo esc_attr( $this->get_field_name('showTitle') ); ?>" type="radio" value="true" <?php if( $showTitle == 'true' )echo 'checked'; ?> /> &nbsp; 
			No <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('showTitle') ); ?>" name="<?php echo esc_attr( $this->get_field_name('showTitle') ); ?>" type="radio" value="false" <?php if( $showTitle == 'false' )echo ' checked'; ?> />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('class') ); ?>">
				<?php _e( 'Custom Class:', KING_DOMAIN ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('class') ); ?>" name="<?php echo esc_attr( $this->get_field_name('class') ); ?>" type="text" value="<?php echo esc_attr($class); ?>" />
		</p>
		<textarea class="widefat phpxcode" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id('data') ); ?>" name="<?php echo esc_attr( $this->get_field_name('data') ); ?>"><?php echo htmlspecialchars( $data ); ?></textarea>

		
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("PHP_Code_Widget");'));

