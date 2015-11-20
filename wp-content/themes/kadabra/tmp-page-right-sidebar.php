
<?php
/*
Template Name: Page with right sidebar
 */
get_template_part('templates/top','page'); ?>

<section id="layout">
    <div class="row">

        <div class="blog-section sidebar-right">
            <section id="main-content" role="main" class="nine columns">

                <?php get_template_part('templates/content', 'page'); ?>

            </section>
            <?php get_template_part('templates/sidebar', 'right'); ?>
        </div>

    </div>
</section>