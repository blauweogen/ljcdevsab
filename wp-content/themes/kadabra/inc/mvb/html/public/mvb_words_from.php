<?php if (!empty($effects)) {
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

<div class="module words_from_module <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>

	<div class="words_from_wrap">
		<div>
			<div class="avatar">
				<?php
				if ($image) {
					$img_url = wp_get_attachment_url($image);
					$article_image = aq_resize($img_url, 150, 150, true);
				?>

				<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>">

				<?php } ?>
			</div>

			<div class="cite">
				<?php if (!empty($title)): ?>
					<span class="quote-author box-name"><?php echo $title; ?></span>
				<?php endif;

				if (!empty($subtitle)): ?>
					<span class="quote-sub dopinfo"><?php echo $subtitle; ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="clear"></div>
		<div>
			<blockquote>
				<?php echo mvb_parse_content($content) ?>
			</blockquote>
		</div>
	</div>

</div>
