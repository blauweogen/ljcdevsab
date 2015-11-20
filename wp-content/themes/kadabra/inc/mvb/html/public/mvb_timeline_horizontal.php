<?php

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$uniq_id = uniqid('timeline_module');

?><div id="<?php echo $uniq_id; ?>" class="module timeline_module <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>

	<div class="row">
		<div id="timeline<?php echo $uniq_id; ?>" class="timeline-horizontal columns twelve">
			<ul id="dates<?php echo $uniq_id; ?>" class="timeline-titles">
				<?php foreach($items as $i => $item): ?>
					<li>
						<a href="#<?php echo $i.$uniq_id; ?>">
							<div class="tl-item">
							    <div class="lead"><?php echo $item['subtitle']; ?></div>
							    <div class="timeline-date"><?php echo $item['title']; ?></div>
							</div>
							
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div id="issues<?php echo $uniq_id; ?>" class="timeline-items">
			<?php foreach($items as $i => $item): ?>
				<div id="<?php echo $i.$uniq_id; ?>">
				
					<div class="row mvb_t_row" style="padding: 0px; margin: 0px;">
						<div class="twelve columns mobile-twelve mvb_first mvb_last">
							<div class="mvb_inner_wrapper">
									<?php echo $item['content']; ?>
							</div>
						</div>
					</div>
						
				</div>
			<?php endforeach; ?>
			</div>
			
			<?php echo DFD_Carousel::controls(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var Modernizr = window.Modernizr;
		var $issues = $('#issues<?php echo $uniq_id; ?>');
		var _startAt = parseInt('<?php echo $animation_start_at; ?>');

		var $testimonials_slide = $('#timeline<?php echo $uniq_id; ?> .testimonials-slide');

		var initTimerlinr = function() {
			$testimonials_slide.trigger('jcarousel:reload');
			$().timelinr({
				orientation: 'horizontal',

				containerDiv: '#timeline<?php echo $uniq_id; ?>',
				datesDiv: '#dates<?php echo $uniq_id; ?>',
				issuesDiv : '#issues<?php echo $uniq_id; ?>',
				prevButton: '#timeline<?php echo $uniq_id; ?> > .jcarousel-control-prev',
				nextButton: '#timeline<?php echo $uniq_id; ?> > .jcarousel-control-next',

				arrowKeys: 'false',

				datesSpeed: '<?php echo $animation_dates_speed; ?>',
				startAt: _startAt,

				afterItemActivate: function($item, settings) {
					$testimonials_slide.trigger('jcarousel:reload');

					var $parent = $item.parent();

					$parent.siblings().removeClass('selected-parent');
					$parent.addClass('selected-parent');

					var index = $parent.parent().children().index($parent);
					_startAt = index + 1;

					var $issues = $(settings.issuesDiv);
					$issues.animate({
						height: $($issues.children().get(index)).height()
					}, 200);
				},
				issuesTransparency: 0
			});
		};

		var timelinrSetWidth = function(){
			$issues.find('.mvb_t_row').andSelf().css('width', $issues.parent().innerWidth() - 20);
			initTimerlinr();
		};

		timelinrSetWidth();
		$(window).load(timelinrSetWidth);

		if (Modernizr.touch) {
			$(window).on('orientationchange', timelinrSetWidth);
		} else {
			$(window).resize(timelinrSetWidth);
		}
	});
</script>
