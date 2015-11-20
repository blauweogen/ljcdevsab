<?php
require_once(dirname(__FILE__).'/widget.php');

class dfd_testimonails extends SB_WP_Widget {
	protected $widget_base_id = 'dfd_testimonails';
	protected $widget_name = 'Widget: Testimonials';
	
	protected $options;

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
		$this->widget_params = array(
			'description' => __('Last Testimonials', 'dfd'),
		);
		
		$this->options = array(
			array(
				'title', 'text', '', 
				'label' => __('Title', 'dfd'), 
				'input'=>'text', 
				'filters'=>'widget_title', 
				'on_update'=>'esc_attr',
			),
		);
		
        parent::__construct();
    }

    /**
     * Display widget
     */
    function widget( $args, $instance ) {
        extract( $args );
		$this->setInstances($instance, 'filter');
		
        echo $before_widget;

		$title = $this->getInstance('title');
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
		}
		
		 $args = array(
			'numberposts' => 1,
			'post_type' => 'testimonials',
		);

		$testimonials = get_posts( $args );
	?>

	<?php foreach ($testimonials as $testimonial): ?>
	<?php
		$testimonial_autor = get_post_meta($testimonial->ID, 'crum_testimonial_autor', true);
		$testimonial_additional = get_post_meta($testimonial->ID, 'crum_testimonial_additional', true);
		//class="testimonials_item"
		
	?>
		<blockquote>
			<?php echo $testimonial->post_excerpt; ?>
		</blockquote>
		<div class="cite">
			<?php if (!empty($testimonial_autor)): //quote-author box-name ?>
				<div class="block-title"><?php echo $testimonial_autor; ?></div>
			<?php endif; ?>

			<?php if (!empty($testimonial_additional)): ?>
				<span class="subtitle"><?php echo $testimonial_additional; ?></span>
			<?php endif; ?>
		</div>

	<?php endforeach; wp_reset_postdata(); ?>

    <?php

        echo $after_widget;
    }

}
