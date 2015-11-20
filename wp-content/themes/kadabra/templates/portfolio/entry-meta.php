<?php
	if (has_post_thumbnail()) {
		$thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url($thumb, 'full');
	} else {
		$img_url = get_template_directory_uri() . '/img/no-image-large.jpg';
	}
	
	$embed_url = get_post_meta($post->ID, 'folio_embed', true);
	
	if (!empty($embed_url)) {
		$img_url = $embed_url;
	}
	
	if (metadata_exists('post', $post->ID, '_my_product_image_gallery')) {
		$my_product_image_gallery = get_post_meta($post->ID, '_my_product_image_gallery', true);
	} else {
		// Backwards compat
		$attachment_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
		$my_product_image_gallery = implode(',', $attachment_ids);
	}

	$attachments = array_filter(explode(',', $my_product_image_gallery));

	$gallery_id = uniqid(get_the_ID());
?>
<div class="portfolio-entry-hover">
	<a class="portfolio-entry-thumb" href="<?php echo $img_url; ?>" data-rel="prettyPhoto[<?php echo $gallery_id; ?>]">
		<i class="icon-link-2"></i>
	</a>
</div>
<div class="portfolio-entry-meta row">
	<div class="columns nine mobile-four">
		<div class="portfolio-entry-meta-info">
			<div class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
			<?php get_template_part('templates/folio', 'terms'); ?>
		</div>
	</div>
	<div class="columns tree mobile-hide">
		<div class="social-share">
			<?php echo getPostLikeLink(); ?>
		</div>
	</div>
</div>
<?php if (!empty($attachments)): ?>
<div class="hide">
<?php
	foreach ($attachments as $attachment_id) {
		$image_src = wp_get_attachment_image_src($attachment_id, 'full');
		if (empty($image_src[0])) {
			continue;
		}
		$attachment_img_url = $image_src[0];
		
		if (strcmp($attachment_img_url, $img_url)===0) {
			continue;
		}
		
		echo '<a href="'. $attachment_img_url .'" data-rel="prettyPhoto['. $gallery_id .']"></a>';
	}
?>
</div>
<?php endif; ?>