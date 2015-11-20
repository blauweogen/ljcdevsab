<?php
/**
 * Woocommerce support
 */
if (!function_exists('dfd_woocommerce_disable_styles')) {
	function dfd_woocommerce_disable_styles() {
		add_filter( 'woocommerce_enqueue_styles', 'dfd_woocommerce_disable_styles_filter', 11 );
	}
}

if (!function_exists('dfd_woocommerce_disable_styles_filter')) {
	function dfd_woocommerce_disable_styles_filter($in) {
		return array();
	}
}

// Redefine woocommerce_output_related_products()
function woocommerce_output_related_products() {
    woocommerce_related_products(3,3); // Display 4 products in rows of 4
}

if ( ! function_exists( 'wc_product_rating_overview' ) ) {
    function wc_product_rating_overview() {
        global $product;
        echo '<span class="show">' . $product->get_rating_html() . '</span>';
    }
}

/**
 * Define image sizes
 */
if (!function_exists('dfd_kadabra_woocommerce_image_dimensions')) {
	function dfd_kadabra_woocommerce_image_dimensions() {
		$catalog = array(
			'width' 	=> '273',	// px
			'height'	=> '280',	// px
			'crop'		=> 1 		// true
		);

		$single = array(
			'width' 	=> '430',	// px
			'height'	=> '600',	// px
			'crop'		=> 0 		// true
		);

		$thumbnail = array(
			'width' 	=> '120',	// px
			'height'	=> '120',	// px
			'crop'		=> 1 		// true
		);

		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
}

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	$fragments['a.woo-cart-contents'] = dfd_woocommerce_total_cart();
	
	return $fragments;
	
}

function dfd_woocommerce_total_cart() {
	if (!is_plugin_active('woocommerce/woocommerce.php')) 
		return;
	
	global $woocommerce;
	
	$href = $woocommerce->cart->get_cart_url();
	$title = __('View your shopping cart', 'dfd');
	
	$items_count = intval($woocommerce->cart->cart_contents_count);
	if ($items_count < 1) $items_count = 0;
	
	ob_start();
	?>
		<a class="woo-cart-contents" href="<?php echo $href; ?>" title="<?php echo $title; ?>">
			<span class="woo-cart-items">
				<i class="icon-basket-1"></i>
			</span>
			<span class="woo-cart-details">
				<?php echo $items_count; ?>
			</span>
		</a>
	<?php
	return ob_get_clean();
}
