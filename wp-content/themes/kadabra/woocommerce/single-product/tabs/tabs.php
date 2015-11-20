<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) :
    $i ='1';
?>

<dl class="tabs contained horisontal  twelve columns">
    <?php foreach ( $tabs as $key => $tab ) :
    if ($i == '1'){
        $class = 'active';
    } else {
        $class = '';
    }
    ?>

    <dd class="<?php echo $key ?>_tab <?php echo $class; ?>">
        <a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
    </dd>

    <?php $i++; endforeach; ?>
</dl>

<ul class="tabs-content contained clearfix twelve columns ">

    <?php
    $it ='1';
    foreach ( $tabs as $key => $tab ) :
        if ($it == '1'){
            $class = 'active';
        } else {
            $class = '';
        }

        ?>

    <li id="tab-<?php echo $key ?>Tab" class="<?php echo $class; ?>">
        <?php call_user_func( $tab['callback'], $key, $tab ) ?>
    </li>

        <?php $it++; endforeach; ?>


</ul>

<?php endif; ?>
