
<?php
/*
    Template Name: Posts grid 3 columns
*/
?>

<?php get_template_part('templates/top', 'page'); ?>

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
        <div class="twelve columns">
            <?php

            if (is_front_page()) {
                $paged = (get_query_var('page')) ? get_query_var('page') : 1;
            } else {
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            }

            $number_per_page = get_post_meta($post->ID, 'blog_number_to_display', true);
            $number_per_page = ($number_per_page) ? $number_per_page : '12';


            $selected_custom_categories = wp_get_object_terms($post->ID, 'category');
            if(!empty($selected_custom_categories)){
                if(!is_wp_error( $selected_custom_categories )){
                    foreach($selected_custom_categories as $term){
                        $blog_cut_array[] = $term->term_id;
                    }
                }
            }

            $blog_custom_categories = ( get_post_meta(get_the_ID(), 'blog_sort_category',true)) ?  $blog_cut_array : '';

            if ($blog_custom_categories){$blog_custom_categories = implode(",", $blog_custom_categories);}


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

            <div id="grid-posts">

                <?php while (have_posts()) : the_post();

                    get_template_part('templates/loop', 'grid');

                endwhile; ?>

            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>

                <nav class="page-nav">

                    <?php echo dfd_kadabra_pagination(); ?>

                </nav>

            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        </div>
    </div>
</section>
