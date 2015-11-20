<?php

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

?>

<div class="features_module_polygon module <?php echo $module_class; ?> <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>
	
	<?php 
		if (!empty($few_rows)) {
			if (count($r_items) >= 3) {
				$column_number = mvb_num_to_string(4);
			}
		}
		
		$_link = '';
		$icon_style = '';
	?>
	
    <div class="row" <?php if (!empty($main_title) || !empty($sub_title)) echo 'style="padding-top: 87px;"'; ?>>
        <?php if (count($r_items) > 0): ?>

            <?php foreach ($r_items as $panel): ?>

                <?php if ($panel['effects']) {
                    $cr_effect = ' cr-animate-gen"  data-gen="' . $panel['effects'] . '" data-gen-offset="bottom-in-view';
                } else {
                    $cr_effect = '';
                } ?>

                <?php
				
				$icon_color = $icon_background = $icon = '';

                if ($panel['link_url'] != '') {

                    $_link = $panel['link_url'];

                } elseif (is_numeric($panel['page_id']) AND $panel['page_id'] > 0) {

                    $_link = get_page_link($panel['page_id']);

                }

                if (isset($panel['icon']) && $panel['icon'] != '') {
					if (isset($panel['icon_color']) && $panel['icon_color'] != '') {
						$icon_color = 'color: #'.$panel['icon_color'].';';
					}
					
					if (isset($panel['color']) && $panel['color'] != '') {
						$icon_background = 'background-color: #'.$panel['color'];
					}
					
                    $icon = '<i class = "' . $panel['icon'] . '" style="'.$icon_color.'"> </i>';
                }
				
				$wrapper_class = $column_number .' columns'. $cr_effect;
				
				if (!empty($box_hover_with_class)) {
					$wrapper_class .= ' '. $box_hover_with_class;
				}
				
				$icon_html = '<div class="icon '.$icon_class.'" style="'. $icon_style .'">';
				$icon_html .= '<div style="'.$icon_background.'">'.$icon.'</div>';
				$icon_html .= '</div>';
				
				?>
                <div class="<?php echo $wrapper_class; ?>">

                    <div class="feature-box">
						
						<?php if ($icon_over_title): ?>
							<?php echo $icon_html; ?>
						<?php endif; ?>

						<div class="block-title">
							<?php  if ($_link) {
								echo '<a href="' . $_link . '">';
							}
							echo $panel['main_title'];

							if ($_link) {
								echo '</a>';
							} ?>
						</div>

						<div class="subtitle"><?php echo $panel['sub_title'] ?></div>

						<?php if (!$icon_over_title): ?>
							<?php echo $icon_html; ?>
						<?php endif; ?>

						<?php if ($panel['content'] != ''): ?>
							<div class="feat-block-content">
								<?php echo mvb_parse_content($panel['content'], TRUE); ?>
							</div>
						<?php endif; ?>

						<?php if ($panel['read_more'] && $_link) { ?>
							<?php echo DFD_HTML::read_more($_link, $panel['read_more_text']); ?>
						<?php } ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
