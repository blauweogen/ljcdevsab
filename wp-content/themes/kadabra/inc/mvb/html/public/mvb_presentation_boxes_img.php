<?php

# Addition CSS styles
$addition_css_styles = '';

if (!empty($css_styles)) {
	$addition_css_styles .= $css_styles;
}
if (!empty($addition_css_styles)) {
	$addition_css_styles = 'style="' . $addition_css_styles . '"';
}

$uniq_id = uniqid('features_module_img_');

?>

<div id="<?php echo $uniq_id; ?>" class="features_module_img module <?php echo (!empty($css)) ? $css : ''; ?>"  <?php echo $addition_css_styles; ?>>

	<?php 
	$_link = '';
	?>
	
    <?php include(dirname(__FILE__).'/_title.php'); ?>

    <?php if (count($r_items) > 0): ?>
        <div class="row">
            <?php foreach ($r_items as $panel): ?>

                <?php if ($panel['effects']) {
                    $cr_effect = ' cr-animate-gen"  data-gen="' . $panel['effects'] . '" data-gen-offset="bottom-in-view';
                } else {
                    $cr_effect = '';
                } ?>

                <div class="<?php echo $column_number ?> columns feature-block-image <?php echo $cr_effect; ?>">
				<?php
                    $img = mvb_aq_resize($panel['image'], 352);
                ?>
                    <div class="picture">
						
                        <?php if (isset($panel['image']) && !empty($panel['image'])): ?>
							<div class="picture-mask">
								<img src="<?php echo $img; ?>" alt="<?php echo $panel['main_title']; ?>" />
							</div>
                        <?php endif; ?>
						
						<div class="picture-entry-hover">
						<?php
							$img_url = wp_get_attachment_url($panel['image'], 'full');
						?>
							<a class="picture-entry-thumb <?php if(!empty($panel['show_title']) && $panel[show_title] == '1' ) echo 'with-title';?>" href="<?php echo $img_url; ?>" data-rel="prettyPhoto[picture<?php echo $uniq_id; ?>]">
								<i class=""></i>
							</a>
						    <?php if(!empty($panel['show_title']) && $panel['show_title'] == '1') {?>
							<?php if (!empty($panel['main_title']) || !empty($panel['sub_title'])): ?>
							<div class="picture-entry-title">
								<div class="picture-entry-title-valign">
									<?php if (!empty($panel['main_title'])): ?>
										<div class="feature-title"><?php echo $panel['main_title']; ?></div>
									<?php endif; ?>

									<?php if (!empty($panel['sub_title'])): ?>
										<div class="subtitle"><?php echo $panel['sub_title']; ?></div>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						    </div>
		    </div>
						    <?php } else {?>
						</div>
                    </div>
					<?php if (!empty($panel['main_title'])): ?>
						<div class="feature-title"><?php echo $panel['main_title']; ?></div>
					<?php endif; ?>

					<?php if (!empty($panel['sub_title'])): ?>
						<div class="subtitle"><?php echo $panel['sub_title']; ?></div>
					<?php endif; ?>
				<?php }?>
			
                    <?php if (!empty($panel['content'])): ?>
                        <div class="content">
                            <?php echo mvb_parse_content($panel['content'], true); ?>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
	wp_enqueue_script('feature-image-box-transform');
?>