<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$subtitle = get_post_meta(get_the_ID(), 'dfd_product_product_subtitle', true);

?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>

<?php if (!empty($subtitle)): ?>
	<div class="produt-subtitle text-left"><?php echo $subtitle; ?></div>
<?php endif; ?>
