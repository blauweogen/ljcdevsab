<?php
/*
Template Name: Portfolio grid 4 columns Excerpt
*/

get_template_part('templates/top', 'page'); ?>

<section id="layout">

    <div class="row">
        <div class="twelve columns">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
				<?php dfd_link_pages(); ?>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="row">
        <div class="twelve columns">
            <?php

            $number_per_page = (get_post_meta($post->ID, 'folio_number_to_display', true)) ? get_post_meta($post->ID, 'folio_number_to_display', true) : '16';

            $selected_custom_categories = wp_get_object_terms($post->ID, 'my-product_category');
            if (!empty($selected_custom_categories)) {
                if (!is_wp_error($selected_custom_categories)) {
                    foreach ($selected_custom_categories as $term) {
                        $blog_cut_array[] = $term->term_id;
                    }
                }
            }

            $folio_custom_categories = (get_post_meta(get_the_ID(), 'folio_sort_category', true)) ? $blog_cut_array : '';

            if ($folio_custom_categories) {
                $folio_custom_categories = implode(",", $folio_custom_categories);
            }

            if (is_front_page()) {
                $paged = (get_query_var('page')) ? get_query_var('page') : 1;
            } else {
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            }


            ?>


            <div id="grid-folio" class="col-4 row">

                <?php

                if ($folio_custom_categories) {
                    $args = array(
                        'post_type' => 'my-product',
                        'posts_per_page' => $number_per_page,
                        'paged' => $paged,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'my-product_category',
                                'field' => 'id',
                                'terms' => $blog_cut_array,
                            )
                        )
                    );
                } else {
                    $args = array(
                        'post_type' => 'my-product',
                        'posts_per_page' => $number_per_page,
                        'paged' => $paged
                    );
                }

                $temp = $wp_query;
                $wp_query = null;
                $wp_query = new WP_Query($args);


                while ($wp_query->have_posts()) : $wp_query->the_post();

                    if (has_post_thumbnail()) {
                        $thumb = get_post_thumbnail_id();
                        $img_url = wp_get_attachment_url($thumb, 'full'); //get img URL
                    } else {
                        $img_url = get_template_directory_uri() . '/img/no-image-large.jpg';
                    }
                    $article_image = aq_resize($img_url, 400, 999, false); //resize & crop img

                    ?>

                    <article class="three columns project">
                        <div class="entry-thumb entry-thumb-small-cros">
                            <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
							<?php get_template_part('templates/portfolio/thumb', 'hover-mini'); ?>
                        </div>

                        <h4 class="box-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

                        <?php get_template_part('templates/folio', 'terms'); ?>
						
						<?php the_excerpt(); ?>
	
						<?php echo DFD_HTML::read_more(get_permalink()); ?>
                    </article>

                <?php endwhile; ?>

            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>

                <nav class="page-nav">

                    <?php echo dfd_kadabra_pagination(); ?>

                </nav>

            <?php endif; ?>

            <?php
            $wp_query = null;
            $wp_query = $temp;
            wp_reset_query();
            ?>

        </div>
    </div>
</section>
