<?php
/**
 * Main class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'king_FILTER' ) ) {

    class king_FILTER {

        public $obj = null;

        /**
         * Constructor
         *
         * @return mixed|king_FILTER_Admin|king_FILTER_Frontend
         */
        public function __construct() {

            // actions
            add_action( 'init', array( $this, 'init' ) );
            add_action( 'widgets_init', array( $this, 'registerWidgets' ) );


            if ( is_admin() ) {
                $this->obj = new king_FILTER_Admin();
            }
            else {
                $this->obj = new king_FILTER_Frontend();
            }

            return $this->obj;
        }


        /**
         * Init method
         *
         * @access public
         */
        public function init() {
		
        }

        /**
         * Load and register widgets
         *
         * @access public
         */
        public function registerWidgets() {
            register_widget( 'king_FILTER_Widget' );
            register_widget( 'king_RESET_FILTER_Widget' );
        }

    }
}