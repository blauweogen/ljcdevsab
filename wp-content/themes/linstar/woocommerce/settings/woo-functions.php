<?php 
 // DEVN Woocommerce functions
 // Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	die;
}

global $king;

add_action('wp_enqueue_scripts', 'woocommerce_enqueue_content');
function woocommerce_enqueue_content(){

	wp_enqueue_style('king-woo', THEME_URI.'/woocommerce/settings/css/king-woo.css', false, KING_VERSION );
	
	wp_register_script( 'king-magnifier', THEME_URI.'/woocommerce/magnifier/js/magnifier.min.js', array('jquery'), KING_VERSION , true );
	wp_register_script( 'king-carouFredSel', THEME_URI.'/woocommerce/magnifier/js/jquery.carouFredSel.min.js', array('jquery'), KING_VERSION , true );
	
	wp_enqueue_script( 'king-magnifier' );
	wp_enqueue_script( 'king-carouFredSel' );
}

/**
 * Get cart to item menu
 */
add_action('wp_ajax_nopriv_king_get_cart', 'woo_king_get_cart');
add_action('wp_ajax_king_get_cart', 'woo_king_get_cart');
function woo_king_get_cart(){
	global $woocommerce; 
	
	$cart_data = '<div class="shopping_cart_inner"><ul>';
	
	if(WC()->cart->cart_contents_count == 0){
		$cart_data .= '<li class="no-product-in-cart">No products in the cart.</li>';
	}
	
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		
		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			if ( $_product->is_sold_individually() ) {
				$product_quantity = 1;
			} else {
				$product_quantity = $cart_item['quantity'];
			}
			
			$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
			
			$cart_data .= '<li><a href="'. $_product->get_permalink( $cart_item ) .'">'. $thumbnail . $_product->get_title() .'</a>
			<span class="quantity">'. $product_quantity .' x '. WC()->cart->get_product_price( $_product ) .'</span></li>';
		}
	}
	
	$cart_data .= '</ul>';
	$cart_data .= '<div class="king-cart-footer"><a class="k-cart-button" href="'. $woocommerce->cart->get_cart_url() .'">Cart <i class="fa fa-shopping-cart"></i></a><span class="total">Total: '. WC()->cart->get_cart_total() .'</span></div>';
	$cart_data .= '</div>'; 
	 	 
	$data = array(
		'cart_content' => $cart_data,
		'count' => WC()->cart->cart_contents_count,
		'total' => WC()->cart->get_cart_total()
	);
		
	wp_send_json($data);
}


add_action('wp_footer', 'woo_king_add_cart_script');
function woo_king_add_cart_script(){
	if ( class_exists( 'WooCommerce' ) ) {
	global $woocommerce;
	?>
	<script type="text/javascript">
	"use strict";
	var king_cart = function(first_load){
		if( typeof first_load === 'undefined' ) first_load = true;
		
		if(first_load){
			jQuery('.king-cart > a').text(0);
			jQuery('.king-cart').append('<div id="king-cart-content"></div>');	
			jQuery('.navbar > .navbar-header').append('<a class="king-res-cart" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>"><span>0</span></a>');
			jQuery('a.king-res-cart').parent('.navbar-toggle').addClass('navbar_cart_res');
			jQuery('a.king-res-cart > span').hide()
		}
				
		var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
			
		var data = {
			action: 'king_get_cart',
		};
		
		// Ajax action
		jQuery.post( ajaxurl, data, function( response ) {
			jQuery('.king-cart > a').text(response.count);
			jQuery('.king-res-cart > span').text(response.count);
			jQuery('.king-res-cart > span').fadeIn('fast');
			jQuery('#king-cart-content').html(response.cart_content);
		});
	}
	
	if(jQuery('ul.nav>li').hasClass('king-cart')){
		king_cart();
		
		jQuery('.nav > .king-cart').hover(function() {
			jQuery(this).children('#king-cart-content').stop().fadeIn(500);
		}, function() {
			jQuery(this).children('#king-cart-content').stop().fadeOut('fast');
		});
	}	
	
	</script>
	<?php
	}
}


