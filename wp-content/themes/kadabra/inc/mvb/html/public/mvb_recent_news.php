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

<?php $unique_id = 'mrn-'.mt_rand(); ?>

<div class="module module-recent-news <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

	<?php include(dirname(__FILE__).'/_title.php'); ?>

	<div class="row">
	
		<div class="columns <?php echo (!empty($description)?'nine':'twelve'); ?>" <?php echo $addition_css_styles; ?>>
			<div class="recent-news-list" id="<?php echo $unique_id; ?>">
				<ul>
				<?php
				$the_query = null;
				$sticky = get_option( 'sticky_posts' );

				if (!empty($categories)) {
					$args = array(
						'category_name' => $categories,
						'posts_per_page' => -1,
						'ignore_sticky_posts' => 1,
						'post__not_in' => $sticky,
					);
				} else {
					$args = array(
						'posts_per_page' => -1,
						'ignore_sticky_posts' => 1,
						'post__not_in' => $sticky,
					);
				}
				?>

				<?php
				$the_query = new WP_Query($args);
				while ($the_query->have_posts()) :
					$the_query->the_post();      
				?>

				<?php if (has_post_thumbnail()) : ?>

				<li class="item">
					<?php
					if (has_post_thumbnail()) {
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url($thumb, 'medium'); //get img URL
					} else {
						$img_url = get_template_directory_uri() . '/img/no-image-large.jpg';
					}

					$article_image = aq_resize($img_url, 460, 380, true);
					?>

					<div class="entry-thumb entry-thumb-hover">
						<img src="<?php echo $article_image ?>" alt="<?php the_title(); ?>"/>
						<?php get_template_part('templates/entry-meta/hover-link'); ?>
					</div>
					<div class="box-name">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</div>

					<div class="text-center">
						<?php get_template_part('templates/entry-meta', 'widget'); ?>
					</div>
				</li>

				<?php endif; ?>

				<?php 
				endwhile;
				wp_reset_query(); 
				?>
				</ul>

				<?php echo DFD_Carousel::controls(); ?>
			</div>
		</div>

		<?php if (!empty($description) && $description != '') : ?>
		<div class="recent-news-description columns three">
			<div class="recent-news-description-content">
				<?php echo wpautop($description); ?>
			</div>

			<?php if (!empty($show_link) && $show_link) : ?>
				<?php echo DFD_HTML::read_more('', ($link_title != '') ? $link_title : __('Read all news', 'dfd')); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
	</div>
	
</div>

<script type="text/javascript">
(function($){
	$(document).ready(function(){
		var $slider = jQuery('#<?php echo $unique_id ?>');
		var columns = 4;
		var gutter_width = 20;
		
		var eqSlides = function(){
			var width = $slider.width();

			switch(true) {
				case (width <= 300): columns = 1; break;
				case (width <= 600): columns = 2; break;
				case (width <= 780): columns = 3; break;
				default: columns = 4;
			}
		};
		
		eqSlides(); $(window).load(eqSlides).resize(eqSlides);
		
		$slider
			.on('jcarousel:reload jcarousel:create', function () {
				var width = Math.floor( (($slider.innerWidth() + gutter_width) / columns) );

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
