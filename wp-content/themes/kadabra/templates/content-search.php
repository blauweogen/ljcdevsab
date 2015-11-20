<?php global $NHP_Options; ?>

<?php if (!have_posts()): ?>

    <article id="post-0" class="post no-results not-found">
        <header class="entry-header">
            <h1><?php _e( 'Nothing Found', 'dfd' ); ?></h1>
        </header>

        <div class="entry-content">
            <p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'dfd' ); ?></p>
            <div class="widget_search">
				<?php get_search_form(); ?>
			</div>
        </div>

    </article>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>

	<?php get_template_part('templates/loop-search'); ?>

<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>

<nav class="page-nav">

    <?php echo dfd_kadabra_pagination(); ?>

</nav>

<?php endif; ?>
