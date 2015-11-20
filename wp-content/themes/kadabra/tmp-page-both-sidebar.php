
<?php
/*
Template Name: Page with both sidebars
 */
get_template_part('templates/top','page'); ?>

<section id="layout">
    <div class="row">

        <div class="blog-section sidebar-both">
            <section id="main-content" role="main" class="six columns">

                <?php get_template_part('templates/content', 'page'); ?>

            </section>
            <?php get_template_part('templates/sidebar', 'left'); ?>
        </div>

        <?php  get_template_part('templates/sidebar', 'right'); ?>

    </div>
</section>