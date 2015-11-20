<?php
$image_crop = DfdThemeSettings::get('thumb_image_crop');
if ($image_crop == "") {
    $image_crop = true;
}

$format = get_post_format();
if (false === $format) {
    $format = 'standard';
} 
?>
<article <?php post_class(); ?>>

    <div class="entry-media">
        <?php
        

        if (has_post_format('video')) {
            get_template_part('templates/post', 'video');
        } elseif (has_post_format('audio')) {
            get_template_part('templates/post', 'audio');
        } elseif (has_post_format('gallery')) {
            get_template_part('templates/post', 'gallery');
        } elseif (has_post_format('quote')) {
//            get_template_part('templates/post', 'quote');
        } else {

        if (has_post_thumbnail()) {
            $thumb = get_post_thumbnail_id();
            $img_url = wp_get_attachment_url($thumb, 'full'); //get img URL
            if (DfdThemeSettings::get('post_thumbnails_width') && DfdThemeSettings::get('post_thumbnails_height')) {
                $article_image = aq_resize($img_url, DfdThemeSettings::get('post_thumbnails_width'), DfdThemeSettings::get('post_thumbnails_height'), $image_crop);
            } else {
                $article_image = aq_resize($img_url, 900, 400, $image_crop);
            }
            ?>

            <div class="entry-thumb">
                <img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
                <a href="<?php the_permalink(); ?>" class="link"></a>
            </div>


        <?php
        }
        } ?>

    </div>
	<div class="clearfix">
		<div class="entry-format">
			<?php get_template_part('templates/entry-meta/post-format-icon'); ?>
		</div>

		<div class="entry-meta-wrap">
			<div class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>

			<?php if (strcmp(DfdThemeSettings::get('post_header'), '1') === 0) : ?>
				<?php get_template_part('templates/entry-meta', 'post'); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>

</article>