add_action( 'wp_enqueue_scripts', 'king_load_woo_add_to_cart_scripts', 9 );
function king_load_woo_add_to_cart_scripts() {
    wp_enqueue_script( 'wc-add-to-cart', THEME_URI.'/woocommerce/settings/js/add-to-cart.js', array( 'jquery' ), WC_VERSION, true );
} 
 
 
// WooCommerce template
if( ! class_exists( 'DevnWooTemplate' )) {

	class DevnWooTemplate{
		
		function __construct(){
			add_filter( 'woocommerce_show_page_title', array( $this, 'shop_title'), 10 );
			// Product Loop page.
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'before_shop_item_buttons' ), 9 );
    		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_shop_item_buttons' ), 11 );
			/* Single product page */
			//add_action( 'woocommerce_single_product_summary', array( $this, 'add_product_line' ), 19 );
		}
		// End __construct();
		
		function before_shop_item_buttons() {
			echo '<div class="product-buttons"><div class="product-buttons-box">';
		}
		function after_shop_item_buttons() {
			echo '<a href="' . get_permalink() . '" class="show_details_button">' . __( 'Show details', 'king' ) . '</a></div></div>';
		}
		/* function add_product_line() {
			echo '<div class="clear"></div><div class="product-line"></div>';
		} */
		
		// Hidden Shop title
		function shop_title() {
			return false;
		}
	} 
	// End class DevnWooTemplate;
}

new DevnWooTemplate();

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);	

add_action('woocommerce_before_shop_loop', 'king_woo_products_order', 30);
function king_woo_products_order() {

	// Get WooCommerce admin setting.
	global $king;
	
	parse_str($_SERVER['QUERY_STRING'], $params);
	
	$query_string = '?'.$_SERVER['QUERY_STRING'];
	
	if( !empty( $king->cfg['product_number'] ) ) {
		$products_per_page = $king->cfg['product_number'];
	} else {
		$products_per_page = 12;
	}
	
	// Set product_orderby, product_order, product_count.
	$king_product_orderby = !empty($params['product_orderby']) ? $params['product_orderby'] : 'default';
	$king_product_order = !empty($params['product_order'])  ? $params['product_order'] : 'asc';
	$king_product_count = !empty($params['product_count']) ? $params['product_count'] : $products_per_page;
	
	$html = '';
	$html .= '<div class="king-product-order">';
	$html .= '<div class="king-orderby-container">';
	$html .= '<ul class="orderby order-dropdown">';
	$html .= '<li>';
	$html .= '<span class="current-li"><span class="current-li-content"><a>'.__('Sort by', 'king').' <strong>'.__('Default Order', 'king').'</strong></a></span></span>';
	$html .= '<ul>';
	$html .= '<li class="'.(($king_product_orderby == 'default') ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_orderby', 'default').'">'.__('Sort by', 'king').' <strong>'.__('Default Order', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_orderby == 'name') ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_orderby', 'name').'">'.__('Sort by', 'king').' <strong>'.__('Name', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_orderby == 'price') ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_orderby', 'price').'">'.__('Sort by', 'king').' <strong>'.__('Price', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_orderby == 'date') ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_orderby', 'date').'">'.__('Sort by', 'king').' <strong>'.__('Date', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_orderby == 'rating') ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_orderby', 'rating').'">'.__('Sort by', 'king').' <strong>'.__('Rating', 'king').'</strong></a></li>';
	$html .= '</ul>';
	$html .= '</li>';
	$html .= '</ul>';
	$html .= '<ul class="order">';
	if($king_product_order == 'desc'):
	$html .= '<li class="desc"><a href="'.king_get_data_url($query_string, 'product_order', 'asc').'"><i class="fa fa-arrow-up"></i></a></li>';
	endif;
	if($king_product_order == 'asc'):
	$html .= '<li class="asc"><a href="'.king_get_data_url($query_string, 'product_order', 'desc').'"><i class="fa fa-arrow-down"></i></a></li>';
	endif;
	$html .= '</ul>';

	$html .= '</div>';

	$html .= '<ul class="sort-count order-dropdown">';
	$html .= '<li>';
	$html .= '<span class="current-li"><a>'.__('Show', 'king').' <strong>'.$products_per_page.' '.__(' Products', 'king').'</strong></a></span>';
	$html .= '<ul>';
	$html .= '<li class="'.(($king_product_count == $products_per_page) ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_count', $products_per_page).'">'.__('Show', 'king').' <strong>'.$products_per_page.' '.__('Products', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_count == $products_per_page*2) ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_count', $products_per_page*2).'">'.__('Show', 'king').' <strong>'.($products_per_page*2).' '.__('Products', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_count == $products_per_page*3) ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_count', $products_per_page*3).'">'.__('Show', 'king').' <strong>'.($products_per_page*3).' '.__('Products', 'king').'</strong></a></li>';
	$html .= '<li class="'.(($king_product_count == $products_per_page*4) ? 'current': '').'"><a href="'.king_get_data_url($query_string, 'product_count', $products_per_page*4).'">'.__('Show', 'king').' <strong>'.($products_per_page*4).' '.__('Products', 'king').'</strong></a></li>';
	$html .= '</ul>';
	$html .= '</li>';
	$html .= '</ul>';
	$html .= '</div>
	<script>
		jQuery(".king-product-order .orderby .current-li a").html(jQuery(".king-product-order .orderby ul li.current a").html());
		jQuery(".king-product-order .sort-count .current-li a").html(jQuery(".king-product-order .sort-count ul li.current a").html());	
	</script>
	';

	print( $html );
}

