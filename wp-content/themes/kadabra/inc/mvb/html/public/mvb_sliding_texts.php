<?php

if (!empty($effects)) {
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

$unique_id = (!empty($unique_id)) ? $unique_id : uniqid('mvbst_');

?>

<div class="module module-sliding-text <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>" <?php echo $addition_css_styles; ?>>

	<?php include(dirname(__FILE__) . '/_title.php'); ?>

	<div class="text-list-slider" id="<?php echo $unique_id; ?>">
		<ul class="slides">
			<?php if (!empty($r_items)) : ?>
				<?php foreach ($r_items as $item) : ?>
					<?php if (!empty($item['content'])) : ?>
						<li class="text-list-item">
							<div class="text-list-item-wrap"><?php echo mvb_parse_content($item['content'], true) ?></div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php echo DFD_Carousel::controls(); ?>
	</div>
</div>

<script type="text/javascript">
(function($){
	var carouselHeight = function($o) {
		$('ul > li', $o).equalHeights({container: 'ul', after: function(h) {
			$o.find('.bx-viewport').css({height: h, 'min-height': h});
			$o.find('ul').css({height: 'auto', 'min-height': 'auto', top: 0});
		}});
	};
	
	$(document).ready(function() {
		var $slider_container = $('#<?php echo $unique_id; ?>');
		var $slider = $('> ul', $slider_container);
		var slider_obj;
		
		slider_obj = $slider.bxSlider({
			mode: '<?php echo (!empty($slideshow_effect)) ? $slideshow_effect : 'horizontal' ?>',
			controls: false,
			pager: false,
			auto: <?php echo (!empty($slideshow)) ? 'true' : 'false'; ?>,
			pause: <?php echo (!empty($slideshow_speed) && intval($slideshow_speed)) ? $slideshow_speed : 7000; ?>,
			responsive: true,
			adaptiveHeight: false,
			useCSS: false,
			onSliderLoad: function() {
				$('.jcarousel-control-prev', $slider_container).live('click', function() {
					slider_obj.goToPrevSlide();
					return false;
				});
				$('.jcarousel-control-next', $slider_container).live('click', function() {
					slider_obj.goToNextSlide();
					return false;
				});
			}
		});
		
		$slider_container.hover(function() {
			slider_obj.stopAuto();
		}, function() {
			slider_obj.startAuto();
		});
		
		$(window).on('load', function() {
			carouselHeight($slider_container);
		});
		
		$(window).on('resize', function() {
			slider_obj.reloadSlider();
			carouselHeight($slider_container);
		});
	});
})(jQuery);
</script>
