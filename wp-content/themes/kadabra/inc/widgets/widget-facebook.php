<?php

class crum_widget_facebook extends WP_Widget {

	public function __construct() {

		parent::__construct(
				'facebook_widget', // Base ID
				'Widget: Facebook widget', // Name
				array('description' => __('Facebook Social Network widget', 'dfd'),) // Args
		);
	}

	public function form($instance) {

		if (isset($instance['title'])) {

			$title = $instance['title'];
		} else {

			$title = __('Facebook widget', 'dfd');
		}


		if (isset($instance['width'])) {

			$width = $instance['width'];
		} else {

			$width = 300;
		}



		if (isset($instance['color'])) {

			$color = $instance['color'];
		} else {

			$color = 'dark';
		}


		if (isset($instance['stream'])) {
			$stream = $instance['stream'];
		} else {

			$stream = 'false';
		}



		if (isset($instance['faces'])) {

			$faces = $instance['faces'];
		} else {

			$faces = 'true';
		}



		if (isset($instance['url'])) {
			$url = $instance['url'];
		} else {
			$url = 'platform';
		}



		if (isset($instance['header'])) {
			$header = $instance['header'];
		} else {
			$header = 'false';
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'dfd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>

			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Facebook Name: ( facebook.com/ * Type into field * )', 'dfd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" />

		</p>

		<p>

			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width(px):', 'dfd'); ?></label>

			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" />

		</p>


		<p>

			<label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color scheme:', 'dfd'); ?></label>

			<select id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>  value="<?php echo esc_attr($color); ?>" >

				<option value ='light' <?php if (esc_attr($color) == 'light') echo 'selected'; ?>>Light</option>

				<option value = 'dark' <?php if (esc_attr($color) == 'dark') echo 'selected'; ?>>Dark</option>

			</select>


		</p>

		<p>

			<label for="<?php echo $this->get_field_id('stream'); ?>"><?php _e('Show stream:', 'dfd'); ?></label>

			<select id="<?php echo $this->get_field_id('stream'); ?>" name="<?php echo $this->get_field_name('stream'); ?>  value="<?php echo esc_attr($stream); ?>" >

				<option value ='true' <?php if (esc_attr($stream) == 'true') echo 'selected'; ?>>Yes</option>

				<option value = 'false' <?php if (esc_attr($stream) == 'false') echo 'selected'; ?>>No</option>

			</select>

		</p>

		<p>

			<label for="<?php echo $this->get_field_id('faces'); ?>"><?php _e('Show faces:', 'dfd'); ?></label>

			<select id="<?php echo $this->get_field_id('faces'); ?>" name="<?php echo $this->get_field_name('faces'); ?>  value="<?php echo esc_attr($faces); ?>" >

				<option value ='true' <?php if (esc_attr($faces) == 'true') echo 'selected'; ?>>Yes</option>

				<option value = 'false' <?php if (esc_attr($faces) == 'false') echo 'selected'; ?>>No</option>

			</select>

		</p>

		<p>

			<label for="<?php echo $this->get_field_id('header'); ?>"><?php _e('Show header:', 'dfd'); ?></label>

			<select id="<?php echo $this->get_field_id('header'); ?>" name="<?php echo $this->get_field_name('header'); ?>  value="<?php echo esc_attr($header); ?>" >

				<option value ='true' <?php if (esc_attr($header) == 'true') echo 'selected'; ?>>Yes</option>

				<option value = 'false' <?php if (esc_attr($header) == 'false') echo 'selected'; ?>>No</option>

			</select>

		</p>


		<?php
	}

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = strip_tags($new_instance['title']);

		$instance['color'] = strip_tags($new_instance['color']);

		$instance['stream'] = strip_tags($new_instance['stream']);

		$instance['width'] = strip_tags($new_instance['width']);

		$instance['faces'] = strip_tags($new_instance['faces']);

		$instance['url'] = strip_tags($new_instance['url']);

		$instance['header'] = strip_tags($new_instance['header']);

		return $instance;
	}

	public function widget($args, $instance) {
		wp_enqueue_script('dfd_facebook_widget_script');
		
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		$width = $instance['width'];

		$color = $instance['color'];

		$stream = $instance['stream'];

		$faces = $instance['faces'];

		$url = $instance['url'];

		$header = $instance['header'];

		echo $before_widget;

		if ($title) {

			echo $before_title;
			echo $title;
			echo $after_title;
		}
		?>

		<div class="facebookOuter">
			<div class="facebookInner">
				<div class="fb-like-box"
					 data-width="<?php echo $width; ?>" data-height="255"
					 data-href="http://www.facebook.com/<?php echo $url; ?>"
					 data-colorscheme="<?php echo $color; ?>"
					 data-show-border="false"
					 data-show-faces="<?php echo $faces; ?>"
					 data-stream="<?php echo $stream; ?>" data-header="<?php echo $header; ?>">
				</div>
			</div>
		</div>

		<div id="fb-root"></div>

		<?php
		echo $after_widget;
	}

}
