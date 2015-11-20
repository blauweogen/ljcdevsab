<?php
require_once(dirname(__FILE__).'/widget.php');

class dfd_vcard_simple extends SB_WP_Widget {
	protected $widget_base_id = 'dfd_vcard_simple';
	protected $widget_name = 'Widget: vCard Simple';
	
	protected $options;
	
    public function __construct() {
		# Stup description
		$this->widget_params = array(
			'description' => __('Use this widget to add a simple vCard', 'dfd'),
		);
		
		$this->options = array(
			array(
				'title', 'text', '', 
				'label' => __('Title', 'dfd'), 
				'input'=>'text', 
				'filters'=>'widget_title', 
				'on_update'=>'esc_attr',
			),
			array(
				'address', 'text', '',
				'label' => __('Address', 'dfd'),
			),
			array(
				'phones', 'text', '',
				'label' => __('Phones', 'dfd'),
			),
			array(
				'display_email_as_link', 'text', '',
				'label' => __('Display Email as link', 'dfd'),
				'input' => 'checkbox',
			),
			array(
				'email', 'text', '',
				'label' => __('Email', 'dfd'),
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
		
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
		}
		
		$address = $this->getInstance('address');
		$phones = $this->getInstance('phones');
		$display_email_as_link = $this->getInstance('display_email_as_link');
		$email = $this->getInstance('email');
		?>

		<p>
		<?php if (!empty($address)): ?>
			<?php _e('Address', 'dfd'); ?>:
			<?php echo $address; ?> <br />
		<?php endif; ?>
		
		<?php if (!empty($phones)): ?>
			<?php _e('Phone', 'dfd'); ?>:
			<?php echo $phones; ?><br />
		<?php endif; ?>
		
		<?php if (!empty($email)): ?>
			<?php if (!empty($display_email_as_link)): ?>
				<a href="mailto:<?php echo trim($email); ?>" title="" ><?php echo $email; ?></a>
			<?php else: ?>
				<?php _e('Email', 'dfd'); ?>:
				<?php echo $email; ?>
			<?php endif; ?><br />
		<?php endif; ?>
		</p>
		
		<?php
		
		echo $after_widget;
    }
	
}
