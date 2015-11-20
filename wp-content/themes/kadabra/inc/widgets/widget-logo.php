<?php
require_once(dirname(__FILE__).'/widget.php');

class dfd_logo extends SB_WP_Widget {
	protected $widget_base_id = 'dfd_logo';
	protected $widget_name = 'Widget: Logo';
	
	protected $options;
	
	public function __construct() {
		# Stup description
		$this->widget_params = array(
			'description' => __('Display site logo.', 'dfd'),
		);
		
		$this->options = array(
	//		array(
	//			'title', 'text', '', 
	//			'label' => 'Title', 
	//			'input'=>'text', 
	//			'filters'=>'widget_title', 
	//			'on_update'=>'esc_attr',
	//		),
		);
		
        parent::__construct();
    }
	
	function widget( $args, $instance ) {
        extract( $args );
		$this->setInstances($instance, 'filter');
		
        echo $before_widget;

		if (DfdThemeSettings::get('custom_logo_image')) {
		?>
			<div class="logo">
				<a href="<?php echo home_url(); ?>/">
					<img src="<?php echo DfdThemeSettings::get('custom_logo_image'); ?>" alt="<?php bloginfo('name'); ?>">
				</a>
			</div>
		<?php
		}
		
		echo $after_widget;
	}
}
