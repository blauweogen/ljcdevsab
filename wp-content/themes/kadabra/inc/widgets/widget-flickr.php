<?php

//FLICKR FEED
class crum_widget_flickr extends WP_Widget {
	function crum_widget_flickr() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'instagram-widget', 'description' => __( 'Displays your Flickr feed', 'dfd') );
		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'crum_widget_flickr' );
		/* Create the widget. */
		$this->WP_Widget( 'crum_widget_flickr', 'Widget: Flickr Feed', $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {

	extract( $args );

	/* User-selected settings. */
	 $title = $instance['title'] ;
     $id = $instance['id'];
	 $num = $instance['num'];


     wp_register_script('flikr_feed', get_template_directory_uri() . '/assets/js/jflickrfeed.min.js', false, null, true);
     wp_enqueue_script('flikr_feed');


  /* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
        if ($title) {

            echo $before_title;
            echo $title;
            echo $after_title;

        }

		/* Display Latest Tweets */
		if ( $num ) { ?>
        <div id="flickr"></div>

        <script type="text/javascript">
            <!--
            jQuery(document).ready(function() {
                jQuery('#flickr').jflickrfeed({
                    limit: <?php echo $num; ?>,
                    qstrings: {
                        id: '<?php echo $id; ?>'
                    },
                    itemTemplate:
                            '<a class="zoom" data-rel="prettyPhoto[flikr_gal]" href="{{image}}" title="{{title}}">' +
                                '<img src="{{image_q}}"  />' +
                                '<span class="hover-box"><span class="zoom-link"></span></span>' +
                            '</a>'

                }, function(data) {
                    jQuery('#flickr a').prettyPhoto();

                });
            });
            // -->
        </script>


		<?php }

		/* After widget (defined by themes). */
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = $new_instance['title'];

		$instance['num'] = strip_tags( $new_instance['num'] );
		$instance['id'] = strip_tags( $new_instance['id'] );

		return $instance;
	}
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Flickr Photos',  'id'=>'31472375@N06', 'num' => '4' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>


    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'dfd'); ?></label>
        <input class="widefat"  type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"/>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('ID:', 'dfd'); ?></label>
        <input class="widefat"  type="text" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $instance['id']; ?>"/>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of photos:', 'dfd'); ?></label>
        <input class="widefat"  type="text" id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num'); ?>" value="<?php echo $instance['num']; ?>"/>
    </p>

        <?php
	}
}
?>