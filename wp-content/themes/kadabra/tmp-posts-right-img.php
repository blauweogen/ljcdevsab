<?php
/*
Template Name: Posts with right aligned image
*/

get_template_part('templates/top', 'page');  ?>

<section id="layout">

    <div class="row">
        <div class="twelve rows">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
				<?php dfd_link_pages(); ?>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="row">

        <div class="blog-section sidebar-right">
            <section id="main-content" role="main" class="nine columns">

                <?php

                if (is_front_page()) {
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                } else {
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                }

                $number_per_page = get_post_meta($post->ID, 'blog_number_to_display', true);
                $number_per_page = ($number_per_page) ? $number_per_page : '12';


                $selected_custom_categories = wp_get_object_terms($post->ID, 'category');
                if (!empty($selected_custom_categories)) {
                    if (!is_wp_error($selected_custom_categories)) {
                        foreach ($selected_custom_categories as $term) {
                            $blog_cut_array[] = $term->term_id;
                        }
                    }
                }

                $blog_custom_categories = (get_post_meta(get_the_ID(), 'blog_sort_category', true)) ? $blog_cut_array : '';

                if ($blog_custom_categories) {
                    $blog_custom_categories = implode(",", $blog_custom_categories);
                }


                $args = array('post_type' => 'post',
                    'posts_per_page' => $number_per_page,
                    'paged' => $paged,
                    'cat' => $blog_custom_categories
                );


                $temp = $wp_query;
                $wp_query = null;
                $wp_query = new WP_Query($args);


                if (!have_posts()) : ?>


                    <div class="alert">
                        <?php _e('Sorry, no results were found.', 'dfd'); ?>
                    </div>
                    <?php get_search_form(); ?>
                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                    <article <?php post_class(); ?>>

                        <div class="row some-aligned-post right-thumbed">
                            <div class="six columns">
								<div class="entry-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</div>

                                <div class="row">
									<div class="columns one">
										<div class="post-format">
											<?php get_template_part('templates/entry-meta/post-format-icon'); ?>
										</div>
									</div>
									<div class="columns eleven">
										<header>
											<?php get_template_part('templates/entry-meta', 'post'); ?>
										</header>
									</div>
								</div>
                                <div class="entry-content">
                                    <?php the_excerpt(); ?>
                                </div>

                            </div>

                            <div class="post-media six columns">
                                <?php

                                if (has_post_format('video')) {
                                    get_template_part('templates/post', 'video');
                                } elseif (has_post_format('audio')) {
                                    get_template_part('templates/post', 'audio');
                                } elseif (has_post_format('gallery')) {
                                    get_template_part('templates/post', 'gallery');
                                } else {

                                    if (has_post_thumbnail()) {
                                        $thumb = get_post_thumbnail_id();
                                        $img_url = wp_get_attachment_url($thumb, 'full'); //get img URL
                                        $article_image = aq_resize($img_url, 430, 220, true);
                                    } ?>

                                    <div class="entry-thumb">
                                        <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
                                        <?php get_template_part('templates/entry-meta/hover-link'); ?>
                                    </div>

                                <?php } ?>

                            </div>

                        </div>

                    </article>

                <?php endwhile; ?>

                <?php if ($wp_query->max_num_pages > 1) : ?>

                    <nav class="page-nav">

                        <?php echo dfd_kadabra_pagination(); ?>

                    </nav>

                <?php endif; ?>

                <?php wp_reset_postdata(); ?>

            </section>

            <?php get_template_part('templates/sidebar', 'right'); ?>

        </div>
</section>
