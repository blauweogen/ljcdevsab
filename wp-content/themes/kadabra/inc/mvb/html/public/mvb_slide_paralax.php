<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="'.$effects.'" data-gen-offset="bottom-in-view';
} else {
    $cr_effect ='';
}

$class = '';


# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$uniq_id = uniqid('feature_image_module');

?>
<div id="<?php echo $uniq_id; ?>" class="module feature-image-module <?php echo $class; ?> <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>
	<?php include(dirname(__FILE__).'/_title.php');?>

	<div class="dfd_slide_parallax">
		<div class="">
			<div class="image-left">
				<img src="<?php echo mvb_get_image_url($image_left); ?>" alt= "" />
			</div>
			<div class="image-right">
				<img src="<?php echo mvb_get_image_url($image_right); ?>" alt= "" />
			</div>
			<div class="handler" style="left: 50%;"><span class="pointer"></span></div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	$('#<?php echo $uniq_id; ?> > .dfd_slide_parallax').slideParallax();
});
</script>