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

$show_jcarousel = (!empty($r_items) && count($r_items) > 1);

if (!isset($unique_id) || empty($unique_id)) {
	$unique_id = 'testimonials_module';
}
$unique_id = uniqid($unique_id);
?>
<div class="testimonials_module <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

<?php include(dirname(__FILE__) . '/_title.php'); ?>


<div id="<?php echo $unique_id ?>">

	<div class="avatar"></div>
	<div class="<?php if ($show_jcarousel): ?>testimonials-slide<?php endif; ?>">
		<?php if (!empty($r_items)): ?>
		<ul>
			<?php
			foreach ($r_items as $item):
				if (isset($item['link_url']) && $item['link_url'] != '') {
					$_link = $item['link_url'];
				} elseif (isset($item['page_id']) && is_numeric($item['page_id']) && $item['page_id'] > 0) {
					$_link = get_page_link($item['page_id']);
				} else {
					$_link = null;
				}
			?>
				<li class="testimonials_item">
					<div>
						<div class="cite">
							<?php if (isset($item['main_title']) && $item['main_title']): ?>
								<div class="block-title"><?php echo $item['main_title']; ?></div>
							<?php endif; ?>

							<?php if (isset($item['client_job']) && $item['client_job']): ?>
								<div class="subtitle"><?php echo $item['client_job']; ?></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="clear"></div>
					<div>
						<blockquote>
							<?php echo mvb_parse_content($item['content']) ?>
						</blockquote>
						<?php if (!empty($_link)): ?>
							<p class="text-center">
								<?php echo DFD_HTML::read_more($_link); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>

			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		
		<?php if ($show_jcarousel): ?>
			<?php echo DFD_Carousel::controls(); ?>
		<?php endif; ?>
	</div>
	
	<?php if ($show_jcarousel): ?>
		<div class="testimonials-pagination"></div>
	<?php endif; ?>
</div>
	
<?php if ($show_jcarousel): ?>
	<script type="text/javascript">
		(function($) {
			var $container = $('#<?php echo $unique_id ?>');
			var $jcarousel = $('.testimonials-slide', $container);
			var $jpagination = $('.testimonials-pagination', $container);

			var bindNav = function() {
				if ($jpagination.find('.jcarousel-control', $container).length > 0)
					return;

				$('.jcarousel-control-prev', $container)
						.jcarouselControl({
							carousel: $jcarousel,
							target: '-=1'
						});

				$('.jcarousel-control-next', $container)
						.jcarouselControl({
							carousel: $jcarousel,
							target: '+=1'
						});
			};

			$(document).ready(function() {
				$jcarousel
						.on('jcarousel:reload jcarousel:create', function() {
							var width = $jcarousel.innerWidth();
							$jcarousel.jcarousel('items').css('width', width + 'px');
							bindNav();
						})
						.jcarousel({
							wrap: 'circular'
						});
						
				if (typeof($jcarousel.touch) === 'function') {
					$jcarousel.touch();
				}

				$jpagination
						.on('jcarouselpagination:createend jcarouselpagination:reloadend', bindNav)
						.on('jcarouselpagination:active', 'a', function() {
							$(this).addClass('active');
						})
						.on('jcarouselpagination:inactive', 'a', function() {
							$(this).removeClass('active');
						})
						.on('click', function(e) {
							e.preventDefault();
						})
						.jcarouselPagination({
							carousel: $jcarousel,
							perPage: 1,
							item: function(page) {
								return '<a href="#' + page + '" class="testimonials-nav-item">' + page + '</a>';
							}
						});
			});
		})(jQuery);
	</script>
<?php endif; ?>

</div>
