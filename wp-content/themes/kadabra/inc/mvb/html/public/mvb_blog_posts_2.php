<?php
/**
 * Tiny Post list. View
 */
if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="'.$effects.'" data-gen-offset="bottom-in-view';
} else {
    $cr_effect ='';
}

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>

<div class="module_posts-style-2 <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php');?>

    <?php


    if (!empty($categories)){
        $args = array(
            'category_name' => $categories,
            'posts_per_page' => $no_of_posts,
            'ignore_sticky_posts' => 'true'
        );
    } else {
        $args = array(
            'posts_per_page' => $no_of_posts,
            'ignore_sticky_posts' => 'true'
        );
    }

    $the_query = new WP_Query($args);

    ?>
    <div class="post-list">

        <?php

        while ($the_query->have_posts()) : $the_query->the_post(); ?>

            <article class="hentry mini-news clearfix <?php echo $cr_effect; ?>">
                <?php

                if (has_post_thumbnail()) {
                    $thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url($thumb, 'thumb'); //get img URL
                    $article_image = aq_resize($img_url, 80, 80, true);
                    ?>
                    <div class="entry-thumb">
                        <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
						<?php get_template_part('templates/entry-meta/hover-link-small'); ?>
                    </div>

                <?php } ?>

                <div class="box-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>

                <?php if (!empty($show_meta)): ?>
                    <?php get_template_part('templates/entry-meta', 'mini'); ?>
                <?php endif; ?>

            </article>

        <?php endwhile; ?>

    </div>

    <?php wp_reset_postdata(); ?>

</div>
