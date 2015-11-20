<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="'.$effects.'" data-gen-offset="bottom-in-view';
} else {
    $cr_effect ='';
} ?>

<?php 
$diagram_type = (!empty($diagram_type)) ? $diagram_type : '';
$description = (!empty($description)) ? $description : '';

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>

<div id="<?php echo (!empty($unique_id)) ? $unique_id : '' ?>" class="module module-facts <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php');?>
		
	<?php if (!empty($r_items)): ?>
	<div class="row facts-items <?php if($diagram_type=='circle') {echo 'facts-items-circle';} ?>">
		<?php
		foreach ($r_items as $item):
		?>
		
		<div class="columns" style="<?php if (!empty($column_width)) echo 'width: '.$column_width.'%;'; ?>">
			<?php if (isset($item['icon']) && !empty($item['icon'])) : ?>
				<div class="icon-wrap">
					    <i class="<?php echo $item['icon'] ?>"></i>
				</div>
			<?php endif; ?>
			<?php 
			switch ($diagram_type) {
				case 'none':
				default:
					?>
					<div class="fact-num">
						<span class="val <?php if(isset($animate_facts)&&$animate_facts): ?>call-on-waypoint<?php endif; ?>" data-end="<?php echo $item['number']; ?>" data-start="0" data-speed="1000">
							<?php echo $item['number']; ?>
						</span>
						<!--<span class="line"></span>-->
					</div>
					<?php
					break;
				case 'circle':
					$unique_num = uniqid('num-');
					?>
					<div class="fact-num circle <?php if(isset($animate_facts)&&$animate_facts): ?>call-on-waypoint<?php endif; ?>" data-end="<?php echo $item['number']; ?>" data-start="0" data-speed="1500" data-knob="#<?php echo $unique_num; ?>">
						<span class="number"><?php echo $item['number']; ?></span>
						<input id="<?php echo $unique_num; ?>" type="text" value="<?php echo $item['number']; ?>">
						<!--<span class="line"></span>-->
					</div>
					
					<script type="text/javascript">
						jQuery(function() {
							jQuery("#<?php echo $unique_num; ?>").knob({
								width: 180,
								height: 180,
								fgColor: '#6cccf4',
								bgColor: '#f4f4f4',
								readOnly: true,
								lineWidth: 5,
								displayInput: false,
							});
						});
					</script>
					<?php
					break;
			};
			?>
			<div class="fact-title"><?php echo (isset($item['title'])) ? $item['title'] : ''; ?></div>
			<div class="fact-subtitle"><?php echo (isset($item['subtitle'])) ? $item['subtitle'] : ''; ?></div>
		</div>
		
		<?php
		endforeach;
		?>
	</div>
	<?php endif; ?>

    <div class="content">
		<?php echo mvb_parse_content($description, true) ?>
	</div>

</div>
