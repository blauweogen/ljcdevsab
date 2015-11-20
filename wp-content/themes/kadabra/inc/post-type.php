<?php

/*
 * Portfolio taxonomy
 */
function my_custom_post_product() {
    $labels = array(
        'name'               => __( 'Portfolios' , 'dfd' ),
        'singular_name'      => __( 'Portfolio' , 'dfd' ),
        'add_new'            => __( 'Add New' , 'dfd' ),
        'add_new_item'       => __( 'Add New Portfolio item' , 'dfd' ),
        'edit_item'          => __( 'Edit Portfolio item' , 'dfd' ),
        'new_item'           => __( 'New Portfolio item' , 'dfd' ),
        'all_items'          => __( 'All Portfolio items' , 'dfd' ),
        'view_item'          => __( 'View Portfolio item' , 'dfd' ),
        'search_items'       => __( 'Search Portfolios item' , 'dfd' ),
        'not_found'          => __( 'No products found' , 'dfd' ),
        'not_found_in_trash' => __( 'No products found in the Trash' , 'dfd' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Portfolios'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our products and product specific data',
        'public'        => true,
        'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'tags', 'sticky' ),
        'has_archive'   => true,
        'menu_icon' => get_stylesheet_directory_uri() . '/assets/images/portfolio-icon.png', /* the icon for the custom post type menu */
        'taxonomies'    => array('post_tag')
    );
    register_post_type( 'my-product', $args );
}
add_action( 'init', 'my_custom_post_product' );

function my_updated_messages( $messages ) {
    global $post, $post_ID;
    $messages['my-product'] = array(
        0 => '',
        1 => sprintf( __('Portfolio updated. <a href="%s">View product</a>', 'dfd'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.', 'dfd'),
        3 => __('Custom field deleted.', 'dfd'),
        4 => __('Portfolio updated.', 'dfd'),
        5 => isset($_GET['revision']) ? sprintf( __('Portfolio restored to revision from %s', 'dfd'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Portfolio published. <a href="%s">View product</a>', 'dfd'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Portfolio saved.', 'dfd'),
        8 => sprintf( __('Portfolio submitted. <a target="_blank" href="%s">Preview product</a>', 'dfd'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>', 'dfd'), date_i18n( __( 'M j, Y @ G:i', 'dfd' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Portfolio draft updated. <a target="_blank" href="%s">Preview product</a>', 'dfd'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'my_updated_messages' );

function my_contextual_help( $contextual_help, $screen_id, $screen ) {
    if ( 'my-product' == $screen->id ) {

        $contextual_help = '<h2>Portfolios</h2>
		<p>Portfolios show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p>
		<p>You can view/edit the details of each product by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';

    } elseif ( 'edit-product' == $screen->id ) {

        $contextual_help = '<h2>Editing products</h2>
		<p>This page allows you to view/modify product details. Please make sure to fill out the available boxes with the appropriate details (product image, price, brand) and <strong>not</strong> add these details to the product description.</p>';

    }
    return $contextual_help;
}
add_action( 'contextual_help', 'my_contextual_help', 10, 3 );

function my_taxonomies_product() {
    $labels = array(
        'name'              => __( 'Portfolio Categories', 'dfd' ),
        'singular_name'     => __( 'Portfolio Category', 'dfd' ),
        'search_items'      => __( 'Search Portfolio Categories', 'dfd' ),
        'all_items'         => __( 'All Portfolio Categories', 'dfd' ),
        'parent_item'       => __( 'Parent Portfolio Category', 'dfd' ),
        'parent_item_colon' => __( 'Parent Portfolio Category:', 'dfd' ),
        'edit_item'         => __( 'Edit Portfolio Category', 'dfd' ),
        'update_item'       => __( 'Update Portfolio Category', 'dfd' ),
        'add_new_item'      => __( 'Add New Portfolio Category', 'dfd' ),
        'new_item_name'     => __( 'New Portfolio Category', 'dfd' ),
        'menu_name'         => __( 'Portfolio Categories', 'dfd' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,

    );
    register_taxonomy( 'my-product_category', 'my-product', $args );
}
add_action( 'init', 'my_taxonomies_product', 0 );


function dfd_time_line() {
    $labels = array(
        'name' => __('Time Line', 'dfd'),
        'singular_name' => __('Time Line', 'dfd'),
        'add_new_item' => __('Add New Time Line Item', 'dfd'),
        'edit_item' => __('Edit Time Line Item', 'dfd'),
        'search_items' => __('Search Time Line Items', 'dfd'),
        'not_found' => __('Sorry: Time Line Item Not Found', 'dfd'),
        'not_found_in_trash' => __('Sorry: Time Line Item Not Found In Trash', 'dfd'),
    );
    $args = array(
        'labels' => $labels,
        'rewrite' => false,
        'public' => true,
        'hierarchical' => 'false',
        'capability_type' => 'page',
        'supports' => array(
			'title', 
			'editor', 
			'page-attributes',
			'custom-fields',
		),
        'menu_icon' => get_stylesheet_directory_uri() . '/assets/images/menu-icon.png', /* the icon for the custom post type menu */
        'has_archive' => false,
    );
    register_post_type( 'timeline', $args );
}
add_action('init', 'dfd_time_line');


function dfd_testimonials() {
	 $labels = array(
        'name' => __('Testimonials', 'dfd'),
        'singular_name' => __('Testimonial', 'dfd'),
        'add_new_item' => __('Add New Testimonial Item', 'dfd'),
        'edit_item' => __('Edit Testimonial Item', 'dfd'),
        'search_items' => __('Search Testimonial Items', 'dfd'),
        'not_found' => __('Sorry: Testimonial Item Not Found', 'dfd'),
        'not_found_in_trash' => __('Sorry: Testimonial Item Not Found In Trash', 'dfd'),
    );
    $args = array(
        'labels' => $labels,
        'rewrite' => false,
        'public' => true,
        'hierarchical' => 'false',
        'capability_type' => 'page',
        'supports' => array(
			'excerpt',
			'thumbnail'
		),
        'menu_icon' => get_stylesheet_directory_uri() . '/assets/images/menu-icon.png', /* the icon for the custom post type menu */
        'has_archive' => false,
    );
	
	register_post_type( 'testimonials', $args );
}
add_action('init', 'dfd_testimonials');