add_action('woocommerce_get_catalog_ordering_args', 'king_woo_get_order', 20);
function king_woo_get_order($args){
	global $woocommerce;

	parse_str($_SERVER['QUERY_STRING'], $params);

	$king_product_orderby = !empty($params['product_orderby']) ? $params['product_orderby'] : 'default';
	$king_product_order = !empty($params['product_order'])  ? $params['product_order'] : 'asc';
	
	switch($king_product_orderby) {
		case 'date':
			$orderby = 'date';
			$order = 'desc';
			$meta_key = '';
		break;
		case 'price':
			$orderby = 'meta_value_num';
			$order = 'asc';
			$meta_key = '_price';
		break;
		case 'popularity':
			$orderby = 'meta_value_num';
			$order = 'desc';
			$meta_key = 'total_sales';
		break;
		case 'title':
			$orderby = 'title';
			$order = 'asc';
			$meta_key = '';
		break;
		case 'default':
		default:
			$orderby = 'menu_order title';
			$order = 'asc';
			$meta_key = '';
		break;
	}

	switch($king_product_order) {
		case 'desc':
			$order = 'desc';
		break;
		case 'asc':
			$order = 'asc';
		break;
		default:
			$order = 'asc';
		break;
	}

	$args['orderby'] = $orderby;
	$args['order'] = $order;
	$args['meta_key'] = $meta_key;

	if( $king_product_orderby == 'rating' ) {
		$args['orderby']  = 'menu_order title';
		$args['order']    = $king_product_order == 'desc' ? 'desc' : 'asc';
		$args['order']	  = strtoupper( $args['order'] );
		$args['meta_key'] = '';

		add_filter( 'posts_clauses', 'king_order_rating' );
	}

	return $args;
}

function king_order_rating( $args ) {
	global $wpdb;

	$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

	$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

	$args['join'] .= "
		LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
		LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
	";
	
	$order = woocommerce_clean( $_GET['product_order'] );
	$order = $order == 'asc' ? 'asc' : 'desc';
	$order = strtoupper($order);

	$args['orderby'] = "average_rating {$order}, $wpdb->posts.post_date DESC";

	$args['groupby'] = "$wpdb->posts.ID";

	return $args;
}

add_filter('loop_shop_per_page', 'king_loop_per_page');
function king_loop_per_page(){
	// Get WooCommerce admin setting.
	global $king;

	parse_str($_SERVER['QUERY_STRING'], $params);

	if( !empty( $king->cfg['product_number'] ) ) {
		$products_per_page = $king->cfg['product_number'];
	} else {
		$products_per_page = 12;
	}

	$king_product_count = !empty($params['product_count']) ? $params['product_count'] : $products_per_page;

	return $king_product_count;
}

function king_get_data_url($KING_URL, $king_pr_name, $king_pr_value) {

	 $KING_URL_info = parse_url($KING_URL);
	 if(!isset($KING_URL_info["query"]))
		 $KING_URL_info["query"]="";

	 $params = array();
	 parse_str($KING_URL_info['query'], $params);
	 $params[$king_pr_name] = $king_pr_value;
	 $KING_URL_info['query'] = http_build_query($params);
	 return king_generate_url($KING_URL_info);
}

function king_generate_url($KING_URL_info) {

     $KING_URL="";
     if(isset($KING_URL_info['host']))
     {
         $KING_URL .= $KING_URL_info['scheme'] . '://';
         if (isset($KING_URL_info['user'])) {
             $KING_URL .= $KING_URL_info['user'];
                 if (isset($KING_URL_info['pass'])) {
                     $KING_URL .= ':' . $KING_URL_info['pass'];
                 }
             $KING_URL .= '@';
         }
         $KING_URL .= $KING_URL_info['host'];
         if (isset($KING_URL_info['port'])) {
             $KING_URL .= ':' . $KING_URL_info['port'];
         }
     }
     if (isset($KING_URL_info['path'])) {
     	$KING_URL .= $KING_URL_info['path'];
     }
     if (isset($KING_URL_info['query'])) {
         $KING_URL .= '?' . $KING_URL_info['query'];
     }
     if (isset($KING_URL_info['fragment'])) {
         $KING_URL .= '#' . $KING_URL_info['fragment'];
     }
     return $KING_URL;
 }

