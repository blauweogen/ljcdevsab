<?php

if (has_post_thumbnail()) {
	if (!DfdThemeSettings::get('thumb_image_crop')) {
		$image_crop = true;
	} else {
		$image_crop = DfdThemeSettings::get('thumb_image_crop');
	}
	
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
		<?php get_template_part('templates/entry-meta/hover-link'); ?>
	</div>
<?php
}