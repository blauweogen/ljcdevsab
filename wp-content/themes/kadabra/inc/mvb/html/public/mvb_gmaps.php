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

# Load Map Style
if (isset($map_style)) {
	$styleVal = MVB_Gmaps::getStyleVal($map_style);
} else {
	$styleVal = false;
}

$unique_id = uniqid((empty($unique_id))?'gmap_module':$unique_id);
$columns = (!empty($columns)) ? $columns : '';

if (!empty($infowindow_content)) {
	$infowindow_content = trim($infowindow_content);
} else {
	$infowindow_content = '';
}

?>

<div class="gmap_module <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

	<?php include(dirname(__FILE__) . '/_title.php'); ?>

	<?php if (!empty($content)) {
		$columns = 'six';
	} else {
		$columns = 'twelve';
	} ?>


	<div class="row map-widget ">

		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>

		<div class="<?php echo $columns; ?> columns">

			<div id="<?php echo $unique_id; ?>" class="gmap-container" style="height: <?php echo (!empty($height)) ? $height : 0; ?>px;"></div>


		</div>

		<?php if (!empty($content)) { ?>

			<div class="<?php echo $columns; ?> columns">
				<?php echo mvb_parse_content($content); ?>
			</div>

		<?php } ?>

	</div>
</div>

<script type="text/javascript">
(function($){
	var gmap3_init = function() {
		$("#<?php echo $unique_id; ?>").gmap3('destroy');

		$("#<?php echo $unique_id; ?>").gmap3({
			marker: {
				values:[
					{
						address: "<?php echo $address; ?>",
						options: {icon: "<?php echo get_template_directory_uri().'/assets/img/gmap-marker.png' ?>"}
					}
				]<?php if (!empty($infowindow_content)): ?>,
				callback: function (marker) {
						$(this).gmap3({
							infowindow: {
								anchor: marker[0],
								options: {content: '<div class="infoblockcontent"><?php echo $infowindow_content; ?></div>'}
							}
						});

				}<?php endif; ?>
			},
			map: {
				options: {
					zoom: <?php echo (!empty($zoom)) ? $zoom : '14'; ?>,
					navigationControl: true,
					scrollwheel: false,
					streetViewControl: true,
					<?php if ($styleVal): ?>
					styles: <?php echo $styleVal; ?>
					<?php endif; ?>
				}
			}

		});
	};
	
<?php if (!empty($address)): ?>
	$(document).ready(function($) {

		$("#<?php echo $unique_id; ?>").bind('gmap-reload', function() {
			gmap3_init();
		});

		gmap3_init();
	});
<?php endif; ?>
})(jQuery);
</script>