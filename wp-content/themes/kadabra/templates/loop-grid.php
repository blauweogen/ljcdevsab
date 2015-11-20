<?php
$format = get_post_format();
if (false === $format) {
    $format = 'standard';
} ?>

<article class="hnews hentry small-news four columns post post-<?php the_ID(); ?> <?php echo 'format-' . $format ?>">

	<div class="clearfix">
		<div class="entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>

		<?php if (strcmp(DfdThemeSettings::get('post_header'), '1') === 0) : ?>
			<?php get_template_part('templates/entry-meta', 'post'); ?>
		<?php endif; ?>
	</div>

    <?php
    if (has_post_thumbnail()) {
		if (!DfdThemeSettings::get('thumb_image_crop')) {
			$image_crop = true;
		} else {
			$image_crop = DfdThemeSettings::get('thumb_image_crop');
		}
        $thumb = get_post_thumbnail_id();
        $img_url = wp_get_attachment_url($thumb, 'large'); //get img URL

        if (is_page_template('tmp-posts-masonry-2-side.php')){
            $article_image = aq_resize($img_url, 407, 270, $image_crop);
        } elseif (is_page_template('tmp-posts-masonry-2.php')){
            $article_image = aq_resize($img_url, 567, 320, $image_crop);
        } else {
            $article_image = aq_resize($img_url, 407, 270, $image_crop);
        }
        ?>
        <div class="entry-thumb">
            <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
            <?php get_template_part('templates/entry-meta/hover-link'); ?>
        </div>
    <?php
    }
	?>

	<div class="entry-summary">
		<p><?php the_excerpt() ?></p>
	</div>
</article>
