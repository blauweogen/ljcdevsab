<?php
$format = get_post_format();
if (false === $format) {
    $format = 'standard';
} 
?>
<article <?php post_class(); ?>>
	
	<div class="clearfix">
		<?php if (strcmp(DfdThemeSettings::get('post_header'), '1') === 0) : ?>
		<div class="entry-calend-date">
			<?php get_template_part('templates/entry-meta/post-calend-date'); ?>
		</div>
		<?php endif; ?>

		<div class="entry-meta-wrap" <?php if (strcmp(DfdThemeSettings::get('post_header'), '1') !== 0) : ?>style="margin-left: 0;"<?php endif; ?>>
			<div class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
			
			<?php if (strcmp(DfdThemeSettings::get('post_header'), '1') === 0) : ?>
				<?php get_template_part('templates/entry-meta', 'archive'); ?>
			<?php endif; ?>
		</div>
	</div>

    <div class="entry-media">
	<?php
		switch(true) {
			case has_post_format('video'):
				get_template_part('templates/post', 'video');
				break;
			case has_post_format('audio'):
				get_template_part('templates/post', 'audio');
				break;
			case has_post_format('gallery'):
				get_template_part('templates/post', 'gallery');
				break;
			case has_post_format('quote'):
				//get_template_part('templates/post', 'quote');
				break;
			default:
				get_template_part('templates/thumbnail/post');
		}
	?>
    </div>
	<div class="clearfix">
		<div class="entry-format">
			<?php get_template_part('templates/entry-meta/post-format-icon'); ?>
		</div>
		<div class="entry-content">
			<?php if (has_post_format('quote')): ?>
				<?php get_template_part('templates/post', 'quote'); ?>
			<?php else: ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
		</div>
	</div>

</article>