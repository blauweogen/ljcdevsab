<?php
/**
 * Main class
 *
 */

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'king_Woocompare' ) ) {
    /**
     * Woocommerce Compare
     */
	 
	 
    class king_Woocompare {


        public $obj = null;

        /**
         * AJAX Helper
         */
        public $ajax = null;

        /**
         * Constructor
         */
        public function __construct() {
            add_action( 'widgets_init', array( $this, 'registerWidgets' ) );

            if( $this->is_frontend() ) {
                $this->obj = new king_Compare_Frontend();
            } 

            return $this->obj;
        }

        /**
         * Detect if is frontend
         * @return bool
         */
        public function is_frontend() {
			
            $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
            return (bool) ( ! is_admin() || $is_ajax && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' );
        }

        /**
         * Load and register widgets
         */
        public function registerWidgets() {
            register_widget( 'king_Woocompare_Widget' );
        }
    }
}