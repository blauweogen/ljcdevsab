<?php $twitter_uniqid = uniqid('twitter_row'); ?>
<div id="<?php echo $twitter_uniqid; ?>" class="twitter-row">
	<div class="twitter-row-icon-container">
		<i class="soc_icon-twitter-3"></i>
	</div>

	<?php
	// Get the tweets from Twitter.
	require_once locate_template('/inc/lib/twitteroauth.php');
	$twitter = new DFDTwitter();
	$tweets = $twitter->getTweets();

	?>
	<?php if (!$twitter->hasError()): ?>
	<div class="twitter-slider">
		<ul>
			<?php if (!empty($tweets)): ?>
				<?php foreach ($tweets as $t) : ?>
					<li>
						<?php echo $t['text']; ?>
						<div class="date">
							<?php echo human_time_diff($t['time'], current_time('timestamp')); ?>
							ago
						</div>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php echo DFD_Carousel::controls(); ?>
	</div>
	<?php else: ?>
		<p class="text-bold text-center">
			<?php echo ($twitter->getError()->message); ?>
		</p>
	<?php endif; ?>
</div>

<?php if (!$twitter->hasError() && !empty($tweets)): ?>
<script type="text/javascript">
jQuery(document).ready(function ($) {

	$('#<?php echo $twitter_uniqid; ?> .twitter-slider').each(function(){ 	
		var $twitter_slider = $(this);

		$twitter_slider
			.on('jcarousel:reload jcarousel:create', function () {
				var width = $twitter_slider.innerWidth();

				$twitter_slider.jcarousel('items')
					.css('width', width + 'px');
			})
			.jcarousel({
				wrap: 'circular'
			})
			.jcarouselAutoscroll({
				interval: 5000,
				target: '+=1',
				autostart: true
			});
			
		if (typeof($twitter_slider.touch) === 'function') {
			$twitter_slider.touch();
		}

		$('.jcarousel-control-prev', $twitter_slider)
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				carousel: $twitter_slider,
				target: '-=1'
			});
		$('.jcarousel-control-next', $twitter_slider)
			.jcarouselControl({
				carousel: $twitter_slider,
				target: '+=1'
			});
	});
});
</script>
<?php endif; ?>