<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$unique_id = uniqid('rp_');

$related = $product->get_related();

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> -1,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= $columns;

if ( $products->have_posts() ) : ?>

	<div class="related products products-slider-wrap">

		<h3><?php _e( 'Related products', 'dfd' ); ?></h3>
		
		<ul id="<?php echo $unique_id.'_nav'; ?>" class="flex-direction-nav"></ul>

		<div class="related_products products-slider" id="<?php echo $unique_id; ?>">
		
			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product-slider' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
			
		</div>

	</div>

	<script type="text/javascript">
	(function($){
		
		$(window).load(function () {
			
			var item_width;
			var wrapTimeID;
			var $slider_el = $('#<?php echo $unique_id ?>');
			var $prev = $('<li><a href="#" class="flex-prev"></a></li>');
			var $next = $('<li><a href="#" class="flex-next"></a></li>');
			
			var setSizes = function(resize) {
				var screen_width = $slider_el.outerWidth();
				item_width = Math.floor(screen_width / 3) - 13.333333333 - 5;
				
				if (resize != undefined && resize == true) {
					$slider_el.jcarousel('scroll', 0);
				}
				
				$slider_el.find('li.product').width(item_width);
			}
			
			var initSlider = function() {
				$slider_el
					.on('jcarousel:createend', function(event, carousel) {
						$('#<?php echo $unique_id; ?>_nav').append($prev).append($next);
						$prev.jcarouselControl({target: '-=1'});
						$next.jcarouselControl({target: '+=1'});
					})
					.jcarousel({
					transitions: true
				});
				
				if (typeof($slider_el.touch) === 'function') {
					$slider_el.touch();
				}

			}
			
			setSizes();
			initSlider();
			
			$(window).resize(function() {
				if (wrapTimeID) {
					clearTimeout(wrapTimeID);
				}

				wrapTimeID = setTimeout(function(){
					setSizes(true);
				}, 200);
			});
		});
		
	})(jQuery);
	</script>

<?php endif;

wp_reset_postdata();
