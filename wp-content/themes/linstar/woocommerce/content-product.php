<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !woo_gate( __FILE__ ) ){return;}

global $product, $woocommerce_loop, $king;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

$items = 4;
if( !empty( $king->cfg['woo_grids'] ) ){
	$items = $king->cfg['woo_grids'];
}

if( !empty( $_REQUEST['perRow'] ) ){
	$items = $_REQUEST['perRow'];
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $items );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;
	
// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();

$classes[] = 'grid-'.$items;

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ){
	$classes[] = 'first';
}	
if ( ( $woocommerce_loop['loop'] ) % $items == 0 ){
	$classes[] = 'last';
}	
if( ($woocommerce_loop['loop']-1) % $items > 0 ){
	$classes[] = 'delay-'. (((($woocommerce_loop['loop']-1) % $items )*1.5)*100).'ms';
}

$classes[] = 'item-'.($woocommerce_loop['loop']%2);
	
	
?>
<li <?php post_class( implode( ' ', $classes )." animated eff-fadeIn ".(!empty($woocommerce_loop['view'])?$woocommerce_loop['view']:'' )); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>" class="product-images">
	
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
	</a>
	
	<div class="king-product-info">

		<div class="product-info-box">

			<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

			

				<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
				?>

			<div class="woo_des"><?php the_content(); ?></div>

		</div>

	</div>
	
	

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>
