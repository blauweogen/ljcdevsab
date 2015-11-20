<?php get_template_part('templates/top', 'page'); ?>

<section id="layout">
    <div class="row">

        <?php
        set_layout('404');?>

        <article id="post-0" class="post no-results not-found">

            <header class="entry-header" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; margin-bottom: 15px">
                <h1 style="text-transform: uppercase; font-size: 26px; font-weight: 300"><?php _e( '<strong>404 Error </strong>- no page here', 'dfd' ); ?></h1>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'dfd' ); ?></p>
            </div><!-- .entry-content -->

            <header class="entry-header" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; margin-bottom: 15px">
                <h2 style="text-transform: uppercase;  font-size: 16px;"><?php _e('Look it in archives', 'dfd'); ?></h2>
            </header><!-- .entry-header -->

            <select name="archive-menu" onChange="document.location.href=this.options[this.selectedIndex].value;">
                <option value="">Select month</option>
                <?php wp_get_archives('type=monthly&format=option'); ?>
            </select>

            <header class="entry-header" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; margin-bottom: 15px">
                <h2 style="text-transform: uppercase; font-size: 16px;"><?php _e('Search can be used', 'dfd'); ?></h2>
            </header><!-- .entry-header -->

            <?php get_search_form(); ?>

            <header class="entry-header" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; margin-bottom: 15px">
                <h2 style="text-transform: uppercase; font-size: 16px;"><?php _e('Most Used Categoriesd', 'dfd'); ?></h2>
            </header><!-- .entry-header -->

            <ul>
                <?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
            </ul>


            <header class="entry-header" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 15px; margin-bottom: 15px">
                <h2 style="text-transform: uppercase; font-size: 16px;"><?php _e('Tags also can be used', 'dfd'); ?></h2>
            </header><!-- .entry-header -->

            <div class="tags-widget">
                <?php wp_tag_cloud('smallest=10&largest=10&number=30'); ?>
            </div>
        </article><!-- #post-0 -->

        <?php set_layout('404', false);

        ?>

    </div>
</section>