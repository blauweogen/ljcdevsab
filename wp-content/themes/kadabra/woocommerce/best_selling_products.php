<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

$unique_id = uniqid('bsp_');
$posts_per_page = (!empty($posts_per_page)) ? $posts_per_page : -1;

$args = array(
	'post_type' => 'product',
	'post_status' => 'publish',
	'ignore_sticky_posts' => 1,
	'posts_per_page' => $posts_per_page,
	'meta_key' => 'total_sales',
	'orderby' => 'meta_value_num',
	'meta_query' => array(
		array(
			'key' => '_visibility',
			'value' => array('catalog', 'visible'),
			'compare' => 'IN'
		)
	)
);

$products = new WP_Query($args);

if ($products->have_posts()) :
	?>
	
	<div class="best-selling-products-wrap products-slider-wrap woocommerce">
		
		<?php if ($show_title) : ?>
		<h3 class="widget-title"><?php _e( 'The best offers', 'dfd' ); ?></h3>
		<?php endif; ?>

		<div class="best-selling-products products-slider" id="<?php echo $unique_id; ?>">

			<?php woocommerce_product_loop_start(); ?>

			<?php while ($products->have_posts()) : $products->the_post(); ?>

				<?php woocommerce_get_template_part('content', 'product-slider'); ?>

			<?php endwhile; ?>

			<?php woocommerce_product_loop_end(); ?>
			
			<?php echo DFD_Carousel::controls(); ?>
		</div>
		
	</div>

	<script type="text/javascript">
	(function($){
		$(document).ready(function() {
			var $slider = $('#<?php echo $unique_id ?>');
			var $slider_controls = $('.jcarousel-control', $slider);
			var columns = 4;
			
			var eqSlides = function(){
				var width = $slider.width();

				switch(true) {
					case (width <= 620): columns = 1; break;
					case (width <= 800): columns = 2; break;
					case (width <= 1000): columns = 3; break;
					default: columns = 4;
				}
				
				$slider_controls.css('top', Math.round($slider.height() / 2) + 'px');
			};

			eqSlides(); $(window).load(eqSlides).resize(eqSlides);
			
			$slider
				.on('jcarousel:reload jcarousel:create', function () {
					var width = Math.floor( $slider.innerWidth() / columns );

					$slider.jcarousel('items')
						.css('width', width + 'px');
				})
				.jcarousel({
					wrap: 'circular'
				});
				
			if (typeof($slider.touch) === 'function') {
				$slider.touch();
			}

			$('.jcarousel-control-prev', $slider)
				.jcarouselControl({
					carousel: $slider,
					target: '-=1'
				});
			$('.jcarousel-control-next', $slider)
				.jcarouselControl({
					carousel: $slider,
					target: '+=1'
				});
		});
	})(jQuery);
	</script>
		
<?php

endif;

wp_reset_postdata();
