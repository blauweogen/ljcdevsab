<?php if (!empty($effects)) {
    $cr_effect = ' cr-animate-gen"  data-gen="'.$effects.'" data-gen-offset="bottom-in-view';
} else {
    $cr_effect ='';
}

$uniq_id = uniqid('mvb_features_module_img_');

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
$icon_style = $_link = $icon_background = '';
?>
<div id="<?php echo $uniq_id; ?>" class="features_module module <?php echo $module_class; ?> <?php echo (!empty($css)) ? $css : ''; ?> <?php echo $cr_effect; ?>"  <?php echo $addition_css_styles; ?>>

    <?php include(dirname(__FILE__).'/_title.php'); ?>
	
	<?php 
		if ($few_rows) {
			if (count($r_items) >= 3) {
				$column_number = mvb_num_to_string(4);
			}
		}
	?>
	
    <div class="row">
        <?php if (count($r_items) > 0): ?>

            <?php foreach ($r_items as $panel): ?>

                <?php if (!empty($panel['effects'])) {
                    $cr_effect = ' cr-animate-gen animated"  data-gen="' . $panel['effects'] . '" data-gen-offset="bottom-in-view';
                } else {
                    $cr_effect = '';
                } ?>

                <?php
				if (!empty($panel['alignment'])) {
					$text_align = 'al-left';
					
					$icon_style = '';
					$icon_background = '';
					if (isset($panel['color']) && !empty($panel['color'])) {
						$icon_background .= "background-color: #{$panel['color']};";
					}
					
					if (isset($panel['icon_shadow_color']) && !empty($panel['icon_shadow_color'])) {
						$icon_style .= "-webkit-box-shadow: 7px 7px 0px 0px #{$panel['icon_shadow_color']};";
						$icon_style .= "box-shadow: 7px 7px 0px 0px #{$panel['icon_shadow_color']};";
					}
					
					if ($few_rows) {
						$text_align = 'al-left-few-rows';
					}
					
				} else {
					$text_align = 'al-top';
				}
				
                if (!empty($panel['image'])) {

                    $img = wp_get_attachment_url($panel['image']);

                    $tile_image = aq_resize($img, 180, 180, false);
                }

                if (!empty($panel['link_url'])) {

                    $_link = $panel['link_url'];

                } elseif (!empty($panel['page_id']) && is_numeric($panel['page_id']) && $panel['page_id'] > 0) {
					$_page_ = get_page($panel['page_id']);
					if (isset($_page_->ID)) {
						$_link = get_page_link($panel['page_id']);
					}
                }

                if (!empty($panel['icon'])) {
					if (!empty($panel['icon_color'])) {
						$icon_color = 'color: #'.$panel['icon_color'].';';
					} else {
						$icon_color = '';
					}
                    $icon = '<i class = "' . $panel['icon'] . '" style="'.$icon_color.'"> </i>';
                } else {
                    $icon = '';
                }
				
				if (!empty($tile_image)) {
					$icon = '<img src="'.$tile_image.'" alt="" />';
					$icon_class .= ' icon-large-image';
				}
				
				$wrapper_class = $column_number .' columns';
				
				if (!empty($few_rows)) {
					$wrapper_class .= ' columns-with-border';
				}
				
				if (!empty($box_hover_with_class)) {
					$wrapper_class .= ' '. $box_hover_with_class;
				}
				
				$icon_html = '<div class="icon '.$icon_class.'" style="'. $icon_style . ((!$_link)?' '.$icon_background:'') .'">';
				if ($_link) {
					$icon_html .= '<a class="link" href="' . $_link . '" style="'.$icon_background.'">'.$icon.'</a>';
				} else {
					$icon_html .= $icon;
				}
				$icon_html .= '</div>';
				
				?>
                <div class="<?php echo $wrapper_class; ?>">

                    <div class="feature-box <?php echo $text_align . $cr_effect; ?>">
						
						<?php if (!empty($icon_over_title)): ?>
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

                        <div class="subtitle <?php if(!$icon_over_title){ echo 'under-subtitle'; }?>"><?php echo $panel['sub_title'] ?></div>
						
						<?php if (!$icon_over_title): ?>
							<?php echo $icon_html; ?>
						<?php endif; ?>

						<div class="feat-block-content">
							<?php if (!empty($panel['content'])): ?>
							<?php echo mvb_parse_content($panel['content'], TRUE); ?>
							<?php endif; ?>

							<?php if (!empty($panel['read_more']) && $_link) { ?>
								<?php echo DFD_HTML::read_more($_link, $panel['read_more_text']); ?>
							<?php } ?>
						</div>

                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
