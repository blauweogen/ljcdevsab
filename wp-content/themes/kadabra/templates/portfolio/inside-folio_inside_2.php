<div class="twelve columns">
	<?php
	$embed_url = get_post_meta($post->ID, 'folio_embed', true);

	if ($embed_url):

		$embed_code = wp_oembed_get($embed_url);

		echo '<div class="single-folio-video">' . $embed_code . '</div>';

	endif;

	?>

	<?php
	if (metadata_exists('post', $post->ID, '_my_product_image_gallery')) {
		$my_product_image_gallery = get_post_meta($post->ID, '_my_product_image_gallery', true);
	} else {
		// Backwards compat
		$attachment_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
		$my_product_image_gallery = implode(',', $attachment_ids);
	}

	$attachments = array_filter(explode(',', $my_product_image_gallery));

	if ($attachments) {


		echo '<div id="my-work-slider" class="loading"><ul class="slides">';

		foreach ($attachments as $attachment_id) {

			$image_attributes = wp_get_attachment_image_src($attachment_id); // returns an array
			$image_src = wp_get_attachment_image_src($attachment_id, 'full'); // returns an array

			$thumb_image = aq_resize($image_attributes[0], 126, 88, true);

			echo '<li data-thumb="' . $thumb_image . '">';
			echo '<a href="'.$image_src[0].'" data-rel="prettyPhoto[pp_gal]">';
			echo wp_get_attachment_image($attachment_id, 'full');
			echo '</a>';
			echo '</li>';
		}
		echo '  </ul></div>';
	} elseif (has_post_thumbnail() && (!$embed_url)) {

		$thumb = get_post_thumbnail_id();
		echo wp_get_attachment_image($thumb, 'full');
	}

	if (DfdThemeSettings::get('portfolio_page_select')) {
		$page = DfdThemeSettings::get('portfolio_page_select');
		$slug = get_permalink($page);
	}
	?>
</div>
<article class="folio-info folio-info-variant-2 twelve columns">

	<dl class="tabs contained horisontal clearfix">

		<dd class="active"><a href="#folio-desc-1"><?php _e('Description', 'dfd') ?></a></dd>

		<?php
		if (function_exists('get_field_objects')) {
			$fields = get_field_objects();
		} else {
			$fields = false;
		}
		if ($fields) {
			$i = 2;
			foreach ($fields as $field_name => $field) {
				if ($field['label']) {
					echo '<dd><a href="#folio-desc-' . $i . '">';
					echo $field['label'];
					echo '</a></dd>';

					$i++;
				}
			}
		} ?>

	</dl>
	<ul class="tabs-content contained">
		<li id="folio-desc-1Tab" class="active">

			<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>

		</li>

		<?php if ($fields) {
			$i = 2;
			foreach ($fields as $field_name => $field) {
				if ($field['label']) {
					echo '<li id="folio-desc-' . $i . 'Tab">';
					echo do_shortcode($field['value']);
					echo '</li>';

					$i++;
				}
			}
		} ?>
	</ul>

	<?php get_template_part('templates/entry-meta', 'portfolio'); ?>

	<?php get_template_part('templates/folio', 'terms'); ?>
</article>	
<div class="twelve columns">
	<div class="pages-nav twelve columns ">

		<?php previous_post_link('<div class="prev-link">%link</div>', 'Prev work'); ?>

		<?php if(!empty($page)): ?>
			<a class="to-folio" href="<?php echo $slug; ?>"></a>
		<?php endif; ?>

		<?php next_post_link('<div class="next-link">%link</div>', 'Next work'); ?>

	</div>

</div>
