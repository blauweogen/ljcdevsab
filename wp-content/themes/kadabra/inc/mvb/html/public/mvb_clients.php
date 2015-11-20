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

if (isset($unique_id) && !empty($unique_id)) {
	$unique_id_prefix = $unique_id;
} else {
	$unique_id_prefix = 'mvb_clients_';
}

$unique_id = uniqid($unique_id_prefix);

?>
<div class="clients_module module row <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

    <?php if (!empty($r_items)): ?>

        <div class="clients-list nine columns">
			<div class="clients-list-slider" id="<?php echo $unique_id; ?>">
				<ul>

					<?php

					foreach ($r_items as $item):

						if (isset($item['image'])) :

							$img_url = wp_get_attachment_url($item['image']);

							$article_image = aq_resize($img_url, 186, 186, true); //resize & crop img
							?>

							<li class="clients-list-item">

								<?php if (isset($item['client_url'])) : // Display chosen img ?>
									<a href="<?php echo (isset($item['client_url'])) ? $item['client_url'] : '' ?>" target="_blank">
										<img src="<?php echo $article_image ?>" alt="<?php echo (isset($item['main_title'])) ? $item['main_title'] : '' ?>"/>
									</a>
								<?php else : ?>
									<img src="<?php echo $article_image ?>" alt="<?php echo (isset($item['main_title'])) ? $item['main_title'] : ''; ?>"/>
								<?php endif; ?>
								<?php if(isset($item['main_title']) && (!empty($item['main_title']))) : ?>
									<span class="clients-tooltip"><?php echo $item['main_title']?></span>
								<?php endif; ?>

							</li>

						<?php endif;   endforeach; ?>

				</ul>
				<?php echo DFD_Carousel::controls(); ?>
			</div>
        </div>
	
		<div class="description columns three">
        <?php if (isset($main_title)) { ?>

			<?php include(dirname(__FILE__).'/_title.php');?>

			<?php echo mvb_parse_content($description) ?>

			<?php
				$view_all_show = (!empty($view_all_show)) ? $view_all_show : 0; 
				$view_all_text = (!empty($view_all_text)) ? $view_all_text : __('View all', 'dfd');
				$view_all_link_url = (!empty($link_url)) ? $link_url : '#';
			?>
			
			<?php if ($view_all_show == 1) : ?>
				<?php echo DFD_HTML::read_more($view_all_link_url, $view_all_text); ?>
			<?php endif; ?>

        <?php } else { ?>

			<div class="twelve columns">
				<?php echo mvb_parse_content($description) ?>
			</div>

        <?php } ?>
		</div>


    <?php endif; ?>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var $slider = $('#<?php echo $unique_id; ?>');
			var columns = 4;
			
			var eqSlides = function(){
				var width = $slider.width();
				
				$('> ul > li', $slider).equalHeights({container: 'ul'});
				
				switch(true) {
					case (width <= 300): columns = 1; break;
					case (width <= 600): columns = 2; break;
					case (width <= 780): columns = 3; break;
					case (width <= 960): columns = 4; break;
					default: columns = 5;
				}
			};
			
			eqSlides(); $(window).load(eqSlides).resize(eqSlides);
			
			$slider
				.on('jcarousel:reload jcarousel:create', function () {
					var width = Math.floor( $slider.innerWidth() / columns );

					$slider.jcarousel('items')
						.css('width', width + 'px');
				})
				.jcarousel({
					wrap: 'circular'
				});
				
			if (typeof($slider.touch) === 'function') {
				$slider.touch();
			}
				
			<?php if (isset($slideshow) && $slideshow): ?>
				$slider
					.jcarouselAutoscroll({
						interval: Math.round(<?php echo (isset($slideshow_speed) && !empty($slideshow_speed) && intval($slideshow_speed)>0)?$slideshow_speed:7000; ?>),
						target: '+=1',
						autostart: true
					});
			<?php endif; ?>
				
			$('.jcarousel-control-prev', $slider)
				.on('jcarouselcontrol:active', function() {
					$(this).removeClass('inactive');
				})
				.on('jcarouselcontrol:inactive', function() {
					$(this).addClass('inactive');
				})
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
	</script>
</div>