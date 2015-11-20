<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'crum_portfolio_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */

function crum_portfolio_metaboxes( array $meta_boxes ) {
	
	// Start with an underscore to hide fields from custom fields list
	$prefix = 'folio_';
	$portfolio_templates = array(
		'tmp-portfolio-masonry-1-bordered.php',
		'tmp-portfolio-masonry-1.php',
		'tmp-portfolio-masonry-4.php',
		'tmp-portfolio-masonry-4excerpt.php',
		'tmp-portfolio-masonry-4mini.php',
		'tmp-portfolio-masonry-full-width-bordered.php',
		'tmp-portfolio-masonry-full-width.php',
		'tmp-portfolio-masonry-sidebar.php',
		'tmp-portfolio-masonry-sidebar_excerpt.php',
		'tmp-portfolio-masonry-sidebar_mini.php',
		'tmp-portfolio-masonry.php',
		'tmp-portfolio-masonry_excerpt.php',
		'tmp-portfolio-masonry_mini.php',
		'tmp-portfolio-template-1-right-sidebar.php',
		'tmp-portfolio-template-1.php',
		'tmp-portfolio-template-2-right-sidebar.php',
		'tmp-portfolio-template-2.php',
		'tmp-portfolio-template-2excerpt.php',
		'tmp-portfolio-template-2mini.php',
		'tmp-portfolio-template-3-right-sidebar.php',
		'tmp-portfolio-template-3.php',
		'tmp-portfolio-template-3excerpt.php',
		'tmp-portfolio-template-3mini.php',
		'tmp-portfolio-template-4.php',
		'tmp-portfolio-template-4excerpt.php',
		'tmp-portfolio-template-4mini.php',
	);

    $meta_boxes[] = array(
        'id'         => 'portfolio-page-options',
        'title'      => __('Select portfolio parameters', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array(
			'key' => 'page-template',
			'value' => $portfolio_templates,
		),
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => 'Display  of certain category?',
                'desc' => 'Check, if you want to display items from a certain category',
                'id'   => $prefix . 'sort_category',
                'type' => 'checkbox'
            ),
            array(
                'name' => 'Portfolio Category',
                'desc'	=> 'Select portfolio items category',
                'id'	=> $prefix . 'category',
                'taxonomy' => 'my-product_category',
                'type' => 'taxonomy_multicheck',
            ),
            array(
                'name' => __('Number of items', 'dfd'),
                'desc' => __('Number of portfolio items that will be show on page', 'dfd'),
                'id' => $prefix . 'number_to_display',
                'type' => 'text'
            ),
        ),
    );

	$meta_boxes[] = array(
		'id'         => $prefix.'_video',
		'title'      => __('Portfolio Video', 'dfd'),
		'pages'      => array('my-product'), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
            array(
                'name' => 'oEmbed',
                'desc' => 'Enter a Youtube, Vimeo, Twitter, or Instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.',
                'id'   => $prefix . 'embed',
                'type' => 'oembed',
            ),
		),
	);
	
	$meta_boxes[] = array(
		'id' => $prefix . '_number_to_display',
		'title' => __('Number to display', 'dfd'),
		'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array(
			'key' => 'page-template',
			'value' => $portfolio_templates,
		),
		'show_names' => true,
		'fields' => array(
			array(
				'name' => 'Number to display',
				'desc' => '',
				'id' => 'folio_number_to_display',
				'type' => 'text',
				'std' => '16',
			),
		),
	);
	
	$meta_boxes[] = array(
		'id'         => $prefix.'_template',
		'title'      => __('Portfolio Template', 'dfd'),
		'pages'      => array('my-product'), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
           array(
                'name' => 'Select template',
                'desc' => '',
                'id' =>  $prefix.'inside_template',
                'type' => 'select',   
                'std'  => 'folio_inside_1',
                'options'  => array(
					array(
						'value' => 'folio_inside_1',
						'name' => __('Portfolio inside 1 variant', 'dfd'),
					),
					array(
						'value' => 'folio_inside_2',
						'name' => __('Portfolio inside 2 variant', 'dfd'),
					),
				),
            ),
		),
	);

 	// Add other metaboxes as needed

	return $meta_boxes;
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function cr_portfolio_add_custom_box() {

    $screens = array( 'my-product' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'crumina_portfolio_gallery',
            __( 'Images gallery', 'dfd' ),
            'cr_portfolio_inner_custom_box',
            $screen,
            'side'
        );
    }
}
add_action( 'add_meta_boxes', 'cr_portfolio_add_custom_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function cr_portfolio_inner_custom_box( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'cr_portfolio_inner_custom_box', 'cr_portfolio_inner_custom_box_nonce' );


    ?>

    <div id="my_product_images_container">
        <ul class="my_product_images">
            <?php
            if ( metadata_exists( 'post', $post->ID, '_my_product_image_gallery' ) ) {
                $my_product_image_gallery = get_post_meta( $post->ID, '_my_product_image_gallery', true );
            } else {
                // Backwards compat
                $attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' );
                $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
                $my_product_image_gallery = implode( ',', $attachment_ids );
            }

            $attachments = array_filter( explode( ',', $my_product_image_gallery ) );

            if ( $attachments )
                foreach ( $attachments as $attachment_id ) {
                    echo '<li class="image" data-attachment_id="' . $attachment_id . '">
								' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', 'dfd' ) . '">' . __( 'Delete', 'dfd' ) . '</a></li>
								</ul>
							</li>';
                }
            ?>
        </ul>

        <input type="hidden" id="my_product_image_gallery" name="my_product_image_gallery" value="<?php echo esc_attr( $my_product_image_gallery ); ?>" />

    </div>
    <p class="add_my_product_images hide-if-no-js">
        <a class="button" href="#"><?php _e( 'Add gallery images', 'dfd' ); ?></a>
    </p>
    <script type="text/javascript">
        jQuery(document).ready(function($){

            // Uploading files
            var my_product_gallery_frame;
            var $image_gallery_ids = $('#my_product_image_gallery');
            var $my_product_images = $('#my_product_images_container ul.my_product_images');

            jQuery('.add_my_product_images').on( 'click', 'a', function( event ) {

                var $el = $(this);
                var attachment_ids = $image_gallery_ids.val();

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( my_product_gallery_frame ) {
                    my_product_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                my_product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php _e( 'Add Images to Product Gallery', 'dfd' ); ?>',
                    button: {
                        text: '<?php _e( 'Add to gallery', 'dfd' ); ?>'
                    },
                    multiple: true
                });

                // When an image is selected, run a callback.
                my_product_gallery_frame.on( 'select', function() {

                    var selection = my_product_gallery_frame.state().get('selection');

                    selection.map( function( attachment ) {

                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                            $my_product_images.append('\
									<li class="image" data-attachment_id="' + attachment.id + '">\
										<img src="' + attachment.url + '" />\
										<ul class="actions">\
											<li><a href="#" class="delete" title="<?php _e( 'Delete image', 'dfd' ); ?>"><?php _e( 'Delete', 'dfd' ); ?></a></li>\
										</ul>\
									</li>');
                        }

                    } );

                    $image_gallery_ids.val( attachment_ids );
                });

                // Finally, open the modal.
                my_product_gallery_frame.open();
            });

            // Image ordering
            $my_product_images.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity:40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'wc-metabox-sortable-placeholder',
                start:function(event,ui){
                    ui.item.css('background-color','#f6f6f6');
                },
                stop:function(event,ui){
                    ui.item.removeAttr('style');
                },
                update: function(event, ui) {
                    var attachment_ids = '';

                    $('#my_product_images_container ul li.image').css('cursor','default').each(function() {
                        var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );
                }
            });

            // Remove images
            $('#my_product_images_container').on( 'click', 'a.delete', function() {

                $(this).closest('li.image').remove();

                var attachment_ids = '';

                $('#my_product_images_container ul li.image').css('cursor','default').each(function() {
                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids.val( attachment_ids );

                return false;
            } );

        });
    </script>


<?php

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function cr_portfolio_save_postdata( $post_id ) {

    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['cr_portfolio_inner_custom_box_nonce'] ) )
        return $post_id;

    $nonce = $_POST['cr_portfolio_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'cr_portfolio_inner_custom_box' ) )
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) )
            return $post_id;

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    $mydata = $_POST['my_product_image_gallery'];

    // Update the meta field in the database.
    update_post_meta( $post_id, '_my_product_image_gallery', $mydata );
}
add_action( 'save_post', 'cr_portfolio_save_postdata' );


