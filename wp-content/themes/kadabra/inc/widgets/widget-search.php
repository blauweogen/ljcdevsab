<?php

class crum_search_widget extends WP_Widget {

	function crum_search_widget() {

		/* Widget settings. */

		$widget_ops = array('classname' => 'search-widget', 'description' => __('Search widget', 'dfd'));

		/* Widget control settings. */

		$control_ops = array('id_base' => 'crum_search_widget');

		/* Create the widget. */

		$this->WP_Widget('crum_search_widget', 'Widget: Search widget', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {

		//get theme options

		if (isset($instance['title'])) {

			$title = $instance['title'];
		}

		if (isset($instance['text'])) {

			$text = $instance['text'];
		}

		extract($args);


		/* show the widget content without any headers or wrappers */

		echo $before_widget;

		if ($title) {

			echo $before_title;
			echo $title;
			echo $after_title;
		}
		?>

		<?php $id_s = uniqid('s_'); ?>
		<form role="search" method="get" id="<?php echo uniqid('searchform_'); ?>" class="form-search" action="<?php echo home_url('/'); ?>">
			<label class="hide" for="<?php echo $id_s; ?>"><?php _e('Search for:', 'dfd'); ?></label>
			<input type="text" value="" name="s" id="<?php echo $id_s; ?>" class="s-field" placeholder="<?php echo $text; ?>">
			<input type="submit" value=" " class="s-submit">
		</form>

		<?php
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		/* Strip tags (if needed) and update the widget settings. */

		$instance['text'] = strip_tags($new_instance['text']);


		return $instance;
	}

	function form($instance) {

		$title = apply_filters('widget_title', $instance['title']);

		$text = $instance['text'];

		/* Set up some default widget settings. */

		$defaults = array('text' => 'Enter request...');

		$instance = wp_parse_args((array) $instance, $defaults);
		?>


		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'dfd'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Placeholder', 'dfd'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($text); ?>"/>
		</p>

		<?php
	}

}
