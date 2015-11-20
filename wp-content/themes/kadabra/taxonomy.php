
<?php get_template_part('templates/top','page'); ?>

<section id="layout">
    <div class="row">

<?php global $NHP_Options;

    if (!have_posts()) : ?>

        <div class="alert">
            <?php _e('Sorry, no results were found.', 'dfd'); ?>
        </div>
        <?php get_search_form(); ?>
        <?php endif; ?>

<?php while (have_posts()) : the_post();

        get_template_part('templates/portfolio', 'item');

      endwhile;

if ($wp_query->max_num_pages > 1) : ?>

        <nav class="page-nav">
            <?php if (get_next_posts_link()) : ?>
            <span class="older"><?php next_posts_link(__('Older', 'dfd')); ?></span>
            <?php endif; ?>
            <?php if (get_previous_posts_link()) : ?>
            <span class="newer"><?php previous_posts_link(__('Newer', 'dfd')); ?></span>
            <?php endif; ?>
        </nav>

        <?php endif; ?>



    </div>
</section>