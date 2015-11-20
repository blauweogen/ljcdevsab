<?php if (!empty($effects)){
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

?>

<?php 
	$column_number = '';

	if (!empty($few_rows)) {
		if (count($r_items) >= 4) {
			$column_number = mvb_num_to_string(3);
		}
	}else{
		if(count($r_items) <= 6){
			$column_number = mvb_num_to_string(ceil(12 / count($r_items)));
		}else{
			$column_number = mvb_num_to_string(2);
		}
	}
?>

<div id="<?php if (!empty($unique_id)) echo $unique_id; ?>" class="clients_tiles_module module row features_module-eq-height <?php echo (!empty($css)) ? $css : ''; ?>" <?php echo $addition_css_styles; ?>>
	
	<?php include(dirname(__FILE__).'/_title.php');?>

    <?php if (!empty($r_items)): ?>
		
		<div>
	
		<?php
		$col_i = 0;
		foreach ($r_items as $item):
			$col_i++;
			
			$client_logo = '';
			$column_tag = 'div';
			$href = '';
			$column_index = '';

			if (isset($item['image'])) {
				$image = wp_get_attachment_url($item['image']);
				$img_url = aq_resize($image, 180, 130, false);
			}

			if (isset($item['client_url'])) {
					$column_tag = 'a';
				$href = 'href="'.$item['client_url'].'"';
			}
		?>
	
			<div class="<?php echo $column_number ?>  <?php echo (!empty($few_rows)) ? 'columns-with-border' : '' ?> columns <?php echo $cr_effect; ?>">
				<<?php echo $column_tag; ?> <?php echo $href; ?> class="client-tile">
					<img src="<?php echo $img_url; ?>" alt="<?php echo (isset($item['main_title'])) ? $item['main_title'] : ''; ?>" />
				 </<?php echo $column_tag; ?>>
				 <?php if(isset($item['main_title']) && (!empty($item['main_title']))) : ?>
					<span class="clients-tooltip"><?php echo $item['main_title']?></span>
				<?php endif; ?>
			</div>

		<?php endforeach; ?>
			
		</div>
			
    <?php endif; ?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.clients_tiles_module .columns-with-border:nth-child(-n+3)').addClass('no-top-border');
		jQuery('.clients_tiles_module .columns-with-border:nth-child(3n+1)').addClass('no-left-border');
	});
</script>
