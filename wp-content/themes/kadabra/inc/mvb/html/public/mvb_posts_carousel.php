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
if (!isset($unique_id) || empty($unique_id)) $unique_id = 'module-post-carousel';

$unique_id = uniqid($unique_id);

?>
<div class="module recent-block module-post-carousel <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>" <?php echo $addition_css_styles; ?>>
    <?php include(dirname(__FILE__).'/_title.php'); ?>
	
    <?php
    $the_query = null;
    $sticky = get_option('sticky_posts');

    if ($categories){
        $args = array(
            'category_name' => $categories,
            'posts_per_page' => $no_of_posts,
            'ignore_sticky_posts' => 1,
            'post__not_in' => $sticky,
        );
    } else {
        $args = array(
            'posts_per_page' => $no_of_posts,
            'ignore_sticky_posts' => 1,
            'post__not_in' => $sticky,
        );
    }

    $the_query = new WP_Query($args);
	?>
	<div id="<?php echo $unique_id; ?>" class="post-carousel">
		<ul>
		<?php while ($the_query->have_posts()) : $the_query->the_post();
			$format = get_post_format();
			if (false === $format) $format = 'standard';

			$thumb = get_post_thumbnail_id();

			if (has_post_thumbnail()) {
				$img_url = wp_get_attachment_url($thumb, 'medium'); //get img URL
				$article_image = aq_resize($img_url, 580, 440, true);
			} else {
				$article_image = $no_image;
			}
		?>

			<li class="post-carousel-item">
				<div class="entry-thumb">
					<div class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					<img src="<?php echo $article_image; ?>" alt="<?php the_title(); ?>" />
					<?php /*
					<div class="entry-meta"><i class="moon-tags-2"></i> <?php the_category(); ?></div>
					<a href="<?php the_permalink(); ?>" class="link"></a>
					*/ ?>
				</div>
			</li>

		<?php endwhile; ?>
		</ul>
		
		<?php echo DFD_Carousel::controls(); ?>
	</div>
	<?php wp_reset_postdata(); ?>
</div>

<script type="text/javascript">
(function($){
	$(document).ready(function () {
		var $slider = $('#<?php echo $unique_id ?>');
		var columns = 2;
		var gutter_width = 4;
		
		var eqSlides = function() {
			var width = $slider.width();

			switch(true) {
				case (width <= 600): columns = 1; break;
				case (width <= 780): columns = 2; break;
				default: columns = 3;
			}
		};

		eqSlides(); $(window).load(eqSlides).resize(eqSlides);
		
		$slider
			.on('jcarousel:reload jcarousel:create', function () {
				var width = Math.floor( ($slider.innerWidth() + gutter_width) / columns );

				$slider.jcarousel('items')
					.css('width', width + 'px');
			})
			.jcarousel({
				wrap: 'circular'
			});
			
		if (typeof($slider.touch) === 'function') {
			$slider.touch();
		}
			
		$('.jcarousel-control-prev', $slider)
			.jcarouselControl({
				carousel: $slider,
				target: '-=1'
			});
		$('.jcarousel-control-next', $slider)
			.jcarouselControl({
				carousel: $slider,
				target: '+=1'
			});
	});
})(jQuery);
</script>