add_filter('add_to_cart_fragments', 'king_woocommerce_update_cart_fragment');
function king_woocommerce_update_cart_fragment( $fragments ) {

	global $woocommerce;

	ob_start();
	?>
	<li class="cart">
		<?php if(!$woocommerce->cart->cart_contents_count): ?>
		<a class="my-cart-link" href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"></a>
		<?php else: ?>
		<a class="my-cart-link my-cart-link-active" href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"></a>
		<div class="cart-contents">
			<?php foreach($woocommerce->cart->cart_contents as $cart_item): ?>
			<div class="cart-content">
				<a href="<?php echo get_permalink($cart_item['product_id']); ?>">
				<?php $thumbnail_id = ($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id']; ?>
				<?php echo get_the_post_thumbnail($thumbnail_id, 'recent-works-thumbnail'); ?>
				<div class="cart-desc">
					<span class="cart-title"><?php echo esc_html( $cart_item['data']->post->post_title ); ?></span><br/>
					<span class="product-quantity"><?php print( $cart_item['quantity'] ); ?> x <?php print( $woocommerce->cart->get_product_subtotal($cart_item['data'], 1) ); ?></span>
				</div>
				</a>
			</div>
			<?php endforeach; ?>
			<div class="cart-checkout">
				<div class="cart-link"><a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"><?php _e('View Cart', 'king'); ?></a></div>
				<div class="checkout-link"><a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>"><?php _e('Checkout', 'king'); ?></a></div>
			</div>
		</div>
		<?php endif; ?>
	</li>
	<?php
	$header_cart = ob_get_clean();
	$fragments['#cart-place .cart'] = $header_cart;

	return $fragments;

}

function  king_cart_place_shortcode(){

	global $woocommerce;
	ob_start();?>
	<div id="cart-place">
		<li class="cart">
			<?php if(!$woocommerce->cart->cart_contents_count): ?>
			<a class="empty-cart" href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"><?php _e('Cart', 'king'); ?></a>
			<?php else: ?>
			<a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?> <?php _e('Item(s)', 'king'); ?> - <?php echo woocommerce_price($woocommerce->cart->subtotal); ?></a>
			<div class="cart-contents">
				<?php foreach($woocommerce->cart->cart_contents as $cart_item): ?>
				<div class="cart-content">
					<a href="<?php echo get_permalink($cart_item['product_id']); ?>">
					<?php $thumbnail_id = ($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id']; ?>
					<?php echo get_the_post_thumbnail($thumbnail_id, 'recent-works-thumbnail'); ?>
					<div class="cart-desc">
						<span class="cart-title"><?php echo esc_html( $cart_item['data']->post->post_title ); ?></span><br/>
						<span class="product-quantity"><?php echo esc_attr( $cart_item['quantity'] ); ?> x <?php echo esc_attr( $woocommerce->cart->get_product_subtotal($cart_item['data'], 1) ); ?></span>
					</div>
					</a>
				</div>
				<?php endforeach; ?>
				<div class="cart-checkout">
					<div class="cart-link"><a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>"><?php _e('View Cart', 'king'); ?></a></div>
					<div class="checkout-link"><a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>"><?php _e('Checkout', 'king'); ?></a></div>
				</div>
			</div>
			<?php endif; ?>
		</li>
	</div>	
<?php

 return ob_get_clean(); 

} 

$king->ext['asc']( 'king_cart_place', 'king_cart_place_shortcode' );

add_action('woocommerce_after_single_product_summary', 'king_woo_social_sharing', 15);
function king_woo_social_sharing(){
	global $king;
						
	if( $king->cfg['showShareBox'] == 1 ){
	
	$link =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$escaped_link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
?>	

	<div class="sharepost woo-social-share">
		
		<ul>
		<?php if( $king->cfg['showShareFacebook'] == 1 ){ ?>
		  <li class="globalBgColor">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $escaped_link ); ?>">
				&nbsp;<i class="fa fa-facebook fa-lg"></i>&nbsp;
			</a>
		  </li>
		  <?php } ?>
		  <?php if( $king->cfg['showShareTwitter'] == 1 ){ ?>
		  <li class="globalBgColor">
			<a href="https://twitter.com/home?status=<?php echo esc_url( $escaped_link ); ?>">
				<i class="fa fa-twitter fa-lg"></i>
			</a>
		  </li>
		  <?php } ?>
		  <?php if( $king->cfg['showShareGoogle'] == 1 ){ ?>
		  <li class="globalBgColor">
			<a href="https://plus.google.com/share?url=<?php echo esc_url( $escaped_link ); ?>">
				<i class="fa fa-google-plus fa-lg"></i>	
			</a>
		  </li>
		  <?php } ?>
		  <?php if( $king->cfg['showShareLinkedin'] == 1 ){ ?>
		  <li class="globalBgColor">
			<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=&amp;title=&amp;summary=&amp;source=<?php echo esc_url( $escaped_link ); ?>">
				<i class="fa fa-linkedin fa-lg"></i>
			</a>
		  </li>
		  <?php } ?>
		  <?php if( $king->cfg['showSharePinterest'] == 1 ){ ?>
		  <li class="globalBgColor">
			<a href="https://pinterest.com/pin/create/button/?url=&amp;media=&amp;description=<?php echo esc_url( $escaped_link ); ?>">
				<i class="fa fa-pinterest fa-lg"></i>
			</a>
		  </li>
		  <?php } ?>
		</ul>
	</div>

	
<?php 
		} 
	}
	
// Add switch woocommerce switch layout

add_action( 'woocommerce_before_shop_loop', 'king_woocommerce_list_or_grid', 20 );
// 
function king_woocommerce_list_or_grid() {
	if ( is_single() ) return;
	global $king, $woocommerce_loop;
?>	
	<div class="king-switch-layout">
			<a id="grid-button" class="grid-view<?php if ( $woocommerce_loop['view'] == 'grid' ) echo ' active'; ?>" href="#"><i class="fa fa-th-large"></i></a>
			<a id="list-button" class="list-view<?php if ( $woocommerce_loop['view'] == 'list' ) echo ' active'; ?>" href="#"><i class="fa fa-list"></i></a>
	</div>
	<?php 
		$html = '';		
		$html .='<script>
			jQuery( document ).ready( function( $ ) {
				$(".king-switch-layout a").on( "click", function(){
					var king_view = $(this).attr("class").replace( "-view", "" );
					$("ul.products li").removeClass("list grid").addClass( king_view );
					$(this).parent().find("a").removeClass("active");
					$(this).addClass("active");
					
					$.cookie(king_shop_view_cookie, king_view);
					$("ul.products li").trigger("styleswitch");
					return false;
				});
			});';
		$html .='</script>';
		print( $html ); 
	} 


add_action('woocommerce_before_shop_loop_item_title', 'king_woocommerce_img_effect', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
function king_woocommerce_img_effect() {

	global $product, $woocommerce;

	$items_in_cart = array();

	if($woocommerce->cart->get_cart() && is_array($woocommerce->cart->get_cart())) {
		foreach($woocommerce->cart->get_cart() as $cart) {
			$items_in_cart[] = $cart['product_id'];
		}
	}

	$id = get_the_ID();
	$in_cart = in_array($id, $items_in_cart);
	$size = 'shop_catalog';

	$gallery = get_post_meta($id, '_product_image_gallery', true);
	$attachment_image = '';
	if(!empty($gallery)) {
		$gallery = explode(',', $gallery);
		$first_image_id = $gallery[0];
		$attachment_image = wp_get_attachment_image($first_image_id , $size, false, array('class' => 'hover-image'));
	}
	$thumb_image = get_the_post_thumbnail($id , $size);

	if($attachment_image) {
		$classes = 'product-detail-image crossfade-images';
	} else {
		$classes = 'product-detail-image';
	}
	
	if( empty( $thumb_image ) ){
		$thumb_image = '<img src="'.$woocommerce->plugin_url().'/assets/images/placeholder.png" />';
	}
	
	echo '<span class="'.$classes.'">';
	print( $attachment_image );
	print( $thumb_image );
	if($in_cart) {
		echo '<span class="cart-loading checked globalBgColor"><i class="icon-check"></i></span>';
	} else {
		echo '<span class="cart-loading"><i class="icon-spinner"></i></span>';
	}
	echo '</span>';
	
}


//======   WooCommerce Magnifier   ============//
function king_magnifier_active(){
	global $king;	
	if($king->cfg['mg_active'] == 0) return false;
	return true;
}

function king_wishlist_actived(){
	global $king;	
	if($king->cfg['wl_actived'] == 0) return false;
	return true;
}

if( !function_exists( 'wc_get_template_part' ) && function_exists( 'woocommerce_get_template_part' ) ){
	function wc_get_template_part( $a, $b ){
		return woocommerce_get_template_part( $a, $b );
	}
}

// Active Wishlist

include THEME_PATH.'/woocommerce/wishlist/init.php';

// Active Ajax navigation

include THEME_PATH.'/woocommerce/filter-product/init.php';

// Active Compare product!

include THEME_PATH.'/woocommerce/compare-product/init.php';

