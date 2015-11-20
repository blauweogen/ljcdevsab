<?php

function sb_best_selling_products($atts, $content = null) {
	global $woocommerce_loop;

	extract(shortcode_atts(array('per_page' => '12', 'columns' => 4, 'show_title' => false), $atts));

	$show_title = (bool) $show_title;

	ob_start();

	best_selling_products($per_page, $columns, $show_title);

	return ob_get_clean();
}

function best_selling_products($posts_per_page = 12, $columns = 4, $show_title = false) {
	woocommerce_get_template('best_selling_products.php', array(
		'posts_per_page' => $posts_per_page,
		'columns' => $columns,
		'show_title' => $show_title
	));
}

add_shortcode('sb_best_selling_products', 'sb_best_selling_products');
