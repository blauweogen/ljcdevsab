<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="' . $effects . '" data-gen-offset="bottom-in-view';
} else {
    $cr_effect = '';
}

$module_id = uniqid('mvb_recent_block');

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

if (isset($categories) && $categories) {
	$selected_category = true;
} else {
	$selected_category = false;
}

?>

<div id="<?php echo $module_id; ?>" class="module recent-block recent-block-isotope  <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>" <?php echo $addition_css_styles; ?>>
	
	<?php if (!isset($simple_mode) || !$simple_mode): ?>
	
	<div class="row">
		<?php if (!empty($main_title) && $main_title != ''): ?>
			<?php include(dirname(__FILE__).'/_title.php'); ?>
		<?php endif; ?>
		
		<div class="sort-panel columns">
			<ul class="filter">
				<li class="active"><a data-filter=".project" href="#"><?php echo __('All', 'dfd') ?></a></li>
				<?php

				$taxonomy = 'my-product_category';
				$args = array();
				if (isset($categories)) {
					$args['include'] = $categories;
				}
				
				$categories_arr = get_terms($taxonomy, $args);
				foreach ($categories_arr as $category) {
					echo '<li><a data-filter=".project[data-category~=\'' . strtolower(preg_replace('/\s+/', '-', $category->slug)) . '\']" href="#">' . $category->name . '</a></li>';
				}

				?>
			</ul>
		</div>
	</div>
	
    <?php endif; ?>

	<div class="recent-works-list">
		<?php 
		$posts_per_page = 8;
		
		if (!isset($selected_category) || !$selected_category) {
			$args = array(
				'post_type' => 'my-product',
				'posts_per_page' => $posts_per_page,
			);
		} else {
			$args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 'my-product_category',
						'field' => 'id',
						'terms' => explode(',',$categories),
					)
				),
				'post_type' => 'my-product',
				'posts_per_page' => $posts_per_page,
			);
		}
		?>
		
		<?php
		$the_query = new WP_Query($args);
		while ($the_query->have_posts()) : $the_query->the_post();
			$terms = get_the_terms(get_the_ID(), 'my-product_category');
		
			if (has_post_thumbnail()) {
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url($thumb, 'full'); //get img URL
			} else {
				$img_url = get_template_directory_uri() . '/img/no-image-large.jpg';
			}

			$article_image = aq_resize($img_url, 600, 450, true);
		?>
		
		<div class="recent-works-item project" data-category="<?php foreach ($terms as $term) {echo strtolower(preg_replace('/\s+/', '-', $term->slug)) . ' '; } ?>">
			<div class="entry-thumb">
				<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
			</div>
			
			<?php get_template_part('templates/portfolio/entry-meta'); ?>
		</div>
		
		<?php endwhile; // END the Wordpress Loop ?>
	</div>
	<div class="recent-works-list-hidden"></div>
	<?php 
		wp_enqueue_script('isotope');
		wp_enqueue_script('isotope_recenworks');
	?>
	
	<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#<?php echo $module_id; ?>').sbIsotopeRecentWorks();
	});
	</script>
</div>
