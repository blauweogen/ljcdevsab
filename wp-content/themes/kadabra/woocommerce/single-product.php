<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-my-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.2.10
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

?>

<?php

if (DfdThemeSettings::get('stan_header')) {
    echo '<div id="stuning-header" style="';
    if(DfdThemeSettings::get('stan_header_color')) { echo ' background-color: ' . DfdThemeSettings::get('stan_header_color') . '; '; }
    if (DfdThemeSettings::get('stan_header_image')) { echo 'background-image: url(' . DfdThemeSettings::get('stan_header_image') . ');  background-position: center;'; }
    echo '">';
} ?>

    <div class="row">
        <div class="twelve columns">
            <div id="page-title">
                <a href="javascript:history.back()" class="back"></a>

                <div class="page-title-inner">
                    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
					<div class="page-title-inner-subtitle"><?php the_title(); ?></div>

                    <div class="breadcrumbs">
                        <?php woocommerce_breadcrumb() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (DfdThemeSettings::get('stan_header')) {
        echo '</div>';
    } ?>
<section id="layout">
    <div class="row">
        <div class="nine columns">

            <?php while (have_posts()) : the_post(); ?>

                <?php woocommerce_get_template_part('content', 'single-product'); ?>

            <?php endwhile; // end of the loop. ?>

        </div>


        <div class="three columns">
            <?php dynamic_sidebar('shop-sidebar'); ?>
        </div>


    </div>
</section>