<?php

/*
Template Name: Page testimonials
 */

get_template_part('templates/top','page'); ?>

<section id="layout">
    <div class="row testimonials">
		<?php 
			$page = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$wp_query = new WP_Query(array(
				'post_type' => 'testimonials',
				'paged' => $page,
				'order' => 'ASC',
				'orderby' => 'menu_order',
			));

			if ( $wp_query->have_posts() ) {
				while ($wp_query->have_posts()) { 
					$wp_query->the_post();
					get_template_part('templates/content', 'testimonials');
				}
			}
			
			if ($wp_query->max_num_pages > 1) : ?>
                <nav class="page-nav">
                    <?php echo dfd_kadabra_pagination(); ?>
                </nav>
            <?php endif;
		?>
    </div>
</section>