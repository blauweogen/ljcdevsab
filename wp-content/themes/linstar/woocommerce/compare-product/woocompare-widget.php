<?php
/**
 * Main class
 *
 */

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'king_Woocompare_Widget' ) ) {


    class king_Woocompare_Widget extends WP_Widget {

        function __construct() {
            $widget_ops = array('classname' => 'king-woocompare-widget', 'description' => __( 'The widget show the list of products added in the compare table.', 'king') );
            parent::__construct('king-woocompare-widget', __('DEVN Woo Compare Widget', 'king'), $widget_ops);
        }


        function widget( $args, $instance ) {
            global $king_woocompare;

            /**
             * WPML Support
             */
            $lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;

            extract( $args );

            $localized_widget_title = $instance['title'];

            print( $before_widget . $before_title . $localized_widget_title . $after_title ); ?>

            <ul class="products-list" data-lang="<?php echo esc_attr( $lang ); ?>">
            <?php print( $king_woocompare->obj->list_products_html() );  ?>
            </ul>

            <a href="<?php echo esc_url( $king_woocompare->obj->remove_product_url('all') ); ?>" data-product_id="all" class="clear-all"><?php _e( 'Clear all', 'king' ) ?></a>
            <a href="<?php echo add_query_arg( array( 'iframe' => 'true' ), $king_woocompare->obj->view_table_url() ) ?>" class="compare button"><?php _e( 'Compare', 'king' ) ?></a>

            <?php print( $after_widget );
        }


        function form( $instance ) {
            global $woocommerce;

            $defaults = array(
                'title' => '',
            );

            $instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label>
                    <strong><?php _e( 'Title', 'king' ) ?>:</strong><br />
                    <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
                </label>
            </p>
        <?php
        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = strip_tags( $new_instance['title'] );

            return $instance;
        }

    }
}