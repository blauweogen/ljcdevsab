<?php
/**
 * Main class
 */

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'king_Compare_Frontend' ) ) {


    class king_Compare_Frontend {
       
        /**
         * The list of products inside the comparison table
         */
        public $products_list = array();

		
		/* Template */
		
        public $template_file = 'templates.php';

        /**
         * The name of cookie name
         */
        public $cookie_name = 'king_woocompare_list';

        /**
         * The action used to view the table comparison
         */
        public $action_view = 'king-woocompare-view-table';

        /**
         * The action used to add the product to compare list

         */
        public $action_add = 'king-woocompare-add-product';

        /**
         * The action used to add the product to compare list
         */
        public $action_remove = 'king-woocompare-remove-product';

        /**
         * The standard fields
         */
        public $default_fields = array();

        /**
         * Constructor
         */
        public function __construct() {
			
			global $king;
            // set coookiename
            if ( is_multisite() ) $this->cookie_name .= '_' . get_current_blog_id();

            // populate the list of products
            $this->products_list = isset( $_COOKIE[ $this->cookie_name ] ) ? unserialize( $_COOKIE[ $this->cookie_name ] ) : array();
			
            // add image size
			add_action( 'init', array( $this, 'king_set_image_size' ), 36 );
			add_action( 'init', array( $this, 'standard_fields' ), 20 );
			
			
			// add button to single page, list page
			add_action( 'init', array( $this, 'add_button_follow_position' ), 38 );
			
            add_action( 'init', array( $this, 'add_product_to_compare_action' ) );
            add_action( 'init', array( $this, 'remove_product_from_compare_action' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'template_redirect', array( $this, 'compare_table_html') );
            // add the shortcode
            $king->ext['asc']( 'king_compare_button', array( $this, 'compare_button_sc' ) );

            // AJAX
            add_action( 'wp_ajax_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );

            add_action( 'wp_ajax_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );

            add_action( 'wp_ajax_' . $this->action_view, array( $this, 'refresh_widget_list_ajax' ) );
            add_action( 'wp_ajax_nopriv_' . $this->action_view, array( $this, 'refresh_widget_list_ajax' ) );

            return $this;
        }
		
        /**
         * Enqueue the scripts and styles in the page
         */
        public function enqueue_scripts() {
			
            // scripts
            wp_enqueue_script( 'king-woocompare-main', king_WOOCOMPARE_URL . 'assets/js/woocompare.js', array('jquery'), '1.0.0', true );
            wp_localize_script( 'king-woocompare-main', 'king_woocompare', array(
                'nonceadd' => wp_create_nonce( $this->action_add ),
                'nonceremove' => wp_create_nonce( $this->action_remove ),
                'nonceview' => wp_create_nonce( $this->action_view ),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'actionadd' => $this->action_add,
                'actionremove' => $this->action_remove,
                'actionview' => $this->action_view,
                'added_label' => __( 'Added', 'king' ),
                'table_title' => __( 'Product Comparison', 'king' ),
                'auto_open' => 'yes'
            ));

            // colorbox
            wp_enqueue_style( 'jquery-colorbox', king_WOOCOMPARE_URL . 'assets/css/colorbox.css' );
            wp_enqueue_script( 'jquery-colorbox', king_WOOCOMPARE_URL . 'assets/js/jquery.colorbox-min.js', array('jquery'), '1.4.21', true );

            // widget
            if ( is_active_widget( false, false, 'king-woocompare-widget', true ) && ! is_admin() ) {
                wp_enqueue_style( 'king-woocompare-widget', king_WOOCOMPARE_URL . 'assets/css/widget.css' );
            }
        }

        /**
         * The fields to show in the table
         */
        public function fields() {

			/**
			 * Get standard and attribute fields.
			 * return $fields as array.
			 */
			global $king;
			if($king->cfg['woo_comp_p_attribute'] == 1){
				$fields = array_merge( $this->get_option_standard_field(), $this->attribute_taxonomies() );
			} else {
				$fields =  $this->get_option_standard_field();
			}
			
            foreach ( $fields as $field => $show ) {
                if ( $show ) {
                    if ( isset( $this->default_fields[$field] ) ) {
                        $fields[$field] = $this->default_fields[$field];
                    } else {
                        if ( taxonomy_exists( $field ) ) {
                            $fields[$field] = get_taxonomy( $field )->label;
                        }
                    }
                } else {
                    unset( $fields[$field] );
                }
            }

            return $fields;
        }
		
		/*
		* Get option standard fields in admin
		* return array
		*/
		public function get_option_standard_field(){
			global $king;
			$option_standard_field = $this->standard_fields();
			if($king->cfg['woo_comp_p_image'] == 0){ 
				unset($option_standard_field['image']);
			}
			if($king->cfg['woo_comp_p_title'] == 0){ 
				unset($option_standard_field['title']);
			}
			if($king->cfg['woo_comp_p_price'] == 0){ 
				unset($option_standard_field['price']);
			}
			if($king->cfg['woo_comp_p_addtc'] == 0){ 
				unset($option_standard_field['add-to-cart']);
			}
			if($king->cfg['woo_comp_p_des'] == 0){ 
				unset($option_standard_field['description']);
			}
			if($king->cfg['woo_comp_p_stock'] == 0){ 
				unset($option_standard_field['stock']);
			}
			
			return $option_standard_field;
		}

        /**
         * Render the maintenance page
         *
         */
        public function compare_table_html() {
            if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_view ) ) return;

            global $woocommerce;

            extract( $this->_vars() );

            // remove all styles from compare template
            add_action('wp_print_styles', array( $this, 'remove_all_styles' ), 100);

            // remove admin bar
            remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
            remove_action( 'wp_head', '_admin_bar_bump_cb' );

            $plugin_path   = plugin_dir_path(__FILE__) . $this->template_file;

            if ( defined('WC_TEMPLATE_PATH') ) {

                $template_path = get_template_directory() . '/' . WC_TEMPLATE_PATH . $this->template_file;
                $child_path    = get_stylesheet_directory() . '/'  .WC_TEMPLATE_PATH . $this->template_file;
            }else{
                $template_path = get_template_directory() . '/' . $woocommerce->template_url . $this->template_file;
                $child_path    = get_stylesheet_directory() . '/' . $woocommerce->template_url . $this->template_file;
            }

            foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                if ( file_exists( ${$var} ) ) {
                    include ${$var};
                    exit();
                }
            }
        }

        /**
         * Return the array with all products and all attributes values
         *
         * @return array The complete list of products with all attributes value
         */
        public function get_products_list() {
			
            $list = array();
            $products = $this->products_list;
            $fields = $this->fields();
			

            foreach ( $products as $product_id ) {
                $product = $this->wc_get_product( $product_id );
                if ( ! $product ) continue;

                $product->fields = array();

                // custom attributes
                foreach ( $fields as $field => $name )  {

                    switch( $field ) {
                        case 'title':
                            $product->fields[$field] = $product->get_title();
                            break;
                        case 'price':
                            $product->fields[$field] = $product->get_price_html();
                            break;
                        case 'image':
                            $product->fields[$field] = intval( get_post_thumbnail_id( $product_id ) );
                            break;
                        case 'description':
                            $product->fields[$field] = apply_filters( 'woocommerce_short_description', $product->post->post_excerpt );
                            break;
                        case 'stock':
                            $availability = $product->get_availability();
                            if ( empty( $availability['availability'] ) ) {
                                $availability['availability'] = __( 'In stock', 'king' );
                            }
                            $product->fields[$field] = sprintf( '<span class="%s">%s</span>', esc_attr( $availability['class'] ), esc_html( $availability['availability'] ) );
                            break;
                        default:
                            
                            if ( taxonomy_exists( $field ) ) {
                                $product->fields[$field] = array();
                                $terms = get_the_terms( $product_id, $field );
                                if ( ! empty( $terms ) ) {
                                    foreach ( $terms as $term ) {
                                        $term = sanitize_term( $term, $field );
                                        $product->fields[$field][] = $term->name;
                                    }
                                }
                                $product->fields[$field] = implode( ', ', $product->fields[$field] );
                            } else {
                                do_action_ref_array( 'king_woocompare_field_' . $field, array( $product, &$product->fields ) );
                            }
                            break;
                    }
                }

                $list[] = $product;
            }

            return $list;
        }

        /**
         * The URL of product comparison table
         */
        public function view_table_url() {
            return add_query_arg( 'action', esc_attr( $this->action_view ) );
        }

        /**
         * The URL to add the product into the comparison table

         */
        public function add_product_url( $product_id ) {
            $url_args = array(
                'action' => esc_attr( $this->action_add ),
                'id' => intval( $product_id )
            );
            return wp_nonce_url( add_query_arg( $url_args ), $this->action_add );
        }

        /**
         * The URL to remove the product into the comparison table
         */
        public function remove_product_url( $product_id ) {
            $url_args = array(
                'action' => esc_attr( $this->action_remove ),
                'id' => esc_attr( $product_id )
            );
            return wp_nonce_url( add_query_arg( $url_args ), $this->action_remove );
        }

		public function add_button_follow_position(){
			/*
			* Add compare button follow position.
			*/

			global $king;
			 
            if(!empty( $king->cfg['woo_comp_single'] )) if ( $king->cfg['woo_comp_single'] == 1 ){
            	 add_action( 'woocommerce_single_product_summary', array( $this, 'add_compare_link' ), 35 );
            }	 
		}
		
		
		 public function king_set_image_size() {
		 
			/*
			* Add config image size
			*/
			global $king;
			$widths = isset($king->cfg['woo_comp_image_width']) ? $king->cfg['woo_comp_image_width'] : 220 ;
			$heights = isset($king->cfg['woo_comp_image_height']) ? $king->cfg['woo_comp_image_height'] : 154 ;
			$crops = isset($king->cfg['woo_comp_i_crop']) ? $king->cfg['woo_comp_i_crop'] : true ;
			add_image_size( 'king-woocompare-image', $widths, $heights, $crops );	
			
        }
        /*
         * The list of standard fields
         */
        public function standard_fields() {
            return array(
                'image' => __('Image', 'king'),
                'title' => __('Title', 'king'),
                'price' => __('Price', 'king'),
                'add-to-cart' => __('Add to cart', 'king'),
                'description' => __('Description', 'king'),
                'stock' => __( 'Availability', 'king' )
            );
        }

        /*
         * Get Woocommerce Attribute Taxonomies
         */
        public static function attribute_taxonomies() {
            global $woocommerce;

            if ( ! isset( $woocommerce ) ) return array();

            $attributes = array();

            if( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if( empty( $attribute_taxonomies ) ) return array();
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    if ( taxonomy_exists( $tax ) ) {
                        $attributes[$tax] = ucfirst( $attribute->attribute_name );
                    }
                }
            }else{
                $attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
                if( empty( $attribute_taxonomies ) ) return array();
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = $woocommerce->attribute_taxonomy_name( $attribute->attribute_name );
                    if ( taxonomy_exists( $tax ) ) {
                        $attributes[$tax] = ucfirst( $attribute->attribute_name );
                    }
                }
            }


            return $attributes;
        } 
		
		/**
        *  Add the link to compare
        */
        public function add_compare_link( $product_id = false, $args = array() ) {
			global $king;
			 //print_r($this->attribute_taxonomies()); exit;
			 //print_r($this->get_option_standard_field()); exit;
			 
			 
			 
			
			
			 
			 
            extract( $args );

            if ( ! $product_id ) {
                global $product;
                $product_id = isset( $product->id ) && $product->exists() ? $product->id : 0;
            }

            // return if product doesn't exist
            if ( empty( $product_id ) ) return;
			
            $is_button = !isset( $button_or_link ) || !$button_or_link ? $king->cfg['woo_comp_button'] : $button_or_link;
            $button_text = isset($king->cfg['woo_comp_button_label']) ? $king->cfg['woo_comp_button_label'] : 'compare';
            printf( '<a href="%s" class="%s" data-product_id="%d"><i class="fa fa-retweet"></i>  %s</a>', $this->add_product_url( $product_id ), 'compare' . ( $is_button == 'button' ? ' button' : '' ), $product_id, ( isset( $button_text ) && $button_text != 'default' ? $button_text : $button_text ) );
        }

        /**
         * Return the url of stylesheet position
         */
        public function stylesheet_url() {
            global $woocommerce;

            $filename = 'compare.css';
            
            $plugin_path   = array( 'path' => plugin_dir_path(__FILE__) . 'assets/css/compare.css', 'url' => king_WOOCOMPARE_URL . 'assets/css/compare.css' );

            if ( defined('WC_TEMPLATE_PATH') ) {
                $template_path = array( 'path' => get_template_directory() . '/' . WC_TEMPLATE_PATH . $filename,         'url' => get_template_directory_uri() . '/' . WC_TEMPLATE_PATH . $filename );
                $child_path    = array( 'path' => get_stylesheet_directory() . '/' . WC_TEMPLATE_PATH . $filename,       'url' => get_stylesheet_directory_uri() . '/' . WC_TEMPLATE_PATH . $filename );
            }else{
                $template_path = array( 'path' => get_template_directory() . '/' . $woocommerce->template_url . $filename,         'url' => get_template_directory_uri() . '/' . $woocommerce->template_url . $filename );
                $child_path    = array( 'path' => get_stylesheet_directory() . '/' . $woocommerce->template_url . $filename,       'url' => get_stylesheet_directory_uri() . '/' . $woocommerce->template_url . $filename );
            }
            foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                if ( file_exists( ${$var}['path'] ) ) {
                    return ${$var}['url'];
                }
            }
        }


        /**
         * Generate template vars
         */
        protected function _vars() {
            $vars = array(
                'products' => $this->get_products_list(),
                'fields' => $this->fields(),
                'repeat_price' => 'yes',
                'repeat_add_to_cart' => 'yes',
            );

            return $vars;
        }

        /**
         * The action called by the query string
         */
        public function add_product_to_compare_action() {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ||
                ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], $this->action_add ) ) &&
                ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add ) )
                return;

            $this->add_product_to_compare( intval( $_REQUEST['id'] ) );

            wp_redirect( remove_query_arg( array( 'id', 'action', '_wpnonce' ) ) );
            exit();
        }

        /**
         * The action called by AJAX
         */
        public function add_product_to_compare_ajax() {
            check_ajax_referer( $this->action_add, '_devnonce_ajax' );

            $this->add_product_to_compare( intval( $_REQUEST['id'] ) );

            $json = array(
                'table_url' => add_query_arg( array(
                    'action' => esc_attr( $this->action_view ),
                    'iframe' => 'true',
                    'ver' => time()
                ), site_url() ),

                'widget_table' => $this->list_products_html()
            );

            echo json_encode( $json );
            die();
        }

        /**
         * Add a product in the products comparison table
         *
         * @param $product_id The product ID to add in the comparison table
         */
        public function add_product_to_compare( $product_id ) {
            $product = $this->wc_get_product( $product_id );

            // don't add the product if doesn't exist
            if ( !$product->exists() || in_array( $product_id, $this->products_list ) ) return;

            $this->products_list[] = $product_id;
            setcookie( $this->cookie_name, serialize($this->products_list), 0, COOKIEPATH, COOKIE_DOMAIN, false, true );
        }

        /**
         * The action called by the query string
         */
        public function remove_product_from_compare_action() {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ||
                ( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( $_REQUEST['_wpnonce'], $this->action_remove ) ) &&
                ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove ) )
                return;

            $this->remove_product_from_compare( intval( $_REQUEST['id'] ) );

            // redirect
            $redirect = remove_query_arg( array( 'id', 'action', '_wpnonce' ) );
            if ( isset( $_REQUEST['redirect'] ) && $_REQUEST['redirect'] == 'view' ) $redirect = remove_query_arg( 'redirect', add_query_arg( 'action', esc_attr( $this->action_view ), $redirect ) );
            wp_redirect( $redirect );
            exit();
        }

        /**
         * The action called by AJAX
         */
        public function remove_product_from_compare_ajax() {
            check_ajax_referer( $this->action_remove, '_devnonce_ajax' );

            $lang = isset( $_REQUEST['lang'] ) ? $_REQUEST['lang'] : false;

            if ( ! isset( $_REQUEST['id'] ) ) die();

            if ( $_REQUEST['id'] == 'all' ) {
                $products = $this->products_list;
                foreach ( $products as $product_id ) {
                    $this->remove_product_from_compare( intval( $product_id ) );
                }
            } else {
                $this->remove_product_from_compare( intval( $_REQUEST['id'] ) );
            }

            header('Content-Type: text/html; charset=utf-8');

            if ( isset( $_REQUEST['responseType'] ) && $_REQUEST['responseType'] == 'product_list' ) {
                echo esc_html( $this->list_products_html( $lang ) );
            } else {
                $this->compare_table_html();
            }

            die();
        }

        /**
         * Return the list of widget table, used in AJAX
         */
        public function refresh_widget_list_ajax() {
            echo esc_html( $this->list_products_html() );
            die();
        }

        /**
         * The list of products as HTML list
         */
        public function list_products_html( $lang = false ) {
            ob_start();

            /**
             * WPML Suppot:  Localize Ajax Call
             */
            global $sitepress;

            if( defined( 'ICL_LANGUAGE_CODE' ) &&  $lang != false && isset( $sitepress )) {
                $sitepress->switch_lang( $lang, true );
            }

            if ( empty( $this->products_list ) ) {
                echo '<li>' . __( 'No products to compare', 'king' ) . '</li>';
                return ob_get_clean();
            }

            foreach ( $this->products_list as $product_id ) {
                $product = $this->wc_get_product( $product_id );
                if ( ! $product ) continue;
                ?>
                <li>
                    <a class="title" href="<?php echo get_permalink( $product_id ) ?>"><?php echo esc_attr( $product->get_title() ); ?></a>
                    <a href="<?php echo esc_attr( $this->remove_product_url( $product_id ) ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" class="remove" title="<?php _e( 'Remove', 'king' ) ?>">x</a>
                </li>
            <?php
            }

            return ob_get_clean();
        }

        /**
         * Remove a product from the comparison table
         *
         * @param $product_id The product ID to remove from the comparison table
         */
        public function remove_product_from_compare( $product_id ) {
            $product = $this->wc_get_product( $product_id );
            if ( ! $product ) return;

            // don't add the product if doesn't exist
            if ( !$product->exists() || !in_array( $product_id, $this->products_list ) ) return;

            foreach ( $this->products_list as $k => $id ) {
                if ( $product_id == $id ) unset( $this->products_list[$k] );
            }
            setcookie( $this->cookie_name, serialize($this->products_list), 0, COOKIEPATH, COOKIE_DOMAIN, false, true );
        }

        /**
         * Remove all styles from the compare template
         */
        public function remove_all_styles() {
            global $wp_styles;
            $wp_styles->queue = array();
        }

        /**
         * Show the html for the shortcode
         */
        public function compare_button_sc( $atts, $content = null ) {
            $atts = shortcode_atts(array(
                'product' => false,
                'type' => 'default',
                'container' => 'yes'
            ), $atts);

            $product_id = 0;

            /**
             * Retrieve the product ID in these steps:
             * - If "product" attribute is not set, get the product ID of current product loop
             * - If "product" contains ID, post slug or post title
             */
            if ( ! $atts['product'] ) {
                global $product;
                $product_id = isset( $product->id ) && $product->exists() ? $product->id : 0;
            } else {
                global $wpdb;
                $product = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE ID = %d OR post_name = %s OR post_title = %s LIMIT 1", $atts['product'], $atts['product'], $atts['product'] ) );
                if ( ! empty( $product ) ) {
                    $product_id = $product->ID;
                }
            }

            // if product ID is 0, maybe the product doesn't exists or is wrong.. in this case, doesn't show the button
            if ( empty( $product_id ) ) return;

            ob_start();
            if ( $atts['container'] == 'yes' ) echo '<div class="woocommerce product compare-button">';
            $this->add_compare_link( $product_id, array(
                'button_or_link' => ( $atts['type'] == 'default' ? false : $atts['type'] ),
                'button_text' => empty( $content ) ? 'default' : $content
            ) );
            if ( $atts['container'] == 'yes' ) echo '</div>';
            return ob_get_clean();
        }

        public function wc_get_product( $product_id ){
            $wc_get_product = function_exists( 'wc_get_product' ) ? 'wc_get_product' : 'get_product';
            return $wc_get_product( $product_id );
        }

    }
}