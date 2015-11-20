<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="' . $effects . '" data-gen-offset="bottom-in-view';
} else {
    $cr_effect = '';
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



<div class="module module-last-x-posts <?php echo (!empty($css)) ? $css : ''; ?> "  <?php echo $addition_css_styles; ?> >

	<?php include(dirname(__FILE__).'/_title.php');?>

<?php

echo '<div class="block-news-feature">';

$the_query = null;
$sticky = get_option('sticky_posts');

if (!empty($categories)) {
    $args = array(
        'category__in' => array($categories),
        'posts_per_page' => 1,
        'ignore_sticky_posts' => 1,
        'post__not_in' => $sticky,
    );
} else {
    $args = array(
        'posts_per_page' => 1,
        'ignore_sticky_posts' => 1,
        'post__not_in' => $sticky,
    );
}


$the_query = new WP_Query($args);
while ($the_query->have_posts()) :
    $the_query->the_post();      ?>

    <div class="featured-news row <?php echo $cr_effect; ?>">

        <article class="hnews hentry small-news ">

            <?php
            if (has_post_thumbnail()) {
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url($thumb, 'medium'); //get img URL
                $article_image = aq_resize($img_url, 380, 270, true);
                ?>

                <div class="six columns">
                    <div class="entry-thumb">
                        <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
                        <?php get_template_part('templates/entry-meta/hover-link'); ?>
                    </div>
                </div>

            <?php } ?>

            <div class="six columns horizontal">
				
				<div class="row">
					<div class="columns two">
						<span class="entry-format">
							<?php get_template_part('templates/entry-meta/post-format-icon'); ?>
						</span>
					</div>
					<div class="columns ten">
						<div class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
					</div>
				</div>
				
				<?php get_template_part('templates/entry-meta', 'post'); ?>

                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>

            </div>

        </article>

    </div>

<?php endwhile;
wp_reset_postdata(); ?>

    <div class="other-news other-news-3 row">

<?php

$the_query = null;
$sticky = get_option('sticky_posts');
if (!empty($categories)) {
    $args = array(
        'category_name' => $categories,
        'posts_per_page' => 2,
        'offset' => 1,
        'ignore_sticky_posts' => 1,
        'post__not_in' => $sticky,
    );

} else {
    $args = array(
        'posts_per_page' => 2,
        'offset' => 1,
        'ignore_sticky_posts' => 1,
        'post__not_in' => $sticky,
    );
}
$the_query = new WP_Query($args);

while ($the_query->have_posts()) :
    $the_query->the_post(); ?>
    <div class="six columns">
        <article class="hentry mini-news clearfix <?php echo $cr_effect; ?>">


            <?php if (has_post_thumbnail()) {
                $thumb = get_post_thumbnail_id();
                $img_url = wp_get_attachment_url($thumb, 'thumb'); //get img URL
                $article_image = aq_resize($img_url, 108, 108, true);
                ?>

				<div class="entry-thumb">
					<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
					<?php get_template_part('templates/entry-meta/hover-link-small'); ?>
				</div>

            <?php } ?>

            <div class="box-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>

            <?php get_template_part('templates/entry-meta', 'widget'); ?>

        </article>
    </div>
<?php  endwhile;
wp_reset_postdata();

echo '</div></div></div>';
