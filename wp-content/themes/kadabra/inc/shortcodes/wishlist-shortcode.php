<?php

function sb_wishlist_button($attr, $content=null) {
	global $product, $yith_wcwl;
	
	if ($yith_wcwl->is_product_in_wishlist( $product->id )) : ?>
		<a class="product-in-wishlist" href="<?php echo esc_url($yith_wcwl->get_wishlist_url()); ?>" target="_blank">
			<i class="icon-add-1"></i>
			<?php _e( 'Wishlist', 'dfd' ); ?>
		</a>
	<?php else : ?>
	<a class="add_to_wishlist" data-product-type="<?php echo $product->product_type; ?>" data-product-id="<?php echo $product->id; ?>" href="<?php echo esc_url( $yith_wcwl->get_addtowishlist_url() ); ?>">
		<i class="icon-add-1"></i>
		<?php _e( 'Wishlist', 'dfd' ); ?>
	</a>
	<?php 
	echo YITH_WCWL_UI::popup_message();
	endif;
}

add_shortcode('sb_wishlist_button', 'sb_wishlist_button');

